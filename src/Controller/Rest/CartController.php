<?php


namespace App\Controller\Rest;

use App\Entity\Cart;
use App\Entity\CartProduct;
use App\Entity\Product;
use App\Entity\User;
use App\Model\Cart\CartFacade;
use App\Repository\ProductRepository;
use Doctrine\ORM\EntityNotFoundException;
use FOS\RestBundle\Controller\AbstractFOSRestController;
use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\View\View;
use Symfony\Component\HttpClient\Exception\InvalidArgumentException;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Exception\BadCredentialsException;
use Symfony\Component\Security\Core\Exception\CustomUserMessageAuthenticationException;
use Symfony\Component\Security\Core\Security;

class CartController extends AbstractFOSRestController
{

    /**
     * Add Product to Cart resource
     * POST http://sshop.local/api/cart/product/5
     *
     * @Rest\Post("/cart/product/{productId}")
     *
     * @param int $productId
     * @param CartFacade $cartFacade
     * @return View
     * @throws EntityNotFoundException
     */
    public function addProduct(int $productId, CartFacade $cartFacade): View
    {
        /*
        $token = $this->container->get('security.token_storage')->getToken();
        if (empty($token)) {
            throw new BadCredentialsException("No auth info.");
        }

        $user = $token->getUser();
        if (!$user instanceof User || !$token->isAuthenticated()) {
            throw new CustomUserMessageAuthenticationException("Not authenticated.");
        }
        */

        $cartFacade->addProduct($productId);

        $response = [
            'success' => 1,
        ];
        return View::create($response, Response::HTTP_OK);
    }


    /**
     * Add Product to Cart resource
     *
     * @Rest\Get("/cart")
     * @return View
     */
    public function show(): View
    {
    }

    /**
     * Update Product quantity in Cart
     *
     * -- if PUT need to create
     * @Rest\Patch("/cart/products/{productId}/{quantity}")
     * @param int $productId
     * @param int $quantity
     * @param CartFacade $cartFacade
     * @return View
     * @throws EntityNotFoundException
     */
    public function update(int $productId, int $quantity, CartFacade $cartFacade): View
    {
        $cartFacade->updateQuantity($productId, $quantity);

        $response = [
            'success' => 1,
        ];
        return View::create($response, Response::HTTP_OK);
    }

    /**
     * Delete Product from Cart
     *
     * @Rest\Delete("/cart/delete/{productId}")
     * @param int $productId
     * @return View
     */
    public function delete(int $productId): View
    {
    }


    /**
     * Get Cart total
     *
     * @Rest\Get("/cart/total")
     * @param Security $security
     * @param CartFacade $cartFacade
     * @return View
     */
    public function total(Security $security, CartFacade $cartFacade): View
    {

        $user = $security->getUser();
        if (!$user instanceof User) {
            throw new AuthenticationException("No authenticated user.");
        }
        $cartPricing = $cartFacade->calculateTotal($user);

        $response = [
            'success' => 1,
            'total' => $cartPricing->getTotal(),
            'detailedTotal' => [
                'product_prices' => $cartPricing->getProductPrices(),
                'free_products' => $cartPricing->getFreeProducts(),
                'products_total' => $cartPricing->getTotalByProducts(),
                'discounts_applied' => $cartPricing->getDiscountsApplied(),
            ]
        ];
        return View::create($response, Response::HTTP_OK);
    }

}
