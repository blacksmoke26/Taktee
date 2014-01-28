#Taktee Console

### Description
Taktee Console is a web interface application to execute the Taktee code via Ajax.

### Screenshot
#####Light and Shine Theme
<img alt="Taktee Console (Light Theme)" src="http://i42.tinypic.com/jhsb48.jpg">
#####Dark Theme
<img alt="Taktee Console (Dark Theme)" src="http://i43.tinypic.com/1zxy74p.jpg">

### Demo
<a target="_blank" href="http://blacksmoke.plutohost.net/taktee/console/">Launch Console Demo</a>

### Requirments
- Taktee Parser
- jQuery library

### Features
- Version 1.0.1
  - Code highlight functions
  - New Events and Methods
  - New Code Viewer
  - Light and Shine Theme
- Version 1.0
  - Powerfull API
  - Own console commands
  - Arrow Up/Down keys to jump to line by line the code history
  - Easy to Use and extend
  - Mutiple code statments (save in code history)
  - Base Themes included
  - Easy JavaScript/Ajax setup
  - Tiny HTML structure

### Usage
- Download the Taktee Package.
- Open console/index.php in your web browser.
- That's it, just start using the Taktee.

### HTML Structure
````html
<div id="webconsole">
	<pre class="buffer"></pre>
	<div class="iwrapper">&gt;
		<input type="text" class="command">
	</div>
</div>
````
`Note: For javascript and PHP setup please check the file index.php and post.php.`

### CSS Themes
Please check the `/console/css/*.css` directory
