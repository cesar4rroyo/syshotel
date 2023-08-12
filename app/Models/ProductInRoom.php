<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ProductInRoom extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'productinrooms';
    protected $fillable = [
        'product_id',
        'service_id',
        'room_id',
        'process_id',
        'branch_id',
        'business_id',
        'quantity',
        'purchase_price',
        'sale_price',
        'total_purchase_price',
    ];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function room()
    {
        return $this->belongsTo(Room::class);
    }

    public function process()
    {
        return $this->belongsTo(Process::class);
    }

    public function branch()
    {
        return $this->belongsTo(Branch::class);
    }

    public function business()
    {
        return $this->belongsTo(Business::class);
    }
}
