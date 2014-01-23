<?php

/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */

/**
 * Taktee Output object to display the code output
 *
 * requires PHP version 5.3+
 *
 * @category	Output
 * @package		Taktee
 * @author		Junaid Atari <mj.atari@gmail.com>
 * @copyright	2014 Junaid Atari
 * @version		0.2
 * @license		http://www.apache.org/licenses/LICENSE-2.0.html
 * @see			TakteeOutput, TakteeOutputNull
 */
class TakteeOutputDebug
{
	/**
	 * @access protected
	 * @var mixed Debug information of execution (null | string value)
	 */
	protected $debugInfo = null;

	/**
	 * Constructor function
	 *
	 * @access public
	 * @author Junaid Atari <mj.atari@gmail.com>
	 *
	 * @param string $debugInfo Debug Information array or Object
	 * @return void
	 */
	function __construct ( $debugInfo )
	{
		$this->debugInfo = $debugInfo;
	}

	/**
	 * Get the debug info as array
	 *
	 * @access public
	 * @author Junaid Atari <mj.atari@gmail.com>
	 *
	 * @return array
	 */
	public function asArray ()
	{
		return (array) $this->debugInfo;
	}
}