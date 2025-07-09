<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Achievement extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'image_url',
        'type',
        'criteria',
        'requirement',
        
    ];

    // Memberitahu Laravel bahwa kolom 'criteria' adalah JSON
    protected $casts = [
        'criteria' => 'array',
    ];
}
