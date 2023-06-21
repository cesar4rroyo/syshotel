<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PosPayment extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'pospayments';
    protected $primaryKey = 'id';

    protected $fillable = [
        'pos_id',
        'paymenttype_id',
    ];

    public function pos()
    {
        return $this->belongsTo(Pos::class, 'pos_id', 'id');
    }

    public function paymenttype()
    {
        return $this->belongsTo(PaymentType::class, 'paymenttype_id', 'id');
    }
}
