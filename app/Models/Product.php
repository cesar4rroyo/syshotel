<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Query\Builder;

class Product extends Model
{
    use SoftDeletes;

    protected $table = 'products';
    protected $primaryKey = 'id';

    protected $fillable = [
        'name',
        'description',
        'sale_price',
        'purchase_price',
        'branch_id',
        'business_id',
        'category_id',
        'unit_id',
    ];

    public function scopesearch(Builder $query, string $param, int $branch_id = null, int $business_id = null, int  $category_id = null, int $unit_id = null)
    {
        return $query->where('name', 'like', "%$param%")
            ->where('description', 'like', "%$param%")
            ->where('sale_price', 'like', "%$param%")
            ->where('purchase_price', 'like', "%$param%")
            ->where('branch_id', $branch_id)
            ->where('business_id', $business_id)
            ->where('category_id', $category_id)
            ->where('unit_id', $unit_id)
            ->orderBy('name', 'asc');
    }

    public function branch()
    {
        return $this->belongsTo(Branch::class);
    }

    public function business()
    {
        return $this->belongsTo(Business::class);
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function unit()
    {
        return $this->belongsTo(Unit::class);
    }
}