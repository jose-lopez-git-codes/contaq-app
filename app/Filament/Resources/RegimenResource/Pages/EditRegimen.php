<?php

namespace App\Filament\Resources\RegimenResource\Pages;

use App\Filament\Resources\RegimenResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditRegimen extends EditRecord
{
    protected static string $resource = RegimenResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
