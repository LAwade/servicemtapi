<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ServidorEfetivo extends Model
{

    use SoftDeletes;

    protected $fillable = ['pes_id', 'se_matricula'];
    protected $table = 'servidor_efetivo';

    public function pessoa()
    {
        return $this->belongsTo(Pessoa::class, 'pes_id', 'pes_id');
    }
}
