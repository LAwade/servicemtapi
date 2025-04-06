<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Pessoa;
use Illuminate\Validation\ValidationException;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Log;

class PessoaController extends Controller
{

    public function index()
    {
        try {
            $pessoas = Pessoa::paginate(20);
            return response()->json($pessoas, 200);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Erro ao buscar pessoas'], 500);
        }
    }

    public function store(Request $request)
    {
        if (!$request->headers->has('Accept')) {
            $request->headers->set('Accept', 'application/json');
        }

        try {
            $valited = $request->validate([
                'pes_id' => 'required|integer',
                'pes_nome' => 'required|string',
                'pes_data_nascimento' => 'required|date',
                'pes_sexo' => 'required|string',
                'pes_mae' => 'string',
                'pes_pai' => 'string',
            ]);

            $pessoa = Pessoa::where('pes_id', $request->pes_id)->first();
            if (!$pessoa) {
                $pessoa = Pessoa::create($valited);
            }

            return response()->json(['message' => 'Pessoa cadastrada com sucesso!', 'pessoa' => $pessoa], 201);
        } catch (ValidationException $e) {
            return response()->json([
                'message' => 'Erro de validação',
                'errors' => $e->errors(),
            ], 422);
        } catch (QueryException $e) {
            Log::channel('database_errors')->error('Erro ao adicionar a pessoa no banco de dados', [
                'exception' => $e->getMessage(),
                'sql' => $e->getSql(),
                'bindings' => $e->getBindings(),
                'input' => $request->all(),
            ]);
            return response()->json(['message' => 'Erro ao cadastrar pessoa'], 500);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Erro ao cadastrar pessoa'], 500);
        }
    }

    public function show(string $pes_id)
    {
        try {
            $pessoa = Pessoa::where('pes_id', $pes_id)->first();
            if (!$pessoa) {
                return response()->json(['message' => 'O registro de pessoa não foi encontrado'], 404);
            }
            return response()->json(['message' => 'Registro de pessoa foi encontrada', 'pessoa' => $pessoa], 200);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Erro ao buscar pessoa'], 500);
        }
    }

    public function update(Request $request, string $pes_id)
    {

        if (!$request->headers->has('Accept')) {
            $request->headers->set('Accept', 'application/json');
        }

        try {
            $valited = $request->validate([
                'pes_nome' => 'string',
                'pes_data_nascimento' => 'date',
                'pes_sexo' => 'string',
                'pes_mae' => 'string',
                'pes_pai' => 'string',
            ]);

            $pessoa = Pessoa::where('pes_id', $pes_id)->first();
            if (!$pessoa) {
                return response()->json(['message' => 'A pessoa não foi encontrada!'], 404);
            }
            $pessoa->update($valited);
            return response()->json(['message' => 'O registro da pessoa foi atualizada!', 'pessoa' => $pessoa], 200);
        } catch (ValidationException $e) {
            return response()->json([
                'message' => 'Erro de validação',
                'errors' => $e->errors(),
            ], 422);
        } catch (QueryException $e) {
            Log::channel('database_errors')->error('Erro ao atualizar a pessoa no banco de dados', [
                'exception' => $e->getMessage(),
                'sql' => $e->getSql(),
                'bindings' => $e->getBindings(),
                'input' => $request->all(),
            ]);
            return response()->json(['message' => 'Erro ao atualizar pessoa'], 500);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Erro ao atualizar pessoa'], 500);
        }
    }

    public function destroy(string $pes_id)
    {
        try {
            $pessoa = Pessoa::where('pes_id', $pes_id)->first();
            if (!$pessoa) {
                return response()->json(['message' => 'A pessoa informada não foi encontrada!'], 404);
            }

            $pessoa->delete();
            return response()->json(['message' => 'A pessoa infomada foi removida'], 200);
        } catch (QueryException $e) {
            Log::channel('database_errors')->error('Erro ao deletar a pessoa no banco de dados', [
                'exception' => $e->getMessage(),
                'sql' => $e->getSql(),
                'bindings' => $e->getBindings(),
                'input' => $request->all(),
            ]);
            return response()->json(['message' => 'Erro ao deletar pessoa'], 500);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Erro ao deletar pessoa'], 500);
        }
    }
}
