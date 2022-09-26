<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Trip extends Model
{
    public $timestamps = false;
    protected $table = "trips";
    protected $primaryKey = "trip_id";
    protected $fillable = [
	'trip_id',
	'bus_id',
	'route_id',
	'is_onward',
	'is_active',
	'sched_start_time',
	'sched_end_time'
    ];
}
