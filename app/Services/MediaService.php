<?php

namespace App\Services;

use App\Common\ApiCommon;
use App\DTO\MediaDTO;
use App\Models\Media;
use Carbon\Carbon;
use GuzzleHttp\Client;
use Illuminate\Http\Client\RequestException;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
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
        $fullPath = null;

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
                'id' => $generatedId,
                'original_name' => $file->getClientOriginalName(),
                'mimetypes' => $file->getMimeType(),
                'generated_name' => "{$generatedId}.{$file->getClientOriginalExtension()}"
            ];

            $additionalData = [
                'key' => $result['Key'],
                'uuid_supabase' => $result['Id'],
            ];

            $result = $this->insertMedia($mediaData, $additionalData);

            if ($type === 'image-profile') {
                $userId = auth()->id(); 
                $user = \App\Models\User::find($userId);
                if ($user) {
                    if (!is_null($user->profile_image)) {
                        $oldMedia = Media::where('id', $user->profile_image)->first();
                        if ($oldMedia) {
                            $oldMedia->delete(); 
                        }
                    }
                    $user->profile_image = $generatedId;
                    $user->save();
    
                    return ApiCommon::sendResponse($result, 'image profile changed');
                }
            }

            // return $response;
            return ApiCommon::sendResponse($result, 'File Uploaded as ' . $type);


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


    public function editMediaPostId(MediaDTO $mediaDTO){
        try{
            DB::beginTransaction();
            $arrayData = $mediaDTO->getData();
            $postId = $mediaDTO->getPost_id();
            $dataSclicing = [];

            if(is_array($arrayData)){
                foreach($arrayData as $item){
                    DB::commit();
                    $dataSclicing[] = $item['id'];
                    $mediaPostId = Media::where('id', $item['id'])->first();
                    $mediaPostId->post_id = $postId;
                    // $mediaPostId->post_id = null;
                    $mediaPostId->save();
                }
                return ApiCommon::sendResponse($arrayData, 'Berhasil Mengubah post_id pada Media item', 201);
            }else{
                return ApiCommon::sendResponse($arrayData, 'data is not the array value', 400);
            }

            // return response()->json([
            //     'data' => $dataSclicing,
            //     'post_id' => $postId,
            // ], 200);


        }catch(\Exception $e){
            // ApiCommon::rollback($e->getMessage());
            DB::rollBack();
            return response()->json([
                'error' => $e->getMessage()
            ], 500);
        }
    }


    public function deleteMediaPostId(MediaDTO $mediaDTO){
        try{
            DB::beginTransaction();
            $data = $mediaDTO->getData();
            $postId = $mediaDTO->getPost_id();
            $dataSclicing = [];

            $mediaDelete = Media::where('id', $data)->first();
            $mediaDelete->deleted_at = Carbon::now();
            $mediaDelete->save();
            DB::commit();

            return ApiCommon::sendResponse($mediaDelete, 'Berhasil Menghapus Media item', 200);

            //  MORE THAN 1
            // if(is_array($data)){
            //     foreach($data as $item){
            //         DB::commit();
            //         $dataSclicing[] = $item['id'];
            //         $mediaPostId = Media::where('id', $item['id'])->first();
            //         $mediaPostId->deleted_at = Carbon::now();
            //         $mediaPostId->save();
            //     }
            //     return ApiCommon::sendResponse($data, 'Berhasil Menghapus Media item', 200);
            // }else{
            //     return ApiCommon::sendResponse($data, 'data is not the array value', 400);
            // }

        }catch(\Exception $e){
            // ApiCommon::rollback($e->getMessage());
            DB::rollBack();
            return response()->json([
                'error' => $e->getMessage()
            ], 500);
        }
    }

    private function insertMedia(array $mediaData, array $additionalData)
    {
        $data = array_merge($mediaData, $additionalData);
        $mediaItem = Media::create($data);
        return $mediaItem;
    }

}
