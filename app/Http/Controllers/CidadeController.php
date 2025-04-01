<?php

namespace App\Http\Controllers;

use App\Models\Cidade;
use Illuminate\Http\Request;
use OpenApi\Annotations as OA;

class CidadeController extends Controller
{

    public function index()
    {
        $cidade = Cidade::paginate(20);
        if (!$cidade) {
            return response()->json(['message' => 'Nenhum registro encontrado'], 404);
        }

        return response()->json([
            'message' => 'Foram encontrados os registros',
            'cidades' => $cidade,
        ], 200);
    }

    public function store(Request $request)
    {
        if (!$request->headers->has('Accept')) {
            $request->headers->set('Accept', 'application/json');
        }

        $valited = $request->validate([
            'cid_nome' => 'required|string',
            'cid_uf' => 'required|string',
        ]);

        try {
            $cidade = Cidade::where('cid_nome', $request->cid_nome)->first();
            if (!$cidade) {
                $cidade = Cidade::create($valited);
            }
            return response()->json(['message' => 'Cidade cadastrada com sucesso!', 'cidade' => $cidade], 200);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Erro ao cadastrar cidade', 'error' => $e->getMessage()], 500);
        } 
    }

    public function show(string $cid_id)
    {

        try {
            $cidade = Cidade::where('cid_id', $cid_id)->first();

            if (!$cidade) {
                return response()->json(['message' => 'A cidade informada não foi encontrado'], 404);
            }
            return response()->json(['message' => 'Cidade foi encontrada', 'cidade' => $cidade], 200);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Erro ao buscar cidade', 'error' => $e->getMessage()], 500);
        }
    }

    public function update(Request $request, string $cid_id)
    {

        if (!$request->headers->has('Accept')) {
            $request->headers->set('Accept', 'application/json');
        }

        $valited = $request->validate([
            'cid_nome' => 'string',
            'cid_uf' => 'string',
        ]);

        try {
            $cidade = Cidade::where('cid_id', $cid_id)->first();
            if (!$cidade) {
                return response()->json(['message' => 'Usuário não encontrado'], 404);
            }

            $cidade->update($valited);
            return response()->json(['message' => 'O registro da cidade foi atualizada', 'cidade' => $cidade], 200);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Erro ao atualizar cidade', 'error' => $e->getMessage()], 500);
        }
    }

    public function destroy(string $cid_id)
    {
        try {
            $cidade = Cidade::where('cid_id', $cid_id)->first();
            if (!$cidade) {
                return response('Error', 404)->json(['message' => 'Usuário não encontrado']);
            }
            $cidade->delete();
            return response()->json(['message' => 'Registro foi removido', 'cidade' => $cidade], 200);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Erro ao buscar/deleção da cidade', 'error' => $e->getMessage()], 500);
        }
    }
}
