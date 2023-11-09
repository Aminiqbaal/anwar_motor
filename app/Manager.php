<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class m_Manager extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    public $table = 'managers';

    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = false;

    public function user()
    {
        return $this->hasOne('App\m_User', 'user_id', 'id');
    }
}
