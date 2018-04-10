<?php
include_once __DIR__ . "/headHome.php";

$m_dominio = new Dominio();
$dominios = $m_dominio->getDominiosUsuario();

if( empty( $dominios ) ){

	$m_session->setValue( 'mensagem', 'Não existem domínio cadastrados.' );
	header("location: acessonegado.php");
	exit();

}else{

	if( count( $dominios ) == 1 ){

		header("location: listObjetivos.php?dmn=" . Dominio::$instances[0]->__GET('id') );
		exit();
	}
}
?>

<div class="row panel_main_page">

	<div class="col-md-12 panel_conteudo">

		<?php foreach ( $dominios as $dominio ) { ?>
			
			<div class="col-md-4 col-sm-6 col-xs-12 packshot">
				<a href="listObjetivos.php?dmn=<?= $dominio->__get( 'id' ); ?>">
					<div class="link_dominio" id="<?= $dominio->__get( 'id' ); ?>">
						<img src="../dominio/<?php echo $dominio->__get( 'diretorio' ); ?>/logo.png" class="img-responsive img-dominio" >
						<h3 class="label-dominio"><?= $dominio->__get( 'nome' ); ?></h3>
					</div>
				</a>
			</div>

		<? } ?>

	</div><!-- .panel_conteudo -->

</div><!-- .panel_main_page -->

<?php include_once __DIR__ . "/footer.php"; ?>

