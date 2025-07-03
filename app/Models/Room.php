//NOT IN USE - DELETE LATER... or use it later

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Room extends Model
{
    protected $fillable = [
        'name',
        'type',
        'capacity',
        'is_available',
        'description',
    ];
}
