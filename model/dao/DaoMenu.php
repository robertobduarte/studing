<?php

class DaoMenu extends IDao{

	public function __construct(){

		parent::__construct();

	}

	public function inserir( IObject $objeto ){}
	public function editar( Iobject $objeto ){}
	public function buscar( $id ){}
	public function listar(){}


	
	public function listMenu( $perfil, $dominio = false ) { 

		$dominio = ( $dominio )? 'S' : 'N';

        try {
 
			if( $perfil == 'ADM' ){

				$sql = "SELECT DISTINCT(m.id), m.* FROM menu as m			
						WHERE m.pai is null 					
						AND m.dominio = :dominio 
						ORDER BY m.ordem";

				$query = $this->conex->prepare( $sql );
				$query->bindParam( ':dominio', $dominio ); 

			}else{

				$sql = "SELECT DISTINCT(m.id), m.* FROM menu as m
						INNER JOIN menu_perfil as mp on (mp.menu = m.id)			
						WHERE m.pai is null 					
						AND m.dominio = :dominio and
						IF( m.dominio = 'S', mp.perfil = :perfil, mp.perfil <> '' )
						ORDER BY m.ordem";

				$query = $this->conex->prepare( $sql );
				$query->bindParam( ':perfil', trim( $perfil ) );
				$query->bindParam( ':dominio', $dominio ); 
			}
           
			$query->execute(); 
            

            $menus = array();
			while ( $menu = $query->fetch( PDO::FETCH_ASSOC ) ){
				
				$submenus = $this->listSubMenus( $menu['id'], trim( $perfil ) );
				$menu['submenus'] = $submenus;

				$menus[] = $menu;
            }
            
			return $menus;

        } catch (Exception $e) {
            $this->conex->rollback();
            print_r( $this->conex->errorInfo() );
			//$this->conex->falha( $this->conex->errorInfo() );
        }
    }	

	
	private function listSubMenus( $pai, $perfil = null ) { 

      	try {
          	
          	if( $perfil == 'ADM' ){

				$sql = "SELECT * FROM menu m
          			WHERE m.pai = :pai
          			ORDER BY m.ordem";

				$query = $this->conex->prepare($sql);
				$query->bindParam( ':pai', $pai );

			}else{

				$sql = "SELECT * FROM menu m
          			INNER JOIN menu_perfil as mp on (mp.menu = m.id)
          			WHERE m.pai = :pai 
          			AND mp.perfil = :perfil 
          			ORDER BY m.ordem";

				$query = $this->conex->prepare($sql);
				$query->bindParam( ':pai', $pai );
				$query->bindParam( ':perfil', trim($perfil) );
			}


            $query->execute(); 
            
			$submenus = array();
			while ($submenu = $query->fetch(PDO::FETCH_ASSOC)){

				$submenus[] = $submenu;				
            }

			return $submenus;

        } catch (Exception $e) {
            $this->conex->rollback();
			$query->falha( $e );
        }
    }
	
}


?>