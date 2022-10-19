<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Query\Builder;

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

    public function scopesearch(Builder $query, string $param, int $branch_id = null, int $business_id = null, int $room_type_id = null, int $floor_id = null)
    {
        return $query->where('name', 'like', "%$param%")
            ->where('number', 'like', "%$param%")
            ->where('branch_id', $branch_id)
            ->where('business_id', $business_id)
            ->where('room_type_id', $room_type_id)
            ->where('floor_id', $floor_id)
            ->orderBy('name', 'asc');
    }

    public function branch()
    {
        return $this->belongsTo(Branch::class);
    }

    public function business()
    {
        return $this->belongsTo(Business::class);
    }

    public function floor()
    {
        return $this->belongsTo(Floor::class);
    }

    public function roomType()
    {
        return $this->belongsTo(RoomType::class);
    }
}