/**
 * Taktee Web Console
 * @version 1.0.1
 * @author Junaid Atari <mj.atari@gmail.com>
 * @link https://github.com/blacksmoke26/Taktee/tree/master/console Taktee Console
 * @link http://blacksmoke26.github.io/Taktee/ Taktee Parser
 */

var takteeconsole = function ()
{
	/**
	 * @access public
	 * @var string string Main console div tag's id
	 */
	this.consoleId = '#webconsole';
	
	/**
	 * @access public
	 * @var string Input command text tag's id
	 */
	this.inputId = '#webconsole .command';
	
	/**
	 * @access public
	 * @var string Buffer div tag's id
	 */
	this.bufferId = '#webconsole .buffer';
	
	/**
	 * @access public
	 * @var string Base path (Directory where console.js file lies.)
	 */
	this.basePath = 'console/js';
	
	/**
	 * @access private
	 * @var object Self class object
	 */
	var parent = this;
	
	/**
	 * @access private
	 * @var object Console main div tag object
	 */
	var main = $(parent.consoleId);
	
	/**
	 * @access private
	 * @var object Input command text tag object
	 */
	var input = $(parent.inputId);
	
	/**
	 * @access private
	 * @var object Buffer div tag object
	 */
	var buffer =$(parent.bufferId);
	
	/**
	 * @access private
	 * @var int Current line (for Events Arrow Key UP/DOWN)
	 */
	var currentline = 0;
	
	/**
	 * @access private
	 * @var array Code history lines
	 */
	var lines;
	
	/**
	 * @access private
	 * @var string Base buffer string
	 */
	var bufferStr = '';
	
	/**
	 * @access private
	 * @var string Taktee code history
	 */
	var code = '';
	
	/**
	 * @var function Callback function when executing the taktee. (Ajax Request)
	 */
	this.sendCallback = null;
	
	/**
	 * @access private
	 * @var string Input command value
	 */
	var value = '';
	
	/**
	 * @access private
	 * @var object Console commands
	 */
	var _commands =	{};
	
	/**
	 * @access private
	 * @var object Console Cache
	 */
	var _console = {
		name: 'Taktee Console',
		ver: '1.0.1',
	};
	
	/**
	 * Set cursor at the end of text
	 *
	 * @access private
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
	
	/**
	 * Jump the buffer's scroll ent the end.
	 *
	 * @access public
	 * @author Junaid Atari <mj.atari@gmail.com>
	 * @return void
	 */
	this.focusCommand = function ()
	{				
		var n = buffer.innerHeight ();
		main.animate({ scrollTop: n }, 1);
	}
	
	/**
	 * Execute the -console command and return output.
	 *
	 * @access private
	 * @author Junaid Atari <mj.atari@gmail.com>
	 * @return string
	 */
	var getHelpString = function()
	{
		var str = '';
		
		$.each(_commands, function (i, v){
			alert('hlpstr =_commands.'+i+'(true);');
			eval ('hlpstr =_commands.'+i+'(true);');
			str+=i+' => '+hlpstr +"\n";
		});
		
		return str;
	}
	
	/**
	 * Add code to the history, Reset the code lines, Display code on the Buffer.
	 *
	 * @access private
	 * @author Junaid Atari <mj.atari@gmail.com>
	 * @return void
	 */
	var _updateCodeConsole = function ()
	{
		code += value + "\n";
		
		// Event: onCommandEntered callback
		if ( typeof ( parent.onCommandEntered ) === 'function' )
			parent.onCommandEntered.call ( this, value );
		
		// Highlight comment code....
		parent.addCodeBufferString (
			/^\/\/.*/.test(value)
				? '<span class="inrcode_comment">'+'&gt; ' + value+'</span>'
				: '<span>'+'&gt; ' + value+'</span>'
		);
		
		// Clear input command
		input.val('');
		// Remap the lines
		lines=code.split("\n");
		// Set the light length by index (removing -1 because of index)
		currentline = lines.length-1;
	}
	
	/**
	 * Event: Executed by pressing DOWN Arrow Key when input command focus.
	 * Increase the position/index of code lines history
	 *
	 * @access private
	 * @author Junaid Atari <mj.atari@gmail.com>
	 * @return void
	 */
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
	
	/**
	 * Event: Executed by pressing UP Arrow Key when input command focus.
	 * Decrease the position/index of code lines history
	 *
	 * @access private
	 * @author Junaid Atari <mj.atari@gmail.com>
	 * @return void
	 */
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
	
	/**
	 * Event: Executed by pressing Enter Key when input command focus.
	 * Highlight the color code, Execute commands, Errors checker, Code history updater
	 *
	 * @access private
	 * @author Junaid Atari <mj.atari@gmail.com>
	 * @return void
	 */
	var _evt_EnterKeyDown = function ()
	{	
		// Event: beforeConsoleCommandEntered callback
		if ( typeof ( parent.beforeConsoleCommandEntered ) === 'function' )
			parent.beforeConsoleCommandEntered.call ( this, parent );
		
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
					parent.addCodeBufferErrorString ('Error: Console command `'+val+'` is not defined.');
					parent.focusCommand();
				}
			});
			
			input.removeClass('inrccmd');
			input.val('');
			
			// Event: afterConsoleCommandEntered callback
			if ( typeof ( parent.afterConsoleCommandEntered ) === 'function' )
				parent.afterConsoleCommandEntered.call ( this, parent );
			
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
				parent.addCodeBufferErrorString ('Error: Missing ; at the end');
				return;
			}
		
			_updateCodeConsole();
		}
	}
	
	/**
	 * Register events for Input command
	 *
	 * @access private
	 * @author Junaid Atari <mj.atari@gmail.com>
	 * @return void
	 */
	var _registerEvent = function()
	{
		input.keyup(function(e)
		{
			value = $.trim ( input.val() );
			
			if ( /^\-console[^;]*/.test( value ) )
			{
				if (!input.hasClass('inrccmd') )
					input.addClass('inrccmd');
			}
			else
				input.removeClass('inrccmd');
			
			if ( /^\/\/.*/.test( value ) )
			{
				if ( !input.hasClass('inrcode_comment') )
					input.addClass('inrcode_comment');
			}
			else
				input.removeClass('inrcode_comment');
		});
		
		input.keydown(function( event )
		{
			if ( event.which == 13 )
			{
				event.preventDefault();
				
				_evt_EnterKeyDown ();
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
		});
		
		// Event: onReady callback
		if ( typeof ( parent.onReady ) === 'function' )
			parent.onReady.call ( this, parent );
	}
	
	/**
	 * Register and Intialize console
	 *
	 * @access public
	 * @author Junaid Atari <mj.atari@gmail.com>
	 * @return void
	 */
	this.register = function ()
	{
		bufferStr += _console['name']+" version: "+_console['ver']+"\n";
		bufferStr += "(c) 2014 Junaid Atari\n";
		bufferStr += "\n";
	
		bufferStrLines = bufferStr.split("\n").length;
		buffer.text ( bufferStr );
		parent.addIcmdBufferString ( "Initializing console, please wait..." );
		parent.addIcmdBufferString ( "Loading commands..." );
		
		$.getScript (parent.basePath + '/commands.js', function ( data, textStatus, jqxhr )
		{
			parent.addIcmdBufferString ( "Commands loaded without error(s)." );
			
			// Event: onRegistred callback
			if ( typeof ( parent.onRegistred ) === 'function' )
				parent.onRegistred.call ( this, parent );
			
			// Loaded extenal commands	
			// Execute the code		
			eval (data);
			
			setTimeout (function()
			{
				// Show buffer message
				parent.addIcmdBufferString ( 'Initializing completed without error(s).' );
				
				// Event: Initialized callback
				if ( typeof ( parent.onInitialized ) === 'function' )
					parent.onInitialized.call ( this, parent );
			}, 20);
			
			setTimeout (function(){
				// Add buffer message
				parent.addIcmdBufferString ( 'Preparing Taktee web console...' );
			}, 300);
			
			setTimeout (function(){
				// Clear buffer
				_commands.clr();
			}, 900);
			
			setTimeout (function(){
							
				// Register key binding event
				_registerEvent ();
				
			}, 1500);
		});
	}
	
	/**
	 * Event: Execute right after Console intialized
	 *
	 * @access public
	 * @author Junaid Atari <mj.atari@gmail.com>
	 * @return void
	 */
	this.onReady = function(){
		return null;
	};
	
	/**
	 * Event: Execute right after Console command entered
	 *
	 * @access public
	 * @author Junaid Atari <mj.atari@gmail.com>
	 * @return void
	 */
	this.afterConsoleCommandEntered = function(){
		return null;
	};
	
	/**
	 * Event: Execute right before Console command entered
	 *
	 * @access public
	 * @author Junaid Atari <mj.atari@gmail.com>
	 * @return void
	 */
	this.beforeConsoleCommandEntered = function(){
		return null;
	};
	
	/**
	 * Event: Execute right after Command entered
	 *
	 * @access public
	 * @author Junaid Atari <mj.atari@gmail.com>
	 * @return void
	 */
	this.onCommandEntered = function(value){
		return null;
	};
	
	/**
	 * Event: Execute right after Console registered successfully.
	 *
	 * @access public
	 * @author Junaid Atari <mj.atari@gmail.com>
	 * @return void
	 */
	this.onRegistred = function(){
		return null;
	};
	
	/**
	 * Event: Execute right after Console initialized successfully.
	 *
	 * @access public
	 * @author Junaid Atari <mj.atari@gmail.com>
	 * @return void
	 */
	this.onInitialized = function(){
		return null;
	};
	
	/**
	 * Display simple Text/HTML on  buffer output
	 *
	 * @access public
	 * @author Junaid Atari <mj.atari@gmail.com>
	 * @param string str String 
	 * @return void
	 */
	this.addBufferString = function (str)
	{
		buffer.append ( str + "\n" );
	}
	
	/**
	 * Display Console command Text/HTML on  buffer output
	 *
	 * @access public
	 * @author Junaid Atari <mj.atari@gmail.com>
	 * @param string str String 
	 * @return void
	 */
	this.addIcmdBufferString = function (str)
	{
		buffer.append ( '<i class="inrcmd">'+str+'<i>' + "\n" );
	}
	
	/**
	 * Display Console error Text/HTML on  buffer output
	 *
	 * @access public
	 * @author Junaid Atari <mj.atari@gmail.com>
	 * @param string str String 
	 * @return void
	 */
	this.addCodeBufferErrorString = function (str)
	{
		buffer.append ( '<i class="inrerror">'+str+'<i>' + "\n" );
	}
	
	/**
	 * Display Taktee code Text/HTML on buffer output
	 *
	 * @access public
	 * @author Junaid Atari <mj.atari@gmail.com>
	 * @param string str String 
	 * @return void
	 */
	this.addCodeBufferString = function (str)
	{
		buffer.append ( '<span class="inrcode">'+str+'<span>' + "\n" );
	}
	
	/**
	 * Display console code Text/HTML on buffer output
	 *
	 * @access public
	 * @author Junaid Atari <mj.atari@gmail.com>
	 * @param string str String 
	 * @return void
	 */
	this.addCodeBufferStrNative = function (str)
	{
		buffer.append ( '<span class="inrstrnative">'+str+'<span>' + "\n" );
	}
	
	/**
	 * Display Taktee output Text/HTML on buffer output
	 *
	 * @access public
	 * @author Junaid Atari <mj.atari@gmail.com>
	 * @param string str String 
	 * @return void
	 */
	this.addCodeBufferOutputString = function (str)
	{
		if ( /^\@error:/.test (str) )
		{
			parent.addCodeBufferErrorString ( str.replace (/^@error:/, '') );
			return;
		}
		
		buffer.append ( '<span class="inroutput">'+str+'<span>' + "\n" );
	}
	
	/**
	 * Return the code
	 *
	 * @access public
	 * @author Junaid Atari <mj.atari@gmail.com>
	 * @return string
	 */
	this.getCode = function (){
		return code;
	}
	
	/**
	 * Add Taktee code to History
	 *
	 * @access public
	 * @author Junaid Atari <mj.atari@gmail.com>
	 * @return void
	 */
	this.addCode = function (str){
		input.val(str);
		value = $.trim ( input.val() );
		_evt_EnterKeyDown();
	}
	
	/**
	 * Set Input command text
	 *
	 * @access public
	 * @author Junaid Atari <mj.atari@gmail.com>
	 * @param string str Text
	 * @return void
	 */
	this.setInput = function (str){
		input.val(str);
	}
	
	/**
	 * Execute the Taktee code in history
	 *
	 * @access public
	 * @author Junaid Atari <mj.atari@gmail.com>
	 * @return void
	 */
	this.execute = function (){
		parent.addCode ('-console exec');
	}
	
	/**
	 * Clear the Output buffer
	 *
	 * @access public
	 * @author Junaid Atari <mj.atari@gmail.com>
	 * @return void
	 */
	this.clearBuffer = function (){
		parent.addCode ('-console clr');
	}
	
	/**
	 * Clear the Output buffer and code history
	 *
	 * @access public
	 * @author Junaid Atari <mj.atari@gmail.com>
	 * @return void
	 */
	this.clearAll = function (){
		parent.addCode ('-console clrall');
	}
	
	/**
	 * Clear the inout command text
	 *
	 * @access public
	 * @author Junaid Atari <mj.atari@gmail.com>
	 * @return void
	 */
	this.clearCommand = function (){
		input.val('');
	}
	
	/**
	 * Clear code history
	 *
	 * @access public
	 * @author Junaid Atari <mj.atari@gmail.com>
	 * @return void
	 */
	this.clearCode = function (){
		code = '';
	}
	
	/**
	 * Focus the input command
	 *
	 * @access public
	 * @author Junaid Atari <mj.atari@gmail.com>
	 * @return void
	 */
	this.inputFocus = function (){
		input.focus();
		// Focus the end of Buffer
		parent.focusCommand();
	}
}