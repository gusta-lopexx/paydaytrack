<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Entrada extends Model
{
    use HasFactory;

    protected $fillable = [
        'descricao',
        'valor',
        'data',
        'categoria_id',
        'tipo_despesa_id',
        'recorrencia_id',
    ];

    protected $casts = [
        'valor' => 'decimal:2',
        'data' => 'date',
    ];

    // Relacionamentos
    public function categoria()
    {
        return $this->belongsTo(Categoria::class);
    }

    public function tipoDespesa()
    {
        return $this->belongsTo(TipoDespesa::class);
    }
}
