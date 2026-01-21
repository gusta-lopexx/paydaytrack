<?php

namespace App\Filament\Pages\Relatorios;

use App\Filament\Widgets\ResumoMensalTable;
use Filament\Pages\Page;
use Filament\Support\Icons\Heroicon;

class ResumoMensal extends Page
{
    protected static string|\BackedEnum|null $navigationIcon = Heroicon::Calendar;

    protected static string|\UnitEnum|null $navigationGroup = 'Relatórios';

    protected static ?string $navigationLabel = 'Resumo Mensal';

    protected static ?int $navigationSort = 3;

    protected function getHeaderWidgets(): array
    {
        return [
            ResumoMensalTable::class,
        ];
    }


}
