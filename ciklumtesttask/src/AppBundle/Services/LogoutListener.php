<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace AppBundle\Services;


use Symfony\Component\Security\Http\Logout\LogoutSuccessHandlerInterface;
use Symfony\Component\Security\Core\SecurityContext;

class LogoutListener implements LogoutSuccessHandlerInterface {

  private $security;  

  public function __construct(SecurityContext $security) {
    $this->security = $security;
  }

  public function onLogoutSuccess(Request $request) {
     $user = $this->security->getToken()->getUser();

    

     $response =  RedirectResponse($this->router->generate('login'));

    return $response;
  }
}
