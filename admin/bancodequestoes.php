<?php
include_once __DIR__ . "/headDominio.php"; //página instancia um dominio - $m_dominio

$m_session->setValue( 'menu', '' );

$objetivo_id = ( isset( $_REQUEST['obj'] ) )? $_REQUEST['obj'] : '';
$disciplina_id = ( isset( $_REQUEST['disc'] ) )? $_REQUEST['disc'] : '';

if( empty( $objetivo_id ) ){

	$m_session->setValue( 'mensagem', 'Parâmetros incorretos.' );
	header("location: acessonegado.php");
	exit();	
}

$disciplina = '';
if( !empty( $disciplina_id ) ){

	$m_disciplina = new Disciplina( array( 'id' => $disciplina_id ) );
	$disciplina = $m_disciplina->__get('nome');
}

$m_objetivo = new Objetivo( array( 'id' => $objetivo_id ) );

$m_objetivo->getParents();
$m_objetivo->setTree(true);

//popula o atributo do objeto com as disciplinas que estão vinculadas ao objetivo
$m_objetivo->getDisciplinas();

?>

<script src="js/bancodequestoes.js?v=<?= filemtime('js/bancodequestoes.js'); ?>"></script>

<div class="row panel_main_page">

	<div class="hidden-xs col-md-2 col-sm-3 panel_menu" id="menu_lateral">
		<?php include __DIR__ . "/menuLateral.php"; ?>
	</div>

	<div class="col-md-10 col-sm-9 col-xs-12 panel_conteudo">

		<?php 

			$retorno = $m_session->getValue( 'retorno', true );

			if( !empty( $retorno ) ){

				echo '<div class="alert alert-warning">' . $retorno . '</div>';
			}
		
		?>

		<span class="linkTree"><?php echo ( isset( $m_objetivoParent ) ) ? $m_objetivoParent->tree :  $m_objetivo->tree ?></span>
		<div class="col-md-12 tituloPage">
			<h4>BANCO DE QUESTÕES</h4>
		</div>

		<div class="col-md-12 corpoPage">							
						
			<section class="col-md-12">

				<?php $m_objetivo->showLinksBQDisciplinas( $disciplina_id ); ?>
				
			</section>

			<div class="col-md-12 divesp"></div>

			<section class="col-md-12">

				<?php $m_objetivo->buttonNovoSlide( $disciplina_id ); ?>
				
			</section>

			<!-- <div class="col-md-12 divesp"></div> -->

			<section class="col-md-12" id="objetivo">

				<h3 style="font-weight:bold;"><?=$disciplina;?></h3>

				<?php $m_objetivo->showListSlides( $disciplina_id ); ?>
				
			</section> <!-- #objetivo -->	


		</div> <!-- .corpoPage -->

	</div> <!-- .panel_main_page -->

<?php include_once __DIR__ . "/footer.php"; ?>
