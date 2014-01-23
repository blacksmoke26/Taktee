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
 * @version		0.1
 * @license		http://www.apache.org/licenses/LICENSE-2.0.html
 */
class TakteeUtils
{
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
}