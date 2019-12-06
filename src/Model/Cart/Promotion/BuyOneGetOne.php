<?php


namespace App\Model\Cart\Promotion;


use App\Model\Cart\CartPricing;

class BuyOneGetOne extends BasePromotionBridge implements PromotionCalculationBridge
{

    public function canBeApplied(CartPricing $context): bool
    {
        $productId = intval($this->getOptionValue('product_id'));
        $paidProductCount = $context->getProductPrices()[$productId]['quantity'] ?? 0;
        $freeProductCount = $context->getFreeProducts()[$productId] ?? 0;
        return $paidProductCount - $freeProductCount >= 2;
    }

    public function apply(CartPricing $context): void
    {
        $productId = intval($this->getOptionValue('product_id'));
        while ($this->canBeApplied($context)) {
            $context->setProductFree($productId);
        }
    }
}
