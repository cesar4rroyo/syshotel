<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Process extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'processes';
    protected $primaryKey = 'id';

    protected $fillable = [
        'date',
        'number',
        'processtype_id',
        'start_date',
        'end_date',
        'status',
        'amount',
        'notes',
        'days',
        'client_id',
        'user_id',
        'room_id',
        'branch_id',
        'business_id',
        'payment_type',
        'booking_id',
    ];

    public function getStatusAttribute($status)
    {
        $values = config('constants.processStatus');
        return $values[$status];
    }

    public function getPaymentTypeAttribute($payment_type)
    {
        $values = config('constants.paymentType');
        return $values[$payment_type];
    }

    public function client()
    {
        return $this->belongsTo(People::class, 'client_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function room()
    {
        return $this->belongsTo(Room::class);
    }

    public function branch()
    {
        return $this->belongsTo(Branch::class);
    }

    public function business()
    {
        return $this->belongsTo(Business::class);
    }

    public function processtype()
    {
        return $this->belongsTo(ProcessType::class);
    }

    public function booking()
    {
        return $this->belongsTo(Booking::class);
    }


    public function scopeSearch(Builder $query, string $param = null, int $branch_id = null, int $business_id = null, string $status = null)
    {
        return $query->when($param, function ($query, $param) {
            return $query->where('number', 'like', "%$param%");
        })->when($branch_id, function ($query, $branch_id) {
            return $query->where('branch_id', $branch_id);
        })->when($business_id, function ($query, $business_id) {
            return $query->where('business_id', $business_id);
        })->when($status, function ($query, $status) {
            return $query->where('status', $status);
        })->orderBy('number', 'asc');
    }
}