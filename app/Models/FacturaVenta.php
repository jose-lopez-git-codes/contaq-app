<?php

namespace App\Models;

use App\Traits\TenantScope;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class FacturaVenta extends Model
{
    use TenantScope;
    protected $table = 'facturas_ventas';

    protected $fillable = [
        'libro_ventas_periodo_id',
        'contribuyente_id',
        'fecha',
        'establecimiento',
        'tipo',
        'estado',
        'serie',
        'numero',
        'nit_cliente',
        'nombre_cliente',
        'base_gravada_bienes',
        'base_gravada_servicios',
        'base_exenta_bienes',
        'base_exenta_servicios',
        'iva_debito_fiscal',
        'total_documento',
        'retencion',
        'exencion',
        'created_by',
        'updated_by'
    ];

    protected $casts = [
        'fecha' => 'date',
        'base_gravada_bienes' => 'decimal:2',
        'base_gravada_servicios' => 'decimal:2',
        'base_exenta_bienes' => 'decimal:2',
        'base_exenta_servicios' => 'decimal:2',
        'iva_debito_fiscal' => 'decimal:2',
        'total_documento' => 'decimal:2',
        'retencion' => 'decimal:2',
        'exencion' => 'decimal:2',
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

    public function libroPeriodo()
    {
        return $this->belongsTo(LibroVentasPeriodo::class, 'libro_ventas_periodo_id');
    }

    public function contribuyente()
    {
        return $this->belongsTo(Contribuyente::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    // Helper para el número completo de factura
    public function getNumeroCompletoAttribute()
    {
        return $this->serie . '-' . $this->numero;
    }

    // Helper para obtener el total de bases gravadas
    public function getTotalBasesGravadasAttribute()
    {
        return ($this->base_gravada_bienes ?? 0) + ($this->base_gravada_servicios ?? 0);
    }

    // Helper para obtener el total de bases exentas
    public function getTotalBasesExentasAttribute()
    {
        return ($this->base_exenta_bienes ?? 0) + ($this->base_exenta_servicios ?? 0);
    }
}
