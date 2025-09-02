<?php

namespace App\Models;

use App\Traits\TenantScope;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class LibroVentasPeriodo extends Model
{
    use TenantScope;
    protected $table = 'libro_ventas_periodos';

    protected $fillable = [
        'contribuyente_id',
        'año',
        'mes',
        'estado',
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

    public function contribuyente()
    {
        return $this->belongsTo(Contribuyente::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function facturas()
    {
        return $this->hasMany(FacturaVenta::class, 'libro_ventas_periodo_id');
    }

    // Helper para obtener el nombre del mes
    public function getNombreMesAttribute()
    {
        $meses = [
            1 => 'Enero', 2 => 'Febrero', 3 => 'Marzo', 4 => 'Abril',
            5 => 'Mayo', 6 => 'Junio', 7 => 'Julio', 8 => 'Agosto',
            9 => 'Septiembre', 10 => 'Octubre', 11 => 'Noviembre', 12 => 'Diciembre'
        ];

        return $meses[$this->mes] ?? '';
    }

    // Helper para obtener años disponibles de un contribuyente
    public static function getAñosDisponibles($contribuyenteId)
    {
        return self::where('contribuyente_id', $contribuyenteId)
                   ->distinct()
                   ->orderBy('año', 'desc')
                   ->pluck('año');
    }

    // Helper para obtener meses de un año específico
    public static function getMesesDelAño($contribuyenteId, $año)
    {
        return self::where('contribuyente_id', $contribuyenteId)
                   ->where('año', $año)
                   ->orderBy('mes')
                   ->get();
    }
}
