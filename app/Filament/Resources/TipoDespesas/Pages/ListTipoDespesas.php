<?php

namespace App\Filament\Resources\TipoDespesas\Pages;

use App\Filament\Resources\TipoDespesas\TipoDespesaResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListTipoDespesas extends ListRecords
{
    protected static string $resource = TipoDespesaResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
