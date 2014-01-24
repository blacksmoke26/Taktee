<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>Web Console</title>
<style type="text/css">
.main { width: 800px; margin: 100px auto; }
.main  h1 { font-family:Gotham, "Helvetica Neue", Helvetica, Arial, sans-serif; }
a { color:#06DF2C; }
</style>
<link rel="stylesheet" type="text/css" href="console/css/theme-dark.css">
<script src="thirdparty/jquery.min.js"></script>
<script src="console/js/console.js"></script>
<script type="text/javascript">

function startConsole ()
{
	var $console = new takteeconsole();
	
	$console.sendCallback = function(code)
	{		
		$.post('post.php',
			{
				'code': ( "@{\n" + code + '}' )
			},
			function(data)
			{
				$console.addBufferString(data);
				parent.focusCommand();
			}
		);
	}
	
	$console.register();
	$console.inputFocus();
	
	$('#btnCodeAddHello').click(function() {
		$console.clearCode();
		$console.addCode('// Say hello');
		$console.addCode('_test [ helloWorld () ];');
		$console.execute();
		$console.inputFocus();
	});
}

$(function()
{
	startConsole ();
});

</script>
</head>

<body>
<div class="main">
<h1>Taktee Console ver 1.0</h1>
	<div id="webconsole">
		<pre class="buffer"></pre>
		<div class="iwrapper">&gt;
			<input type="text" class="command">
		</div>
	</div>
	<div class="Tools">
		<button type="button" id="btnCodeAddHello">Hello Code</button>
	</div>
</div>
<div id="lin"></div>
</body>
</html>