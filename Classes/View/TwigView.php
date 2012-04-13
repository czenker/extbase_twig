<?php

class Tx_ExtbaseTwig_View_TwigView implements Tx_Extbase_MVC_View_ViewInterface {


    /**
     * @var Tx_Extbase_MVC_Controller_ControllerContext
     */
    protected $controllerContext;

    protected $variables = array();

    /**
     * Sets the current controller context
     *
     * @param Tx_Extbase_MVC_Controller_ControllerContext $controllerContext
     * @return void
     */
    public function setControllerContext(Tx_Extbase_MVC_Controller_ControllerContext $controllerContext)
    {
        $this->controllerContext = $controllerContext;
    }

    /**
     * Add a variable to the view data collection.
     * Can be chained, so $this->view->assign(..., ...)->assign(..., ...); is possible
     *
     * @param string $key Key of variable
     * @param object $value Value of object
     * @return Tx_Extbase_MVC_View_ViewInterface an instance of $this, to enable chaining
     * @api
     */
    public function assign($key, $value)
    {
        $this->variables[$key] = $value;
        return $this;
    }

    /**
     * Add multiple variables to the view data collection
     *
     * @param array $values array in the format array(key1 => value1, key2 => value2)
     * @return Tx_Extbase_MVC_View_ViewInterface an instance of $this, to enable chaining
     * @api
     */
    public function assignMultiple(array $values)
    {
        $this->variables = array_merge(
            $this->variables,
            $values
        );

        return $this;
    }

    /**
     * Tells if the view implementation can render the view for the given context.
     *
     * @param Tx_Extbase_MVC_Controller_ControllerContext $controllerContext
     * @return boolean TRUE if the view has something useful to display, otherwise FALSE
     * @api
     */
    public function canRender(Tx_Extbase_MVC_Controller_ControllerContext $controllerContext)
    {
        // @todo
        return true;
    }

    /**
     * Renders the view
     *
     * @return string The rendered view
     * @api
     */
    public function render()
    {
        $this->initTwigAutoloader();

        $loader = new Twig_Loader_Filesystem('typo3conf/ext/twig_hello/Resources/Templates/Default');
        $twig = new Twig_Environment($loader, array(
            'cache' => 'typo3temp/twig/',
        ));

        return $twig->render('index.html.twig', $this->variables);
    }

    /**
     * Initializes this view.
     *
     * @return void
     * @api
     */
    public function initializeView() {
    }

    protected function initTwigAutoloader() {
        require_once t3lib_extMgm::extPath('extbase_twig').'Vendor/Twig/lib/Twig/Autoloader.php';
        Twig_Autoloader::register();
    }
}