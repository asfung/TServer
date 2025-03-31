<?php

namespace App\Common;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Config;
use Illuminate\Http\Exceptions\HttpResponseException;

class ApiCommon
{
  public static function sendResponse($result, $message, $code = 200, $isSuccess = true, $showLength = false)
  {
    $response = [
      'success' => $isSuccess,
      'data'    => $result
    ];
    if (!empty($message)) {
      $response['message'] = $message;
    }
    if ($showLength) {
      $response['length'] = is_array($result) || $result instanceof \Countable ? count($result) : 0; 
    }
    if($result === null){
      unset($response['data']);
    }
    return response()->json($response, $code);
  }

  public static function sendPaginatedResponse($paginator, $message = "", $code = 200, $extraData = [])
  {
      $response = [
          'success' => true,
          '_typename' => 'PaginatedData',
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
      if(!empty($extraData)){
        $response = array_merge($response, $extraData);
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

  public static function encryptUserId($userId) {
    $key = '__n0t1f1c4t10n'; 
    $iv = '1223334444555556'; 
    $key = substr(hash('sha256', $key, true), 0, 32);
    $iv = substr($iv, 0, 16);

    $encrypted = openssl_encrypt($userId, 'AES-256-CBC', $key, OPENSSL_RAW_DATA, $iv);
    
    return base64_encode($encrypted);  
  }

  public static function decryptUserId(string $encryptedId): string {
    return Crypt::decryptString($encryptedId);
  }

}
