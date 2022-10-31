<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;


class Room extends Model
{
    use SoftDeletes;

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

    public function getStatusRoomAttribute()
    {
        return $this->status == 'A' ? 'Activo' : 'Inactivo';
    }

    public function scopesearch(Builder $query, string $param = null, int $branch_id = null, int $business_id = null)
    {
        return $query->when($param, function ($query, $param) {
            return $query->where('name', 'like', "%$param%");
        })->when($branch_id, function ($query, $branch_id) {
            return $query->where('branch_id', $branch_id);
        })->when($business_id, function ($query, $business_id) {
            return $query->where('business_id', $business_id);
        })->orderBy('name', 'asc');
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
}