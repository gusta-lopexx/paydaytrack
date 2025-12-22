<?php

namespace App\Filament\Resources\Gastos\Tables;

use Filament\Tables;
Use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\DatePicker;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Carbon\Carbon;
use Filament\Actions\DeleteAction;
use Filament\Forms\Components\Select;
use Filament\Tables\Columns\Summarizers\Sum;
use SebastianBergmann\CodeCoverage\Filter;

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
                    ->label('Data')
                    ->date('d/m/Y')
                    ->sortable(),
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
                
                
                
                
                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\Filter::make('periodo')
                    ->label('Período')
                    ->form([
                        DatePicker::make('data_inicio')
                            ->label('Data inicial')
                            ->displayFormat('d/m/Y')
                            ->native(false),

                        DatePicker::make('data_fim')
                            ->label('Data final')
                            ->displayFormat('d/m/Y')
                            ->native(false),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query
                            ->when(
                                $data['data_inicio'] ?? null,
                                fn (Builder $query, $date) =>
                                    $query->whereDate('data', '>=', $date)
                            )
                            ->when(
                                $data['data_fim'] ?? null,
                                fn (Builder $query, $date) =>
                                    $query->whereDate('data', '<=', $date)
                            );
                    }),

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
                                ->visible(fn (callable $get) => !$get('mostrar_todos')),

                            Select::make('ano')
                                ->label('Ano')
                                ->options(
                                    collect(range(now()->year - 2, now()->year + 1))
                                        ->mapWithKeys(fn ($year) => [$year => $year])
                                )
                                ->default(now()->year)
                                ->visible(fn (callable $get) => !$get('mostrar_todos')),

                            \Filament\Forms\Components\Toggle::make('mostrar_todos')
                                ->label('Mostrar todas as despesas')
                                ->default(false),
                        ])
                        ->query(function (Builder $query, array $data): Builder {
                            // Se marcar "mostrar todos", não aplica filtro
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
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
