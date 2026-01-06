<?php

namespace App\Filament\Widgets;

use App\Models\Entrada;
use App\Models\Gasto;
use Carbon\Carbon;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class FinanceiroResumo extends StatsOverviewWidget
{
    protected function getStats(): array
    {
        // Mês atual
        $inicioMes = Carbon::now()->startOfMonth();
        $fimMes = Carbon::now()->endOfMonth();

        // Total de entradas do mês
        $totalEntradas = Entrada::whereBetween('data', [$inicioMes, $fimMes])
            ->sum('valor');

        // Total de despesas do mês
        $totalDespesas = Gasto::whereBetween('data', [$inicioMes, $fimMes])
            ->sum('valor');

        // Despesas em atraso
        $despesasAtrasadas = Gasto::atrasadas()->count();

        $pendentes = Gasto::whereNull('data_pagamento')
            ->whereBetween('data', [$inicioMes, $fimMes])
            ->count();


        return [
            Stat::make('Entradas do mês', 'R$ ' . number_format($totalEntradas, 2, ',', '.'))
                ->color('success'),

            Stat::make('Despesas do mês', 'R$ ' . number_format($totalDespesas, 2, ',', '.'))
                ->color('danger'),

            Stat::make(
                'Saldo do mês',
                'R$ ' . number_format($totalEntradas - $totalDespesas, 2, ',', '.')
            )
                ->color(($totalEntradas - $totalDespesas) >= 0 ? 'success' : 'danger'),

            Stat::make('Despesas em atraso', $despesasAtrasadas)
                ->color($despesasAtrasadas > 0 ? 'danger' : 'success'),
            
            Stat::make('Pendentes a pagar', $pendentes)
                ->color($pendentes > 0 ? 'warning' : 'success'),

        ];
    }
}
