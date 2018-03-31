<?php

function autoload($classname) {

  $prefixo = substr( $classname, 0, 3);
  $dir = ( $prefixo == 'Dao' )? 'dao/' : '';

  require_once $_SERVER['DOCUMENT_ROOT'] . '/' . APP . "model/".$dir . $classname.".php";

}

spl_autoload_register( "autoload" );

?>