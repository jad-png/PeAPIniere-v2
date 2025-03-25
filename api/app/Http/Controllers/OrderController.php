<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderPlant;
use App\Models\Plant;
use App\Models\User;
use App\Repositories\OrderRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Validation\ValidationException;

class OrderController extends Controller
{
    protected OrderRepository $orderRepository;

    public function __construct(OrderRepository $orderRepository)
    {
        $this->orderRepository = $orderRepository;
    }

    // method to get all orders with thier plants
    public function index()
    {
        $orders = $this->orderRepository->all();
        return $this->sendResponse("Orders.", $orders);
    }

    // method to get the plants in an order
    public function plantsOrder(Order $order)
    {
        $plants = $this->orderRepository->plantsOrder($order);
        return $this->sendResponse("Plants belong to order $order->id.", $plants->plants);
    }

    // method to make order by clients
    public function store(Request $request)
    {
        $user = User::find($request->attributes->get("jwt_payload")->sub);
        if (Gate::forUser($user)->allows('create', Order::class)) {
            $order = $this->orderRepository->create($request);
        } else {
            abort(403, 'Unauthorized');
        }
        return $this->sendResponse("Order created succefully", $order, 201);
    }

    // show the details of the order to its owner
    public function show(Request $request, Order $order)
    {
        $user = User::find($request->attributes->get("jwt_payload")->sub);
        if (Gate::forUser($user)->allows('view', $order, Order::class)) {
            $order = $this->orderRepository->find($order);
        } else {
            abort(403, 'Unauthorized');
        }

        if (!$order) return $this->sendError("Order not found");
        return $this->sendResponse("Order.", $order);
    }

    // method to update order status by employees
    public function update(Request $request, Order $order)
    {
        $user = User::find($request->attributes->get("jwt_payload")->sub);
        if (Gate::forUser($user)->allows('update', $order, Order::class)) {
            $order = $this->orderRepository->update($request, $order);
        } else {
            abort(403, 'Unauthorized');
        }
        if (!$order) return $this->sendError("Cannot update order status, check if the status provided valid or order not delivred yet.");
        return $this->sendResponse("Order updated succefully", $order, 201);
    }

    // method to delete order by admins or employees
    public function destroy(Request $request, Order $order)
    {
        $user = User::find($request->attributes->get("jwt_payload")->sub);
        if (Gate::forUser($user)->allows('forceDelete', Order::class)) {
            $orderDeleted = $this->orderRepository->delete($order);
        } else {
            abort(403, 'Unauthorized');
        }
        if ($orderDeleted) return $this->sendResponse("Order deleted.", []);
        else return $this->sendError("Order wont't be deleted, try again.", [], 400);
    }

    // method to cancel order by its owner
    public function cancel(Request $request, Order $order)
    {
        $user = User::find($request->attributes->get("jwt_payload")->sub);
        if (Gate::forUser($user)->allows('delete', $order, Order::class)) {
            $orderCanceled = $this->orderRepository->cancel($order);
        } else {
            abort(403, 'Unauthorized');
        }
        if ($orderCanceled) return $this->sendResponse("Order canceled.", []);
        else return $this->sendError("Order wont't be canceled, try again.", [], 400);
    }
}
