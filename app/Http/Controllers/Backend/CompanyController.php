<?php

namespace App\Http\Controllers\Backend;

use App\DataTables\Admin\Settings\CompanyDataTable;
use App\Http\Controllers\Controller;
use App\Models\Company;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;

class CompanyController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(CompanyDataTable $dataTable)
    {
        //
        return $dataTable->render('backend.company.index');

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(CompanyDataTable $dataTable)
    {
        //
        return $dataTable->render('backend.company.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
         $validatedData = $request->validate([
            'name' => 'required|string|max:255|unique:companies,name,',
            'address' => 'required|string|max:255,',
            'phone' => 'required|numeric|digits:10|unique:companies,phone',
            'email' => 'nullable|email|unique:companies,email',
        ], [
            'name.required' => 'Please enter a Company name',
            'name.unique' => 'This company already exists',
            'address.required' => 'Please enter a Company address',
            'phone.required' => 'Please enter a Company phone number',
            'phone.unique' => 'This company phone number is already in use',
            'phone.digits' => 'Phone number must be 10 digits',
            'phone.numeric' => 'Phone number must be numeric',
            'email.email' => 'Please enter a valid Company email address',
            'email.unique' => 'This company email is already in use',
        ]);

        try {
            // Use only validated data and add created_by
            $data = $validatedData;
            $data['created_by'] = Auth::id();
            
            // Use a transaction to ensure data integrity
            DB::transaction(function () use ($data) {
                Company::create($data);
            });

            return response()->json([
                'status' => 200,
                'message' => "Company '{$data['name']}' was successfully created",
            ]);

        } catch(\Throwable $e) {
            return response()->json([
                'status' => 400,
                'message' => $e->getMessage(),
            ]); 
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(string $id)
    {
        //
        $company = Company::findOrFail(Crypt::decrypt($id)); // if using encrypted IDs
        return view('backend.company.edit', compact('company'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
