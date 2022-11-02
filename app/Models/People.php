<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class People extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'people';
    protected $primaryKey = 'id';

    protected $fillable = [
        'name',
        'dni',
        'email',
        'phone',
        'age',
        'birthday',
        'social_reason',
        'ruc',
        'address',
        'district_id',
        'province_id',
        'department_id',
        'country_id',
        'notes',
    ];

    public function district()
    {
        return $this->belongsTo(District::class);
    }

    public function province()
    {
        return $this->belongsTo(Province::class);
    }

    public function department()
    {
        return $this->belongsTo(Department::class);
    }

    public function country()
    {
        return $this->belongsTo(Country::class);
    }
}