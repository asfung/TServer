<?php

namespace App\Http\Controllers;

use App\DTO\BookmarkDTO;
use App\Common\ApiCommon;
use Illuminate\Http\Request;
use App\Services\BookmarkService;

class BookmarkController extends Controller{
    protected $bookmarkService;

    public function __construct(BookmarkService $bookmarkService){
        $this->bookmarkService = $bookmarkService;
    }

    public function storeBookmarkCTLL(Request $request){
        try{
            $request->validate([
                'post_id' => 'required',
            ]);
            $post_id = $request->input('post_id');

            $bookmarkDTO = new BookmarkDTO();
            $bookmarkDTO->setPost_id($post_id);
            $bookmarkDTO->setUser_id(ApiCommon::getUserId());
            return $this->bookmarkService->store_and_scratchOut($bookmarkDTO);

        }catch(\Exception $e){
            // ApiCommon::rollback($e->getMessage());
            return response()->json([
                'error' => $e->getMessage()
            ], 500);
        }
    }


}
