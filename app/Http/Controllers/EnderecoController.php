<?php

namespace App\Http\Controllers;

use App\Models\Endereco;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Log;

class EnderecoController extends Controller
{
    
    public function index()
    {
        $endereco = Endereco::paginate(20);
        if (!$endereco) {
            return response('O endereço não foi encontrado', 404)->json();
        }
        return response()->json(['message' => 'Os seguintes endereços foram encontrados', 'endereco' => $endereco], 200);
    }

    public function store(Request $request)
    {
        if (!$request->headers->has('Accept')) {
            $request->headers->set('Accept', 'application/json');
        }

        try {
            $valited = $request->validate([
                'end_tipo_logradouro' => 'required|string',
                'end_logradouro' => 'required|string',
                'end_numero' => 'required|string',
                'end_bairro' => 'required|string',
                'cid_id' => 'required|integer',
            ]);
    
            $endereco = Endereco::where('end_id', $request->end_id)->first();
            if (!$endereco) {
                $endereco = Endereco::create($valited);
            }
    
            return response()->json(['message' => 'O Endereço foi cadastrado com sucesso!', 'endereco' => $endereco], 200);
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
            return response()->json(['message' => 'Erro ao cadastrar endereço'], 500);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Erro ao cadastrar endereço'], 500);
        }

    }

    public function show(string $end_id)
    {
        try {
            $endereco = Endereco::where('end_id', $end_id)->first();

            if (!$endereco) {
                return response()->json(['message' => 'Endereço não foi encontrado'], 404);
            }
            return response()->json(['message' => 'O endereço foi encontrado', 'endereco' => $endereco], 200);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Erro ao buscar endereço', 'error' => $e->getMessage()], 500);
        }

    }

    public function update(Request $request, string $end_id)
    {

        if (!$request->headers->has('Accept')) {
            $request->headers->set('Accept', 'application/json');
        }

        try {
            $valited = $request->validate([
                'end_tipo_logradouro' => 'string',
                'end_logradouro' => 'string',
                'end_numero' => 'string',
                'end_bairro' => 'string',
                'cid_id' => 'integer',
            ]);
    
            $endereco = Endereco::where('end_id', $end_id)->first();
            if (!$endereco) {
                return response('Error', 404)->json(['message' => 'O endereço não foi encontrado!']);
            } else {
                $endereco->update($valited);
            }
    
            return response()->json(['message' => 'O registro do endereço foi atualizado', 'endereco' => $endereco]);
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
            return response()->json(['message' => 'Erro ao cadastrar endereço'], 500);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Erro ao cadastrar endereço'], 500);
        }
        
    }

    public function destroy(string $end_id)
    {
        try {
            $endereco = Endereco::where('end_id', $end_id)->first();
            if (!$endereco) {
                return response()->json(['message' => 'O endereço não foi encontrado.'], 404);
            }
            $endereco->delete();

            return response()->json(['message' => 'O registro do endereço foi removido', 'endereco' => $endereco]);
        } catch (QueryException $e) {
            Log::channel('database_errors')->error('Erro ao deletar a cidade no banco de dados', [
                'exception' => $e->getMessage(),
                'sql' => $e->getSql(),
                'bindings' => $e->getBindings()
            ]);
            return response()->json(['message' => 'Erro ao deletar endereço'], 500);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Erro ao deletar endereço'], 500);
        }
    }
}
