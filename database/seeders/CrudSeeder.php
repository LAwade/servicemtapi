<?php

namespace Database\Seeders;

use App\Models\Cidade;
use App\Models\Endereco;
use App\Models\Lotacao;
use App\Models\Pessoa;
use App\Models\ServidorEfetivo;
use App\Models\ServidorTemporario;
use App\Models\Unidade;
use App\Models\UnidadeEndereco;
use Illuminate\Database\Seeder;

class CrudSeeder extends Seeder
{

    /** INPUT DATA WITH SEEDER */
    public function run(): void
    {
        $this->getCidade('Cuiabá', 'MT');
        $this->getServidor([
            'pes_nome' => 'Pedro Almeida Modesto',
            'pes_data_nascimento' => '2010-05-12',
            'pes_sexo' => 'Masculino',
            'pes_mae' => 'Carla Almeida',
            'pes_pai' => 'Julio Modesto',
            'servidorEfetivo' => [
                'se_matricula' => '201300'
            ],
            'unidade' => [
                'unid_nome' => 'Unidade do centro',
                'unid_sigla' => 'Unidade centro',
            ],
            'unidade_endereco' => [
                'end_tipo_logradouro' => 'Avenida do CPA',
                'end_logradouro' => 'Avenida',
                'end_numero' => '2676',
                'end_bairro' => 'CPA',
                'cid_id' => $this->getCidade('Cuiabá')->cid_id,
            ],
            'lotacao' => [
                'lot_data_lotacao' => '2012-01-23',
                'lot_data_remocao' => '2019-01-12',
                'lot_portaria' => 'Portaria nº 2676/2000 em Diário Oficial do Estado do Mato Grosso',
            ],
        ], 'efeivo');

        $this->getServidor([
            'pes_nome' => 'Guilherme da silva',
            'pes_data_nascimento' => '2005-02-22',
            'pes_sexo' => 'Masculino',
            'pes_mae' => 'Margarete da Silva',
            'pes_pai' => 'Julio Costesto',
            'servidorTemporario' => [
                'st_data_admissao' => '2023-10-02',
                'st_data_demissao' => '2024-12-10',
            ],
            'unidade' => [
                'unid_nome' => 'Unidade do centro',
                'unid_sigla' => 'Unidade centro',
            ],
            'unidade_endereco' => [
                'end_tipo_logradouro' => 'Avenida do CPA',
                'end_logradouro' => 'Avenida',
                'end_numero' => '2676',
                'end_bairro' => 'CPA',
                'cid_id' => $this->getCidade('Cuiabá')->cid_id,
            ],
            'lotacao' => [
                'lot_data_lotacao' => '2015-08-22',
                'lot_data_remocao' => '2019-11-10',
                'lot_portaria' => 'Portaria nº 2676/2000 em Diário Oficial do Estado do Mato Grosso',
            ],
        ], 'temporario');

        $this->getServidor([
            'pes_nome' => 'Kathy Perez',
            'pes_data_nascimento' => '1990-01-15',
            'pes_sexo' => 'Feminino',
            'pes_mae' => 'Helena da Cruz Perez',
            'pes_pai' => 'Marcus da Costa Pinheiro',
            'servidorEfetivo' => [
                'se_matricula' => '12145'
            ],
            'unidade' => [
                'unid_nome' => 'Unidade do centro',
                'unid_sigla' => 'Unidade centro',
            ],
            'unidade_endereco' => [
                'end_tipo_logradouro' => 'Rua',
                'end_logradouro' => 'Rua Rio de Janeiro',
                'end_numero' => '456',
                'end_bairro' => 'Jardim Alvorada',
                'cid_id' => $this->getCidade('Cuiabá')->cid_id,
            ],
            'lotacao' => [
                'lot_data_lotacao' => '2011-10-09',
                'lot_data_remocao' => '2023-01-25',
                'lot_portaria' => 'Portaria nº 456/2012 em Diário Oficial do Estado do Mato Grosso',
            ],
        ], 'efetivo');

        $this->getServidor([
            'pes_nome' => 'Fasto Geraldo',
            'pes_data_nascimento' => '1988-07-20',
            'pes_sexo' => 'Masculino',
            'pes_mae' => 'Ana Castelo Marques',
            'pes_pai' => 'Roberto Geraldo',
            'servidorEfetivo' => [
                'se_matricula' => '32216'
            ],
            'unidade' => [
                'unid_nome' => 'Unidade Centro Norte',
                'unid_sigla' => 'Unidade Centro Norte',
            ],
            'unidade_endereco' => [
                'end_tipo_logradouro' => 'Avenida CPA',
                'end_logradouro' => 'Avenida Brasil',
                'end_numero' => '345',
                'end_bairro' => 'Centro Norte',
                'cid_id' => $this->getCidade('Várzea Grande')->cid_id,
            ],
            'lotacao' => [
                'lot_data_lotacao' => '2000-03-10',
                'lot_data_remocao' => '2019-05-21',
                'lot_portaria' => 'Portaria nº 345/2010 em Diário Oficial do Estado do Mato Grosso',
            ],
        ], 'efetivo');

        $this->getServidor([
            'pes_nome' => 'Adalbrto Fagundes',
            'pes_data_nascimento' => '1988-02-27',
            'pes_sexo' => 'Masculino',
            'pes_mae' => 'Josefa Fagundes e Silva',
            'pes_pai' => 'Amarildo Pereira',
            'servidorTemporario' => [
                'st_data_admissao' => '2022-04-07',
                'st_data_demissao' => '2024-02-13',
            ],
            'unidade' => [
                'unid_nome' => 'Unidade do Centro',
                'unid_sigla' => 'Unidade do Centro',
            ],
            'unidade_endereco' => [
                'end_tipo_logradouro' => 'Avenida do CPA',
                'end_logradouro' => 'Avenida',
                'end_numero' => '345',
                'end_bairro' => 'CPA',
                'cid_id' => $this->getCidade('Cuiabá')->cid_id,
            ],
            'lotacao' => [
                'lot_data_lotacao' => '2023-01-10',
                'lot_data_remocao' => '2025-06-12',
                'lot_portaria' => 'Portaria nº 345/2021 em Diário Oficial do Estado do Mato Grosso',
            ],
        ], 'temporario');

        $this->getServidor([
            'pes_nome' => 'Manuel Henrique Flores',
            'pes_data_nascimento' => '1992-02-22',
            'pes_sexo' => 'Masculino',
            'pes_mae' => 'Elizabete Flores',
            'pes_pai' => 'Jorge Campos',
            'servidorTemporario' => [
                'st_data_admissao' => '2021-12-15',
                'st_data_demissao' => '2025-01-08',
            ],
            'unidade' => [
                'unid_nome' => 'Unidade Centro',
                'unid_sigla' => 'Unidade Centro',
            ],
            'unidade_endereco' => [
                'end_tipo_logradouro' => 'Rua B',
                'end_logradouro' => 'Rua Espanha',
                'end_numero' => '987',
                'end_bairro' => 'Jardim Europa',
                'cid_id' => $this->getCidade('Cuiabá')->cid_id,
            ],
            'lotacao' => [
                'lot_data_lotacao' => '2022-03-20',
                'lot_data_remocao' => '2024-08-01',
                'lot_portaria' => 'Portaria nº 987/2021 em Diário Oficial do Estado do Mato Grosso',
            ],
        ], 'temporario');
    }

    public function getCidade($nome, $uf = 'MT')
    {
        $cidade = Cidade::where('cid_nome', $nome)->first();
        if (!$cidade) {
            $cidade = Cidade::create([
                'cid_nome' => $nome,
                'cid_uf' => $uf,
            ]);
        }
        return $cidade;
    }

    public function getServidor($data, $tipoServidor)
    {
        $pessoa = Pessoa::where('pes_nome', $data['pes_nome'])->first();
        if (!$pessoa) {
            $pessoa = Pessoa::create([
                'pes_nome' => $data['pes_nome'],
                'pes_data_nascimento' => $data['pes_data_nascimento'],
                'pes_sexo' => $data['pes_sexo'],
                'pes_mae' => $data['pes_mae'],
                'pes_pai' => $data['pes_pai'],
            ]);
        }

        switch ($tipoServidor) {
            case 'efetivo':
                $this->getServidorEfetivo($pessoa->pes_id, $data['servidorEfetivo']);
                break;
            case 'temporario':
                $this->getServidorTemporario($pessoa->pes_id, $data['servidorTemporario']);
                break;
            default:
                break;
        }

        $unidade = $this->getUnidade($data['unidade']);
        $endereco = $this->getEndereco($data['unidade_endereco']);
        $this->getUnidadeEndereco($unidade->unid_id, $endereco->end_id);
        $this->getLotacao($pessoa->pes_id, $unidade->unid_id, $data['lotacao']);
    }

    public function getServidorEfetivo($pes_id, $data)
    {
        $servidor = ServidorEfetivo::where('pes_id', $pes_id)->first();
        if (!$servidor) {
            $servidor = ServidorEfetivo::create([
                'pes_id' => $pes_id,
                'se_matricula' => $data['se_matricula'],
            ]);
        }
        return $servidor;
    }

    public function getServidorTemporario($pes_id, $data)
    {
        $servidor = ServidorTemporario::where('pes_id', $pes_id)->first();
        if (!$servidor) {
            $servidor = ServidorTemporario::create([
                'pes_id' => $pes_id,
                'st_data_admissao' => $data['st_data_admissao'],
                'st_data_demissao' => $data['st_data_demissao'],
            ]);
        }
        return $servidor;
    }

    public function getUnidade($data)
    {
        $unidade = Unidade::where('unid_nome', $data['unid_nome'])->first();
        if (!$unidade) {
            $unidade = Unidade::create([
                'unid_nome' => $data['unid_nome'],
                'unid_sigla' => $data['unid_sigla'],
            ]);
        }
        return $unidade;
    }

    public function getEndereco($data)
    {
        $endereco = Endereco::where('end_logradouro', $data['end_logradouro'])->first();
        if (!$endereco) {
            $endereco = Endereco::create([
                'end_tipo_logradouro' => $data['end_tipo_logradouro'],
                'end_logradouro' => $data['end_logradouro'],
                'end_numero' => $data['end_numero'],
                'end_bairro' => $data['end_bairro'],
                'cid_id' => $data['cid_id'],
            ]);
        }
        return $endereco;
    }

    public function getUnidadeEndereco($unid_id, $end_id)
    {
        $uniEnd = UnidadeEndereco::where('unid_id', $unid_id)->first();
        if (!$uniEnd) {
            $uniEnd = UnidadeEndereco::create([
                'unid_id' => $unid_id,
                'end_id' => $end_id,
            ]);
        }
        return $uniEnd;
    }

    public function getLotacao($pes_id, $unid_id, $data)
    {
        $lotacao = Lotacao::where('pes_id', $pes_id)->first();
        if (!$lotacao) {
            $lotacao = Lotacao::create([
                'pes_id' => $pes_id,
                'unid_id' => $unid_id,
                'lot_data_lotacao' => $data['lot_data_lotacao'],
                'lot_data_remocao' => $data['lot_data_remocao'],
                'lot_portaria' => $data['lot_portaria'],
            ]);
        }
        return $lotacao;
    }
}
