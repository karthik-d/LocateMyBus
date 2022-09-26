<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Stop extends Model
{
    public $timestamps = false;
    protected $table = "stops";
    protected $primaryKey = "stop_id";
    protected $fillable = [
	'stop_id',
	'stop_name',
	'is_active'
    ];
}
