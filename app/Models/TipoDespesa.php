<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TipoDespesa extends Model
{
    protected $table = 'tipo_despesas';

    protected $fillable = [
        'nome',
    ];

    public function gastos()
    {
        return $this->hasMany(Gasto::class);
    }



}
