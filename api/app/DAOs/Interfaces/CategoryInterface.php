<?php

namespace App\DAOs\Interfaces;

interface CategoryInterface
{
    public function all();
    public function find($id);
    public function create($category);
    public function update($id, $category);
    public function delete($id);
}
