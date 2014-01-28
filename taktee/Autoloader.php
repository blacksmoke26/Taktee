<?php

defined ('DS') | define ( 'DS', DIRECTORY_SEPARATOR );

/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */

/**
 * Taktee Classes Autoloader
 *
 * requires PHP version 5.3+
 *
 * @category	Base
 * @package		None
 * @author		Junaid Atari <mj.atari@gmail.com>
 * @copyright	2014 Junaid Atari
 * @version		0.1
 * @license		http://www.apache.org/licenses/LICENSE-2.0.html
 */
class Taktee_Autoloader
{
	/**
	 * Registers Taktee_Autoloader as an SPL autoloader.
	 *
	 * @static
	 * @return void
	 */
    static public function register ()
    {
        ini_set ( 'unserialize_callback_func', 'spl_autoload_call' );
        spl_autoload_register (  array ( __CLASS__, 'autoload' ) );
    }

	/**
	 * Handles autoloading of classes.
	 *
     * @static
	 * @param string $class A class name.
	 * @return void
     */
    static public function autoload ( $class )
    {
		$coreClasses =  array (
			'TakteeBase' => 'base/Base.php',
			'TakteeUtils' => 'base/Utils.php',
			'Taktee' => 'Taktee.php',
			'TakteeComments' => 'base/Comments.php',
			'TakteeErrorInternal' => 'error/ErrorInternal.php',
			'TakteeError' => 'error/Error.php',
			'TakteeParameters' => 'parameters/Parameters.php',
			'TakteeParameterTypes' => 'parameters/ParameterTypes.php',
			'TakteeLibrary' => 'helpers/Library.php',
			'TakteeFunction' => 'helpers/Function.php',
			'TakteeCodeLine' => 'lines/CodeLine.php',
			'TakteeLinesMap' => 'lines/LinesMap.php',
			'TakteeOutput' => 'output/Output.php',
			'TakteeOutputNull' => 'output/OutputNull.php',
			'TakteeOutputDebug' => 'output/OutputDebug.php',
			'TakteeOuputBeautifier' => 'output/OuputBeautifier.php',
		);

		if ( $coreClasses[$class] )
		{
			$file = dirname ( __FILE__ ) . DIRECTORY_SEPARATOR . $coreClasses[$class];

			if ( is_file ( $file ) )
				include_once ( $file );
		}
    }
}