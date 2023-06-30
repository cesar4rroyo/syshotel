<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PaymentType extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'paymenttypes';
    protected $primaryKey = 'id';

    protected $fillable = [
        'description',
        'status',
        'image'
    ];

    const CASH_ID = 1;
    const CARD_ID = 2;
    const DIGITALWALLET_ID = 3;
    const TRANSFER_ID = 4;
    const DEPOSIT_ID = 5;
}
