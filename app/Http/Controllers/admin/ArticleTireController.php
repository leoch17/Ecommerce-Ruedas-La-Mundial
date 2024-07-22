<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\Tire;
use Illuminate\Http\Request;

class ArticleTireController extends Controller
{
    public function index(Request $request) {

        if(!empty($request->suspension_id)) {
            $tires = Tire::where('suspension_id', $request->suspension_id)
            ->orderBy('name', 'ASC')
            ->get();
    
            return response()->json([
                'status' => true,
                'tires' => $tires
            ]);
        } else {
            return response()->json([
                'status' => true,
                'tires' => []
            ]);
        }
    }
}
