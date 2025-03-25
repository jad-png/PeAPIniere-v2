<?php

namespace App\DAOs;

use App\DAOs\Interfaces\CategoryInterface;
use App\Models\Category;

class CategoryDAO implements CategoryInterface
{
    protected Category $category;

    public function __construct(Category $category)
    {
        $this->category = $category;
    }

    public function all()
    {
        return $this->category->all();
    }

    public function find($id)
    {
        return $this->category->find($id);
    }

    public function create($category)
    {
        return $this->category->create([
            "name" => $category["name"]
        ]);
    }

    public function update($id, $newCategory)
    {
        $category = $this->category->find($id);

        if (!$category) return null;

        $category->name = $newCategory['name'];
        $category->save();

        return $category;
    }

    public function delete($id)
    {
        $category = $this->category->find($id);

        if (!$category) return false;

        $category->delete();
        return true;
    }
}
