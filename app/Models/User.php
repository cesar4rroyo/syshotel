<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Query\Builder;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Fortify\TwoFactorAuthenticatable;
use Laravel\Jetstream\HasProfilePhoto;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens;
    use HasFactory;
    use HasProfilePhoto;
    use Notifiable;
    use TwoFactorAuthenticatable;

    /**
     * The attributes that are mass assignable.
     *
     * @var string[]
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'usertype_id',
        'business_id',
        'people_id'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
        'two_factor_recovery_codes',
        'two_factor_secret',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    /**
     * The accessors to append to the model's array form.
     *
     * @var array
     */
    protected $appends = [
        'profile_photo_url',
    ];

    public function business()
    {
        return $this->belongsTo(Business::class);
    }

    public function people()
    {
        return $this->belongsTo(People::class);
    }

    public function usertype()
    {
        return $this->belongsTo(Usertype::class);
    }

    public function branches()
    {
        return $this->belongsToMany(Branch::class, 'user_branches', 'user_id', 'branch_id');
    }

    public function cashboxes()
    {
        return $this->belongsToMany(CashBox::class, 'user_cashboxes', 'user_id', 'cashbox_id');
    }

    public function scopesearch(Builder $query, string $param, int $usertype_id = null, int $business_id = null)
    {
        return $query->where('name', 'like', "%$param%")
            ->where('email', 'like', "%$param%")
            ->where('usertype_id', $usertype_id)
            ->where('business_id', $business_id)
            ->orderBy('name', 'asc');
    }
}