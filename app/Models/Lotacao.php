<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Lotacao extends Model
{

    use SoftDeletes;

    protected $table  = 'lotacao';
    protected $primaryKey = 'lot_id';
    protected $fillable = ['pes_id','unid_id','lot_data_lotacao','lot_data_remocao','lot_portaria'];

    public function unidade()
    {
        return $this->belongsTo(Unidade::class, 'unid_id', 'unid_id');
    }
    public function pessoa()
    {
        return $this->belongsTo(Pessoa::class, 'pes_id', 'pes_id');
    }
}
