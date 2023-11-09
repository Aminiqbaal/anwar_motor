<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Service extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    public $table = 'services';

    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = false;
}
