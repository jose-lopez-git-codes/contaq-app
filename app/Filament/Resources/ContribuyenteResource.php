<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ContribuyenteResource\Pages;
use App\Models\Contribuyente;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class ContribuyenteResource extends Resource
{
    protected static ?string $model = Contribuyente::class;

    protected static ?string $navigationIcon = 'gmdi-people-alt-s';

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
                Forms\Components\TextInput::make('nit')
                    ->label('NIT')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('nombre')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('nombre_establecimiento')
                    ->label('Nombre Establecimiento (opcional)')
                    ->maxLength(255),
                Forms\Components\Select::make('regimen_id')
                    ->relationship('regimen', 'nombre')
                    ->searchable()
                    ->preload()
                    ->createOptionForm([
                        Forms\Components\TextInput::make('nombre')
                            ->required()
                            ->maxLength(255),
                    ])
                    ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('nit')
                    ->label('NIT')
                    ->searchable(),
                Tables\Columns\TextColumn::make('nombre')
                    ->searchable(),
                Tables\Columns\TextColumn::make('regimen.nombre')
                    ->numeric()
                    ->sortable(),
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
                Tables\Filters\SelectFilter::make('regimen_id')
                    ->relationship('regimen', 'nombre')
                    ->label('Régimen'),
            ])
            ->actions([
                Tables\Actions\Action::make('gestionar_libros')
                    ->label('Gestionar Libros')
                    ->icon('heroicon-o-book-open')
                    ->color('success')
                    ->url(fn (Contribuyente $record): string =>
                         static::getUrl('gestionar-libros', ['record' => $record])
                ),
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
            'index' => Pages\ListContribuyentes::route('/'),
            'create' => Pages\CreateContribuyente::route('/create'),
            'edit' => Pages\EditContribuyente::route('/{record}/edit'),
            'gestionar-libros' => Pages\GestionarLibros::route('/{record}/gestionar-libros'),
            'libro-ventas-anos' => Pages\LibroVentasAnos::route('/{record}/libro-ventas'),
            'libro-ventas-meses' => Pages\LibroVentasMeses::route('/{record}/libro-ventas/{ano}'),
            'gestionar-facturas-mes' => Pages\GestionarFacturasMes::route('/{record}/facturas/{periodo}'),

        ];
    }
}
