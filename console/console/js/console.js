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
		main.animate({ scrollTop: n }, 1);
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
					parent.addBufferString ('Command `'+val+'` is not defined.');
					parent.focusCommand();
				}
			});
			
			input.val('');
			return false;
		}
		else
		{
			if ( $.trim(value)=='' )
			{
				parent.addBufferString ('');
				return;
			}
			
			if ( !/^\/\//.test( value ) && !/;$/.test( value ) )
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
		
		input.keypress (32, function (event){
			if (event.ctrlKey)
			{
				input.val('-console ');
				input.focus();
				event.preventDefault();
			}
		})
	}
	
	this.register = function ()
	{
		bufferStr += _console['name']+" version: "+_console['ver']+"\n";
		bufferStr += "(c) 2014 Junaid Atari\n";
		bufferStr += "\n";
	
		bufferStrLines = bufferStr.split("\n").length;
		buffer.text ( bufferStr );
		parent.addBufferString ( "Initializing, please wait..." );
		
		$.getScript (parent.basePath + '/commands.js', function ( data, textStatus, jqxhr )
		{
			// Loaded extenal commands			
			eval (data);
			
			// Add buffer message
			parent.addBufferString ( "Initializing completed without error(s)." );
			
			setTimeout (function(){
				// Add buffer message
				parent.addBufferString ( "Preparing Taktee web console..." );
			}, 300);
			
			setTimeout (function(){
				// Add buffer message
				parent.addBufferString ( "Clearing buffer..." );
			}, 900);
			
			setTimeout (function(){
				_commands.clr();
			}, 2000);
			
			// Register key binding event
			_registerEvent ();
		});
	}
	
	this.addBufferString = function (str)
	{
		buffer.append ( str + "\n" );
	}
	
	this.getCode = function (){
		return code;
	}
	
	this.addCode = function (str){
		input.val(str);
		value = $.trim ( input.val() );
		_evt_AnyKeyDown();
	}
	
	this.setInput = function (str){
		input.val(str);
	}
	
	this.execute = function (){
		parent.addCode ('-console exec');
	}
	
	this.clearBuffer = function (){
		parent.addCode ('-console clr');
	}
	
	this.clearAll = function (){
		parent.addCode ('-console clrall');
	}
	
	this.clearCommand = function (){
		input.val('');
	}
	
	this.clearCode = function (){
		code='';
	}
	
	this.inputFocus = function (){
		input.focus();
	}
}