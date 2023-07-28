<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ProcessType extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'processtypes';
    protected $primaryKey = 'id';
    protected $fillable = [
        'name',
        'description',
        'abbreviation',
    ];

    const SELL_ID = 1;
    const CASH_REGISTER_MOVEMENT_ID = 2;
    const HOTEL_SERVICE_ID = 3;
    const PURCHASE_ID = 4;
    const STOCK_MOVEMENT_ID = 5;
}
