<?php

namespace App\Http\Controllers\Backend;

use App\DataTables\Admin\Settings\CategoryDataTable;
use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\TemporaryFile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;


class CategoryController extends Controller
{
    // public function index(){

    //     $categoris = Category::orderBy('name', 'asc')->get();
    //     return view('backend.category.index',compact('categoris'));

    // } 

    public function index(CategoryDataTable $dataTable)
    {
        //
        return $dataTable->render('backend.category.index');
    }


    public function create(){

     return view('backend.category.create');
    } // End Mehtod 


    public function store(Request $request){

        $validatedData = $request->validate([
            'name' => 'required|string|max:255|unique:categories,name,',
            'description' => 'nullable|string',
            'show_on_homepage' => 'boolean',
            'category_image' => 'required',
        ], [
            'name.required' => 'Please enter a Category name',
            'name.unique' => 'This category already exists',
            'category_image.required' => 'Please upload a category image',
        ]);

        try {
            // Use only validated data and add created_by
            $data = $validatedData;
            $data['created_by'] = Auth::id();
            
            // Use a transaction to ensure data integrity
            DB::transaction(function () use ($data, $request) {
               $category = Category::create($data);
                if($request->category_image)
                {
                   
                        $tempFile = TemporaryFile::where('folder', $request->category_image)->first();
                        if ($tempFile) {

                                
                            $category->addMedia(storage_path('app/public/category/tmp/'. $tempFile->folder . '/' . $tempFile->filename))
                            ->withResponsiveImages()->toMediaCollection('category_image');

                            rmdir(storage_path('app/public/category/tmp/' . $tempFile->folder));
                            $tempFile->delete();
                        }
                }
                
            });
           
            

            return response()->json([
                'status' => 200,
                'message' => "Category '{$data['name']}' was successfully created",
            ]);

        } catch(\Throwable $e) {
            return response()->json([
                'status' => 400,
                'message' => $e->getMessage(),
            ]); 
        }
    } // End Method 

     public function edit($encryptedId){
        try {
            $id = Crypt::decrypt($encryptedId);
            $category = Category::findorfail($id);
            
            if (!$category) {
                return redirect()->route('categories.index')->with('error', 'Category not found.');
            }
            
            return view('backend.category.edit', compact('category'));
        } catch (\Exception $e) {
            return redirect()->route('categories.index')->with('error', 'Invalid category ID.');
        }
    }// End Method 


     public function update(Request $request, $encryptedId){ 
        try {
            $id = Crypt::decrypt($encryptedId);
            $category = Category::find($id);
            
            if (!$category) {
                return response()->json([
                    'status' => 404,
                    'message' => 'Category not found.',
                ]);
            }

            $validatedData = $request->validate([
                'name' => 'required|string|max:255|unique:categories,name,' . $id,
                'description' => 'nullable|string',
                'show_on_homepage' => 'boolean',
            ], [
                'name.required' => 'Please enter a category name',
                'name.unique' => 'This category already exists',
            ]);


            $validatedData['updated_by'] = Auth::id();
            try {
                DB::transaction(function () use ($validatedData, $category) {
                    $category->update($validatedData);
                });

                return response()->json([
                    'status' => 200,
                    'message' => "Category '{$validatedData['name']}' was successfully updated",
                    'redirect' => route('categories.index')
                ]);

            } catch(\Throwable $e) {
                return response()->json([
                    'status' => 400,
                    'message' => $e->getMessage(),
                ]);
            }
        } catch (\Exception $e) {
            return response()->json([
                'status' => 400,
                'message' => 'Invalid category ID.',
            ]);
        }

    }// End Method 


    public function destroy($encryptedId){
        try {
            $id = Crypt::decrypt($encryptedId);
            $category = Category::findOrFail($id);
            

            
            
            if (!$category) {
                return response()->json([
                    'status' => 404,
                    'message' => 'Category not found.',
                ]);
            } 
            // Check if category has products
            if ($category->products()->count() > 0) {
               return response()->json([
                   'status' => 422,
                   'message' => 'Cannot delete category with associated products. Reassign products first.'
               ], 422);
           }
            
            $categoryName = $category->name;
            
            DB::transaction(function () use ($category) {
                $category->delete();
            });
            
            return response()->json([
                'status' => 200,
                'message' => "Category '{$categoryName}' was successfully deleted.",
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'status' => 400,
                'message' => 'Failed to delete category',
            ]);
        }

    } // End Method 

    public function updateWebsiteStatus(Request $request)
    {

        $request->validate([
            'id' => 'required|exists:categories,id',
            'show_on_homepage' => 'required|boolean'
        ]);

        try {
            // Decrypt ID if it's encrypted
            $id = $request->id;
            if (is_string($id) && strpos($id, 'eyJ') === 0) {
                $id = Crypt::decrypt($id);
            }
            
            $category = Category::findOrFail($id);
            $category->show_on_homepage = $request->show_on_homepage;
            $category->save();
            
            return response()->json([
                'status' => 'success',
                'message' => 'Category visibility updated successfully',
                'data' => [
                    'id' => $category->id,
                    'show_on_homepage' => $category->show_on_homepage
                ]
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to update category visibility: ' . $e->getMessage()
            ], 500);
        }
    }
    
    /**
     * Bulk update website visibility
     */
    public function bulkUpdateWebsiteStatus(Request $request)
    {
        $request->validate([
            'ids' => 'required|array',
            'ids.*' => 'exists:categories,id',
            'show_on_homepage' => 'required|boolean'
        ]);
        
        try {
            $updatedCount = Category::whereIn('id', $request->ids)
                ->update(['show_on_homepage' => $request->show_on_homepage]);
            
            return response()->json([
                'status' => 'success',
                'message' => 'Categories updated successfully',
                'updated_count' => $updatedCount
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to update categories'
            ], 500);
        }
    }

}
