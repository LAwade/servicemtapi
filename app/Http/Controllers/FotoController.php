<?php

namespace App\Http\Controllers;

use App\Models\FotoPessoa;
use App\Models\Pessoa;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

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

    public function store(Request $request, string $pes_id)
    {
        $request->validate([
            'foto' => 'required|image',
        ]);

        try {
            $pessoa = Pessoa::where('pes_id', $pes_id)->first();
            if (!$pessoa) {
                return response('Registro de não foi encontrado!', 404)->json();
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
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'OPS: Não foi possível encontrar o arquivo!',
                'arquivos' => $e->getMessage(),
            ], 404);
        }
    }

    public function update(Request $request, string $pes_id)
    {
        $request->validate([
            'foto' => 'required|image',
        ]);

        try {
            $pessoa = Pessoa::where('pes_id', $pes_id)->first();
            if (!$pessoa) {
                return response()->json(['message' => 'Arquivo não encontrado!'], 404);
            }

            $foto = FotoPessoa::where('pes_id', $pes_id)->first();
            if (!$foto) {
                return response()->json(['message' => 'O arquivo de imagem não foi encontrado!'], 404);
            }

            $path = $request->file('foto')->store('fotos/uploads', 's3');
            $foto = FotoPessoa::update([
                'fp_id' => $pes_id,
                'pes_id' => $pes_id,
                'fp_data' => Carbon::now(),
                'fp_bucket' => env('AWS_BUCKET'),
                'fp_hash' => $path,
            ]);

            return response()->json([
                'message' => 'Arquivo de imagem da pessoa foi atualizado!',
                'Foto' => $foto,
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'OPS: Não foi possível converter o arquivo!',
                'arquivos' => $e->getMessage(),
            ], 500);
        }
    }

    public function show(string $pes_id)
    {

        try {
            $foto = FotoPessoa::where('pes_id', $pes_id)->first();
            if (!$foto) {
                return response()->json(['message' => 'Arquivo não encontrado!'], 404);
            }

            if (!Storage::disk('s3')->exists($foto->fp_hash)) {
                return response()->json(['message' => 'Arquivo não encontrado no MinIO'], 404);
            }

            $url = Storage::disk('s3')->temporaryUrl(
                $foto->fp_hash,
                now()->addMinutes(5)
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
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'OPS: Não foi possível converter o arquivo!',
                'arquivos' => $e->getMessage(),
            ], 404);
        }
    }
}
