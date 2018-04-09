<?php

$menus = $m_autenticacao->listMenu( true);


$menuSelected = $m_session->getValue( 'menu', true );

/*echo '<pre>';
echo $m_session->getValue('perfil');
print_r($menus);
echo '</pre>';
*/
?>
			
<div class="menu_lateral">	

	<div class="logoMenuLateral">
		<a href="listObjetivos.php?dmn=<?=$m_dominio->__get('id')?>" class="" >			
			<img src="img/logo.png" class="img-responsive" >
		</a>
	</div>

	<?php foreach($menus as $menu){ 

		$class = ( $menuSelected == $menu['id'] )? 'menuSelected' : '';
	?>
		<a href="<?= $menu['caminho']; ?>?dmn=<?= $m_dominio->__get( 'id' ); ?>" class="link_menu" ><div class="listMenu <?= $class ?>"><?= $menu['label']; ?></div></a> 
				
	<?php }	?>	

</div>			
			
				





