<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Customer;
use App\Models\Item;

class Purchase extends Model
{
    use HasFactory;

    protected $fillable = [
        'customer_id',
        'status',
    ];

    // 顧客テーブルへのリレーション（1対多）
    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    // 商品テーブルへのリレーション（多対多）
    public function items()
    {
        return $this->belongsToMany(Item::class)
        ->withPivot('quantity');  // 中間テーブルの情報を取得（1回の購入で複数の商品を買えるように、数量カラムを取得）
    }
}
