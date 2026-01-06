<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class Gasto extends Model
{
    public function categoria()
    {
        return $this->belongsTo(Categoria::class);
    }

    public function tipoDespesa()
    {
        return $this->belongsTo(TipoDespesa::class);
    }

    public function scopeAtrasadas(Builder $query): Builder
    {
        return $query
            ->whereNull('data_pagamento')
            ->whereDate('data', '<', now()->toDateString());
    }


    protected $fillable = [
        'descricao',
        'valor',
        'data', // Dt. Vencimento
        'data_pagamento',
        'categoria_id',
        'tipo_despesa_id',
        'recorrencia_id',
        
    ];

    protected $casts = [
        'valor' => 'decimal:2',
        'data' => 'date',
        'data_pagamento' => 'date',
    ];


}
