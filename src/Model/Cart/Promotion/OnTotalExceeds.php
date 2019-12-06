<?php


namespace App\Model\Cart\Promotion;


use App\Model\Cart\CartPricing;

class OnTotalExceeds extends BasePromotionBridge implements PromotionCalculationBridge
{

    public function canBeApplied(CartPricing $context): bool
    {
        return $context->getTotal() > $this->getOptionValue('amount');
    }

    public function apply(CartPricing $context): void
    {
        $context->applyDiscount($this->getOptionValue('discount'));
    }
}
