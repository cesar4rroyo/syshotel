<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class StockMovementDetail extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'stockmovementdetails';
    protected $primaryKey = 'id';

    protected $fillable = [
        'stockmovement_id',
        'product_id',
        'initialbranch_id',
        'finalbranch_id',
        'quantity',
        'business_id'
    ];

    public function stockmovement()
    {
        return $this->belongsTo(StockMovement::class, 'stockmovement_id');
    }
}