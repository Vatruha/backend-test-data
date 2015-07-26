<?php
namespace AppBundle\Controller;

use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use FOS\RestBundle\Controller\FOSRestController;
//use FOS\RestBundle\Controller\Annotations as Rest;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use \AppBundle\Entity\Category;
use FOS\RestBundle\Request\ParamFetcher;
use FOS\RestBundle\Controller\Annotations\QueryParam;

class CategoryRestController extends FOSRestController{
    
    
    
   /**
     * @ApiDoc(
     *  description="Returns a collection of Categories",
     * 
     * )
    * get categories list
    * @return Symfony\Component\HttpFoundation\Response
    */
    public function allAction()
    {
        $data = $this->getDoctrine()->getRepository('AppBundle:Category')->findAll(); 
        $view = $this->view($data, 200);
        return $this->handleView($view);    
    }
    
    /**
     * @ApiDoc(
     *  description="Return a concrete Category",
     *  requirements={
     *      {"name"="id", "requirement"="\d+", "dataType"="integer", "required"=true, "description"="category id"}
     *  }
     * )
     * @param type $id
     * @return Symfony\Component\HttpFoundation\Response
     * @throws NotFoundHttpException
     */
    public function getAction($id){
        $category = $this->getDoctrine()->getRepository('AppBundle:Category')->find($id);
        if(!$category instanceof Category) {
            throw new NotFoundHttpException('Category not found');
        }        
        $view = $this->view($category, 200);
        return $this->handleView($view);
    }
    
    
    /**
     *  @ApiDoc(
     *    description="Returns products list in Category",
     *    requirements={
     *        {"name"="id", "requirement"="\d+", "dataType"="integer", "required"=true, "description"="category id"}
     *    }
     *  )
     * @param Category $category
     * @param ParamFetcher $paramFetcher
     * @return Symfony\Component\HttpFoundation\Response
     * @QueryParam(name="offset", requirements="\d+", default="0", description="Offset of the overview.")
     * @QueryParam(name="limit", requirements="\d+", default="10", description="Limit of the overview.")
     * @QueryParam(name="sortBy", requirements="(title|price|quantity)+", description="Sort direction")
     */
    public function getProductsAction(Category $category, ParamFetcher $paramFetcher){
        $paramFetcher->get('sortBy');
        $products = $this->getDoctrine()->getRepository('AppBundle:Product')->findBy(
                array('category'=>$category), 
                $paramFetcher->get('sortBy')?array($paramFetcher->get('sortBy')=>'ASC'):null, 
                $paramFetcher->get('limit'), 
                $paramFetcher->get('offset'));
        
        
        $view = $this->view($products, 200);
        return $this->handleView($view); 
    }
}