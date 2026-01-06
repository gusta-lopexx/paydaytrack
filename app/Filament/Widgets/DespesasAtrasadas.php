<?php

namespace App\Filament\Widgets;

use App\Models\Gasto;
use Filament\Actions\Action;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;
use Illuminate\Contracts\Support\Htmlable;
use Illuminate\Database\Eloquent\Builder;

class DespesasAtrasadas extends BaseWidget
{
    public function getHeading(): string | Htmlable | null
    {
        return '❗ Despesas em atraso (Mês Atual)';
    }

    protected static ?int $sort = 4;

    public function getColumnSpan(): int | string
    {
        return 'full';
    }

    protected function getTableQuery(): Builder
    {
        return Gasto::query()
            ->whereNull('data_pagamento')
            ->whereDate('data', '<', now())
            ->orderBy('data');
    }

    public function table(Table $table): Table
    {
        return $table
            ->query($this->getTableQuery())
            ->columns([
                Tables\Columns\TextColumn::make('descricao')
                    ->label('Descrição')
                    ->searchable(),

                Tables\Columns\TextColumn::make('categoria.nome')
                    ->label('Categoria'),

                Tables\Columns\TextColumn::make('data')
                    ->label('Vencimento')
                    ->date('d/m/Y')
                    ->color('danger'),

                Tables\Columns\TextColumn::make('valor')
                    ->label('Valor')
                    ->money('BRL'),
            ])
            ->actions([
                Action::make('pagar')
                    ->label('Pagar')
                    ->icon('heroicon-o-check-circle')
                    ->color('success')
                    ->requiresConfirmation()
                    ->action(fn (Gasto $record) =>
                        $record->update(['data_pagamento' => now()])
                    ),
            ])
            ->paginated(false);
    }
}
