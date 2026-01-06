<?php

namespace App\Filament\Resources\Entradas\Pages;

use App\Filament\Resources\Entradas\EntradaResource;
use App\Models\Entrada;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditEntrada extends EditRecord
{
    protected static string $resource = EntradaResource::class;

    protected function handleRecordUpdate($record, array $data): Entrada
    {
        // Atualiza sempre a entrada atual
        $record->update([
            'descricao' => $data['descricao'],
            'valor' => $data['valor'],
            'data' => $data['data'],
            'categoria_id' => $data['categoria_id'],
            'tipo_despesa_id' => $data['tipo_despesa_id'],
        ]);

        // Se marcar replicar, atualiza as futuras
        if (
            ($data['replicar_futuras'] ?? false) === true &&
            !empty($record->recorrencia_id)
        ) {
            Entrada::query()
                ->where('recorrencia_id', $record->recorrencia_id)
                ->where('data', '>=', $record->data)
                ->where('id', '!=', $record->id)
                ->update([
                    'descricao' => $data['descricao'],
                    'valor' => $data['valor'],
                    'categoria_id' => $data['categoria_id'],
                    'tipo_despesa_id' => $data['tipo_despesa_id'],
                ]);
        }

        return $record;
    }

    protected function getHeaderActions(): array
        {
            return [
                DeleteAction::make()
                    ->label('Excluir')
                    ->modalHeading('Excluir entrada recorrente')
                    ->modalDescription('O que você deseja excluir?')
                    ->form([
                        \Filament\Forms\Components\Radio::make('tipo_exclusao')
                            ->label('Opção de exclusão')
                            ->options([
                                'apenas_esta' => 'Excluir somente esta entrada',
                                'futuras' => 'Excluir esta e as entradas futuras',
                                'todas' => 'Excluir todas as entradas da recorrência',
                            ])
                            ->default('apenas_esta')
                            ->required()
                            ->visible(fn () => !empty($this->record->recorrencia_id)),
                    ])
                    ->action(function (array $data) {
                        $record = $this->record;

                        // Caso NÃO seja recorrente → exclui direto
                        if (empty($record->recorrencia_id)) {
                            $record->delete();
                            return;
                        }

                        $tipo = $data['tipo_exclusao'] ?? 'apenas_esta';

                        match ($tipo) {
                            'apenas_esta' => $record->delete(),

                            'futuras' => \App\Models\Entrada::query()
                                ->where('recorrencia_id', $record->recorrencia_id)
                                ->where('data', '>=', $record->data)
                                ->delete(),

                            'todas' => \App\Models\Entrada::query()
                                ->where('recorrencia_id', $record->recorrencia_id)
                                ->delete(),
                        };
                    }),
            ];
        }


    protected function getRedirectUrl(): string
    {
        return static::$resource::getUrl('index');
    }
}


    