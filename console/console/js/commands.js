_commands =
{
	exec:function()
	{
		parent.addBufferString ( 'Executing...' );
			
		if ( $.trim (code) == '' )
		{
			parent.addBufferString ( 'Error: No Taktee code given.' );
			return true;
		}
		
		if ( typeof ( parent.sendCallback ) === 'function' )
		{
			parent.sendCallback.call ( this, code, parent );
		}
		
		parent.focusCommand();
		return true;
	},
	
	clr:function()
	{
		buffer.text ( bufferStr );		
		parent.addBufferString ( 'type "-console help" for help, press ctrl+space for -console keyword.' );
		return true;
	},
	
	clrall:function()
	{		
		_commands.clr();
		_commands.clrcode();
		return true;
	},
	
	clrcode:function()
	{
		code = '';
		currentline = 1;
		return true;
	},
	
	about:function()
	{			
		parent.addBufferString ( "ABOUT\n"+_console['name']+" version: "+_console['ver']);
		parent.addBufferString ('copyright 2014 <strong>Junaid Atari</strong> (mj.atari@gmail.com)' );
		parent.addBufferString ('Licence: <a target="_blank" href="http://www.apache.org/licenses/LICENSE-2.0.html">http://www.apache.org/licenses/LICENSE-2.0.html</a>' );
		parent.addBufferString ('Github: <a target="_blank" href="http://blacksmoke26.github.io/Taktee/">Taktee Project</a>' );
		return true;
	},
	
	showcode:function()
	{
		_code = $.trim(code);
		
		if (_code == '')
		{
			parent.addBufferString ( 'Error: Code is empty.' );
			return true;
		}
		
		codestr = '';
		
		$.each(_code.split("\n"),function(i,v){
			codestr+=(i+1)+'. ' + v + "\n";
		});
		
		parent.addBufferString ( '===================================================================' );
		parent.addBufferString ( $.trim(codestr) );
		parent.addBufferString ( '===================================================================' );
		return true;
	},
	
	help: function (){
		parent.addBufferString ( "HELP" );
		parent.addBufferString ( "Note: Only a subset of Console's commands are provided here." );
		parent.addBufferString ( "Given commands only work if following by '-console' and seperated by space" );
		parent.addBufferString ( "Example 1: -console about" );
		parent.addBufferString ( "Example 2: -console clr about" );
		parent.addBufferString ( "------------------------------------------------------------" );
		parent.addBufferString ( "about : About Console" );
		parent.addBufferString ( 'exec : Execute the Taktee code' );
		parent.addBufferString ( 'clr : Clear buffer output' );
		parent.addBufferString ( 'clrall : Clear buffer output and all the Taktee code' );
		parent.addBufferString ( 'clrcode : Clear all the Taktee code' );
		parent.addBufferString ( 'showcode : Print all the written code' );
		parent.addBufferString ( 'help : Show console help' );
		return true;
	}
};