<?php

namespace App\Http\Controllers;

use App\Models\Unidade;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class UnidadeController extends Controller
{
    public function index()
    {
        try {
            $unidade = Unidade::paginate(20);
            return response()->json([
                'message' => 'As unidades foram encontradas!',
                'unidade' => $unidade,
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Não foi possível encontrar as unidades',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'unid_nome' => 'required|string',
            'unid_sigla' => 'required|string',
        ]);

        try {
            $unidade = Unidade::where('unidade_id', $request->unidade_id)->first();
            if (!$unidade) {
                $unidade = Unidade::create($validated);
            }

            return response()->json([
                'message' => 'A unidade foi cadastrada com sucesso!',
                'unidade' => $unidade,
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Não foi possível cadastrar a unidade',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    public function show(string $unidade_id)
    {
        try {
            $unidade = Unidade::where('unidade_id', $unidade_id)->first();
            if (!$unidade) {
                return response('Não encontrado', 404)->json([
                    'message' => 'A Unidade não foi encontrada!',
                ], 404);
            }
            return response()->json([
                'message' => 'Unidade foi encontrada!',
                'unidade' => $unidade,
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Não foi possível encontrar a unidade',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    public function update(Request $request, string $unidade_id)
    {

        $validated = $request->validate([
            'st_data_admissao' => 'string',
            'st_data_demissao' => 'string',
        ]);

        try {
            $unidade = Unidade::where('unidade_id', $unidade_id)->first();
            if (!$unidade) {
                return response()->json([
                    'message' => 'A unidade não foi encontrada!',
                ], 404);
            }
            $unidade->update($validated);

            return response()->json([
                'message' => 'A unidade foi atualizada!',
                'unidade' => $unidade,
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Não foi possível atualizar a unidade',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    public function destroy(string $unidade_id)
    {
        try {
            $unidade = Unidade::where('unidade_id', $unidade_id)->first();
            if (!$unidade) {
                return response()->json([
                    'message' => 'A unidade não foi encontrada!',
                ], 404);
            }
            $unidade->delete();

            return response()->json(['message' => 'A unidade foi removida com sucesso!',]);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Não foi possível remover a unidade',
                'error' => $e->getMessage(),
            ], 500);
        }
    }
}
