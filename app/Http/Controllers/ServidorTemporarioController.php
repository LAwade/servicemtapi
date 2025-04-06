<?php

namespace App\Http\Controllers;

use App\Models\ServidorTemporario;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;
use Illuminate\Database\QueryException;

class ServidorTemporarioController extends Controller
{

    public function index()
    {
        try {
            $servidor = ServidorTemporario::with('pes_id')->paginate(20);
            return response()->json([
                'message' => 'Os servidores foram encontrados',
                'servidor' => $servidor,
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Não foi possível encontrar os servidores'
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
                'pes_id' => 'required',
                'st_data_admissao' => 'required|date',
                'st_data_demissao' => 'required|date',
            ]);

            $servidor = ServidorTemporario::where('pes_id', $request->pes_id)->first();
            if (!$servidor) {
                $servidor = ServidorTemporario::create($validated);
            }

            return response()->json([
                'message' => 'O servidor temporario foi cadastrado!',
                'servidor' => $servidor,
            ], 201);
        } catch (ValidationException $e) {
            return response()->json([
                'message' => 'Erro de validação',
                'errors' => $e->errors(),
            ], 422);
        } catch (QueryException $e) {
            Log::channel('database_errors')->error('Erro ao adicionar o servidor temporario no banco de dados', [
                'exception' => $e->getMessage(),
                'sql' => $e->getSql(),
                'bindings' => $e->getBindings(),
                'input' => $request->all(),
            ]);
            return response()->json([
                'message' => 'Erro ao cadastrar o servidor temporario'
            ], 500);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Não foi possível cadastrar o servidor'
            ], 500);
        }
    }

    public function show(string $pes_id)
    {
        try {
            $servidor = ServidorTemporario::with('pessoa')->where('pes_id', $pes_id)->first();
            if (!$servidor) {
                return response()->json([
                    'message' => 'O servidor não foi encontrado!',
                ], 404);
            }

            return response()->json([
                'message' => 'Registro do servidor foi encontrado',
                'servidor' => $servidor,
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Não foi possível encontrar o servidor'
            ], 500);
        }
    }

    public function update(Request $request, string $pes_id)
    {

        if (!$request->headers->has('Accept')) {
            $request->headers->set('Accept', 'application/json');
        }

        try {

            $validated = $request->validate([
                'st_data_admissao' => 'date',
                'st_data_demissao' => 'date',
            ]);

            $servidor = ServidorTemporario::with('pessoa')->where('pes_id', $pes_id)->first();
            if (!$servidor) {
                return response()->json([
                    'message' => 'O servidor não foi encontrado!',
                ], 404);
            }
            $servidor->update($validated);

            return response()->json([
                'message' => 'Servidor atualizado com sucesso!',
                'servidor' => $servidor,
            ], 200);
        } catch (ValidationException $e) {
            return response()->json([
                'message' => 'Erro de validação',
                'errors' => $e->errors(),
            ], 422);
        } catch (QueryException $e) {
            Log::channel('database_errors')->error('Erro ao atualizar o servidor no banco de dados', [
                'exception' => $e->getMessage(),
                'sql' => $e->getSql(),
                'bindings' => $e->getBindings(),
                'input' => $request->all(),
            ]);
            return response()->json([
                'message' => 'Erro ao atualizar o servidor'
            ], 500);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Não foi possível atualizar o servidor'
            ], 500);
        }
    }

    public function destroy(string $pes_id)
    {
        try {
            $servidor = ServidorTemporario::with('pessoa')->where('pes_id', $pes_id)->first();
            if (!$servidor) {
                return response()->json([
                    'message' => 'O servidor não foi encontrado!',
                ], 404);
            }
            $servidor->delete();

            return response()->json(['message' => 'O servidor foi removido com sucesso!'], 200);
        } catch (QueryException $e) {
            Log::channel('database_errors')->error('Erro ao remover o servidor no banco de dados', [
                'exception' => $e->getMessage(),
                'sql' => $e->getSql(),
                'bindings' => $e->getBindings(),
                'input' => $request->all(),
            ]);
            return response()->json([
                'message' => 'Erro ao remover o servidor'
            ], 500);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Não foi possível remover o servidor'
            ], 500);
        }
    }
}
