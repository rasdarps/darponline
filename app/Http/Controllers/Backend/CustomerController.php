<?php

namespace App\Http\Controllers\Backend;

use App\DataTables\Admin\Settings\CustomerDataTable;
use App\Http\Controllers\Controller;
use App\Models\Customer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class CustomerController extends Controller
{
    function __construct()
    {
                $this->middleware('permission:customer-list', ['only' => ['index','show']]);
         $this->middleware('permission:customer-create', ['only' => ['create','store']]);
                $this->middleware('permission:customer-edit', ['only' => ['edit','update']]);
         $this->middleware('permission:customer-delete', ['only' => ['destroy']]);
    }

    // public function index(){
    //     $customer = Customer::orderBy('name', 'asc')->get();
    //     return view('backend.customer.index', compact('customer'));
    // } // End Method
    public function index(CustomerDataTable $dataTable)
    {
        //
        return $dataTable->render('backend.customer.index');
    }

    public function create(){
        return view('backend.customer.create');
    } // End Method

    public function store(Request $request){
        $validatedData = $request->validate([
            'name' => ['required', 'string', 'max:255', Rule::unique('customers', 'name')->whereNull('deleted_at')],
            'mobile_no' => ['required', 'string', 'max:15', Rule::unique('customers', 'mobile_no')->whereNull('deleted_at')],
            'email' => ['nullable', 'email', 'max:255', Rule::unique('customers', 'email')->whereNull('deleted_at')],
            'address' => 'nullable|string|max:500',
        ], [
            'name.required' => 'Please enter a customer name',
            'name.unique' => 'This customer name already exists',
            'mobile_no.required' => 'Please enter a mobile number',
            'mobile_no.unique' => 'This mobile number already exists',
            'email.email' => 'Please enter a valid email address',
            'email.unique' => 'This email address already exists',
        ]);

        try {
            // Use only validated data and add created_by
            $data = $validatedData;
            $data['created_by'] = Auth::id();
            
            // Use a transaction to ensure data integrity
            DB::transaction(function () use ($data) {
                Customer::create($data);
            });

            return response()->json([
                'status' => 200,
                'message' => "Customer '{$data['name']}' was successfully created",
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
            $customer = Customer::find($id);
            
            if (!$customer) {
                return redirect()->route('customers.index')->with('error', 'Customer not found.');
            }
            
            return view('backend.customer.show', compact('customer'));
        } catch (\Exception $e) {
            return redirect()->route('customers.index')->with('error', 'Invalid customer ID.');
        }
    }

    public function edit($encryptedId){
        try {
            $id = Crypt::decrypt($encryptedId);
            $customer = Customer::find($id);
            
            if (!$customer) {
                return redirect()->route('customers.index')->with('error', 'Customer not found.');
            }
            
            return view('backend.customer.edit', compact('customer'));
        } catch (\Exception $e) {
            return redirect()->route('customers.index')->with('error', 'Invalid customer ID.');
        }
    }// End Method

    public function update(Request $request, $encryptedId){ 
        try {
            $id = Crypt::decrypt($encryptedId);
            $customer = Customer::find($id);
            
            if (!$customer) {
                return response()->json([
                    'status' => 404,
                    'message' => 'Customer not found.',
                ]);
            }

            $validatedData = $request->validate([
                'name' => 'required|string|max:255|unique:customers,name,' . $id,
                'mobile_no' => 'required|string|max:15|unique:customers,mobile_no,' . $id,
                'email' => 'nullable|email|max:255|unique:customers,email,' . $id,
                'address' => 'nullable|string|max:500',
            ], [
                'name.required' => 'Please enter a customer name',
                'name.unique' => 'This customer name already exists',
                'mobile_no.required' => 'Please enter a mobile number',
                'mobile_no.unique' => 'This mobile number already exists',
                'email.email' => 'Please enter a valid email address',
                'email.unique' => 'This email address already exists',
            ]);

            try {
                DB::transaction(function () use ($validatedData, $customer) {
                    $updateData = [
                        'name' => $validatedData['name'],
                        'mobile_no' => $validatedData['mobile_no'],
                        'email' => $validatedData['email'],
                        'address' => $validatedData['address'],
                        'updated_by' => Auth::id(),
                    ];
                    
                    $customer->update($updateData);
                });

                return response()->json([
                    'status' => 200,
                    'message' => "Customer '{$validatedData['name']}' was successfully updated",
                    'redirect' => route('customers.index')
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
                'message' => 'Invalid customer ID.',
            ]);
        }
    }// End Method

    public function destroy($encryptedId){
        try {
            $id = Crypt::decrypt($encryptedId);
            $customer = Customer::find($id);

            if (!$customer) {
                $message = 'Customer not found.';
                if (request()->expectsJson() || request()->ajax()) {
                    return response()->json(['status' => 404, 'message' => $message], 404);
                }
                return redirect()->route('customers.index')->with(['message' => $message, 'alert-type' => 'error']);
            }

            if ($customer->payments()->count() > 0) {
                $message = 'Cannot delete customer with associated Sale or payment records.';
                if (request()->expectsJson() || request()->ajax()) {
                    return response()->json(['status' => 422, 'message' => $message], 422);
                }
                return redirect()->route('customers.index')->with(['message' => $message, 'alert-type' => 'error']);
            }

            $customerName = $customer->name;

            DB::transaction(function () use ($customer) {
                $customer->delete();
            });

            $message = "Customer '{$customerName}' was successfully deleted.";

            if (request()->expectsJson() || request()->ajax()) {
                return response()->json([
                    'status' => 200,
                    'message' => $message,
                ]);
            }

            return redirect()->route('customers.index')->with([
                'message' => $message,
                'alert-type' => 'success',
            ]);

        } catch (\Exception $e) {
            $message = 'Failed to delete customer.';

            if (request()->expectsJson() || request()->ajax()) {
                return response()->json([
                    'status' => 400,
                    'message' => $message,
                ], 400);
            }

            return redirect()->route('customers.index')->with([
                'message' => $message,
                'alert-type' => 'error',
            ]);
        }
    } // End Method


}

