<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Query\Builder;

class Unit extends Model
{
    use SoftDeletes;

    protected $table = 'units';
    protected $primaryKey = 'id';

    protected $fillable = [
        'name',
        'branch_id',
        'business_id',
    ];

    public function scopesearch(Builder $query, string $param, int $branch_id = null, int $business_id = null)
    {
        return $query->where('name', 'like', "%$param%")
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

    public function products()
    {
        return $this->hasMany(Product::class);
    }
}