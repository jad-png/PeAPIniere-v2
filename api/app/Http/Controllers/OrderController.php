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

    /**
     * @OA\Get(
     *     path="/orders",
     *     tags={"Orders"},
     *     summary="Get all orders",
     *     operationId="getOrders",
     *     security={{"bearerAuth": {}}},
     *     @OA\Response(
     *         response=200,
     *         description="Successful operation",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="Orders"),
     *             @OA\Property(
     *                 property="data",
     *                 type="array",
     *                 @OA\Items(ref="#/components/schemas/Order")
     *             ),
     *             @OA\Property(property="status", type="integer", example=200)
     *         )
     *     ),
     *     @OA\Response(
     *         response=403,
     *         description="Unauthorized",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Unauthorized")
     *         )
     *     )
     * )
     */
    public function index()
    {
        $orders = $this->orderRepository->all();
        return $this->sendResponse("Orders.", $orders);
    }

    /**
     * @OA\Get(
     *     path="/orders/{order}/plants",
     *     tags={"Orders"},
     *     summary="Get plants in an order",
     *     operationId="getOrderPlants",
     *     @OA\Parameter(
     *         name="order",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Successful operation",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="Plants belong to order 1"),
     *             @OA\Property(
     *                 property="data",
     *                 type="array",
     *                 @OA\Items(ref="#/components/schemas/Plant")
     *             ),
     *             @OA\Property(property="status", type="integer", example=200)
     *         )
     *     )
     * )
     */
    public function plantsOrder(Order $order)
    {
        $plants = $this->orderRepository->plantsOrder($order);
        return $this->sendResponse("Plants belong to order $order->id.", $plants->plants);
    }

    /**
     * @OA\Post(
     *     path="/orders",
     *     tags={"Orders"},
     *     summary="Create a new order",
     *     operationId="createOrder",
     *     security={{"bearerAuth": {}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/OrderRequest")
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Order created successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="Order created successfully"),
     *             @OA\Property(property="data", ref="#/components/schemas/Order"),
     *             @OA\Property(property="status", type="integer", example=201)
     *         )
     *     ),
     *     @OA\Response(
     *         response=403,
     *         description="Unauthorized",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Unauthorized")
     *         )
     *     )
     * )
     */
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

    /**
     * @OA\Get(
     *     path="/orders/{order}",
     *     tags={"Orders"},
     *     summary="Get specific order",
     *     operationId="getOrder",
     *     security={{"bearerAuth": {}}},
     *     @OA\Parameter(
     *         name="order",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Successful operation",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="Order"),
     *             @OA\Property(property="data", ref="#/components/schemas/Order"),
     *             @OA\Property(property="status", type="integer", example=200)
     *         )
     *     ),
     *     @OA\Response(
     *         response=403,
     *         description="Unauthorized",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Unauthorized")
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Order not found",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=false),
     *             @OA\Property(property="message", type="string", example="Order not found"),
     *             @OA\Property(property="status", type="integer", example=404)
     *         )
     *     )
     * )
     */
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

    /**
     * @OA\Put(
     *     path="/orders/{order}",
     *     tags={"Orders"},
     *     summary="Update order status",
     *     operationId="updateOrder",
     *     security={{"bearerAuth": {}}},
     *     @OA\Parameter(
     *         name="order",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/StatusUpdateRequest")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Order updated successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="Order updated successfully"),
     *             @OA\Property(property="data", ref="#/components/schemas/Order"),
     *             @OA\Property(property="status", type="integer", example=200)
     *         )
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Bad request",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=false),
     *             @OA\Property(property="message", type="string", example="Cannot update order status"),
     *             @OA\Property(property="status", type="integer", example=400)
     *         )
     *     ),
     *     @OA\Response(
     *         response=403,
     *         description="Unauthorized",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Unauthorized")
     *         )
     *     )
     * )
     */
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

    /**
     * @OA\Delete(
     *     path="/orders/{order}",
     *     tags={"Orders"},
     *     summary="Delete an order",
     *     operationId="deleteOrder",
     *     security={{"bearerAuth": {}}},
     *     @OA\Parameter(
     *         name="order",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Order deleted successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="Order deleted"),
     *             @OA\Property(property="data", type="array", @OA\Items()),
     *             @OA\Property(property="status", type="integer", example=200)
     *         )
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Bad request",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=false),
     *             @OA\Property(property="message", type="string", example="Order won't be deleted"),
     *             @OA\Property(property="status", type="integer", example=400)
     *         )
     *     ),
     *     @OA\Response(
     *         response=403,
     *         description="Unauthorized",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Unauthorized")
     *         )
     *     )
     * )
     */
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

    /**
     * @OA\Get(
     *     path="/orders/{order}/cancel",
     *     tags={"Orders"},
     *     summary="Cancel an order",
     *     operationId="cancelOrder",
     *     security={{"bearerAuth": {}}},
     *     @OA\Parameter(
     *         name="order",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Order canceled successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="Order canceled"),
     *             @OA\Property(property="data", type="array", @OA\Items()),
     *             @OA\Property(property="status", type="integer", example=200)
     *         )
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Bad request",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=false),
     *             @OA\Property(property="message", type="string", example="Order won't be canceled"),
     *             @OA\Property(property="status", type="integer", example=400)
     *         )
     *     ),
     *     @OA\Response(
     *         response=403,
     *         description="Unauthorized",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Unauthorized")
     *         )
     *     )
     * )
     */
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
