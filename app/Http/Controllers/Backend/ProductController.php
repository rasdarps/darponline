<?php

namespace App\Http\Controllers\Backend;

use App\DataTables\Admin\Settings\ProductDataTable;
use App\Http\Controllers\Controller;
use App\Http\Requests\ProductCreationRequest;
use App\Models\Brand;
use App\Models\Category;
use App\Models\Product;
use App\Models\ProductColor;
use App\Models\TemporaryFile;
use App\Models\Unit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class ProductController extends Controller
{
    function __construct()
    {
         $this->middleware('permission:product-list|product-create|product-edit|product-delete', ['only' => ['index','show']]);
         $this->middleware('permission:product-create', ['only' => ['create','store']]);
         $this->middleware('permission:product-edit', ['only' => ['edit','update']]);
         $this->middleware('permission:product-delete', ['only' => ['destroy']]);
    }

    public function index(ProductDataTable $dataTable){
     
        $categories = Category::orderBy('name', 'asc')->get();


        return $dataTable->render('backend.product.index', compact('categories'));
    } // End Method 

    //barcode lookup method for POS scanning
    public function findByBarcode(Request $request)
    {
        $barcode = $request->input('barcode');
        $product = Product::where('barcode', $barcode)->first();

        if (!$product) {
            return response()->json([
                'status' => 404,
                'message' => 'Product not found for this barcode.',
            ]);
        }

        return response()->json([
            'status' => 200,
            'product' => $product,
        ]);
    }

    public function create(){
        $units = Unit::orderBy('name', 'asc')->get();
        $categories = Category::orderBy('name', 'asc')->get();
        $brands = Brand::where('is_active', true)->orderBy('name', 'asc')->get();
        $colors = ProductColor::orderBy('color_name', 'asc')->get();

        return view('backend.product.create', compact('units', 'categories', 'brands', 'colors'));
    } // End Method 

    public function store(ProductCreationRequest $request){
             // Generate SKU and Barcode
            $sku = $this->generateUniqueSKU($request->name);
            $productData = $request->getProductData($sku);

            
        try {

            DB::transaction(function () use ($productData , $request) {
                $product = Product::create($productData);

                if (empty($product->barcode)) {
                    $barcode = Product::generateBarcode($product->name, $product->id, $product->created_at);
                    $product->barcode = $barcode;
                    $product->saveQuietly();
                }

                if($request->has('featured_image') && $request->featured_image)
                {
                
                
                    
                            $tempFile = TemporaryFile::where('folder', $request->featured_image)->first();
                        if ($tempFile) {

                                
                            $product->addMedia(storage_path('app/public/featured_image/tmp/'. $tempFile->folder . '/' . $tempFile->filename))
                            ->toMediaCollection('featured_image');

                            rmdir(storage_path('app/public/featured_image/tmp/' . $tempFile->folder));
                            $tempFile->delete();
                        }
                

                }

                if($request->has('gallery_images') && $request->gallery_images)
                {
                
                
                    
                            $tempFiles = TemporaryFile::where('folder', $request->gallery_images)->get();
                            $temp_file = TemporaryFile::where('folder', $request->gallery_images)->first();

                            if ($tempFiles) {

                                foreach($tempFiles as $tempFile)
                                {

                                    $product
                                        ->addMedia(storage_path('app/public/gallery_images/tmp/'. $tempFile->folder . '/' . $tempFile->filename))
                                        ->toMediaCollection('gallery_images');
                                        

                                        if(file_exists(storage_path('app/public/gallery_images/tmp/'. $tempFile->folder . '/' . $tempFile->filename))) {
                                            unlink(storage_path('app/public/gallery_images/tmp/'. $tempFile->folder . '/' . $tempFile->filename));
                                        }
                                    
                                        $tempFile->delete();
                                }
                                rmdir(storage_path('app/public/gallery_images/tmp/'. $temp_file->folder ));


                            }
                    
                

                }
            });
            return response()->json([
                'status' => 'success',
                'message' => 'Product created successfully!',
            ], 200);

        }
        catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Validation failed',
                'errors' => $e->errors()
            ], 422);
            
        } 
         catch(\Throwable $e) {

            return response()->json([
                'status' => 400,
                'message' => $e->getMessage(),
            ]); 
        }
    } // End Method 

      /**
     * Generate a unique SKU for the product.
     */
    private function generateUniqueSKU($productName)
    {
        $prefix = strtoupper(substr(preg_replace('/[^a-zA-Z0-9]/', '', $productName), 0, 3));
        $random = strtoupper(Str::random(6));
        $sku = $prefix . '-' . $random;
        
        while (Product::where('sku', $sku)->exists()) {
            $random = strtoupper(Str::random(6));
            $sku = $prefix . '-' . $random;
        }
        
        return $sku;
    }
    
    /**
     * Generate a unique barcode for the product.
     */
    // private function generateUniqueBarcode()
    // {
    //     $barcode = '6' . str_pad(random_int(10000000000, 99999999999), 11, '0', STR_PAD_LEFT);
        
    //     while (Product::where('barcode', $barcode)->exists()) {
    //         $barcode = '6' . str_pad(random_int(10000000000, 99999999999), 11, '0', STR_PAD_LEFT);
    //     }
        
    //     return $barcode;
    // }
    
    /**
     * Display the specified resource.
     *
     * @param  string  $encryptedId
     * @return \Illuminate\Http\Response
     */
    // public function show($encryptedId)
    // {
    //     try {
    //         $id = Crypt::decrypt($encryptedId);
    //         $product = Product::with(['unit', 'category'])->find($id);
            
    //         if (!$product) {
    //             return redirect()->route('products.index')->with('error', 'Product not found.');
    //         }
            
    //         return view('backend.product.show', compact('product'));
    //     } catch (\Exception $e) {
    //         return redirect()->route('products.index')->with('error', 'Invalid product ID.');
    //     }
    // }

     /**
     * Show product details (for quick view modal)
     */
    public function show(string $id)
    {
        try {
            $product = Product::findOrFail($id);
            
            // Return HTML for modal
            $html = view('backend.product.partials.product-details-modal', compact('product','id'))->render();
            
            return response()->json([
                'status' => 'success',
                'html' => $html
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ], 404);
        }
    }

    public function edit(string $encryptedId){
        try {
            $id = Crypt::decrypt($encryptedId);
            $product = Product::with(['unit', 'category'])->find($id);
            
            if (!$product) {
                return redirect()->route('products.index')->with('error', 'Product not found.');
            }
            
          $units = Unit::orderBy('name', 'asc')->get();
            $categories = Category::orderBy('name', 'asc')->get();
            $brands = Brand::where('is_active', true)->orderBy('name', 'asc')->get();
            $colors = ProductColor::orderBy('color_name', 'asc')->get();
            return view('backend.product.edit', compact('product', 'units', 'categories', 'brands', 'colors', 'encryptedId'));
        } catch (\Exception $e) {
            return redirect()->route('products.index')->with('error', 'Invalid product ID.');
        }
    }// End Method 

    public function update(Request $request, string $encryptedId){ 
        try {
            $id = Crypt::decrypt($encryptedId);
            $product = Product::find($id);
            
            if (!$product) {
                return response()->json([
                    'status' => 404,
                    'message' => 'Product not found.',
                ]);
            }

            $validatedData = $request->validate([
                'name' => 'required|string|max:255|unique:products,name,' . $id,
                'barcode'     => 'nullable|string|max:50|unique:products,barcode,' . $id,
                'unit_id' => 'required|exists:units,id',
                'category_id' => 'required|exists:categories,id',
                'stock_level' => 'required|numeric|min:0',
            ], [
                'name.required' => 'Please enter a product name',
                'name.unique' => 'This product already exists',
                'barcode.unique'       => 'This barcode is already registered',
                'barcode.string'       => 'Barcode must be a string',
                'barcode.max'          => 'Barcode must not exceed 50 characters',
                'unit_id.required' => 'Please select a unit',
                'unit_id.exists' => 'Selected unit is invalid',
                'category_id.required' => 'Please select a category',
                'category_id.exists' => 'Selected category is invalid',
                'stock_level.required' => 'Please enter stock level',
                'stock_level.numeric' => 'Stock level must be a number',
                'stock_level.min' => 'Stock level cannot be negative',
            ]);

            try {
                DB::transaction(function () use ($validatedData, $product) {
                    $updateData = [
                        'name' => $validatedData['name'],
                        'barcode'     => $validatedData['barcode'] ?? $product->barcode,
                        'unit_id' => $validatedData['unit_id'],
                        'category_id' => $validatedData['category_id'],
                        'updated_by' => Auth::id(),
                    ];
                    
                    // Update quantity if it's less than the new stock level
                    if ($product->quantity < $validatedData['stock_level']) {
                        $updateData['quantity'] = $validatedData['stock_level'];
                    }
                    
                    $product->update($updateData);
                });

                return response()->json([
                    'status' => 200,
                    'message' => "Product '{$validatedData['name']}' was successfully updated",
                    'redirect' => route('products.index')
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
                'message' => 'Invalid product ID.',
            ]);
        }
    }// End Method 

    /**
     * Toggle product status (active/inactive)
     */
    public function toggleStatus(Request $request)
    {
        $request->validate([
            'id' => 'required|exists:products,id',
            'status' => 'required|boolean'
        ]);
        
        $product = Product::find($request->id);
        $product->is_active = $request->status;
        $product->save();
        
        return response()->json([
            'status' => 'success',
            'message' => 'Product status updated successfully'
        ]);
    }

    /**
     * Toggle online visibility
     */
    public function toggleOnline(Request $request)
    {
        $request->validate([
            'id' => 'required|exists:products,id',
            'status' => 'required|boolean'
        ]);
        
        $product = Product::find($request->id);
        $product->is_online = $request->status;
        $product->save();
        
        return response()->json([
            'status' => 'success',
            'message' => 'Online visibility updated successfully'
        ]);
    }

    /**
     * Toggle featured status
     */
    public function toggleFeatured(Request $request)
    {
        $request->validate([
            'id' => 'required|exists:products,id',
            'status' => 'required|boolean'
        ]);
        
        $product = Product::find($request->id);
        $product->is_featured = $request->status;
        $product->save();
        
        return response()->json([
            'status' => 'success',
            'message' => 'Featured status updated successfully'
        ]);
    }

    public function destroy($encryptedId){
        try {
            $id = Crypt::decrypt($encryptedId);
            $product = Product::find($id);
            
            if (!$product) {
                return response()->json([
                    'status' => 404,
                    'message' => 'Product not found.',
                ]);
            }
            
            $productName = $product->name;
            
            DB::transaction(function () use ($product) {
                $product->delete();
            });
            
            return response()->json([
                'status' => 200,
                'message' => "Product '{$productName}' was successfully deleted.",
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'status' => 400,
                'message' => 'Invalid product ID.',
            ]);
        }
    } // End Method 

}

