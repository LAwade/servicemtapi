<?php

namespace App\Http\Controllers;

use App\Models\UnidadeEndereco;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;
use Illuminate\Database\QueryException;


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
        } catch (QueryException $e) {
            Log::channel('database_errors')->error('Erro ao buscar uniade_endereco no banco de dados', [
                'exception' => $e->getMessage(),
                'sql' => $e->getSql(),
                'bindings' => $e->getBindings(),
                'input' => $request->all(),
            ]);
            return response()->json([
                'message' => 'Não foi possível buscar o endereco da unidade',
            ], 500);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Não foi possível encontrar as unidades com endereços'
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
                'unid_id' => 'required|integer',
                'end_id' => 'required|integer'
            ]);

            $unidade = UnidadeEndereco::firstOrCreate(
                ['unid_id' => $validated['unid_id']],
                $validated
            );

            if (!$unidade) {
                return response()->json([
                    'message' => 'A unidade não foi encontrada!',
                ], 404);
            }

            return response()->json([
                'message' => $unidade->wasRecentlyCreated 
                    ? 'Unidade com endereço criada com sucesso!' 
                    : 'A unidade já possui esse endereço cadastrado.',
                'unidade' => $unidade,
            ], $unidade->wasRecentlyCreated ? 201 : 200);

        } catch (ValidationException $e) {
            return response()->json([
                'message' => 'Erro de validação',
                'errors' => $e->errors(),
            ], 422);
        } catch (QueryException $e) {
            Log::channel('database_errors')->error('Erro ao adicionar a unidade_endereco no banco de dados', [
                'exception' => $e->getMessage(),
                'sql' => $e->getSql(),
                'bindings' => $e->getBindings(),
                'input' => $request->all(),
            ]);
            return response()->json([
                'message' => 'Não foi possível cadastrar a unidade com endereço',
            ], 500);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Não foi possível cadastrar a unidade com endereço'
            ], 500);
        }
    }

    public function show(string $unid_id)
    {
        try {
            $unidade = UnidadeEndereco::where('unid_id', $unid_id)->first();
            if (!$unidade) {
                return response()->json([
                    'message' => 'A unidade e endereço não foi encontrado',
                ], 404);
            }
            return response()->json([
                'message' => 'A unidade e endereço foi encontrada!',
                'unidade' => $unidade,
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Não foi possível encontrar a unidade com endereço'
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
                'end_id' => 'required|integer'
            ]);

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
        } catch (ValidationException $e) {
            return response()->json([
                'message' => 'Erro de validação',
                'errors' => $e->errors(),
            ], 422);
        } catch (QueryException $e) {
            Log::channel('database_errors')->error('Erro ao atualizar a unidade_endereco no banco de dados', [
                'exception' => $e->getMessage(),
                'sql' => $e->getSql(),
                'bindings' => $e->getBindings(),
                'input' => $request->all(),
            ]);
            return response()->json([
                'message' => 'Não foi possível atualizar a unidade com endereço',
            ], 500);
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
        } catch (QueryException $e) {
            Log::channel('database_errors')->error('Erro ao remover a unidade_endereco no banco de dados', [
                'exception' => $e->getMessage(),
                'sql' => $e->getSql(),
                'bindings' => $e->getBindings()
            ]);
            return response()->json([
                'message' => 'Não foi possível remover o endereço da unidade'
            ], 500);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Não foi possível remover o endereço da unidade'
            ], 500);
        }
    }
}
