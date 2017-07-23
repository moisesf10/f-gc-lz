<?php
namespace controller;
use \library\php\ErrorCode as ErrorCode;
use \library\php\Blowfish as Blowfish;

/** 
* Classe abstrada. Serve de modelo para o controlador
* 
* @author Moisés Ferreira  
* @version 1.0 
* @copyright GAUCHACRED © 2016. 
* @access public 
* @package gauchacred
* @subpackage Aplicação
* @abstract
*/ 




abstract class Controller
{
		/** 
    * Armazena o conteúdo HTML a ser renderizado.
    * @access protected 
    * @name $st_contents 
    */ 
    protected $st_contents = null;
      
    /** 
    * Armazena o nome do arquivo de visualização. (arquivo a ser chamado em VIEW)
    * @access protected 
    * @name $st_view 
    */ 
    protected $st_view = null;
    
      
    /** 
    * Armazena dados para serem utilizados na view. (Por exemplo os dados consultados no Banco de Dados)
    * @access protected 
    * @name $v_params 
    */ 
    protected $v_params = array();
    
    
    /** 
    * Indica se existe uma sessão válida para o usuário
    * @access private 
    * @name $validSession
    */ 
    private $validSession = false;
    
    
     /** 
    * Indica se ocorreu algum erro
    * @access private 
    * @name $errorCode
    */ 
    
    private $errorCode = null;
     /** 
    * Identifica qual a causa do erro em errorCode
    * @access private 
    * @name $errorCause
    */ 
    private $errorCause = null;
    
    
    private $jsList = array();
    
    private $cssList = array();
    
    private $headerInclude = true;
    
    private $footerInclude = true;
    
    /** 
    * Construtor padrão
    * @access public 
    * @abstract
    * @return void 
    */ 
    
    function __construct()
	{ 
        
        //verifica se controladores e metodos existem
        
        
       
        if (! $this->is_child_valid_method())
        {
            //retorna um erro HTTP 404
            //http_response_code(404);
            header("HTTP/1.1 404 Bad Request");
            exit;
        }
        
        if (isset($_SESSION['token'])  && $_SESSION['token'] != '')
        {
            if (! $this->confirmLoginFromSession())
            {
                if (\Application::getNameAction() != 'autenticar' && \Application::getNameAction() != 'criarConta')
                {
                    if (\Application::isRequestWs())
                    {
                        $this->setErrorCode(ErrorCode::getCodeError('Cliente Não Autenticado'));
                        $this->setErrorCause('O cliente não está autenticado ou a sessão expirou');
                        $this->showContents();
                    }else
                    {
                        // Para browser
                        // Redirecionar para a página de autenticação
                        $_SESSION['failedautenticate'] = false;
                        header('Location: '. \Application::getUrlSite() );
                        exit;
                    }
                }
            }else
                if (\Application::getNameController() == 'Index' && \Application::getNameAction() == 'actionDefault')
                    header('Location: '. \Application::getUrlSite(). '/home' );
            
            
            // se chegar aqui continua automaticamente o fluxo normal da pagina indicando que o usuário está autenticado
        }else
        {
            if (
                       (\Application::getNameController() == 'ControleAcesso' &&  \Application::getNameAction() != 'autenticar' )
               )
            {
                if (\Application::isRequestWs())
                {   
                    $this->setErrorCode(ErrorCode::getCodeError('Cliente Não Autenticado'));
                    $this->setErrorCause('O cliente não está autenticado ou a sessão expirou');
                    $this->showContents();
                }else
                {
                    // Para browser
                    // Redirecionar para a página de autenticação
                    $_SESSION['failedautenticate'] = true;
                    header('Location: '. \Application::getUrlSite() );
                    exit;
                }
            }
        }
             
            
        
	}
    
    
    // verifica se existe o metodo desejado na classe filha, retorna um booleano 
    private function is_child_valid_method() {  

        $backtrace = debug_backtrace();  
        $childClass = get_class($backtrace[0]['object']);  
        
        
         
        return method_exists($childClass, \Application::getNameAction());

    }
    
    
    
    
    /** 
    * Verifica se o token presente na sessão é um token válido
    * @access public 
    * @final
    * @return boolean Retorna true se for válido ou false caso contrário 
    */ 
    
    private final function confirmLoginFromSession()
    {
       
        $this->validSession = false;
        
        $token = $_SESSION['token'];
        $login = $_SESSION['login'];
        if ($_SESSION['remote_addr'] == $_SERVER['REMOTE_ADDR'])
        {
            if (Blowfish::compare(md5($login), $token))
                $this->validSession = true;
        }
            
        return $this->validSession;
    }
    
    
    
    /** 
    * Executado caso nenhum ação tenha sido passada para o controlador
    * @access public 
    * @abstract
    * @return void 
    */ 
	public abstract function  ActionDefault();
    
    
    protected final function isValidSession()
    {
        return $this->validSession;
    }
    
    protected function addJs($name)
    {
        if (! empty($name))
            array_push($this->jsList, $name);
    }
    
    
    protected function addCss($name)
    {
        if (! empty($name))
            array_push($this->cssList, $name);
    }
    
    protected function removeJs($position)
    {
        unset($this->jsList[$position]);
    }
    
    protected function removeCss($position)
    {
        unset($this->cssList[$position]);
    }
    
    protected function getJs($position = null)
    {
        if ($position == null)
            return $this->jsList;
        else
            return $this->jsList[$position];
    }
    
    protected function getCss($position = null)
    {
        if ($position == null)
            return $this->cssList;
        else
            return $this->cssList[$position];
    }
    
    
    protected function setHeaderInclude($value)
    {
        $this->headerInclude = $value;
    }
    
    protected function setFooterInclude($value)
    {
        $this->footerInclude = $value;
    }
	
    
    
    /** 
    * Define o nome do arquivo HTML a ser renderizado (arquivo de view)
    * @access protected 
    * @final
    * @param string $st_view Nome do arquivo a ser renderizado
    * @return void 
    */ 
    protected final function  setView($st_view)
    {
        $this->st_view = $st_view;
   
    }
	
	
    
    
    /** 
    * Define os dados a serem utilizados pela view
    * @access protected 
    * @final
    * @param string $v_params Informação ou array de informações
    * @return void 
    */ 
    protected final function setParams($chave, $v_params)
    {
        if (! is_array($this->v_params))
            $this->v_params = array();
        $this->v_params[$chave] = $v_params; 
    }
    
    protected final function setErrorCode($errorCode)
    {
        $this->errorCode = $errorCode;   
    }
    
    
    
    protected final function setErrorCause($errorCause)
    {
        $this->errorCause = $errorCause;   
    }
	
 /** 
    * Retorna o nome do arquivo que será renderizado
    * @access protected 
    * @final
    * @return String 
    */ 
    protected final function getView()
    {
        return $this->st_view;
    }
    
   
    
    
    /** 
    * Retorna os dados a serem utilizados pela view
    * @access protected 
    * @final
    * @param string $position OPCIONAL. Posição do array a ser retornada
    * @return String ou array 
    */ 
    protected final function getParams( $position = null)
    {
                   
        if (! is_array($this->v_params))
            $this->v_params = array();
        if ($position === null)
            return $this->v_params;
        else
            if (array_key_exists($position, $this->v_params))
                return $this->v_params[$position];
            else
                return null;

    }
      
 /** 
    * Retorna string contendo o HTML de visualização
    * @access protected 
    * @final
    * @return String
    */ 
    protected final function getContents()
    {
          
			ob_start();
			if(isset($this->st_view))
			{
                if ($this->headerInclude)
                    require_once  \Application::getIndexPath(). DS. 'view'. DS . 'head.php';
                
                if ( file_exists(\Application::getIndexPath(). DS. 'view'. DS . $this->st_view. '.php')) 
				    require_once \Application::getIndexPath(). DS.'view'. DS . $this->st_view . '.php';
				if ($this->footerInclude)
                    require_once  \Application::getIndexPath(). DS. 'view'. DS . 'footer.php';
			}
			
			$this->st_contents = ob_get_contents();
			ob_end_clean();
			return $this->st_contents; 
    }
      
 /** 
    * Escreve a saída HTML de visualização
    * @access protected 
    * @final
    * @return String
    */ 
    protected final function showContents()
    {
  
        
		if (\Application::isRequestWs())
		{
			if ($this->errorCode !== null)
            {
                $response['ErrorCode'] = $this->errorCode;
                $response['ErrorException'] = ErrorCode::getMessageError($this->errorCode);   
                $response['ErrorCause'] = $this->errorCause;
            }else
            {
                $response['ErrorCode'] = '';
                $response['ErrorException'] = '';   
                $response['ErrorCause'] = '';
                $response['Message'] = $this->getParams();
            }
                
            
            echo json_encode($response);	
            exit;
		}else
		{
			
            if(! file_exists('view'. DS . $this->st_view . '.php'))
                throw new \Exception("Arquivo \\view". DS . $this->st_view . ".php nao existe em view");     
            else
        	   echo $this->getContents();
		}
		
    }	
}

?>