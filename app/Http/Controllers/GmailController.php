<?php

namespace App\Http\Controllers;

use App\Mail\Gmail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class GmailController extends Controller {


  public function send(Request $request){
    try{
      Mail::to('syahrulfitraaghfari47@gmail.com')->send(new Gmail([
        'title' => 'The Title',
        'body' => 'Helo Syahrul',
      ]));
    } catch (\Exception $e) {
      return response()->json([
        'error' => $e->getMessage()
      ], 500);
    }
  }

}
