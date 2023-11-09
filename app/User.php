<?php

namespace App;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use Notifiable;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    public $table = 'users';

    /**
     * The attributes that aren't mass assignable.
     *
     * @var array
     */
    protected $guarded = [];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password'
    ];

    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = false;

    public function workshop()
    {
        return $this->belongsTo('App\Workshop', 'workshop_id', 'id');
    }

    public function hasRole($role)
    {
        return in_array($this->role, $role) ? : abort('403');
    }

    public function data()
    {
        if($this->role == 'manager') return $this->hasOne('App\Manager', 'user_id', 'id');
        elseif($this->role == 'cashier') return $this->hasOne('App\Cashier', 'user_id', 'id');
        elseif($this->role == 'mechanic') return $this->hasOne('App\Mechanic', 'user_id', 'id');
    }
}
