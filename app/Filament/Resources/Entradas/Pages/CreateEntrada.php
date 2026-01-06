<?php

namespace App\Filament\Resources\Entradas\Pages;

use App\Filament\Resources\Entradas\EntradaResource;
use App\Models\Entrada;
use Carbon\Carbon;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Support\Str;

class CreateEntrada extends CreateRecord
{
    protected static string $resource = EntradaResource::class;

    protected function handleRecordCreation(array $data): Entrada
    {
        // Cria um ID único para a recorrência
        $recorrenciaId = Str::uuid();

        // Cria a entrada principal
        $entrada = Entrada::create([
            'descricao' => $data['descricao'],
            'valor' => $data['valor'],
            'data' => $data['data'],
            'categoria_id' => $data['categoria_id'],
            'tipo_despesa_id' => $data['tipo_despesa_id'],
            'recorrencia_id' => ($data['gerar_recorrencia'] ?? false) ? $recorrenciaId : null,

        ]);

        // Se não for recorrente, encerra aqui
        if (
            empty($data['gerar_recorrencia']) ||
            empty($data['quantidade_meses']) ||
            (int) $data['quantidade_meses'] <= 1
        ) {
            return $entrada;
        }

        $dataInicial = Carbon::parse($data['data']);

        // Cria as entradas futuras
        for ($i = 1; $i < (int) $data['quantidade_meses']; $i++) {
            Entrada::create([
                'descricao' => $data['descricao'],
                'valor' => $data['valor'],
                'data' => $dataInicial->copy()->addMonths($i),
                'categoria_id' => $data['categoria_id'],
                'tipo_despesa_id' => $data['tipo_despesa_id'],
                'recorrencia_id' => $recorrenciaId,
            ]);
        }

        return $entrada;
    }

    protected function getRedirectUrl(): string
    {
        return static::$resource::getUrl('index');
    }
}
