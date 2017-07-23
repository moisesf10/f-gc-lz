<?php


namespace Gauchacred\controller;

use Gauchacred\library\php\Blowfish as Blowfish;
use Gauchacred\controller\Controller as Controller;
use Gauchacred\library\php\Utils as Utils;
use Gauchacred\model\Usuario as Usuario;
use Gauchacred\library\php\ErrorCode as ErrorCode;

/**
 * @author moisés
 * @version 1.0
 * @created 23-Jul-2016 13:30
 */
class ControleAcesso extends Controller
{

	
    function __construct()
	{
        parent::__construct();
        
	}



	/**
	 * Executado caso nenhum ação tenha sido passada para o controlador
	 * @access public
	 * @abstract
	 * @return void
	 */
	public function actionDefault()
	{
	}

	public function autenticar()
	{
        
                
        $login = (isset(\Application::getRequest()['login'])) ? \Application::getRequest()['login'] : null;
        $password = (isset(\Application::getRequest()['password'])) ? \Application::getRequest()['password'] : null;
        
        
        if ($login === null || $password === null)
        {
            $this->setErrorCode(3);
            $this->setErrorCause('Usuário ou senha inválido');
        }else
        {
            
            $usuario = new Usuario();

            if (filter_var($login, FILTER_VALIDATE_EMAIL) )
            {
                $email = $login;
                $cpf = null;
                
            }else
            {
                $cpf = $login;
                if ($cpf !== null)
                    $cpf = Utils::formatCpfHowString($cpf);
                $email = null;
            }
           
            $userAutenticate = $usuario->autenticar($cpf, $email);
            
           
            // compara com a senha do banco
             if ($userAutenticate !== false)
                $validUser = Blowfish::compare($password, $userAutenticate['senha']);
            else
                $validUser = false;
         
            if ($validUser === false)
            {
                $this->setErrorCode(3);
                $this->setErrorCause('Usuário ou senha inválido');
                if (isset($_SESSION['token']))
                    unset($_SESSION['token']);
                if (isset($_SESSION['nome']))
                    unset($_SESSION['nome']);
                if (isset($_SESSION['userid']))
                    unset($_SESSION['userid']);
                
                //session_destroy();
                $_SESSION['failedautenticate'] = true;
                header('Location: '. \Application::getUrlSite());
            }else
            {
                //cria Token
                $token = Blowfish::crypt(md5($login));
                
                // cria sessão
                $_SESSION['token'] = Blowfish::crypt(md5($login));
                $_SESSION['email'] = $userAutenticate['email'];
                $_SESSION['login'] = $login;
                $_SESSION['userid'] = $userAutenticate['id'];
                $_SESSION['nome'] = $userAutenticate['nome'];
                $_SESSION['failedautenticate'] = false;
                // Ajuda a evitar roubo de sessão
                $_SESSION['remote_addr'] = $_SERVER['REMOTE_ADDR'];
                
                header('Location: '. \Application::getUrlSite(). '/home' );
            }
        }
        
        
         exit;
        
        
       
    }

	
    
    public function logout()
	{
            unset($_SESSION['token']);
            unset($_SESSION['login']);
            unset($_SESSION['userid']);
            unset($_SESSION['email']);
            unset($_SESSION['nome']);
            unset($_SESSION['remote_addr']);
            unset($_SESSION['failedautenticate']);
        
            session_destroy();
  
           header('Location: '. \Application::getUrlSite() );
            //$this->setParams(true);
			//$this->showContents();
        
        exit;
	}
    
    

	public function printSession()
    {
        echo "Sessão valida: ";
        var_dump($this->isValidSession());
        echo  "<br />";
        echo "<pre>"; print_r($_SESSION); echo "</pre><br /><br />";
        exit;
        
    }

		

}
?>