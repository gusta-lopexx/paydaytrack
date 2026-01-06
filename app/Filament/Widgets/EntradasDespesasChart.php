<?php

namespace App\Filament\Widgets;

use App\Models\Entrada;
use App\Models\Gasto;
use Carbon\Carbon;
use Filament\Widgets\ChartWidget;
use Illuminate\Contracts\Support\Htmlable;

class EntradasDespesasChart extends ChartWidget
{
    protected static ?int $sort = 2;

    public function getHeading(): string | Htmlable | null
    {
        return 'Entradas x Despesas (Mês atual)';
    }

    protected function getData(): array
    {
        $inicioMes = Carbon::now()->startOfMonth();
        $fimMes = Carbon::now()->endOfMonth();

        $totalEntradas = Entrada::whereBetween('data', [$inicioMes, $fimMes])
            ->sum('valor');

        $totalDespesas = Gasto::whereBetween('data', [$inicioMes, $fimMes])
            ->sum('valor');

        return [
            'labels' => ['Mês Atual'],
            'datasets' => [
                [
                    'label' => 'Entradas',
                    'data' => [$totalEntradas],
                    'backgroundColor' => '#22c55e', // verde
                ],
                [
                    'label' => 'Despesas',
                    'data' => [$totalDespesas],
                    'backgroundColor' => '#ef4444',
                    'borderColor' => '#ef4444', // vermelho
                ],
            ],
        ];
    }

    protected function getType(): string
    {
        return 'bar';
    }
}
