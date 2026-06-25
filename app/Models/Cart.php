<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Cart extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
    ];

    // --- Métodos estáticos ---

    public static function getOrCreate(User $user): self
    {
        return self::firstOrCreate(['user_id' => $user->id]);
    }

    // --- Reglas de negocio ---

    /**
     * El pago en efectivo requiere coordinar un punto de encuentro, por lo que solo
     * está disponible si el comprador y todos los vendedores del carrito son de la
     * misma provincia y ciudad.
     */
    public function efectivoDisponiblePara(User $buyer): bool
    {
        $this->loadMissing('items.product.seller');

        if ($this->items->isEmpty()) {
            return false;
        }

        return $this->items->every(function (CartItem $item) use ($buyer) {
            return $buyer->livesInSameLocationAs($item->product?->seller);
        });
    }

    // --- Relaciones ---

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function items(): HasMany
    {
        return $this->hasMany(CartItem::class);
    }
}
