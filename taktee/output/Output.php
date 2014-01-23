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
 * @see			TakteeOutputDebug, TakteeOutputNull
 */
class TakteeOutput
{
	/**
	 * @access protected
     * @var mixed Holds the output of executed expression or null
     */
	protected $output = null;
	
	/**
	 * @access protected
     * @var mixed The executed code expression (null | string value)
     */
	protected $code = null;
	
	/**
	 * @access protected
     * @var mixed Debug information of execution (null | string value)
     */
	protected $debug = null;
	
	/**
	 * @access protected
     * @var int Holds the executed line number
     */
	protected $lineNo = 0;
	
	/**
	 * Constructor function
	 *
	 * @access public
	 * @author Junaid Atari <mj.atari@gmail.com>
	 *
	 * @param string $output The generation output of code expression
	 * @param int $execLineNo Executed line number
	 * @param string $execExpr Executed code expression
	 * @param array $debugInfo Debug Information of execution
	 * @return void
	 */
	public function __construct ( $output, $execLineNo, $execExpr, array $debugInfo = array () )
	{
		// Set Output
		$this->output = $output;
		
		// Set the execution line number
		$this->lineNo = (int) $execLineNo;
		
		// Set the execution expression when constant defined with `true` value
		if ( defined ('TAKTEE_DEBUG_CODE_OUTPUT') && TAKTEE_DEBUG_CODE_OUTPUT )
			$this->code = $execExpr;
		
		// Constant defined and constant have true value and have debug Information
		if ( defined ('TAKTEE_DUBUG_OUTPUT') && TAKTEE_DUBUG_OUTPUT && count ( $debugInfo ) )
			$this->debug = new TakteeOutputDebug ( $debugInfo );
	}
	
	/**
     * Get the output value
     *
	 * @access public
	 * @author Junaid Atari <mj.atari@gmail.com>
	 *
	 * @return string Output value
	 */
	public function getValue ()
	{
		return $this->output;
	}
	
	/**
     * Get the output value
     *
	 * @access public
	 * @author Junaid Atari <mj.atari@gmail.com>
	 *
	 * @return string Output value
	 */
	public function setValue ( $output )
	{
		$this->output = $output;
	}
	
	/**
     * Get the output's debug information
	 * Note: if constant `TAKTEE_DUBUG_OUTPUT` defined with the `true` value.
     *
	 * @access public
	 * @author Junaid Atari <mj.atari@gmail.com>
	 *
	 * @return mixed NULL | object TakteeOutputDebug
	 */
	public function getDebugInfo ()
	{
		return $this->debug;
	}
	
	/**
     * Get the executed code expression
	 * Note: if constant `TAKTEE_DEBUG_CODE_OUTPUT` defined with the `true` value.
     *
	 * @access public
	 * @author Junaid Atari <mj.atari@gmail.com>
	 *
	 * @return string code statment
	 */
	public function getCode ()
	{
		return $this->code;
	}
	
	/**
     * Get the execution line number
     *
	 * @access public
	 * @author Junaid Atari <mj.atari@gmail.com>
	 *
	 * @return int Line number
	 */
	public function getLineNo ()
	{
		return $this->lineNo;
	}
}