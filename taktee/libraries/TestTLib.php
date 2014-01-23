<?php

/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */

/**
 * Test functions library used with Functions TakteeFunctionCommands
 *
 * requires PHP version 5.3+
 *
 * @category	Plugin
 * @package		Libraries
 * @author		Junaid Atari <mj.atari@gmail.com>
 * @copyright	2014 Junaid Atari
 * @version		0.1
 * @license		http://www.apache.org/licenses/LICENSE-2.0.html
 * @see			TakteeFunctionCommands
 */
class TestTLib extends TakteeLibrary
{
	/**
	 * Just a hello word function to test the command
	 *
	 * @param mixed $number Number to be add
	 * @return string
	 */
	public function helloWorld ()
	{
		echo 'Welcome to Taktee World!';
	}

	/**
	 * Just add # number in 50
	 *
	 * @static
	 * @param int $number Number to be add
	 * @return string
	 */
	public static function increase ( $number )
	{
		echo "10 + " . $number . ' = ' . ( 10 + $number ) ;
	}
}