<?php

namespace Gauchacred\controller;



//use \controller\Controller as Controller;
//use \library\php\Blowfish as Blowfish;

use Gauchacred\library\php\Utils as Utils;
use Gauchacred\model\TelefoneUtil as TelefoneUtil;
use Gauchacred\model\Banco as Banco;
use Gauchacred\model\Promotora as Promotora;
use Gauchacred\model\SenhaBanco as SenhaBanco;

/**
 * @author moises
 * @version 1.0
 * @created 13-set-2015 23:08:59
 */
class Cadastrosbasicos extends Controller
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
        
     
	}
    
    public function telefonesUteis()
    {
        if (! \Application::isAuthorized('Cadastros Basicos' , 'telefones_uteis', 'ler')  
                    && ! \Application::isAuthorized('Cadastros Basicos' , 'telefones_uteis', 'escrever')
           )
            \Application::print404();
        
        $this->setView('telefonesuteis/index');
        
        $telefoneUtil = new TelefoneUtil();
        $result = $telefoneUtil->listarTelefones();
        if ($result !== false)
            $this->setParams('telefones', $result);
        
        
        $this->showContents();
        
    }
    
    
    public function pesquisarTelefoneUtil(){
        if (! \Application::isAuthorized('Cadastros Basicos' , 'telefones_uteis', 'ler')  
                    && ! \Application::isAuthorized('Cadastros Basicos' , 'telefones_uteis', 'escrever')
           )
        {
            echo json_encode(array('success'=>false, 'message'=>'Usuário sem permissão'));
            exit;
        }
        
        $nome = ($_REQUEST['nome'] != '')? $_REQUEST['nome'] : null;
        $limit = ($_REQUEST['limit'] != '') ? $_REQUEST['limit'] : 10000;
       
        $telefoneUtil = new TelefoneUtil();
        $result = $telefoneUtil->listarTelefones(null, $nome, null, $limit);
        
        if($result == false)
            $return = array();
        else
            $return = $result;
        
        echo json_encode($return);
        
    }
    
    public function cadastrarTelefoneUtil()
    {
        if (! \Application::isAuthorized('Cadastros Basicos' , 'telefones_uteis', 'ler')  
                    && ! \Application::isAuthorized('Cadastros Basicos' , 'telefones_uteis', 'escrever')
           )
            \Application::print404();
        
        $this->setView('telefonesuteis/cadastrar');
        
        
        $id = (\Application::getUrlParams(0) === null) ? '' : \Application::getUrlParams(0);
        
        $telefoneUtil = new TelefoneUtil();
        $result = $telefoneUtil->listarTelefones($id);
      
        if ($result !== false && count($result) > 0)
            $this->setParams('telefone', $result);
        
         $this->showContents();
        
    }
    
    public function salvarTelefoneUtil()
    {
        if (! \Application::isAuthorized('Cadastros Basicos' , 'telefones_uteis', 'escrever'))
        {
             $json['success'] = false;
             $json['message'] = 'Usuário sem autorização';
             echo json_encode($json);
            exit;
        }
        
       // $id = (! isset($_REQUEST['id']) || trim($_REQUEST['id']) == '') ? null : $_REQUEST['id'];
        //$nome = $_REQUEST['nome'];
        $observacao = $_REQUEST['observacao'];
        $dados = json_decode($_REQUEST['dados'], true);
        $dados['observacao'] = $observacao;
        
        $telefoneUtil = new TelefoneUtil();
        
        if (count($dados) < 1)
        {
             $json['success'] = false;
             $json['message'] = 'Parâmetros mal informados';
             echo json_encode($json);
            exit;
        }
        
      
        
       
        
        if (! isset($dados['id']))
            $result = $telefoneUtil->salvar( $dados);
        else
            $result = $telefoneUtil->salvar($dados, $dados['id']);
        
        
        if ($result === false)
        {
            $json['success'] = false;
            switch($telefoneUtil->getMysqlError())
            {
                case 1062 : $json['message'] = 'Não foi possível salvar o registro. Já existe um registro cadastrado para este código'; break;
                default: $json['message'] = 'Não foi possível salvar o registro. Codigo: '. $telefoneUtil->getMysqlError(); break;
            }
            
        }else
        {
             $json['success'] = true;
             $json['id'] = $result;
        }
        
        echo json_encode($json);
    }
    
    
    public function apagarTelefoneUtil()
    {
        if (! \Application::isAuthorized('Cadastros Basicos' , 'telefones_uteis', 'remover'))
        {
             $json['success'] = false;
             $json['message'] = 'Usuário sem autorização';
             echo json_encode($json);
            exit;
        }
        
        $id = trim($_REQUEST['id']);
        
        $telefoneUtil = new TelefoneUtil();
        
        $result = $telefoneUtil->excluir($id);
         
       
         
        if ($result === false)
        {
            $json['success'] = false;
            $json['message'] = 'Não foi possível remover o registro. '. $telefoneUtil->getMysqlError();
        }else if ($result === 1)
        {
            $json['success'] = false;
            $json['message'] = 'Não foi possível remover o registro. Este cadastro pertence a outro usuário';
        }else
        {
             $json['success'] = true;
             $json['id'] = $result;
        }
        
        echo json_encode($json);
        exit;
        
    }
    
    
     /*
    * Senhas Bancárias ************
    * ###################################################
    * */
    
    
    public function senhasBancarias()
    {
        if (! \Application::isAuthorized('Cadastros Basicos' , 'senhas_bancos', 'ler')  
                    && ! \Application::isAuthorized('Cadastros Basicos' , 'senhas_bancos', 'escrever')
           )
            \Application::print404();
        
        $this->setView('senhasbancarias/index');
        
         $senhaBanco = new SenhaBanco();
         $result = $senhaBanco->listarSenhasBancos();
      
        if ($result !== false && count($result) > 0)
            $this->setParams('senhabanco', $result);
        
        $banco = new Banco();
        $result = $banco->listarBancos();
        if ($result !== false)
            $this->setParams('bancos', $result);
        
        
        $this->showContents();
        
    }
    
    
    public function pesquisarSenhasBancarias()
    {
        if (! \Application::isAuthorized('Cadastros Basicos' , 'senhas_bancos', 'ler')  
                    && ! \Application::isAuthorized('Cadastros Basicos' , 'senhas_bancos', 'escrever')
           )
        {
            echo json_encode(array());
            exit;
        }
        
        $idBanco = (empty($_REQUEST['banco'])) ? null : $_REQUEST['banco'];
        $promotora = (empty($_REQUEST['promotora'])) ? null : $_REQUEST['promotora'];
        
        $senhaBanco = new SenhaBanco();
        $result = $senhaBanco->listarSenhasBancos(null, null,  1000000, 1, 'asc', $idBanco, $promotora);
        
        if (is_array($result))
            $json = $result;
        else
            $json = array();
        
        echo json_encode($json);
    }
    
    public function cadastrarSenhaBancaria()
    {
        if (! \Application::isAuthorized('Cadastros Basicos' , 'senhas_bancos', 'ler')  
                    && ! \Application::isAuthorized('Cadastros Basicos' , 'senhas_bancos', 'escrever')
           )
            \Application::print404();
        
        $this->setView('senhasbancarias/cadastrar');
        
        
        $id = (\Application::getUrlParams(0) === null) ? '' : \Application::getUrlParams(0);
        
        $senhaBanco = new SenhaBanco();
        $result = $senhaBanco->listarSenhasBancos($id);
        
       // var_dump($result); exit;
      
        if ($result !== false && count($result) > 0)
            $this->setParams('senhabanco', $result);
        
        $banco = new Banco();
        $result = $banco->listarBancos();
        if ($result !== false)
            $this->setParams('bancos', $result);
        
        //$promotora = new Promotora();
       // $result = $promotora->listarPromotoras();
       // if ($result !== false)
         //   $this->setParams('promotoras', $result);
        
        
         $this->showContents();
        
    }
    
    public function salvarSenhaBancaria()
    {
       
        if (! \Application::isAuthorized('Cadastros Basicos' , 'senhas_bancos', 'escrever'))
        {
             $json['success'] = false;
             $json['message'] = 'Usuário sem autorização';
             echo json_encode($json);
            exit;
        }
        
       // $id = (! isset($_REQUEST['id']) || trim($_REQUEST['id']) == '') ? null : $_REQUEST['id'];
        //$nome = $_REQUEST['nome'];
      
        $observacao = $_REQUEST['observacao'];
        $link = $_REQUEST['link'];
        
        $dados = json_decode($_REQUEST['dados'], true);
        $dados['observacao'] = $observacao;
        $dados['link'] = $link;
        
        $id = (isset($dados['id'])) ? $dados['id'] : null;
        
        
        if (count($dados) < 1)
        {
             $json['success'] = false;
             $json['message'] = 'Parâmetros mal informados';
             echo json_encode($json);
            exit;
        }
        
      
        
       
        
       $senhaBanco = new SenhaBanco();
        $result = $senhaBanco->salvar($dados, $id);
        
        
        if ($result === false)
        {
            $json['success'] = false;
            switch($senhaBanco->getMysqlError())
            {
                case 1062 : $json['message'] = 'Não foi possível salvar o registro. Já existe um registro cadastrado para este código'; break;
                default: $json['message'] = 'Não foi possível salvar o registro. Codigo: '. $senhaBanco->getMysqlError(); break;
            }
            
        }else
        {
             $json['success'] = true;
             $json['id'] = $result;
        }
        
        echo json_encode($json);
    }
    
    
    public function apagarSenhaBancaria()
    {
        if (! \Application::isAuthorized('Cadastros Basicos' , 'senhas_bancos', 'remover'))
        {
             $json['success'] = false;
             $json['message'] = 'Usuário sem autorização';
             echo json_encode($json);
            exit;
        }
        
        $id = trim($_REQUEST['id']);
        
        $senhaBanco = new SenhaBanco();
        
        $result = $senhaBanco->excluir($id);
         
       
         
        if ($result === false)
        {
            $json['success'] = false;
            $json['message'] = 'Não foi possível remover o registro. '. $senhaBanco->getMysqlError();
        }else 
        {
             $json['success'] = true;
            // $json['id'] = $result;
        }
        
        echo json_encode($json);
        exit;
        
    }
    
    
    
    
}