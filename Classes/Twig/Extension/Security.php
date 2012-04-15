<?php

class Tx_ExtbaseTwig_Twig_Extension_Security extends Twig_Extension
{

	public function getFunctions()
	{
		return array(
			'is_authenticated' => new Twig_Function_Function('Tx_ExtbaseTwig_Twig_Extension_Security::is_authenticated'),
		);
	}

	public static function is_authenticated()
	{
		return isset($GLOBALS['TSFE']) && $GLOBALS['TSFE']->loginUser;
	}


	/**
	 * Returns the name of the extension.
	 *
	 * @return string The extension name
	 */
	public function getName()
	{
		return 'extbasetwig.security';
	}
}