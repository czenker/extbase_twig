<?php

class Tx_ExtbaseTwig_MVC_Controller_ControllerContext extends Tx_Extbase_MVC_Controller_ControllerContext {

	/**
	 * @var Tx_ExtbaseTwig_MVC_Controller_FlashMessages
	 */
	protected $flashMessageContainer;


	public function setFlashMessageContainer($flashMessageContainer) {
		$this->flashMessageContainer = $flashMessageContainer;
	}

	/**
	 * @return Tx_ExtbaseTwig_MVC_Controller_FlashMessages
	 */
	public function getFlashMessageContainer() {
		return $this->flashMessageContainer;
	}

	public function getFlashMessages() {
		return $this->flashMessageContainer;
	}

	/**
	 * @var Tx_Extbase_Object_Manager
	 */
	protected $objectManager;

	/**
	 * @var Tx_Extbase_Configuration_ConfigurationManagerInterface
	 */
	protected $configurationManager;

	/**
	 * @param \Tx_Extbase_Object_Manager $objectManager
	 */
	public function setObjectManager($objectManager)
	{
		$this->objectManager = $objectManager;
	}

	/**
	 * @return \Tx_Extbase_Object_Manager
	 */
	public function getObjectManager()
	{
		return $this->objectManager;
	}



	/**
	 * @param \Tx_Extbase_Configuration_ConfigurationManagerInterface $configurationManager
	 */
	public function setConfigurationManager($configurationManager)
	{
		$this->configurationManager = $configurationManager;
	}

	/**
	 * @return \Tx_Extbase_Configuration_ConfigurationManagerInterface
	 */
	public function getConfigurationManager()
	{
		return $this->configurationManager;
	}





	public static function createFromExtbaseControllerContext(Tx_Extbase_MVC_Controller_ControllerContext $extbaseContext) {
		$context = new self();

		$context->setObjectManager(t3lib_div::makeInstance('Tx_Extbase_Object_Manager'));
		$context->setConfigurationManager($context->getObjectManager()->get('Tx_Extbase_Configuration_ConfigurationManagerInterface'));

		$context->setArguments($extbaseContext->getArguments());
		$context->setArgumentsMappingResults($extbaseContext->getArgumentsMappingResults());
		$context->setFlashMessageContainer($context->getObjectManager()->get('Tx_ExtbaseTwig_MVC_Controller_FlashMessages'));
		$context->setRequest($extbaseContext->getRequest());
		$context->setResponse($extbaseContext->getResponse());
		$context->setUriBuilder($extbaseContext->getUriBuilder());

		return $context;
	}


}