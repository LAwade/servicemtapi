<?php

namespace App\Http\Controllers;

use App\Models\PessoaEndereco;
use Illuminate\Http\Request;

class PessoaEnderecoController extends Controller
{

    public function index()
    {
        try {
            $pessoaEndereco = PessoaEndereco::with(['pessoa', 'endereco'])->paginate(20);
            if (!$pessoaEndereco) {
                return response()->json(['message' => 'Nenhum registro encontrado'], 404);
            }
            return response()->json(['message' => 'Pessoas Endereços encontrados', 'pessoaEndereco' => $pessoaEndereco], 200);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Erro ao buscar pessoas endereços', 'error' => $e->getMessage()], 500);
        }
    }

    public function store(Request $request)
    {
        if (!$request->headers->has('Accept')) {
            $request->headers->set('Accept', 'application/json');
        }

        $valited = $request->validate([
            'pes_id' => 'required|integer',
            'end_id' => 'required|integer',
        ]);

        try {
            $pessoaEndereco = PessoaEndereco::where('end_id', $request->end_id)->first();
            if ($pessoaEndereco) {
                return response()->json(['message' => 'Endereço já vinculado a outra pessoa'], 400);
            }

            $pessoaEndereco = PessoaEndereco::where('pes_id', $request->pes_id)->first();
            if (!$pessoaEndereco) {
                $pessoaEndereco = PessoaEndereco::create($valited);
            }

            return response()->json(['message' => 'Vinculo cadastrado com sucesso', 'pessoaEndereco' => $pessoaEndereco], 200);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Erro ao vincular endereço a pessoa', 'error' => $e->getMessage()], 500);
        }
    }

    public function show(string $pes_id)
    {
        $pessoaEndereco = PessoaEndereco::with(['pessoa', 'endereco'])->where('pes_id', $pes_id)->orWhere('end_id', $pes_id)->first();

        if (!$pessoaEndereco) {
            return response()->json(['message' => 'O endereço da pessoa não foi encontrado'], 404);
        }
        return response()->json(['message' => 'O endereço da pessoa foi encontrado', 'pessoaEndereco' => $pessoaEndereco], 200);
    }

    public function update(Request $request, string $pes_id)
    {

        if (!$request->headers->has('Accept')) {
            $request->headers->set('Accept', 'application/json');
        }

        $valited = $request->validate([
            'end_id' => 'integer',
        ]);

        try {
            $pessoaEndereco = PessoaEndereco::where('pes_id', $pes_id)->first();
            if (!$pessoaEndereco) {
                return response()->json(['message' => 'O endereço da pessoa não foi encontrada'], 404);
            }
            $pessoaEndereco->update($valited);

            return response()->json(['message' => 'Pessoa Endereço atualizada', 'pessoaEndereco' => $pessoaEndereco], 200);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Erro ao atualizar o endereço da pessoa', 'error' => $e->getMessage()], 500);
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
            
            return response()->json(['message' => 'O endereço da pessoa foi removida', 'pessoaEndereco' => $pessoaEndereco], 200);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Erro ao deletar o endereço da pessoa', 'error' => $e->getMessage()], 500);
        }
        
    }
}
