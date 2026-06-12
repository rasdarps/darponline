<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Brand;
use App\Models\Category;
use App\Models\Product;
use App\Models\Unit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;

class ProductController extends Controller
{
    function __construct()
    {
         $this->middleware('permission:product-list|product-create|product-edit|product-delete', ['only' => ['index','show']]);
         $this->middleware('permission:product-create', ['only' => ['create','store']]);
         $this->middleware('permission:product-edit', ['only' => ['edit','update']]);
         $this->middleware('permission:product-delete', ['only' => ['destroy']]);
    }

    public function index(){
        $product = Product::with(['unit', 'category'])
                          ->orderBy('name', 'asc')
                          ->get();
        return view('backend.product.index', compact('product'));
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
        $unit = Unit::orderBy('name', 'asc')->get();
        $category = Category::orderBy('name', 'asc')->get();
        $brands = Brand::where('is_active', true)->orderBy('name', 'asc')->get();

        return view('backend.product.create', compact('unit', 'category', 'brands'));
    } // End Method 

    public function store(Request $request){
        $validatedData = $request->validate([
            'name' => 'required|string|max:255|unique:products,name',
            'barcode'     => 'nullable|string|max:50|unique:products,barcode',
            'unit_id' => 'required|exists:units,id',
            'category_id' => 'required|exists:categories,id',
            'stock_level' => 'required|numeric|min:0',
        ], [
            'name.required' => 'Please enter a Product name',
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
            // Use only validated data and add created_by
            $data = $validatedData;
            $data['created_by'] = Auth::id();
            $data['quantity'] = $validatedData['stock_level']; // Set initial quantity to stock level
            
            // Use a transaction to ensure data integrity
            DB::transaction(function () use ($data) {
                Product::create($data);
            });

            return response()->json([
                'status' => 200,
                'message' => "Product '{$data['name']}' was successfully created",
            ]);

        } catch(\Throwable $e) {
            return response()->json([
                'status' => 400,
                'message' => $e->getMessage(),
            ]); 
        }
    } // End Method 

    /**
     * Display the specified resource.
     *
     * @param  string  $encryptedId
     * @return \Illuminate\Http\Response
     */
    public function show($encryptedId)
    {
        try {
            $id = Crypt::decrypt($encryptedId);
            $product = Product::with(['unit', 'category'])->find($id);
            
            if (!$product) {
                return redirect()->route('products.index')->with('error', 'Product not found.');
            }
            
            return view('backend.product.show', compact('product'));
        } catch (\Exception $e) {
            return redirect()->route('products.index')->with('error', 'Invalid product ID.');
        }
    }

    public function edit($encryptedId){
        try {
            $id = Crypt::decrypt($encryptedId);
            $product = Product::with(['unit', 'category'])->find($id);
            
            if (!$product) {
                return redirect()->route('products.index')->with('error', 'Product not found.');
            }
            
            $unit = Unit::orderBy('name', 'asc')->get();
            $category = Category::orderBy('name', 'asc')->get();
            
            return view('backend.product.edit', compact('product', 'unit', 'category'));
        } catch (\Exception $e) {
            return redirect()->route('products.index')->with('error', 'Invalid product ID.');
        }
    }// End Method 

    public function update(Request $request, $encryptedId){ 
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

    // Legacy methods for backward compatibility (if needed)
    public function ProductAll(){
        return $this->index();
    }

    public function ProductAdd(){
        return $this->create();
    }

    public function ProductStore(Request $request){
        return $this->store($request);
    }

    public function ProductEdit($id){
        return $this->edit(Crypt::encrypt($id));
    }

    public function ProductUpdate(Request $request){
        $encryptedId = Crypt::encrypt($request->id);
        return $this->update($request, $encryptedId);
    }

    public function ProductDelete($id){
        return $this->destroy(Crypt::encrypt($id));
    }
}
