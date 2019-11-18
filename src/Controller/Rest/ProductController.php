<?php


namespace App\Controller\Rest;


use App\Entity\Category;
use Doctrine\ORM\EntityNotFoundException;
use FOS\RestBundle\Controller\AbstractFOSRestController;
use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\View\View;
use JMS\Serializer\Exception\UnsupportedFormatException;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\UnsupportedMediaTypeHttpException;

class ProductController extends AbstractFOSRestController
{

    /**
     * Get Product resource by Category ID
     * http://sshop.local/api/xml/products/2
     *
     * @Rest\Get("/{format}/products/{categoryId}")
     * @param string $format
     * @param int $categoryId
     * @return View
     * @throws EntityNotFoundException
     */
    public function getProducts(string $format, int $categoryId): View
    {
        if (!in_array($format, ['json', 'xml'])) {
            throw new UnsupportedFormatException("Unsupported format: {$format}.");
        }

        $categoryRepository = $this->getDoctrine()->getRepository(Category::class);
        $category = $categoryRepository->find($categoryId);
        if (empty($category)) {
            throw new EntityNotFoundException("Category #{$categoryId} not found.");
        }
        $products = $category->getProducts();
        return View::create($products, Response::HTTP_OK)
            ->setFormat($format);
    }

}
