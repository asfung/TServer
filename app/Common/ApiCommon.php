<?php

namespace App\Common;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\Exceptions\HttpResponseException;

class ApiCommon
{
  public static function sendResponse($result, $message, $code = 200, $isSuccess = true)
  {
    $response = [
      'success' => $isSuccess,
      'data'    => $result
    ];
    if (!empty($message)) {
      $response['message'] = $message;
    }
    return response()->json($response, $code);
  }

  public static function sendPaginatedResponse($paginator, $message = "", $code = 200)
  {
      $response = [
          'success' => true,
          'data' => $paginator->items(), 
          'meta' => [
              'current_page' => $paginator->currentPage(),
              'last_page' => $paginator->lastPage(),
              'per_page' => $paginator->perPage(),
              'total' => $paginator->total(),
          ]
      ];

      if (!empty($message)) {
          $response['message'] = $message;
      }

      return response()->json($response, $code);
  }


  public static function rollback($e, $message = "Something went wrong! Process not completed")
  {
    DB::rollBack();
    self::throw($e, $message);
  }

  public static function throw($e, $message = "Something went wrong! Process not completed")
  {
    Log::info($e);
    throw new HttpResponseException(response()->json(["message" => $message], 500));
  }

  public static function getUser(){
    return auth()->user();
  }

  public static function getUserId(){
    return auth()->user()->id;
  }

}
