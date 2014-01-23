<?php

/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */

/**
 * Taktee Functions parameter handler
 *
 * requires PHP version 5.3+
 *
 * @category	Parameter
 * @package		Taktee
 * @author		Junaid Atari <mj.atari@gmail.com>
 * @copyright	2014 Junaid Atari
 * @version		0.1
 * @license		http://www.apache.org/licenses/LICENSE-2.0.html
 * @see			TakteeParameters
 */
class TakteeParameterTypes
{
	/**
	 * @access protected
     * @var mixed Output data (null | value with type)
     */
	protected $output = null;
	
	/**
	 * @access protected
     * @var string Paramter value (null | string)
     */
	protected $value = null;
	
	/**
	 * @access protected
     * @var string Paramter type (null in not defined, treat as string)
     */
	protected $type = null;
	
	/**
	 * @access protected
     * @var array Supported variable types
     */
	protected $varTypes = array ();
	
	/**
	 * Constructor function
	 *
	 * @access public
	 * @author Junaid Atari <mj.atari@gmail.com>
	 *
	 * @param string $value Parameter value
	 * @param mixed $type Parameter Type
	 * @param array $varTypes Variable type, skiping will use default types
	 * @return void
	 */
	public function __construct ( $value, $type = null, array $varTypes = array ()  )
	{
		// Set parameter value
		$this->value = trim ( (string) $value );
		
		// Set parameter type ([type] value)
		$this->type = $type;
		
		// Set variable types
		if ( count ( $varTypes ) )
			return $this->varTypes = $varTypes;
		
		// Predefined variable types
		$this->varTypes = array (
			'string', 'bool', 'int', 'float', 'null'
		);
	}
	
	/**
     * Execute params expression.
	 *
	 * @access protected
	 * @author Junaid Atari <mj.atari@gmail.com>
	 * @return mixed Output value with variable Type
     */
	protected function execute ()
	{
		// Check if the value unquoted
		$isQuoted = (bool) preg_match ('/^(\'|")(.*)\1$/', $this->value, $matches );

		// Undefined parameter type `$type`.
		if ( $this->type !== null && !in_array ( $this->type, $this->varTypes ) )
		{
			return new TakteeErrorInternal ( 57, array (
				'$type'=> (
					$this->type === null
						? $this->value
						: $this->type
				)
			));
		}
		
		// if quoted, return string
		if ( $isQuoted && ($this->type == 'string' || $this->type == null) )
			return (string) $matches[2];
		
		// Get unquoted value
		if ( $isQuoted )
			$this->value = $matches[2];
		
		// If type int, convert value to Integer
		if ( $this->type == 'int' )
			return (int) $this->value;
		
		// If type bool, convert value to Boolean.
		if ( $this->type == 'bool' )
			return (bool) $this->value;
		
		// If type float, convert value to Float
		if ( $this->type == 'float' )
			return (float) $this->value;
		
		// If type null and value is null the return null
		if ( $this->type === null && $this->value == 'null' )
			return null;
		
		// If type missed and value without quotes matched with value throw error:
		if ( in_array ( $this->value,$this->varTypes ) )
			return new TakteeErrorInternal ( 59, null );
			
		// Check Integer in value
		if ( preg_match ( '/^(?<o>\+|\-)?(?<val>\d+)$/', $this->value ) )
			return (int) $this->value;
		
		// Check Float in value
		if ( preg_match ( '~^(\+|\-)?(?<val>\d+(?:\.\d+)?)$~', $this->value ) )
			return (float) $this->value;
		
		// Check Boolean in value
		if ( in_array ($this->value, array ( 'true', 'false', 'TRUE', 'FALSE' )) )
			return (bool) $this->value;
		
		// Other expressions
		// -----------------------------------------------------------------------------
		
		// Error: Undefined paramater
		return new TakteeErrorInternal ( 58, null );
	}
	
	/**
     * Execute params expression and return the output value.
	 *
	 * @access public
	 * @author Junaid Atari <mj.atari@gmail.com>
	 * @return mixed Output value with variable type
     */
	public function toValue ()
	{
		/*
		 * @see $this->execute() function.
		*/
		$output = $this->execute();
		
		// Other logic here...
		// --------
		
		// Return output
		return $output;
	}
}