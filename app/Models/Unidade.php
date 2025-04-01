<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Unidade extends Model
{
    use SoftDeletes;

    protected $table = 'unidade';
    protected $fillable = ['unid_nome','unid_sigla'];
    protected $primaryKey = 'unid_id';

    public function endereco()
    {
        return $this->hasOne(UnidadeEndereco::class, 'unid_id', 'unid_id');
    }
}
