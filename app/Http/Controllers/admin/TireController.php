<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\Vehicle;
use App\Models\Suspension;
use App\Models\Tire;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\File;

class TireController extends Controller
{
    public function index(Request $request) {
        $tires = Tire::select('tires.*','vehicles.name as vehicleName','suspensions.name as suspensionName')
        ->latest('tires.id')
        ->leftJoin('suspensions','suspensions.id','tires.suspension_id')
        ->leftJoin('vehicles','vehicles.id','tires.vehicle_id');

        // $tirees = Tire::select('tires.*','suspensions.name as suspensionName')
        // ->latest('tires.id')
        // ->leftJoin('suspensions','suspensions.id','tires.suspension_id');

        if(!empty($request->get('keyword'))){
            $tires = $tires->where('tires.name','like','%'.$request->get('keyword').'%');
            $tires = $tires->orWhere('vehicles.name','like','%'.$request->get('keyword').'%');
            $tires = $tires->orWhere('suspensions.name','like','%'.$request->get('keyword').'%');
        }
        $tires = $tires->paginate(10);
        return view('admin.tire.list',compact('tires'));
        
    }

    public function create() {
        $vehicles = Vehicle::orderBy('name','ASC')->get();
        $suspensions = Suspension::orderBy('name','ASC')->get();
        $data['vehicles'] = $vehicles;
        $data['suspensions'] = $suspensions;
        return view('admin.tire.create',$data);

        }

    public function store(Request $request) {
        $validator = Validator::make($request->all(),[
            'name' => 'required',
            'slug' => 'required|unique:tires',
            'vehicle'=>'required',
            'suspension'=>'required',
            'status' => 'required'
        ]);

        if($validator->passes()) {

            $tire = new Tire();
            $tire->name = $request->name;
            $tire->slug = $request->slug;
            $tire->status = $request->status;
            $tire->vehicle_id = $request->vehicle;
            $tire->suspension_id = $request->suspension;
            // $suspension->showHome = $request->showHome;
            $tire->save();

            $request->session()->flash('success', 'Neumático creado satisfactoriamente');

            return response([
                'status' => true,
                'message' => 'Neumático creado satisfactoriamente'
            ]);

        } else {
            return response([
                'status' => false,
                'errors' => $validator->errors()
            ]);
        
        }
    }

    public function edit($id, Request $request) {

        $tire = Tire::find($id);
        if(empty($tire)) {
            $request->session()->flash('error','Artículo no encontrado');
            return redirect()->route('tires.index');
        }

        $vehicles = Vehicle::orderBy('name','ASC')->get();
        $suspensions = Suspension::orderBy('name','ASC')->get();
        $data['vehicles'] = $vehicles;
        $data['suspensions'] = $suspensions;
        $data['tire'] = $tire;
        return view('admin.tire.edit',$data);

        
    }

    public function update($id, Request $request) {
        
        $tire = Tire::find($id);
        
        if(empty($tire)) {
            $request->session()->flash('error','Neumático no encontrado');
            return response()->json([
                'status' => false,
                'notFound' => true
            ]);
        }

        $validator = Validator::make($request->all(),[
            'name' => 'required',
            'slug' => 'required|unique:tires,slug,'.$tire->id.',id',
            'vehicle'=>'required',
            'suspension'=>'required',
            'status' => 'required'
        ]);

        if($validator->passes()) {
            
            $tire->name = $request->name;
            $tire->slug = $request->slug;
            $tire->status = $request->status;
            // $suspension->showHome = $request->showHome;
            $tire->vehicle_id = $request->vehicle;
            $tire->suspension_id = $request->suspension;
            $tire->save();

            $request->session()->flash('success', 'Neumático actualizado satisfactoriamente');

            return response()->json([
                'status' => true,
                'message' => 'Neumático actualizado satisfactoriamente'
            ]);

        } else {
            return response()->json([
                'status' => false,
                'errors' => $validator->errors()
            ]);
        
        }
    }

    public function destroy($tireId, Request $request) {
        $tire = Vehicle::find($tireId);

        if(empty($tire)) {
            $request->session()->flash('error','Neumático no encontrado');
            return response()->json([
                'status' => true,
                'message' => 'Neumático no encontrado'
            ]);
        }

        File::delete(public_path().'/uploads/tire/thumb/'.$tire->image);
        File::delete(public_path().'/uploads/tire/'.$tire->image);

        $tire->delete();

        $request->session()->flash('success', 'Neumático eliminado satisfactoriamente');

        return response()->json([
            'status' => true,
            'message' => 'Neumático eliminado satisfactoriamente'
        ]);
    }
}
