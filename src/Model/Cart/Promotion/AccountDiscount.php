<?php


namespace App\Model\Cart\Promotion;


use App\Entity\PromotionOption;
use App\Entity\User;
use App\Model\Cart\CartPricing;

class AccountDiscount extends BasePromotionBridge implements PromotionCalculationBridge
{

    public function canBeApplied(CartPricing $context): bool
    {
        return $context->getTotal() > 0.0001
            && $context->getUserId() == $this->getUserId();
    }

    public function apply(CartPricing $context): void
    {
        $context->applyDiscount($this->getOptionValue('discount'));
    }

}
