/**
 * Taktee Web Console
 *
 * @author Junaid Atari <mj.atari@gmail.com>
 */

var takteeconsole = function ()
{
	this.consoleId = '#webconsole';
	this.inputId = '#webconsole .command';
	this.bufferId = '#webconsole .buffer';
	
	var parent = this;
	this.basePath = 'console/js';
	
	var main = $(parent.consoleId);
	var input = $(parent.inputId);
	var buffer =$(parent.bufferId);
	
	var currentline = 0;
	var lines;
	var bufferStr = '';
	var code = '';
	this.sendCallback = null;
	var value = '';
	var tagOpen = false;
	var tagClose = false;
	
	/**
	 * @var object Console Cache
	 */
	var _console = {
		name: 'Taktee Console',
		ver: '1.0',
	};
	
	/**
	 * Set cursor at the end of text
	 *
	 * @author Junaid Atari <mj.atari@gmail.com>
	 * @return void
	 */
	var setCursorAtEnd = function()
	{	
		// Hack, set cursor at end
		input.selectionStart = input.val().length;
		input.selectionEnd = input.val().length;
		input.focus();
	}
	
	this.focusCommand = function ()
	{				
		var n = buffer.innerHeight ();
		main.animate({ scrollTop: n }, 50);
	}
	
	var _commands =	{};
	
	var getHelpString = function(){
		var str = '';
		$.each(_commands, function (i, v){
			alert('hlpstr =_commands.'+i+'(true);');
			eval ('hlpstr =_commands.'+i+'(true);');
			str+=i+' => '+hlpstr +"\n";
		});
		return str;
	}
	
	var _evt_ArrowkeyDown = function ()
	{
		lines = code.split("\n")
				
		if ( lines.length && currentline < lines.length-1 )
		{
			currentline += 1;
			
			vstr = lines[currentline];
			
			input.val ( !/^\>\s/.test(vstr) ? vstr : vstr.substring(2)  );
			
			// Hack, set cursor at end
			setCursorAtEnd();
		}
	}
	
	var _evt_ArrowkeyUp = function ()
	{
		lines = code.split("\n")
		
		if ( lines.length && currentline > 0 && currentline <= lines.length )
		{
			currentline-=1;
		}
		
		vstr = lines[currentline];
		
		input.val ( !/^\>\s/.test(vstr) ? vstr : vstr.substring(2)  );
		
		// Hack, set cursor at end
		setCursorAtEnd();
	}
	
	var _updateCodeConsole = function (){
		code += value + "\n";
		
		buffer.text( buffer.text() + '> ' + value + "\n" );
		input.val('');
		lines=code.split("\n");
		currentline = lines.length-1;
	}
	
	var _evt_AnyKeyDown = function ()
	{		
		if ( /^\-console\s[^;]+$/.test( value ) )
		{
			cmds = value.replace(/^\-console\s/,'').split(' ');
			
			if ( !cmds.length )
				return;
			
			var cmdResponse = false;
			
			$.each ( cmds, function(i, val){
				val = $.trim(val);
				
				eval ('chkcommand = typeof _commands.'+val + ' === "function";');
				
				if ( chkcommand )
					eval ('cmdResponse = _commands.'+val + '(); ');
				
				if ( cmdResponse !== true )
				{
					parent.addBufferString ('Undefined command `'+val+'`.');
					parent.focusCommand();
				}
			});
			
			input.val('');
			return false;
		}
		else
		{
			if ( /^\@\{$/.test( value ) )
			{
				if ( tagOpen == true )
				{
					parent.addBufferString ('Error: Please close the previous tag first');
					return;
				}
				
				tagOpen = true;
				tagClose = false;
				
				_updateCodeConsole();
				
				return;
			}
			
			if ( tagOpen && /^\}$/.test( value ) )
			{
				tagOpen = false;
				tagClose = true;
				input.val('');
				
				_updateCodeConsole();
				
				return;
			}
			
			if ( !tagOpen )
			{
				input.val('');
				parent.addBufferString ('Error: Please open the "@{" tag first.');
				return;
			}
			
			if ( tagOpen && $.trim(value) != '' && !/^\/\//.test( value ) && !/;$/.test( value ) )
			{
				parent.addBufferString ('Error: Missing ; at the end');
				return;
			}	
		
			_updateCodeConsole();
		}
	}
	
	var _registerEvent = function()
	{
		
		input.keydown(function( event )
		{
			value = $.trim ( input.val() );
			
			if ( event.which == 13 )
			{
				event.preventDefault();
				
				_evt_AnyKeyDown ();
			}
			
			// Up Arrow Key
			if ( event.which == 38 )
			{
				event.preventDefault();
				
				_evt_ArrowkeyUp ()
			}			
			
			// Down Arrow Key
			if ( event.which == 40 )
			{
				event.preventDefault();
				
				_evt_ArrowkeyDown ();
			}

			parent.focusCommand();
		});
	}
	
	this.register = function ()
	{
		bufferStr += _console['name']+" version: "+_console['ver']+"\n";
		bufferStr += "(c) 2014 Junaid Atari\n";
		bufferStr += "\n";
	
		bufferStrLines = bufferStr.split("\n").length;
		buffer.text ( bufferStr);
		parent.addBufferString ( "Initializing, please wait..." );
		
		$.getScript (parent.basePath + '/commands.js', function ( data, textStatus, jqxhr )
		{
			// Loaded extenal commands			
			eval (data);
			
			// Add buffer message
			parent.addBufferString ( "Initializing completed without error(s)." );
			
			// Add buffer message
			parent.addBufferString ( "Clearing buffer..." );
			
			setTimeout (function(){
				_commands.clr();
				parent.addBufferString ( 'type "-console help" for help' );
			}, 500);
			
			// Register key binding event
			_registerEvent ();
		});
	}
	
	this.addBufferString = function (str)
	{
		buffer.text ( buffer.text () + str + "\n" );
	}
	
	this.getCode = function (){
		return code;
	}
	
	this.inputFocus = function (){
		input.focus();
	}
}