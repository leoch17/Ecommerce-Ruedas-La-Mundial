<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\Vehicle;
use App\Models\Suspension;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\File;

class SuspensionController extends Controller
{
    public function index(Request $request) {
        $suspensions = Suspension::select('suspensions.*','vehicles.name as vehicleName')
        ->latest('suspensions.id')
        ->leftJoin('vehicles','vehicles.id','suspensions.vehicle_id');

        if(!empty($request->get('keyword'))){
            $suspensions = $suspensions->where('suspensions.name','like','%'.$request->get('keyword').'%');
            $suspensions = $suspensions->orWhere('vehicles.name','like','%'.$request->get('keyword').'%');
        }
        $suspensions = $suspensions->paginate(10);
        return view('admin.suspension.list',compact('suspensions'));
    }

    public function create() {
        $vehicles = Vehicle::orderBy('name','ASC')->get();
        $data['vehicles'] = $vehicles;
        return view('admin.suspension.create',$data);
    }

    public function store(Request $request) {
        $validator = Validator::make($request->all(),[
            'name' => 'required',
            'slug' => 'required|unique:suspensions',
            'vehicle'=>'required',
            'status' => 'required'
        ]);

        if($validator->passes()) {

            $suspension = new Suspension();
            $suspension->name = $request->name;
            $suspension->slug = $request->slug;
            $suspension->status = $request->status;
            $suspension->vehicle_id = $request->vehicle;
            // $suspension->showHome = $request->showHome;
            $suspension->save();

            $request->session()->flash('success', 'Suspensión creada satisfactoriamente');

            return response([
                'status' => true,
                'message' => 'Suspensión creada satisfactoriamente'
            ]);

        } else {
            return response([
                'status' => false,
                'errors' => $validator->errors()
            ]);
        
        }
    }

    public function edit($id, Request $request) {

        $suspension = Suspension::find($id);
        if(empty($suspension)) {
            $request->session()->flash('error','Artículo no encontrado');
            return redirect()->route('suspensions.index');
        }

        $vehicles = Vehicle::orderBy('name','ASC')->get();
        $data['vehicles'] = $vehicles;
        $data['suspension'] = $suspension;
        return view('admin.suspension.edit',$data);
    }

    public function update($id, Request $request) {
        
        $suspension = Suspension::find($id);
        
        if(empty($suspension)) {
            $request->session()->flash('error','Artículo no encontrado');
            return response()->json([
                'status' => false,
                'notFound' => true
            ]);
        }

        $validator = Validator::make($request->all(),[
            'name' => 'required',
            'slug' => 'required|unique:suspensions,slug,'.$suspension->id.',id',
            'vehicle'=>'required',
            'status' => 'required'
        ]);

        if($validator->passes()) {
            
            $suspension->name = $request->name;
            $suspension->slug = $request->slug;
            $suspension->status = $request->status;
            // $suspension->showHome = $request->showHome;
            $suspension->vehicle_id = $request->vehicle;
            $suspension->save();

            $request->session()->flash('success', 'Suspensión actualizada satisfactoriamente');

            return response()->json([
                'status' => true,
                'message' => 'Suspensión actualizada satisfactoriamente'
            ]);

        } else {
            return response()->json([
                'status' => false,
                'errors' => $validator->errors()
            ]);
        
        }
    }

    public function destroy($suspensionId, Request $request) {
        $suspension = Vehicle::find($suspensionId);

        if(empty($suspension)) {
            $request->session()->flash('error','Suspensión no encontrada');
            return response()->json([
                'status' => true,
                'message' => 'Suspensión no encontrada'
            ]);
        }

        File::delete(public_path().'/uploads/suspension/thumb/'.$suspension->image);
        File::delete(public_path().'/uploads/suspension/'.$suspension->image);

        $suspension->delete();

        $request->session()->flash('success', 'Suspensión eliminada satisfactoriamente');

        return response()->json([
            'status' => true,
            'message' => 'Suspensión eliminada satisfactoriamente'
        ]);
    }
}
