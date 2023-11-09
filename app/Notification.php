<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    public $table = 'notifications';

    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = false;

    public function target_user()
    {
        return $this->belongsTo('App\User', 'target_user_id', 'id');
    }

    public function user()
    {
        return $this->belongsTo('App\User', 'user_id', 'id');
    }
}
