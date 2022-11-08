<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Payment extends Model
{
    use SoftDeletes;

    protected $table = 'payments';
    protected $primaryKey = 'id';

    protected $fillable = [
        'name',
        'type',
        'notes',
        'business_id',
        'branch_id',
    ];

    public function business()
    {
        return $this->belongsTo(Business::class, 'business_id');
    }

    public function branch()
    {
        return $this->belongsTo(Branch::class, 'branch_id');
    }

    public function processes()
    {
        return $this->belongsToMany(Process::class, 'paymentprocesses', 'payment_id', 'process_id');
    }
}