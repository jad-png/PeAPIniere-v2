<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;
    protected $fillable = [
        "status",
        "user_id",
    ];

    protected $attributes = [
        "status" => "pending"
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function plants()
    {
        return $this->belongsToMany(Plant::class, "orders_plants");
    }
}
