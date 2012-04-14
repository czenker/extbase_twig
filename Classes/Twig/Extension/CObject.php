<?php


/**
 * functions/ filters that require a cObject
 */
class Tx_ExtbaseTwig_Twig_Extension_CObject extends Twig_Extension
{

	protected static $cObject;

	public function getFunctions()
	{
		return array(
			'image' => new Twig_Function_Function('Tx_ExtbaseTwig_Twig_Extension_CObject::image'),
			'cObject' => new Twig_Function_Function('Tx_ExtbaseTwig_Twig_Extension_CObject::cObject', array('needs_environment' => true)),
			'rte' => new Twig_Function_Function('Tx_ExtbaseTwig_Twig_Extension_CObject::rte', array('is_safe' => array('html'))),
			'crop' => new Twig_Function_Function('Tx_ExtbaseTwig_Twig_Extension_CObject::crop'),
			'cropHTML' => new Twig_Function_Function('Tx_ExtbaseTwig_Twig_Extension_CObject::cropHTML', array('is_safe' => array('html'))),
		);
	}


	public static function image($src, $width, $height)
	{
		if (TYPO3_MODE === 'BE') {
			throw new RuntimeException(get_class(self) . ' currently only works in Frontend.');
		}

		$setup = array(
			'width' => $width,
			'height' => $height,
		);

		$imageInfo = self::getCObject()->getImgResource($src, $setup);
		$GLOBALS['TSFE']->lastImageInfo = $imageInfo;
		if (!is_array($imageInfo)) {
			throw new InvalidArgumentException('Could not get image resource for "' . htmlspecialchars($src) . '".', 1253191060);
		}
		$imageInfo[3] = t3lib_div::png_to_gif_by_imagemagick($imageInfo[3]);

		$GLOBALS['TSFE']->imagesOnPage[] = $imageInfo[3];

		$imageSource = $GLOBALS['TSFE']->absRefPrefix . t3lib_div::rawUrlEncodeFP($imageInfo[3]);

		return new Tx_ExtbaseTwig_Twig_Model_Image($imageSource, $imageInfo[0], $imageInfo[1]);
	}

	/**
	 * @static
	 * @param Tx_ExtbaseTwig_Twig_Environment $env
	 * @param $typoscriptObjectPath
	 * @param null $data
	 * @param null $currentValueKey
	 * @return string
	 * @throws RuntimeException|InvalidArgumentException
	 */
	public static function cObject(Tx_ExtbaseTwig_Twig_Environment $env, $typoscriptObjectPath, $data = NULL, $currentValueKey = NULL)
	{
		if (TYPO3_MODE === 'BE') {
			throw new RuntimeException(get_class(self) . ' currently only works in Frontend.');
		}

		if (is_object($data)) {
			$data = Tx_Extbase_Reflection_ObjectAccess::getAccessibleProperties($data);
		} elseif (is_string($data)) {
			$currentValue = $data;
			$data = array($data);
		}

		$cObject = self::getCObject();

		$cObject->start($data);
		if (isset($currentValue)) {
			$cObject->setCurrentVal($currentValue);
		} elseif ($currentValueKey !== NULL && isset($data[$currentValueKey])) {
			$cObject->setCurrentVal($data[$currentValueKey]);
		}

		$pathSegments = t3lib_div::trimExplode('.', $typoscriptObjectPath);
		$lastSegment = array_pop($pathSegments);
		$setup = self::getConfigurationManger()->getConfiguration(Tx_Extbase_Configuration_ConfigurationManagerInterface::CONFIGURATION_TYPE_FULL_TYPOSCRIPT);

		foreach ($pathSegments as $segment) {
			if (!array_key_exists($segment . '.', $setup)) {
				throw new InvalidArgumentException('TypoScript object path "' . htmlspecialchars($typoscriptObjectPath) . '" does not exist', 1253191023);
			}
			$setup = $setup[$segment . '.'];
		}
		$content = $cObject->cObjGetSingle($setup[$lastSegment], $setup[$lastSegment . '.']);

		return $content;
	}

	/**
	 * @param string $value
	 * @param string $parseFuncTSPath
	 * @return string
	 * @throws RuntimeException
	 */
	public function rte($value, $parseFuncTSPath = 'lib.parseFunc_RTE') {
		if (TYPO3_MODE === 'BE') {
			throw new RuntimeException(get_class(self) . ' currently only works in Frontend.');
		}

		return self::getCObject()->parseFunc($value, array(), '< ' . $parseFuncTSPath);
	}

	public function crop($stringToTruncate, $maxCharacters, $append = '...', $respectWordBoundaries = TRUE) {
		return self::getCObject()->crop($stringToTruncate, $maxCharacters . '|' . $append . '|' . $respectWordBoundaries);
	}

	public function cropHTML($stringToTruncate, $maxCharacters, $append = '...', $respectWordBoundaries = TRUE) {
		return self::getCObject()->cropHTML($stringToTruncate, $maxCharacters . '|' . $append . '|' . $respectWordBoundaries);
	}

	/**
	 * @return tslib_cObj
	 */
	protected static function getCObject() {
		if(is_null(self::$cObject)) {
			self::$cObject = self::getConfigurationManger()->getContentObject();
		}

		return self::$cObject;
	}

	/**
	 * @static
	 * @return Tx_Extbase_Configuration_ConfigurationManagerInterface
	 */
	protected static function getConfigurationManger() {
		/**
		 * @var Tx_Extbase_Object_Manager
		 */
		$objectManager = t3lib_div::makeInstance('Tx_Extbase_Object_Manager');
		return $objectManager->get('Tx_Extbase_Configuration_ConfigurationManagerInterface');
	}


	/**
	 * Returns the name of the extension.
	 *
	 * @return string The extension name
	 */
	public function getName()
	{
		return 'extbasetwig.cobj';
	}
}