<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PaymentProcess extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'paymentprocesses';
    protected $primaryKey = 'id';

    protected $fillable = [
        'date',
        'number',
        'description',
        'status',
        'image',
        'amount',
        'comment',
        'concept_id',
        'card_id',
        'bank_id',
        'digitalwallet_id',
        'pos_id',
        'branch_id',
        'business_id',
        'payment_id',
        'process_id',
        'nrooperation',
    ];

    protected $dates = [
        'date',
    ];

    public function card()
    {
        return $this->belongsTo(Card::class);
    }

    public function bank()
    {
        return $this->belongsTo(Bank::class);
    }

    public function digitalwallet()
    {
        return $this->belongsTo(DigitalWallet::class);
    }

    public function pos()
    {
        return $this->belongsTo(Pos::class);
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
