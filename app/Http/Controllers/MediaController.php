<?php
namespace App\Http\Controllers;

use App\DTO\MediaDTO;;
use Illuminate\Http\Request;
use App\Services\MediaService;

class MediaController extends Controller{

    protected $mediaService;

    public function __construct(MediaService $mediaService){
        $this->mediaService = $mediaService;
    }

    public function uploadFile(Request $request){
        try{
        $request->validate([
            'file' => 'required|file|mimes:png,jpg,jpeg,gif|max:2048',
            'type' => 'string',
        ]);

        $file = $request->file('file');
        $type = $request->input('type');
        // $filePath = $file->getRealPath();

        // if (strpos($file->getMimeType(), 'image/') === 0) {
        //     if (empty($type)) {
        //         $type = 'image-post';
        //     } else {
        //         $type = 'image-profile';
        //     }
        // }
        // elseif (strpos($file->getMimeType(), 'video/') === 0) {
        //     if (!empty($type)) {
        //         $type = 'video';
        //     }
        //     // } else {
        //     //     $type = 'video-profile';
        //     // }
        // } else {
        //     return response()->json([
        //         'error' => 'The uploaded file must be an image or a video.'
        //     ], 400);
        // }

        if(is_null($type)){
            if (strpos($file->getMimeType(), 'image/') === 0) {
                $type = 'image-post';
            } elseif (strpos($file->getMimeType(), 'video/') === 0) {
                $type = 'video';
            }
        }else{
            $type = 'image-profile';
        }

        $result = $this->mediaService->uploadFile($type, $file);

        return response()->json($result);
        // return response()->json($file->getMimeType());
        }catch(\Exception $e){
            return response()->json([
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function getFile(Request $request){
        try{
            $request->validate([
                'id' => 'required|exists:media,id',
            ]);
            $id = $request->input('id');

            $result = $this->mediaService->getFileById($id);

            if (!$result['error']) {
                return response($result['data'])
                    ->header('Content-Type', $result['headers']['Content-Type'])
                    ->header('Content-Disposition', $result['headers']['Content-Disposition']);
            } else {
                return response()->json(['error' => true, 'message' => $result['message']], 404);
            }

        }catch(\Exception $e){
            return response()->json([
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function mediaPostIdEditCTLL(Request $request){
        try{

            $arrayData = $request->input('data');
            $postId = $request->input('post_id');
            $dataSclicing = [];

            // if(is_array($arrayData)){
            //     foreach($arrayData as $item){
            //         $dataSclicing[] = $item['key'];
            //     }
            // }
            $mediaDto = new MediaDTO();
            $mediaDto->setPost_id($postId);
            $mediaDto->setData($arrayData);

            return $this->mediaService->editMediaPostId($mediaDto);


            // return response()->json([
            //     'data' => $dataSclicing,
            //     'post_id' => $postId,
            // ], 200);
        }catch(\Exception $e){
            return response()->json([
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function mediaPostIdDeleteCTLL(Request $request){
        try{

            $arrayData = $request->input('data');
            $postId = $request->input('post_id');

            $mediaDto = new MediaDTO();
            $mediaDto->setPost_id($postId);
            $mediaDto->setData($arrayData);

            return $this->mediaService->editMediaPostId($mediaDto);
        }catch(\Exception $e){
            return response()->json([
                'error' => $e->getMessage()
            ], 500);
        }
    }


}
