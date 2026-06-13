<?php

namespace App\Http\Controllers;

use App\Models\TemporaryFile;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class UploadController extends Controller
{
    public static function generateCode()
    {
        return uniqid();
    }

    public function store(Request $request)
    {


        $file_categories = ['category_image', 'product','featured_image', 'gallery_images' ];

        try{
        
            // dd("dksdj");
            foreach($file_categories as $category)
            {
                
                
                if($request->hasFile($category))
                {
    
                    if($category == "category_image")
                    {
                        $file = $request->file($category);
                       
                        $filename = $file->getClientOriginalName();
                        $folder = uniqid() . '-' . now()->timestamp;
                        $file->storeAs('public/category/tmp/' . $folder, $filename);
                        TemporaryFile::create([
                            'folder' => $folder,
                            'filename' => $filename,
    
                        ]);
                    }

                    elseif($category == "featured_image")
                    {
                        
                        $file = $request->file($category);
                       
                        $filename = $file->getClientOriginalName();
                        $folder = uniqid() . '-' . now()->timestamp;
                        $file->storeAs('public/featured_image/tmp/' . $folder, $filename);
                        TemporaryFile::create([
                            'folder' => $folder,
                            'filename' => $filename,
    
                        ]);
                    }
                  
                  
                    elseif($category == "gallery_images")
                    {

                        $code = $this::generateCode();
                        foreach($request->file($category) as $upload)
                        {

                            $file = $upload;
                            $filename = $file->getClientOriginalName();
                            $folder =  $request->uniqueId;
                            $file->storeAs('public/gallery_images/tmp/' . $folder, $filename);

                            TemporaryFile::create([
                                'folder' => $folder,
                                'filename' => $filename,
        
                            ]);
                        }

                    }
                    else{
    
                        $file = $request->file($category);
                        $filename = $file->getClientOriginalName();
                        $folder = uniqid() . '-' . now()->timestamp;
                        $file->storeAs('public/uploads/tmp/' . $folder, $filename);

                        TemporaryFile::create([
                            'folder' => $folder,
                            'filename' => $filename,
    
                        ]);
                    }
    
                   
    
                    return $folder;
                }
            }
    
            return '';
        }
        catch(Exception $e){
            return $e->getMessage();
        }
       
    }


    public function temp_delete(Request $request)
    {
        $tmp = TemporaryFile::where('folder', $request->getContent())->first();
        if($tmp)
        {
            Storage::deleteDirectory('public/tmp/' . $tmp->folder);
            $tmp->delete();
            return response('');
        }
    }

}
