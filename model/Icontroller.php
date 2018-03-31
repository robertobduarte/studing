<?php

include_once __DIR__ . "/../config.php";

abstract class Icontroller {
	
    protected $m_autenticacao;
    protected $m_session;
    protected $destinoDefault;
    protected $mensagemDefault;
    protected $dados;
    protected $m_object;
    protected $object_id;

    public function __construct(){
        
        $this->m_session = new Session();

        $request = $_REQUEST;

        if( !empty( $request ) ){

            $dadosForm = $this->m_session->getValue( $this->name_session );

            if( !empty( $dadosForm ) ){

                foreach ( $dadosForm as $key => $value ) {
                    
                    $this->dados[ $key ] = $value;
                }
            }

            foreach ( $this->striptag( $request ) as $key => $value ) {
                    
                $this->dados[ $key ] = $value;
            }
        /*
        echo "<pre>";
        print_r($this->dados);
        echo "</pre>";
        exit();
        */
            
        } 

        $this->m_autenticacao = new Autenticacao();
        $this->m_autenticacao->checkAcess();

        $this->definePropriedades();
        $this->checkParams();
        $this->checkDadosType();
        $this->startAction();

    }

    abstract protected function definePropriedades();
    abstract protected function startAction();
    

    protected function checkParams(){

        if( empty( $this->dados['action'] ) ){

            $this->redirect( array( 'msg' => 'Parâmetros incorretos.' ) );

        }
    }


    protected function redirect( $dados ){

        $mensagem = ( !empty( $dados['msg'] ) )? $dados['msg'] : $this->mensagemDefault;

        $destino = ( !empty( $dados['dst'] ) )? $dados['dst'] : $this->destinoDefault;

        $this->m_autenticacao->redirectAcesso( array( 'mensagem' => $mensagem, 'destino' => $destino ) );

        exit();

    }

    /*
    Método chamado sempre que instanciado a classe
    */
    protected function striptag( $dados ){

        if( is_array( $dados ) ){

            foreach ($dados as $key => $value) {
                
                if( is_array( json_decode( $key, true ) ) ){

                    striptag( $key );
                }else{

                    $key = strip_tags( $value );
                }
            }
        }else{

            strip_tags( $dados );
        }

        return $dados;
    }


    protected function retornoAjax( $param ){

        $result = array();

        foreach ( $param as $key => $value ) {
            
            $result[$key] = $value;
        }

        $result['cod'] = ( !empty( $result['cod'] ) )? $result['cod'] : 0;
        $result['msg'] = ( !empty( $result['msg'] ) )? $result['msg'] : 'A requisição falhou devido a um erro.';


        $resposta = json_encode( $result );
        echo $resposta;
        exit();
    }


    
    protected function checkDadosType(){

        $errors = array();

        //if( $this->method != 'ajaxRequest' ){
        if( $this->dados['method'] != 'ajaxRequest' ){

            if ( !empty( $this->m_object->__get('tiposDeDados') ) ){

                $tiposDeDados = $this->m_object->__get('tiposDeDados');

                foreach ($tiposDeDados as $chave => $dado ) {

                    //verifica o tipo de dado
                    if ( array_key_exists( $chave, $this->dados ) && !empty( $this->dados[$chave] ) ){

                    switch ( $dado['type'] ) {

                        case 'int':

                            if ( !is_int( intval( $this->dados[$chave] ) ) ||  intval( $this->dados[$chave] ) == 0 ){

                                $errors[] = array( 'msg' => 'Valor invalido: ' . $chave . ' => ' . $this->dados[$chave] . ' = ' . $dado['type'] );

                            }else{

                                $this->dados[$chave] = intval( $this->dados[$chave] );
                            }

                            break;

                        case 'float':

                            if ( !is_float( floatval( $this->dados[$chave] ) ) ||  floatval( $this->dados[$chave] ) == 0  ){

                                $errors[] = array( 'msg' => 'Valor invalido: ' . $chave . ' => ' . $this->dados[$chave] . ' = ' . $dado['type'] );
                            
                            }else{

                                $this->dados[$chave] = floatval( $this->dados[$chave] );
                            }

                            break;
                        }

                    }

                    if ( $dado['mandatory'] ){

                        if ( !array_key_exists( $chave, $this->dados ) || empty( $this->dados[$chave] ) ){

                            $errors[] = array( 'msg' => 'Valor obrigatório não encontrado: ' . $chave . ' => ' . $this->dados[$chave] );
                        }
                    }

                    if ( $dado['size'] ){

                        if ( array_key_exists( $chave, $this->dados ) && !empty( $this->dados[$chave] ) ){

                            if( strlen( $this->dados[$chave] ) > $dado['size'] ){

                                $errors[] = array( 'msg' => 'Valor informado excede o limite máximo esperado: ' . $chave . ' => ' . $this->dados[$chave] );

                            }
                        }
                    }
                }
            }

            if( !empty( $errors ) ) {

                $mensagem = '';

                foreach ($errors as $erro ) {
                    
                    $mensagem .= $erro['msg'] . '<br>';

                }

                $this->redirect( array( 'msg' => $mensagem ) );

            }

        }        
    
    }


    protected function checaDados(){

        foreach ( $this->dados as $key => $value ) {
           
            if( array_key_exists( $key, $this->m_object->__get('tiposDeDados') ) ){


            }

        }
    }
}
?>