<?php

namespace App\Http\Controllers;

use App\Models\Cidade;
use App\Models\Endereco;
use App\Models\Pessoa;
use App\Models\PessoaEndereco;
use App\Models\ServidorEfetivo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ServidorEfetivoController extends Controller
{

    public function index()
    {
        try {
            $servidor = ServidorEfetivo::with('pessoa')->paginate(20);
            return response()->json([
                'message' => 'Os servidores foram encontrados',
                'servidor' => $servidor,
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Não foi possível encontrar os servidores',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'pes_id' => 'required',
            'se_matricula' => 'required'
        ]);

        try {
            $servidor = ServidorEfetivo::where('pes_id', $request->pes_id)->first();
            if (!$servidor) {
                $servidor = ServidorEfetivo::create($validated);
            }

            return response()->json([
                'message' => 'Servidor Efetivo cadastrado!',
                'servidor' => $servidor,
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Não foi possível cadastrar o servidor',
                'error' => $e->getMessage(),
            ]);
        }
    }

    public function show(string $pes_id)
    {
        try {
            $servidor = ServidorEfetivo::with('pessoa')->where('pes_id', $pes_id)->first();
            if (!$servidor) {
                return response()->json([
                    'message' => 'O servidor informado não foi encontrado!',
                ], 404);
            }
            return response()->json([
                'message' => 'o servidor foi encontrado!',
                'servidor' => $servidor,
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Não foi possível encontrar o servidor',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    public function update(Request $request, string $pes_id)
    {

        $validated = $request->validate([
            'se_matricula' => 'string'
        ]);

        try {
            $servidor = ServidorEfetivo::with('pessoa')->where('pes_id', $pes_id)->first();
            if (!$servidor) {
                return response()->json([
                    'message' => 'O servidor informado não foi encontrado!',
                ], 404);
            }

            $servidor->update($validated);

            return response()->json([
                'message' => 'O registro do servidor foi atualizado!',
                'servidor' => $servidor,
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Não foi possível atualizar o servidor',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    public function destroy(string $pes_id)
    {
        try {

            $servidor = ServidorEfetivo::with('pessoa')->where('pes_id', $pes_id)->first();
            if (!$servidor) {
                return response()->json([
                    'message' => 'O servidor não foi encontrado!',
                ], 404);
            }

            $servidor->delete();

            return response()->json(['message' => 'O servidor foi removido!'], 200);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Não foi possível remover o servidor',
                'error' => $e->getMessage(),
            ],500);
        }
    }
}
