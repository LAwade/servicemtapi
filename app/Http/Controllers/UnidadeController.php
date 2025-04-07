<?php

namespace App\Http\Controllers;

use App\Models\Unidade;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Log;

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
                'message' => 'Não foi possível encontrar as unidades'
            ], 500);
        }
    }

    public function store(Request $request)
    {
        if (!$request->headers->has('Accept')) {
            $request->headers->set('Accept', 'application/json');
        }

        try {
            $validated = $request->validate([
                'unid_nome' => 'required|string',
                'unid_sigla' => 'required|string',
            ]);

            $unidade = Unidade::where('unid_id', $request->unid_id)->first();
            if (!$unidade) {
                $unidade = Unidade::create($validated);
            }

            return response()->json([
                'message' => 'A unidade foi cadastrada com sucesso!',
                'unidade' => $unidade,
            ], 200);
        } catch (ValidationException $e) {
            return response()->json([
                'message' => 'Erro de validação',
                'errors' => $e->errors(),
            ], 422);
        } catch (QueryException $e) {
            Log::channel('database_errors')->error('Erro ao adicionar a unidade no banco de dados', [
                'exception' => $e->getMessage(),
                'sql' => $e->getSql(),
                'bindings' => $e->getBindings(),
                'input' => $request->all(),
            ]);
            return response()->json([
                'message' => 'Não foi possível cadastrar a unidade',
            ], 500);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Não foi possível cadastrar a unidade'
            ], 500);
        }
    }

    public function show(string $unid_id)
    {
        try {
            $unidade = Unidade::where('unid_id', $unid_id)->first();
            if (!$unidade) {
                return response()->json([
                    'message' => 'A Unidade não foi encontrada!',
                ], 404);
            }
            return response()->json([
                'message' => 'Unidade foi encontrada!',
                'unidade' => $unidade,
            ], 200);
        } catch (QueryException $e) {
            Log::channel('database_errors')->error('Erro ao buscar a unidade no banco de dados', [
                'exception' => $e->getMessage(),
                'sql' => $e->getSql(),
                'bindings' => $e->getBindings(),
                'input' => $unidade_id,
            ]);
            return response()->json([
                'message' => 'Não foi possível encontrar a unidade',
            ], 500);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Não foi possível encontrar a unidade'
            ], 500);
        }
    }

    public function update(Request $request, string $unid_id)
    {
        if (!$request->headers->has('Accept')) {
            $request->headers->set('Accept', 'application/json');
        }

        try {
            $validated = $request->validate([
                'unid_nome' => 'string',
                'unid_sigla' => 'string',
            ]);

            $unidade = Unidade::where('unid_id', $unid_id)->first();
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
        } catch (ValidationException $e) {
            return response()->json([
                'message' => 'Erro de validação',
                'errors' => $e->errors(),
            ], 422);
        } catch (QueryException $e) {
            Log::channel('database_errors')->error('Erro ao atualizar a unidade no banco de dados', [
                'exception' => $e->getMessage(),
                'sql' => $e->getSql(),
                'bindings' => $e->getBindings(),
                'input' => $request->all(),
            ]);
            return response()->json([
                'message' => 'Não foi possível atualizar a unidade'
            ], 500);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Não foi possível atualizar a unidade'
            ], 500);
        }
    }

    public function destroy(string $unid_id)
    {
        try {
            $unidade = Unidade::where('unid_id', $unid_id)->first();
            if (!$unidade) {
                return response()->json([
                    'message' => 'A unidade não foi encontrada!',
                ], 404);
            }
            $unidade->delete();

            return response()->json(['message' => 'A unidade foi removida com sucesso!'], 200);
        } catch (QueryException $e) {
            Log::channel('database_errors')->error('Erro ao remover a unidade no banco de dados', [
                'exception' => $e->getMessage(),
                'sql' => $e->getSql(),
                'bindings' => $e->getBindings()
            ]);
            return response()->json([
                'message' => 'Não foi possível remover a unidade',
            ], 500);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Não foi possível remover a unidade'
            ], 500);
        }
    }
}
