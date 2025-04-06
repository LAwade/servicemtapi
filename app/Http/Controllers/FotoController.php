<?php

namespace App\Http\Controllers;

use App\Models\FotoPessoa;
use App\Models\Pessoa;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\ValidationException;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\URL;

class FotoController extends Controller
{

    public function index()
    {
        try {
            $fotoPessoa = FotoPessoa::with('pessoa')->paginate(20);
            if (!$fotoPessoa) {
                return response('Arquivo não encontrado', 404)->json();
            }
            return response()->json(['message' => 'Arquivo encontrado', 'fotoPessoa' => $fotoPessoa]);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Erro ao buscar arquivo de imagem.',
                'arquivos' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * ATUALIZA E SALVA O ARQUIVO NO MINIO
     * @param Request $request
     * @param string $pes_id         
     */
    public function store(Request $request, string $pes_id)
    {
        try {
            $request->validate([
                'foto' => 'required|image',
            ]);

            $pessoa = Pessoa::where('pes_id', $pes_id)->first();
            if (!$pessoa) {
                return response()->json(['message' => 'Arquivo não encontrado!'], 404);
            }

            $path = $request->file('foto')->store('fotos/uploads', 's3');
            $foto = FotoPessoa::where('pes_id', $pes_id)->first();

            if (!$foto) {
                $foto = FotoPessoa::create([
                    'pes_id' => $pes_id,
                    'fp_data' => Carbon::now(),
                    'fp_bucket' => env('AWS_BUCKET'),
                    'fp_hash' => $path,
                ]);

                return response()->json([
                    'message' => 'Arquivo de imagem da pessoa foi salva!',
                    'Foto' => $foto,
                ], 201);
            }

            $foto->update([
                'fp_data' => Carbon::now(),
                'fp_bucket' => env('AWS_BUCKET'),
                'fp_hash' => $path,
            ]);

            return response()->json([
                'message' => 'Arquivo de imagem da pessoa foi atualizada!',
                'Foto' => $foto,
            ], 200);
        } catch (ValidationException $e) {
            return response()->json([
                'message' => 'Erro de validação',
                'errors' => $e->errors(),
            ], 422);
        } catch (QueryException $e) {
            Log::channel('database_errors')->error('Erro ao adicionar o arquivo no banco de dados', [
                'exception' => $e->getMessage(),
                'sql' => $e->getSql(),
                'bindings' => $e->getBindings(),
                'input' => $request->all(),
            ]);
            return response()->json([
                'message' => 'Erro ao adicionar o arquivo no banco de dados',
            ], 500);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'OPS: Não foi possível encontrar o arquivo!',
                'arquivos' => $e->getMessage(),
            ], 404);
        }
    }

    public function show(string $pes_id)
    {

        try {
            $foto = FotoPessoa::where('pes_id', $pes_id)->first();
            if (!$foto) {
                return response()->json(['message' => 'Arquivo não encontrado!'], 404);
            }
            // Verifica se o arquivo existe no MinIO
            if (!Storage::disk(env('FILESYSTEM_DISK'))->exists($foto->fp_hash)) {
                return response()->json(['message' => 'Arquivo não encontrado no MinIO'], 404);
            }

            // Baixar o arquivo do MinIO e salvar localmente
            $file = Storage::disk(env('FILESYSTEM_DISK'))->get($foto->fp_hash);
            $fotoreplace = str_replace('fotos/uploads/', '', $foto->fp_hash);
            Storage::disk('local')->put("public/exported/$fotoreplace", $file);

            // Gerar URL temporária assinada
            $url = URL::temporarySignedRoute(
                'exported.file',
                now()->addMinutes(5),
                ['filename' => $fotoreplace]
            );

            return response()->json([
                'message' => 'URL temporária gerada com sucesso!',
                'url' => $url,
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'OPS: Não foi possível converter o arquivo!',
                'arquivos' => $e->getMessage(),
            ], 500);
        }
    }

    public function destroy(string $pes_id)
    {
        try {
            $fotoPessoa = FotoPessoa::where('pes_id', $pes_id)->first();
            if (!$fotoPessoa) {
                return response()->json(['message' => 'O arquivo da imagem não foi encontrada!'], 404);
            }

            $fotoPessoa->delete();
            return response()->json(['message' => 'Arquivo da imagem da pessoa foi removida!', 'fotoPessoa' => $fotoPessoa], 200);
        } catch (QueryException $e) {
            Log::channel('database_errors')->error('Erro ao deletar o arquivo no banco de dados', [
                'exception' => $e->getMessage(),
                'sql' => $e->getSql(),
                'bindings' => $e->getBindings(),
                'input' => $request->all(),
            ]);
            return response()->json([
                'message' => 'Erro ao deletar o arquivo no banco de dados',
            ], 500);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'OPS: Não foi possível converter o arquivo!',
                'arquivos' => $e->getMessage(),
            ], 404);
        }
    }
}
