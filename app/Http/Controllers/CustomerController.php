<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Customer;
use App\Transaction;
use Response;
use DB;

class CustomerController extends Controller
{
    public function index()
    {
        $customers = Customer::all();
        $data = ["customers" => $customers];
        return Response::json($data, 200);
    }

    public function create(Request $request)
    {
        $input = $request->all();
        $input['bonus'] = rand(5, 20);
        $customer = new Customer();
        $customer->fill($input)->save();
        $data = ['edit' => '/api/customer/' . $customer->id . '/edit', "customer" => $input];
        return Response::json($data, 200);
    }

    public function store(Request $request)
    {
        $input = $request->all();
        $input['bonus'] = rand(5, 20);
        $customer = new Customer();
        $customer->fill($input)->save();
        $data = ['edit' => '/api/customer/' . $customer->id . '/edit', "customer" => $input];
        return Response::json($data, 200);
    }

    public function show($id)
    {
        $customer = Customer::Find($id);
        if (!$customer) {
            return response()->json([
                'message' => 'Record not found',
            ], 404);
        }
        $data = ["customer" => $customer];
        return Response::json($data, 200);
    }

    public function edit($id)
    {
        //
    }

    public function update(Request $request, $id)
    {
        $customer = Customer::Find($id);
        if (!$customer) {
            return response()->json([
                'message' => 'Record not found',
            ], 404);
        }
        $input = $request->all();
        $customer->fill($input)->save();
        $data = ["message" => "Customer successfully updated", "customer" => $customer];
        return Response::json($data, 200);
    }

    public function destroy($id)
    {
        $customer = Customer::Find($id);
        if (!$customer) {
            return response()->json([
                'message' => 'Record not found',
            ], 404);
        }
        $customer->delete();
        $data = ["message" => "Customer successfully deleted"];
        return Response::json($data, 200);
    }

    /**
     * @param Request $request
     * @param $id
     * @return mixed
     */
    public function deposit(Request $request, $id)
    {
        $customer = Customer::Find($id);
        if (!$customer) {
            return response()->json([
                'message' => 'Record not found',
            ], 404);
        }

        $input = $request->all();

        $transaction = New Transaction();
        if (isset($input['type'])) {
            $transaction['type'] = $input['type'];
        }

        if (isset($input['balance'])) {
            $transaction['balance'] = $input['balance'];
        }

        if (isset($input['bonus'])) {
            $transaction['bonus'] = $input['bonus'];
        }

        if (isset($input['currency'])) {
            $transaction['currency'] = $input['currency'];
        }

        // Count number of transaction
        $count = $customer->transaction->count();


        //  $count%3 == 2 for every third element
        if ($count > 0 && ($count + 1) % 3 === 0) {
            $transaction['bonus'] = (($customer->bonus * $transaction['balance']) / 100);
        } else {
            $transaction['bonus'] = 0;
        }

        $customerId = array($customer->id);
        $transaction->save();
        $transaction->customer()->sync($customerId);

        $type = "Deposit";

        $data = ["type" => $type, "transaction" => $transaction, "customer" => $customer];
        return Response::json($data, 200);
    }

    public function withdraw(Request $request, $id)
    {
        $customer = Customer::Find($id);
        if (!$customer) {
            return response()->json([
                'message' => 'Record not found',
            ], 404);
        }

        $input = $request->all();

        $transaction = New Transaction();
        if (isset($input['type'])) {
            $transaction['type'] = $input['type'];
        }

        if (isset($input['balance'])) {
            $transaction['balance'] = $input['balance'];
        }


        if (isset($input['currency'])) {
            $transaction['currency'] = $input['currency'];
        }

        // set bonus to zero
        $transaction['bonus'] = 0;

        $balances = $customer->transaction;
        $deposit = (int)0;
        $withdraw = (int)0;
        foreach ($balances as $balance) {
            // get total balance of deposit
            if ($balance->type == 0) {
                $deposit += $balance->balance;
            }

            // get total balance of withdraw
            if ($balance->type == 1) {
                $withdraw += $balance->balance;
            }
        }

        $totalBalance = (int)$deposit - (int)$withdraw;

        if ($transaction['balance'] <= $totalBalance) {
            $customerId = array($customer->id);
            $transaction->save();
            $transaction->customer()->sync($customerId);
        } else {
            return response()->json([
                'message' => 'You can only withdraw ' . ($totalBalance) . " " . $balance->currency,
            ], 200);
        }

        $type = "Withdraw";

        $data = ["type" => $type, "transaction" => $transaction, "customer" => $customer];

        return Response::json($data, 200);
    }

    public function report()
    {
        $customers = Customer::get();

        $uniqueCustomers = array();
        $result = Customer::with('transaction')->where('country', '=', 'mk')->get();

        $uniqueTransactions = count($result[0]->transaction);
        $transactions = $result[0]->transaction;

        $deposit = (int)0;
        $withdraw = (int)0;

        foreach ($transactions as $transaction) {
            // get total balance of deposit
            if ($transaction->type == 0) {
                $deposit += $transaction->balance;
            }

            // get total balance of withdraw
            if ($transaction->type == 1) {
                $withdraw += $transaction->balance;
            }
        }

        foreach($customers as $customer) {
            $uniqueCustomers[] = DB::table('customer_transaction')->where('customer_id', '=', $customer->id)->get();
        }

        $uniqueCustomers = count($uniqueCustomers);

        $data = ["customers" => $customers, "unique" => $uniqueCustomers, "uniqueTransactions" => $uniqueTransactions, "deposit" => $deposit, "withdraw" => $withdraw];
        return view('welcome')->with($data);
    }


}
