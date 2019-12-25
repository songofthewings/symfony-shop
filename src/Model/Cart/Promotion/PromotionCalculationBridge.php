<?php


namespace App\Model\Cart\Promotion;


use App\Entity\Promotion;
use App\Model\Cart\CartPricing;

interface PromotionCalculationBridge
{
    public function __construct(Promotion $promotion);

    public function canBeApplied(CartPricing $context): bool;

    public function apply(CartPricing $context): void;
}
