<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'full_name',
        'email',
        'student_id',
        'class_code',
        'account_id',
        'role',
        'password',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    // ✅ Role accessors (used like $user->is_admin)
    public function getIsAdminAttribute(): bool
    {
        return $this->role === 'is_admin';
    }

    public function getIsFacultyAdminAttribute(): bool
    {
        return $this->role === 'is_facultyAdmin';
    }

    public function getIsLecturerAttribute(): bool
    {
        return $this->role === 'Lecturer';
    }

    public function getIsStudentAttribute(): bool
    {
        return $this->role === 'Student';
    }

    // ✅ Timetables owned by this user (if a lecturer)
    public function timetables()
    {
        return $this->hasMany(Timetable::class, 'user_id');
    }

    // ✅ Unified role checker (safe & flexible)
    public function hasRole(string $role): bool
    {
        return $this->role === $role;
    }

    // ✅ Role scopes for cleaner queries (User::facultyAdmins()->get())
    public function scopeAdmins($query)
    {
        return $query->where('role', 'is_admin');
    }

    public function scopeFacultyAdmins($query)
    {
        return $query->where('role', 'is_facultyAdmin');
    }

    public function scopeLecturers($query)
    {
        return $query->where('role', 'Lecturer');
    }

    public function scopeStudents($query)
    {
        return $query->where('role', 'Student');
    }
}
