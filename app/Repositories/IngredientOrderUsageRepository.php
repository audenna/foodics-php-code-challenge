<?php

namespace App\Repositories;

use App\Models\IngredientOrderUsage;
use App\Repositories\Base\BaseRepositoryAbstract;
use Illuminate\Support\Facades\Log;

class IngredientOrderUsageRepository extends BaseRepositoryAbstract
{

    /**
     * @var string
     */
    protected string $databaseTableName = 'ingredient_order_usages';

    /**
     *
     * @param IngredientOrderUsage $usage
     */
    public function __construct(IngredientOrderUsage $usage)
    {
        parent::__construct($usage, $this->databaseTableName);
    }

    /**
     *
     * @param int $orderId
     * @param int $ingredientId
     * @param float $amountUsed
     * @return void
     */
    public function saveLog(int $orderId, int $ingredientId, float $amountUsed): void
    {
        try {

            $this->createModel(
                [
                    'order_id'      => $orderId,
                    'ingredient_id' => $ingredientId,
                    'quantity_used' => $amountUsed,
                ]
            );

        } catch (\Exception $exception) { Log::error($exception); }
    }
}
