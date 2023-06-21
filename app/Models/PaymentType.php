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
}
