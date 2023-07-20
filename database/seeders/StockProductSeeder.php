<?php

namespace Database\Seeders;

use App\Models\Product;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class StockProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $totalProducts = Product::count();

        for ($i = 1; $i <= $totalProducts; $i++) {
            $quantity = rand(1, 100);
            $minQuantity = rand(1, 100);
            $maxQuantity = rand(1, 100);
            $alertQuantity = rand(1, 100);
            $purchasePrice = rand(1, 100);
            $salePrice = rand(1, 100);

            $data = [
                'product_id' => $i,
                'branch_id' => 1,
                'business_id' => 1,
                'quantity' => $quantity,
                'min_quantity' => $minQuantity,
                'max_quantity' => $maxQuantity,
                'alert_quantity' => $alertQuantity,
                'purchase_price' => $purchasePrice,
                'sale_price' => $salePrice,
            ];

            DB::table('stockproducts')->insert($data);
        }
    }
}
