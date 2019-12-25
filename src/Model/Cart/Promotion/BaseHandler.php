<?php


namespace App\Model\Cart\Promotion;


use App\Model\Cart\CartPricing;

/* abstract */ class BaseHandler implements HandlerInterface
{
    /**
     * @var HandlerInterface
     */
    private $nextHandler;

    /**
     * @var PromotionCalculationBridge
     */
    private $promotionCalculationBridge;

    public function __construct(PromotionCalculationBridge $promotionCalculationBridge)
    {
        $this->promotionCalculationBridge = $promotionCalculationBridge;
    }

    public function handle(CartPricing $context)
    {
        if ($this->promotionCalculationBridge->canBeApplied($context)) {
            $this->promotionCalculationBridge->apply($context);
        }

        if ($this->nextHandler instanceof HandlerInterface) {
            $this->nextHandler->handle($context);
        }
    }

    public function setNext(HandlerInterface $handler)
    {
        $this->nextHandler = $handler;
    }
}
