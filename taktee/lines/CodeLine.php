<?php

/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */

/**
 * Taktee Code line handler
 *
 * requires PHP version 5.3+
 *
 * @category	Base
 * @package		Taktee
 * @author		Junaid Atari <mj.atari@gmail.com>
 * @copyright	2014 Junaid Atari
 * @version		0.1
 * @license		http://www.apache.org/licenses/LICENSE-2.0.html
 * @see			TakteeLinesMap
 */
class TakteeCodeLine
{
	/**
	 * @access protected
     * @var int Line number
     */
	protected $line = 0;
	
	/**
	 * @access protected
     * @var mixed Code expression | null
     */
	protected $code = null;
	
	/**
	 * Constructor function
	 *
	 * @access public
	 * @author Junaid Atari <mj.atari@gmail.com>
	 *
	 * @param int $line Line number
	 * @param mixed $code Line Code
	 * @return void
	 */
	public function __construct ( $line, $code )
	{
		// Set line number
		$this->line = $line;
		
		// Set line code
		$this->code = $code;
	}
	
	/**
	 * Get the line number
	 *
	 * @access public
	 * @author Junaid Atari <mj.atari@gmail.com>
	 *
	 * @return int Line number
	 */
	public function getLine ()
	{
		return $this->line;
	}
	
	/**
	 * Get the line code
	 *
	 * @access public
	 * @author Junaid Atari <mj.atari@gmail.com>
	 *
	 * @return mixed Line code | null
	 */
	public function getCode ()
	{
		return $this->code;
	}
}
