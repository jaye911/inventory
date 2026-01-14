<?php

namespace App\Http\Controllers;

use App\Models\Unit;
use Illuminate\Http\JsonResponse;

class UnitController extends Controller
{
    public function index(): JsonResponse
    {
        $units = Unit::orderBy('name')->get();
        
        return response()->json($units);
    }
}
