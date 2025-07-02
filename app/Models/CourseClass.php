<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class CourseClass extends Model
{
    use HasFactory;

    protected $table = 'classes';

    protected $fillable = [
        'class_code',
        'name',
        'description',
        'capacity'
    ];

    public function schedules()
    {
        return $this->hasMany(Schedule::class, 'class_id');
    }
}
