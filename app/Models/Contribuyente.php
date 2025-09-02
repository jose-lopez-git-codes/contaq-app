<?php

namespace App\Models;

use App\Traits\TenantScope;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class Contribuyente extends Model
{
    use TenantScope;
    protected $fillable = [
        'nit',
        'nombre',
        'nombre_establecimiento',
        'regimen_id',
        'created_by',
        'updated_by'
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            $model->created_by = Auth::id();
            $model->updated_by = Auth::id();
        });

        static::updating(function ($model) {
            $model->updated_by = Auth::id();
        });
    }

    public function regimen()
    {
        return $this->belongsTo(Regimen::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}
