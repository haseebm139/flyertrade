<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;


use Illuminate\Support\Facades\Hash;
use App\Http\Controllers\Api\BaseController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

use App\Models\Service;
class HomeController extends BaseController
{
    public function services()
    {
        try {

            $data = Service::where('status', 1)->get();
            return $this->sendResponse($data, 'Services');
        } catch (\Throwable $th) {

            return $this->sendError($th->getMessage());
        }


    }
}
