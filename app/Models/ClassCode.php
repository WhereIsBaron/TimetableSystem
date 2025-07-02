<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class ClassCode extends Model
{
    protected $fillable = [
        'code',
        'description',
    ];

    /**
     * Many-to-Many: ClassCode <-> Timetable
     */
    public function timetables(): BelongsToMany
    {
        return $this->belongsToMany(Timetable::class, 'class_code_timetable', 'class_code_id', 'timetable_id');
    }

    /**
     * Many-to-Many relationship with User model.
     * This represents the faculty admins associated with this class code.
     */
    public function facultyAdmins(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'class_code_user', 'class_code_id', 'user_id')
                    ->withTimestamps()
                    ->wherePivot('is_faculty_admin', true);
    }

    /**
     * Many-to-Many relationship with User model.
     * This represents all users enrolled in this class code.
     */
    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'class_code_user', 'class_code_id', 'user_id')
                    ->withTimestamps();
    }
}
