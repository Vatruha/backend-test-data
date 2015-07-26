<?php

namespace AppBundle\Controller;


use FOS\RestBundle\Controller\FOSRestController;
use AppBundle\Exceptions\NotEnoughProductsInStockException;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

use AppBundle\Entity\Product;
use AppBundle\Entity\BasketItem;
use AppBundle\Entity\User;
use AppBundle\Entity\Order;
use AppBundle\Entity\OrderItem;
use Doctrine\DBAL\Connection;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;

class OrderRestController extends FOSRestController{
    
    
    
   /**
    * @ApiDoc(
    *   description="Returns a collection of orders for current authorized user"
    * )
    * get orders list
    * @return Symfony\Component\HttpFoundation\Response
    */
    public function allAction()
    {
         
        $view = $this->view($this->getUser()->getOrders(), 200);
        return $this->handleView($view);    
    }
    
   /** 
    * @ApiDoc(
    *   description="Create order, products from basket will copied to order and removed from basket"
    * )
    * 
    */
    public function makeOrderAction(){
        /**
         * $conn
         * @var \Doctrine\DBAL\Connection;
         */
        $basketItemsList = $this->getUser()->getBasketItems();
        if(null == $basketItemsList || !count($basketItemsList)){
            throw new BadRequestHttpException("Basket is empty");
        }
        $conn = $this->getDoctrine()->getConnection();
        $conn->setTransactionIsolation(Connection::TRANSACTION_SERIALIZABLE);
        try{
            $conn->beginTransaction();
            $order = new Order();
            $order->setUser($this->getUser());
            foreach($basketItemsList as $basketItem){
                $product = $basketItem->getProduct();
                if($product->getQuantity() < $basketItem->getQuantity()){
                    throw new NotEnoughProductsInStockException("Not enough products in stock");
                }
                $product->setQuantity($product->getQuantity() - $basketItem->getQuantity());
                
                $orderItem = new OrderItem();
                $orderItem->setProduct($product);
                $orderItem->setQuantity($basketItem->getQuantity());
                $orderItem->setOrder($order);
                $order->addItem($orderItem);                
                $this->getDoctrine()->getManager()->persist($product);
                $this->getDoctrine()->getManager()->persist($orderItem);
                $this->getDoctrine()->getManager()->remove($basketItem);
            }
            $this->getDoctrine()->getManager()->persist($order);
            $this->getDoctrine()->getManager()->flush();
            $conn->commit();
        }
        catch(\Exception $e){
            $conn->rollBack();
            throw $e; 
        }        
        $view = $this->routeRedirectView('ordrers_list');
        $view->setData($this->get('router')->generate('ordrers_list', array(), true));
        return $this->handleView($view);  
    }
}