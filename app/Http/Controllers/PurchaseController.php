<?php

namespace App\Http\Controllers;

use App\Http\Requests\StorePurchaseRequest;
use App\Http\Requests\UpdatePurchaseRequest;
use App\Models\Purchase;
use Inertia\Inertia;
use App\Models\Customer;
use App\Models\Item;
use Illuminate\Support\Facades\DB;
use App\Models\Order;

class PurchaseController extends Controller
{
    // 購買履歴の一覧
    public function index()
    {
        // dd(Order::paginate(50));

        $orders = Order::groupBy('id')
        ->selectRaw('id, sum(subtotal) as total, 
        customer_name, status, created_at') //集計関数使ってるため、生のSQL書く必要あり  //生のSQL書くにはselectRaw使う
        ->paginate(50);

        // dd($orders);

        return Inertia::render('Purchases/index', ['orders' => $orders ]);
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
            DB::commit();

            return to_route('dashboard');
        }

        catch(\Exception $e){
            DB::rollback();
        }
    }

    
    public function show(Purchase $purchase)
    {
        // 商品ごとの小計
        $items = Order::where('id', $purchase->id )->get();

        // 合計（小計を合算したもの）
        $order = Order::groupBy('id')
        ->where('id', $purchase->id )
        ->selectRaw('id, sum(subtotal) as total, 
        customer_name, status, created_at')
        ->get();

        // dd($items, $order);

        return Inertia::render('Purchases/show', [
            'items' => $items,
            'order' => $order
        ]);
    }

    
    public function edit(Purchase $purchase)
    {
        $purchase = Purchase::find($purchase->id); //$purchaseとは、ビュー側から渡ってくるパラメータのpurchaseのこと。引数で受け取ってるやつ
        $allItems = Item::select('id', 'name', 'price')->get();

        $items = [];

        foreach($allItems as $allItem){
            $quantity = 0; //数を一旦0にしておいて、情報があれば更新していける状態を作る
            
            foreach($purchase->items as $item){ //商品数のデータは中間テーブルに入っているため、$purchase->itemsとする
                if($allItem->id === $item->id){
                    $quantity = $item->pivot->quantity;
                }
            }
            array_push($items, [
                'id' => $allItem->id,
                'name' => $allItem->name,
                'price' => $allItem->price,
                'quantity' => $quantity
            ]);
        }
        // dd($items);

        $order = Order::groupBy('id')
        ->where('id', $purchase->id )
        ->selectRaw('id, customer_id,
        customer_name, status, created_at')
        ->get();

        return Inertia::render('Purchases/edit', [
            'items' => $items,
            'order' => $order
        ]);
    }

    
    public function update(UpdatePurchaseRequest $request, Purchase $purchase)
    {
        DB::beginTransaction();
        // dd($request, $purchase);

        try{
        // purchaseテーブルから更新対象となる情報は、ステータスのみ
        $purchase->status = $request->status;
        $purchase->save();

        // 商品数の編集（中間テーブルのデータ更新のため、sync()を使う）
        $items = [];

        foreach($request->items as $item){
            $items = $items + [
                $item['id'] => [
                    'quantity' => $item['quantity']
                ]
            ];
        }

        // dd($items);

        $purchase->items()->sync($items);

        DB::commit();

        return to_route('dashboard');
        }

        catch(\Exception $e){
            DB::rollback();
        }
    }

    
    public function destroy(Purchase $purchase)
    {
        //
    }
}
