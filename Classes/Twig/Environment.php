<?php

class Tx_ExtbaseTwig_Twig_Environment extends Twig_Environment {

    const LOADER_PARTIAL = 1;
    const LOADER_TEMPLATE = 2;
    const LOADER_LAYOUT = 3;

    /**
     * loader will be switched depending if a partial, layout or a template should be loaded
     */
    protected $loader;

    /**
     * the loader for a template
     *
     * @var Twig_LoaderInterface
     */
    protected $templateLoader;

    /**
     * the loader for a partial
     *
     * @var Twig_LoaderInterface
     */
    protected $partialLoader;

    /**
     * the loader for a layout
     *
     * @var Twig_LoaderInterface
     */
    protected $layoutLoader;


    /**
     * @var Tx_ExtbaseTwig_MVC_Controller_ControllerContext
     */
    protected $controllerContext;

    /**
     * @param Tx_ExtbaseTwig_MVC_Controller_ControllerContext $controllerContext
     */
    public function setControllerContext($controllerContext) {
        $this->controllerContext = $controllerContext;
    }

    /**
     * @return Tx_ExtbaseTwig_MVC_Controller_ControllerContext
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

    /**
     * set loader for partials
     *
     * @param Twig_LoaderInterface $loader
     */
    public function setPartialLoader(Twig_LoaderInterface $loader) {
        $this->partialLoader = $loader;
    }

    /**
     * get loader for partials
     *
     * @return Twig_LoaderInterface
     */
    public function getPartialLoader() {
        return $this->partialLoader;
    }

    /**
     * set loader for templates
     *
     * @param Twig_LoaderInterface $loader
     */
    public function setTemplateLoader(Twig_LoaderInterface $loader) {
        $this->templateLoader = $loader;
    }

    public function getTemplateLoader() {
        return $this->templateLoader;
    }

    /**
     * set loader for layouts
     *
     * @param Twig_LoaderInterface $loader
     */
    public function setLayoutLoader(Twig_LoaderInterface $loader) {
        $this->layoutLoader = $loader;
    }

    public function getLayoutLoader() {
        return $this->layoutLoader;
    }



    public function loadTemplate($name, $type = self::LOADER_LAYOUT) {
        $this->useLoader($type);
        return parent::loadTemplate($name);
    }

    public function resolveTemplate($names, $type = self::LOADER_LAYOUT)
    {
        if (!is_array($names)) {
            $names = array($names);
        }

        foreach ($names as $name) {
            if ($name instanceof Twig_Template) {
                return $name;
            }

            try {
                return $this->loadTemplate($name, $type);
            } catch (Twig_Error_Loader $e) {
            }
        }

        if (1 === count($names)) {
            throw $e;
        }

        throw new Twig_Error_Loader(sprintf('Unable to find one of the following templates: "%s".', implode('", "', $names)));
    }

    /**
     * set a loader to use for the next template requests
     *
     * @param $type
     */
    public function useLoader($type) {
        if($type === NULL) {
            return;
        } elseif($type === self::LOADER_PARTIAL) {
            $this->setLoader($this->partialLoader);
        } elseif($type === self::LOADER_LAYOUT) {
            $this->setLoader($this->layoutLoader);
        } elseif($type === self::LOADER_TEMPLATE) {
            $this->setLoader($this->templateLoader);
        } else {
            throw new Twig_Error_Loader('Could not find the desired loader "'.$type.'"');
        }
    }

	/**
	 * Gets the cache filename for a given template.
	 *
	 * (overridden as t3lib_div::writeFileToTypo3tempDir only
	 * allows two subfolders below typo3_temp in TYPO3 4.5)
	 *
	 * @param string $name The template name
	 *
	 * @return string The cache file name
	 */
	public function getCacheFilename($name)
	{
		if (false === $this->cache) {
			return false;
		}

		$class = substr($this->getTemplateClass($name), strlen($this->templateClassPrefix));

		return $this->getCache().'/'.substr($class, 0, 4).'/'.substr($class, 4).'.php';
	}

	/**
	 * use typo3 internal functions to write cache file
	 * (in order to repect configuration)
	 *
	 * @param $file
	 * @param $content
	 * @return mixed
	 * @throws RuntimeException|Twig_Error_Runtime
	 */
	protected function writeCacheFile($file, $content)
	{
		$state = t3lib_div::writeFileToTypo3tempDir($file, $content);
		if(is_null($state)) {
			// if: everything worked fine
			return;
		} else {
			throw new Twig_Error_Runtime($state);
		}
	}


}