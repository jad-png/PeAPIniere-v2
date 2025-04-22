<?php

namespace App\DAOs;

use App\DAOs\Interfaces\PlantInterface;
use App\Models\Category;
use App\Models\Plant;

class PlantDAO implements PlantInterface
{
    protected Plant $plant;

    public function __construct(Plant $plant)
    {
        $this->plant = $plant;
    }

    public function all()
    {
        return $this->plant->all();
    }

    public function find($slug)
    {
        return $this->plant->where("slug", $slug)->first();
    }

    public function create($plant)
    {
        $category = Category::find($plant["category_id"]);
        if (!$category) return null;

        return $this->plant->create([
            "name" => $plant["name"],
            "description" => $plant['description'],
            "image" => $plant['image'],
            "price" => $plant['price'],
            "category_id" => $plant['category_id']
        ]);
    }

    public function update($id, $newPlant)
    {
        $plant = $this->plant->find($id);
        if (!$plant) return null;

        $category = Category::find($plant["category_id"]);
        if (!$category) return null;

        $plant->name = $newPlant['name'];
        $plant->description = $newPlant['description'];
        $plant->image = $newPlant['image'];
        $plant->price = $newPlant['price'];
        $plant->category_id = $newPlant['category_id'];
        $plant->save();

        return $plant;
    }

    public function delete($id)
    {
        $plant = $this->plant->find($id);

        if (!$plant) return false;

        $plant->delete();
        return true;
    }
}
