<?php

class UsersTFunc extends TakteeFunction
{
	protected $users = array ();
	
	private function getPattern ( $type )
	{
		$pattern = array ();
		
		// routine | routine.*(*)
		$pattern['base'] = $this->getBaseName(). '('
			. '\.(?<method>[a-zA-Z_0-9]{3,50})\s*'
				. '\(\s*(?<params>.*?|(("|\')(.*)\5)\s*,?\s*|(?R))?\s*\)'
		.')?';
		
		// routine.subroutine | routine.subroutine.property | routine.subroutine.*(*)
		$pattern['subroutine'] = $this->getBaseName(). '\.'
			.'(?<subroutine>[a-zA-Z_0-9]{3,50})'.
			'('
				. '\.((?<property>[a-zA-Z_0-9]{3,50})|(?<method>[a-zA-Z_0-9]{3,50})\s*'
				. '\(\s*(?<params>.*?|(("|\')(.*)\7)\s*,?\s*|(?R))?\s*\))'
			.')?';
		
		return $pattern[$type];
	}
	
	public function execute ()
	{
		$matches = array ();
		
		/*
		 * Check for the base functions
		 * @see users/Base_UsersTFunc
		 *
		 * syntax 1: routine;
		 * syntax 2: routine.*(*);
		 */
		if ( $this->checkStatment ( $this->getPattern('base'), $matches ) )
		{
			// Return with error. No base class found
			if ( !TakteeUtils::registerClassByFile ( $this->getLibPathByName ('Base') ) )
				return $this->setRoutineError ( 404, 'Base library does not exist'	);
			
			// No function given, Show base note.
			if ( !isset ( $matches['method'] ) )
				return $this->setOutput (
					"<strong>routine</strong> {$this->getBaseName()}\n" . "Note: use {$this->getBaseName()}.help(); for reference"
				);
			
			// Remove numeric keys=>value from matches.
			TakteeUtils::removeNumericIndexes ( $matches );
			
			// Execute the method and return output
			$output = TakteeUtils::getOutputByMethod ( $this->getLibName ('Base'), $matches['method'], $matches['params'], array () );
			
			// Return if error exists
			if ( $output instanceof TakteeErrorInternal )
			{
				$this->output = $output;
				return;
			}
			
			// Return the output
			return $this->setOutput ( $output );
		}
		
		/*
		 * Check for the base functions
		 * @see users/Base_UsersTFunc
		 *
		 * syntax 1: routine.subroutine;
		 * syntax 2: routine.subroutine.property;
		 * syntax 2: routine.subroutine.*(*);
		 */
		if ( $this->checkStatment ( $this->getPattern('subroutine'), $matches ) )
		{
			// Return with error. No base class found
			if ( !TakteeUtils::registerClassByFile ( $this->getLibPathByName ('Subroutine') ) )
				return $this->setRoutineError ( 404, 'Subroutine library does not exist' );
			
			TakteeUtils::removeNumericIndexes ( $matches );
			
			// Send options
			$_options = array (
				'root' => $this->getBaseName() .'.'. $matches['subroutine'],
				'routine' => $this->getBaseName(),
				'subroutine' => $matches['subroutine'],
			);
			
			$subrClass = $this->getLibName ('Subroutine');
			
			// Create object
			$routine = new $subrClass ( $_options );
			
			if ( !$routine->_subroutineExists ( $matches['subroutine'] ) )
				return $this->setRoutineError ( 100, 'Subroutine does not exist' );
			
			// No function given, Show base note.
			if ( ( !isset ( $matches['property'] ) && !isset ( $matches['method'] ) ) || strpos ( $matches['method'], '_' ) !== false )
				return $this->setOutput ( $routine->_baseInfo() );
			
			// Check for property
			if ( isset ( $matches['property'] ) )
			{
				if ( !$routine->_propertyExists ( $matches['subroutine'], $matches['property'] ) )
					return $this->setRoutineError ( 101, 'property does not exist' );
								
				// Return if error exists
				if ( $output instanceof TakteeErrorInternal )
					return $this->setRoutineError ( $output->id, $output->getKey('msg'), $output->vars );
				
				return $this->setOutput (
					$routine->get ( $matches['property'] )
				);
			}
			
			// Execute the method and return output
			$output = TakteeUtils::getOutputByMethod ( $this->getLibName ('Subroutine'), $matches['method'], $matches['params'], $_options );
			
			// Return if error exists
			if ( $output instanceof TakteeErrorInternal )
				return $this->setRoutineError ( $output->id, $output->getKey('msg'), $output->vars );
			
			// Return the output
			return $this->setOutput ( $output );
		}
		
		// No matched, continue parsing
		return parent::setNullOutput();
		
	}
	
	private function setRoutineError ( $no, $msg, array $vars = array () )
	{
		return parent::setCustomErrorOutput ( $no, ucfirst ( $this->getBaseName() ) . ' error', $msg, $vars );
	}
}