<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Customer;
use Response;

class CustomerController extends Controller
{
    public function index()
    {
        $customers = Customer::all();
        $data      = ["customers" => $customers];
        return Response::json($data,200);
    }

    public function create()
    {
        $input          = $request->all();
        $input['bonus'] = rand(5,20);
        $customer       = new Customer();
        $customer->fill($input)->save();
        $data = ['edit' => '/api/customer/'.$customer->id.'/edit', "customer" => $input];
        return Response::json($data,200);
    }

    public function store(Request $request)
    {
        $input          = $request->all();
        $input['bonus'] = rand(5,20);
        $customer       = new Customer();
        $customer->fill($input)->save();
        $data = ['edit' => '/api/customer/'.$customer->id.'/edit', "customer" => $input];
        return Response::json($data,200);
    }

    public function show($id)
    {
        $customer = Customer::Find($id);
        if ( ! $customer)
        {
            return response()->json([
                'message' => 'Record not found',
            ], 404);
        }
        $data     = ["customer" => $customer];
        return Response::json($data,200);
    }

    public function edit($id)
    {
        //
    }

    public function update(Request $request, $id)
    {
        $customer = Customer::Find($id);
        if ( ! $customer)
        {
            return response()->json([
                'message' => 'Record not found',
            ], 404);
        }
        $input    = $request->all();
        $customer->fill($input)->save();
        $data     = ["message" => "Customer successfully updated", "customer" => $customer];
        return Response::json($data,200);
    }

    public function destroy($id)
    {
        $customer = Customer::Find($id);
        if ( ! $customer)
        {
            return response()->json([
                'message' => 'Record not found',
            ], 404);
        }
        $customer->delete();
        $data     = ["message" => "Customer successfully deleted"];
        return Response::json($data,200);
    }
}
