<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ApiToken extends Model
{
    public $timestamps = false;
    protected $table = "api_tokens";
    protected $primaryKey = "api_token";
    protected $fillable = [
	'api_token',
	'owner_type',
	'access_type',
	'owner_id',
	'expiry'
    ];
}
