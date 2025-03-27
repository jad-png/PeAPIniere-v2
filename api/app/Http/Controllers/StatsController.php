<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class StatsController extends Controller
{
    /**
     * @OA\Get(
     *     path="/admin/sales",
     *     tags={"Admin Stats"},
     *     summary="Get sales data with customer information",
     *     operationId="getSalesData",
     *     security={{"bearerAuth": {}}},
     *     @OA\Response(
     *         response=200,
     *         description="Successful operation",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="Sales by orders"),
     *             @OA\Property(
     *                 property="data",
     *                 type="array",
     *                 @OA\Items(
     *                     @OA\Property(property="price_total", type="number", format="float", example=150.00),
     *                     @OA\Property(property="fullname", type="string", example="John Doe")
     *                 )
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
    public function sales()
    {
        /*
        This query retrieves sales data by joining the orders and users tables, fetching the total
        price of each order along with the customer's full name

        equivalent in SQL:
            SELECT orders.price_total, users.fullname
                FROM orders
                INNER JOIN users ON users.id = orders.user_id

        Expected output:
            [
                {
                    "price_total": 150.00,
                    "fullname": "John Doe"
                },
                {
                    "price_total": 200.50,
                    "fullname": "Jane Smith"
                },
                // ...
            ]
        */
        $sales = DB::table("orders")
            ->join("users", "users.id", "orders.user_id")
            ->select("orders.price_total", "users.fullname")
            ->get();

        return $this->sendResponse("Sales by orders", $sales);
    }

    /**
     * @OA\Get(
     *     path="/admin/popular-plants",
     *     tags={"Admin Stats"},
     *     summary="Get top 10 most popular plants",
     *     operationId="getPopularPlants",
     *     security={{"bearerAuth": {}}},
     *     @OA\Response(
     *         response=200,
     *         description="Successful operation",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="Popular plants"),
     *             @OA\Property(
     *                 property="data",
     *                 type="array",
     *                 @OA\Items(
     *                     @OA\Property(property="name", type="string", example="Monstera Deliciosa"),
     *                     @OA\Property(property="count", type="integer", example=42)
     *                 )
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
    public function popularPlants()
    {
        /*
        This query retrieves the top 10 most ordered plants by counting orders in the orders_plants table,
        joining with the plants table to get plant names.
        
        Equivalent in SQL:
            SELECT plants.name, COUNT(orders_plants.plant_id) as count
            FROM orders_plants
            INNER JOIN plants ON plants.id = orders_plants.plant_id
            GROUP BY plants.id
            ORDER BY count DESC
            LIMIT 10
            
        Expected output:
            [
                {
                    "name": "Monstera Deliciosa",
                    "count": 42
                },
                {
                    "name": "Snake Plant",
                    "count": 38
                },
                // ... (top 8 other plants)
            ]
        */
        $plants = DB::table("orders_plants")
            ->join("plants", "plants.id", "orders_plants.plant_id")
            ->select(DB::raw("plants.name, COUNT(orders_plants.plant_id)"))
            ->groupBy("plants.id")
            ->orderBy("count", "desc")
            ->limit(10)
            ->get();

        return $this->sendResponse("Popular plants.", $plants);
    }

    /**
     * @OA\Get(
     *     path="/admin/plants-repartition",
     *     tags={"Admin Stats"},
     *     summary="Get plant distribution by category",
     *     operationId="getPlantsRepartition",
     *     security={{"bearerAuth": {}}},
     *     @OA\Response(
     *         response=200,
     *         description="Successful operation",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="Plants repartition by category"),
     *             @OA\Property(
     *                 property="data",
     *                 type="array",
     *                 @OA\Items(
     *                     @OA\Property(property="count", type="integer", example=15),
     *                     @OA\Property(property="name", type="string", example="Succulents")
     *                 )
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
    public function plantsRepartition()
    {
        /*
        This query retrieves plant counts by category, including categories with zero plants,
        sorted by the number of plants in descending order.
        
        Equivalent in SQL:
            SELECT COUNT(plants.category_id) as count, categories.name
            FROM categories
            LEFT JOIN plants ON plants.category_id = categories.id
            GROUP BY categories.name
            ORDER BY count DESC
            
        Expected output:
            [
                {
                    "count": 15,
                    "name": "Succulents"
                },
                {
                    "count": 12,
                    "name": "Tropical Plants"
                },
                {
                    "count": 0,
                    "name": "Cacti"
                },
                // ... other categories
            ]
        */
        $plants = DB::table("categories")
            ->leftJoin("plants", "plants.category_id", "categories.id")
            ->select(DB::raw("COUNT(plants.category_id), categories.name"))
            ->groupBy("categories.name")
            ->orderBy("count", "desc")
            ->get();

        return $this->sendResponse("Plants repartition by category", $plants);
    }
}
