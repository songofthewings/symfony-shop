<?php


namespace App\Controller\Rest;

use App\Entity\Cart;
use App\Entity\Product;
use App\Entity\User;
use App\Repository\ProductRepository;
use Doctrine\ORM\EntityNotFoundException;
use FOS\RestBundle\Controller\AbstractFOSRestController;
use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\View\View;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Exception\BadCredentialsException;
use Symfony\Component\Security\Core\Exception\CustomUserMessageAuthenticationException;

class CartController extends AbstractFOSRestController
{

    /**
     * Add Product to Cart resource
     * POST http://sshop.local/api/cart/product/5
     *
     * @Rest\Post("/cart/product/{productId}")
     *
     * @param int $productId
     * @ param string|null $cartCode
     * @return View
     * @throws EntityNotFoundException
     */
    public function addProduct(int $productId/*, ?string $cartCode*/): View
    {
        $token = $this->container->get('security.token_storage')->getToken();
        if (empty($token)) {
            throw new BadCredentialsException("No auth info.");
        }

        $user = $token->getUser();
        if (!$user instanceof User || !$token->isAuthenticated()) {
            throw new CustomUserMessageAuthenticationException("Not athenticated.");
        }

        $productRepository = $this->getDoctrine()->getRepository(Product::class);
        $product = $productRepository->find($productId);
        if (empty($product)) {
            throw new EntityNotFoundException("Product {$productId} not found.");
        }

        $entityManager = $this->getDoctrine()->getManager();
        $cart = $user->getCart();
        if (!$cart instanceof Cart) {
            $cart = new Cart();
            $cart->setUser($user);
        }


        //$cart->addProduct($product);
        $response = [
            'success' => 1,
        ];
        return View::create($response, Response::HTTP_OK);
    }


    /**
     * Add Product to Cart resource
     *
     * @Rest\Get("/cart/{cartCode}/get")
     * @param string $cartCode
     * @return View
     */
    public function get(string $cartCode): View
    {
    }

    /**
     * Update Product in Cart
     *
     * @Rest\Post("/cart/{cartCode}/update/{productId}/{quantity}")
     * @param string $cartCode
     * @param int $productId
     * @param int $quantity
     * @return View
     */
    public function update(string $cartCode, int $productId, int $quantity): View
    {
    }

    /**
     * Delete Product from Cart
     *
     * @Rest\Delete("/cart/{cartCode}/delete/{productId}")
     * @param string $cartCode
     * @param int $productId
     * @return View
     */
    public function delete(string $cartCode, int $productId): View
    {
    }


    /**
     * Get Cart total
     *
     * @Rest\Get("/cart/{cartCode}/total/{promotionId}")
     * @param string $cartCode
     * @param int $userId
     * @return View
     */
    public function total(string $cartCode, int $userId): View
    {
    }

}
