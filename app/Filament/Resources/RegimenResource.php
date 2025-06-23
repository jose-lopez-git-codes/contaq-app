<?php

namespace App\Filament\Resources;

use App\Filament\Resources\RegimenResource\Pages;
use App\Filament\Resources\RegimenResource\RelationManagers;
use App\Models\Regimen;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class RegimenResource extends Resource
{
    protected static ?string $model = Regimen::class;

    protected static ?string $navigationIcon = 'hugeicons-taxes';

    public static function getNavigationLabel(): string
    {
        return 'Regímenes';
    }

    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::count();
    }

    public static function getNavigationBadgeColor(): ?string
    {
        return static::getModel()::count() < 0 ? 'danger' : 'success';
    }

    public static function getNavigationBadgeTooltip(): ?string
    {
        return 'Total de Contribuyentes';
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('nombre')
                    ->required()
                    ->maxLength(255),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('nombre')
                    ->searchable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListRegimens::route('/'),
            'create' => Pages\CreateRegimen::route('/create'),
            'edit' => Pages\EditRegimen::route('/{record}/edit'),
        ];
    }
}
