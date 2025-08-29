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
    public function services(Request $request)
    {
        try {
            $query = Service::query()->where('status', 1);

            // 🔹 Search by service name
            if ($request->has('search') && !empty($request->search)) {
                $search = $request->search;
                $query->where('name', 'like', '%' . $search . '%');
            }

            $data = $query->get();

            return $this->sendResponse($data, 'Services fetched successfully.');
        } catch (\Throwable $th) {
            return $this->sendError($th->getMessage());
        }

    }
}
