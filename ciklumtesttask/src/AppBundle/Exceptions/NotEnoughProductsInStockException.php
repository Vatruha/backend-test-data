<?php
namespace AppBundle\Exceptions;


use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;


class NotEnoughProductsInStockException extends BadRequestHttpException{
    
}