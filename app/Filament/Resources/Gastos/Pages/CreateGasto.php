<?php

namespace App\Filament\Resources\Gastos\Pages;

use App\Filament\Resources\Gastos\GastoResource;
use App\Models\Gasto;
use Carbon\Carbon;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Support\Str;

class CreateGasto extends CreateRecord
{
    protected static string $resource = GastoResource::class;

    protected function handleRecordCreation(array $data): Gasto
    {
        // Define recorrência apenas se for fixa e gerar recorrência
        $recorrenciaId = null;

        if (
            ($data['gerar_recorrencia'] ?? false) === true &&
            !empty($data['quantidade_meses']) &&
            (int) $data['quantidade_meses'] > 1
        ) {
            $recorrenciaId = Str::uuid();
        }

        // Cria a despesa principal
        $gasto = Gasto::create([
            'descricao' => $data['descricao'],
            'valor' => $data['valor'],
            'data' => $data['data'],
            'categoria_id' => $data['categoria_id'],
            'tipo_despesa_id' => $data['tipo_despesa_id'],
            'recorrencia_id' => $recorrenciaId,
        ]);

        // Gera as despesas futuras
        if ($recorrenciaId) {
            $dataInicial = Carbon::parse($data['data']);

            for ($i = 1; $i < (int) $data['quantidade_meses']; $i++) {
                Gasto::create([
                    'descricao' => $data['descricao'],
                    'valor' => $data['valor'],
                    'data' => $dataInicial->copy()->addMonths($i),
                    'categoria_id' => $data['categoria_id'],
                    'tipo_despesa_id' => $data['tipo_despesa_id'],
                    'recorrencia_id' => $recorrenciaId,
                ]);
            }
        }

        return $gasto;
    }


    protected function getRedirectUrl(): string
        {
            // Data informada no formulário
            $data = $this->data;

            if (! empty($data['data'])) {
                $date = Carbon::parse($data['data']);

                return static::$resource::getUrl('index', [
                    'tableFilters' => [
                        'mes_atual' => [
                            'mes' => $date->format('m'),
                            'ano' => $date->year,
                        ],
                    ],
                ]);
            }

            return static::$resource::getUrl('index');
        }

}
