<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CashBox extends Model
{
    use SoftDeletes;

    protected $table = 'cashboxes';
    protected $primaryKey = 'id';

    protected $fillable = [
        'name',
        'phone',
        'comments',
        'branch_id',
        'business_id'
    ];

    public function scopesearch(Builder $query, string $param = null, int $branch_id = null, int $business_id = null)
    {
        return $query->when($param, function ($query, $param) {
            return $query->where('name', 'like', '%' . $param . '%')
                ->orWhere('phone', 'like', '%' . $param . '%')
                ->orWhere('comments', 'like', '%' . $param . '%');
        })
            ->when($branch_id, function ($query, $branch_id) {
                return $query->where('branch_id', $branch_id);
            })
            ->when($business_id, function ($query, $business_id) {
                return $query->whereHas('branch', function ($query) use ($business_id) {
                    return $query->where('business_id', $business_id);
                });
            });
    }

    public function branch()
    {
        return $this->belongsTo(Branch::class);
    }

    public function users()
    {
        return $this->belongsTo(User::class);
    }
}