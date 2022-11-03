<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Payments extends Model
{
    use SoftDeletes;

    protected $table = 'payments';
    protected $primaryKey = 'id';

    protected $fillable = [
        'name',
        'type',
        'notes',
        'branch_id',
        'business_id',
    ];

    public function getTypeAttribute($value)
    {
        switch ($value) {
            case 'cash':
                return 'Efectivo';
            case 'card':
                return 'Tarjeta';
            case 'transfer':
                return 'Transferencia';
            case 'check':
                return 'Cheque';
            case 'deposit':
                return 'Depósito';
            case 'other':
                return 'Otro';
            default:
                return 'Desconocido';
        }
    }

    public function branch()
    {
        return $this->belongsTo(Branch::class, 'branch_id');
    }

    public function business()
    {
        return $this->belongsTo(Business::class, 'business_id');
    }

    public function scopesearch(Builder $builder, string $param = null, $branch_id, $business_id)
    {
        return $builder->when($param, function ($query) use ($param) {
            return $query->where('name', 'like', '%' . $param . '%');
        })->when($branch_id, function ($query) use ($branch_id) {
            return $query->where('branch_id', $branch_id);
        })->when($business_id, function ($query) use ($business_id) {
            return $query->where('business_id', $business_id);
        });
    }
}