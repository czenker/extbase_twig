<?php

class Tx_ExtbaseTwig_Twig_Extension_Security extends Twig_Extension
{

	public function getFunctions()
	{
		return array(
			'is_authenticated' => new Twig_Function_Function('Tx_ExtbaseTwig_Twig_Extension_Security::is_authenticated'),
			'has_role' => new Twig_Function_Function('Tx_ExtbaseTwig_Twig_Extension_Security::has_role'),
		);
	}

	public static function is_authenticated()
	{
		return isset($GLOBALS['TSFE']) && $GLOBALS['TSFE']->loginUser;
	}

	public static function has_role($role)
	{
		if (!isset($GLOBALS['TSFE']) || !$GLOBALS['TSFE']->loginUser) {
			return FALSE;
		}
		if (is_numeric($role)) {
			return (is_array($GLOBALS['TSFE']->fe_user->groupData['uid']) && in_array($role, $GLOBALS['TSFE']->fe_user->groupData['uid']));
		} else {
			return (is_array($GLOBALS['TSFE']->fe_user->groupData['title']) && in_array($role, $GLOBALS['TSFE']->fe_user->groupData['title']));
		}
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