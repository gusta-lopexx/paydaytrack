<?php

namespace App\Filament\Resources\Gastos\Pages;

use App\Filament\Resources\Gastos\GastoResource;
use App\Models\Gasto;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditGasto extends EditRecord
{
    protected static string $resource = GastoResource::class;

    /**
     * Lógica de atualização (edição simples ou em lote)
     */
    protected function handleRecordUpdate($record, array $data): Gasto
    {
        // Atualiza o registro atual (incluindo data_pagamento, se vier)
        $record->update([
            'descricao' => $data['descricao'],
            'valor' => $data['valor'],
            'data' => $data['data'], // Dt. Vencimento
            'data_pagamento' => $data['data_pagamento'] ?? $record->data_pagamento,
            'categoria_id' => $data['categoria_id'],
            'tipo_despesa_id' => $data['tipo_despesa_id'],
        ]);

        // Se marcar "replicar alterações", atualiza as futuras
        if (
            ($data['replicar_futuras'] ?? false) === true &&
            !empty($record->recorrencia_id)
        ) {
            Gasto::query()
                ->where('recorrencia_id', $record->recorrencia_id)
                ->where('data', '>', $record->data)
                ->where('id', '!=', $record->id)
                ->update([
                    'descricao' => $data['descricao'],
                    'valor' => $data['valor'],
                    'categoria_id' => $data['categoria_id'],
                    'tipo_despesa_id' => $data['tipo_despesa_id'],
                    // NÃO replica data_pagamento
                ]);
        }

        return $record;
    }


    /**
     * Ações do topo (Excluir)
     */
    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
            
        ];
    }

    /**
     * Redireciona para a listagem após salvar
     */
    protected function getRedirectUrl(): string
    {
        return static::$resource::getUrl('index');
    }
}
