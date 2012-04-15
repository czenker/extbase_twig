<?php

class Tx_ExtbaseTwig_View_TwigView implements Tx_Extbase_MVC_View_ViewInterface {

    /**
     * @var bool
     */
    protected static $twigAutoloaderInitialized = false;


    /**
     * @var Tx_ExtbaseTwig_MVC_Controller_ControllerContext
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
	    if(
			$controllerContext instanceof Tx_Extbase_MVC_Controller_ControllerContext &&
			! $controllerContext instanceof Tx_ExtbaseTwig_MVC_Controller_ControllerContext
	    ) {
		    $this->controllerContext = Tx_ExtbaseTwig_MVC_Controller_ControllerContext::createFromExtbaseControllerContext($controllerContext);
	    } else {
            $this->controllerContext = $controllerContext;
	    }
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

        $template = $this->twigEnvironment->loadTemplate($templateName, Tx_ExtbaseTwig_Twig_Environment::LOADER_TEMPLATE);
        return $template->render($this->variables);
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
	    $setup = $this->controllerContext->getConfigurationManager()->getConfiguration(Tx_Extbase_Configuration_ConfigurationManagerInterface::CONFIGURATION_TYPE_FULL_TYPOSCRIPT);

	    if(!array_key_exists('config.', $setup) || !array_key_exists('tx_extbasetwig.', $setup['config.'])) {
		    throw new InvalidArgumentException('config.tx_extbasetwig was not configured');
	    }

	    $setup = $setup['config.']['tx_extbasetwig.'];

        $this->twigEnvironment = new Tx_ExtbaseTwig_Twig_Environment(null, array(
            //'cache' => PATH_site.'typo3temp/twig',
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

		if(array_key_exists('extensions.', $setup)) {
			$this->initExtensions($setup['extensions.']);
		}



	    // @todo this should be more selective (UriBuilder is not needed for instance)
	    $this->twigEnvironment->addGlobal('typo3', $this->controllerContext);
    }

	public function initExtensions($config) {

		if(!is_array($config) && !$config instanceof Iterator) {
			return;
		}

		foreach($config as $extensionClassName => $extensionConfig) {
			$extensionClassName = rtrim($extensionClassName, '.');

			if(!array_key_exists('enable', $extensionConfig)) {
				throw new InvalidArgumentException(sprintf('config.tx_extbasetwig.%s.enable is not set', $extensionClassName));
			}

			if(!$extensionConfig['enable']) {
				continue;
			}

			$extension = $this->controllerContext->getObjectManager()->get($extensionClassName);
			if(!is_object($extension)) {
				throw new InvalidArgumentException(sprintf('Extension class %s could not be found.'));
			}
			if(!$extension instanceof Twig_ExtensionInterface) {
				throw new InvalidArgumentException(sprintf(
					'Class %s does not implement Twig_ExtensionInterface',
					get_class($extension)
				));
			}
			$this->twigEnvironment->addExtension($extension);
		}
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