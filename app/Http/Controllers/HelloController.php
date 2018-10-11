<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;

class HelloController extends Controller
{

    /**
     * @return JsonResponse
     */
    public function index() : JsonResponse
    {
        return response()->json('Hello world!', 200);
    }
}