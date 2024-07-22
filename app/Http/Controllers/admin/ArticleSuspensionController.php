<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\Suspension;
use Illuminate\Http\Request;

class ArticleSuspensionController extends Controller
{
    public function index(Request $request){

        if(!empty($request->vehicle_id)) {
            $suspensions = Suspension::where('vehicle_id', $request->vehicle_id)
            ->orderBy('name', 'ASC')
            ->get();
    
            return response()->json([
                'status' => true,
                'suspensions' => $suspensions
            ]);
        } else {
            return response()->json([
                'status' => true,
                'suspensions' => []
            ]);
        }
    }
}
