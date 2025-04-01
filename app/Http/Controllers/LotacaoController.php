<?php

namespace App\Http\Controllers;

use App\Models\Lotacao;
use App\Models\ServidorEfetivo;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class LotacaoController extends Controller
{
    
    public function index()
    {
        try{
            $lotacao = Lotacao::with('unidade')->paginate(20);
            if (!$lotacao) {
                return response()->json(['message' => 'A lotação não foi encontrado'], 404);
            }
            return response()->json(['message' => 'As seguintes lotações foram encontradas', 'lotacao' => $lotacao], 200);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Erro ao buscar lotações', 'error' => $e->getMessage()], 500);
        }   
    }

    public function store(Request $request)
    {
        if (!$request->headers->has('Accept')) {
            $request->headers->set('Accept', 'application/json');
        }

        $valited = $request->validate([
            'pes_id' => 'required|integer',
            'unid_id' => 'required|integer',
            'lot_data_lotacao' => 'required|date',
            'lot_data_remocao' => 'required|date',
            'lot_portaria' => 'required|string',
        ]);

        try {
            $lotacao = Lotacao::where('lot_id', $request->lot_id)->first();
            if (!$lotacao) {
                $lotacao = Lotacao::create($valited);
            }
            return response()->json(['message' => 'Lotação foi criada com sucesso!', 'lotacao' => $lotacao], 201);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Erro ao cadastrar lotação', 'error' => $e->getMessage()], 500);
        }
        
    }

    public function show(string $lot_id)
    {
        try {
            $lotacao = Lotacao::with(['pessoa', 'unidade'])->where('lot_id', $lot_id)->first();
            if (!$lotacao) {
                return response()->json(['message' => 'Lotação não encontrada'], 404);
            }

            return response()->json(['message' => 'A Lotação foi encontrada!', 'lotacao' => $lotacao], 200);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Erro ao buscar lotação', 'error' => $e->getMessage()], 500);
        }
    }

    public function showByUnidade(string $unid_id)
    {
        $lotacoes = Lotacao::with(['pessoa.foto', 'unidade'])
            ->where('unid_id', $unid_id)
            ->paginate(10); // Paginação com 10 registros por página

        // Transformando os dados para incluir idade e link da foto
        $data = $lotacoes->map(function ($lotacao) {
            return [
                'nome' => $lotacao->pessoa->pes_nome,
                'idade' => Carbon::parse($lotacao->pessoa->pes_data_nascimento)->age,
                'unidade_lotacao' => $lotacao->unidade->unid_nome,
                'fotografia' => $lotacao->pessoa->foto
                    ? Storage::disk('s3')->temporaryUrl($lotacao->pessoa->foto->fp_hash, now()->addMinutes(30))
                    : null,
            ];
        });

        return response()->json([
            'data' => $data,
            'pagination' => [
                'current_page' => $lotacoes->currentPage(),
                'last_page' => $lotacoes->lastPage(),
                'per_page' => $lotacoes->perPage(),
                'total' => $lotacoes->total(),
            ],
        ]);
    }

    public function showByNome(Request $request)
    {
        $servidores = ServidorEfetivo::with('pessoa.lotacoes.unidade.endereco.endereco')->whereHas('pessoa', function ($q) use ($request) {
            $q->where('pes_nome', 'ilike', '%' . $request->pes_nome . '%');
        })->paginate(15);

        // Transformando os dados para incluir idade e link da foto
        $data = $servidores->map(function ($servidores) {
            return [
                'nome' => $servidores->pessoa->pes_nome,
                'idade' => Carbon::parse($servidores->pessoa->pes_data_nascimento)->age,
                'unidade_lotacao' => $servidores->pessoa->lotacoes->unidade->unid_nome,
                'endereco' => $servidores->pessoa->lotacoes->unidade->endereco->endereco->end_logradouro,
                'fotografia' => $servidores->pessoa->foto
                    ? Storage::disk('s3')->temporaryUrl($servidores->pessoa->foto->fp_hash, now()->addMinutes(30))
                    : null,
            ];
        });

        return response()->json([
            'data' => $data,
            'pagination' => [
                'current_page' => $servidores->currentPage(),
                'last_page' => $servidores->lastPage(),
                'per_page' => $servidores->perPage(),
                'total' => $servidores->total(),
            ],
        ]);
    }

    public function update(Request $request, string $lot_id)
    {

        if (!$request->headers->has('Accept')) {
            $request->headers->set('Accept', 'application/json');
        }

        $valited = $request->validate([
            'pes_id' => 'integer',
            'unid_id' => 'integer',
            'lot_data_lotacao' => 'date',
            'lot_data_remocao' => 'date',
            'lot_portaria' => 'string',
        ]);

        $lotacao = Lotacao::where('lot_id', $lot_id)->first();
        if (!$lotacao) {
            return response('Error', 404)->json(['message' => 'Lotação não encontrada']);
        } else {
            $lotacao->update($valited);
        }

        return response()->json(['message' => 'Lotação atualizada', 'lotacao' => $lotacao]);
    }

    public function destroy(string $lot_id)
    {
        $lotacao = Lotacao::where('lot_id', $lot_id)->first();
        if (!$lotacao) {
            return response('Error', 404)->json(['message' => 'Lotação não encontrada']);
        } else {
            $lotacao->delete();
        }
        return response()->json(['message' => 'Lotação Removida', 'lotacao' => $lotacao]);
    }
}
