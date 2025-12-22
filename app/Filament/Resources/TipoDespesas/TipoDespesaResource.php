<?php

namespace App\Filament\Resources\TipoDespesas;

use App\Filament\Resources\TipoDespesas\Pages\CreateTipoDespesa;
use App\Filament\Resources\TipoDespesas\Pages\EditTipoDespesa;
use App\Filament\Resources\TipoDespesas\Pages\ListTipoDespesas;
use App\Filament\Resources\TipoDespesas\Schemas\TipoDespesaForm;
use App\Filament\Resources\TipoDespesas\Tables\TipoDespesasTable;
use App\Models\TipoDespesa;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class TipoDespesaResource extends Resource
{
    protected static ?string $model = TipoDespesa::class;

    protected static string|\UnitEnum|null $navigationGroup = 'Configurações';
    protected static ?int $navigationSort = 10;

    protected static ?string $modelLabel = 'Tipo de despesa';
    protected static ?string $pluralModelLabel = 'Tipos de despesa';


    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    protected static ?string $recordTitleAttribute = 'nome';

    public static function form(Schema $schema): Schema
    {
        return TipoDespesaForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return TipoDespesasTable::configure($table);
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
            'index' => ListTipoDespesas::route('/'),
            'create' => CreateTipoDespesa::route('/create'),
            'edit' => EditTipoDespesa::route('/{record}/edit'),
        ];
    }
}
