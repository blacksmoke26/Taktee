<?php

/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */

# Debug Mode
//define ( 'TAKTEE_DUBUG_OUTPUT', true );

# Add executed code expression in Output
//define ( 'TAKTEE_DEBUG_CODE_OUTPUT', true );

/**
 * Taktee Code Parser
 *
 * requires PHP version 5.3+
 *
 * @category	Base
 * @package		Taktee
 * @author		Junaid Atari <mj.atari@gmail.com>
 * @copyright	2014 Junaid Atari
 * @version		0.2
 * @license		http://www.apache.org/licenses/LICENSE-2.0.html
 */
class Taktee extends TakteeBase
{
	 /**
     * Execute the code file.
     *
	 * @access public
	 * @author Junaid Atari <mj.atari@gmail.com>
	 *
     * @param string $path Path to code file.
	 *
     * @return void
     */
	public function executeFile ( $path )
	{
		if ( !is_file ( $path ) )
			return $this->setError ( 55, array ( '$file'=>$path ) );

		if ( !is_readable ( $path ) )
			return $this->setError ( 56, array ( '$file'=>$path ) );

		$this->lastFilePath = realpath ( $path );

		$code = trim ( file_get_contents ( $path ) );

		return $this->execute ( $code, $sessionIndex, $exprIndex );
	}

	/**
     * Execute the code.
     *
	 * @access public
	 * @author Junaid Atari <mj.atari@gmail.com>
     *
     * @param string $code Code to execute
     * @param bool $debugMode Show the advance details.
     * @return void
     */
	public function execute ( $code )
	{
		$output = $this->renderOutput ( $code );
	}
	
	/**
     * Execute the code.
     *
	 * @access public
	 * @author Junaid Atari <mj.atari@gmail.com>
     *
     * @param int $sessionIndex Index to the tag's id
     * @param int $commandIndex Index to the tag id's line
	 *
     * @return array Output of code
     */
	public function getOutput ( $sessionIndex=null, $lineIndex=null )
	{
		// Return on error
		if ( $this->hasError() )
			return null;
		
		// Get the output
		$output = $this->output;

		if ( isset ( $output[$sessionIndex] ) )
			$output = $output[$sessionIndex];

		if ( isset ( $output[$lineIndex] ) )
			$output = $output[$lineIndex];
		
		return $output;
	}

	/**
	 * Clear the output in memory
	 *
	 * @access public
	 * @author Junaid Atari <mj.atari@gmail.com>
	 *
	 * @return void
	 */
	public function clearOutput ()
	{
		$this->output = null;
	}

	/**
     * Set the language for error messages
     *
	 * @access public
	 * @author Junaid Atari <mj.atari@gmail.com>
	 *
	 * @param string $iso ISO name of langauage (e.g., en_us)
	 * @return void
	 */
	public function setLanguage ( $iso )
	{
		// Check the valid given ISO of language
		if ( !preg_match ('/^[a-z]{2}_[a_z]{2}$/', (string) $iso ) )
			return $this->setError (10);

		// Set the messages language ISO
		$this->language = $iso;
	}

	/**
     * Check that any error occurred
     *
	 * @access public
	 * @author Junaid Atari <mj.atari@gmail.com>
	 *
	 * @return bool
	 */
	public function hasError ()
	{
		// if error code and message both are not null, it means error
		return $this->lastErrorNo === null && $this->lastErrorMsg === null
				? false
				: true;
	}

	/**
     * Details of last occurred error
     *
	 * @access public
	 * @author Junaid Atari <mj.atari@gmail.com>
	 *
	 * @return mixed Null on none | object TakteeError
	 */
	public function getError ()
	{
		// No error, return null
		if ( !$this->hasError() )
			return null;

		// If no file, give the parent file path else last execution file
		$codeFile = $this->lastFilePath === null
				? $_SERVER["SCRIPT_FILENAME"]
				: $this->lastFilePath;

		// Create error object and return
		return new TakteeError (
			$this->lastErrorNo, // Error Code
			$this->lastErrorMsg, // Error Message
			$this->lastErrorLine,  // Error on Line Number
			$this->lastParseCode,  // Error in Expression
			$codeFile, // Error in file
			$this->lastErrorMsgCategory // Error category
		);
	}
}
