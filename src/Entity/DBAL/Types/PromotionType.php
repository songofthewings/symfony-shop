<?php


namespace App\Entity\DBAL\Types;

/**
 * Class PromotionType
 * @package App\Entity\DBAL\Types
 *
 * Promotion:
 * - bogof (buy one get one free)
 * - X% (account discount)
 * - -10$ if total > 200$
 * etc
 */
class PromotionType extends EnumType
{
    protected $name = 'promotion_type';
    protected $values = array('buyOneGetOne', 'accountDiscount', 'onTotalExceeds');
}
