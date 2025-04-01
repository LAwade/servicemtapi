<?php

namespace App\Http\Controllers;

use App\Models\ServidorTemporario;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

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
                'message' => 'Não foi possível encontrar os servidores',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'pes_id' => 'required',
            'st_data_admissao' => 'required|date',
            'st_data_demissao' => 'required|date',
        ]);

        try {
            $servidor = ServidorTemporario::where('pes_id', $request->pes_id)->first();
            if (!$servidor) {
                $servidor = ServidorTemporario::create($validated);
            }

            return response()->json([
                'message' => 'O servidor temporario foi cadastrado!',
                'servidor' => $servidor,
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Não foi possível cadastrar o servidor',
                'error' => $e->getMessage(),
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
                'message' => 'Não foi possível encontrar o servidor',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    public function update(Request $request, string $pes_id)
    {

        $validated = $request->validate([
            'st_data_admissao' => 'date',
            'st_data_demissao' => 'date',
        ]);

        try {
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

            $servidor = ServidorTemporario::with('pessoa')->where('pes_id', $pes_id)->first();
            if (!$servidor) {
                return response()->json([
                    'message' => 'O servidor não foi encontrado!',
                ], 404);
            }
            $servidor->delete();

            return response()->json(['message' => 'O servidor foi removido com sucesso!'], 200);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Não foi possível remover o servidor',
                'error' => $e->getMessage(),
            ], 500);
        }
    }
}
