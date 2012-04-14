<?php

/**
 * wrapper for the flash messages to have an easy interface for a twig interface
 *
 * you can do some operations queries on the number of flash messages without
 * flushing them. Only if you iterate over them, they are removed from the queue.
 *
 */
class Tx_ExtbaseTwig_MVC_Controller_FlashMessages implements Countable, Iterator {

	/**
	 * @var array<t3lib_FlashMessage>
	 */
	protected $messages = null;

	protected function init() {
		if(is_null($this->messages)) {
			$this->messages = t3lib_FlashMessageQueue::getAllMessagesAndFlush();
		}
	}

	public function current()
	{
		$this->init();
		return current($this->messages);
	}

	public function next()
	{
		$this->init();
		return next($this->messages);
	}

	public function key()
	{
		$this->init();
		return key($this->messages);
	}


	public function valid()
	{
		$this->init();
		return key($this->messages) !== FALSE;
	}


	public function rewind() {
		reset($this->messages);
	}

	/**
	 * get the number of flash messages
	 * @return int
	 */
	public function count()
	{
		return count(t3lib_FlashMessageQueue::getAllMessages());
	}
}