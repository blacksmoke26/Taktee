<?php

# Debug Mode (comment/remove the line on production)
//define ('TAKTEE_DUBUG_OUTPUT', 1);

# Add executed code expression in Output (comment/remove the line on production)
//define ('TAKTEE_DEBUG_CODE_OUTPUT', 1);

// Include Taktee Bootstrapper
include ( "taktee/bootstrap.php" );

// Just Taktee Code will only capture @{ ... }
$code = '
@{
	// Routine
	users.getAll();
	
	// Subroutine property
	users.junaid.name;
	
	// Subrutine get property
	users.ali.get ("name");
}';

// Create Taktee Object and execute the code
$taktee = new Taktee ( $code );

// Check for the error
if ( $taktee->hasError () )
{
    // print_r for more error details
    echo $taktee->getError();

    return;
}

// Convert the output in text and display
echo TakteeOuputBeautifier::asText ( $taktee->getOutput() );


