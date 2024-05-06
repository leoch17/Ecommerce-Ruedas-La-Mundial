<?php

namespace App\Http\Controllers;

use App\Models\Page;
use App\Models\Product;
use App\Models\Wishlist;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class FrontController extends Controller
{
    public function index() {

        $products =  Product::where('is_featured','Yes')
                        ->orderBy('id','DESC')
                        ->take(8)
                        ->where('status',1)->get();
        $data['featuredProducts'] = $products;

        $latestProducts =  Product::orderBy('id','DESC')
                            ->where('status',1)
                            ->take(8)->get();

        $data['latestProducts'] = $latestProducts;
        return view('frontend.home',$data);
    }

    public function addToWishlist(Request $request) {
        if (Auth::check() == false) {

            session(['url.intended' => url()->previous()]);

            return response()->json([
                'status' => false
            ]);
        }

        $product = Product::where('id',$request->id)->first();

        if ($product == null) {
            return response()->json([
                'status' => true,
                'message' => '<div class="alert alert-danger">Producto no encontrado</div>'
            ]);
        }

        Wishlist::updateOrCreate(
            [
                'user_id' => Auth::user()->id,
                'product_id' => $request->id,
            ],
            [
                'user_id' => Auth::user()->id,
                'product_id' => $request->id,
            ]
        );

        // $wishlist = new Wishlist;
        // $wishlist->user_id = Auth::user()->id;
        // $wishlist->product_id = $request->id;
        // $wishlist->save();

        return response()->json([
            'status' => true,
            'message' => '<div class="alert alert-success"><strong>"'.$product->title.'"</strong> añadido en su lista de deseos</div>'
        ]);
    }

    public function page($slug) {
        $page = Page::where('slug',$slug)->first();
        if ($page == null) {
            abort(404);
        }
        return view('frontend.page',[
            'page' => $page
        ]);
    }
}
