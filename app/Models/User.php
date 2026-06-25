<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Str;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'apellido', // Agregado para MSGRUP-27
        'email',
        'password',
        'role',
        'is_active',
        'info_entrega',
        'provincia',
        'ciudad',
    ];

    public const PROVINCIAS = [
        'Buenos Aires',
        'Ciudad Autónoma de Buenos Aires',
        'Catamarca',
        'Chaco',
        'Chubut',
        'Córdoba',
        'Corrientes',
        'Entre Ríos',
        'Formosa',
        'Jujuy',
        'La Pampa',
        'La Rioja',
        'Mendoza',
        'Misiones',
        'Neuquén',
        'Río Negro',
        'Salta',
        'San Juan',
        'San Luis',
        'Santa Cruz',
        'Santa Fe',
        'Santiago del Estero',
        'Tierra del Fuego',
        'Tucumán',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'role' => 'string',
        ];
    }

    // --- Helpers de rol ---

    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    public function isUsuario(): bool
    {
        return $this->role === 'usuario';
    }

    // --- Helpers de ubicación ---

    /**
     * Determina si este usuario y otro están en la misma provincia y ciudad,
     * usado para habilitar el pago en efectivo (requiere coordinar un punto de encuentro).
     */
    public function livesInSameLocationAs(?User $other): bool
    {
        if (! $other || blank($this->provincia) || blank($this->ciudad) || blank($other->provincia) || blank($other->ciudad)) {
            return false;
        }

        return self::normalizeLocation($this->provincia) === self::normalizeLocation($other->provincia)
            && self::normalizeLocation($this->ciudad) === self::normalizeLocation($other->ciudad);
    }

    private static function normalizeLocation(string $value): string
    {
        return Str::of($value)->trim()->lower()->ascii()->value();
    }

    // --- Relaciones ---

    public function cart(): HasOne
    {
        return $this->hasOne(Cart::class);
    }

    public function orders(): HasMany
    {
        return $this->hasMany(Order::class);
    }

    public function favorites(): HasMany
    {
        return $this->hasMany(Favorite::class);
    }

    public function products(): HasMany
    {
        return $this->hasMany(Product::class);
    }
}
