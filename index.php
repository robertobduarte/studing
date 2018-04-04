<?php
include_once __DIR__ . "/config.php";

$m_session = new Session();

$m_autenticacao = new Autenticacao();
$m_autenticacao->setUser( 'sofiarduarte' );
//debug($_SESSION);
//exit();

//header('location:view/home.php');
//exit();

if ( !$m_session->getValue('usuario') ) {

    $location = ( 'view/login.php' );

}else{

    $url = $m_session->getValue( 'url', true );
    $location = ( $url )? $url : 'view/home.php';

}

header("location:".$location);
exit();

?>