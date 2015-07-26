<?php
namespace AppBundle\Controller;


use FOS\RestBundle\Controller\FOSRestController;
use AppBundle\Exceptions\NotEnoughProductsInStockException;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\HttpFoundation\Request;
use \AppBundle\Entity\Product;
use AppBundle\Entity\BasketItem;
use \AppBundle\Entity\User;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;

class BasketRestController extends FOSRestController{
    const PARAM_QUANTITY = 'quantity';
    
    
    /**    
    * @ApiDoc(
    *   description="Get products list in Basket for current user"
    * )
    *
    * Get all items list in Basket
    * @return Symfony\Component\HttpFoundation\Response
    */
    public function allItemsInBasketAction(){
        
        $view = $this->view($this->getUser()->getBasketItems(), 200);
        return $this->handleView($view); 
    }
    
    /**
     * @ApiDoc(
     *     description="Add product to basket with quantity",
     *     parameters={
     *        {
     *            "name"="product",
     *            "required"=true,
     *            "dataType"="integer",
     *            "format"="\d+",
     *            "description"="Product id"
     *        },
     *        {
     *            "name"="quantity",
     *            "required"=false,
     *            "default"=1,
     *            "dataType"="integer",
     *            "format"="\d+",
     *            "description"="How many products need add to basket, default = 1"
     *        }
     *     },
     *     statusCodes={
     *         201="Product added successful",
     *         400={
     *           "Product not found",
     *           "Not enough products in stock"
     *         }
     *     }
     * )
     * @param Request $request
     * @return Symfony\Component\HttpFoundation\Response
     * @throws NotFoundHttpException
     * @throws NotEnoughProductsInStockException
     */
    public function addProductAction(Request $request){       
        $user = $this->getUser();
        $product = $this->getDoctrine()->getRepository('AppBundle:Product')
                        ->find((int)$request->get('product'));
        $quantityRequested = (int)($request->get(self::PARAM_QUANTITY) > 1 ? $request->get(self::PARAM_QUANTITY) : 1);
        if(!$product instanceof Product) {
            throw new NotFoundHttpException('Product not found');
        }
        
        if($product->getQuantity() < $quantityRequested){
            throw new NotEnoughProductsInStockException("Not enough products in stock");
        }
        
        $basketItem = $this->getBasketItemWithUserAndProduct($user, $product);
        $basketItem->setQuantity(
                $basketItem->getQuantity()+
                $quantityRequested);
        
        $this->getDoctrine()->getManager()->persist($basketItem);
        $this->getDoctrine()->getManager()->flush();
        $view = $this->routeRedirectView('basket_items_list');
        $view->setData($this->get('router')->generate('basket_items_list', array(), true));
        return $this->handleView($view);                
    }
    
    /**     
     * @ApiDoc(
     *     description="Delete product from basket",
     *     requirements={
     *        {"name"="id", "requirement"="\d+", "dataType"="integer", "required"=true, "description"="Product id"}
     *     }
     * )
     * @param Product $product
     * @return Symfony\Component\HttpFoundation\Response   
     */
    public function deleteProductAction(Product $product){
              
        $this->getDoctrine()->getManager()
                ->remove($this->getBasketItemWithUserAndProduct($this->getUser(), $product));
        $this->getDoctrine()->getManager()->flush();
        
        $view = $this->routeRedirectView('basket_items_list');
        $view->setData($this->get('router')->generate('basket_items_list', array(), true));
        return $this->handleView($view); 
    }
    
    /**
     *     
     * @ApiDoc(
     *     description="Delete product from basket",
     *     requirements={
     *        {"name"="id", "requirement"="\d+", "dataType"="integer", "required"=true, "description"="Product id"}
     *     },
     *     parameters={
     *        {
     *            "name"="quantity",
     *            "required"=true,
     *            "dataType"="integer",
     *            "format"="\d+",
     *            "description"="How many products need set in basket"
     *        }
     *     },
     *     statusCodes = {
     *         201 = "Product in basket updated successful",
     *         400 = { "Not enough products in stock", "Missing requred parameter"}
     *     }
     * )
     * 
     * @param Product $product
     * @param Request $request
     * @return FOS\RestBundle\View\View
     * @throws BadRequestHttpException
     * @throws NotEnoughProductsInStockException
     */
    public function setProductQuantityAction(Product $product, Request $request){
        if($request->get(self::PARAM_QUANTITY) == null){
            throw new BadRequestHttpException("Missing requred parameter '". self::PARAM_QUANTITY."'");
        }
        
        $quantity = (int)$request->get(self::PARAM_QUANTITY);
        
        if($product->getQuantity() < $quantity){
            throw new NotEnoughProductsInStockException("Not enough products in stock");
        }
        
        $user = $this->container->get('security.context')->getToken()->getUser();
        $basketItem = $this->getBasketItemWithUserAndProduct($user, $product);
        if($quantity < 1){
            $this->getDoctrine()->getManager()->remove($basketItem);
        }
        else{
            $basketItem->setQuantity($quantity);
            $this->getDoctrine()->getManager()->persist($basketItem);
        }
        $this->getDoctrine()->getManager()->flush();
        
        $view = $this->routeRedirectView('basket_items_list');
        $view->setData($this->get('router')->generate('basket_items_list', array(), true));
        return $this->handleView($view);       
    }


    
    private function getBasketItemWithUserAndProduct(User $user, Product $product){
        $basketItem = $this->getDoctrine()->getRepository('AppBundle:BasketItem')
                ->findOneBy(array(
                    'product' => $product,
                    'user' => $user
                ));
        if(!$basketItem){
            $basketItem = new BasketItem();
            $basketItem->setProduct($product);
            $basketItem->setUser($user);
        }
        return $basketItem;
    }
}
