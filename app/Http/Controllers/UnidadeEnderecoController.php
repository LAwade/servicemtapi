<?php

namespace App\Http\Controllers;

use App\Models\UnidadeEndereco;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class UnidadeEnderecoController extends Controller
{

    public function index()
    {
        try {
            $unidade = UnidadeEndereco::paginate(20);
            return response()->json([
                'message' => 'As unidades e endereços foram encontradas',
                'unidade' => $unidade,
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Não foi possível encontrar as unidades com endereços',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'unid_id' => 'required',
            'end_id' => 'required',
        ]);

        try {
            $unidade = UnidadeEndereco::firstOrCreate(
                ['unid_id' => $validated['unid_id']],
                $validated
            );
    
            return response()->json([
                'message' => $unidade->wasRecentlyCreated 
                    ? 'Unidade com endereço criada com sucesso!' 
                    : 'A unidade já possui esse endereço cadastrado.',
                'unidade' => $unidade,
            ], 201);

        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Não foi possível cadastrar a unidade com endereço',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    public function show(string $unid_id)
    {
        try {
            $unidade = UnidadeEndereco::where('unid_id', $unid_id)->first();
            if (!$unidade) {
                return response('Não encontrado', 404)->json([
                    'message' => 'A unidade e endereço não foi encontrado',
                ]);
            }
            return response()->json([
                'message' => 'A unidade e endereço foi encontrada!',
                'unidade' => $unidade,
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Não foi possível encontrar a unidade com endereço',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    public function update(Request $request, string $unid_id)
    {
        $validated = $request->validate([
            'st_data_admissao' => 'string',
            'st_data_demissao' => 'string',
        ]);

        try {
            $unidade = UnidadeEndereco::where('unid_id', $unid_id)->first();
            if (!$unidade) {
                return response()->json([
                    'message' => 'A unidade fornecida não encontrada!',
                ], 404);
            }
            $unidade->update($validated);

            return response()->json([
                'message' => 'O endereço da unidade foi atualizado com sucesso!',
                'unidade' => $unidade,
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Não foi possível atualizar a unidade com endereço',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    public function destroy(string $unid_id)
    {
        try {
            $unidade = UnidadeEndereco::where('unid_id', $unid_id)->first();
            if (!$unidade) {
                return response()->json([
                    'message' => 'A unidade não foi encontrada!',
                ], 404);
            }
            $unidade->delete();

            return response()->json(['message' => 'O endereço foi removido da unidade!'], 200);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Não foi possível remover o endereço da unidade',
                'error' => $e->getMessage(),
            ], 500);
        }
    }
}
