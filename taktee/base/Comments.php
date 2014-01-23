<?php

/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */

/**
 * Escape Comment(s) from line
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
class TakteeComments
{	
	/**
	 * Remove inline comments
	 * 
	 * @static
	 * @access public
	 * @author Junaid Atari <mj.atari@gmail.com>
	 *
	 * @param string $str Any text
	 * @return string
	 */
	public static function clearInline ( $str )
	{
		$has = preg_match ( '/(?<!\"|\')(\/\/.*)$/', $str, $matches );
		
		if ( !$has )
			return $str;
		
		return trim (
			str_replace ( $matches[0], '', $str )
		);
	}
	
	/**
	 * Remove inline comments
	 * 
	 * @static
	 * @access public
	 * @author Junaid Atari <mj.atari@gmail.com>
	 *
	 * @param string $str Any text
	 * @return string
	 */
	public static function clearLine ( $str )
	{
		$has = preg_match ( '/^\/\/(.*)$/', $str, $matches );
		
		if ( !$has )
			return $str;
		
		return "";
	}
	
	/**
	 * Remove line/inline comment from given string
	 *
	 * @static
	 * @access public
	 * @author Junaid Atari <mj.atari@gmail.com>
	 *
	 * @param string $str Any text
	 * @return string
	 */
	public static function clear ( $str )
	{
		$line = self::clearLine ( trim ( $str ) );
		
		if ( $line == '' )
			return '';
		
		return self::clearInline ( $line );
	}	
}

