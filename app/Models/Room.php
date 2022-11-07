<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Termwind\Components\Dd;

class Room extends Model
{
    use SoftDeletes, HasFactory;

    protected $table = 'rooms';
    protected $primaryKey = 'id';

    protected $fillable = [
        'name',
        'number',
        'status',
        'branch_id',
        'business_id',
        'floor_id',
        'room_type_id',
    ];

    public function getStatusAttribute($status)
    {
        $values = config('constants.roomStatus');
        return $values[$status];
    }

    public function getColorAttribute()
    {
        $values = config('constants.roomStatusColor');
        return $values[$this->status];
    }

    public function scopesearch(Builder $query, string $param = null, int $branch_id = null, int $business_id = null, array $status = null)
    {
        return $query->when($param, function ($query, $param) {
            return $query->where('name', 'like', "%$param%");
        })->when($branch_id, function ($query, $branch_id) {
            return $query->where('branch_id', $branch_id);
        })->when($business_id, function ($query, $business_id) {
            return $query->where('business_id', $business_id);
        })->when($status, function ($query, $status) {
            return $query->whereIn('status', $status);
        });
    }

    public function branch()
    {
        return $this->belongsTo(Branch::class, 'branch_id');
    }

    public function business()
    {
        return $this->belongsTo(Business::class);
    }

    public function floor()
    {
        return $this->belongsTo(Floor::class, 'floor_id');
    }

    public function roomType()
    {
        return $this->belongsTo(RoomType::class, 'room_type_id');
    }

    public function bookings()
    {
        return $this->hasMany(Booking::class);
    }

    public function processes()
    {
        return $this->hasMany(Process::class, 'room_id');
    }

    public function scopeAvailable(Builder $query, string $datefrom, string $dateto)
    {
        return $query->whereDoesntHave('bookings', function ($query) use ($datefrom, $dateto) {
            $query->where('datefrom', '<=', $datefrom)
                ->where('dateto', '>=', $dateto)
                ->where('status', '!=', 'C');
        });
    }
}