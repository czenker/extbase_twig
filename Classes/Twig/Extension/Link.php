<?php

class Tx_ExtbaseTwig_Twig_Extension_Link extends Twig_Extension {

    public function getFunctions()
    {
        return array(
            'path_page' => new Twig_Function_Method($this, 'path_page', array('needs_environment' => true)),
            'path_action' => new Twig_Function_Method($this, 'path_action', array('needs_environment' => true)),
            'uri_page' => new Twig_Function_Method($this, 'uri_page', array('needs_environment' => true)),
            'uri_action' => new Twig_Function_Method($this, 'uri_action', array('needs_environment' => true)),
        );
    }



    /**
     * Returns the name of the extension.
     *
     * @return string The extension name
     */
    public function getName()
    {
        return 'extbasetwig.link';
    }

    public function path_page(Twig_Environment $env, $pageUid, $options=array()) {
        $options['pageUid'] = $pageUid;

        $uriBuilder = $this->getInitializedUriBuilder($env, $options);

        return $uriBuilder->build();
    }

    public function path_action(Twig_Environment $env, $action = NULL, $options=array()) {

        $format = array_key_exists('format', $options) ? $options['format'] : '';
        $arguments = array_key_exists('arguments', $options) ? $options['arguments'] : array();
        $controller = array_key_exists('controller', $options) ? $options['controller'] : NULL;
        $extensionName = array_key_exists('extensionName', $options) ? $options['extensionName'] : NULL;
        $pluginName = array_key_exists('pluginName', $options) ? $options['pluginName'] : NULL;


        return $this->getInitializedUriBuilder($env, $options)
            ->setFormat($format)
            ->uriFor($action, $arguments, $controller, $extensionName, $pluginName);
    }

    public function uri_page(Twig_Environment $env, $pageUid, $options) {
        $options['absolute'] = true;
        return $this->path_page($env, $pageUid, $options);
    }

    public function uri_action(Twig_Environment $env, $action = NULL, $options=array()) {
        $options['absolute'] = true;
        return $this->path_action($env, $action, $options);
    }

    protected function getInitializedUriBuilder($env, $options) {
        $uriBuilder = $env->getUriBuilder()->reset();

        if(array_key_exists('pageUid', $options)) {
            $uriBuilder->setTargetPageUid($options['pageUid']);
        }
        if(array_key_exists('pageType', $options)) {
            $uriBuilder->setTargetPageType($options['pageType']);
        }
        if(array_key_exists('noCache', $options)) {
            $uriBuilder->setNoCache($options['noCache']);
        }
        if(array_key_exists('noCacheHash', $options)) {
            $uriBuilder->setUseCacheHash(!$options['noCacheHash']);
        }
        if(array_key_exists('section', $options)) {
            $uriBuilder->setSection($options['section']);
        }
        if(array_key_exists('linkAccessRestrictedPages', $options)) {
            $uriBuilder->setLinkAccessRestrictedPages($options['linkAccessRestrictedPages']);
        }
        if(array_key_exists('additionalParams', $options)) {
            $uriBuilder->setArguments($options['additionalParams']);
        }
        if(array_key_exists('absolute', $options)) {
            $uriBuilder->setCreateAbsoluteUri($options['absolute']);
        }
        if(array_key_exists('addQueryString', $options)) {
            $uriBuilder->setAddQueryString($options['addQueryString']);
        }
        if(array_key_exists('argumentsToBeExcludedFromQueryString', $options)) {
            $uriBuilder->setArgumentsToBeExcludedFromQueryString($options['argumentsToBeExcludedFromQueryString']);
        }

        // @todo throw error on unknown option

        return $uriBuilder;

    }
}