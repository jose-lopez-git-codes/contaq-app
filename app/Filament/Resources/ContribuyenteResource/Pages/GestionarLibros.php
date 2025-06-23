<?php

namespace App\Filament\Resources\ContribuyenteResource\Pages;

use App\Filament\Resources\ContribuyenteResource;
use App\Models\Contribuyente;
use Filament\Actions;
use Filament\Resources\Pages\Page;
use Filament\Infolists\Infolist;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Components\Section;

class GestionarLibros extends Page
{
    protected static string $resource = ContribuyenteResource::class;

    protected static string $view = 'filament.resources.contribuyente-resource.pages.gestionar-libros';

    public Contribuyente $record;

    public function getTitle(): string
    {
        return 'Gestión de Libros - ' . $this->record->nombre;
    }

    protected function getHeaderActions(): array
    {
        return [
            Actions\Action::make('volver')
                ->label('Volver a Contribuyentes')
                ->icon('heroicon-o-arrow-left')
                ->url($this->getResource()::getUrl('index'))
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
                        TextEntry::make('nit')
                            ->label('NIT'),
                        TextEntry::make('nombre')
                            ->label('Nombre'),
                        TextEntry::make('nombre_establecimiento')
                            ->label('Nombre Establecimiento')
                            ->placeholder('No especificado'),
                        TextEntry::make('regimen.nombre')
                            ->label('Régimen'),
                    ])
                    ->columns(2)
            ]);
    }
}
