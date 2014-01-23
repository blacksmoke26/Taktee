<?php

/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */

/**
 * Taktee Code statments finder, filter lines/statments for exections
 *
 * requires PHP version 5.3+
 *
 * @category	Base
 * @package		Taktee
 * @author		Junaid Atari <mj.atari@gmail.com>
 * @copyright	2014 Junaid Atari
 * @version		0.1
 * @license		http://www.apache.org/licenses/LICENSE-2.0.html
 * @see			Taktee, TakteeCodeLine
 */
class TakteeLinesMap
{
	const COUNT_SESSIONS = 10;
	const COUNT_LINES = 11;
	
	/**
	 * @access protected
     * @var array Mapped line
     */
	protected $mappedLines = array ();
	
	/**
	 * Constructor function
	 *
	 * @access public
	 * @author Junaid Atari <mj.atari@gmail.com>
	 *
	 * @param array $unMappedlines Lines list of un parsed lines, just uncommented/empty lines
	 * @return void
	 */
	public function __construct ( array $unMappedlines )
	{
		$this->mappedLines = $this->reMap ( $unMappedlines );
	}
	
	/**
	 * Parse line for multi code statments per line, remove empty, verfiy for execution
	 *
	 * @access public
	 * @author Junaid Atari <mj.atari@gmail.com>
	 *
	 * @param array $unMappedlines Lines list of un parsed lines, just uncommented/empty lines
	 * @return array Multidimensional array of TakteeCodeLine object
	 */
	protected function reMap ( array $unMappedlines )
	{
		// No line found
		if ( !count ( $unMappedlines ) )
			return $unMappedlines;
		
		// List of code lines splitted by Semi colon (;)
		$codelines = array ();
		
		// Tag session id, count by opened Tag
		$j = 0;
		
		foreach ( $unMappedlines as $rcmd )
		{
			// Parsed lines list
			$cmdsParse = array();
			
			// Split code lines by semi color, skip if between quotes
			preg_match_all ( '/"(?:\\\\.|[^\\\\"])*"|\'(?:\\\\.|[^\\\\\'])*\'|[^;]*;/', $rcmd, $cmdsParse );
			
			// Get descired secion
			$cmdsParse=$cmdsParse[0];
			
			// List of valid code lines with line numbers
			$list = array ();
			
			// Add line numbers properly
			for ($i = 0; $i < count($cmdsParse); $i++)
			{
				// Even line number (divide on 2)
				if ($i % 2 == 0)
					$list[].=trim($cmdsParse[$i]);
				// Odd line number
				else
					$list[].=trim($cmdsParse[$i]);
			}
			
			// If the session have code lines, then continue
			if ( count ( $list ) )
			{
				// Previous or Current line number
				$lin = null;
				
				// Append line number on missing code line.
				foreach ($list as $l)
				{					
					// If already appened number, set for the next one
					if ( preg_match ('/^(\d+:).*/', $l, $m) )
						$lin=$m[1];
					else
						$l = $lin.$l;
					
					// Map of to code line (line number | code)					
					$ll = array ();
					
					// Parse line (lineNumber , code)
					preg_match ( '/^(\d+):(.*)$/', $l, $ll );
					
					// Add to commands list [tagSessionId][id] = [lineNo, code]
					$codelines[$j][]=new TakteeCodeLine ( $ll[1], $ll[2] );
				}
			}
			// No line found | Line(s) commented.
			else
			{
				// Add empty session trace
				$codelines[$j] = array();
			}
			
			// Current session
			$j++;
		}
		
		// Code lines with valid line numbers
		return $codelines;
	}
	
	/**
	 * Get the mapped lines
	 *
	 * @access public
	 * @author Junaid Atari <mj.atari@gmail.com>
	 *
	 * @return array Multidimensional array of TakteeCodeLine object
	 */
	public function asArray ()
	{
		return $this->mappedLines;
	}
	
	/**
	 * Count the mapped items by Lines or Sessions
	 *
	 * @access public
	 * @author Junaid Atari <mj.atari@gmail.com>
	 *
	 * @param int $countType Count statments ( by Type Sessions or lines )
	 * @param int $sessionIndex Only count lines of session, works when previous param set to COUNT_LINES
	 * @return int Total lines
	 */
	public function count ( $countType = self::COUNT_SESSIONS, $sessionIndex = -1 )
	{
		if ( $this->mappedLines instanceof TakteeErrorInternal )
			return 0;
		
		// Count total sessions
		$totalSessions = count ( $this->mappedLines );
		
		// Count sessions
		if ( $countType === self::COUNT_SESSIONS )
			return $totalSessions;
		
		//-- Count lines
		
		// Total lines
		$lines = 0;
		
		// If no session found, return 0
		if ( !$totalSessions )
			return $lines;		
		
		// Count lines off session, if valid session id given
		if ( isset ( $this->mappedLines[$sessionIndex]) )
			return count ( $this->mappedLines[$sessionIndex] );
		
		// Count lines per session
		foreach ( $this->mappedLines as $session )
			$lines += count ( $session );
		
		// Return total lines of all sessions
		return $lines;
	}
}
