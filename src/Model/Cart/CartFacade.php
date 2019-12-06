<?php


namespace App\Model\Cart;


use App\Entity\Cart;
use App\Entity\CartProduct;
use App\Entity\Product;
use App\Entity\User;
use App\Model\Cart\Promotion\BaseHandler;
use App\Model\Cart\Promotion\HandlerInterface;
use App\Model\Cart\Promotion\PromotionBridgeFactory;
use App\Repository\ProductRepository;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\ORM\EntityNotFoundException;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\VarDumper\VarDumper;

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


    public function getContent()
    {
        $user = $this->security->getUser();
        if (!$user instanceof User) {
            throw new AuthenticationException("No authenticated user.");
        }
        $cart = $user->getCart();
        if (!$cart instanceof Cart) {
            return [];
        }
    }

    /**
     * Update Product quantity in Cart
     *
     * @param int $productId
     * @param int $quantity
     */
    public function updateQuantity(int $productId, int $quantity): void
    {
        $user = $this->security->getUser();
        if (!$user instanceof User) {
            throw new AuthenticationException("No authenticated user.");
        }
        if ($quantity < 1) {
            throw new \InvalidArgumentException("Product quantity should be > 0.");
        }
        $cart = $user->getCart();
        if (!$cart instanceof Cart) {
            throw new \BadMethodCallException("Cart is empty.");
        }
        foreach ($cart->getCartProducts() as $cartProduct) {
            if ($cartProduct->getProduct()->getId() == $productId) {
                $cartProduct->setQuantity($quantity);
                $this->entityManager->persist($cartProduct);
                $this->entityManager->flush();
                return;
            }
        }
        throw new \BadMethodCallException("Product #{$productId} isn't in cart.");
    }


    /**
     * Remove Product from Cart
     *
     * @param int $productId
     */
    public function removeProduct(int $productId): void
    {
        $user = $this->security->getUser();
        if (!$user instanceof User) {
            throw new AuthenticationException("No authenticated user.");
        }
        $cart = $user->getCart();
        if (!$cart instanceof Cart) {
            throw new \BadMethodCallException("Cart is empty.");
        }
        foreach ($cart->getCartProducts() as $cartProduct) {
            if ($cartProduct->getProduct()->getId() == $productId) {
                $cart->getCartProducts()->removeElement($cartProduct);
                $this->entityManager->persist($cart);
                $this->entityManager->flush();
                return;
            }
        }
        throw new \BadMethodCallException("Product #{$productId} isn't in cart.");
    }


    public function calculateTotal(User $user): CartPricing
    {
        $cart = $user->getCart();
        if (is_null($cart)) {
            return 0;
        }
        $cartPricing = $cart->getPricing();

        $promotions = $this->entityManager->getRepository('App:Promotion')
            ->getPromotionsForUser($user->getId());

        /**
         * @var HandlerInterface $handlerPointer
         * @var HandlerInterface $previousHandler
         */
        $handlerPointer = null;
        $previousHandler = null;
        foreach ($promotions as $promotion) {
            $currentHandler = new BaseHandler(PromotionBridgeFactory::getPromotionBridge($promotion));
            if (is_null($handlerPointer)) {
                $handlerPointer = $currentHandler;
            }
            if (!is_null($previousHandler)) {
                $previousHandler->setNext($currentHandler);
            }
            $previousHandler = $currentHandler;
        }

        $handlerPointer->handle($cartPricing);

        return $cartPricing;
    }

}
