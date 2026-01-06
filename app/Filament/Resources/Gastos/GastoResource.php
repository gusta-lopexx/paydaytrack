<?php

namespace App\Filament\Resources\Gastos;

use App\Filament\Resources\Gastos\Pages\CreateGasto;
use App\Filament\Resources\Gastos\Pages\EditGasto;
use App\Filament\Resources\Gastos\Pages\ListGastos;
use App\Filament\Resources\Gastos\Schemas\GastoForm;
use App\Filament\Resources\Gastos\Tables\GastosTable;
use App\Models\Gasto;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class GastoResource extends Resource
{
    protected static ?string $model = Gasto::class;

    protected static string|\UnitEnum|null $navigationGroup = 'Financeiro';
    protected static ?string $modelLabel = 'Despesa';
    protected static ?string $pluralModelLabel = 'Despesas';
    protected static ?int $navigationSort = 1;



    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    protected static ?string $recordTitleAttribute = 'descricao';

    public static function getNavigationBadge(): ?string
    {
        $count = Gasto::atrasadas()->count();

        return $count > 0 ? (string) $count : null;
    }
    
    public static function getNavigationBadgeColor(): ?string
    {
        return 'danger';
    }


    public static function form(Schema $schema): Schema
    {
        return GastoForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return GastosTable::configure($table);
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
            'index' => ListGastos::route('/'),
            'create' => CreateGasto::route('/create'),
            'edit' => EditGasto::route('/{record}/edit'),
        ];
    }
}
