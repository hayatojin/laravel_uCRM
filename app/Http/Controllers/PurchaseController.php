<?php

namespace App\Http\Controllers;

use App\Http\Requests\StorePurchaseRequest;
use App\Http\Requests\UpdatePurchaseRequest;
use App\Models\Purchase;
use Inertia\Inertia;
use App\Models\Customer;
use App\Models\Item;
use Illuminate\Support\Facades\DB;

class PurchaseController extends Controller
{
    
    public function index()
    {
        //
    }

   
    public function create()
    {
        // $customers = Customer::select('id', 'name', 'kana')->get();
        $items = Item::select('id', 'name', 'price')->where('is_selling', true)->get();

        return Inertia::render('Purchases/create', [
            // 'customers' => $customers, 
            'items' => $items 
        ]);
    }

    
    public function store(StorePurchaseRequest $request)
    {
        // dd($request);

        DB::beginTransaction();

        try{
            $purchase = Purchase::create([
                'customer_id' => $request->customer_id,
                'status' => $request->status
            ]);
    
            // 中間テーブル（Item_Purchase）にレコード生成
            foreach($request->items as $item){
                $purchase->items()->attach($purchase->id, [
                    'item_id' => $item['id'],
                    'quantity' => $item['quantity']
                ]);
            }
        } 
        catch(\Exception $e){
            DB::rollback();
        }

        DB::commit();

        return to_route('dashboard');
    }

    
    public function show(Purchase $purchase)
    {
        //
    }

    
    public function edit(Purchase $purchase)
    {
        //
    }

    
    public function update(UpdatePurchaseRequest $request, Purchase $purchase)
    {
        //
    }

    
    public function destroy(Purchase $purchase)
    {
        //
    }
}
