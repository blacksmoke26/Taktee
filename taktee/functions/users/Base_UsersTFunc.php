<?php

class Base_UsersTFunc extends TakteeLibrary
{
	protected $routine = 'users';
	
	public function getAll ()
	{
		return implode ("\n", array ('subroutine <strong>ali</strong>', 'subroutine <strong>junaid</strong>'));
	}
	
	public function delete ($needle)
	{
		return __FUNCTION__;
	}
	
	public function deleteAll ($mixed)
	{
		return __FUNCTION__;
	}
	
	/**
	 * <info>
	 *   <desc>Count subroutines</desc>
	 *   <params>
	 *      <param label="subroutine name|id" isOptional="false" />
	 *   </params>
	 *   <returnType>int</returnType>
	 * </info>
	 */
	public function exists ( $needle )
	{
		return 'called ' . __FUNCTION__;
	}
	
	/**
	 * <info>
	 *   <desc>Count subroutines</desc>
	 *   <params>
	 *   </params>
	 *   <returnType>Count subroutines</returnType>
	 * </info>
	 */
	public function count ()
	{
		return 10;
	}
	
	public function help ()
	{
		parent::help();
		
		return "<strong>routine</strong> {$this->routine}\n"
			. ".<strong>subroutine</strong>.*\n"
			. ".<strong>getAll</strong> (); Get the name of subroutines\n"
			. ".<strong>count</strong> (); Count subroutines\n"
			. ".<strong>create</strong> (username, surname, email, type); Create new subroutine\n"
			. ".<strong>delete</strong> (subroutine name or id | query); Delete subroutine by name or id\n"
			. ".<strong>deleteAll</strong> (query); Delete subroutines by condition\n"
			. ".<strong>exists</strong> (subroutine name or id); Check for subroutine exists";
	}
}