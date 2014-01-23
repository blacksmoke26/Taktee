<?php

/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */

/**
 * Functions command Plugin for Taktee
 * Note: It is a Test plugin, 90% chances to be removed or re-coded in future.
 *
 * requires PHP version 5.3+
 *
 * About plugin:
 * > Starts with ^ or _
 * > Command `core` (predefined) or class name `test` (@see ../libraries/TestTakteeLibrary.php)
 *
 * Parameter Types:
 * null, string, bool, int, float
 *
 * Syntax:
 * <code>
 * _Type [ functionName ( ParamType ParamValue ) ];
 * </code>
 * or
 * <code>
 * ^Type [ functionName ( ParamType ParamValue ) ];
 * </code>
 *
 * Example 1: (already defined global function)
 * <code>
 * ^core [ sayHello ("Name") ];
 * </code>
 *
 * Example 2: (class method)
 * <code>
 * _test [ helloWorld () ];
 * </code>
 *
 * Example 3: (class method, passing null)
 * <code>
 * _test [ helloWorld ( null ) ];
 * </code>
 *
 * Example 4: (class static method, type casting)
 * <code>
 * _test [ increase (int "1s") ]; / will convert (string value) to (integer value)
 * </code>
 * or
 * <code>
 * _test [ increase (50) ]; / 10 + 50 = 60 output
 * </code>
 *
 * @category	Plugin
 * @package		Functions
 * @author		Junaid Atari <mj.atari@gmail.com>
 * @copyright	2014 Junaid Atari
 * @version		0.2
 * @license		http://www.apache.org/licenses/LICENSE-2.0.html
 * @see			TestTakteeLibrary
 * @see			TakteeFunction
 * @see			TakteeParameters
 * @see			TakteeParameterTypes
 */
class CommandsTFunc extends TakteeFunction
{
	/**
	 * @access protected
	 * @var array Supported commands
	 */
	protected $commandTypes = [];
	
	/**
	 * Instialize the class
	 *
	 * @access public
	 * @author Junaid Atari <mj.atari@gmail.com>
	 *
	 * @return void
	 */
	public function beforeExecute ()
	{
		parent::beforeExecute ();
		
		$this->commandTypes = [
			'^'=>'core',
			'_'=>'library',
		];
	}

	/**
	 * Parse the line code and return details for execution
	 *
	 * @access protected
	 * @author Junaid Atari <mj.atari@gmail.com>
	 *
	 * @return mixed Array | TakteeOutputNull
	 */
	protected function parse ()
	{
		parent::parse ();

		// Get commands
		$cmds = array_keys ( $this->commandTypes );

		// Append command names for PCRE pattern
		foreach ($cmds as $c)
		{
			$e.='\\'.$c.'|';
		}

		// Patterns
		$ptn = new stdClass;
		// Command Name
		$ptn->cmd = '(?<cmd>[a-zA-Z_][a-zA-Z0-9_]{1,30})\s*';
		// Library type
		$ptn->lib = '(?<lib>'.preg_replace ('/\|$/','', $e).')';
		// Function Name
		$ptn->func = '(?<function>[a-zA-Z_][a-zA-Z0-9_]{1,30})\s*';
		// Parameters
		$ptn->params = '\(\s*(.*?|(("|\')(.*)\6)\s*,?\s*|(?R))?\s*\)';

		// Found commands
		$matches = array ();

		// PCRE Pattern for command
		$pattern = '/^' . $ptn->lib . $ptn->cmd
					. '\[\s*' . $ptn->func . $ptn->params
					. '\s*\]' . '\s*'.'\s*;?$/i';

		// Match command
		if ( preg_match ( $pattern, $this->expr, $matches) > 0 )
		{
			// Remove empty elements
			$matches = array_filter ( $matches );

			// Parse Parameters code
			$parameters = new TakteeParameters ( $matches[4] );

			// Get Parameters
			$parameters = $parameters->toValue();

			// If error found, throw exception
			if ( $parameters instanceof TakteeErrorInternal )
				return $parameters;

			// Return code
			return array (
				'type' => $this->commandTypes [ $matches['lib'] ],
				'cmd' => $matches['cmd'],
				'function' => $matches['function'],
				'params' => (array) $parameters
			);
		}

		return new TakteeOutputNull ();
	}

	/**
	 * Execute the plugin
	 *
	 * @access public
	 * @author Junaid Atari <mj.atari@gmail.com>
	 *
	 * @return void
	 */
	public function execute ()
	{
		$parsed = $this->parse();
		
		// Check for output is NULLED or an error
		if ( $parsed instanceof TakteeOutputNull || $parsed instanceof TakteeErrorInternal )
		{
			$this->output = $parsed;
			return;
		}

		// Execute the command
		$this->executeCommand (
			$parsed['type'], $parsed['cmd'], $parsed['function'], $parsed['params']
		);
	}

	/**
	 * Register the required library
	 *
	 * @access protected
	 * @author Junaid Atari <mj.atari@gmail.com>
	 *
	 * @return bool True on registered | False on fail
	 */
	protected function registerLibrary ( $class )
	{
		foreach ( $this->librariesDirs as $dir )
		{
			if ( is_file ($dir.DIRECTORY_SEPARATOR.$class.'.php') )
			{
				include_once ( $dir.DIRECTORY_SEPARATOR.$class.'.php' );
				return true;
			}
		}

		return false;
	}

	/**
	 * Execute the logic, returned by TakteeFunctionCommands::parse();
	 *
	 * @access protected
	 * @author Junaid Atari <mj.atari@gmail.com>
	 *
	 * @param string $type Type of function, library or core
	 * @param string $cmd Type/Command name
	 * @param string $function Command's function name to be executed
	 * @param array $params List of function parameters
	 * @return mixed FALSE on error | string Ouput of execution
	 */
	protected function executeCommand ( $type, $cmd, $function, $params = array () )
	{
		$params = $params === null
				? array ()
				: $params;

		// Execute PHP Core function
		if ( $type === 'core' )
		{
			if ( !in_array ($cmd, array ('core')) )
				return parent::setErrorOutput ( 54, array ( '$cmd'=>$cmd ) );

			if ( !function_exists ($function) )
				return parent::setErrorOutput ( 50, array ( '$function'=>$function ) );

			$refFunc = new ReflectionFunction ( $function );

			if ( count ( $params ) < $refFunc->getNumberOfRequiredParameters () )
				return parent::setErrorOutput ( 51 );
		}

		// Execute Library
		if ( $type === 'library'  )
		{
			// Library class name
			$cmde = ucfirst ( $cmd ) . $this->config->libraries->suffix;

			if ( !class_exists($cmde, 0) )
			{
				if ( $this->registerLibrary($cmde) !== true || !class_exists($cmde, 0) )
					return parent::setErrorOutput ( 52, array ( '$cmd'=>$cmd ) );
			}

			$class = new $cmde ();

			if ( !method_exists ( $class, $function ) )
				return parent::setErrorOutput ( 50, array ( '$function'=>$function ) );

			$method = new ReflectionMethod ( $class, $function );

			if ( count ( $params ) < $method->getNumberOfRequiredParameters () )
				return parent::setErrorOutput ( 51 );
		};

		//** Start Output Buffer
		ob_start ();
		ob_implicit_flush ( false );

		// Execute function
		if ( $type === 'core'  )
			echo call_user_func_array ( $function, $params );

		// Execute method
		if ( $type === 'library'  )
			echo call_user_method_array ( $function, $class, $params );

		//** Return the rendered output.
		$output = ob_get_clean ();

		// Debug Info
		$debugInfo  = array (
			"type" => $type,
			"command" => $cmd,
			"function" => $function,
			"params" => $params,
		);

		// Set output, Line Number, Code Expression, Debug Information
		$this->output = new TakteeOutput ( $output, $this->lineNo, $this->expr, $debugInfo );

		return;
	}
}
