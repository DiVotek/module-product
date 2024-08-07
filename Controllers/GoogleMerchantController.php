<?php

namespace Modules\Product\Controllers;

use App\Http\Controllers\Controller;
use Modules\Product\Services\GoogleMerchantFeedService;

class GoogleMerchantController extends Controller
{
    public function index(): \Illuminate\Http\Response
    {
        $content = GoogleMerchantFeedService::update();

        return response()->view('google-merchant-feed', ['content' => $content])->header('Content-Type', 'text/xml');
    }
}
