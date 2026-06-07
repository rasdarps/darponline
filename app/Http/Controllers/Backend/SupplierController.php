<?php

namespace App\Http\Controllers\Backend;

use App\Models\Supplier;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;

class SupplierController extends Controller
{
    function __construct()
    {
         $this->middleware('permission:supplier-list|supplier-create|supplier-edit|supplier-delete', ['only' => ['index','show']]);
         $this->middleware('permission:supplier-create', ['only' => ['create','store']]);
         $this->middleware('permission:supplier-edit', ['only' => ['edit','update']]);
         $this->middleware('permission:supplier-delete', ['only' => ['destroy']]);
    }

    public function index(){
        $supplier = Supplier::orderBy('name', 'asc')->get();
        return view('backend.supplier.index', compact('supplier'));
    } // End Method 

    public function create(){
        return view('backend.supplier.create');
    } // End Method 

    public function store(Request $request){
        $validatedData = $request->validate([
            'name' => 'required|string|max:255|unique:suppliers,name',
            'mobile_no' => 'required|string|max:15|unique:suppliers,mobile_no',
            'email' => 'nullable|email|max:255|unique:suppliers,email',
            'address' => 'nullable|string|max:500',
            'city' => 'nullable|string|max:100',
            'type' => 'nullable|string|in:Distributor,Wholesaler,Retailer,Manufacturer,Other',
        ], [
            'name.required' => 'Please enter a supplier name',
            'name.unique' => 'This supplier name already exists',
            'mobile_no.required' => 'Please enter a mobile number',
            'mobile_no.unique' => 'This mobile number already exists',
            'email.email' => 'Please enter a valid email address',
            'email.unique' => 'This email address already exists',
            'type.in' => 'Please select a valid supplier type',
        ]);

        try {
            // Use only validated data and add created_by
            $data = $validatedData;
            $data['created_by'] = Auth::id();
            
            // Use a transaction to ensure data integrity
            DB::transaction(function () use ($data) {
                Supplier::create($data);
            });

            return response()->json([
                'status' => 200,
                'message' => "Supplier '{$data['name']}' was successfully created",
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
            $supplier = Supplier::find($id);
            
            if (!$supplier) {
                return redirect()->route('suppliers.index')->with('error', 'Supplier not found.');
            }
            
            return view('backend.supplier.show', compact('supplier'));
        } catch (\Exception $e) {
            return redirect()->route('suppliers.index')->with('error', 'Invalid supplier ID.');
        }
    }

    public function edit($encryptedId){
        try {
            $id = Crypt::decrypt($encryptedId);
            $supplier = Supplier::find($id);
            
            if (!$supplier) {
                return redirect()->route('suppliers.index')->with('error', 'Supplier not found.');
            }
            
            return view('backend.supplier.edit', compact('supplier'));
        } catch (\Exception $e) {
            return redirect()->route('suppliers.index')->with('error', 'Invalid supplier ID.');
        }
    }// End Method 

    public function update(Request $request, $encryptedId){ 
        try {
            $id = Crypt::decrypt($encryptedId);
            $supplier = Supplier::find($id);
            
            if (!$supplier) {
                return response()->json([
                    'status' => 404,
                    'message' => 'Supplier not found.',
                ]);
            }

            $validatedData = $request->validate([
                'name' => 'required|string|max:255|unique:suppliers,name,' . $id,
                'mobile_no' => 'required|string|max:15|unique:suppliers,mobile_no,' . $id,
                'email' => 'nullable|email|max:255|unique:suppliers,email,' . $id,
                'address' => 'nullable|string|max:500',
                'city' => 'nullable|string|max:100',
                'type' => 'nullable|string|in:Distributor,Wholesaler,Retailer,Manufacturer,Other',
            ], [
                'name.required' => 'Please enter a supplier name',
                'name.unique' => 'This supplier name already exists',
                'mobile_no.required' => 'Please enter a mobile number',
                'mobile_no.unique' => 'This mobile number already exists',
                'email.email' => 'Please enter a valid email address',
                'email.unique' => 'This email address already exists',
                'type.in' => 'Please select a valid supplier type',
            ]);

            try {
                DB::transaction(function () use ($validatedData, $supplier) {
                    $updateData = [
                        'name' => $validatedData['name'],
                        'mobile_no' => $validatedData['mobile_no'],
                        'email' => $validatedData['email'],
                        'address' => $validatedData['address'],
                        'city' => $validatedData['city'],
                        'type' => $validatedData['type'],
                        'updated_by' => Auth::id(),
                    ];
                    
                    $supplier->update($updateData);
                });

                return response()->json([
                    'status' => 200,
                    'message' => "Supplier '{$validatedData['name']}' was successfully updated",
                    'redirect' => route('suppliers.index')
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
                'message' => 'Invalid supplier ID.',
            ]);
        }
    }// End Method 

    public function destroy($encryptedId){
        try {
            $id = Crypt::decrypt($encryptedId);
            $supplier = Supplier::find($id);
            
            if (!$supplier) {
                return response()->json([
                    'status' => 404,
                    'message' => 'Supplier not found.',
                ]);
            }
            
            $supplierName = $supplier->name;
            
            DB::transaction(function () use ($supplier) {
                $supplier->delete();
            });
            
            return response()->json([
                'status' => 200,
                'message' => "Supplier '{$supplierName}' was successfully deleted.",
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'status' => 400,
                'message' => 'Invalid supplier ID.',
            ]);
        }
    } // End Method 

    
}
