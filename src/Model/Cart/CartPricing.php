<?php


namespace App\Model\Cart;


use App\Entity\Cart;

class CartPricing
{
    /**
     * @var Cart
     */
    protected $cart;
    /**
     * @var array(product_id => [price=>price, quantity=>quantity])
     */
    protected $productPrices;
    /**
     * @var array(product_id => quantity)
     */
    protected $freeProducts = [];
    /**
     * @var float Total amount calculated by product prices.
     */
    protected $totalByProducts;
    /**
     * @var array[int] Discount values applied to Cart
     */
    protected $discountsApplied = [];
    /**
     * @var float Cart final total calculated with all discounts.
     */
    protected $calculatedTotal;

    public function __construct(Cart $cart)
    {
        $this->cart = $cart;
        /* Set initial product prices and calculate totals without discount. */
        $this->productPrices = [];
        foreach ($cart->getCartProducts() as $cartProduct) {
            $this->productPrices[$cartProduct->getProduct()->getId()] = [
                'price' => $cartProduct->getProduct()->getPrice(),
                'quantity' => $cartProduct->getQuantity(),
            ];
        }
        $this->calculatedTotal = $this->getTotalByProducts();
    }

    public function getTotalByProducts(): float
    {
        if (is_null($this->totalByProducts)) {
            $this->totalByProducts = 0;
            foreach ($this->productPrices as $productPriceInfo) {
                $this->totalByProducts += $productPriceInfo['price'] * $productPriceInfo['quantity'];
            }
        }

        return $this->totalByProducts;
    }

    public function setProductFree(int $productId): void
    {
        if (empty($this->productPrices[$productId]['quantity'])) {
            throw new \UnexpectedValueException("Product #$productId is not in cart.");
        }
        $this->productPrices[$productId]['quantity']--;
        if ($this->productPrices[$productId]['quantity'] == 0) {
            uset($this->productPrices[$productId]);
        }

        $this->freeProducts[$productId] = $this->freeProducts[$productId] ?? 0;
        $this->freeProducts[$productId]++;

        $this->totalByProducts = null;
        $this->calculatedTotal = null;
    }

    public function applyDiscount(int $discountPercent): void
    {
        if ($discountPercent >= 100) {
            throw new \UnexpectedValueException("Incorrect discount percent: $discountPercent.");
        }
        $this->discountsApplied[] = $discountPercent;

        $this->calculatedTotal = null;
    }

    public function getTotal(): float
    {
        if (is_null($this->calculatedTotal)) {
            $total = $this->getTotalByProducts();
            /**
             * @var int $discountPercent
             */
            foreach ($this->discountsApplied as $discountPercent) {
                $total -= $total * $discountPercent / 100;
            }
        }

        return $this->calculatedTotal;
    }

}
