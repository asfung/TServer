<?php

namespace App\Http\Controllers;

use App\DTO\PostDTO;
use App\Services\SelectQueryService;
use Illuminate\Http\Request;

class SelectQueryController extends Controller
{
    protected $selectQueryService;

    public function __construct(SelectQueryService $selectQueryService){
        $this->selectQueryService = $selectQueryService;
    }

    public function getPostCTLL(Request $request){
        try{

            $perPage = $request->input('per_page', 10); 

            $postDto = new PostDTO();
            $postDto->setPerPage($perPage);

            return $this->selectQueryService->getPost($postDto);

        }catch(\Exception $e){
            // ApiCommon::rollback($e->getMessage());
            return response()->json([
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
