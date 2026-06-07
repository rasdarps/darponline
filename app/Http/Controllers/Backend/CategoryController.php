<?php

namespace App\Http\Controllers\Backend;

use App\DataTables\Admin\Settings\CategoryDataTable;
use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;


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
        ], [
            'name.required' => 'Please enter a Category name',
            'name.unique' => 'This category already exists',
        ]);

        try {
            // Use only validated data and add created_by
            $data = $validatedData;
            $data['created_by'] = Auth::id();
            
            // Use a transaction to ensure data integrity
            DB::transaction(function () use ($data) {
                Category::create($data);
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
            $category = Category::find($id);
            
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
            ], [
                'name.required' => 'Please enter a category name',
                'name.unique' => 'This category already exists',
            ]);

            try {
                DB::transaction(function () use ($validatedData, $category) {
                    $category->update([
                        'name' => $validatedData['name'],
                        'updated_by' => Auth::id(),
                    ]);
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
            $category = Category::find($id);
            
            if (!$category) {
                return response()->json([
                    'status' => 404,
                    'message' => 'Category not found.',
                ]);
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
                'message' => 'Invalid category ID.',
            ]);
        }

    } // End Method 


}
