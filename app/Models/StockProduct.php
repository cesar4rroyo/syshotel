<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class StockProduct extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'stockproducts';
    protected $primaryKey = 'id';

    protected $fillable = [
        'product_id',
        'branch_id',
        'business_id',
        'quantity',
        'min_quantity',
        'max_quantity',
        'alert_quantity',
        'purchase_price',
        'sale_price',
    ];

    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id');
    }

    public function branch()
    {
        return $this->belongsTo(Branch::class, 'branch_id');
    }

    public function business()
    {
        return $this->belongsTo(Business::class, 'business_id');
    }

    public function scopesearch(Builder $query, string $param = null, int $branchId = null, int $businessId = null)
    {
        return $query->whereHas('product', function ($query) use ($param) {
            $query->where('name', 'like', "%$param%");
        })->when($branchId, function ($query, $branchId) {
            return $query->where('branch_id', $branchId);
        })->when($businessId, function ($query, $businessId) {
            return $query->where('business_id', $businessId);
        })->orderBy('id', 'asc');
    }
}
