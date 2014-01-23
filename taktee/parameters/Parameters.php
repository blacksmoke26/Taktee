<?php
/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */

/**
 * Taktee Functions/Commands parameters handler
 *
 * requires PHP version 5.3+
 *
 * @category	Parameters
 * @package		Taktee
 * @author		Junaid Atari <mj.atari@gmail.com>
 * @copyright	2014 Junaid Atari
 * @version		0.1
 * @license		http://www.apache.org/licenses/LICENSE-2.0.html
 * @see			TakteeParametersTypes
 */
class TakteeParameters
{
	/**
	 *@var string Paramaters code
	 */
	protected $code;

	/**
	 * Constructor function
	 *
	 * @access public
	 * @author Junaid Atari <mj.atari@gmail.com>
	 *
	 * @param string $paramCode Paramters Code
	 * @return void
	 */
	public function __construct ( $paramCode )
	{
		$this->code = trim ( $paramCode );
	}

	/**
     * Parse paramters code
	 *
	 * @access protected
	 * @author Junaid Atari <mj.atari@gmail.com>
	 *
	 * @param string $paramCode Param code
	 * @return mixed Parameters output values with variable Types
     */
	protected function parse ()
	{
		// Parameter code is empty
		if ( !$this->code )
			return array ();

		// Parameter given as null
		if ( strtolower ( $this->code ) == 'null' )
			return null;

		// Captured params list
		$parameters = array ();

		// Split by Comma (,) / will no split between quotes);
		preg_match_all ( '/"(?:\\\\.|[^\\\\"])*"|[^,]+/', $this->code, $parameters );

		// return null if none of parameter given
		if ( !count ( $parameters ) )
			return null;

		// Choose 1st section and remove empty array
		$parameters = array_filter ( (array) $parameters[0] );

		// Defined parameteres
		$_params = array ();

		// Parse parameters
		foreach ( $parameters as $param )
		{
			// Remove white spaces from left, right
			$param = trim ( $param );

			//-- Add parameter and parse
			// Check if only 1 param given with quotes (single/double)
			$pvalue = preg_match ( '/^(\'|")(?<val>.*)\1$/', $param, $mat ) > 0
					// Removed skipped words by removing backslash (\)
					? preg_replace ( '/\\\(.)/', '$1', $mat['val'] )
					// Param type found, parse type
					: (
						// Check paramater given
						!$param
							// If none return null
							? NULL
							// Parse param type and value
							: $this->parseType ( $param )
					);

			// If there is an error, throw.
			if ( $pvalue instanceof TakteeErrorInternal )
				return $pvalue;

			// Add to list
			$_params[] = $pvalue;
		}

		// Return defined params with types
		return $_params;
	}

	/**
     * Parse paramter code by type and value and convert value by type
	 *
	 * @access protected
	 * @author Junaid Atari <mj.atari@gmail.com>
	 *
	 * @param string $paramCode Param code
	 * @return mixed Parameter output value with variable Type
     */
	protected function parseType ( $paramCode )
	{
		// Paramater ([type|null], value)
		$param = array ();

		// Split by Space (never split between quotes)
		preg_match_all ( '/("|\')(?:\\\\.|[^\\\\"\'])*\1|\S+/', $paramCode, $param );

		// Get the first group (Type, Value)
		$param = $param[0];

		// if nothing, return null
		if ( !count($param) )
			return null;

		// If given Type, Value, ***, throw error
		if ( count ( $param ) > 2 )
		{
			// Error: Invalid parameter.
			return new TakteeErrorInternal ( 58 );
		}

		// if type and value found.
		$lpparam = count ( $param ) == 2
			// Set value, type
			? new TakteeParameterTypes ( $param[1], $param[0] )
			// Set value
			: new TakteeParameterTypes ( $param[0] );

		// Return param value with type
		return $lpparam->toValue();
	}

	/**
     * Execute logic and get the parameters value.
	 *
	 * @access public
	 * @author Junaid Atari <mj.atari@gmail.com>
	 *
	 * @return mixed Parameter output value with variable Type
     */
	public function toValue ()
	{
		// Execute and get params
		$params = $this->parse();

		// Return params
		return $params;
	}
}