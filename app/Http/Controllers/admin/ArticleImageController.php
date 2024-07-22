<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\ArticleImage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Image;

class ArticleImageController extends Controller
{
    public function update(Request $request)
    {

        $image = $request->image;
        $ext = $image->getClientOriginalExtension();
        $sourcePath = $image->getPathName();

        $articleImage = new ArticleImage();
        $articleImage->article_id = $request->article_id;
        $articleImage->image = 'NULL';
        $articleImage->save();

        $imageName = $request->article_id . '-' . $articleImage->id . '-' . time() . '.' . $ext;
        $articleImage->image = $imageName;
        $articleImage->save();

        //Large Image
        $destPath = public_path() . '/uploads/article/large/' . $imageName;
        $image = Image::make($sourcePath);
        $image->resize(1400, null, function ($constraint) {
            $constraint->aspectRatio();
        });
        $image->save($destPath);

        //Small Image
        $destPath = public_path() . '/uploads/article/small/' . $imageName;
        $image = Image::make($sourcePath);
        $image->fit(300, 300);
        $image->save($destPath);

        return response()->json([
            'status' => true,
            'image_id' => $articleImage->id,
            'ImagePath' => asset('uploads/article/small/' . $articleImage->image),
            'message' => 'Imagen guardada satisfactoriamente'
        ]);
    }

    public function destroy(Request $request)
    {
        $articleImage = ArticleImage::find($request->id);

        if (empty($articleImage)) {
            return response()->json([
                'status' => false,
                'message' => 'Imagen guardada satisfactoriamente'
            ]);
        }

        // Imagenes borradas desde la carpeta
        File::delete(public_path('uploads/article/large/' . $articleImage->image));
        File::delete(public_path('uploads/article/small/' . $articleImage->image));

        $articleImage->delete();

        return response()->json([
            'status' => true,
            'message' => 'Imagen no encontrada'
        ]);
    }
}
