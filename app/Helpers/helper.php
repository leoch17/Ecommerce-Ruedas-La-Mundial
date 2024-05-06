<?php

use App\Mail\OrderEmail;
use App\Models\Category;
use App\Models\Order;
use App\Models\Page;
use App\Models\ProductImage;
use App\Models\State;
use Illuminate\Support\Facades\Mail;

function getCategories() {
    return Category::orderBy('name', 'ASC')
        ->with('sub_category')
        ->orderBy('id','DESC')
        ->where('status',1)
        ->where('showHome','Yes')
        ->get();
}

function getProductImage($productId){
    return ProductImage::where('product_id', $productId)->first();
}

function orderEmail($orderId, $usertype='customer') {
    $order = Order::where('id',$orderId)->with('items')->first();

    if ($usertype == 'customer') {
        $subject = 'Gracias por su pedido';
        $email = $order->email;
    } else {
        $subject = 'Ha recibido un pedido';
        $email = env('ADMIN_EMAIL');
    }

    $mailData = [
        'subject' => $subject,
        'order' => $order,
        'userType' => $usertype 
    ];

    Mail::to($email)->send(new OrderEmail($mailData));
}

function getStateInfo($id) {
    return State::where('id',$id)->first();
}

function staticPages() {
    $pages = Page::orderBy('name','ASC')->get();
    return $pages;
}
