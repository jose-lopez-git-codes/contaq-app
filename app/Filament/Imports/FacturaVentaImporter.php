<?php

namespace App\Filament\Imports;

use App\Models\FacturaVenta;
use App\Models\LibroVentasPeriodo;
use Filament\Actions\Imports\ImportColumn;
use Filament\Actions\Imports\Importer;
use Filament\Actions\Imports\Models\Import;
use Illuminate\Support\Facades\Log;

class FacturaVentaImporter extends Importer
{
    protected static ?string $model = FacturaVenta::class;

    public static function getColumns(): array
    {
        return [
            ImportColumn::make('fecha')
                ->requiredMapping()
                ->guess(['Fecha', 'Fecha de emisión', 'Date', 'Fecha de emisiÃ³n'])
                ->rules(['required', 'date']),

            ImportColumn::make('establecimiento')
                ->requiredMapping()
                ->guess(['Establecimiento', 'Código de establecimiento', 'CÃ³digo de establecimiento'])
                ->rules(['required', 'string']),

            ImportColumn::make('tipo')
                ->guess(['Tipo', 'Tipo de DTE (nombre)', 'Type', 'tipo'])
                ->rules(['string']),

            ImportColumn::make('estado')
                ->guess(['Estado', 'Status', 'estado'])
                ->rules(['string']),

            ImportColumn::make('serie')
                ->requiredMapping()
                ->guess(['Serie', 'Series', 'serie'])
                ->rules(['required', 'string']),

            ImportColumn::make('numero')
                ->requiredMapping()
                ->guess(['Número', 'Numero', 'Número del DTE', 'Number', 'NÃºmero del DTE'])
                ->rules(['required', 'string']),

            ImportColumn::make('nit_cliente')
                ->requiredMapping()
                ->guess(['NIT Cliente', 'ID del receptor', 'NIT', 'nit_cliente'])
                ->rules(['required', 'string']),

            ImportColumn::make('nombre_cliente')
                ->requiredMapping()
                ->guess(['Nombre Cliente', 'Nombre completo del receptor', 'Client Name', 'nombre_cliente'])
                ->rules(['required', 'string']),

            ImportColumn::make('iva_debito_fiscal')
                ->guess(['IVA', 'IVA Débito Fiscal', 'IVA (monto de este impuesto)', 'iva_debito_fiscal'])
                ->rules(['numeric', 'min:0']),

            ImportColumn::make('total_documento')
                ->requiredMapping()
                ->guess(['Total', 'Total Documento', 'Monto (Gran Total)', 'Gran Total (Moneda Original)'])
                ->rules(['required', 'numeric', 'min:0']),

        ];
    }

    public function resolveRecord(): ?FacturaVenta
    {
        try {
            Log::debug('🟡 Processing factura venta import', $this->data);

            // Obtener el período seleccionado
            $periodo = LibroVentasPeriodo::with('contribuyente')->findOrFail($this->options['libro_ventas_periodo_id'] ?? null);

            // Calcular base gravada e IVA automáticamente si no vienen
            $total = floatval($this->data['total_documento']);
            $baseGravadaBienes = $this->data['base_gravada_bienes'] ?? round($total / 1.12, 2);
            $ivaDebitoFiscal = $this->data['iva_debito_fiscal'] ?? round($baseGravadaBienes * 0.12, 2);

            // Crear o actualizar factura
            $factura = FacturaVenta::updateOrCreate(
                [
                    'libro_ventas_periodo_id' => $periodo->id,
                    'serie' => $this->data['serie'],
                    'numero' => $this->data['numero'],
                ],
                [
                    'contribuyente_id' => $periodo->contribuyente_id,
                    'fecha' => $this->data['fecha'],
                    'establecimiento' => $this->data['establecimiento'],
                    'tipo' => $this->data['tipo'] ?? 'FACT',
                    'estado' => $this->data['estado'] ?? 'Vigente',
                    'nit_cliente' => $this->data['nit_cliente'],
                    'nombre_cliente' => $this->data['nombre_cliente'],
                    'base_gravada_bienes' => $baseGravadaBienes,
                    'base_gravada_servicios' => $this->data['base_gravada_servicios'] ?? null,
                    'base_exenta_bienes' => $this->data['base_exenta_bienes'] ?? null,
                    'base_exenta_servicios' => $this->data['base_exenta_servicios'] ?? null,
                    'iva_debito_fiscal' => $ivaDebitoFiscal,
                    'total_documento' => $total,
                    'retencion' => $this->data['retencion'] ?? null,
                    'exencion' => $this->data['exencion'] ?? null,
                ]
            );

            Log::debug('🟢 Factura processed successfully', [
                'id' => $factura->id,
                'serie' => $factura->serie,
                'numero' => $factura->numero,
            ]);

            return $factura;

        } catch (\Throwable $e) {
            Log::error('❌ Error processing factura:', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'data' => $this->data,
            ]);

            return null;
        }
    }

    public static function getCompletedNotificationBody(Import $import): string
    {
        $body = 'La importación de facturas se completó y ' . number_format($import->successful_rows) . ' ' . str('fila')->plural($import->successful_rows) . ' fueron importadas.';

        if ($failedRowsCount = $import->getFailedRowsCount()) {
            $body .= ' ' . number_format($failedRowsCount) . ' ' . str('fila')->plural($failedRowsCount) . ' fallaron al importar.';
        }

        return $body;
    }
}
