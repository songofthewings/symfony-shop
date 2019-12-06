<?php


namespace App\Model\Cart\Promotion;


use App\Entity\Promotion;
use App\Entity\User;
use Doctrine\DBAL\Exception\DatabaseObjectNotFoundException;

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

    protected function getOptionValue(string $optionName): string
    {
        foreach ($this->promotion->getPromotionOptions() as $promotionOption) {
            if ($promotionOption->getName() == $optionName) {
                return $promotionOption->getValue();
            }
        }
        throw new DatabaseObjectNotFoundException(
            "Promotion Option '$optionName' not found for promotion #{$this->promotion->getId()}."
        );
    }

    protected function getUserId(): ?int
    {
        foreach ($this->promotion->getPromotionOptions() as $promotionOption) {
            if ($promotionOption->getUser() instanceof User) {
                return $promotionOption->getUser()->getId();
            }
        }
        return null;
    }

}
