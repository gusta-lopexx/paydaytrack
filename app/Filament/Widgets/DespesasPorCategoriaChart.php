<?php

namespace App\Filament\Widgets;

use App\Models\Gasto;
use Carbon\Carbon;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Facades\DB;
use Illuminate\Contracts\Support\Htmlable;

class DespesasPorCategoriaChart extends ChartWidget
{
    
    public function getHeading(): string | Htmlable | null
    {
        return 'Despesas por categoria (mês atual)';
    }

    protected static ?int $sort = 3;

    protected function getType(): string
    {
        return 'pie';
    }

    protected function getData(): array
    {
        $inicioMes = Carbon::now()->startOfMonth();
        $fimMes = Carbon::now()->endOfMonth();

        $dados = Gasto::query()
            ->select('categorias.nome as categoria', DB::raw('SUM(gastos.valor) as total'))
            ->join('categorias', 'categorias.id', '=', 'gastos.categoria_id')
            ->whereBetween('data', [$inicioMes, $fimMes])
            ->groupBy('categorias.nome')
            ->orderByDesc('total')
            ->get();

        return [
            'labels' => $dados->pluck('categoria')->toArray(),
            'datasets' => [
                [
                    'data' => $dados->pluck('total')->toArray(),
                    'backgroundColor' => [
                        '#22c55e', // verde
                        '#ef4444', // vermelho
                        '#3b82f6', // azul
                        '#f59e0b', // amarelo
                        '#8b5cf6', // roxo
                        '#14b8a6', // teal
                        '#ec4899', // rosa
                    ],
                ],
            ],
        ];
    }
}
