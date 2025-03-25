<?php

namespace App\DAOs\Interfaces;

interface PlantInterface
{
    public function all();
    public function find($slug);
    public function create($category);
    public function update($id, $category);
    public function delete($id);
}
