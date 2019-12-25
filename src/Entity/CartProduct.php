<?php


namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Class CartProduct
 * @package App\Entity
 *
 * @ORM\Entity()
 * @ORM\Table(name="cart_products",
 *     uniqueConstraints={@ORM\UniqueConstraint(name="product_unique",columns={"cart_id","product_id"})}
 * )
 */
class CartProduct
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\JoinColumn(nullable=false)
     * @ORM\ManyToOne(targetEntity="App\Entity\Cart", inversedBy="cartProducts")
     */
    private $cart;
    /**
     * @ORM\JoinColumn(nullable=false)
     * @ORM\ManyToOne(targetEntity="App\Entity\Product")
     * , inversedBy="cart"
     */
    private $product;

    /**
     * @ORM\Column(type="integer")
     */
    private $quantity = 0;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getQuantity(): ?int
    {
        return $this->quantity;
    }

    public function setQuantity(int $quantity): self
    {
        $this->quantity = $quantity;

        return $this;
    }

    public function changeQuantity(int $quantity): self
    {
        $this->quantity += $quantity;
        $this->quantity = max($this->quantity, 0);

        return $this;
    }

    public function getCart(): ?Cart
    {
        return $this->cart;
    }

    public function setCart(?Cart $cart): self
    {
        $this->cart = $cart;

        return $this;
    }

    public function getProduct(): ?Product
    {
        return $this->product;
    }

    public function setProduct(?Product $product): self
    {
        $this->product = $product;

        return $this;
    }
}
