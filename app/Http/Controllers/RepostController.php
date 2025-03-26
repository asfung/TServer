<?php

namespace App\Http\Controllers;

use App\DTO\PostDTO;
use App\Common\ApiCommon;
use Illuminate\Http\Request;
use App\Services\RepostService;

class RepostController extends Controller{
  private $repostService;

  public function __construct(RepostService $repostService){
    $this->repostService = $repostService;
  }

  public function repostToggleCTLL(Request $request){
    try {
      $request->validate([
        'post_id' => 'required',
      ]);
      $post_id = $request->input('post_id');

      $postDTO = new PostDTO();
      $postDTO->setPost_id($post_id);
      $postDTO->setUser_id(ApiCommon::getUserId());
      return $this->repostService->store_and_scratchOut($postDTO);
    } catch (\Exception $e) {
      // ApiCommon::rollback($e->getMessage());
      return response()->json([
        'error' => $e->getMessage()
      ], 500);
    }
  }
}
