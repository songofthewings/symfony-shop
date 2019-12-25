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

    /**
     * @param string $optionName
     * @return string
     * @throws \Exception
     */
    protected function getOptionValue(string $optionName): string
    {
        $value = null;

        foreach ($this->promotion->getPromotionOptions() as $promotionOption) {
            if ($promotionOption->getName() == $optionName) {
                $value = $promotionOption->getValue();
                break;
            }
        }

        if (is_null($value)) {
            throw new \Exception(
                sprintf(
                    "Promotion Option '%s' not found for promotion #%s.",
                    $optionName,
                    $this->promotion->getId()
                )
            );
        }

        return $value;
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
