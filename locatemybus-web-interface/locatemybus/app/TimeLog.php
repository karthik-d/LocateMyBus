<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TimeLog extends Model
{
    public $timestamps = false;
    protected $table = "time_logs";
    protected $primaryKey = "id";
    protected $fillable = [
	'trip_id',
	'stop_id',
	'date',
	'time'
    ];
}
