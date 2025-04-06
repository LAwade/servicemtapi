<?php

namespace App\Http\Controllers;

use App\Models\PessoaEndereco;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Log;

class PessoaEnderecoController extends Controller
{

    public function index()
    {
        try {
            $pessoaEndereco = PessoaEndereco::with(['pessoa', 'endereco'])->paginate(20);
            if (!$pessoaEndereco) {
                return response()->json(['message' => 'Nenhum registro encontrado'], 404);
            }
            return response()->json(['message' => 'Os Endereços das Pessoas foram encontrados', 'pessoa_endereco' => $pessoaEndereco], 200);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Erro ao buscar pessoas endereços'], 500);
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
                'end_id' => 'required|integer',
            ]);

            $pessoaEndereco = PessoaEndereco::where('end_id', $request->end_id)->first();
            if ($pessoaEndereco) {
                return response()->json(['message' => 'Endereço já vinculado a outra pessoa'], 400);
            }

            $pessoaEndereco = PessoaEndereco::where('pes_id', $request->pes_id)->first();
            if (!$pessoaEndereco) {
                $pessoaEndereco = PessoaEndereco::create($valited);
            }

            return response()->json(['message' => 'Vinculo cadastrado com sucesso', 'pessoa_endereco' => $pessoaEndereco], 200);
        } catch (ValidationException $e) {
            return response()->json([
                'message' => 'Erro de validação',
                'errors' => $e->errors(),
            ], 422);
        } catch (QueryException $e) {
            Log::channel('database_errors')->error('Erro ao adicionar o vinculo no banco de dados', [
                'exception' => $e->getMessage(),
                'sql' => $e->getSql(),
                'bindings' => $e->getBindings(),
                'input' => $request->all(),
            ]);
            return response()->json(['message' => 'Erro ao vincular endereço a pessoa'], 500);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Erro ao vincular endereço a pessoa'], 500);
        }
    }

    public function show(string $pes_id)
    {
        try {
            $pessoaEndereco = PessoaEndereco::with(['pessoa', 'endereco'])->where('pes_id', $pes_id)->orWhere('end_id', $pes_id)->first();

            if (!$pessoaEndereco) {
                return response()->json(['message' => 'O endereço da pessoa não foi encontrado'], 404);
            }
            return response()->json(['message' => 'O endereço da pessoa foi encontrado', 'pessoa_endereco' => $pessoaEndereco], 200);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Erro ao buscar o endereço da pessoa'], 500);
        }
        
    }

    public function update(Request $request, string $pes_id)
    {

        if (!$request->headers->has('Accept')) {
            $request->headers->set('Accept', 'application/json');
        }

        try {
            $valited = $request->validate([
                'end_id' => 'integer',
            ]);

            $pessoaEndereco = PessoaEndereco::where('pes_id', $pes_id)->first();
            if (!$pessoaEndereco) {
                return response()->json(['message' => 'O endereço da pessoa não foi encontrada'], 404);
            }
            $pessoaEndereco->update($valited);

            return response()->json(['message' => 'Pessoa Endereço atualizada', 'pessoa_endereco' => $pessoaEndereco], 200);
        } catch (ValidationException $e) {
            return response()->json([
                'message' => 'Erro de validação',
                'errors' => $e->errors(),
            ], 422);
        } catch (QueryException $e) {
            Log::channel('database_errors')->error('Erro ao atualizar o endereço da pessoa no banco de dados', [
                'exception' => $e->getMessage(),
                'sql' => $e->getSql(),
                'bindings' => $e->getBindings(),
                'input' => $request->all(),
            ]);
            return response()->json(['message' => 'Erro ao atualizar o endereço da pessoa'], 500);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Erro ao atualizar o endereço da pessoa'], 500);
        }
    }

    public function destroy(string $pes_id)
    {

        try {
            $pessoaEndereco = PessoaEndereco::where('pes_id', $pes_id)->first();
            if (!$pessoaEndereco) {
                return response()->json(['message' => 'O endereço da pessoa não foi encontrada!'], 404);
            } 
                $pessoaEndereco->delete();
            
            return response()->json(['message' => 'O endereço da pessoa foi removida'], 200);
        } catch (QueryException $e) {
            Log::channel('database_errors')->error('Erro ao deletar o endereço da pessoa no banco de dados', [
                'exception' => $e->getMessage(),
                'sql' => $e->getSql(),
                'bindings' => $e->getBindings(),
                'input' => $request->all(),
            ]);
            return response()->json(['message' => 'Erro ao deletar o endereço da pessoa'], 500);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Erro ao deletar o endereço da pessoa'], 500);
        }
        
    }
}
