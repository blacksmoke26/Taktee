Taktee
======

### Description

Taktee: (Urdu: تقطیع) means cutting into parts, dissection; scanning (of verse); the pause (in reading poetry), The custom language parser. I just developing it in free time for fun and practice. I love to write PCRE patterns and learn new programming ways to improve my skills and knowledge. I always want to have my custom language which do quick tasks on web via command line web tool. After getting this idea, i started to work on that parser, but honeslty I love my URDU language that's why I named it in URDU.

#### Features
- Parse line by line based on statments ended by semi colon (;).
- Opening and Closing Braces (can be change)
- Comment lines
- Multi line statments on each line
- Better error handling
- Internationalization (i18n) of error messages
- Type hinting/casting for Parameters
- Easily customized and well documented
- Plugin based, develope Libraries, Functions without touching core/base files
- Beautify Output
- Detailed debug information
- Easy to use

### Use

#### Global/Defined functions
```php
# Debug Mode (Remove or comment the line for off)
define ('TAKTEE_DUBUG_OUTPUT', 1);

# Add executed code expression in Output (Remove or comment the line for off)
define ('TAKTEE_DEBUG_CODE_OUTPUT', 1);

// Include Taktee
include ( "bootstrap.php" );

/**
 * Say Hay to $name
 * 
 * @param string $name Any name
 */
function sayHay ( $name )
{
    echo 'Hay ' . $name . '!';
}

$code = '
@{
	// It is the Function library Plugin, @see /functions/TakteeFunctionCommands.php
	// I use ^core for already defined global function
	^core [ sayHay ( "Junaid Atari" ) ];
}
';

// Now the fun part

$taktee = new Taktee ( $code );

if ( $taktee->hasError () )
{
	// print_r for error details
	echo $taktee->getError();
	
	return;
}

// Convert the output in text and display
echo TakteeOuputBeautifier::asText ( $taktee->getOutput() );
```

##### Output
````text
Hay Junaid Atari!
````

##### Debug Output
````php
print_r ( $taktee->getOutput() );
````
**Turns to**
````text
Array
(
    [0] => Array
        (
            [0] => TakteeOutput Object
                (
                    [output:protected] => Hay Junaid Atari!
                    [code:protected] => ^core [ sayHay ( "Junaid Atari" ) ];
                    [debug:protected] => TakteeOutputDebug Object
                        (
                            [debugInfo:protected] => Array
                                (
                                    [type] => core
                                    [command] => core
                                    [function] => sayHay
                                    [params] => Array
                                        (
                                            [0] => Junaid Atari
                                        )

                                )

                        )

                    [lineNo:protected] => 4
                )

        )

)
````

### Debug Error : echo

````php
// As string
echo $taktee->getError();
````
Turns to

````text
Taktee syntax error (27): missing ; at end of the line in foo/bar/file.php on line 5
````
### Debug Error : print_r ()

````php
// As detailed object
print_r ( $taktee->getError() );
````
Turns to
````html
TakteeError Object
(
    [category:protected] => Syntax error
    [errorNumber:protected] => 27
    [message:protected] => missing ; at end of the line
    [line:protected] => 5
    [code:protected] => }1
    [file:protected] => foo/bar/file.php
)
````
#####