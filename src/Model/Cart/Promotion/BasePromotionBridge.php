<?php


namespace App\Model\Cart\Promotion;


use App\Entity\Promotion;

abstract class BasePromotionBridge implements PromotionCalculationBridge
{
    /**
     * @var Promotion
     */
    protected $promotion;

    public function __construct(Promotion $promotion)
    {
        $this->promotion = $promotion;
    }

}
