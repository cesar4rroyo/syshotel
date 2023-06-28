<?php

namespace App\Http\Services;

use App\Models\StockProduct;
use Illuminate\Support\Collection;

class StockProductService
{
    private StockProduct $stock;

    public function __construct(private int $businessId, private int $branchId)
    {
        $this->stock = new StockProduct();
    }

    public function getStocks(int $branchId, int|null $productId = null): Collection
    {
        return $this->stock->where('business_id', $this->businessId)
            ->where('branch_id', $branchId)
            ->when($productId, function ($query) use ($productId) {
                $query->where('product_id', $productId);
            })
            ->with('product')
            ->get();
    }

    public function moveAllStockToSpecificBranch(int $branchId): Collection
    {
        $this->stock->where('business_id', $this->businessId)
            ->where('branch_id', $this->branchId)
            ->update(['branch_id' => $branchId]);

        return $this->getStocks($branchId);
    }

    public function moveStockToSpecificBranch(int $branchId, int $productId): Collection
    {
        $this->stock->where('business_id', $this->businessId)
            ->where('branch_id', $this->branchId)
            ->where('product_id', $productId)
            ->update(['branch_id' => $branchId]);

        return $this->getStocks($branchId);
    }

    public function increaseStock(int $branchId, int $productId, int $quantity): Collection
    {
        $stock = $this->stock->where('business_id', $this->businessId)
            ->where('branch_id', $branchId)
            ->where('product_id', $productId)
            ->first();

        if ($stock) {
            $stock->quantity += $quantity;
            $stock->save();
        } else {
            $this->stock->create([
                'business_id' => $this->businessId,
                'branch_id' => $branchId,
                'product_id' => $productId,
                'quantity' => $quantity
            ]);
        }

        return $this->getStocks($branchId, $productId);
    }

    public function decreaseStock(int $branchId, int $productId, int $quantity): Collection
    {
        $stock = $this->stock->where('business_id', $this->businessId)
            ->where('branch_id', $branchId)
            ->where('product_id', $productId)
            ->first();

        if ($stock) {
            $stock->quantity -= $quantity;
            $stock->save();
        } else {
            $this->stock->create([
                'business_id' => $this->businessId,
                'branch_id' => $branchId,
                'product_id' => $productId,
                'quantity' => -$quantity
            ]);
        }

        return $this->getStocks($branchId, $productId);
    }
}