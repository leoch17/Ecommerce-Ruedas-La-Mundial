<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\Vehicle;
use App\Models\Suspension;
use App\Models\Tire;
use App\Models\Article;
use App\Models\ArticleImage;
use App\Models\TempImage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Validator;
use Image;

class ArticleController extends Controller{
    public function index(Request $request) {
        $articles = Article::select('articles.*','vehicles.name as vehicleName','suspensions.name as suspensionName','tires.name as tireName')
        ->latest('articles.id')
        ->leftJoin('tires','tires.id','articles.tire_id')
        ->leftJoin('suspensions','suspensions.id','articles.suspension_id')
        ->leftJoin('vehicles','vehicles.id','articles.vehicle_id');

        if(!empty($request->get('keyword'))){
            $articles = $articles->where('name','like','%'.$request->get('keyword').'%');
            $articles = $articles->where('tires.name','like','%'.$request->get('keyword').'%');
            $articles = $articles->orWhere('vehicles.name','like','%'.$request->get('keyword').'%');
            $articles = $articles->orWhere('suspensions.name','like','%'.$request->get('keyword').'%');
        }

        $articles = $articles->paginate(10);
        $data['articles'] = $articles;
        return view('admin.articles.list',compact('articles'));
    }

    public function create() {
        $data = [];
        $vehicles = Vehicle::orderBy('name','ASC')->get();
        $suspensions = Suspension::orderBy('name','ASC')->get();
        $tires = Tire::orderBy('name','ASC')->get();
        $data['vehicles'] = $vehicles;
        $data['suspensions'] = $suspensions;
        $data['tires'] = $tires;
        return view('admin.articles.create',$data);
    }

    public function store(Request $request) {
        $rules = [
            'name' => 'required',
            'slug' => 'required|unique:tires',
            'vehicle'=>'required',
            'suspension'=>'required',
            'tire'=>'required',
            'status' => 'required'
        ];

        $validator = Validator::make($request->all(),$rules);

        if($validator->passes()) {
            $article = new Article();
            $article->name = $request->name;
            $article->slug = $request->slug;
            $article->status = $request->status;
            $article->vehicle_id = $request->vehicle;
            $article->suspension_id = $request->suspension;
            $article->tire_id = $request->tire;
            $article->save();

            $imageArray = explode(",", $request->image_array);

            //Save Gallery Pics
            if(!empty($imageArray)) {
                foreach($imageArray as $temp_image_id) {
                    

                    $tempImageInfo = TempImage::find($temp_image_id);
                    $extArray = explode('.',$tempImageInfo->name);
                    $ext = last($extArray); //like jpg,gif,png etc

                    $articleImage = new ArticleImage();
                    $articleImage->article_id = $article->id;
                    $articleImage->image = 'NULL';
                    $articleImage->save();

                    $imageName = $article->id.'-'.$articleImage->id.'-'.time().'.'.$ext;
                    $articleImage->image = $imageName;
                    $articleImage->save();

                    //Generate Product Thumbnails

                    //Large Image
                    $sourcePath = public_path().'/temp/'.$tempImageInfo->name;
                    $destPath = public_path().'/uploads/article/large/'.$imageName;
                    $image = Image::make($sourcePath);
                    $image->resize(1400, null, function ($constraint) {
                        $constraint->aspectRatio();
                    });
                    $image->save($destPath);

                    //Small Image
                    $destPath = public_path().'/uploads/article/small/'.$imageName;
                    $image = Image::make($sourcePath);
                    $image->fit(300, 300);
                    $image->save($destPath);
                }
            }

            $request->session()->flash('success', 'Artículo creado satisfactoriamente');

            return response([
                'status' => true,
                'message' => 'Artículo creado satisfactoriamente'
            ]);

        } else {
            return response([
                'status' => false,
                'errors' => $validator->errors()
            ]);
        
        }
    }

    public function edit($id, Request $request) {

        $article = Article::find($id);
        if(empty($article)) {
            $request->session()->flash('error','Artículo no encontrado');
            return redirect()->route('articles.index');
        }

        //Fetch Product Images
        $articleImages = ArticleImage::where('article_id',$article->id)->get();

        $suspensions = Suspension::where('vehicle_id',$article->vehicle_id)->get();

        $vehicles = Vehicle::orderBy('name','ASC')->get();
        $suspensions = Suspension::orderBy('name','ASC')->get();
        $tires = Tire::orderBy('name','ASC')->get();
        $data['vehicles'] = $vehicles;
        $data['suspensions'] = $suspensions;
        $data['tires'] = $tires;
        $data['article'] = $article;
        $data['articleImages'] = $articleImages;
        return view('admin.articles.edit',$data);

    }

    public function update($id, Request $request) {

        $article = Article::find($id);
        
        if(empty($article)) {
            $request->session()->flash('error','Artículo no encontrado');
            return response()->json([
                'status' => false,
                'notFound' => true
            ]);
        }

        $validator = Validator::make($request->all(),[
            'name' => 'required',
            'slug' => 'required|unique:articles,slug,'.$article->id.',id',
            'vehicle'=>'required',
            'suspension'=>'required',
            'tire'=>'required',
            'status' => 'required'
        ]);

        if($validator->passes()) {
            
            $article->name = $request->name;
            $article->slug = $request->slug;
            $article->status = $request->status;
            // $suspension->showHome = $request->showHome;
            $article->vehicle_id = $request->vehicle;
            $article->suspension_id = $request->suspension;
            $article->tire_id = $request->tire;
            $article->save();

            $request->session()->flash('success', 'Artículo actualizado satisfactoriamente');

            return response()->json([
                'status' => true,
                'message' => 'Artículo actualizado satisfactoriamente'
            ]);

        } else {
            return response()->json([
                'status' => false,
                'errors' => $validator->errors()
            ]);
        }
    }

    public function destroy($id, Request $request) {
        $article = Article::find($id);

        if(empty($article)) {
            $request->session()->flash('error','Artículo no encontrado');
            return response()->json([
                'status' => true,
                'message' => 'Artículo no encontrado'
            ]);
        }

        $articleImages = ArticleImage::where('article_id',$id)->get();

        if (!empty($articleImages)) {
            foreach($articleImages as $articleImage){
                File::delete(public_path('uploads/article/large/'.$articleImage->image));
                File::delete(public_path('uploads/article/small/'.$articleImage->image));
            }

            ArticleImage::where('article_id',$id)->delete();
        }

        $article->delete();

        $request->session()->flash('success', 'Artículo eliminado satisfactoriamente');

        return response()->json([
            'status' => true,
            'message' => 'Artículo eliminado satisfactoriamente'
        ]);
    }
}


