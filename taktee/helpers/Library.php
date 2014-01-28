<?php

/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */

/**
 * Taktee Function Plugins's command functions library
 * This is just template class, use it by extending
 *
 * requires PHP version 5.3+
 *
 * @category	Helper
 * @package		Taktee
 * @author		Junaid Atari <mj.atari@gmail.com>
 * @copyright	2014 Junaid Atari
 * @version		0.2
 * @license		http://www.apache.org/licenses/LICENSE-2.0.html
 * @see			TakteeFunction
 */
class TakteeLibrary
{
	protected $options = array ();
	
	/**
	 * Constructor method
	 * Note: Please do not add addtional paramaters or optional
	 *
	 * @access public
	 * @author Junaid Atari <mj.atari@gmail.com>
	 *
	 * @return void
	 */
	public function __construct ( $options = array () )
	{
		$this->options = $options;
	}
	
	/**
	 * Basic information of about library
	 *
	 * @access public
	 * @author Junaid Atari <mj.atari@gmail.com>
	 *
	 * @return string
	 */
	public function _baseInfo ()
	{
		return 'called ' . __FUNCTION__;
	}
	
	/**
	 * Help/reference about using library.
	 *
	 * @access public
	 * @author Junaid Atari <mj.atari@gmail.com>
	 *
	 * @return string
	 */
	public function help ()
	{
		return 'called ' . __FUNCTION__;
	}
}