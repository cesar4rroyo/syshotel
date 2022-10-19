<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Query\Builder;

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
    ];

    public function scopesearch(Builder $query, string $param, int $branch_id = null)
    {
        return $query->where('name', 'like', "%$param%")
            ->where('phone', 'like', "%$param%")
            ->where('comments', 'like', "%$param%")
            ->where('branch_id', $branch_id)
            ->orderBy('name', 'asc');
    }

    public function branch()
    {
        return $this->belongsTo(Branch::class);
    }

    public function users()
    {
        return $this->belongsToMany(User::class, 'user_cashboxes', 'cashbox_id', 'user_id');
    }
}