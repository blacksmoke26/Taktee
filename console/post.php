<?php

if ( !isset($_POST['code']) || !trim ($_POST['code']) )
{
	echo "Not code given....";
	return;
}

$code = trim ( $_POST['code'] );

include ("../taktee/bootstrap.php");

$taktee = new Taktee ( $code );

if ( $taktee->hasError () )
{
	$err = $taktee->getError();
	echo '@error:' . ucfirst ( $err->getCategory () ) . ' ('.$err->getNumber().'): ' . $err->getMessage(). ' "'.$err->getCode().'" on line '.$err->getLineNumber().'.';

    return;
}

// Convert the output in text and display
echo TakteeOuputBeautifier::asText ( $taktee->getOutput() );