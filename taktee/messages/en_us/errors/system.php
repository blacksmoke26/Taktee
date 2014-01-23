<?php

/**
 * Error Messages
 * US English (en_US)
 *
 * @category	Messages
 * @package		Taktee
 * @author		Junaid Atari <mj.atari@gmail.com>
 * @copyright	2014 Junaid Atari
 * @version		0.2
 * @license		http://www.apache.org/licenses/LICENSE-2.0.html
 * @see			Taktee, TakteeFunctionCommands, TakteeLinesMap, TakteeParameterTypes
 */
 
return array (

	/*
	- Example:
		$ERROR_CODE => array (
			'msg'=>'$ERROR_MESSAGE',
			'category'=>'$ERROR_CATEGORY',
		),
	*/
	
	10 => array (
		'msg' => 'invalid language given',
		'category' => 'Language error'
	),
	11 => array (
		'msg' => 'invalid language source file',
		'category' => 'Language error'
	),
	26 => array (
		'msg' => 'undefined expression',
		'category' => 'Runtime error',
	),
	27 => array (
		'msg' => 'missing ; at end of the line',
		'category' => 'Syntax error',
	),
	33 => array (
		'msg' => 'tag never closed properly',
		'category' => 'Syntax error',
	),
	50 => array (
		'msg' => 'undefined function `$function`',
		'category' => 'Runtime error',
	),
	51 => array (
		'msg' => 'required parameters are missing',
		'category' => 'Runtime error',
	),
	52 => array (
		'msg' => 'undefined library `$cmd`',
		'category' => 'Runtime error',
	),
	53 => array (
		'msg' => 'undefined method `$function`',
		'category' => 'Runtime error',
	),
	54 => array (
		'msg' => 'undefined core library `$cmd`',
		'category' => 'Runtime error',
	),
	55 => array (
		'msg' => 'file `$file` not found',
		'category' => 'File error',
	),
	56 => array (
		'msg' => 'file `$file` unreadable',
		'category' => 'File error',
	),
	57 => array (
		'msg' => 'undefined parameter type `$type`',
		'category' => 'Runtime error',
	),
	58 => array (
		'msg' => 'invalid parameter',
		'category' => 'Runtime error',
	),
	59 => array (
		'msg' => 'parameter type specified but value is missing',
		'category' => 'Runtime error',
	),
);