<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\SubCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\File;

class SubCategoryController extends Controller
{
    public function index(Request $request) {
        $subCategories = SubCategory::select('sub_categories.*','categories.name as categoryName')
        ->latest('sub_categories.id')
        ->leftJoin('categories','categories.id','sub_categories.category_id');

        if(!empty($request->get('keyword'))){
            $subCategories = $subCategories->where('sub_categories.name','like','%'.$request->get('keyword').'%');
            $subCategories = $subCategories->orWhere('categories.name','like','%'.$request->get('keyword').'%');
        }
        $subCategories = $subCategories->paginate(10);
        return view('admin.sub_category.list',compact('subCategories'));
    }

    public function create() {
        $categories = Category::orderBy('name','ASC')->get();
        $data['categories'] = $categories;
        return view('admin.sub_category.create',$data);
    }

    public function store(Request $request) {
        $validator = Validator::make($request->all(),[
            'name' => 'required',
            'slug' => 'required|unique:sub_categories',
            'category'=>'required',
            'status' => 'required'
        ]);

        if($validator->passes()) {

            $subCategory = new SubCategory();
            $subCategory->name = $request->name;
            $subCategory->slug = $request->slug;
            $subCategory->status = $request->status;
            $subCategory->category_id = $request->category;
            $subCategory->showHome = $request->showHome;
            $subCategory->save();

            $request->session()->flash('success', 'Sub Categoría creada satisfactoriamente');

            return response([
                'status' => true,
                'message' => 'Sub Categoría creada satisfactoriamente'
            ]);

        } else {
            return response([
                'status' => false,
                'errors' => $validator->errors()
            ]);
        
        }
    }

    public function edit($id, Request $request) {

        $subCategory = SubCategory::find($id);
        if(empty($subCategory)) {
            $request->session()->flash('error','Registro no encontrado');
            return redirect()->route('sub-categories.index');
        }

        $categories = Category::orderBy('name','ASC')->get();
        $data['categories'] = $categories;
        $data['subCategory'] = $subCategory;
        return view('admin.sub_category.edit',$data);
    }

    public function update($id, Request $request) {
        
        $subCategory = SubCategory::find($id);
        
        if(empty($subCategory)) {
            $request->session()->flash('error','Registro no encontrado');
            return response()->json([
                'status' => false,
                'notFound' => true
            ]);
        }

        $validator = Validator::make($request->all(),[
            'name' => 'required',
            'slug' => 'required|unique:sub_categories,slug,'.$subCategory->id.',id',
            'category'=>'required',
            'status' => 'required'
        ]);

        if($validator->passes()) {
            
            $subCategory->name = $request->name;
            $subCategory->slug = $request->slug;
            $subCategory->status = $request->status;
            $subCategory->showHome = $request->showHome;
            $subCategory->category_id = $request->category;
            $subCategory->save();

            $request->session()->flash('success', 'Sub Categoría actualizada satisfactoriamente');

            return response()->json([
                'status' => true,
                'message' => 'Sub Categoría actualizada satisfactoriamente'
            ]);

        } else {
            return response()->json([
                'status' => false,
                'errors' => $validator->errors()
            ]);
        
        }
    }

    public function destroy($subCategoryId, Request $request) {
        $subCategory = Category::find($subCategoryId);

        if(empty($subCategory)) {
            $request->session()->flash('error','Sub Categoría no encontrada');
            return response()->json([
                'status' => true,
                'message' => 'Sub Categoría no encontrada'
            ]);
        }

        File::delete(public_path().'/uploads/category/thumb/'.$subCategory->image);
        File::delete(public_path().'/uploads/category/'.$subCategory->image);

        $subCategory->delete();

        $request->session()->flash('success', 'Sub Categoría eliminada satisfactoriamente');

        return response()->json([
            'status' => true,
            'message' => 'Sub Categoría eliminada satisfactoriamente'
        ]);
    }
}
