<?php

namespace App\Http\Controllers;

use Laravel\Lumen\Routing\Controller as BaseController;

use Illuminate\Http\Request;

class Controller extends BaseController
{
    public function getArrayOfRequestedValues(Request $r, array $props)
    {
        $condition = [];

        foreach($props as $requestParam => $newArrayKey) {
            if(!is_null($r->$requestParam)) {
                $condition[$newArrayKey] = $r->$requestParam;
            }
        }

        return $condition;
    }
}
