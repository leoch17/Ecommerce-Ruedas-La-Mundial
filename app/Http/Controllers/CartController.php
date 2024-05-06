<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\State;
use App\Models\CustomerAddress;
use App\Models\DiscountCoupon;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\ShippingCharge;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
// use Illuminate\Support\Carbon;
use Gloudemans\Shoppingcart\Facades\Cart;
use Illuminate\Support\Facades\Validator;

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

        $discount = 0;


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

        $customerAddress = CustomerAddress::where('user_id',Auth::user()->id)->first();

        session()->forget('url.intended');

        $states = State::orderBy('name','ASC')->get();

        $subTotal = Cart::subtotal(2,'.','');

        // Aplicar descuento aquí
        if (session()->has('code')) {
            $code = session()->get('code');

            if ($code->type == 'percent') {
                $discount = ($code->discount_amount/100)*$subTotal;
            } else {
                $discount = $code->discount_amount;
            }
        }

        
        // Calcular envíos aquí
        if ($customerAddress != '') {
            $userState = $customerAddress->state_id;
            $shippingInfo = ShippingCharge::where('state_id', $userState)->first();

            $totalQty = 0;
            $totalShippingCharge = 0;
            $grandTotal = 0;
            foreach (Cart::content() as $item) {
                $totalQty += $item->qty;
            }

            $totalShippingCharge = $totalQty*$shippingInfo->amount;
            $grandTotal = ($subTotal-$discount)+$totalShippingCharge;

        } else {
            $grandTotal = ($subTotal-$discount);
            $totalShippingCharge = 0;
        }
        
        return view('frontend.checkout',[
            'states' => $states,
            'customerAddress' => $customerAddress,
            'totalShippingCharge' => $totalShippingCharge,
            'discount' => $discount,
            'grandTotal' => $grandTotal
        ]);
    }

    public function processCheckout(Request $request) {

        //step - 1 Apply Validation

        $validator = Validator::make($request->all(),[
            'first_name'=>'required',
            'last_name'=>'required',
            'email'=>'required|email',
            'state'=>'required',
            'address'=>'required',
            'city'=>'required',
            'zip'=>'required',
            'mobile'=>'required',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Por favor arregla los errores',
                'status' => false,
                'errors' => $validator->errors()
            ]);
        }

        //step - 2 save user address

        $user = Auth::user();

        CustomerAddress::updateOrCreate(
            ['user_id' => $user->id],
            [
                'user_id' => $user->id,
                'first_name' => $request->first_name,
                'last_name' => $request->last_name,
                'email' => $request->email,
                'mobile' => $request->mobile,
                'state_id' => $request->state,
                'address' => $request->address,
                'apartment' => $request->apartment,
                'city' => $request->city,
                'zip' => $request->zip,
            ]
        );

        //step - 3 store data in orders table

        if ($request->payment_method == 'cod') {

            $discountCodeId = NULL;
            $promoCode = '';
            $shipping = 0;
            $discount = 0;
            $subTotal = Cart::subtotal(2,'.','');
            $grandTotal = $subTotal+$shipping;

            // Aplicar descuento aquí
            if (session()->has('code')) {
                $code = session()->get('code');

                if ($code->type == 'percent') {
                    $discount = ($code->discount_amount/100)*$subTotal;
                } else {
                    $discount = $code->discount_amount;
                }

                $discountCodeId = $code->id;
                $promoCode = $code->code;
            }

            //Calculate shipping
            $shippingInfo = ShippingCharge::where('state_id',$request->state)->first();

            $totalQty = 0;
            foreach (Cart::content() as $item) {
                $totalQty += $item->qty;
            }

            if ($shippingInfo != null) {
                $shipping = $totalQty*$shippingInfo->amount;
                $grandTotal = ($subTotal-$discount)+$shipping;

            } else {
                $shippingInfo = ShippingCharge::where('state_id','international')->first();
                $shipping = $totalQty*$shippingInfo->amount;
                $grandTotal = ($subTotal-$discount)+$shipping;
            }

            $order = new Order;
            $order->subtotal = $subTotal;
            $order->shipping = $shipping;
            $order->grand_total = $grandTotal;
            $order->discount = $discount;
            $order->coupon_code_id = $discountCodeId;
            $order->coupon_code = $promoCode;
            $order->payment_status = 'not paid';
            $order->status = 'pending';
            $order->user_id = $user->id;
            $order->first_name = $request->first_name;
            $order->last_name = $request->last_name;
            $order->email = $request->email;
            $order->mobile = $request->mobile;
            $order->address = $request->address;
            $order->apartment = $request->apartment;
            $order->city = $request->city;
            $order->zip = $request->zip;
            $order->notes = $request->order_notes;
            $order->state_id = $request->state;
            $order->save();

            //step - 4 store order items in order items table
            foreach (Cart::content() as $item) {
                $orderItem = new OrderItem;
                $orderItem->product_id = $item->id;
                $orderItem->order_id = $order->id;
                $orderItem->name = $item->name;
                $orderItem->qty = $item->qty;
                $orderItem->price = $item->price;
                $orderItem->total = $item->price*$item->qty;
                $orderItem->save();

                //Update Product Stock
                $productData = Product::find($item->id);
                if ($productData->track_qty == 'Yes') {
                    $currentQty = $productData->qty;
                    $updateQty =  $currentQty-$item->qty;
                    $productData->qty = $updateQty;
                    $productData->save();
                }
               
            }

            //Send Order Email
            orderEmail($order->id,'customer');

            session()->flash('success','Usted ha realizado correctamente su pedido');

            Cart::destroy();

            session()->forget('code');

            return response()->json([
                'message' => 'Pedido guardado satisfactoriamente',
                'orderId' => $order->id,
                'status' => true
            ]);

        } else {
            //
        }
    }

    public function thankyou($id) {
        return view('frontend.thanks', [
            'id' => $id
        ]);
    }

    public function getOrderSummery(Request $request) {
        $subTotal = Cart::subtotal(2,'.','');
        $discount = 0;
        $discountString = '';

        // Aplicar descuento aquí
        if (session()->has('code')) {
            $code = session()->get('code');

            if ($code->type == 'percent') {
                $discount = ($code->discount_amount/100)*$subTotal;
            } else {
                $discount = $code->discount_amount;
            }

            $discountString = '<div class="mt-4" id="discount-response">
                <strong>'.session()->get('code')->code.'</strong>
                <a class="btn btn-sm btn-danger" id="remove-discount"><i class="fa fa-times"></i></a>
            </div>';

        }

        if ($request->state_id > 0) {            

            $shippingInfo = ShippingCharge::where('state_id',$request->state_id)->first();

            $totalQty = 0;
            foreach (Cart::content() as $item) {
                $totalQty += $item->qty;
            }

            if ($shippingInfo != null) {
                
                $shippingCharge = $totalQty*$shippingInfo->amount;
                $grandTotal = ($subTotal-$discount)+$shippingCharge;

                return response()->json([
                    'status' => true,
                    'grandTotal' => number_format($grandTotal,2),
                    'discount' => number_format($discount,2),
                    'discountString' => $discountString,
                    'shippingCharge' => number_format($shippingCharge,2),
                ]);

            } else {
                $shippingInfo = ShippingCharge::where('state_id','international')->first();

                $shippingCharge = $totalQty*$shippingInfo->amount;
                $grandTotal = ($subTotal-$discount)+$shippingCharge;

                return response()->json([
                    'status' => true,
                    'grandTotal' => number_format($grandTotal,2),
                    'discount' => number_format($discount,2),
                    'discountString' => $discountString,
                    'shippingCharge' => number_format($shippingCharge,2),
                ]);
            }

        } else {

            return response()->json([
                'status' => true,
                'grandTotal' => number_format(($subTotal-$discount),2),
                'discount' => number_format($discount,2),
                'discountString' => $discountString,
                'shippingCharge' => number_format(0,2),
            ]);
        }
    }

    public function applyDiscount(Request $request) {

        $code = DiscountCoupon::where('code',$request->code)->first();

        if ($code == null) {
            return response()->json([
                'status' => false,
                'message' => 'Cupón de descuento inválido',
            ]);
        }

        // Comprobación de si la fecha de comienzo del cupón es válido o no

        $now = Carbon::now();

        if ($code->starts_at != "") {
            $startDate = Carbon::createFromFormat('Y-m-d H:i:s',$code->starts_at);

            if ($now->lt($startDate)) {
                return response()->json([
                    'status' => false,
                    'message' => 'Cupón de descuento inválido',
                ]);
            }
        }  
        
        if ($code->expires_at != "") {
            $endDate = Carbon::createFromFormat('Y-m-d H:i:s',$code->expires_at);

            if ($now->gt($endDate)) {
                return response()->json([
                    'status' => false,
                    'message' => 'Cupón de descuento inválido',
                ]);
            }
        }  

        // Comprobación de Usos máximo
        if ($code->max_uses > 0) {
            $couponUsed = Order::where('coupon_code_id', $code->id)->count();

            if ($couponUsed >= $code->max_uses) {
                return response()->json([
                    'status' => false,
                    'message' => 'Cupón de descuento inválido',
                ]);
            }
        }
        
        // Comprobación de máximo uso de usarios 
        if ($code->max_uses_user > 0) { 
            $couponUsedByUser = Order::where(['coupon_code_id' => $code->id, 'user_id' => Auth::user()->id])->count();

            if ($couponUsedByUser >= $code->max_uses_user) {
                return response()->json([
                    'status' => false,
                    'message' => 'Ya usaste este código de cupón',
                ]);
            }
        }

        $subTotal = Cart::subtotal(2,'.','');

        // Comprobación de la condición de cantidad mínima
        if ($code->min_amount > 0) {
            if ($subTotal < $code->min_amount) {
                return response()->json([
                    'status' => false,
                    'message' => 'Tu cantidad mínima debe ser $'.$code->min_amount.'.',
                ]);
            }
        }

        session()->put('code',$code);

        return $this->getOrderSummery($request);
    }

    public function removeCoupon(Request $request) {
        session()->forget('code');
        return $this->getOrderSummery($request);
    }
}
