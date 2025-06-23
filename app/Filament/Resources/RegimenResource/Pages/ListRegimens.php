<?php

namespace App\Filament\Resources\RegimenResource\Pages;

use App\Filament\Resources\RegimenResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListRegimens extends ListRecords
{
    protected static string $resource = RegimenResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
