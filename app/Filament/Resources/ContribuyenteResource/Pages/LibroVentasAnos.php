<?php

namespace App\Filament\Resources\ContribuyenteResource\Pages;

use App\Filament\Resources\ContribuyenteResource;
use App\Models\Contribuyente;
use App\Models\LibroVentasPeriodo;
use Filament\Actions;
use Filament\Resources\Pages\Page;
use Filament\Infolists\Infolist;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Components\Section;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Select;
use Filament\Notifications\Notification;

class LibroVentasAnos extends Page
{
    protected static string $resource = ContribuyenteResource::class;

    protected static string $view = 'filament.resources.contribuyente-resource.pages.libro-ventas-anos';

    public Contribuyente $record;

    // Propiedad para el modo de vista
    public string $viewMode = 'grid';

    public function mount(Contribuyente $record): void
    {
        $this->record = $record;

        // Recuperar la preferencia guardada o usar 'grid' por defecto
        $this->viewMode = session('view_mode_libro_ventas_anos', 'grid');
    }

    public function getTitle(): string
    {
        return 'Libro de Ventas - ' . $this->record->nombre;
    }

    /**
     * Método para cambiar el modo de vista
     */
    public function setViewMode(string $mode): void
    {
        // Validar que el modo sea válido
        if (!in_array($mode, ['grid', 'list', 'icons'])) {
            return;
        }

        $this->viewMode = $mode;

        // Guardar la preferencia en la sesión (separada de la vista de meses)
        session(['view_mode_libro_ventas_anos' => $mode]);
    }

    protected function getHeaderActions(): array
    {
        return [
            Actions\Action::make('crear_año')
                ->label('Crear Nuevo Año Contable')
                ->icon('heroicon-o-plus')
                ->color('success')
                ->form([
                    TextInput::make('año')
                        ->label('Año')
                        ->numeric()
                        ->required()
                        ->minValue(2020)
                        ->maxValue(2030)
                        ->default(date('Y')),
                ])
                ->action(function (array $data): void {
                    // Verificar si el año ya existe
                    $existeAño = LibroVentasPeriodo::where('contribuyente_id', $this->record->id)
                        ->where('año', $data['año'])
                        ->exists();

                    if ($existeAño) {
                        Notification::make()
                            ->title('El año ' . $data['año'] . ' ya existe')
                            ->warning()
                            ->send();
                        return;
                    }

                    // Crear los 12 meses del año
                    for ($mes = 1; $mes <= 12; $mes++) {
                        LibroVentasPeriodo::create([
                            'contribuyente_id' => $this->record->id,
                            'año' => $data['año'],
                            'mes' => $mes,
                        ]);
                    }

                    Notification::make()
                        ->title('Año ' . $data['año'] . ' creado exitosamente')
                        ->body('Se crearon los 12 meses del año contable.')
                        ->success()
                        ->send();
                }),

            Actions\Action::make('volver')
                ->label('Volver')
                ->icon('heroicon-o-arrow-left')
                ->url($this->getResource()::getUrl('gestionar-libros', ['record' => $this->record]))
                ->color('gray'),
        ];
    }

    public function contribuyenteInfolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->record($this->record)
            ->schema([
                Section::make('Información del Contribuyente')
                    ->schema([
                        TextEntry::make('nit')->label('NIT'),
                        TextEntry::make('nombre')->label('Nombre'),
                        TextEntry::make('regimen.nombre')->label('Régimen'),
                    ])
                    ->columns(3)
                    ->compact()
            ]);
    }

    public function getAñosDisponibles()
    {
        return LibroVentasPeriodo::getAñosDisponibles($this->record->id);
    }

    /**
     * Obtener estadísticas de años para mostrar información adicional
     */
    public function getEstadisticasAnos(): array
    {
        $años = $this->getAñosDisponibles();

        $estadisticas = [];
        foreach ($años as $año) {
            $mesesCount = LibroVentasPeriodo::where('contribuyente_id', $this->record->id)
                ->where('año', $año)
                ->count();

            $estadisticas[$año] = [
                'meses_creados' => $mesesCount,
                'completo' => $mesesCount === 12,
                'progreso' => round(($mesesCount / 12) * 100)
            ];
        }

        return $estadisticas;
    }
}
