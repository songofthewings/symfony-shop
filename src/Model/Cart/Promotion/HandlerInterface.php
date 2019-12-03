<?php


namespace App\Model\Cart\Promotion;


use App\Model\Cart\CartPricing;

interface HandlerInterface
{

    public function handle(CartPricing $context);

    public function setNext(HandlerInterface $handler);

}
