<?php

namespace App\Filament\Pages\Relatorios;

use Filament\Pages\Page;
use Filament\Support\Icons\Heroicon;

class DespesasPorCategoria extends Page
{
    protected static string|\BackedEnum|null $navigationIcon = Heroicon::ChartPie;

    protected static string|\UnitEnum|null $navigationGroup = 'Relatórios';

    protected static ?string $navigationLabel = 'Despesas por Categoria';

    protected static ?int $navigationSort = 2;
}
