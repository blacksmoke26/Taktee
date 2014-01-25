_commands =
{
	exec:function()
	{
		parent.addCodeBufferStrNative ( 'Executing...' );
			
		if ( $.trim (code) == '' )
		{
			parent.addCodeBufferErrorString ( 'Error: No Taktee code given.' );
			return true;
		}
		
		if ( typeof ( parent.sendCallback ) === 'function' )
			parent.sendCallback.call ( this, code, parent );
		
		parent.focusCommand();
		return true;
	},
	
	clr:function()
	{
		parent.addIcmdBufferString ( 'Clearing buffer...' );
		setTimeout(function(){
			buffer.text ( bufferStr );		
			parent.addCodeBufferStrNative ( 'type "-console help" for help, press ctrl+space for -console keyword.' );
		}, 200);
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
		parent.addBufferString ('View on Github: <a target="_blank" href="http://blacksmoke26.github.io/Taktee/">Taktee Project</a>' );
		return true;
	},
	
	showcode:function()
	{
		_code = $.trim(code);
		
		if (_code == '')
		{
			parent.addCodeBufferErrorString ( 'Error: Code is empty.' );
			return true;
		}
		
		codestr = '';
		var _lins = 1;
		$.each(_code.split("\n"),function(i,v){
			_lins +=1;
			codestr += '<span class="lineno">' +(i+2)+'.</span>&nbsp;&nbsp;&nbsp;&nbsp;<span class="takteecode">' + v + "</span>\n";
		});
		
		parent.addBufferString ( '===================================================================' );
		parent.addBufferString ( '<span class="lineno">1</span>.&nbsp;&nbsp;<span class="takteetags">@{</span>' );
		parent.addBufferString ( $.trim(codestr) );
		parent.addBufferString ( '<span class="lineno">'+(_lins+1)+'</span>.&nbsp;&nbsp;<span class="takteetags">}</span>' );
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