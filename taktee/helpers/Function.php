<?php

/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */

/**
 * Taktee Basic structure plugin class for writting additional functions for the Taktee code
 * This is just template class, use it by extending
 *
 * requires PHP version 5.3+
 *
 * @category	Helper
 * @package		Taktee
 * @author		Junaid Atari <mj.atari@gmail.com>
 * @copyright	2014 Junaid Atari
 * @version		0.1
 * @license		http://www.apache.org/licenses/LICENSE-2.0.html
 * @see			TakteeFunctionCommands
 */
class TakteeFunction
{
	/**
	 *@var int Line number
	 */
	protected $lineNo = 0;

	/**
	 * @access protected
     * @var array Configuration data
     */
	protected $config = [];

	/**
	 *@var string Code expression
	 */
	protected $expr = '';

	/**
	 *@var int Session ID of line
	 */
	protected $sessionId = '';

	/**
	 *@var mixed Generated output
	 */
	protected $output = null;

	/**
	 *@var mixed Taktee base generated output oubjects
	 */
	protected $baseOutput;

	/**
	 *@var string Current executing code script
	 */
	protected $scriptPath;
	
	/**
	 * @access protected
	 * @var string Base path of Taktee
	 */
	protected $basePath;
	
	/**
	 * @access protected
	 * @var string Messages language
	 */
	protected $language;
	
	/**
	 * @access protected
	 * @var array variables cache
	 */
	public $variables = array ();
	
	/**
	 * @access protected
	 * @var array Additonal libraries for plugin
	 */
	protected $librariesDirs = array ();
	
	/**
	 * @access protected
	 * @var array Additonal functions for plugin
	 */
	protected $functionsDirs = array ();

	/**
	 * Constructor function
	 *
	 * @access public
	 * @author Junaid Atari <mj.atari@gmail.com>
	 *
	 * @param integer $lineNumber Line number
	 * @param string $expression Line code
	 * @return void
	 */
	public function __construct ( $lineNumber, $expression, array $variables )
	{
		$this->lineNo = $lineNumber;
		$this->expr = $expression;
		
		foreach ( $variables as $name => $value )
		{
			$this->$name = $value;
		}
	}
	
	/**
	 * Get the base output
	 * Note: Only use for Taktee base classes
	 *
	 * @access public
	 * @author Junaid Atari <mj.atari@gmail.com>
	 *
	 * @return array
	 */
	final public function getBaseOutputObject ()
	{
		return $this->baseOutput;
	}

	/**
	 * Throw the Taktee error exception
	 *
	 * @see TakteeError
	 * @see TakteeErrorInternal
	 *
	 * @access protected
	 * @author Junaid Atari <mj.atari@gmail.com>
	 *
	 * @param int $no Error ID/Number
	 * @param array $vars Variables to replace from error message
	 * @return bool False
	 */
	protected function setErrorOutput ( $no, $vars = array () )
	{
		$this->output = new TakteeErrorInternal ( $no, $vars );
		return false;
	}
	
	/**
	 * Throw the custom Taktee error exception
	 *
	 * @see TakteeError
	 * @see TakteeErrorInternal
	 *
	 * @access protected
	 * @author Junaid Atari <mj.atari@gmail.com>
	 *
	 * @param int $no Error ID/Number
	 * @param string $category Error Category
	 * @param string $message Error Message
	 * @param array $vars Variables to replace from error message
	 * @return bool False
	 */
	protected function setCustomErrorOutput ( $no, $category, $message, $vars = array () )
	{
		$error = new TakteeErrorInternal ( $no, $vars );
		
		$error->setKey ( 'category', $category );
		$error->setKey ( 'msg', $message );
		
		$this->output = $error;
		
		return false;
	}

	/**
	 * Parce expression/line code
	 * Note: Method should be overrided
	 *
	 * @access protected
	 * @author Junaid Atari <mj.atari@gmail.com>
	 *
	 * @return void
	 */
	protected function parse () {}

	/**
	 * Event function: Execute this function after main Taktee execution
	 *
	 * @access public
	 * @author Junaid Atari <mj.atari@gmail.com>
	 *
	 * @return void
	 */
	public function beforeExecute () {}

	/**
	 * Execute the parsed expression's logic
	 *
	 * @access public
	 * @author Junaid Atari <mj.atari@gmail.com>
	 *
	 * @return void
	 */
	public function execute () {}

	/**
	 * Event function: Execute this function after main Taktee execution
	 *
	 * @access public
	 * @author Junaid Atari <mj.atari@gmail.com>
	 * @see TakteeOutput
	 * @see TakteeOutputNull
	 *
	 * @param object $outputObj Output object
	 * @return object TakteeOutput | TakteeOutputNull
	 */
	public function afterExecute ( $outputObj )
	{
		return $outputObj;
	}

	/**
	 * Get the output value of Functions plugin
	 *
	 * @see TakteeFunction::init ()
	 *
	 * @access public
	 * @author Junaid Atari <mj.atari@gmail.com>
	 *
	 * @return mixed
	 */
	public function getValue ()
	{
		return $this->output;
	}
}
