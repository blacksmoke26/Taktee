<?php

/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */

/**
 * Taktee Error Handler, details what happend when error, full debug information
 *
 * requires PHP version 5.3+
 *
 * @category	Ouput
 * @package		Taktee
 * @author		Junaid Atari <mj.atari@gmail.com>
 * @copyright	2014 Junaid Atari
 * @version		0.1
 * @license		http://www.apache.org/licenses/LICENSE-2.0.html
 * @see			TakteeOuput
 */
class TakteeOuputBeautifier
{
	/**
	 * Invalid Ouput object
	 */
	const OUPUT_TYPE_INVALID = 55;

	/**
	 * TakteeOuput object
	 */
	const OUPUT_TYPE_ELEMENT = 56;

	/**
	 * Group of TakteeOuput objects
	 */
	const OUPUT_TYPE_GROUP = 57;

	/**
	 * Complete Taktee output
	 */
	const OUPUT_TYPE_FULL = 58;

	/**
	 * Valid the given output Object or Objects Array
	 *
	 * @static
	 * @access public
	 * @author Junaid Atari <mj.atari@gmail.com>
	 *
	 * @param mixed $output TakteeOutput Object | Array of TakteeOutput objects
	 * @return int Output type
	 */
	public static function getOutputType ( $output )
	{
		// Only one code expression output
		if ( $output instanceof TakteeOutput )
			return self::OUPUT_TYPE_ELEMENT;

		// Not array or empty, NULL
		if ( !is_array ( $output ) || !count ( $output ) )
			return self::OUPUT_TYPE_INVALID;

		// If first item is TakteeOutput then its the section's lines output
		if ( isset ($output[0]) && $output[0] instanceof TakteeOutput )
		{
			// Loop the section's TakteeOutput objects
			foreach ( $output[0] as $line )
			{
				// If not valid object, return
				if ( !$line instanceof TakteeOutput )
					return self::OUPUT_TYPE_INVALID;
			}

			// Valid objects group
			return self::OUPUT_TYPE_GROUP;
		}

		// Validate the whole bunch of TakteeOutput objects
		foreach ( $output as $section )
		{
			// Make sure that section is not empty
			if ( count ( $section ) )
			{
				// Loop the section's TakteeOutput objects
				foreach ( $section as $line )
				{
					// If not valid object, return
					if ( !$line instanceof TakteeOutput )
						return self::OUPUT_TYPE_INVALID;
				}
			}
		}

		// Valid full object
		return self::OUPUT_TYPE_FULL;
	}

	/**
	 * Beautify the output as text
	 *
	 * @static
	 * @access public
	 * @author Junaid Atari <mj.atari@gmail.com>
	 *
	 * @param mixed $output Taktee Output | Group of TakteeOutput objects | Complete output object
	 * @param string $glue Append at the edit of line
	 * @return string Text of output
	 */
	public static function asText ( $output, $glue = "\n" )
	{
		// Code lines
		$lines = array ();

		// Validate the Output object
		$outputType = self::getOutputType ( $output );

		// Invalid object, return
		if ( $outputType === self::OUPUT_TYPE_INVALID )
			return '';

		// TakteeOuput Element, return it's value
		if ( $outputType === self::OUPUT_TYPE_ELEMENT )
			return $output->getValue();

		// TakteeOuput Elements Group
		if ( $outputType === self::OUPUT_TYPE_GROUP )
		{
			// Loop the lines
			foreach ( $output as $opt )
			{
				// Add to text lines
				$lines[] = $opt->getValue();
			}

			// Join the ouput text lines with $glue and return.
			return implode ( $glue, $lines );
		}

		// Loop the Sections
		foreach ( $output as $section )
		{
			// Section must have element
			if ( count ( $section ) )
			{
				// Loop the lines
				foreach ( $section as $line )
				{
					// Add to text lines
					$lines[] = $line->getValue();
				}
			}
		}

		// Join the ouput text lines with $glue and return.
		return implode ( $glue, $lines );
	}
}