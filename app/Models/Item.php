<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Purchase;

class Item extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'memo',
        'price',
        'is_selling',
    ];

    // 購入情報テーブルへのリレーション（多対多）
    public function purchases()
    {
        return $this->belongsToMany(Purchase::class)
        ->withPivot('quantity');  // 中間テーブルの情報を取得（1回の購入で複数の商品を買えるように、数量カラムを取得）
    }
}
