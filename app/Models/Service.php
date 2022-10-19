<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Query\Builder;

class Service extends Model
{
    use SoftDeletes;

    protected $table = 'services';
    protected $primaryKey = 'id';

    protected $fillable = [
        'name',
        'description',
        'price',
        'branch_id',
        'business_id',
    ];

    public function scopesearch(Builder $query, string $param, int $branch_id = null, int $business_id = null)
    {
        return $query->where('name', 'like', "%$param%")
            ->where('description', 'like', "%$param%")
            ->where('branch_id', $branch_id)
            ->where('business_id', $business_id)
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
}