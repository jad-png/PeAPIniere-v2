<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderPlant;
use App\Models\Plant;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class OrderController extends Controller
{
    // method to get all orders with thier plants
    public function index()
    {
        return $this->sendResponse("Orders.", OrderPlant::all());
    }

    // method to get the plants in an order
    public function plantsOrder(Request $request, Order $order)
    {
        $plants = Order::with("plants")->find($order->id);
        return $this->sendResponse("Plants belong to order $order->id.", $plants->plants);
    }

    // method to make order by clients
    public function store(Request $request)
    {
        $this->authorize("create");
        // create order with user_id and status = pending
        $order = Order::create([
            "user_id" => $request->attributes->get("jwt_payload")->sub
        ]);

        $plants = $request->plants;
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
        return $this->sendResponse("Order created succefully", $order, 201);
    }

    // show the details of the order to its owner
    public function show(Order $order)
    {
        return $this->sendResponse("Order $order->id.", $order->plants);
    }

    // method to update order status by employees
    public function update(Request $request, Order $order)
    {
        $this->authorize("update", $order);
        try {
            $fields = $request->validate([
                "status" => ["required"]
            ]);
        } catch (ValidationException $e) {
            return $this->sendError("Validation errors", $e->errors(), 422);
        }

        if ($fields["status"] != "in preparation" && $fields["status"] !== "delivred")
            return $this->sendError("Status not approuved", [], 400);

        if ($order->status == "delivred")
            return $this->sendError("Cannot update order status after delevring.", [], 400);

        $order->update($fields);

        return $this->sendResponse("Order status updated succesfully to '$fields[status]'.", []);
    }

    // method to delete order by admins or employees
    public function destroy(Order $order)
    {
        $this->authorize("delete", $order);
        $order->delete();
        return $this->sendResponse("Order deleted.", []);
    }

    // method to cancel order by its owner
    public function cancel(Request $request, Order $order)
    {
        $this->authorize("delete", $order);
        if (!$order->status == "pending")
            return $this->sendError("Cannot update order status from now.", [], 403);

        $order->delete();
        return $this->sendResponse("Order cancelled.", [], 200);
    }
}
