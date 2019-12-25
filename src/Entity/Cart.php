<?php


namespace App\Entity;

use App\Model\Cart\CartPricing;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * Class Cart
 * @package App\Entity
 *
 * @ORM\Entity(repositoryClass="App\Repository\CartRepository")
 *
 */
class Cart
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\JoinColumn(nullable=false)
     * @ORM\OneToOne(targetEntity="App\Entity\User", inversedBy="cart")
     */
    private $user;

    /**
     * @ ORM\ManyToMany(targetEntity="App\Entity\Product")
     * @ ORM\JoinTable(name="cart_products",
     *     joinColumns={@ ORM\JoinColumn(name="product_id", referencedColumnName="id")},
     *     inverseJoinColumns={@ ORM\JoinColumn(name="cart_id", referencedColumnName="id")}
     * )
     */
    private $products;


    /**
     * @ORM\OneToMany(targetEntity="App\Entity\CartProduct", mappedBy="cart")
     */
    private $cartProducts;

    public function __construct()
    {
        $this->cartProducts = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): self
    {
        $this->user = $user;

        return $this;
    }

    /**
     * @return Collection|Product[]
     */
    public function getProducts(): Collection
    {
        if (is_null($this->products)) {
            $this->products = new ArrayCollection();
            foreach ($this->getCartProducts() as $cartProduct) {
                $this->products->add($cartProduct->getProduct());
            }
        }
        return $this->products;
    }

    /**
     * @return Collection|CartProduct[]
     */
    public function getCartProducts(): Collection
    {
        return $this->cartProducts;
    }

    public function addCartProduct(CartProduct $cartProduct): self
    {
        if (!$this->cartProducts->contains($cartProduct)) {
            $this->cartProducts[] = $cartProduct;
            $cartProduct->setCart($this);
        }

        return $this;
    }

    public function removeCartProduct(CartProduct $cartProduct): self
    {
        if ($this->cartProducts->contains($cartProduct)) {
            $this->cartProducts->removeElement($cartProduct);
            // set the owning side to null (unless already changed)
            if ($cartProduct->getCart() === $this) {
                $cartProduct->setCart(null);
            }
        }

        return $this;
    }

    public function getPricing(): CartPricing
    {
        return new CartPricing($this);
    }

    /**
     * Add Product to cart
     *
     * @param Product $product
     * @param int $quantity
     */
    public function updateProducts(Product $product, int $quantity = 1): void
    {
        $targetCartProduct = null;

        /** @var CartProduct $cartProduct */
        foreach ($this->cartProducts as $cartProduct) {
            if ($cartProduct->getProduct()->getId() == $product->getId()) {
                $targetCartProduct = $cartProduct;
                break;
            }
        }

        if (!$targetCartProduct) {
            $targetCartProduct = new CartProduct();
            $targetCartProduct
                ->setCart($this)
                ->setProduct($product);
        }

        $targetCartProduct->changeQuantity($quantity);
    }
}
