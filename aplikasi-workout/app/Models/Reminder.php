<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Reminder extends Model
{
    protected $casts = [
    'days_of_week' => 'array',
];

}
