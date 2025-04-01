<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;
use Illuminate\Database\Eloquent\SoftDeletes;

class Pessoa extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $primaryKey = 'pes_id';
    protected $table  = 'pessoa';
    protected $fillable = ['pes_nome', 'pes_data_nascimento', 'pes_sexo', 'pes_mae', 'pes_pai'];

    public function servidorEfetivo()
    {
        return $this->hasOne(ServidorEfetivo::class, 'pes_id', 'pes_id');
    }

    public function foto()
    {
        return $this->hasOne(FotoPessoa::class, 'pes_id', 'pes_id');
    }

    public function enderecos()
    {
        return $this->hasMany(PessoaEndereco::class, 'pes_id', 'pes_id');
    }
    
    public function servidorTemporario()
    {
        return $this->hasOne(ServidorTemporario::class, 'pes_id', 'pes_id');
    }

    public function lotacoes()
    {
        return $this->hasOne(Lotacao::class, 'pes_id', 'pes_id');
    }
    
}
