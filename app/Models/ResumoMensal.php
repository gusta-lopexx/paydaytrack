<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ResumoMensal extends Model
{
    protected $table = 'movimentos'; // alias do fromSub

    protected $primaryKey = 'id';

    public $incrementing = false;

    protected $keyType = 'string';

    public $timestamps = false;

    protected $guarded = [];
}
