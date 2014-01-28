<?php

/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */

/**
 * Taktee base functions/utilties class
 *
 * requires PHP version 5.3+
 *
 * @category	Base
 * @package		Taktee
 * @author		Junaid Atari <mj.atari@gmail.com>
 * @copyright	2014 Junaid Atari
 * @version		0.2
 * @license		http://www.apache.org/licenses/LICENSE-2.0.html
 * @since		0.1
 */
class TakteeUtils
{
	/**
	 * Remove numeric indexes and empty elements from Array
	 *
     * @static
	 * @access public
	 * @author Junaid Atari <mj.atari@gmail.com>
	 *
	 * @param array $ary Array to check
	 * @return void
	 */
	public static function removeNumericIndexes ( array &$ary )
	{
		$ary = array_filter ( $ary );
		
		foreach ( $ary as $key => $value )
		{
			if ( is_numeric ( $key ) )
				unset ( $ary[$key] );
		}
	}
	
	/**
     * Execute the function and return output
     *
     * @static
     * @access public
	 * @author Junaid Atari <mj.atari@gmail.com>
	 *
     * @version 1.0
	 * @param string $name Function name
	 * @param array $params Paramaters Array
     * @return mixed
     */
	public static function getOuputByFunction ( $name, array $params = array () )
	{
		return call_user_func_array ( $name, $params );
	}
	
	/**
     * Execute the object's method and return output
     *
     * @static
     * @access public
	 * @author Junaid Atari <mj.atari@gmail.com>
	 *
     * @version 1.0
     * @param object $obj Class object
	 * @param string $name Method name
	 * @param array $params Paramaters Array
	 * @param array $options Properties for the constructure
     * @return mixed
     */
	public static function getOutputByMethod ( $class, $name, $paramsRaw = '', array $options = array () )
	{
		try
		{ 
			$method = new ReflectionMethod ( $class, $name );
		}		
		catch ( ReflectionException $e )
		{
			return new TakteeErrorInternal ( 61 );
		}
		
		if ( !$method->isPublic() )
			return new TakteeErrorInternal ( 60, array ('$method', $name) );
		
		$paramaters = array ();
		
		$paramaters = new TakteeParameters ( $paramsRaw );
		$paramaters = $paramaters->toValue();
		
		unset ( $paramsParser );
		
		if ( count ( $paramaters ) < $method->getNumberOfRequiredParameters () )
			return new TakteeErrorInternal ( 51 );
		
		$object = new $method->class( $options );
		
		return call_user_method_array ( $name, $object, $paramaters );
	}
	
	/**
     * Convert an array keys and values to array notation object (Recursive)
     *
     * @static
     * @access public
     * @author Richard Castera
	 * @author Junaid Atari <mj.atari@gmail.com>
	 *
     * @version 1.0
     * @param array $array Array to make objects
	 * @param bool $lowerCase Convert keys case to lower.
     * @return object
     */
    public static function arrayToObject ( $array, $lowerCase = false )
    {
		//** Return is none array value given.
        if ( !is_array ( $array ) || !count ( $array ) )
			return $array;

        $object = new stdClass ();
				
		foreach ( $array as $name => $value )
		{
			$name = trim ( $name );
			
			//** Convert keys to lower upon request.
			$name = $lowerCase
				? strtolower ( $name )
				: $name;
			
			if ( !empty ( $name ) )
				# recursive
				$object->$name = self::arrayToObject ( $value, $lowerCase );
		}

		return $object;
    }

	/**
	 * Register the class file
	 *
	 * @static
     * @access public
	 * @author Junaid Atari <mj.atari@gmail.com>
	 *
	 * @param string $classPath Path to php class file
	 * @return bool True on registered | False on fail
	 */
	public static function registerClassByFile ( $classPath )
	{
		// Check for PHP extension
		if ( strpos ( $classPath, '.php', strlen ($classPath)-4 ) === false )
			$classPath .= '.php';
		
		// Return if no file or not readable
		if ( !is_file( $classPath) || !is_readable ($classPath) )
			return false;
		
		// Get the file name
		$class = pathinfo ( $classPath, PATHINFO_FILENAME );		
		
		// Include class file
		include_once ( $classPath );
		
		// Check if class loaded.
		if ( !class_exists ( $class, false ) )
			return false;
		
		return true;
	}

	/**
     * Replace array keys with value from string
     *
	 * @access public
	 * @author Junaid Atari <mj.atari@gmail.com>
	 *
	 * @param string $str Text to replace
	 * @param array $vars Array variables
	 * @return string
	 */
	public static function replaceVariables ( $str, array $vars )
	{
		return str_replace (
			array_keys ( $vars ),
			array_values ( $vars ),
			$str
		);
	}
}