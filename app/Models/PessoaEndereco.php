<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PessoaEndereco extends Model
{
    use SoftDeletes;

    protected $table  = 'pessoa_endereco';
    protected $fillable = ['pes_id','end_id'];

    public function endereco()
    {
        return $this->belongsTo(Endereco::class, 'end_id', 'end_id');
    }

    public function pessoa()
    {
        return $this->belongsTo(Pessoa::class, 'pes_id', 'pes_id');
    }
}
