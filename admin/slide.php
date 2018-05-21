<?php
include_once __DIR__ . "/headDominio.php"; //página instancia um dominio - $m_dominio


$slide_id = ( isset( $_REQUEST['sld'] ) )? $_REQUEST['sld'] : '';
$objetivo_id = ( isset( $_REQUEST['obj'] ) )? $_REQUEST['obj'] : '';
$disciplina_id = ( isset( $_REQUEST['disc'] ) )? $_REQUEST['disc'] : '';

if( ( empty( $slide_id ) ) && ( empty( $objetivo_id ) || empty( $disciplina_id ) ) ){
	$m_session->setValue( 'Parâmetros incorretos' );
	header("location: acessonegado.php");
	exit();	
}

if( !empty( $slide_id) ){

	$m_slide = new Slide( array( 'id' => $slide_id ) );	
	$m_objetivo = new Objetivo( array( 'id' => $m_slide->__get('objetivo') ) );
	//$m_slide->getAlternativas();	

}else{

	$m_slide = new Slide();
	$m_slide->__set( 'Slide', array( 'disciplina' => $disciplina_id ) );
	$m_slide->__set( 'Slide', array( 'objetivo' => $objetivo_id ) );
	$m_objetivo = new Objetivo( array( 'id' => $objetivo_id ) );
}

/*echo "<pre>";
print_r($m_slide);
echo "</pre>";*/
?>

<script src="js/slide.js?v=<?= filemtime('js/slide.js'); ?>"></script>
<script src="js/tinymce/js/tinymce/tinymce.min.js"></script>

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

	<div class="col-md-12 tituloPage">
		<h4>SLIDE</h4>
	</div>

	<div class="col-md-12 tituloAcaPagina">
		<span><?php $m_slide->showLink( $m_dominio->__get('id') );?></span>
	</div>

	<div class="col-md-12 corpoPage">

		<section class="col-md-12" id="slide">

			<?php $m_slide->showFormulario( $m_objetivo, $m_session, $m_autenticacao ); ?>

			<div class="col-md-12" id="mensagem"></div>

		</section> <!-- #slide -->

		<div class="col-md-12 divesp"></div>

		<section class="col-md-12 divDestaqueClara" id="alternativas">


			<?php $m_slide->showAlternartivas( $m_session, $m_autenticacao ); ?>

			<div class="col-md-12" id="mensagem"></div>

		</section> <!-- #alternativas -->

	</div>

</div> <!-- .panel_main_page -->


<?php include_once __DIR__ . "/footer.php"; ?>