<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Exception;

class UploadFileService
{
    /**
     * Faz upload da imagem Base64 para o ImgBB
     *
     * @param string $base64Image
     * @param array $config ['folder' => 'caminho/no/bucket/', 'extension' => 'png', 'random_identifier_name' => 123]
     * @return array ['url' => '...', 'file_name' => '...']
     */
    static function uploadImages(string $base64Image)
    {
        try {
            $apiKey = env('IMGBB_API_KEY');
            $apiUrl = env('IMGBB_API_URL', 'https://api.imgbb.com/1/upload');

            if (strpos($base64Image, ',') !== false) {
                $parts = explode(',', $base64Image);
                $base64Image = $parts[1];
            }

            $response = Http::asForm()->post($apiUrl, [
                'key'   => $apiKey,
                'image' => $base64Image,
            ]);

            $data = $response->json();

            if (!isset($data['data']['url'])) {
                throw new Exception('Erro no upload ImgBB: ' . $response->body());
            }

            return [
                'url' => $data['data']['url']
            ];
        } catch (Exception $e) {
            return [
                'status' => 500,
                'message' => $e->getMessage(),
                'file' => $fileName ?? null
            ];
        }
    }
}
