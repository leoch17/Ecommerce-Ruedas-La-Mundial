<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\State;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Gloudemans\Shoppingcart\Facades\Cart;

class CartController extends Controller
{
    public function addToCart(Request $request) {
        $product = Product::with('products_images')->find($request->id);

        if ($product == null) {
            return response()->json([
                'status' => false,
                'message' => 'Producto no encontrado'
            ]);
        }

        if (Cart::count() > 0) {
            // Product found in cart
            // Check if this product already in the cart
            // Return as message that product already added in your cart
            // if product not found in the cart, then add product in cart

            $cartContent = Cart::content();
            $productAlreadyExist = false;

            foreach ($cartContent as $item) {
                if($item->id == $product->id) {
                    $productAlreadyExist = true;
                }
            }

            if ($productAlreadyExist == false) {
                Cart::add($product->id, $product->title, 1, $product->price, ['productImage' => (!empty($product->products_images)) ? $product->products_images->first() : '']);
                
                $status = true;
                $message = '<strong>'.$product->title.'</strong> añadido al carrito satisfactoriamente';
                session()->flash('success',$message);

            } else {
                $status = false;
                $message = $product->title.' ya esta en el carrito';
            }

        } else {
            Cart::add($product->id, $product->title, 1, $product->price, ['productImage' => (!empty($product->products_images)) ? $product->products_images->first() : '']);
            $status = true;
            $message = '<strong>'.$product->title.'</strong> añadido al carrito satisfactoriamente';
            session()->flash('success',$message);
        }

        return response()->json([
            'status' => $status,
            'message' => $message
        ]);

    }

    public function cart() {
        $cartContent = Cart::content();
        $data['cartContent'] = $cartContent;
        return view('frontend.cart',$data);
    }

    public function updateCart(Request $request) {
        $rowId = $request->rowId;
        $qty = $request->qty;

        $itemInfo = Cart::get($rowId);

        $product = Product::find($itemInfo->id);
        // check qty available in stock
        if ($product->track_qty == 'Yes') {
            if ($qty <= $product->qty) {
                Cart::update($rowId, $qty);
                $message = 'Carrito actualizado satisfactoriamente';
                $status = true;
                session()->flash('success',$message);
            } else {
                $message = 'Solicitud qty('.$qty.') no disponible en existencia';
                $status = false;
                session()->flash('error',$message);
            }
        } else {
            Cart::update($rowId, $qty);
            $message = 'Carrito actualizado satisfactoriamente';
            $status = true;
            session()->flash('success',$message);
        }

        return response()->json([
            'status' => $status,
            'message' => $message
        ]);
    }

    public function deleteItem(Request $request) {
        
        $itemInfo = Cart::get($request->rowId);

        if ($itemInfo == null) {
            $errorMessage = 'Item no encontrado en carrito';
            session()->flash('error',$errorMessage);
            
            return response()->json([
                'status' => false,
                'message' => $errorMessage
            ]);
        }

        Cart::remove($request->rowId);

        $message = 'Item removido del carrito satisfactoriamente';
        
        session()->flash('success',$message);
       
        return response()->json([
            'status' => true,
            'message' => $message
        ]);

    }

    public function checkout() {

        //-- if cart is empty redirect to cart page
        if (Cart::count() == 0) {
            return redirect()->route('frontend.cart');
        }

        //-- is user not logged in the redirect to login page
        if (Auth::check() == false) {

            if (!session()->has('url.intended')){
                session(['url.intended' => url()->current()]);
            }

            return redirect()->route('account.login');
        }

        session()->forget('url.intended');

        $states = State::orderBy('name','ASC')->get();

        return view('frontend.checkout',[
            'states' => $states
        ]);
    }
}
