<?php

include_once __DIR__ . "/../config.php";

$controller = isset( $_REQUEST['c'] )? $_REQUEST['c'] : '';

switch ( $controller ) {

	case 'objetivo':
		$m_controller = new ControllerObjetivo();
		break;

	case 'competencia':
		$m_controller = new ControllerCompetencia();
		break;

	case 'disciplina':
		$m_controller = new ControllerDisciplina();
		break;

	case 'dominio':
		$m_controller = new ControllerDominio();
		break;

	case 'slide':
		$m_controller = new ControllerSlide();
		break;

	case 'alternativa':
		$m_controller = new ControllerAlternativa();
		break;
	
	default:
		$m_session = new Session();
		$m_session->setValue( 'mensagem', 'Controlador nao definido.' );
		header("location: acessonegado.php");
		exit();
		break;
}


?>