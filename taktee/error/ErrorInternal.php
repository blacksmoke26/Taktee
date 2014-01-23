<?php

/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */

/**
 * Taktee Internal Error Handler, for exchanging error current object to Taktee
 *
 * requires PHP version 5.3+
 *
 * @category	Error Handling
 * @package		Taktee
 * @author		Junaid Atari <mj.atari@gmail.com>
 * @copyright	2014 Junaid Atari
 * @version		0.1
 * @license		http://www.apache.org/licenses/LICENSE-2.0.html
 * @see			TakteeError
 */
class TakteeErrorInternal
{
	/**
	 * @access public
	 * @var int Error ID
	 */
	public $id = 0;

	/**
	 * @access public
	 * @var array Variables for replacing from error messaage
	 */
	public $vars = array ();

	/**
	 * @access protected
	 * @var array Additional properties of error
	 */
	protected $extra = array ();

	/**
	 * Constructor function
	 *
	 * @access public
	 * @author Junaid Atari <mj.atari@gmail.com>
	 *
	 * @param int Valid error number
	 * @param array Variables for replacing from error messaage
	 * @return void
	 */
	function __construct ( $id, $vars = array () )
	{
		$this->id = $id;
		$this->vars = $vars;
	}

	/**
	 * Add/Set the additonal property  for current error
	 *
	 * @access public
	 * @author Junaid Atari <mj.atari@gmail.com>
	 *
	 * @param string $name Name of Property
	 * @param mixed $val Value for the property
	 * @return void
	 */
	public function setKey ( $name, $val = null )
	{
		$this->extra[$name] = $val;
	}

	/**
	 * Get the value of additonal property
	 *
	 * @access public
	 * @author Junaid Atari <mj.atari@gmail.com>
	 *
	 * @param string $name Name of Property
	 * @return mixed Value | Null if not exist
	 */
	public function getKey ( $name )
	{
		if ( isset ( $this->extra[$name] ) )
			return $this->extra[$name];

		return null;
	}
}