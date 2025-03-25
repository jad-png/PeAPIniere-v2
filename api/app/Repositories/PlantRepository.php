<?php

namespace App\Repositories;

use App\DAOs\PlantDAO;

class PlantRepository
{
    protected PlantDAO $plantDAO;

    public function __construct(PlantDAO $plantDAO)
    {
        $this->plantDAO = $plantDAO;
    }

    public function all()
    {
        return $this->plantDAO->all();
    }

    public function find($slug)
    {
        return $this->plantDAO->find($slug);
    }

    public function create($plant)
    {
        return $this->plantDAO->create($plant);
    }

    public function update($id, $plant)
    {
        return $this->plantDAO->update($id, $plant);
    }

    public function delete($id)
    {
        return $this->plantDAO->delete($id);
    }
}
