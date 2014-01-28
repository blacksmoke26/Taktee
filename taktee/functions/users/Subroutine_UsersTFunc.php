<?php

class Subroutine_UsersTFunc extends TakteeLibrary
{
	private $users = array ();
	
	public function __construct ( $options = array () )
	{
		parent::__construct ( $options );
		
		$this->users = array (
			'junaid'=>array (
				'name' => 'Junaid Atari',
				'email' => 'mj.atari@gmail.com',
				'type' => 'Super',
			),
			
			'ali'=>array (
				'name' => 'Muhammad Ali',
				'email' => 'muhammal_ali@yahoo.com',
			)
		);
	}
	
	public function set ( $property, $value )
	{
		return __FUNCTION__;
	}
	
	public function _baseInfo ()
	{
		return '<strong>subroutine</strong> ' . $this->options['root'] . "\n"
				. 'Note: use ' . $this->options['root'] . '.help(); for reference';
	}
	
	public function get ( $property )
	{
		if ( !$this->_propertyExists ( $this->options['subroutine'], $property ) )
			return $this->setError ( 90, '$root does not exist', array ( '$root' => $this->options['root'].'.'.$property ) );
		
		return $this->users[$this->options['subroutine']][$property];
	}
	
	
	protected function setError ( $id, $msg, array $vars = array () )
	{
		$ierror = new TakteeErrorInternal ( $id, $vars );
		$ierror->setKey ( 'msg', $msg );
		
		return $ierror;
	}
	
	/**
	 * <info>
	 *   <desc>Delete subroutine</desc>
	 *   <returnType>bool</returnType>
	 * </info>
	 */
	public function delete ()
	{
		return $this->options['root'] . ' removed';
	}
	
	public function _subroutineExists ( $name )
	{
		return array_key_exists ( $name, $this->users );
	}
	
	public function _propertyExists ( $subroutine, $property )
	{
		return isset ( $this->users[$subroutine][$property] );
	}
	
	public function help ()
	{
		parent::help();
		
		return '<strong>subroutine</strong> '.$this->options['root']."\n"
			. ".<strong>property</strong>; Get property value\n"
			. ".<strong>set</strong> (property, value); Update property value \n"
			. ".<strong>get</strong> (property); Get property value\n"
			. ".<strong>delete</strong> (); Remove subroutine\n"
			. ".<strong>help</strong> (); Subroutine reference";
	}
}