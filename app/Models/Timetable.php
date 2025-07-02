<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Timetable extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'day',
        'slot_number',
        'start_time',
        'end_time',
        'venue',
        'module_name',
        'user_id',
        'class_code_id',
    ];

    /**
     * The user (lecturer) assigned to this timetable.
     */
    public function lecturer()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * The class code (e.g., BSSM3100) associated with this timetable.
     */
    public function classCode()
    {
        return $this->belongsTo(ClassCode::class, 'class_code_id');
    }

    /**
     * Check if a timetable entry conflicts with others.
     */
    public static function detectConflicts($day, $start_time, $end_time, $venue = null, $class_code = null, $user_id = null, $exclude_id = null)
    {
        return self::where('day', $day)
            ->where(function ($query) use ($start_time, $end_time, $venue, $class_code, $user_id) {
                if ($venue) {
                    $query->orWhere(function ($q) use ($venue, $start_time, $end_time) {
                        $q->where('venue', $venue)
                          ->where(function ($q) use ($start_time, $end_time) {
                              $q->whereBetween('start_time', [$start_time, $end_time])
                                ->orWhereBetween('end_time', [$start_time, $end_time])
                                ->orWhere(function ($q) use ($start_time, $end_time) {
                                    $q->where('start_time', '<', $start_time)
                                      ->where('end_time', '>', $end_time);
                                });
                          });
                    });
                }

                if ($class_code) {
                    $query->orWhere(function ($q) use ($class_code, $start_time, $end_time) {
                        $q->where('class_code_id', $class_code)
                          ->where(function ($q) use ($start_time, $end_time) {
                              $q->whereBetween('start_time', [$start_time, $end_time])
                                ->orWhereBetween('end_time', [$start_time, $end_time])
                                ->orWhere(function ($q) use ($start_time, $end_time) {
                                    $q->where('start_time', '<', $start_time)
                                      ->where('end_time', '>', $end_time);
                                });
                          });
                    });
                }

                if ($user_id) {
                    $query->orWhere(function ($q) use ($user_id, $start_time, $end_time) {
                        $q->where('user_id', $user_id)
                          ->where(function ($q) use ($start_time, $end_time) {
                              $q->whereBetween('start_time', [$start_time, $end_time])
                                ->orWhereBetween('end_time', [$start_time, $end_time])
                                ->orWhere(function ($q) use ($start_time, $end_time) {
                                    $q->where('start_time', '<', $start_time)
                                      ->where('end_time', '>', $end_time);
                                });
                          });
                    });
                }
            })
            ->when($exclude_id, fn ($q) => $q->where('id', '!=', $exclude_id))
            ->exists();
    }

    /**
     * Check if the user has full access to view/manage all timetables.
     */
    public static function userHasFullTimetableAccess($user): bool
    {
        return in_array($user->role, ['is_admin', 'is_facultyAdmin']);
    }
}
