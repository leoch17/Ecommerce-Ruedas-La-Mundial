<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\State;
use App\Models\ShippingCharge;
use Illuminate\Support\Facades\Validator;

class ShippingController extends Controller
{
    public function create() {
        $states = State::get();
        $data['states'] = $states;

        $shippingCharges = ShippingCharge::select('shipping_charges.*','states.name')
                            ->leftJoin('states','states.id','shipping_charges.state_id')->get();

        $data['shippingCharges'] = $shippingCharges;
        return view('admin.shipping.create',$data);
    }

    public function store(Request $request) {

        $validator = Validator::make($request->all(),[
            'state' => 'required',
            'amount' => 'required|numeric'
        ]);

        if ($validator->passes()) {

            $count = ShippingCharge::where('state_id',$request->state)->count();

            if ($count > 0) {
                session()->flash('error','Envío ya añadido');
                return response()->json([
                    'status' => true,
                ]);
            }

            $shipping = new ShippingCharge;
            $shipping->state_id = $request->state;
            $shipping->amount = $request->amount;
            $shipping->save();

            session()->flash('success','Envío añadido satisfactoriamente');

            return response()->json([
                'status' => true,
            ]);

        } else {
            return response()->json([
                'status' => false,
                'errors' => $validator->errors()
            ]);
        }
    }

    public function edit($id) {

        $shippingCharge = ShippingCharge::find($id);

        $states = State::get();
        $data['states'] = $states;
        $data['shippingCharge'] = $shippingCharge;
        

        return view('admin.shipping.edit',$data);
    }

    public function update($id, Request $request) {

        $shipping = ShippingCharge::find($id);

        $validator = Validator::make($request->all(),[
            'state' => 'required',
            'amount' => 'required|numeric'
        ]);

        if ($validator->passes()) {

            if ($shipping == null) {
                session()->flash('error','Envío no encontrado');
    
                return response()->json([
                    'status' => true,
                ]);
            }

            $shipping->state_id = $request->state;
            $shipping->amount = $request->amount;
            $shipping->save();

            session()->flash('success','Envío actualizado satisfactoriamente');

            return response()->json([
                'status' => true,
            ]);

        } else {
            return response()->json([
                'status' => false,
                'errors' => $validator->errors()
            ]);
        }
    }

    public function destroy($id) {

        $shippingCharge = ShippingCharge::find($id);

        if ($shippingCharge == null) {
            session()->flash('error','Envío no encontrado');

            return response()->json([
                'status' => true,
            ]);
        }

        $shippingCharge->delete();

        session()->flash('success','Envío eliminado satisfactoriamente');

        return response()->json([
            'status' => true,
        ]);
    }
}
