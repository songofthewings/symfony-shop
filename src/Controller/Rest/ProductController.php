<?php


namespace App\Controller\Rest;


use FOS\RestBundle\Controller\AbstractFOSRestController;
use FOS\RestBundle\Controller\Annotations as Rest;

class ProductController extends AbstractFOSRestController
{
    /**
     * @var ProductRepository
     */
    protected $productRepository;

    /**
     * Retrieves a Product resource
     * @Rest\Get("/products/{categoryId}")
     * @param int $categoryId
     * @return View
     */
    public function getProducts(int $categoryId): View
    {
        $products = $this->productRepository->findByCategoryId($categoryId);
        return View::create($products, Response::HTTP_OK);
    }

}
