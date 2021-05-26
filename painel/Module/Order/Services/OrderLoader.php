<?php


declare(strict_types=1);

namespace KED\Module\Order\Services;


use function KED\dispatch_event;
use KED\Services\Db\Processor;
use KED\Services\Db\Table;
use function KED\the_container;

class OrderLoader extends Table
{
    protected $loadedOrders = [];

    public function __construct()
    {
        parent::__construct("order", the_container()->get(Processor::class));
        parent::addFieldToSelect("order.*");

        dispatch_event("order_loader_init", [$this]);
    }

    public function load($id)
    {
        if (isset($this->loadedOrders[$id]))
            return $this->loadedOrders[$id];

        $order = parent::load($id);
        if ($order)
            $this->loadedOrders[$id] = $order;

        return $order;
    }
}