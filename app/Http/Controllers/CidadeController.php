<?php

namespace App\Http\Controllers;

use App\Models\Cidade;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Log;

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

        try {
            $valited = $request->validate([
                'cid_nome' => 'required|string',
                'cid_uf' => 'required|string',
            ]);

            $cidade = Cidade::where('cid_nome', $request->cid_nome)->first();
            if (!$cidade) {
                $cidade = Cidade::create($valited);
            }
            return response()->json(['message' => 'Cidade cadastrada com sucesso!', 'cidade' => $cidade], 200);
        } catch (ValidationException $e) {
            return response()->json([
                'message' => 'Erro de validação',
                'errors' => $e->errors(),
            ], 422);
        } catch (QueryException $e) {
            Log::channel('database_errors')->error('Erro ao adicionar a cidade no banco de dados', [
                'exception' => $e->getMessage(),
                'sql' => $e->getSql(),
                'bindings' => $e->getBindings(),
                'input' => $request->all(),
            ]);
            return response()->json(['message' => 'Erro ao cadastrar cidade'], 500);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Erro ao cadastrar cidade'], 500);
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

        try {
            $valited = $request->validate([
                'cid_nome' => 'required|string',
                'cid_uf' => 'required|string',
            ]);

            $cidade = Cidade::where('cid_id', $cid_id)->first();
            if (!$cidade) {
                return response()->json(['message' => 'Cidade não foi encontrado'], 404);
            }

            $cidade->update($valited);
            return response()->json(['message' => 'O registro da cidade foi atualizada', 'cidade' => $cidade], 200);
        } catch (ValidationException $e) {
            return response()->json([
                'message' => 'Erro de validação',
                'errors' => $e->errors(),
            ], 422);
        } catch (QueryException $e) {
            Log::channel('database_errors')->error('Erro ao atualizar a cidade no banco de dados', [
                'exception' => $e->getMessage(),
                'sql' => $e->getSql(),
                'bindings' => $e->getBindings(),
                'input' => $request->all(),
            ]);
            return response()->json(['message' => 'Erro ao atualizar cidade'], 500);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Erro ao atualizar cidade'], 500);
        }
    }

    public function destroy(string $cid_id)
    {
        try {
            $cidade = Cidade::where('cid_id', $cid_id)->first();
            if (!$cidade) {
                return response()->json(['message' => 'Cidade não foi encontrada'], 404);
            }
            $cidade->delete();
            return response()->json(['message' => 'Registro foi removido', 'cidade' => $cidade], 200);
        } catch (QueryException $e) {
            Log::channel('database_errors')->error('Erro ao deletar a cidade no banco de dados', [
                'exception' => $e->getMessage(),
                'sql' => $e->getSql(),
                'bindings' => $e->getBindings()
            ]);
            return response()->json(['message' => 'Erro ao buscar/deleção da cidade'], 500);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Erro ao buscar/deleção da cidade'], 500);
        }
    }
}
