<?php
include_once __DIR__ . '/environment.php';

header("Content-type: UTF-8");

date_default_timezone_set('America/Sao_Paulo');

error_reporting( E_ALL );

ini_set( 'ignore_repeated_source', true );    
ini_set( 'ignore_repeated_errors', true );
ini_set('display_errors', false);
ini_set( 'log_errors', true );

//classe e função callback chamada sempre que houver um erro
require_once CAMINHO_ABSOLUTO . "model/ErrorControl.php";
set_error_handler(array('ErrorControl','errorAction'));

require_once CAMINHO_ABSOLUTO . "autoload.php";


function debug( $variavel ){

	echo '<br>';
	echo '***************><***************';
	echo '<pre>';
	print_r( $variavel );
	echo '</pre>';
	echo '***************><***************';
	echo '<br>';

}

?>
