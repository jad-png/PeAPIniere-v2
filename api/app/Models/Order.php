<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @OA\Schema(
 *     schema="Order",
 *     @OA\Property(property="id", type="integer", example=1),
 *     @OA\Property(property="status", type="string", enum={"pending", "in preparation", "delivered"}, example="pending"),
 *     @OA\Property(property="price_total", type="number", format="float", example=99.99),
 *     @OA\Property(property="user_id", type="integer", example=1),
 *     @OA\Property(property="created_at", type="string", format="date-time"),
 *     @OA\Property(property="updated_at", type="string", format="date-time")
 * )
 *
 * @OA\Schema(
 *     schema="OrderRequest",
 *     required={"plants"},
 *     @OA\Property(
 *         property="plants",
 *         type="array",
 *         @OA\Items(
 *             type="object",
 *             @OA\Property(property="id", type="integer", example=1),
 *             @OA\Property(property="quantity", type="integer", example=2)
 *         )
 *     )
 * )
 *
 * @OA\Schema(
 *     schema="StatusUpdateRequest",
 *     required={"status"},
 *     @OA\Property(
 *         property="status",
 *         type="string",
 *         enum={"pending", "in preparation", "delivered"},
 *         example="in preparation"
 *     )
 * )
 */
class Order extends Model
{
    use HasFactory;
    protected $fillable = [
        "status",
        "user_id",
        "price_total"
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
