<?php
include_once __DIR__ . "/config.php";

$m_session = new Session();

$m_autenticacao = new Autenticacao();
$m_autenticacao->setUser( 'rbduarte' );
//exit();

//header('location:view/home.php');
//exit();

if ( !$m_session->getValue('usuario') && isset( $_REQUEST['directac'] ) ) {   

    $str1 = $m_autenticacao->unscramble( $_REQUEST['directac'] );

    $ip = $m_autenticacao->scramble3( date('Ymd') . ( @$_GET['ip'] == null ? $_SERVER['REMOTE_ADDR'] : $_GET['ip'] ) );

    if ( substr( $str1, 0, strlen( $ip ) ) != $ip ) {

    	die( 'Erro na autenticação!<br> x '.$_SERVER['REMOTE_ADDR'] );
    }

    $str = $m_autenticacao->unscramble( substr( $str1,strlen( $ip ) ) );   

    $m_autenticacao->setUser( $str );

}



$url = $m_session->getValue( 'url', true );
$location = ( $url )? $url : 'view/home.php';
header("location:".$location);
exit();

?>