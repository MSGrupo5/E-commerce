<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\belongsTo;
use Illuminate\Support\Str;

class Category extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
    ];

    protected static function booted(): void
    {
        static::creating(function (Category $category) {
            $slug = Str::slug($category->name);
            $original = $slug;
            $counter = 2;

            while (Category::where('slug', $slug)->exists()) {
                $slug = $original . '-' . $counter;
                $counter++;
            }

            $category->slug = $slug;
        });
    }

    // --- Relaciones ---

    public function products(): HasMany
    {
        return $this->hasMany(Product::class);
    }
}
