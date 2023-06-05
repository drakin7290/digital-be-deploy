<?php

namespace Botble\Medals\Http\Controllers\API;

use Botble\Base\Events\BeforeEditContentEvent;
use Botble\Medals\Repositories\Interfaces\MedalsInterface;
use Botble\Base\Http\Controllers\BaseController;
use Illuminate\Http\Request;
use Exception;
use Botble\Base\Http\Responses\BaseHttpResponse;
use Botble\Medals\Models\Medals;
use RvMedia;

class MedalController extends BaseController
{
    /**
     * @var MedalsInterface
     */
    
    public function getList (Request $request) {
        $data = Medals::where('status', 'published')->get();
        foreach ($data as &$element) {
            $element["icon"] = RvMedia::getImageUrl($element["icon"], '', false, RvMedia::getDefaultImage());
        };
        return response()->json([
            "error" => false,
            "data" => $data
        ]);
    }
}
