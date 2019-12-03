<?php


namespace App\Model\Cart\Promotion;


use App\Entity\Promotion;

class PromotionBridgeFactory
{

    public static function getPromotionBridge(Promotion $promotion): PromotionCalculationBridge
    {
        $bridgeClassName = 'App\Model\Cart\Promotion\\' . ucfirst($promotion->getType());
        if (!class_exists($bridgeClassName) || !is_subclass_of(
                $bridgeClassName,
                'App\Model\Cart\Promotion\PromotionCalculationBridge'
            )) {
            throw new \UnexpectedValueException(
                'No Promotion Calculation for promotion type: ' . $promotion->getType()
            );
        }

        return new $bridgeClassName($promotion);
    }

}
