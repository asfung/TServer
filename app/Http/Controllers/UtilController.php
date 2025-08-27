<?php

namespace App\Http\Controllers;

use App\Common\ApiCommon;
use Embed\Embed;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class UtilController extends Controller {

    public function getLinkPreview(Request $request){
        $url = $request->input('url');
        if (!$url) return ApiCommon::sendResponse(null, 'must provide the \'url\'', 400, false);

        try{
            if (preg_match('/(twitter\.com|x\.com)/i', $url)) {
                $apiUrl = "https://publish.twitter.com/oembed?url=" . urlencode($url);
                $res = Http::get($apiUrl);

                if ($res->ok()) {
                    $json = $res->json();
                    return ApiCommon::sendResponse([
                        'title'       => $json['author_name'] ?? 'Twitter Post',
                        'description' => strip_tags($json['html'] ?? ''),
                        'image'       => null,
                        'url'         => $url,
                    ], 'berhasil dapat link preview (via oEmbed)', 200, true);
                }
            }

            $embed = new Embed();
            $info = $embed->get($url);

            return ApiCommon::sendResponse([
                'title' => $info->title ?? '',
                'description' => $info->description ?? '',
                'image' => $info->image ?? null,
                'url' => $url,
            ], 'berhasil dapat link preview', 200, true);

        }catch(\Exception $e){
            return ApiCommon::sendResponse(null, $e->getMessage(), 500, false);
        }
    }

}
