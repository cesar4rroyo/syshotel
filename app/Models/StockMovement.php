<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class StockMovement extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'stockmovements';
    protected $primaryKey = 'id';

    protected $fillable = [
        'date',
        'description',
        'type',
        'status',
        'user_id',
        'business_id'
    ];

    public function details()
    {
        return $this->hasMany(StockMovementDetail::class, 'stockmovement_id');
    }
}