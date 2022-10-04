<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Customer;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Purchase>
 */
class PurchaseFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        $decade = $this->faker->dateTimeThisDecade; // 過去10年分のダミーデータ作成
        $created_at = $decade->modify('+2 years');  // +2年 = 過去8年、未来2年のダミーデータ作成

        return [
            'customer_id' => rand(1, Customer::count()), // カスタマーテーブルから、ランダムでIDを1件ずつ生成
            'status' => $this->faker->boolean,
            'created_at' => $created_at
        ];
    }
}
