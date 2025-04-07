<?php

namespace App\Http\Controllers;

use App\Models\Lotacao;
use App\Models\ServidorEfetivo;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\ValidationException;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\URL;
use App\Actions\FotoAction;

class LotacaoController extends Controller
{
    
    public function index()
    {
        try{
            $lotacao = Lotacao::with('unidade')->paginate(20);
            if (!$lotacao) {
                return response()->json(['message' => 'A lotação não foi encontrado'], 404);
            }
            return response()->json(['message' => 'As seguintes lotações foram encontradas', 'lotacao' => $lotacao], 200);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Erro ao buscar lotações'], 500);
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
                'unid_id' => 'required|integer',
                'lot_data_lotacao' => 'required|date',
                'lot_data_remocao' => 'required|date',
                'lot_portaria' => 'required|string',
            ]);

            $lotacao = Lotacao::where('lot_id', $request->lot_id)->first();
            if (!$lotacao) {
                $lotacao = Lotacao::create($valited);
            }
            return response()->json(['message' => 'Lotação foi criada com sucesso!', 'lotacao' => $lotacao], 201);
        } catch (ValidationException $e) {
            return response()->json([
                'message' => 'Erro de validação',
                'errors' => $e->errors(),
            ], 422);
        } catch (QueryException $e) {
            Log::channel('database_errors')->error('Erro ao adicionar a lotação no banco de dados', [
                'exception' => $e->getMessage(),
                'sql' => $e->getSql(),
                'bindings' => $e->getBindings(),
                'input' => $request->all(),
            ]);
            return response()->json(['message' => 'Erro ao cadastrar lotação'], 500);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Erro ao cadastrar lotação'], 500);
        }
        
    }

    public function show(string $lot_id)
    {
        try {
            $lotacao = Lotacao::with(['pessoa', 'unidade'])->where('lot_id', $lot_id)->first();
            if (!$lotacao) {
                return response()->json(['message' => 'Lotação não encontrada'], 404);
            }

            return response()->json(['message' => 'A Lotação foi encontrada!', 'lotacao' => $lotacao], 200);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Erro ao buscar lotação'], 500);
        }
    }

    public function showByUnidade(string $unid_id)
    {
        try {
            $lotacoes = Lotacao::with(['pessoa.foto', 'unidade'])
                ->where('unid_id', $unid_id)
                ->paginate(20);

            if (!$lotacoes) {
                return response()->json(['message' => 'Nenhuma lotação encontrada'], 404);
            }

            $data = $lotacoes->map(function ($lotacao) {
                $url = null;

                if ($lotacao->pessoa->foto) {
                    $url = FotoAction::dispatch($lotacao->pessoa->foto->fp_hash);
                }

                return [
                    'nome' => $lotacao->pessoa->pes_nome,
                    'idade' => Carbon::parse($lotacao->pessoa->pes_data_nascimento)->age,
                    'unidade_lotacao' => $lotacao->unidade->unid_nome,
                    'fotografia' => $url
                ];
            });

            return response()->json([
                'data' => $data,
                'pagination' => [
                    'current_page' => $lotacoes->currentPage(),
                    'last_page' => $lotacoes->lastPage(),
                    'per_page' => $lotacoes->perPage(),
                    'total' => $lotacoes->total(),
                ],
            ], 200);
        } catch (QueryException $e) {
            Log::channel('database_errors')->error('Erro ao buscar lotação no banco de dados', [
                'exception' => $e->getMessage(),
                'sql' => $e->getSql(),
                'bindings' => $e->getBindings()
            ]);
            return response()->json(['message' => 'Erro ao buscar lotação'], 500);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Erro ao buscar lotação. ' . $e->getMessage()], 500);
        }
        
    }

    public function showByNome(Request $request)
    {
        try {
            $request->validate([
                'pes_nome' => 'required|string',
            ]);

            $servidores = ServidorEfetivo::with('pessoa.lotacoes.unidade.endereco.endereco')->whereHas('pessoa', function ($q) use ($request) {
                $q->where('pes_nome', 'ilike', '%' . $request->pes_nome . '%');
            })->paginate(20);

            if (!$servidores) {
                return response()->json(['message' => 'Nenhum servidor encontrado'], 404);
            }
    
            $data = $servidores->map(function ($servidores) {
                $url = null;

                if ($servidores->pessoa->foto) {
                    $url = FotoAction::dispatch($servidores->pessoa->foto->fp_hash);
                }

                return [
                    'nome' => $servidores->pessoa->pes_nome,
                    'idade' => Carbon::parse($servidores->pessoa->pes_data_nascimento)->age,
                    'unidade_lotacao' => $servidores->pessoa->lotacoes->unidade->unid_nome,
                    'endereco' => $servidores->pessoa->lotacoes->unidade->endereco->endereco->end_logradouro,
                    'fotografia' => $url,
                ];
            });
    
            return response()->json([
                'data' => $data,
                'pagination' => [
                    'current_page' => $servidores->currentPage(),
                    'last_page' => $servidores->lastPage(),
                    'per_page' => $servidores->perPage(),
                    'total' => $servidores->total(),
                ],
            ], 200);

        } catch (ValidationException $e) {
            return response()->json([
                'message' => 'Erro de validação',
                'errors' => $e->errors(),
            ], 422);
        } catch (QueryException $e) {
            Log::channel('database_errors')->error('Erro ao buscar lotação no banco de dados', [
                'exception' => $e->getMessage(),
                'sql' => $e->getSql(),
                'bindings' => $e->getBindings(),
                'input' => $request->all(),
            ]);
            return response()->json(['message' => 'Erro ao buscar lotação'], 500);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Erro ao buscar lotação'], 500);
        }
    }

    public function update(Request $request, string $lot_id)
    {

        if (!$request->headers->has('Accept')) {
            $request->headers->set('Accept', 'application/json');
        }

        try {
            $valited = $request->validate([
                'pes_id' => 'integer',
                'unid_id' => 'integer',
                'lot_data_lotacao' => 'date',
                'lot_data_remocao' => 'date',
                'lot_portaria' => 'string',
            ]);
    
            $lotacao = Lotacao::where('lot_id', $lot_id)->first();
            if (!$lotacao) {
                return response()->json(['message' => 'Lotação não encontrada'], 404);
            } else {
                $lotacao->update($valited);
            }
    
            return response()->json(['message' => 'Lotação atualizada', 'lotacao' => $lotacao], 200);
        } catch (ValidationException $e) {
            return response()->json([
                'message' => 'Erro de validação',
                'errors' => $e->errors(),
            ], 422);
        } catch (QueryException $e) {
            Log::channel('database_errors')->error('Erro ao atualizar a lotação no banco de dados', [
                'exception' => $e->getMessage(),
                'sql' => $e->getSql(),
                'bindings' => $e->getBindings(),
                'input' => $request->all(),
            ]);
            return response()->json(['message' => 'Erro ao atualizar lotação'], 500);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Erro ao atualizar lotação'], 500);
        }
    }

    public function destroy(string $lot_id)
    {
        try {
            $lotacao = Lotacao::where('lot_id', $lot_id)->first();
            if (!$lotacao) {
                return response()->json(['message' => 'Lotação não encontrada'], 404);
            }
            $lotacao->delete();
            return response()->json(['message' => 'Lotação Removida'], 200);
        } catch (QueryException $e) {
            Log::channel('database_errors')->error('Erro ao deletar lotação no banco de dados', [
                'exception' => $e->getMessage(),
                'sql' => $e->getSql(),
                'bindings' => $e->getBindings()
            ]);
            return response()->json(['message' => 'Erro ao deletar lotação'], 500);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Erro ao deletar lotação'], 500);
        }
    }
}
