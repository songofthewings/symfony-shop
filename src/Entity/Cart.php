<?php


namespace App\Entity;

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
     * @ORM\OneToOne(targetEntity="App\Entity\User", inversedBy="cart")
     */
    private $user;

    /**
     * @ ORM\ManyToMany(targetEntity="App\Entity\Product")
     * @ ORM\JoinTable(name="cart_products",
     *     joinColumns={@ ORM\JoinColumn(name="product_id", referencedColumnName="id")},
     *     inverseJoinColumns={@ ORM\JoinColumn(name="cart_id", referencedColumnName="id")}
     * )
     * /
    private $products;
     * */


    /**
     * @ORM\OneToMany(targetEntity="App\Entity\CartProduct", mappedBy="cart")
     */
    private $cartProducts;

    public function __construct()
    {
        $this->products = new ArrayCollection();
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
        return $this->products;
    }

    public function addProduct(Product $product): self
    {
        if (!$this->products->contains($product)) {
            $this->products[] = $product;
        }

        return $this;
    }

    public function removeProduct(Product $product): self
    {
        if ($this->products->contains($product)) {
            $this->products->removeElement($product);
        }

        return $this;
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

    /**
     * @var array
     *
     * /
    protected $productQuantities = [];

    public function __construct()
    {
    }

    public function getProductQuantities()
    {
        return $this->productQuantities;
    }

    public function setProductQuantities($productQuantities)
    {
        $this->productQuantities = $productQuantities;
    }


    public function addProduct(Product $product)
    {
        if (!isset($this->productQuantities[$product->getId()])) {
            $this->productQuantities[$product->getId()] = 0;
            $this->products[$product->getId()] = $product;
        }
        $this->productQuantities[$product->getId()]++;
    }

     */

}
