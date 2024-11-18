<?php

namespace App\Services;

use App\Common\ApiCommon;
use App\Models\Media;
use GuzzleHttp\Client;
use Illuminate\Http\Client\RequestException;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Str;

class MediaService
{
    protected $client;
    protected $url;
    protected $get_file_url;
    protected $token;

    public function __construct()
    {
        $this->client = new Client();
        $this->url = env('SUPABASE_URL');
        $this->get_file_url = env('SUPABASE_GET_FILE_URL');
        $this->token = 'Bearer ' . env('SUPABASE_TOKEN');
    }

    public function uploadFile($type, UploadedFile $file)
    {
        $generatedId = Str::uuid()->toString();
        // $extension = pathinfo($filePath, PATHINFO_EXTENSION); 

        if ($type === 'image-post') {
            $fullPath = "{$this->url}image/post/{$generatedId}.{$file->getClientOriginalExtension()}";
        } elseif ($type === 'image-profile') {
            $fullPath = "{$this->url}image/profile/{$generatedId}.{$file->getClientOriginalExtension()}";
        } elseif ($type === 'video') {
            $fullPath = "{$this->url}image/video/{$generatedId}.{$file->getClientOriginalExtension()}";
        } else {
            return ['error' => true, 'message' => 'silahkan pilih tipe nya'];
        }

        try {
            $response = $this->client->request('POST', $fullPath, [
                'headers' => [
                    'Authorization' => $this->token,
                ],
                'multipart' => [
                    [
                        'name' => 'file',
                        'contents' => fopen($file, 'r'),
                        'filename' => "{$generatedId}.{$file->getClientOriginalExtension()}",
                    ],
                ],
            ]);

            $responseBody = $response->getBody();
            $result = json_decode($responseBody, true);

            $mediaData = [
                'original_name' => $file->getClientOriginalName(),
                'mimetypes' => $file->getMimeType(),
                'generated_name' => "{$generatedId}.{$file->getClientOriginalExtension()}"
            ];

            $additionalData = [
                'key' => $result['Key'],
                'uuid_supabase' => $result['Id'],
            ];

            $result = $this->insertMedia($mediaData, $additionalData);

            // return $response;
            return ApiCommon::sendResponse($result, 'File Uploaded');


            // return $file->getClientOriginalExtension();
        } catch (RequestException $e) {
            return [
                'error' => true,
                'message' => $e->getMessage(),
                // 'response' => $e->getResponse() ? (string) $e->getResponse()->getBody() : null,
            ];
        }
    }

    public function getFileById($id)
    {
        // $mediaItem = Media::find($id);
        $mediaItem = Media::where('id', $id)->get()->first();

        if (!$mediaItem) {
            return [
                'error' => true,
                'message' => 'Media not found',
            ];
        }

        $fileUrl = "{$this->get_file_url}/{$mediaItem->key}";

        try {
            $response = $this->client->request('GET', $fileUrl, [
                'headers' => [
                    'Authorization' => $this->token,
                ],
            ]);

            $responseBody = $response->getBody();

            return [
                'error' => false,
                'data' => $responseBody,
                'headers' => [
                    'Content-Type' => $response->getHeaderLine('Content-Type'),
                    'Content-Disposition' => 'attachment; filename="' . basename($fileUrl) . '"',
                ],
            ];
        } catch (RequestException $e) {
            return [
                'error' => true,
                'message' => $e->getMessage(),
            ];
        }
    }


    private function insertMedia(array $mediaData, array $additionalData)
    {
        $data = array_merge($mediaData, $additionalData);
        $mediaItem = Media::create($data);
        return $mediaItem;
    }
}
