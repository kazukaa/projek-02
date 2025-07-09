<?php

namespace App\Models;

use Illuminate\Support\Str; 
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;
// Sebaiknya ditambahkan

/**
 * Model untuk merepresentasikan sebuah kategori latihan.
 */
class Category extends Model
{
    use HasFactory; // Trait ini berguna untuk testing dengan data dummy

    /**
     * Atribut yang dapat diisi secara massal.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'description',
        'slug',
        // Tambahkan nama kolom lain yang relevan di sini
    ];

    /**
     * Mendefinisikan relasi "one-to-many" ke model Exercise.
     * Satu Category memiliki banyak Exercises.
     */
    public function exercises(): HasMany
    {
        return $this->hasMany(Exercise::class);
    }

    protected static function booted(): void
    {
        static::creating(function ($category) {
            $category->slug = Str::slug($category->name);
        });
    }
}