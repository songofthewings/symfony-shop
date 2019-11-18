<?php


namespace App\Controller\Rest;

use App\Entity\Cart;
use App\Entity\Product;
use App\Repository\ProductRepository;
use Doctrine\ORM\EntityNotFoundException;
use FOS\RestBundle\Controller\AbstractFOSRestController;
use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\View\View;
use JMS\Serializer\SerializationContext;
use JMS\Serializer\SerializerBuilder;
use Symfony\Component\HttpFoundation\Response;

class CartController extends AbstractFOSRestController
{

    /**
     * Add Product to Cart resource
     * POST http://sshop.local/api/cart/product/5
     *
     * @Rest\Post("/cart/product/{productId}")
     * ("/cart/product/{productId}/{cartCode}")
     * @param int $productId
     * @ param string|null $cartCode
     * @return View
     * @throws EntityNotFoundException
     */
    public function addProduct(int $productId/*, ?string $cartCode*/): View
    {
        $cart = new Cart();
        $productRepository = $this->getDoctrine()->getRepository(Product::class);
        $product = $productRepository->find($productId);
        if (empty($product)) {
            throw new EntityNotFoundException("Product {$productId} not found.");
        }
        $cart->addProduct($product);

        $serializer = SerializerBuilder::create()->build();
        $jsonCart = $serializer->serialize($cart, 'json');
        //$jsonCart = $serializer->serialize(/* $cart */[5=>1], 'json', SerializationContext::create()->setInitialType('array'));

        $response = [
            'cart' => $jsonCart,
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
