<?php

class Tx_ExtbaseTwig_View_TwigView implements Tx_Extbase_MVC_View_ViewInterface {

    /**
     * @var bool
     */
    protected static $twigAutoloaderInitialized = false;


    /**
     * @var Tx_Extbase_MVC_Controller_ControllerContext
     */
    protected $controllerContext;

    /**
     * variables for the template
     *
     * @var array
     */
    protected $variables = array();

    /**
     * @var Twig_Loader_Filesystem
     */
    protected $twigLoader;

    /**
     * @var Tx_ExtbaseTwig_Twig_Environment
     */
    protected $twigEnvironment;

    /**
     * @var string
     */
    protected $templateRootPathPattern = '@packageResourcesPath/Private/Templates';

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
        $controllerName = $this->controllerContext->getRequest()->getControllerName();
        $actionName = $this->controllerContext->getRequest()->getControllerActionName();
        $formatName = $this->controllerContext->getRequest()->getFormat();

        $templateName = $controllerName.'/'.$actionName.'.'.$formatName.'.twig';

        $this->twigEnvironment->useLoader(Tx_ExtbaseTwig_Twig_Environment::LOADER_TEMPLATE);
        return $this->twigEnvironment->render($templateName, $this->variables);
    }

    /**
     * Initializes this view.
     *
     * @return void
     * @api
     */
    public function initializeView() {
        $this->initTwigAutoloader();

        $this->initTwigEnvironment();

    }

    /**
     * make sure twig autoloader is initialized
     */
    protected function initTwigAutoloader() {
        if(!self::$twigAutoloaderInitialized) {
            require_once t3lib_extMgm::extPath('extbase_twig').'Vendor/Twig/lib/Twig/Autoloader.php';
            Twig_Autoloader::register();
            self::$twigAutoloaderInitialized = true;
        }
    }

    protected function initTwigEnvironment() {
        $this->twigEnvironment = new Tx_ExtbaseTwig_Twig_Environment(null, array(
            //'cache' => 'typo3temp/twig/',
        ));

        // set loaders
        $extKey = $this->controllerContext->getRequest()->getControllerExtensionKey();

        $defaultTemplateRootPath = t3lib_extMgm::extPath($extKey).'Resources/Private/Templates';
        $this->twigEnvironment->setTemplateLoader(new Twig_Loader_Filesystem(array($defaultTemplateRootPath)));

        $defaultPartialRootPath = t3lib_extMgm::extPath($extKey).'Resources/Private/Partials';
        $this->twigEnvironment->setPartialLoader(new Twig_Loader_Filesystem(array($defaultPartialRootPath)));

        $defaultLayoutRootPath = t3lib_extMgm::extPath($extKey).'Resources/Private/Layouts';
        $this->twigEnvironment->setLayoutLoader(new Twig_Loader_Filesystem(array($defaultLayoutRootPath)));

        // set extbase controller context as global
        $this->twigEnvironment->setControllerContext($this->controllerContext);
        // init extensions
        $this->twigEnvironment->addExtension(new Tx_ExtbaseTwig_Twig_Extension_Core());
        $this->twigEnvironment->addExtension(new Tx_ExtbaseTwig_Twig_Extension_Link());
        $this->twigEnvironment->addExtension(new Tx_ExtbaseTwig_Twig_Extension_Image());
    }

    /**
     * add another template root path
     *
     * @param $templateRootPath
     */
    public function setTemplateRootPath($templateRootPath) {
        // @todo this could be an array with fallback

        $this->twigLoader->addPath($templateRootPath);
    }
}