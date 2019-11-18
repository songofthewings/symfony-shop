<?php


namespace App\Entity;

use JMS\Serializer\Annotation as Serializer;

/**
 * Class Cart
 * @package App\Entity
 * @Serializer\ExclusionPolicy("all")
 */
class Cart
{

    /**
     * @var array
     */
    protected $products;

    /**
     * @var array
     *
     * @Serializer\Expose
     * @Serializer\Type("array<int,int>")
     * @ Serializer\Inline()
     * @Serializer\Accessor(getter="getProductQuantities",setter="setProductQuantities")
     */
    protected $productQuantities = [];

    public function __construct()
    {
    }

    /**
     *
     * @ Serializer\VirtualProperty(name="p")
     */
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

    public function contains(Product $product)
    {
        return !empty($this->productQuantities[$product->getId()]);
    }
}
