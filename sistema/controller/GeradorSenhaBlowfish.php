<?php

namespace Gauchacred\controller;



//use \controller\Controller as Controller;
use Gauchacred\library\php\Blowfish as Blowfish;
/**
 * @author moises
 * @version 1.0
 * @created 13-set-2015 23:08:59
 */
class GeradorSenhaBlowfish 
{

	
    function __construct()
	{
        //parent::__construct();
        //print_r($password = Blowfish::crypt('froodo')); exit;
	}



	/**
	 * Executado caso nenhum ação tenha sido passada para o controlador
	 * @access public
	 * @abstract
	 * @return void
	 */
	public function gerar()
	{
       if (! isset($_GET['senha']))
           echo '&Eacute; necess&aacute;rio informar a senha atrav&eacute;s do par&ecirc;metro GET "/?senha=Senha_que_deseja"';
        else
        {
            echo 'A senha <b>'. $_GET['senha'] . '</b> criptografada &eacute: '. Blowfish::crypt($_GET['senha']);
        }
        exit;
	}
    
    


	

}

?>