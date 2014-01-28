<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>Taktee Console v1.0.1</title>
<style type="text/css">
body, button { font-family:Segoe, "Segoe UI", "DejaVu Sans", "Trebuchet MS", Verdana, sans-serif; }
a { color:#B10002; }
.main { width: 844px; margin: 100px auto; }
.main .pull-left { float:left; }
.main .pull-right { float:right; }
.main .pull-right a.theme { color:#D80306; display:inline-block; width:30px; height:15px; position:relative; top:2px; cursor:pointer; }
.main .pull-right a.theme.light { background-color:#8AAFE8; }
.main .pull-right a.theme.dark { background-color:#1B1A1A; }
.main  h1 { font-family:Gotham, "Helvetica Neue", Helvetica, Arial, sans-serif; }
</style>
<link rel="stylesheet" type="text/css" href="console/css/theme-light.css" id="consolethemefile">
<script src="thirdparty/jquery.min.js"></script>
<script src="console/js/console.js"></script>
<script type="text/javascript">

var $console;

/**
 * Taktee Console setup
 *
 * @access public
 * @author Junaid Atari <mj.atari@gmail.com>
 * @return void
 */
function startConsole ()
{
	// Create console object
	$console = new TakteeConsole();
	
	// Directory where console.js file lies.
	$console.basePath = 'console/js';
	
	// Register callback for execute Taktee code...
	$console.sendCallback = function( code )
	{
		// post.php when Taktee parser execute code.
		$.post('post.php',
			{
				// Append the taktee Tag
				'code': ( "@{\n" + code + '}' )
			},
			function(data)
			{
				// Show output to Buffer
				$console.addCodeBufferOutputString ( data );
				
				//$console.clearCode();
				
				// Focus the input command
				$console.inputFocus();
			}
		);
	}
	
	// Register the console
	$console.register();
	
	// Focus the input command
	$console.inputFocus();
}

$(function(){
	// Now DOM is ready, start console...
	startConsole ();
	
	// Theme changer
   $(".pull-right a.theme.dark").click(function()
	{
		$console.addBufferString('Switching theme... Dark');
		$("#consolethemefile").attr("href", "console/css/theme-dark.css");
		// Got to the end of Buffer and Focus the input command
		$console.inputFocus();
		
		return false;
	});
   
	$(".pull-right a.theme.light").click(function()
	{
		$console.addBufferString('Switching theme... Light and Shine');
		
		// Show to the log
		$("#consolethemefile").attr("href", "console/css/theme-light.css");
		
		// Got to the end of Buffer and Focus the input command
		$console.inputFocus();
		return false;
	});
	
	//================================== Test code ======================================
	$('#btnCodeAddHello').click(function() {
		$console.clearCode();
		$console.addCode('// Call user routine');
		$console.addCode('// Several functions are dummy, you can write logic there.');
		$console.addCode('// Files: ../taktee/functions/users/*.php');
		$console.addCode('users;');
		$console.execute();
		// Got to the end of Buffer and Focus the input command
		$console.inputFocus();
	});
	
	$console.onReady = function()
	{
		$('.tools').fadeIn();
	}
	//================================== /Test code =====================================
});

</script>
</head>

<body>
<div class="main">
<h1>Taktee Console ver 1.0.1</h1>
	<!-- Taktee Console HTML Structure -->
	<div id="webconsole">
		<pre class="buffer"></pre>
		<div class="iwrapper">&gt;
			<input type="text" class="command">
		</div>
	</div>
	<!-- /Taktee Console HTML Structure -->
	<div class="tools" style="display:none"><br>
		<div class="pull-left">
		
		<button type="button" id="btnCodeAddHello">Basic Example</button> View source on <a href="https://github.com/blacksmoke26/Taktee" target="_blank">GitHub</a> </div>
		<div class="pull-right">Switch theme <a href="javascript:;" class="theme light" title="Light and Shine"></a> <a href="javascript:;" class="theme dark" title="Light"></a></div>
	</div>
</div>
</body>
</html>