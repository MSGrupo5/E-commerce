<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'category_id',
        'name',
        'description',
        'price',
        'stock',
        'image',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'price'     => 'float',
            'stock'     => 'integer',
            'is_active' => 'boolean',
        ];
    }

    protected function imageUrl(): Attribute
    {
        return Attribute::get(function () {
            if (! $this->image) {
                return null;
            }

            if (str_starts_with($this->image, 'http')) {
                return $this->image;
            }

            return asset('storage/'.$this->image);
        });
    }

    // --- Helpers ---

    public function inStock(): bool
    {
        return $this->stock > 0;
    }

    // --- Scopes ---

    public function scopeSearch(Builder $query, string $term): void
    {
        $query->where('name', 'LIKE', "%{$term}%");
    }

    // --- Relaciones ---

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function seller(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function cartItems(): HasMany
    {
        return $this->hasMany(CartItem::class);
    }

    public function orderItems(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }

    public function favorites(): HasMany
    {
        return $this->hasMany(Favorite::class);
    }
}
