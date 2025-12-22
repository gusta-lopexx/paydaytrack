<?php

namespace App\Filament\Resources\TipoDespesas\Pages;

use App\Filament\Resources\TipoDespesas\TipoDespesaResource;
use Filament\Resources\Pages\CreateRecord;

class CreateTipoDespesa extends CreateRecord
{
    protected static string $resource = TipoDespesaResource::class;

    protected function getRedirectUrl(): string
    {
        return static::$resource::getUrl('index');
    }

}
