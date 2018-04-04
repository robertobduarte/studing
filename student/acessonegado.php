<?php
include_once __DIR__ . "/../config.php";
include $_SERVER['DOCUMENT_ROOT'] . '/' . APP . "view/head.php";

$msg = $m_session->getValue( 'mensagem', true );
$mensagem = ( !empty( $msg ) )? $msg : ' Você não tem acesso a este conteúdo. Verifique se parâmetros estão incorretos.';

?>

<div class="row panel_main_page ">

	<div class="col-md-12 panel_conteudo">

		<div class='col-md-12' id='acessonegado'>
			<div class="alert alert-danger" role="alert" style="margin-top: 20px;">
				<p><b><?= $mensagem; ?></b></p>	
			</div>

		</div>
	
	</div>

</div> <!-- .panel_main_page -->
		
<?php include $_SERVER['DOCUMENT_ROOT'] . '/' . APP . "view/footer.php"; ?>