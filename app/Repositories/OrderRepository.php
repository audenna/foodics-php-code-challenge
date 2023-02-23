<?php

namespace App\Repositories;

use App\Models\Order;
use App\Repositories\Base\BaseRepositoryAbstract;

class OrderRepository extends BaseRepositoryAbstract
{

    /**
     * @var string
     */
    protected string $databaseTableName = 'orders';

    /**
     *
     * @param Order $order
     */
    public function __construct(Order $order)
    {
        parent::__construct($order, $this->databaseTableName);
    }


}
