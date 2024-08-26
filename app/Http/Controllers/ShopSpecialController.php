<?php

namespace App\Http\Controllers;

use App\Models\Vehicle;
use App\Models\Suspension;
use App\Models\Tire;
use App\Models\Article;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ShopSpecialController extends Controller
{
    public function index(Request $request) {
        $tiresArray = [];

        $tires = Tire::orderBy('name','ASC')->where('status',1)->get();

        $articles = Article::where('status',1);

        //Aplicar filtros aquí

        if(!empty ($request->get('tire'))) {
            $tiresArray = explode(',',$request->get('tire'));
            $articles = $articles->whereIn('tire_id',$tiresArray);
        }

        if (!empty($request->get('search'))) {
            $articles = $articles->where('title','like','%'.$request->get('search').'%');
        }

        $articles = $articles->paginate(9);

        $data['tires'] = $tires;
        $data['tiresArray'] = $tiresArray;

        return view('frontend.shop-special',$data);
    }
}
