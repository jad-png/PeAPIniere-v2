<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Sluggable\HasSlug;
use Spatie\Sluggable\SlugOptions;

/**
 * @OA\Schema(
 *     schema="Plant",
 *     required={"id", "name", "slug", "description", "price", "image", "category_id"},
 *     @OA\Property(property="id", type="integer", example=1),
 *     @OA\Property(property="name", type="string", example="Monstera Deliciosa"),
 *     @OA\Property(property="slug", type="string", example="monstera-deliciosa"),
 *     @OA\Property(property="description", type="string", example="A popular tropical houseplant"),
 *     @OA\Property(property="price", type="number", format="float", example=29.99),
 *     @OA\Property(property="image", type="string", example="monstera.jpg"),
 *     @OA\Property(property="category_id", type="integer", example=1),
 *     @OA\Property(property="created_at", type="string", format="date-time"),
 *     @OA\Property(property="updated_at", type="string", format="date-time")
 * )
 *
 * @OA\Schema(
 *     schema="PlantRequest",
 *     required={"name", "description", "image", "price", "category_id"},
 *     @OA\Property(property="name", type="string", example="Snake Plant"),
 *     @OA\Property(property="description", type="string", example="Hardy indoor plant"),
 *     @OA\Property(property="image", type="string", example="snake-plant.jpg"),
 *     @OA\Property(property="price", type="number", format="float", example=24.99),
 *     @OA\Property(property="category_id", type="integer", example=2)
 * )
 */
class Plant extends Model
{
    use HasFactory, HasSlug;
    protected $fillable = [
        "name",
        "description",
        "price",
        "image",
        "category_id",
        "slug"
    ];

    public function getSlugOptions(): SlugOptions
    {
        return SlugOptions::create()
            ->generateSlugsFrom("name")
            ->saveSlugsTo("slug")
            ->doNotGenerateSlugsOnUpdate();
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function orders()
    {
        return $this->belongsToMany(Order::class, "orders_plants");
    }
}
