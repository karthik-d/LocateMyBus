<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class StopSequence extends Model
{
    public $timestamps = false;
    protected $table = "stop_sequences";
    protected $primaryKey = "id";
    protected $fillable = [
	'stop_id',
	'route_id',
	'onward_serial'
    ];
}
