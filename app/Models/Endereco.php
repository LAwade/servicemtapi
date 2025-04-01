<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Endereco extends Model
{
    use SoftDeletes;

    protected $table  = 'endereco';
    protected $primaryKey = 'end_id';

    protected $fillable = ['end_tipo_logradouro','end_logradouro','end_numero','end_bairro','cid_id'];
}
