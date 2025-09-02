<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function contribuyentes()
    {
        return $this->hasMany(Contribuyente::class, 'created_by');
    }

    public function facturasVentas()
    {
        return $this->hasMany(FacturaVenta::class, 'created_by');
    }

    public function libroVentasPeriodos()
    {
        return $this->hasMany(LibroVentasPeriodo::class, 'created_by');
    }

    public function regimens()
    {
        return $this->hasMany(Regimen::class, 'created_by');
    }
}
