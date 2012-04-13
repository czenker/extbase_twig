<?php

class Tx_ExtbaseTwig_Twig_Environment extends Twig_Environment {

    /**
     * @var Tx_Extbase_MVC_Controller_ControllerContext
     */
    protected $controllerContext;

    /**
     * @param Tx_Extbase_MVC_Controller_ControllerContext $controllerContext
     */
    public function setControllerContext($controllerContext) {
        $this->controllerContext = $controllerContext;
    }

    /**
     * @return Tx_Extbase_MVC_Controller_ControllerContext
     */
    public function getControllerContext() {
        return $this->controllerContext;
    }

    /**
     * @return Tx_Extbase_MVC_Web_Routing_UriBuilder
     */
    public function getUriBuilder() {
        return $this->controllerContext->getUriBuilder();
    }




}