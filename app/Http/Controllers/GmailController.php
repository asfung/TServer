<?php

namespace App\Http\Controllers;

use App\Helpers\CryptoHelper;
use App\Mail\Gmail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class GmailController extends Controller {

  protected $crypto;

  public function __construct(CryptoHelper $crypto)
  {
    $this->crypto = $crypto;
  }


  public function send(Request $request){
    try{
      // Mail::to('syahrulfitraaghfari47@gmail.com')->send(new Gmail([
      //   'title' => 'The Title',
      //   'body' => 'Helo Syahrul',
      // ]));
      // return CryptoHelper::encrypt('c91cdbc1-9afd-4976-b540-993eae9257dc');
      // return $this->crypto->encrypt('c91cdbc1-9afd-4976-b540-993eae9257dc');
      // return CryptoHelper::encrypt('paung');
      // return hash('sha256', '_notifications.c91cdbc1-9afd-4976-b540-993eae9257dc');
      return hash('sha256', '_watcherpost.166791947900420096');
    } catch (\Exception $e) {
      return response()->json([
        'error' => $e->getMessage()
      ], 500);
    }
  }

}
