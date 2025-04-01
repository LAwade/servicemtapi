<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class UnidadeEndereco extends Model
{
    use SoftDeletes;

    protected $fillable = ['unid_id','end_id'];
    protected $table = 'unidade_endereco';
    
    public function endereco()
    {
        return $this->belongsTo(Endereco::class, 'end_id', 'end_id');
    }

    public function unidade()
    {
        return $this->belongsTo(Unidade::class, 'unid_id', 'unid_id');
    }
}
