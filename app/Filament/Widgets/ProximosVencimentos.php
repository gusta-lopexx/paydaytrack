<?php

namespace App\Filament\Widgets;

use App\Models\Gasto;
use Carbon\Carbon;
use Filament\Tables;
use Filament\Widgets\TableWidget as BaseWidget;
use Illuminate\Contracts\Support\Htmlable;
use Illuminate\Database\Eloquent\Builder;

class ProximosVencimentos extends BaseWidget
{
    public function getHeading(): string | Htmlable | null
    {
        return '📅 Próximos vencimentos (mês atual)';
    }
    protected static ?int $sort = 5;

    public function getColumnSpan(): int | string
    {
        return 'full';
    }

    protected function getTableQuery(): Builder
    {
        return Gasto::query()
            ->whereNull('data_pagamento')
            ->whereBetween('data', [
                Carbon::now()->startOfMonth(),
                Carbon::now()->endOfMonth(),
            ])
            ->orderBy('data');
    }

    protected function getTableColumns(): array
    {
        return [
            Tables\Columns\TextColumn::make('descricao')
                ->label('Descrição'),

            Tables\Columns\TextColumn::make('data')
                ->label('Vencimento')
                ->date('d/m/Y'),

            Tables\Columns\TextColumn::make('valor')
                ->money('BRL'),

            Tables\Columns\TextColumn::make('categoria.nome')
                ->label('Categoria'),
        ];
    }

    protected function getTableRecordUrlUsing(): ?callable
    {
        return fn (Gasto $record) =>
            route('filament.admin.resources.gastos.edit', $record);
    }

    protected function isTablePaginationEnabled(): bool
    {
        return false;
    }
}
