<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ApiOwner extends Model
{
    public $timestamps = false;
    protected $table = "api_owners";
    protected $primaryKey = "id";
    protected $fillable = [
	'id',
	'stop_id',
	'user_email'
    ];
}
