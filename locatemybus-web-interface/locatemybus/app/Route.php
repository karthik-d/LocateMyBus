<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Route extends Model
{
    public $timestamps = false;
    protected $table = "routes";
    protected $primaryKey = "route_id";
    protected $fillable = [
        'route_id',
        'origin',
		'destination'
    ]; // for mass-assignment
}

