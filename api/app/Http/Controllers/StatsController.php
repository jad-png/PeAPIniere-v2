<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class StatsController extends Controller
{
    public function sales()
    {
        $sales = DB::table("orders")
            ->join("users", "users.id", "orders.user_id")
            ->select("orders.price_total", "users.fullname")
            ->get();

        return $this->sendResponse("Sales by orders", $sales);
    }

    public function popularPlants()
    {
        $plants = DB::table("orders_plants")
            ->join("plants", "plants.id", "orders_plants.plant_id")
            ->select(DB::raw("plants.name, COUNT(orders_plants.plant_id)"))
            ->groupBy("plants.id")
            ->orderBy("count", "desc")
            ->limit(10)
            ->get();

        return $this->sendResponse("Popular plants.", $plants);
    }
}
