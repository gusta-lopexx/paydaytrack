<?php

namespace App\Filament\Resources\Gastos\Schemas;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;
use App\Models\TipoDespesa;


class GastoForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('descricao')
                    ->required(),
                
                TextInput::make('valor')
                    ->label('Valor')
                    ->required()
                    ->numeric()
                    ->prefix('R$')
                    ->inputMode('decimal'),


                DatePicker::make('data')
                    ->label('Dt. Vencimento')
                    ->required()
                    ->displayFormat('d/m/Y')
                    ->format('Y-m-d')
                    ->native(false)
                    ->closeOnDateSelection(),

                Select::make('categoria_id')
                    ->label('Categoria')
                    ->relationship('categoria', 'nome')
                    ->required(),
                
                Select::make('tipo_despesa_id')
                    ->label('Tipo de despesa')
                    ->relationship('tipoDespesa', 'nome')
                    ->required()
                    ->live(),
                
                Toggle::make('gerar_recorrencia')
                    ->label('Gerar despesas futuras?')
                    ->helperText('Use para despesas fixas mensais')
                    ->default(false)
                    ->live()
                    ->dehydrated(),


                TextInput::make('quantidade_meses')
                    ->label('Quantidade de meses')
                    ->numeric()
                    ->minValue(1)
                    ->maxValue(120)
                    ->default(1)
                    ->required(fn (callable $get) => $get('gerar_recorrencia') === true)
                    ->visible(fn (callable $get) => $get('gerar_recorrencia') === true)
                    ->dehydrated(),

                Toggle::make('replicar_futuras')
                    ->label('Replicar alterações para despesas futuras')
                    ->helperText('Aplica as alterações para todas as despesas fixas futuras')
                    ->visible(function ($record) {
                        return $record !== null && !empty($record->recorrencia_id);
                    })
                    ->dehydrated(),

                DatePicker::make('data_pagamento')
                    ->label('Dt. Pagamento')
                    ->displayFormat('d/m/Y')
                    ->native(false)
                    ->helperText('Informe a data em que a despesa foi paga')
                    ->visible(fn ($record) => $record !== null),

                
                
            ]);
    }
}
