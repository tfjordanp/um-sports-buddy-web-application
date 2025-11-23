<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Country extends Model
{
    //
    protected $table = 'Countries';

    // Tell Eloquent to stop expecting 'created_at' and 'updated_at' columns
    public $timestamps = false;
}
