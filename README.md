Taktee
======

### Description

Taktee: (Urdu: تقطیع) means cutting into parts, dissection; scanning (of verse); the pause (in reading poetry), The custom language parser using with Taktee Console. I just developing it in free time for fun and practice. I love to write PCRE patterns and learn new programming ways to improve my skills and knowledge. I always want to have my custom language which do quick tasks on web via command line web tool. After getting this idea, i started to work on that parser, but honeslty I love my URDU language that's why I named it in URDU.

**Note**: Experimental (can be change without notice)

#### Features
- Parse line by line based on statments ended by semi colon (;).
- Opening and Closing Braces (can be change)
- Comment lines
- Multi statments on each line
- Better error handling
- Internationalization (i18n) of error messages
- Type hinting/casting for Parameters
- Customizable and well documented
- Support Plugins (develope Libraries, Functions without touching core/base files)
- Beautify Output
- Detailed debug information (if plugin provides)
- Easy to use

### Links
- <a target="_blank" href="http://blacksmoke.plutohost.net/taktee/console/">Console Demo</a>
- <a target="_blank" href="http://blacksmoke.plutohost.net/taktee/">Main Demo</a>
- <a target="_blank" href="http://blacksmoke.plutohost.net/taktee/reference/">Class Reference</a>

### Use

#### Global/Defined functions
```php
# Debug Mode (comment or remove the line on production)
define ('TAKTEE_DUBUG_OUTPUT', 1);

# Add executed code expression in Output (comment or remove the line on production)
define ('TAKTEE_DEBUG_CODE_OUTPUT', 1);

// Include Taktee
include ( "bootstrap.php" );

$code = '
@{
    // Get all the Subroutines name
    users.getAll();
        
    // Get subroutine property value
    users.junaid.name;
        
    // Gte subroutine property value by method
    users.ali.get ("name");
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
````html
subroutine <strong>ali</strong>
subroutine <strong>junaid</strong>
Junaid Atari
Muhammad Ali
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
                    [output:protected] => subroutine <strong>ali</strong>
subroutine <strong>junaid</strong>
                    [code:protected] => users.getAll();
                    [debug:protected] => 
                    [lineNo:protected] => 3
                )

            [1] => TakteeOutput Object
                (
                    [output:protected] => Junaid Atari
                    [code:protected] => users.junaid.name;
                    [debug:protected] => 
                    [lineNo:protected] => 6
                )

            [2] => TakteeOutput Object
                (
                    [output:protected] => Muhammad Ali
                    [code:protected] => users.ali.get ("name");
                    [debug:protected] => 
                    [lineNo:protected] => 9
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
