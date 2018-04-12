<?php

function autoload($classname) {

  $prefixo = substr( $classname, 0, 3);
  $dir = ( $prefixo == 'Dao' )? 'dao/' : '';

  require_once CAMINHO_ABSOLUTO . "model/".$dir . $classname.".php";

}

spl_autoload_register( "autoload" );

?>