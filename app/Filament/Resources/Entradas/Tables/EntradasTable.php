<?php

namespace App\Filament\Resources\Entradas\Tables;

use Filament\Actions\Action;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Tables;
use Filament\Forms\Components\Select;
use Illuminate\Database\Eloquent\Builder;
use Carbon\Carbon;
use Filament\Tables\Columns\Summarizers\Sum;



class EntradasTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->defaultSort('data', 'asc')
            ->columns([
                TextColumn::make('descricao')
                    ->label('Descrição')
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
                    ->toggleable(isToggledHiddenByDefault: true),

                TextColumn::make('updated_at')
                    ->dateTime()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])

            ->filters([
                Tables\Filters\Filter::make('periodo')
                    ->label('Período')
                    ->form([
                        Select::make('mes')
                            ->label('Mês')
                            ->options([
                                'todos' => 'Todos',
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
                            ->default('todos')
                            ->required(),

                        Select::make('ano')
                            ->label('Ano')
                            ->options(
                                collect(range(now()->year - 2, now()->year + 1))
                                    ->mapWithKeys(fn ($year) => [$year => $year])
                            )
                            ->default(now()->year)
                            ->required(),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        // 👇 Se for "Todos", não aplica filtro de data
                        if (($data['mes'] ?? null) === 'todos') {
                            return $query;
                        }

                        return $query
                            ->whereMonth('data', $data['mes'])
                            ->whereYear('data', $data['ano']);
                    })
                    ->default([
                        'mes' => 'todos',
                        'ano' => now()->year,
                    ]),
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
