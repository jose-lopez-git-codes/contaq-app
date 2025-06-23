<?php

namespace App\Filament\Resources\ContribuyenteResource\Pages;

use App\Filament\Imports\FacturaVentaImporter;
use App\Filament\Resources\ContribuyenteResource;
use App\Models\Contribuyente;
use App\Models\LibroVentasPeriodo;
use App\Models\FacturaVenta;
use Filament\Actions;
use Filament\Actions\ImportAction;
use Filament\Resources\Pages\Page;
use Filament\Infolists\Infolist;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Components\Section;
use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Actions\CreateAction;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Actions\DeleteAction;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Grid;
use Illuminate\Validation\Rules\File;

class GestionarFacturasMes extends Page implements HasTable, HasForms
{
    use InteractsWithTable, InteractsWithForms;

    protected static string $resource = ContribuyenteResource::class;

    protected static string $view = 'filament.resources.contribuyente-resource.pages.gestionar-facturas-mes';

    public Contribuyente $record;
    public LibroVentasPeriodo $periodo;

    public function mount(Contribuyente $record, LibroVentasPeriodo $periodo): void
    {
        $this->record = $record;
        $this->periodo = $periodo;
    }

    public function getTitle(): string
    {
        return 'Facturas de ' . $this->periodo->nombre_mes . ' ' . $this->periodo->año . ' - ' . $this->record->nombre;
    }

    protected function getHeaderActions(): array
    {
        return [
            ImportAction::make()
                    ->importer(FacturaVentaImporter::class)
                    ->label('Importar Excel/CSV')
                    ->icon('heroicon-o-document-arrow-up')
                    ->color('success')
                    ->slideOver()
                    ->options([
                        'libro_ventas_periodo_id' => $this->periodo->id,
                    ])
                    ->fileRules([File::types(['csv'])->max(10240)]),

            Actions\Action::make('crear_factura')
                ->label('Nueva Factura')
                ->icon('heroicon-o-plus')
                ->color('primary')
                ->form($this->getFacturaForm()),

            Actions\Action::make('volver')
                ->label('Volver a Meses')
                ->icon('heroicon-o-arrow-left')
                ->url($this->getResource()::getUrl('libro-ventas-meses', ['record' => $this->record, 'ano' => $this->periodo->año]))
                ->color('gray'),
        ];
    }

    public function table(Table $table): Table
    {
        return $table
            ->query(FacturaVenta::query()->where('libro_ventas_periodo_id', $this->periodo->id))
            ->columns([
                TextColumn::make('fecha')
                    ->label('Fecha')
                    ->date()
                    ->sortable(),

                TextColumn::make('serie')
                    ->label('Serie')
                    ->searchable(),

                TextColumn::make('numero')
                    ->label('Número de Factura')
                    ->searchable(),

                TextColumn::make('nombre_cliente')
                    ->label('Cliente')
                    ->searchable()
                    ->limit(30),

                TextColumn::make('total_documento')
                    ->label('Total')
                    ->money('GTQ')
                    ->sortable(),

                TextColumn::make('base_gravada_bienes')
                    ->label('Sub-Total')
                    ->money('GTQ')
                    ->sortable(),

                TextColumn::make('iva_debito_fiscal')
                    ->label('IVA')
                    ->money('GTQ')
                    ->sortable(),

                TextColumn::make('estado')
                    ->label('Estado')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'Vigente' => 'success',
                        'Anulado' => 'danger',
                        default => 'gray',
                    }),
            ])
            ->actions([
                EditAction::make()
                    ->form($this->getFacturaForm()),
                DeleteAction::make(),
            ])
            ->headerActions([
                CreateAction::make()
                    ->label('Nueva Factura')
                    ->icon('heroicon-o-plus')
                    ->form($this->getFacturaForm())
                    ->mutateFormDataUsing(function (array $data): array {
                        $data['libro_ventas_periodo_id'] = $this->periodo->id;
                        $data['contribuyente_id'] = $this->record->id;
                        return $data;
                    }),
            ]);
    }

    protected function getFacturaForm(): array
    {
        return [
            Grid::make(2)
                ->schema([
                    DatePicker::make('fecha')
                        ->label('Fecha')
                        ->required()
                        ->default(now()),

                    TextInput::make('establecimiento')
                        ->label('Establecimiento')
                        ->required()
                        ->numeric(),
                ]),

            Grid::make(3)
                ->schema([
                    Select::make('tipo')
                        ->label('Tipo')
                        ->required()
                        ->options([
                            'FACT' => 'Factura',
                            'FCAM' => 'Factura Cambiaria',
                            'FPEQ' => 'Factura Pequeño Contribuyente',
                            'FCAP' => 'Factura Cambiaria Pequeño Contribuyente',
                            'FESP' => 'Factura Especial',
                            'NABN' => 'Nota de Abono',
                        ]),

                    Select::make('estado')
                        ->label('Estado')
                        ->required()
                        ->options([
                            'vigente' => 'Vigente',
                            'anulado' => 'Anulado',
                        ])
                        ->default('vigente'),

                    TextInput::make('serie')
                        ->label('Serie')
                        ->required(),
                ]),

            Grid::make(2)
                ->schema([
                    TextInput::make('numero')
                        ->label('Número')
                        ->required(),

                    TextInput::make('nit_cliente')
                        ->label('NIT Cliente')
                        ->required(),
                ]),

            TextInput::make('nombre_cliente')
                ->label('Nombre del Cliente')
                ->required()
                ->columnSpanFull(),

            Grid::make(2)
                ->schema([
                    TextInput::make('base_gravada_bienes')
                        ->label('Base Gravada Bienes')
                        ->required()
                        ->numeric()
                        ->step(0.01)
                        ->prefix('Q')
                        ->readOnly(),

                    TextInput::make('base_gravada_servicios')
                        ->label('Base Gravada Servicios')
                        ->numeric()
                        ->step(0.01)
                        ->prefix('Q'),
                ]),

            Grid::make(2)
                ->schema([
                    TextInput::make('base_exenta_bienes')
                        ->label('Base Exenta Bienes')
                        ->numeric()
                        ->step(0.01)
                        ->prefix('Q'),

                    TextInput::make('base_exenta_servicios')
                        ->label('Base Exenta Servicios')
                        ->numeric()
                        ->step(0.01)
                        ->prefix('Q'),
                ]),

            Grid::make(2)
                ->schema([
                    TextInput::make('iva_debito_fiscal')
                        ->label('IVA Débito Fiscal')
                        ->required()
                        ->numeric()
                        ->step(0.01)
                        ->prefix('Q')
                        ->readOnly(),

                    TextInput::make('total_documento')
                        ->label('Total Documento')
                        ->required()
                        ->numeric()
                        ->step(0.01)
                        ->prefix('Q')
                        ->live(onBlur: true)
                        ->afterStateUpdated(function ($state, $set) {
                            if ($state) {
                                $total = floatval($state);
                                // Base gravada = Total / 1.12
                                $baseGravada = round($total / 1.12, 2);
                                // IVA = Base gravada * 0.12
                                $iva = round($baseGravada * 0.12, 2);

                                $set('base_gravada_bienes', $baseGravada);
                                $set('iva_debito_fiscal', $iva);
                            }
                        }),
                ]),

            Grid::make(2)
                ->schema([
                    TextInput::make('retencion')
                        ->label('Retención')
                        ->numeric()
                        ->step(0.01)
                        ->prefix('Q'),

                    TextInput::make('exencion')
                        ->label('Exención')
                        ->numeric()
                        ->step(0.01)
                        ->prefix('Q'),
                ]),
        ];
    }

    public function periodoInfolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->record($this->periodo)
            ->schema([
                Section::make('Información del Período')
                    ->schema([
                        TextEntry::make('año')->label('Año'),
                        TextEntry::make('nombre_mes')->label('Mes'),
                        TextEntry::make('estado')
                            ->label('Estado')
                            ->badge()
                            ->color(fn (string $state): string => match ($state) {
                                'Vigente' => 'success',
                                'Anulado' => 'gray',
                                default => 'gray',
                            }),
                        TextEntry::make('facturas_count')
                            ->label('Total Facturas')
                            ->getStateUsing(fn () => $this->periodo->facturas->count()),
                    ])
                    ->columns(4)
                    ->compact()
            ]);
    }
}
