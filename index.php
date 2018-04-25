<?php
include_once __DIR__ . "/config.php";

$m_session = new Session();

$m_autenticacao = new Autenticacao();
//$m_autenticacao->setUser( 'sofiarduarte' );
//$m_autenticacao->setUser( 'robertobduarte' );
//$m_autenticacao->setUser( 'azambuja' );
$m_autenticacao->setUser( 'gensa' );
//debug($_SESSION);
//exit();

//header('location:view/home.php');
//exit();

if ( !$m_session->getValue('usuario') ) {

    $location = ( 'view/login.php' );

}else{

    $location = $m_session->getValue( 'url', true );
    if( !$location ){

        $perfil = $m_session->getValue( 'perfil' );
        switch ( $perfil ) {

            case 'ADM':
                $location = "admin/home.php";
                break;

            case 'ADMD':
                $location = "admin/home.php";
                break;

            case 'PRF':
                $location = "admin/home.php";
                break;

            case 'STD':
                $location = "student/home.php";
                break;
            
            default:
                $location = "student.php";
                break;
        }
    }

}

header("location:".$location);
exit();

?>
