<?php

namespace App\Repositories;

use App\DAOs\OrderDAO;

class OrderRepository
{
    protected OrderDAO $orderDAO;

    public function __construct(OrderDAO $orderDAO)
    {
        $this->orderDAO = $orderDAO;
    }

    public function all()
    {
        return $this->orderDAO->all();
    }

    public function plantsOrder($order)
    {
        return $this->orderDAO->plantsOrder($order);
    }

    public function create($items)
    {
        return $this->orderDAO->create($items);
    }

    public function find($order)
    {
        return $this->orderDAO->find($order);
    }

    public function update($items, $order)
    {
        return $this->orderDAO->update($items, $order);
    }

    public function delete($order)
    {
        return $this->orderDAO->delete($order);
    }

    public function cancel($order)
    {
        return $this->orderDAO->cancel($order);
    }
}
