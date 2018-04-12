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


if( $ambiente == 'desenvolvimento'){
	
	//BANCO DE DADOS***********
	define('DB', "mysql");
	define('DB_NAME', "studing");
	define('DB_HOST', "localhost");
	define('DB_USER', "root");
	define('DB_PASS', "");
	define('DB_PORT', "3306");
	

}else if( $ambiente == 'producao'){
			
	//BANCO DE DADOS***********
	define('DB', "mysql");
	define('DB_NAME', "comidinhaspara");
	define('DB_HOST', "mysql.comidinhasparaceliaco.com.br");
	define('DB_USER', "comidinhaspara");
	define('DB_PASS', "M3rd41980");
	define('DB_PORT', "3306");

}

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
