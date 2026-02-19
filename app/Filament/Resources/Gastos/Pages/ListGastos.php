<?php

namespace App\Filament\Resources\Gastos\Pages;

use App\Filament\Resources\Gastos\GastoResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use Filament\Tables\Table;

class ListGastos extends ListRecords
{
    protected static string $resource = GastoResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()
                ->label('Criar despesa'),
        ];
    }

    public function table(Table $table): Table
    {
        return parent::table($table)
            ->persistFiltersInSession()
            ->persistSearchInSession()
            ->persistSortInSession()
            ->persistColumnSearchesInSession();
    }
}
