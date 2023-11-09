<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Supplier extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    public $table = 'suppliers';

    /**
     * The attributes that aren't mass assignable.
     *
     * @var array
     */
    protected $guarded = [];

    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = false;
}
