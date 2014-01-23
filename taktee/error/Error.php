<?php

/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */

/**
 * Taktee Error Handler, detailed information about error
 *
 * requires PHP version 5.3+
 *
 * @category	Error Handling
 * @package		Taktee
 * @author		Junaid Atari <mj.atari@gmail.com>
 * @copyright	2014 Junaid Atari
 * @version		0.1
 * @license		http://www.apache.org/licenses/LICENSE-2.0.html
 * @see			TakteeErrorInternal
 * @see			Taktee::setError ()
 */
class TakteeError
{
	/**
	 *@var string Error category name
	 */
	protected $category = '';

	/**
	 *@var int Error Code
	 */
	protected $errorNumber = 0;

	/**
	 *@var string Error message
	 */
	protected $message = '';

	/**
	 *@var int Error on line
	 */
	protected $line = 0;

	/**
	 *@var string Error line's code
	 */
	protected $code = '';

	/**
	 *@var string File path
	 */
	protected $file = '';

	/**
	 * Constructor function
	 *
	 * @access public
	 * @author Junaid Atari <mj.atari@gmail.com>
	 *
	 * @param integer $code Error ID
	 * @param string $message Error message
	 * @param integer $lineNumber Line number
	 * @param string $expression Code of line
	 * @param string $file File path
	 * @param string $category Error category
	 * @return void
	 */
	public function __construct ( $code, $message, $lineNumber = 0, $expression, $file = '', $category = '' )
	{
		$this->errorNumber = $code;
		$this->message = $message;
		$this->line = $lineNumber;
		$this->code = $expression;
		$this->file = $file;
		$this->category = $category;
	}

	/**
	 * Return error in text when echo the object
	 *
	 * @return string PHP style error message
	 */
	public function __toString ()
	{
		return '<strong>Taktee '.strtolower($this->category).'</strong> ('.$this->errorNumber.'): ' . $this->message . ' in <strong>'
			. $this->file . '</strong> on line <strong>' . $this->line . '</strong>';
	}

	/**
	 * Get the number/id/code of error
	 *
	 * @access public
	 * @author Junaid Atari <mj.atari@gmail.com>
	 *
	 * @return int
	 */
	public function getNumber ()
	{
		return $this->errorNumber;
	}

	/**
	 * Get the error message
	 *
	 * @access public
	 * @author Junaid Atari <mj.atari@gmail.com>
	 *
	 * @return string
	 */
	public function getMessage ()
	{
		return $this->message;
	}

	/**
	 * Get the name of error category
	 *
	 * @access public
	 * @author Junaid Atari <mj.atari@gmail.com>
	 *
	 * @return string
	 */
	public function getCategory ()
	{
		return $this->category;
	}

	/**
	 * Get the error line number
	 *
	 * @access public
	 * @author Junaid Atari <mj.atari@gmail.com>
	 *
	 * @return int
	 */
	public function getLineNumber ()
	{
		return $this->line;
	}

	/**
	 * Get the complete text of line | code statment
	 *
	 * @access public
	 * @author Junaid Atari <mj.atari@gmail.com>
	 *
	 * @return string
	 */
	public function getCode ()
	{
		return $this->code;
	}

	/**
	 * Get the complete file path where error occurred
	 *
	 * @access public
	 * @author Junaid Atari <mj.atari@gmail.com>
	 *
	 * @return string
	 */
	public function getFile ()
	{
		return $this->file;
	}
}