<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreCustomerRequest;
use App\Http\Requests\UpdateCustomerRequest;
use App\Models\Customer;
use Inertia\Inertia;
use Illuminate\Http\Request;

class CustomerController extends Controller
{
    public function index(Request $request)
    {
        $customers = Customer::searchCustomers($request->search)
        ->select('id', 'name', 'kana', 'tel')->paginate(50);

        // dd($customers);

        return Inertia::render('Customers/index', [ 'customers' => $customers ]);
    }


    public function create()
    {
        return Inertia::render('Customers/create');
    }

    
    public function store(StoreCustomerRequest $request)
    {
        Customer::create([
            'name' => $request->name,
            'kana' => $request->kana,
            'tel' => $request->tel,
            'email' => $request->email,
            'postcode' => $request->postcode,
            'address' => $request->address,
            'birthday' => $request->birthday,
            'gender' => $request->gender,
            'memo' => $request->memo
        ]);

        return to_route('customers.index')
        ->with([
            'message' => '登録しました。',
            'status' => 'success'
        ]);
    }

    
    public function show(Customer $customer)
    {
        //
    }

    
    public function edit(Customer $customer)
    {
        //
    }

    
    public function update(UpdateCustomerRequest $request, Customer $customer)
    {
        //
    }

    
    public function destroy(Customer $customer)
    {
        //
    }
}
