<?php

namespace App\Filament\Resources\Gastos\Tables;

use Filament\Tables;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\DatePicker;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Filament\Actions\Action;
use Filament\Actions\DeleteAction;
use Filament\Forms\Components\Select;
use Filament\Tables\Columns\Summarizers\Sum;
use Filament\Forms\Components\Toggle;

class GastosTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('descricao')
                    ->searchable(),

                TextColumn::make('valor')
                    ->label('Valor')
                    ->money('BRL', locale: 'pt_BR')
                    ->sortable()
                    ->summarize(
                        Sum::make()
                            ->label('Total')
                            ->money('BRL', locale: 'pt_BR')
                    ),

                TextColumn::make('data')
                    ->label('Dt. Vencimento')
                    ->date('d/m/Y')
                    ->sortable()
                    ->color(fn ($record) =>
                        $record->data_pagamento === null && $record->data->isPast()
                            ? 'danger'
                            : null
                    )
                    ->icon(fn ($record) =>
                        $record->data_pagamento === null && $record->data->isPast()
                            ? 'heroicon-o-exclamation-triangle'
                            : null
                    ),

                TextColumn::make('categoria.nome')
                    ->label('Categoria')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('tipoDespesa.nome')
                    ->label('Tipo')
                    ->badge()
                    ->colors([
                        'success' => 'Fixo',
                        'warning' => 'Variável',
                        'gray' => 'Eventual',
                    ])
                    ->sortable(),

                TextColumn::make('data_pagamento')
                    ->label('Dt. Pagamento')
                    ->date('d/m/Y')
                    ->placeholder('Em aberto')
                    ->sortable(),
            ])

            ->filters([
                // 🔴 Atrasadas (vencidas e não pagas)
                Tables\Filters\Filter::make('atrasadas')
                    ->label('Atrasadas')
                    ->query(fn (Builder $query) =>
                        $query
                            ->whereNull('data_pagamento')
                            ->whereDate('data', '<', now())
                    ),

                // 🟡 Em aberto (não pagas, independente da data)
                Tables\Filters\Filter::make('em_aberto')
                    ->label('Em aberto')
                    ->query(fn (Builder $query) =>
                        $query->whereNull('data_pagamento')
                    ),

                // 📅 Filtro por mês / ano
                Tables\Filters\Filter::make('mes_atual')
                    ->label('Filtro')
                    ->form([
                        Select::make('mes')
                            ->label('Mês')
                            ->options([
                                '01' => 'Janeiro',
                                '02' => 'Fevereiro',
                                '03' => 'Março',
                                '04' => 'Abril',
                                '05' => 'Maio',
                                '06' => 'Junho',
                                '07' => 'Julho',
                                '08' => 'Agosto',
                                '09' => 'Setembro',
                                '10' => 'Outubro',
                                '11' => 'Novembro',
                                '12' => 'Dezembro',
                            ])
                            ->default(now()->format('m'))
                            ->visible(fn (callable $get) => ! $get('mostrar_todos')),

                        Select::make('ano')
                            ->label('Ano')
                            ->options(
                                collect(range(now()->year - 2, now()->year + 1))
                                    ->mapWithKeys(fn ($year) => [$year => $year])
                            )
                            ->default(now()->year)
                            ->visible(fn (callable $get) => ! $get('mostrar_todos')),

                        Toggle::make('mostrar_todos')
                            ->label('Mostrar todas as despesas')
                            ->default(false),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        if (($data['mostrar_todos'] ?? false) === true) {
                            return $query;
                        }

                        return $query
                            ->when(
                                $data['mes'] ?? null,
                                fn (Builder $query, $mes) =>
                                    $query->whereMonth('data', $mes)
                            )
                            ->when(
                                $data['ano'] ?? null,
                                fn (Builder $query, $ano) =>
                                    $query->whereYear('data', $ano)
                            );
                    }),
            ])

            ->recordActions([
                EditAction::make(),
                DeleteAction::make(),

                Action::make('pagar')
                    ->label('Pagar')
                    ->icon('heroicon-o-check-circle')
                    ->color('success')
                    ->visible(fn ($record) => $record->data_pagamento === null)
                    ->form([
                        DatePicker::make('data_pagamento')
                            ->label('Data de pagamento')
                            ->default(now())
                            ->displayFormat('d/m/Y')
                            ->native(false)
                            ->required(),
                    ])
                    ->action(fn ($record, array $data) =>
                        $record->update([
                            'data_pagamento' => $data['data_pagamento'],
                        ])
                    ),
            ])

            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
