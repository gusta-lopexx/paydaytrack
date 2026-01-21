<?php

namespace App\Filament\Pages\Relatorios;

use Filament\Pages\Page;
use Filament\Support\Icons\Heroicon;

class DespesasPorMes extends Page
{
    protected static string|\BackedEnum|null $navigationIcon = Heroicon::ChartBar;
    
    protected static ?string $title = 'Relatório de Despesas por Mês';

    protected static string|\UnitEnum|null $navigationGroup = 'Relatórios';

    protected static ?string $navigationLabel = 'Despesas por Mês';

    protected static ?int $navigationSort = 1;


}
