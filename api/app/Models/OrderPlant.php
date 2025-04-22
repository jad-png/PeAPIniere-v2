<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrderPlant extends Model
{
    protected $fillable = [
        "quantity",
        "order_id",
        "plant_id"
    ];

    protected $table = "orders_plants";
    protected $timestamp = false;
}
