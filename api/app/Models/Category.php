<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @OA\Schema(
 *     schema="Category",
 *     @OA\Property(property="id", type="integer", example=1),
 *     @OA\Property(property="name", type="string", example="Indoor Plants"),
 *     @OA\Property(property="created_at", type="string", format="date-time"),
 *     @OA\Property(property="updated_at", type="string", format="date-time")
 * )
 *
 * @OA\Schema(
 *     schema="CategoryRequest",
 *     required={"name"},
 *     @OA\Property(property="name", type="string", example="Outdoor Plants")
 * )
 */
class Category extends Model
{
    use HasFactory;
    protected $fillable = [
        "name"
    ];

    public function plants()
    {
        return $this->hasMany(Plant::class);
    }
}
