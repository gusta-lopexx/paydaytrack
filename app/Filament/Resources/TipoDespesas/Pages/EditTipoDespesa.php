<?php

namespace App\Filament\Resources\TipoDespesas\Pages;

use App\Filament\Resources\TipoDespesas\TipoDespesaResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditTipoDespesa extends EditRecord
{
    protected static string $resource = TipoDespesaResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
