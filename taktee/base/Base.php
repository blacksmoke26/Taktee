<?php

/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */

# Debug Mode
//define ( 'TAKTEE_DUBUG_OUTPUT', true );

# Add executed code expression in Output
//define ( 'TAKTEE_DEBUG_CODE_OUTPUT', true );

/**
 * Taktee Base
 *
 * requires PHP version 5.3+
 *
 * @category	Base
 * @package		Taktee
 * @author		Junaid Atari <mj.atari@gmail.com>
 * @copyright	2014 Junaid Atari
 * @version		0.2
 * @license		http://www.apache.org/licenses/LICENSE-2.0.html
 */
class TakteeBase
{
	/**
	 * @access protected
     * @var int Last error code (number)
     */
	protected $lastErrorNo = null;

	/**
	 * @access protected
     * @var array Configuration data
     */
	protected $config = array ();

	/**
	 * @access protected
     * @var string Last error message
     */
	protected $lastErrorMsg = null;

	/**
	 * @access protected
     * @var string Error message category
     */
	protected $lastErrorMsgCategory = null;

	/**
	 * @access protected
     * @var int Last error line number ( occured on # line )
     */
	protected $lastErrorLine = null;

	/**
	 * @access protected
     * @var int Current line number of execution
     */
	protected $currentLine = 0;

	/**
	 * @access protected
     * @var array Directories where Taktee libraries located.
     */
	protected $librariesDirs = array ();

	/**
	 * @access protected
     * @var array Directories where Taktee functions located.
     */
	protected $functionsDirs = array ();

	/**
	 * @access protected
     * @var string Last execute file full path
     */
	protected $lastFilePath = null;

	/**
	 * @access protected
     * @var string Source of last executed line
     */
	protected $lastParseCode = null;

	/**
	 * @access protected
     * @var array Error messages
     */
	protected $errMessages = array ();

	/**
	 * @access protected
     * @var string Source Language ISO of Messages (--_--)
     */
	protected $language = 'en_us';
	
	/**
	 * @access protected
	 * @var mixed Output of Taktee
	 */
	protected $output = null;	
	
	/**
	 * @access protected
	 * @var array Variables cache for Functions
	 */
	protected $variables = array ();
	
	/**
	 * Constructor function
	 *
	 * @access public
	 * @author Junaid Atari <mj.atari@gmail.com>
	 *
	 * @param string $code Code to execute
	 * @return void
	 */
	public function __construct ( $code = null )
	{
		// Add core libraries directory
		$this->librariesDirs[] = $this->getPath() . DIRECTORY_SEPARATOR . 'libraries';

		// Add core functions directory
		$this->functionsDirs[] = $this->getPath() . DIRECTORY_SEPARATOR . 'functions';

		// Check for the error file
		if ( !file ( $this->getPath() . '/messages/'.$this->language.'/errors/system.php' ) )
			exit ( 'Taktee Error: messages file missing' );
			
		// Check configurations file
		if ( !file ( $this->getPath() . '/config/main.php' ) )
			exit ( 'Taktee Error: configurations file missing' );
		
		// Include config file, covert array to object
		$this->config = TakteeUtils::arrayToObject (
			include ( $this->getPath() . '/config/main.php' )
		);
		
		// Execute the code if given
		if ( $code !== null )
			$this->execute ( $code );
	}

	/**
     * Add an additional directory of Taktee libraries
     *
	 * @access public
	 * @author Junaid Atari <mj.atari@gmail.com>
	 *
	 * @param string $path Path to directory
	 * @return void
	 */
	public function addLibrariesDir ( $path )
	{
		if ( is_dir ( $path ) )
			$this->librariesDirs[] = $path;
	}

	/**
     * Add an additional directory of Taktee functions
     *
	 * @access public
	 * @author Junaid Atari <mj.atari@gmail.com>
	 *
	 * @param string $path Path to directory
	 * @return void
	 */
	public function addFunctionsDir ( $path )
	{
		if ( is_dir ( $path ) )
			$this->functionsDirs[] = $path;
	}

	/**
     * Parse the given string
     *
	 * @access protected
	 * @author Junaid Atari <mj.atari@gmail.com>
	 *
	 * @param string $code Taktee code | any of text
	 * @return mixed Null on error | object TakteeLinesMap
	 */
	protected function parseString ( $code )
	{
		$code = trim ( $code );
		
		if ( !$code )
			return null;
		
		// Split lines by all combinations of \r and \n
		$lines = preg_split ( '/((\r(?!\n))|((?<!\r)\n)|(\r\n))/', $code );

		// if no line found, return
		if ( !count ($lines) )
			return null;

		// Opening Tag off by default
		$braces = false;

		// Data of the line
		$data = '';

		// Lines for remapping and executions (none empty, none commented lines)
		$codelines = array ();

		// Total Opening tags
		$i = -1;

		// Total Closing tags
		$j = -1;

		// Current line of given $code
		$cline = 0;

		// The line of opened tag
		$line = false;

		// Taktee Language ~ Opening tag (PCRE escaped)
		$startTag = '@\{';

		// Taktee Language ~ Ending tag (PCRE escaped)
		$endTag = '\}';

		// End of Ending tag
		$endEndTag = false;

		// Loop the lines array
		foreach ( $lines as $l )
		{
			// Current line counter
			$cline++;

			// Clean the whitespace from beginning and at end.
			$l = trim ( $l );
			
			// Skipping: line is empty
			if ( !$l )
			{
				continue;
			}			

			// Set the last line code for the error
			$this->lastErrorLine = $l;

			// Opening of language tag, Don't capture between quotes.
			if ( preg_match ( "#(?<!\"|\')$startTag#", $l ) )
			{
				// Add count opened tag
				$i++;

				// This is the opening tag line
				$line = true;

				// Line have opening tag
				$braces = true;
			}

			// Opening tag opened, now match for the closing tag, never look if between quotes or escaped with backslash (\)
			if ( $braces && preg_match ( '/((?<!"|\')[^'.$endTag.']*(?:\\\\.[^'.$endTag.']*)*).*'.$endTag.'$/i', $l ) )
			{
				// This line have valid closing tag, previous opened tag closed
				$endEndTag = true;

				// Now increase closed tags counter.
				$j++;

				// Validating the opening and closing tags are same
				// This will match with the previous opened tag
				if ( $j !== $i )
				{
					// Set this line code for the error
					$this->lastParseCode = $l;

					// Set error of none closed detected
					return $this->setError ( 33, -1, $cline );
				}

				// Close the previous opened tag
				$braces = false;
			}
			
			// If Opening tag is opened and not closed
			if ( $braces && !$endEndTag )
			{
				// If this line is the Opened Tag line.
				$data = $line
					// Remove the Opened tag string from beginning
					// Maybe this have statment, @{ ... } , it will check for that ...
					? preg_replace ( "/^(?<!\"|\')$startTag/", '', $l )
					// Set as ummapped line text
					: $l;
				
				// Remove comments
				$data = TakteeComments::clear ( $data );
				
				// $data is empty (will never treat the 0 as false), return
				if ( $data == '' )
					continue;			

				// Check for end fo statment (Semi colon missing at end)
				if ( !preg_match ('/;$/', $data) )
				{	
					// Set the parced line text
					$this->lastParseCode = $data;
	
					// Throw error and return
					return $this->setError ( 27, -1, $cline );
				}
				
				// Add line for the mapping.
				$codelines[$i+1] .= $cline . ':' . $data;
			}

			// Clear if this is the Opened tag's line
			$line = false;

			// Session ended, Tag closed of last opened tag
			$endEndTag = false;
		}

		// If any error in memory, return
		if ( $this->hasError() )
			return null;
		
		// Now check and Remap lines for multi statments (end right after ;) on each line.
		return new TakteeLinesMap ( $codelines );
	}

	/**
     * Parse the given string, mapped lines and execute.
     *
	 * @access protected
	 * @author Junaid Atari <mj.atari@gmail.com>
	 *
	 * @param string $code Taktee code | any of text
	 * @return mixed Null on error | Array of object TakteeOutput
	 */
	protected function renderOutput ( $code )
	{
		// Output of TakteeLinesMap ( array $unMappedLines )
		$mappedLines = $this->parseString ( $code );

		// Any last error found, return
		if ( $mappedLines === null || $this->hasError() )
			return null;

		// Error in Mapped Lines Output
		if ( $mappedLines->asArray() instanceof TakteeErrorInternal )
		{
			// Get Error object
			$err = $mappedLines->asArray();
			// Set the code where error happened
			$this->lastParseCode = $err->getKey ('code');
			// Throw error, return null
			return $this->setErrorInternal ( $mappedLines->asArray(), $line->getLine('line') );
		}

		// Any previous error found, return
		if ( $this->hasError() )
			return null;

		// Valid the it's the valid TakteeLinesMap object
		if ( $mappedLines instanceof TakteeLinesMap )
		{
			// No statments found for execution
			if ( !$mappedLines->count ( TakteeLinesMap::COUNT_LINES ) )
				return null;

			// List of Mapped lines to be executed.
			$mappedLines = $mappedLines->asArray ();
		}

		// Execute lines and return output
		return $this->execMappedLines ( $mappedLines );
	}

	/**
     * Execute the mapped array of TakteeLinesMap
     *
	 * @access protected
	 * @author Junaid Atari <mj.atari@gmail.com>
	 *
	 * @param array $mappedLines Mapped lines of TakteeLinesMap
	 * @return mixed Null on error | Array of object TakteeOutput
	 */
	protected function execMappedLines ( array $mappedLines )
	{
		// Skip if error found
		if ( $this->hasError() )
			return null;

		// Output of commands
		$output = array ();

		// Session ID
		$sessionId = 0;

		// Loop Sessions
		foreach ( $mappedLines as $session )
		{
			// Default output
			$output[$sessionId] = null;

			// if sessions have lines, execute it
			if ( count ( $session ) )
			{
				// Loop Code lines //
				foreach ( $session as $line )
				{
					// Execute statment ( $line instanceof TakteeCodeLine )
					$statOutput = $this->execLineStatment ( $line->getLine (), $line->getCode(), $sessionId );

					// Skip if error found
					if ( $this->hasError() )
						return null;

					// Check if error found in output
					if ( $statOutput instanceof TakteeErrorInternal )
						return $this->setErrorInternal ( $statOutput, $line->getLine() );

					// Add to session
					$output[$sessionId][] = $statOutput;
					
					$this->output[$sessionId][] = $statOutput;
				}
			}

			// Increase session ID
			$sessionId++;
		}

		// Return proper output with session order
		return $output;
	}
	
	/**
	 * Path of the Taktee main directory
	 *
	 * @access public
	 * @author Junaid Atari <mj.atari@gmail.com>
	 *
	 * @return string Directory's path of Taktee
	 */
	public function getPath ()
	{
		return dirname ( dirname ( __FILE__ ) );
	}

	/**
     * Execute the code expression
     *
	 * @access protected
	 * @author Junaid Atari <mj.atari@gmail.com>
	 *
	 * @param int $line Number of code line
	 * @param string $expr Code Expression to execute
	 * @return mixed Null on error | object TakteeOutput
	 */
	protected function execLineStatment ( $line, $expr, $sessionId )
	{
		// Skip if error found
		if ( $this->hasError() )
			return null;

		// Set current code
		$this->lastParseCode = $expr;

		// Taktee Functions
		$functionFiles = glob (
			$this->getPath () . DIRECTORY_SEPARATOR . 'functions' . DIRECTORY_SEPARATOR . '*'.$this->config->functions->suffix.'.php'
		);

		// Count if files found.
		if ( count ( $functionFiles ) )
		{
			// Execute file 1 by 1
			foreach ( $functionFiles as $ffine )
			{
				// Get file details
				$details = pathinfo ( $ffine );

				// Get class name (Same as file name)
				$className = $details['filename'];

				// Include the functions library
				include_once ( $ffine );

				// Validate that the included file have that class, autoload off, in case for raising error
				if ( class_exists ( $className, 0 ) )
				{
					// Execute expression
					$output = new $className ( $line, $expr, $this->getPostVariables ( array (
						'sessionId'=>$sessionId,
						'baseOutput' =>$this->output,
						'variables' => $this->variables,
						'config' => $this->config,
					)));
					
					// Valid if the instance of that class
					if ( $output instanceof $className )
					{
						// Execute event
						$output->beforeExecute ();
						
						// Execute function
						$output->execute ();
					
						// Collect main output
						$this->output = $output->getBaseOutputObject ();
						
						// Collect function's output
						$executedOutput = $output->getValue();
						
						// Execute event
						$executedOutput = $output->afterExecute ( $executedOutput );
						
						// Merge functions variables cache
						$this->variables = array_merge_recursive (
							$this->variables, $output->variables
						);
						
						// Check if output not NULL
						if ( !$executedOutput instanceof TakteeOutputNull )
							// Return output
							return $executedOutput;
					}
				}
			}
		}

		// Raise error of Undefined expression.
		return $this->setError ( 26, -1, $line );
	}
	
	/**
     * Variables that post to Functions of Taktee
     *
	 * @access protected
	 * @author Junaid Atari <mj.atari@gmail.com>
	 *
	 * @param int $line Number of code line
	 * @param string $expr Code Expression to execute
	 * @return mixed Null on error | object TakteeOutput
	 */
	protected function getPostVariables ( array $options = array () )
	{
		return array_merge_recursive (
			array (
				'librariesDirs' => $this->librariesDirs,
				'functionsDirs' => $this->functionsDirs,
				'language' => $this->language,
				'basePath' => $this->getPath(),
				'scriptPath' => (
					// If no file, give the parent file path else last execution file
					$this->lastFilePath === null
						? $_SERVER["SCRIPT_FILENAME"]
						: $this->lastFilePath
				),
			),
			$options
		);
	}

	/**
	 * Get the error details by number
	 *
	 * @param int $id Error ID
	 * @param array $vars List of variables to replace from error message
	 * @return mixed string Message | array Message with details
	 */
	protected function errorMessage ( $id, $vars = array () )
	{
		if ( !is_array ( $vars ) )
			$vars = array ();

		if ( !count ( $this->errMessages ) )
		{
			$file = $this->getPath() . '/messages/'.$this->language.'/errors/system.php';

			if ( !is_file ( $file ) )
				$this->setError ( 11 );

			// Include the error messages file
			$this->errMessages = include_once ( $file );
		}
		
		// System error messages list
		$list = $this->errMessages;
		
		if ( isset ($list[$id]) )
		{
			$list[$id]['msg'] = TakteeUtils::replaceVariables ( $list[$id]['msg'], $vars );
			
			return $list[$id];
		}
		
		// Message not found
		return null;
	}
	
	/**
     * Set the custom error of Functions
     *
	 * @access public
	 * @author Junaid Atari <mj.atari@gmail.com>
	 *
	 * @param TakteeErrorInternal $err Error object
	 * @param int $line Line number
	 * @return null
	 */
	protected function setErrorInternal ( TakteeErrorInternal $err, $line )
	{
		$options = array (
			'msg' => $err->getKey ('msg'),
			'category' => $err->getKey ('category'),
		);
		
		return $this->setError ( $err->id, $err->vars, $line, $options );
	}

	/**
     * Throw an error and break execution
     *
	 * @access protected
	 * @author Junaid Atari <mj.atari@gmail.com>
	 *
	 * @param int $id Code/Number of Error
	 * @param mixed $vars Array of variables for replacement ($key=>$val) | -1 for Skip
	 * @param mixed $line int Line Number | -1 for auto pick current line | default = NULL
	 * @return null
	 */
	protected function setError ( $id, $vars = array (), $line = null, array $options = array () )
	{		
		// If vars skipped, none array given, convert to array
		if ( !is_array ( $vars ) )
			$vars = array ();
		
		// Set custom error category and message
		if ( count ( $options )
			&& isset ( $options['msg'] )
			&& trim ( $options['msg'] )
			&& isset ( $options['category'] )
			&& trim ( $options['category'] ) )
		{		
			$msg = array (
				'msg' => TakteeUtils::replaceVariables ( $options['msg'], $vars ),
				'category' => $options['category'],
			);
		}
		else
		{
			// Get the system error message by Code
			$msg = $this->errorMessage ( $id, $vars );
		}

		// Check for asking current line, then set
		if ( $line === -1 )
			$line = $this->currentLine;

		// If message not empty
		if ( is_array ( $msg ) )
		{
			// Set the message
			$this->lastErrorMsg = $msg['msg'];

			// Set the message category, if exists
			$this->lastErrorMsgCategory = $msg['category'];

			// Set error code
			$this->lastErrorNo = $id;

			// Set line number where error occurred
			$this->lastErrorLine = $line;
		}

		// Nothing
		return null;
	}
}