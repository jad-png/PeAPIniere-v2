<?php

namespace App\DAOs\Interfaces;

interface OrderInterface
{
    public function all();
    public function plantsOrder($order);
    public function create($items);
    public function find($order);
    public function update($items, $order);
    public function delete($order);
    public function cancel($order);
}
