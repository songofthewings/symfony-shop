<?php


namespace App\Model\Cart\Promotion;


use App\Model\Cart\CartPricing;

class OnTotalExceeds extends BasePromotionBridge implements PromotionCalculationBridge
{

    public function canBeApplied(CartPricing $context): bool
    {
        // TODO: Implement canBeApplied() method.
    }

    public function apply(CartPricing $context): void
    {
        // TODO: Implement apply() method.
    }
}
