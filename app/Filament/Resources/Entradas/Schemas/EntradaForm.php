<?php

namespace App\Filament\Resources\Entradas\Schemas;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;
use Filament\Forms\Components\Toggle;


class EntradaForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('descricao')
                    ->label('Descrição')
                    ->required()
                    ->maxLength(255),

                TextInput::make('valor')
                    ->label('Valor')
                    ->required()
                    ->numeric()
                    ->prefix('R$')
                    ->inputMode('decimal')
                    ->dehydrateStateUsing(function ($state) {
                        if ($state === null) {
                            return 0;
                        }

                        // Normaliza 99,99 ou 99.99 para 99.99
                        $state = str_replace(['.', ','], ['', '.'], $state);

                        return (float) $state;
                    }),

                DatePicker::make('data')
                    ->label('Data de recebimento')
                    ->required()
                    ->displayFormat('d/m/Y')
                    ->native(false),

                Select::make('categoria_id')
                    ->label('Categoria')
                    ->relationship('categoria', 'nome')
                    ->searchable()
                    ->preload()
                    ->required(),

                Select::make('tipo_despesa_id')
                    ->label('Tipo de entrada')
                    ->relationship('tipoDespesa', 'nome')
                    ->required()
                    ->live(),
                Toggle::make('gerar_recorrencia')
                    ->label('Gerar recorrência')
                    ->helperText('Cria entradas mensais automaticamente')
                    ->visible(fn (callable $get) => $get('tipo_despesa_id') == 1) // 1 = Fixo
                    ->live(),

                TextInput::make('quantidade_meses')
                    ->label('Quantidade de meses')
                    ->numeric()
                    ->minValue(1)
                    ->maxValue(24)
                    ->default(12)
                    ->visible(fn (callable $get) => $get('gerar_recorrencia'))
                    ->required(fn (callable $get) => $get('gerar_recorrencia')),
                
                Toggle::make('replicar_futuras')
                    ->label('Replicar alterações para entradas futuras')
                    ->helperText('Aplica as alterações para todas as entradas recorrentes futuras')
                    ->visible(fn ($record) => $record !== null && !empty($record->recorrencia_id))
                    ->dehydrated(),


            ]);
    }
}
