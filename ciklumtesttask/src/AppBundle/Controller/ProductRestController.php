<?php

namespace AppBundle\Controller;


use FOS\RestBundle\Controller\FOSRestController;

use AppBundle\Entity\Product;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;

class ProductRestController extends FOSRestController{
    
    
    
   /**
    * @ApiDoc(
    *   description="Returns a collection of product images",
    *   requirements={
    *      {"name"="id", "requirement"="\d+", "dataType"="integer", "required"=true, "description"="product id"}
    *  }
    * )
    * get product images list
    * @return Symfony\Component\HttpFoundation\Response
    */
    public function getImagesAction(Product $product)
    {
        
        $view = $this->view($product->getImages(), 200);
        return $this->handleView($view);    
    }
}
    