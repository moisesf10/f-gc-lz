<?php

namespace Gauchacred\controller;



//use \controller\Controller as Controller;
use Gauchacred\library\php\Blowfish as Blowfish;
use Gauchacred\model\Usuario as Usuario;
/**
 * @author moises
 * @version 1.0
 * @created 13-set-2015 23:08:59
 */
class Usuarios extends Controller
{

	
    function __construct()
	{
        parent::__construct();
        //print_r($password = Blowfish::crypt('froodo')); exit;
	}



	/**
	 * Executado caso nenhum ação tenha sido passada para o controlador
	 * @access public
	 * @abstract
	 * @return void
	 */
	public function ActionDefault()
	{
        $this->setView('usuarios/index');

        $this->showContents();
	}
    
    
    public function novoUsuario()
    {
        
        
        $this->setView('usuarios/cadastro_usuario');

        $this->showContents();
    }
    
    // *********************************************
    // ALTERAR SENHA
    //
    
    public function alterarSenha()
    {
                  
        $this->setView('alterarsenha/index');
        
       // $cliente = new Cliente();
       // $result = $cliente->clientesNaoAgendados();
       // if ($result !== false)
       //     $this->setParams('clientesnaoagendados', $result);
        
        $this->showContents();
        
    }
    
    public function salvarAlteracaoSenha()
    {
        $senhaAtual = $_REQUEST['senhaatual'];
        $senhaNova = Blowfish::crypt($_REQUEST['senhanova']);
        
        $usuario = new Usuario();
        $result = $usuario->listarUsuarios($_SESSION['userid']);
        //var_dump($senhaAtual); var_dump($result);
        
        if ($result === false)
        {
            echo json_encode(array('success' => false, 'message' => 'Não foi possível concluir a operação. Contate o administrador'));
            exit;
        }
        
        if (! Blowfish::compare($senhaAtual, $result[0]['senha'] ))
        {
            echo json_encode(array('success' => false, 'message' => 'A senha atual é inválida'));
            exit;
        }
        
        $usuario->alterarSenha($_SESSION['userid'], $senhaNova);
        if ($result === false)
        {
            echo json_encode(array('success' => false, 'message' => 'Operação abortada. Contate o administrador'));
            exit;
        }else
        {
            echo json_encode(array('success' => true, 'message' => 'Senha alterada com sucesso'));
            exit;
        }
        
        
        
    }

	

}

?>