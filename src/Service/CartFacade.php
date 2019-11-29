<?php


namespace App\Service;


use App\Entity\Cart;
use App\Entity\CartProduct;
use App\Entity\Product;
use App\Entity\User;
use App\Repository\ProductRepository;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\ORM\EntityNotFoundException;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Core\Security;

class CartFacade
{
    /**
     * @var Security
     */
    private $security;
    /**
     * @var ProductRepository
     */
    private $productRepository;
    /**
     * @var ObjectManager
     */
    private $entityManager;


    public function __construct(Security $security, ProductRepository $productRepository, ObjectManager $entityManager)
    {
        $this->security = $security;
        $this->productRepository = $productRepository;
        $this->entityManager = $entityManager;
    }

    /**
     * Add Product to Cart
     *
     * @param int $productId
     * @throws EntityNotFoundException
     */
    public function addProduct(int $productId): void
    {
        $user = $this->security->getUser();
        if (!$user instanceof User) {
            throw new AuthenticationException("No authenticated user.");
        }
        //$productRepository = $this->getDoctrine()->getRepository(Product::class);
        $product = $this->productRepository->find($productId);
        if (empty($product)) {
            throw new EntityNotFoundException("Product #{$productId} not found.");
        }

        $cart = $user->getCart();
        if (!$cart instanceof Cart) {
            $cart = new Cart();
            $cart->setUser($user);
        }
        if ($cart->getProducts()->contains($product)) {
            throw new \InvalidArgumentException("Product #{$productId} is already in cart.");
        }
        $cartProduct = new CartProduct();
        $cartProduct->setProduct($product);
        $cartProduct->setQuantity(1);
        $cart->addCartProduct($cartProduct);

        //$entityManager = $this->getDoctrine()->getManager();
        $this->entityManager->persist($cart);
        $this->entityManager->persist($cartProduct);
        $this->entityManager->flush();

    }

}
