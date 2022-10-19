<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class MenuGroup extends Model
{
    use SoftDeletes;

    protected $table = 'menu_groups';
    protected $primaryKey = 'id';
    protected $fillable = [
        'name',
        'icon',
        'order',
    ];

    public function menuoption()
    {
        return $this->hasMany(MenuOption::class, 'menugroup_id');
    }

    public function scopesearch($query, $name)
    {
        return $query
            ->where(function ($subquery) use ($name) {
                if (!is_null($name) && strlen($name) > 0) {
                    $subquery->where('name', 'LIKE', '%' . $name . '%');
                }
            })
            ->orderBy('name', 'DESC');
    }
}