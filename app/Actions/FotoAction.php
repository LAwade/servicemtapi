<?php
namespace App\Actions;

use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\URL;
use Exception;

class FotoAction
{
    /**
     * Gera uma URL temporária assinada para um arquivo do MinIO.
     *
     * @param string $hash Caminho do arquivo no MinIO
     * @return string URL temporária assinada
     * @throws Exception Se o arquivo não for encontrado ou houver falha no processamento
     */
    public static function dispatch(string $hash): string|null
    {
        // Verifica se o arquivo existe no MinIO
        $disk = env('FILESYSTEM_DISK', 's3'); // Default para 's3' se não definido
        if (!Storage::disk($disk)->exists($hash)) {
            return null;
        }

        // Baixa o arquivo do MinIO
        $file = Storage::disk($disk)->get($hash);
        $fotoreplace = str_replace('fotos/uploads/', '', $hash);

        // Salva localmente
        $localPath = "public/exported/{$fotoreplace}";
        if (!Storage::disk('local')->put($localPath, $file)) {
            return null;
        }

        // Gera URL temporária assinada
        $url = URL::temporarySignedRoute(
            'exported.file',
            now()->addMinutes(5),
            ['filename' => $fotoreplace]
        );

        return $url;
    }
}