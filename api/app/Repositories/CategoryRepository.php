<?php

namespace App\Repositories;

use App\DAOs\CategoryDAO;

class CategoryRepository
{
    protected CategoryDAO $categoryDAO;

    public function __construct(CategoryDAO $categoryDAO)
    {
        $this->categoryDAO = $categoryDAO;
    }

    public function all()
    {
        return $this->categoryDAO->all();
    }

    public function find($id)
    {
        return $this->categoryDAO->find($id);
    }

    public function create($category)
    {
        return $this->categoryDAO->create($category);
    }

    public function update($id, $category)
    {
        return $this->categoryDAO->update($id, $category);
    }

    public function delete($id)
    {
        return $this->categoryDAO->delete($id);
    }
}
