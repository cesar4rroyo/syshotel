<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Query\Builder;

class Category extends Model
{
    use SoftDeletes;

    protected $table = 'categories';
    protected $primaryKey = 'id';

    protected $fillable = [
        'name',
        'description',
        'business_id',
        'branch_id',
    ];

    public function scopesearch(Builder $query, string $param, int $branch_id = null, int $business_id = null)
    {
        return $query->where('name', 'like', "%$param%")
            ->where('description', 'like', "%$param%")
            ->where('business_id', $business_id)
            ->where('branch_id', $branch_id)
            ->orderBy('name', 'asc');
    }

    public function business()
    {
        return $this->belongsTo(Business::class);
    }

    public function branch()
    {
        return $this->belongsTo(Branch::class);
    }

    public function products()
    {
        return $this->hasMany(Product::class);
    }
}