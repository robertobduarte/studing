<?php

class ErrorControl extends Exception {
    private $adminMail = '';
    private $erro_numero = 0;
    protected $mensagem = '';
    protected $arquivo = '';
    protected $linha = 0;
    protected $arr_contexto = array();
        

    function __construct( $perro_numero = null, $pmensagem = null, $parquivo = null, $plinha = null, $pcontexto = null ){
            
        $this->erro_numero = $perro_numero;
        $this->mensagem = $pmensagem;
        $this->arquivo = $parquivo;
        $this->linha = $plinha;
        $this->arr_contexto = $pcontexto;

    }
    

    public static function errorAction( $errno, $errstr, $errfile, $errline, $errcontext ){
        // criando uma instancia propria deste objeto...
        $self = new self( $errno, $errstr, $errfile, $errline, $errcontext );

        switch($errno){

            case E_USER_NOTICE: 
            	return $self->writeError();
            	break;

            case E_NOTICE:
                return $self->writeError();
                break;

            case E_USER_ERROR:
            	return $self->writeError();
            	break;

            case E_ERROR:
            	return $self->writeError();
                break;

            case E_USER_WARNING:
            	return $self->writeError();
            	break;

            case E_WARNING:
            	return $self->writeError();
                break;

            default:
            	return $self->writeError();
            	break;                    
        }
    }
        

    public function writeError(){
            
        $hoje = date('d/m/Y H:i:s');

        error_log( 
            "\n\n ======== $hoje =========" .
            "\n Erro no arquivo : " . $this->arquivo.
            "\n Linha:      " . $this->linha .
            "\n Mensagem:   " . $this->mensagem .
            "\n Error cod. :   " . $this->erro_numero
            , $this->erro_numero
        );
    }


} // fim da classe:  ErrorControl

?>