<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use App\Models\Vehicle;
use App\Models\TempImage;
use Illuminate\Support\Facades\File;
use Image;

class VehicleController extends Controller
{
    public function index(Request $request) {
        $vehicles = Vehicle::latest();

        if(!empty($request->get('keyword'))){
            $vehicles = $vehicles->where('name','like','%'.$request->get('keyword').'%');
        }
        $vehicles = $vehicles->paginate(10);
        
        return view('admin.vehicle.list',compact('vehicles'));
    }

    public function create() {
        return view('admin.vehicle.create');
    }

    public function store(Request $request) {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'slug' => 'required|unique:vehicles',
        ]);

        if ($validator->passes()) {
            
            $vehicle = new Vehicle();
            $vehicle->name = $request->name;
            $vehicle->slug = $request->slug;
            $vehicle->status = $request->status;
            // $vehicle->showHome = $request->showHome;
            $vehicle->save();


            // Save Image Here
            if(!empty($request->image_id)){
                $tempImage = TempImage::find($request->image_id);
                $extArray = explode('.',$tempImage->name);
                $ext = last($extArray);

                $newImageName = $vehicle->id.'.'.$ext;
                $sPath = public_path().'/temp/'.$tempImage->name;
                $dPath = public_path().'/uploads/vehicle/thumb/'.$newImageName;
                File::copy($sPath,$dPath);

                // Generate Image Thumbnail
                // $dPath = public_path().'/uploads/vehicle/thumb/'.$newImageName;
                // $img = Image::make($sPath);
                //$img->resize(450, 600);
                // $img->fit(450, 600, function ($constraint) {
                //     $constraint->upsize();
                // });
                // $img->save($dPath);

                $vehicle->image = $newImageName;
                $vehicle->save();
            }

            $request->session()->flash('success', 'Vehículo añadido satisfactoriamente');

            return response()->json([
                'status' => true,
                'message' => 'Vehículo añadido satisfactoriamente'
            ]);

        }else{
            return response()->json([
                'status' => false,
                'errors' => $validator->errors()
            ]);
        }
    }

    public function edit($vehicleId, Request $request) {
        $vehicle = Vehicle::find($vehicleId);
        if(empty($vehicle)) {
            return redirect()->route('vehicles.index');
        }

        return view('admin.vehicle.edit',compact('vehicle'));
    }

    public function update($vehicleId, Request $request) {
        $vehicle = Vehicle::find($vehicleId);

        if(empty($vehicle)) {
            $request->session()->flash('error', 'Vehículo no encontrado');
            return response()->json([
                'status' => false,
                'notFound' => true,
                'message' => 'Vehículo no encontrado'
            ]);
        }

        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'slug' => 'required|unique:vehicles,slug,'.$vehicle->id.',id',
        ]);

        if ($validator->passes()) {
            
            $vehicle->name = $request->name;
            $vehicle->slug = $request->slug;
            $vehicle->status = $request->status;
            $vehicle->save();

            $oldImage = $vehicle->image;

            // Save Image Here
            if(!empty($request->image_id)){
                $tempImage = TempImage::find($request->image_id);
                $extArray = explode('.',$tempImage->name);
                $ext = last($extArray);

                $newImageName = $vehicle->id.'-'.time().'.'.$ext;
                $sPath = public_path().'/temp/'.$tempImage->name;
                $dPath = public_path().'/uploads/vehicle/'.$newImageName;
                File::copy($sPath,$dPath);

                // Generate Image Thumbnail
                $dPath = public_path().'/uploads/vehicle/thumb/'.$newImageName;
                $img = Image::make($sPath);
                $img->fit(450, 600, function ($constraint) {
                    $constraint->upsize();
                });
                $img->save($dPath);

                $vehicle->image = $newImageName;
                $vehicle->save();

                //Delete Old Images Here
                File::delete(public_path().'/uploads/vehicle/thumb/'.$oldImage);
                File::delete(public_path().'/uploads/vehicle/'.$oldImage);


            }

            $request->session()->flash('success', 'Vehículo actualizado satisfactoriamente');

            return response()->json([
                'status' => true,
                'message' => 'Vehículo actualizado satisfactoriamente'
            ]);

        }else{
            return response()->json([
                'status' => false,
                'errors' => $validator->errors()
            ]);
        }
    }

    public function destroy($vehicleId, Request $request) {
        $vehicle = Vehicle::find($vehicleId);

        if(empty($vehicle)) {
            $request->session()->flash('error','Vehículo no encontrado');
            return response()->json([
                'status' => true,
                'message' => 'Vehículo no encontrado'
            ]);
        }

        File::delete(public_path().'/uploads/vehicle/thumb/'.$vehicle->image);
        File::delete(public_path().'/uploads/vehicle/'.$vehicle->image);

        $vehicle->delete();

        $request->session()->flash('success', 'Vehículo eliminado satisfactoriamente');

        return response()->json([
            'status' => true,
            'message' => 'Vehículo eliminado satisfactoriamente'
        ]);
    }
}
