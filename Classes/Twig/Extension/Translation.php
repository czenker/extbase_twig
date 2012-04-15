<?php

class Tx_ExtbaseTwig_Twig_Extension_Translation extends Twig_Extension
{

	public function getFunctions()
	{
		return array(
			'translate' => new Twig_Function_Method($this, 'translate', array('needs_environment' => true)),
		);
	}

	public function translate(Tx_ExtbaseTwig_Twig_Environment $env, $key, $arguments = array(), $extensionName = NULL)
	{
		if(is_null($extensionName)) {
			$extensionName = $env->getControllerContext()->getRequest()->getControllerExtensionName();
		}
		return Tx_Extbase_Utility_Localization::translate($key, $extensionName, $arguments);
	}


	/**
	 * Returns the name of the extension.
	 *
	 * @return string The extension name
	 */
	public function getName()
	{
		return 'extbasetwig.translate';
	}
}