<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Bus extends Model
{
    public $timestamps = false;
    protected $table = "buses";
    protected $primaryKey = "bus_id";
    protected $fillable = [
	"route_id",
	"model",
	"rf_id",
	"current_tripcode"
    ]; // for mass-assignment
}
