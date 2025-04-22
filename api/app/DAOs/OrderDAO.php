<?php

namespace App\DAOs;

use App\DAOs\Interfaces\OrderInterface;
use App\Models\Order;
use App\Models\Plant;

class OrderDAO implements OrderInterface
{
    protected Order $order;

    public function __construct(Order $order)
    {
        $this->order = $order;
    }

    public function all()
    {
        return $this->order->all();
    }

    public function plantsOrder($order)
    {
        $plants = $this->order->with("plants")->find($order->id);
        return $plants;
    }

    public function create($items)
    {
        $order = Order::create([
            "user_id" => $items->attributes->get("jwt_payload")->sub
        ]);

        $plants = $items->plants;
        $priceTotal = 0;

        foreach ($plants as $plant) {
            $plantItem = Plant::find($plant["plant_id"]);
            $order->plants()->attach(
                $plant["plant_id"],
                [
                    "quantity" => $plant["quantity"],
                    "price_total" => $plantItem->price * $plant["quantity"],
                ]
            );
            $priceTotal += $plantItem->price * $plant["quantity"];
        }

        $order->update(["price_total" => $priceTotal]);
        return $order;
    }

    public function find($order)
    {
        $order = $this->order->find($order);
        if (!$order) return null;
        return $order;
    }

    public function update($items, $order)
    {
        if ($items["status"] != "in preparation" && $items["status"] !== "delivred") return null;

        if ($order->status == "delivred") return null;

        $order->update([
            "status" => $items["status"]
        ]);
        return $order;
    }

    public function delete($order)
    {
        $orderToDelete = $this->order->find($order->id);
        if (!$orderToDelete) return null;

        return $orderToDelete->delete();
    }

    public function cancel($order)
    {
        $orderToCancel = $this->order->find($order->id);
        if (!$orderToCancel) return null;
        if($orderToCancel->status != "pending") return false;

        $orderToCancel->delete();
        return true;
    }
}
