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
use Livewire\Attributes\Url;

class LibroVentasMeses extends Page
{
    protected static string $resource = ContribuyenteResource::class;

    protected static string $view = 'filament.resources.contribuyente-resource.pages.libro-ventas-meses';

    public Contribuyente $record;
    public int $ano;

    // Propiedad para el modo de vista con persistencia en URL
    #[Url(as: 'view', except: 'grid')]
    public string $viewMode = 'grid';

    public function mount(Contribuyente $record, int $ano): void
    {
        $this->record = $record;
        $this->ano = $ano;

        // Recuperar la preferencia guardada o usar 'grid' por defecto
        $this->viewMode = session('view_mode_libro_ventas', 'grid');
    }

    public function getTitle(): string
    {
        return 'Libro de Ventas ' . $this->ano . ' - ' . $this->record->nombre;
    }

    protected function getHeaderActions(): array
    {
        return [
            Actions\Action::make('volver')
                ->label('Volver a Años')
                ->icon('heroicon-o-arrow-left')
                ->url($this->getResource()::getUrl('libro-ventas-anos', ['record' => $this->record]))
                ->color('gray'),
        ];
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

        // Guardar la preferencia en la sesión
        session(['view_mode_libro_ventas' => $mode]);
    }

    /**
     * Obtener las opciones de vista disponibles
     */
    public function getViewModeOptions(): array
    {
        return [
            'grid' => [
                'label' => 'Cuadrícula',
                'icon' => 'heroicon-o-squares-2x2',
                'tooltip' => 'Vista de cuadrícula'
            ],
            'list' => [
                'label' => 'Lista',
                'icon' => 'heroicon-o-list-bullet',
                'tooltip' => 'Vista de lista'
            ],
            'icons' => [
                'label' => 'Iconos',
                'icon' => 'heroicon-o-squares-plus',
                'tooltip' => 'Vista de iconos grandes'
            ]
        ];
    }

    /**
     * Verificar si una vista está activa
     */
    public function isViewModeActive(string $mode): bool
    {
        return $this->viewMode === $mode;
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

    public function getMesesDelAño()
    {
        return LibroVentasPeriodo::getMesesDelAño($this->record->id, $this->ano);
    }

    /**
     * Obtener estadísticas generales para mostrar en el header
     */
    public function getEstadisticasGenerales(): array
    {
        $meses = $this->getMesesDelAño();

        return [
            'total_meses' => $meses->count(),
            'meses_abiertos' => $meses->where('estado', 'abierto')->count(),
            'meses_cerrados' => $meses->where('estado', 'cerrado')->count(),
            'total_facturas' => $meses->sum(function($periodo) {
                return $periodo->facturas->count();
            })
        ];
    }
}
