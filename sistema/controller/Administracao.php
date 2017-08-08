<?php

namespace Gauchacred\controller;



//use \controller\Controller as Controller;
//use \library\php\Blowfish as Blowfish;
use Gauchacred\library\php\Utils as Utils;
use Gauchacred\model\Banco as Banco;
use Gauchacred\model\Perfil as Perfil;
use Gauchacred\model\Recurso as Recurso;
use Gauchacred\model\Entidade as Entidade;
use Gauchacred\model\Roteiro as Roteiro;
use Gauchacred\model\GrupoUsuario as GrupoUsuario;
use Gauchacred\model\Usuario as Usuario;
use Gauchacred\model\Tabela as Tabela;
use Gauchacred\model\Operacao as Operacao;
use Gauchacred\model\Subtabela as Subtabela;
use Gauchacred\model\OperacaoSubtabela as OperacaoSubtabela;
use Gauchacred\model\Meta as Meta;
use Gauchacred\model\Cliente as Cliente;
use Gauchacred\model\SubstatusContrato;
use Gauchacred\model\DespesasPagar;
/**
 * @author moises
 * @version 1.0
 * @created 13-set-2015 23:08:59
 */
class Administracao extends Controller
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
	
    
    /*
    * Roteiros ************
    * ###################################################
    * */
    
    
    public function listarRoteiro()
    {
        if (! \Application::isAuthorized(ucfirst(strtolower(\Application::getNameController())) , 'roteiros', 'ler')  
                    && ! \Application::isAuthorized(ucfirst(strtolower(\Application::getNameController())) , 'roteiros', 'escrever')
           )
            \Application::print404();
        
        $this->setView('roteiro/index');
        
        
         $banco = new Banco();
        $result = $banco->listarBancos();
        if ($result !== false)
            $this->setParams('bancos', $result);
        
         $entidade = new Entidade();
         $result = $entidade->listarEntidades();
        if ($result !== false)
            $this->setParams('entidades', $result);
        
        
         $this->showContents();
        
    }
    
     public function pesquisarRoteiro()
    {
        if (! \Application::isAuthorized(ucfirst(strtolower(\Application::getNameController())) , 'roteiros', 'ler')  
                    && ! \Application::isAuthorized(ucfirst(strtolower(\Application::getNameController())) , 'roteiros', 'escrever')
           )
        {
             $json['success'] = false;
             $json['message'] = 'Usuário sem autorização';
             echo json_encode($json);
            exit;
        }
        
         // verifica se foi passado paramêtros
         if (\Application::getUrlParams(0) !== null)
             $idBanco = \Application::getUrlParams(0);
         else
             if (isset($_REQUEST['idBanco']))
                 $idBanco = (trim($_REQUEST['idBanco']) == '') ? '%' : $_REQUEST['idBanco'];
             else
                 $idBanco = '%';
         
         // verifica se foi passado paramêtros
         if (\Application::getUrlParams(1) !== null)
             $idEntidade = \Application::getUrlParams(1);
         else
             if (isset($_REQUEST['idEntidade']))
                 $idEntidade = (trim($_REQUEST['idEntidade']) == '') ? '%' : $_REQUEST['idEntidade'];
             else
                 $idEntidade = '%';
         
         // verifica se foi passado paramêtros
         if (\Application::getUrlParams(2) !== null)
             $limit = \Application::getUrlParams(2);
         else
             if (isset($_REQUEST['limit']))
                 $limit = (trim($_REQUEST['limit']) == '') ? '%' : $_REQUEST['limit'];
             else
                 $limit = 100000;
       
         
          $roteiro = new Roteiro();
         $result = $roteiro->listarRoteiros('%', $idBanco, $idEntidade, $limit);
        if (is_array($result))
            $return['data'] = $result;
         else
             $return['data'] = array();
        
            echo json_encode($return);
         exit;
        
    }
    
    public function cadastrarRoteiro()
	{
        
        if (! \Application::isAuthorized(ucfirst(strtolower(\Application::getNameController())) , 'roteiros', 'ler')  
                    && ! \Application::isAuthorized(ucfirst(strtolower(\Application::getNameController())) , 'roteiros', 'escrever')
           )
            \Application::print404();
        
        $this->setView('roteiro/cadastrar');
      
        
         $banco = new Banco();
        $result = $banco->listarBancos();
        if ($result !== false)
            $this->setParams('bancos', $result);
        
         $entidade = new Entidade();
         $result = $entidade->listarEntidades();
        if ($result !== false)
            $this->setParams('entidades', $result);
        
        
        $id = (\Application::getUrlParams(0) === null) ? null : \Application::getUrlParams(0);
        
        $roteiro = new Roteiro();
         $result = $roteiro->listarRoteiros($id);
        if ($result !== false)
            $this->setParams('roteiro', $result);
        
        
      //  echo'moises';
        $this->showContents();
	}
    
    
    public function salvarRoteiro()
    {
        if (! \Application::isAuthorized(ucfirst(strtolower(\Application::getNameController())) , 'roteiros', 'escrever'))
        {
             $json['success'] = false;
             $json['message'] = 'Usuário sem autorização';
             echo json_encode($json);
            exit;
        }
        
        $id = trim($_REQUEST['id']);
        $banco = $_REQUEST['banco'];
        $entidade = $_REQUEST['entidade'];
        $descricao = urldecode($_REQUEST['descricao']);
        
        
        $roteiro = new Roteiro();
        if ($id !== '')
            $result = $roteiro->salvar($id, $banco, $entidade, $descricao);
        else
            $result = $roteiro->salvar(null, $banco, $entidade, $descricao);
        
        if ($result === false)
        {
            $json['success'] = false;
            $json['message'] = 'Não foi possível salvar o registro. Codigo: '. $roteiro->getMysqlError();
        }else
        {
             $json['success'] = true;
             $json['id'] = $result;
        }
        
        echo json_encode($json);
        exit;
        
    }
    
    
     public function apagarRoteiro()
    {
        if (! \Application::isAuthorized(ucfirst(strtolower(\Application::getNameController())) , 'roteiros', 'remover'))
        {
             $json['success'] = false;
             $json['message'] = 'Usuário sem autorização';
             echo json_encode($json);
            exit;
        }
        
        $id = trim($_REQUEST['id']);
        
        $roteiro = new Roteiro();
         
        $result = $roteiro->excluir($id);
         
        if ($result === false)
        {
            $json['success'] = false;
            $json['message'] = 'Não foi possível salvar o registro. Codigo: '. $roteiro->getMysqlError();
        }else
        {
             $json['success'] = true;
             $json['id'] = $result;
        }
        
        echo json_encode($json);
        exit;
        
    }
    
    
    /*
    * Grupos de Usuarios ************
    * ###################################################
    * */
    
    
    public function listarGrupos()
    {
        if (! \Application::isAuthorized(ucfirst(strtolower(\Application::getNameController())) , 'grupos', 'ler')  
                    && ! \Application::isAuthorized(ucfirst(strtolower(\Application::getNameController())) , 'grupos', 'escrever')
           )
            \Application::print404();
        
        $this->setView('grupos/index');
        
        
         $this->showContents();
        
    }
    
    public function cadastrarGrupoUsuario()
    {
        if (! \Application::isAuthorized(ucfirst(strtolower(\Application::getNameController())) , 'grupos', 'ler')  
                    && ! \Application::isAuthorized(ucfirst(strtolower(\Application::getNameController())) , 'grupos', 'escrever')
           )
            \Application::print404();
        
        $this->setView('grupos/cadastrar');
        
        
        $id = (\Application::getUrlParams(0) === null) ? null : \Application::getUrlParams(0);
        $grupo = new GrupoUsuario();
        $result = $grupo->listarGrupos($id);
        if ($result !== false)
            $this->setParams('grupo', $result);
        
         
         $this->showContents();
        
    }
    
    
    
    public function pesquisarGrupoUsuario()
    {
        if (! \Application::isAuthorized(ucfirst(strtolower(\Application::getNameController())) , 'roteiros', 'ler')  
                    && ! \Application::isAuthorized(ucfirst(strtolower(\Application::getNameController())) , 'roteiros', 'escrever')
           )
        {
             $json['success'] = false;
             $json['message'] = 'Usuário sem autorização';
             echo json_encode($json);
            exit;
        }
        
         // verifica se foi passado paramêtros
         if (\Application::getUrlParams(0) !== null)
             $id = \Application::getUrlParams(0);
         else
             if (isset($_REQUEST['id']))
                 $id = (trim($_REQUEST['id']) == '') ? '%' : $_REQUEST['id'];
             else
                 $id = '%';
        
        // verifica se foi passado paramêtros
         if (\Application::getUrlParams(1) !== null)
             $nome = \Application::getUrlParams(1);
         else
             if (isset($_REQUEST['nome']))
                 $nome = (trim($_REQUEST['nome']) == '') ? '%' : $_REQUEST['nome'];
             else
                 $nome = '%';
         
         
         // verifica se foi passado paramêtros
         if (\Application::getUrlParams(2) !== null)
             $limit = \Application::getUrlParams(2);
         else
             if (isset($_REQUEST['limit']))
                 $limit = (trim($_REQUEST['limit']) == '') ? '%' : $_REQUEST['limit'];
             else
                 $limit = 100000;
       
         
          $grupo = new GrupoUsuario();
          $result = $grupo->listarGrupos($id, $nome, $limit);
        if (is_array($result))
            $return['data'] = $result;
         else
             $return['data'] = array();
        
            echo json_encode($return);
         exit;
        
    }
    
    
    
    
    public function salvarGrupoUsuario()
    {
        if (! \Application::isAuthorized(ucfirst(strtolower(\Application::getNameController())) , 'grupos', 'escrever'))
        {
             $json['success'] = false;
             $json['message'] = 'Usuário sem autorização';
             echo json_encode($json);
            exit;
        }
        
        $id = (! isset($_REQUEST['id']) || trim($_REQUEST['id']) == '') ? null : $_REQUEST['id'];
        $nome = $_REQUEST['nome'];
        
        
        $grupo = new GrupoUsuario();
        $result = $grupo->salvar($id, $nome);
        
        if ($result === false)
        {
            $json['success'] = false;
            $json['message'] = 'Não foi possível salvar o registro. Codigo: '. $grupo->getMysqlError();
        }else
        {
             $json['success'] = true;
             $json['id'] = $result;
        }
        
        
        
        echo json_encode($json);
        exit;
    }

	 public function apagarGrupoUsuario()
    {
        if (! \Application::isAuthorized(ucfirst(strtolower(\Application::getNameController())) , 'grupos', 'remover'))
        {
             $json['success'] = false;
             $json['message'] = 'Usuário sem autorização';
             echo json_encode($json);
            exit;
        }
        
        $id = trim($_REQUEST['id']);
        
        $grupo = new GrupoUsuario();
         
        $result = $grupo->excluir($id);
         
        if ($result === false)
        {
            $json['success'] = false;
            $json['message'] = 'Não foi possível salvar o registro. Codigo: '. $grupo->getMysqlError();
        }else
        {
             $json['success'] = true;
             $json['id'] = $result;
        }
        
        echo json_encode($json);
        exit;
        
    }
    
    public function atribuirUsuarioGrupo()
    {
        if (! \Application::isAuthorized(ucfirst(strtolower(\Application::getNameController())) , 'grupos', 'ler')  
                    && ! \Application::isAuthorized(ucfirst(strtolower(\Application::getNameController())) , 'grupos', 'escrever')
           )
            \Application::print404();
        
        $this->setView('grupos/atribuir_usuarios');
        
        
        $id = (\Application::getUrlParams(0) === null) ? null : \Application::getUrlParams(0);
        
        $grupo = new GrupoUsuario();
        
        $result = $grupo->listarGrupos($id);
        if ($result !== false)
            $this->setParams('grupo', $result);
        else
            \Application::print404();
        
        
        
        
        $result = $grupo->listarAtribuicoes($id);
        if ($result !== false)
            $this->setParams('atribuicoes', $result);
        else
            \Application::print404();
        
        
        
        
        $this->showContents();
        
    }
    
    
    public function buscarAtribuicaoUsuario()
    {
        
        if (! \Application::isAuthorized(ucfirst(strtolower(\Application::getNameController())) , 'grupos', 'ler')  
                    && ! \Application::isAuthorized(ucfirst(strtolower(\Application::getNameController())) , 'grupos', 'escrever')
           )
        {
             $json['success'] = false;
             $json['message'] = 'Usuário sem autorização';
             echo json_encode($json);
            exit;
        }
        
        // verifica se foi passado paramêtros
         if (\Application::getUrlParams(0) !== null)
             $id = \Application::getUrlParams(0);
         else
             if (isset($_REQUEST['id']))
                 $id = (trim($_REQUEST['id']) == '') ? '%' : $_REQUEST['id'];
             else
             {
                 $json['success'] = false;
                 $json['message'] = 'Parâmetros Incorretos. Contate o administrador';
                 echo json_encode($json);
            exit;
             }
        
         
        
        $result = $grupo->listarAtribuicoes($id);
        if (is_array($result))
            $return['data'] = $result;
         else
             $return['data'] = array();
        
            echo json_encode($return);
        
        
    }
    
    
    public function buscarUsuarioGrupo()
    {
        
        if (! \Application::isAuthorized(ucfirst(strtolower(\Application::getNameController())) , 'grupos', 'ler')  
                    && ! \Application::isAuthorized(ucfirst(strtolower(\Application::getNameController())) , 'grupos', 'escrever')
           )
        {
             $json['success'] = false;
             $json['message'] = 'Usuário sem autorização';
             echo json_encode($json);
            exit;
        }
        
        $str = $_GET['nome'];
    
        $usuario = new Usuario();
        
         
        $result = $usuario->listarUsuarios(null,null, $str);
        $return['suggestions'] = array();
        if (is_array($result))
        {
            foreach($result as $i => $value)
            {
                unset($value['senha']);
                array_push($return['suggestions'],
                     array('value' => $value['nome'], 'data' => $value));
            }
                
            
        }
         
        
        echo json_encode($return);
        exit;
    }
    
    
    public function salvarAtribuicaoGrupo()
    {
        if (! \Application::isAuthorized(ucfirst(strtolower(\Application::getNameController())) , 'grupos', 'ler')  
                    && ! \Application::isAuthorized(ucfirst(strtolower(\Application::getNameController())) , 'grupos', 'escrever')
           )
        {
             $json['success'] = false;
             $json['message'] = 'Usuário sem autorização';
             echo json_encode($json);
            exit;
        }
        
        // verifica se foi passado paramêtros
        $idGrupo = (isset($_REQUEST['grupo'])) ? $_REQUEST['grupo'] : null;
        $atribuicoes = (isset($_REQUEST['atribuicoes'])) ? json_decode($_REQUEST['atribuicoes'], true) : null;
        
        
         if ($idGrupo === null || $atribuicoes === null)
         {
             $json['success'] = false;
                 $json['message'] = 'Parâmetros Incorretos. Contate o administrador';
                 echo json_encode($json);
             exit;
         }
           
        $grupo = new GrupoUsuario();
        $result = $grupo->atribuirUsuarios($idGrupo, $atribuicoes);
        if ($result == true)
        {
             $json['success'] = true;
        }else
        {
             $json['success'] = false;
            switch($grupo->getMysqlError())
            {
                case 1062:   $json['message'] = 'O usuário já faz parte do grupo'; break;
                default: $json['message'] = 'Não foi possível gravar as informações. Contate o administrador. Erro: ' . $grupo->getMysqlError(); 
            }
            
             
        }
        
        echo json_encode($json);
        exit;
        
    }
    
    // *********************************************
    // TABELAS
    //
    
    
    public function tabelas()
    {
          if (! \Application::isAuthorized(ucfirst(strtolower(\Application::getNameController())) , 'tabelas', 'ler')  
                    && ! \Application::isAuthorized(ucfirst(strtolower(\Application::getNameController())) , 'tabelas', 'escrever')
           )
            \Application::print404();
        
        $this->setView('tabelas/index');
        
        $tabela = new Tabela();
        $result = $tabela->listarTabelas();
        if ($result !== false)
            $this->setParams('tabela', $result);
        else
            $this->setParams('tabela', null);
            
        $this->showContents();
        
    }
    
    
    public function cadastrarTabela()
    {
          if (! \Application::isAuthorized(ucfirst(strtolower(\Application::getNameController())) , 'tabelas', 'ler')  
                    && ! \Application::isAuthorized(ucfirst(strtolower(\Application::getNameController())) , 'tabelas', 'escrever')
           )
            \Application::print404();
        
        $this->setView('tabelas/cadastrar');
        
        if (\Application::getUrlParams(0) !== null)
             $idTabela = \Application::getUrlParams(0);
        else
            $idTabela = null;
        
        if ($idTabela !== null)
        {
            $tabela = new Tabela();
            $result = $tabela->listarTabelas($idTabela);
        }else
            $result = false;
        
        if ($result !== false && is_array($result) && count($result) > 0   )
            $this->setParams('tabela', $result);
        else
            $this->setParams('tabela', null);
        
        
         $banco = new Banco();
        $result = $banco->listarBancos();
        if ($result !== false)
            $this->setParams('bancos', $result);
        
         $entidade = new Entidade();
         $result = $entidade->listarEntidades();
        if ($result !== false)
            $this->setParams('convenios', $result);
            
        $this->showContents();
        
    }
    
     public function salvarTabela()
    {
        if (! \Application::isAuthorized(ucfirst(strtolower(\Application::getNameController())) , 'tabelas', 'escrever'))
        {
             $json['success'] = false;
             $json['message'] = 'Usuário sem autorização';
             echo json_encode($json);
            exit;
        }
        
        $id = (! isset($_REQUEST['id']) || trim($_REQUEST['id']) == '') ? null : $_REQUEST['id'];
        $banco = $_REQUEST['banco'];
        $convenio = $_REQUEST['convenio'];
        $nome = $_REQUEST['nome'];
        
        
        $tabela = new Tabela();
        $result = $tabela->salvar($nome, $banco, $convenio, $id);
        
        if ($result === false)
        {
            $json['success'] = false;
            $json['message'] = 'Não foi possível salvar o registro. Codigo: '. $tabela->getMysqlError();
        }else
        {
             $json['success'] = true;
             $json['id'] = $result;
        }
        
        
        
        echo json_encode($json);
        exit;
    }
    
     public function apagarTabela()
    {
        if (! \Application::isAuthorized(ucfirst(strtolower(\Application::getNameController())) , 'tabelas', 'remover'))
        {
             $json['success'] = false;
             $json['message'] = 'Usuário sem autorização';
             echo json_encode($json);
            exit;
        }
        
        $id = trim($_REQUEST['id']);
        
        $tabela = new Tabela();
         
        $result = $tabela->excluir($id);
         
        if ($result === false)
        {
            $json['success'] = false;
            $json['message'] = 'Não foi possível salvar o registro. Codigo: '. $tabela->getMysqlError();
        }else
        {
             $json['success'] = true;
             $json['id'] = $result;
        }
        
        echo json_encode($json);
        exit;
        
    }
    
    
    
    // *********************************************
    // SUB-TABELAS
    //
    
    public function subtabelas()
    {
          if (! \Application::isAuthorized(ucfirst(strtolower(\Application::getNameController())) , 'subtabelas', 'ler')  
                    && ! \Application::isAuthorized(ucfirst(strtolower(\Application::getNameController())) , 'subtabelas', 'escrever')
           )
            \Application::print404();
        
        $this->setView('subtabelas/index');
        
        $subtabela = new Subtabela();
        $result = $subtabela->listarSubtabelas();
        if ($result !== false)
            $this->setParams('subtabelas', $result);
        
        $entidade = new Entidade();
        $result = $entidade->listarEntidades();
        if ($result != false)
            $this->setParams('convenios', $result);
        
        $banco = new Banco();
        $result = $banco->listarBancos();
        if ($result != false)
            $this->setParams('bancos', $result);
        
       
            
        $this->showContents();
        
    }
    
    public function pesquisarSubtabela()
    {
        
        if (! \Application::isAuthorized(ucfirst(strtolower(\Application::getNameController())) , 'subtabelas', 'ler')  
                    && ! \Application::isAuthorized(ucfirst(strtolower(\Application::getNameController())) , 'subtabelas', 'escrever')
           )
        {
            echo json_encode(array());
            exit;
        }
        
        $banco = (! empty($_REQUEST['banco'])) ? $_REQUEST['banco'] : null;
        $convenio = (! empty($_REQUEST['convenio'])) ? $_REQUEST['convenio'] : null;
        $dataInicial = (empty($_POST['datainicial'])) ? '0001-01-01' : Utils::formatStringDate($_POST['datainicial'], 'd/m/Y', 'Y-m-d');
        $dataFinal = (empty($_POST['datafinal'])) ? '2100-01-01' : Utils::formatStringDate($_POST['datafinal'], 'd/m/Y', 'Y-m-d');
        
        
        $subtabela = new Subtabela();
        $result = $subtabela->listarSubtabelas(null, 1, 'desc', 100000, $convenio, $banco, $dataInicial, $dataFinal);
        
        if (! is_array($result))
            $json = array();
        else
            $json = $result;
        
        echo json_encode($json);
        
    }
    
    public function cadastrarSubtabela()
    {
          if (! \Application::isAuthorized(ucfirst(strtolower(\Application::getNameController())) , 'subtabelas', 'ler')  
                    && ! \Application::isAuthorized(ucfirst(strtolower(\Application::getNameController())) , 'subtabelas', 'escrever')
           )
            \Application::print404();
        
        $this->setView('subtabelas/cadastrar');
        
        if (\Application::getUrlParams(0) !== null)
             $idSubtabela = \Application::getUrlParams(0);
        else
            $idSubtabela = null;
        
        if ($idSubtabela !== null)
        {
            $subtabela = new Subtabela();
            $result = $subtabela->listarSubtabelas($idSubtabela);
        }else
            $result = false;
        
        if ($result !== false && is_array($result) && count($result) > 0   )
            $this->setParams('subtabela', $result);
        else
            $this->setParams('subtabela', null);
        
        
        
        $grupo = new GrupoUsuario();
        $result = $grupo->listarGrupos();
        if ($result !== false)
            $this->setParams('grupos', $result);
        
        
        $tabela = new Tabela();
        $result = $tabela->listarTabelas(null, null, null, null, 100000);
        if ($result !== false)
            $this->setParams('tabelacompleta', $result);

        
        
        $operacao = new Operacao();
        $result = $operacao->listarOperacoes(null, null, 100000);
        if ($result !== false)
            $this->setParams('operacao', $result);
        
       
            
        $this->showContents();
        
    }
    
     public function salvarSubtabela()
    {
        if (! \Application::isAuthorized(ucfirst(strtolower(\Application::getNameController())) , 'subtabelas', 'escrever'))
        {
             $json['success'] = false;
             $json['message'] = 'Usuário sem autorização';
             echo json_encode($json);
            exit;
        }
        
        $dados = ($_REQUEST['dados'] == '') ? null : json_decode($_REQUEST['dados'], true);
        
        // echo '<pre>'; print_r($dados); exit;
        
        $subtabela = new Subtabela();
        $result = $subtabela->salvar($dados);
        
        if ($result === false)
        {
            $json['success'] = false;
            $json['message'] = 'Não foi possível salvar o registro. Codigo: '. $subtabela->getMysqlError();
        }else
        {
             $json['success'] = true;
             $json['id'] = $result;
        }
        
        
      
        echo json_encode($json);
        exit;
    }
    
    
     public function apagarSubtabela()
    {
        if (! \Application::isAuthorized(ucfirst(strtolower(\Application::getNameController())) , 'subtabelas', 'remover'))
        {
             $json['success'] = false;
             $json['message'] = 'Usuário sem autorização';
             echo json_encode($json);
            exit;
        }
        
        $id = trim($_REQUEST['id']);
        
        $subtabela = new Subtabela();
         
        $result = $subtabela->excluir($id);
         
        if ($result === false)
        {
            $json['success'] = false;
            $json['message'] = 'Não foi possível salvar o registro. Codigo: '. $tabela->getMysqlError();
        }else
        {
             $json['success'] = true;
             $json['id'] = $result;
        }
        
        echo json_encode($json);
        exit;
        
    }
    
    
    
     public function visualizarSubtabela()
    {
          if (! \Application::isAuthorized(ucfirst(strtolower(\Application::getNameController())) , 'subtabelas', 'ler')  
                    && ! \Application::isAuthorized(ucfirst(strtolower(\Application::getNameController())) , 'subtabelas', 'escrever')
           )
            \Application::print404();
        
        $this->setView('subtabelas/visualizar');
         
          if (\Application::getUrlParams(0) !== null)
             $id = \Application::getUrlParams(0);
         else
             $id = null;
        
         if ($id == null)
             \application::print404();
         
         
        $subtabela = new Subtabela();
        $result = $subtabela->listarSubtabelas($id);
        if ($result !== false)
            $this->setParams('subtabela', $result);
        
       
            
        $this->showContents();
        
    }
    
    
    // *********************************************
    // CADASTRO DE BANCOS
    //
    
    public function bancos()
    {
          if (! \Application::isAuthorized(ucfirst(strtolower(\Application::getNameController())) , 'bancos', 'ler')  
                    && ! \Application::isAuthorized(ucfirst(strtolower(\Application::getNameController())) , 'bancos', 'escrever')
           )
            \Application::print404();
        
        $this->setView('bancos/index');
        
        $banco = new Banco();
        $result = $banco->listarBancos();
        if ($result !== false)
            $this->setParams('bancos', $result);
        
        $this->showContents();
        
    }
    
    public function cadastrarBanco()
    {
        if (! \Application::isAuthorized(ucfirst(strtolower(\Application::getNameController())) , 'bancos', 'ler')  
                    && ! \Application::isAuthorized(ucfirst(strtolower(\Application::getNameController())) , 'bancos', 'escrever')
           )
            \Application::print404();
        
        $this->setView('bancos/cadastrar');
        
        
        $id = (\Application::getUrlParams(0) === null) ? null : \Application::getUrlParams(0);
        $banco = new Banco();
        
        $result = $banco->listarBancos($id);
      
        if ($result !== false)
            $this->setParams('banco', $result);
        
         $this->showContents();
        
    }
    
    public function salvarBanco()
    {
        if (! \Application::isAuthorized(ucfirst(strtolower(\Application::getNameController())) , 'bancos', 'escrever'))
        {
             $json['success'] = false;
             $json['message'] = 'Usuário sem autorização';
             echo json_encode($json);
            exit;
        }
        
        $id = (! isset($_REQUEST['id']) || trim($_REQUEST['id']) == '') ? null : $_REQUEST['id'];
        $codigo = $_REQUEST['codigo'];
        $nome = $_REQUEST['nome'];
        $status = $_REQUEST['status'];
        
        
        $banco = new Banco();
        if ($id == null)
            $result = $banco->salvar($codigo, $nome, $status);
        else
            $result = $banco->salvar($codigo, $nome, $status, $id);
        
        
        if ($result === false)
        {
            $json['success'] = false;
            switch($banco->getMysqlError())
            {
                case 1062 : $json['message'] = 'Não foi possível salvar o registro. Já existe um registro cadastrado para este código'; break;
                default: $json['message'] = 'Não foi possível salvar o registro. Codigo: '. $banco->getMysqlError(); break;
            }
            
        }else
        {
             $json['success'] = true;
             $json['id'] = $result;
        }
        
        echo json_encode($json);
    }
    
     public function apagarBanco()
    {
        if (! \Application::isAuthorized(ucfirst(strtolower(\Application::getNameController())) , 'bancos', 'remover'))
        {
             $json['success'] = false;
             $json['message'] = 'Usuário sem autorização';
             echo json_encode($json);
            exit;
        }
        
        $id = trim($_REQUEST['id']);
        
        $banco = new Banco();
         
        $result = $banco->excluir($id);
         
        if ($result === false)
        {
            $json['success'] = false;
            $json['message'] = 'Não foi possível salvar o registro. Codigo: '. $banco->getMysqlError();
        }else
        {
             $json['success'] = true;
             $json['id'] = $result;
        }
        
        echo json_encode($json);
        exit;
        
    }
    
    
     // *********************************************
    // OPERAÇÕES  DA SUBTABELA
    //
    
    public function operacaoSubtabela()
    {
          if (! \Application::isAuthorized(ucfirst(strtolower(\Application::getNameController())) , 'operacoes_subtabela', 'ler')  
                    && ! \Application::isAuthorized(ucfirst(strtolower(\Application::getNameController())) , 'operacoes_subtabela', 'escrever')
           )
            \Application::print404();
        
        $this->setView('operacoessubtabela/index');
        
        $operacao = new OperacaoSubtabela();
        $result = $operacao->listarOperacoes();
        if ($result !== false)
            $this->setParams('operacoes', $result);
        
        $this->showContents();
        
    }
    
    public function cadastrarOperacaoSubtabela()
    {
        if (! \Application::isAuthorized(ucfirst(strtolower(\Application::getNameController())) , 'operacoes_subtabela', 'ler')  
                    && ! \Application::isAuthorized(ucfirst(strtolower(\Application::getNameController())) , 'operacoes_subtabela', 'escrever')
           )
            \Application::print404();
        
        $this->setView('operacoessubtabela/cadastrar');
        
        
        $id = (\Application::getUrlParams(0) === null) ? null : \Application::getUrlParams(0);
        $operacao = new OperacaoSubtabela();
        if ($id !== null)
            $result = $operacao->listarOperacoes($id);
      else
          $result = false;
        
    if ($result !== false)
        $this->setParams('operacoes', $result);
        
         $this->showContents();
        
    }
    
    
    public function salvarOperacaoSubtabela()
    {
        if (! \Application::isAuthorized(ucfirst(strtolower(\Application::getNameController())) , 'operacoes_subtabela', 'escrever'))
        {
             $json['success'] = false;
             $json['message'] = 'Usuário sem autorização';
             echo json_encode($json);
            exit;
        }
        
        $id = (! isset($_REQUEST['id']) || trim($_REQUEST['id']) == '') ? null : $_REQUEST['id'];
        $nome = $_REQUEST['nome'];

        
        
        $operacao = new OperacaoSubtabela();
        if ($id == null)
            $result = $operacao->salvar( $nome);
        else
            $result = $operacao->salvar($nome, $id);
        
        
        if ($result === false)
        {
            $json['success'] = false;
            switch($banco->getMysqlError())
            {
                case 1062 : $json['message'] = 'Não foi possível salvar o registro. Já existe um registro cadastrado para este código'; break;
                default: $json['message'] = 'Não foi possível salvar o registro. Codigo: '. $operacao->getMysqlError(); break;
            }
            
        }else
        {
             $json['success'] = true;
             $json['id'] = $result;
        }
        
        echo json_encode($json);
    }
    
     public function apagarOperacaoSubtabela()
    {
        if (! \Application::isAuthorized(ucfirst(strtolower(\Application::getNameController())) , 'operacoes_subtabela', 'remover'))
        {
             $json['success'] = false;
             $json['message'] = 'Usuário sem autorização';
             echo json_encode($json);
            exit;
        }
        
        $id = trim($_REQUEST['id']);
        
        $operacao = new OperacaoSubtabela();
         
        $result = $operacao->excluir($id);
         
        if ($result === false)
        {
            $json['success'] = false;
            $json['message'] = 'Não foi possível salvar o registro. Codigo: '. $operacao->getMysqlError();
        }else
        {
             $json['success'] = true;
             $json['id'] = $result;
        }
        
        echo json_encode($json);
        exit;
        
    }
    
    
    
     // *********************************************
    // CONVÊNIOS/ENTIDADES
    //
    
    public function convenios()
    {
          if (! \Application::isAuthorized(ucfirst(strtolower(\Application::getNameController())) , 'convenio_entidades', 'ler')  
                    && ! \Application::isAuthorized(ucfirst(strtolower(\Application::getNameController())) , 'convenio_entidades', 'escrever')
           )
            \Application::print404();
        
        $this->setView('convenios/index');
        
        $entidade = new Entidade();
        $result = $entidade->listarEntidades();
        if ($result !== false)
            $this->setParams('convenios', $result);
        
        
        $this->showContents();
        
    }
    
    public function cadastrarConvenio()
    {
        if (! \Application::isAuthorized(ucfirst(strtolower(\Application::getNameController())) , 'convenio_entidades', 'ler')  
                    && ! \Application::isAuthorized(ucfirst(strtolower(\Application::getNameController())) , 'convenio_entidades', 'escrever')
           )
            \Application::print404();
        
        $this->setView('convenios/cadastrar');
        
        
        $id = (\Application::getUrlParams(0) === null) ? null : \Application::getUrlParams(0);
        $convenio = new Entidade();
        
        $result = $convenio->listarEntidades($id);
      
        if ($result !== false)
            $this->setParams('convenios', $result);
        
         $this->showContents();
        
    }
    
    public function salvarConvenio()
    {
        if (! \Application::isAuthorized(ucfirst(strtolower(\Application::getNameController())) , 'convenio_entidades', 'escrever'))
        {
             $json['success'] = false;
             $json['message'] = 'Usuário sem autorização';
             echo json_encode($json);
            exit;
        }
        
        $id = (! isset($_REQUEST['id']) || trim($_REQUEST['id']) == '') ? null : $_REQUEST['id'];
        $nome = $_REQUEST['nome'];

        
        
        $entidade = new Entidade();
        if ($id == null)
            $result = $entidade->salvar( $nome);
        else
            $result = $entidade->salvar($nome, $id);
        
        
        if ($result === false)
        {
            $json['success'] = false;
            switch($banco->getMysqlError())
            {
                case 1062 : $json['message'] = 'Não foi possível salvar o registro. Já existe um registro cadastrado com este nome'; break;
                default: $json['message'] = 'Não foi possível salvar o registro. '. $entidade->getMysqlError(); break;
            }
            
        }else
        {
             $json['success'] = true;
             $json['id'] = $result;
        }
        
        echo json_encode($json);
    }
    
     public function apagarConvenio()
    {
        if (! \Application::isAuthorized(ucfirst(strtolower(\Application::getNameController())) , 'convenio_entidades', 'remover'))
        {
             $json['success'] = false;
             $json['message'] = 'Usuário sem autorização';
             echo json_encode($json);
            exit;
        }
        
        $id = trim($_REQUEST['id']);
        
        $entidade = new Entidade();
         
        $result = $entidade->excluir($id);
         
        if ($result === false)
        {
            $json['success'] = false;
            $json['message'] = 'Não foi possível salvar o registro. Codigo: '. $entidade->getMysqlError();
        }else
        {
             $json['success'] = true;
             $json['id'] = $result;
        }
        
        echo json_encode($json);
        exit;
        
    }
    
     // *********************************************
    // METAS
    //
    
    public function listarMetas()
    {
          if (! \Application::isAuthorized(ucfirst(strtolower(\Application::getNameController())) , 'cadastrar_metas', 'ler')  
                    && ! \Application::isAuthorized(ucfirst(strtolower(\Application::getNameController())) , 'cadastrar_metas', 'escrever')
           )
            \Application::print404();
        
        $this->setView('metas/index');
        
         $meta = new Meta();
        $result = $meta->listarMetas(null, null, null, null,  100000);
        if ($result !== false)
            $this->setParams('metas', $result);
        
        
        $this->showContents();
        
    }
    
    public function cadastrarMeta()
    {
        if (! \Application::isAuthorized(ucfirst(strtolower(\Application::getNameController())) , 'cadastrar_metas', 'ler')  
                    && ! \Application::isAuthorized(ucfirst(strtolower(\Application::getNameController())) , 'cadastrar_metas', 'escrever')
           )
            \Application::print404();
        
        $this->setView('metas/cadastrar');
        
        
        $id = (\Application::getUrlParams(0) === null) ? null : \Application::getUrlParams(0);
        $meta = new Meta();
        if ($id != null)
        {
            $result = $meta->listarMetas($id);
            if ($result !== false)
                $this->setParams('meta', $result);
        }
        
        $usuarios = new Usuario();
        $result = $usuarios->listarUsuarios();
        if ($result !== false)
            $this->setParams('usuarios', $result);
        
        $grupo = new GrupoUsuario();
        $result = $grupo->listarGrupos();
        if ($result !== false)
            $this->setParams('grupos', $result);
        
        
         $this->showContents();
        
    }
    
    public function salvarMeta()
    {
        if (! \Application::isAuthorized(ucfirst(strtolower(\Application::getNameController())) , 'cadastrar_metas', 'escrever'))
        {
             $json['success'] = false;
             $json['message'] = 'Usuário sem autorização';
             echo json_encode($json);
            exit;
        }
        
        $id = (! isset($_REQUEST['id']) || trim($_REQUEST['id']) == '') ? null : $_REQUEST['id'];
       
        $dtInicio = ($_REQUEST['datainicio'] == '') ? null : Utils::formatStringDate($_POST['datainicio'], 'd/m/Y', 'Y-m-d');
        $prazo = ($_REQUEST['prazo'] == '') ? null : Utils::formatStringDate($_POST['prazo'], 'd/m/Y', 'Y-m-d');
        $valorMeta = $_REQUEST['meta'];
        $incremento = $_REQUEST['incremento'];
        $tipoMeta = (empty($_REQUEST['tipometa'])) ? null : $_REQUEST['tipometa'];
        
        
        $auxDestinatario = (empty($_REQUEST['usuario'])) ? '' : $_REQUEST['usuario'];
        $auxDestinatario = explode(';', $auxDestinatario);
        if (empty($auxDestinatario[0]))
        {
            echo json_encode(array('success' => false, 'message', 'Usuário ou Grupo não foi definido'));
            exit;
        }
        
        if ($auxDestinatario[0] == 'Vendedor')
        {
            $idUsuario = $auxDestinatario[1];
            $idGrupo = null;
        }else
        {
            $idUsuario = null;
            $idGrupo = $auxDestinatario[1];
        }
            
        
        
        $meta = new Meta();
        if ($id == null)
            $result = $meta->salvar( $idUsuario, $idGrupo, $tipoMeta, $dtInicio, $prazo, $valorMeta, $incremento);
        else
            $result = $meta->salvar($idUsuario, $idGrupo, $tipoMeta, $dtInicio, $prazo, $valorMeta, $incremento, $id);
        
        
        if ($result === false)
        {
            $json['success'] = false;
            switch($meta->getMysqlError())
            {
                case 1062 : $json['message'] = 'Não foi possível salvar o registro. Já existe um registro cadastrado com estes dados'; break;
                default: $json['message'] = 'Não foi possível salvar o registro. '. $meta->getMysqlError(); break;
            }
            
        }else
        {
             $json['success'] = true;
             $json['id'] = $result;
        }
        
        echo json_encode($json);
    }
    
    
    
    
      public function apagarMeta()
    {
        if (! \Application::isAuthorized(ucfirst(strtolower(\Application::getNameController())) , 'cadastrar_metas', 'remover'))
        {
             $json['success'] = false;
             $json['message'] = 'Usuário sem autorização';
             echo json_encode($json);
            exit;
        }
        
        $id = trim($_REQUEST['id']);
        
        $meta = new Meta();
         
        $result = $meta->excluir($id);
         
        if ($result === false)
        {
            $json['success'] = false;
            $json['message'] = 'Não foi possível remover o registro. Codigo: '. $meta->getMysqlError();
        }else
        {
             $json['success'] = true;
             $json['id'] = $result;
        }
        
        echo json_encode($json);
        exit;
        
    }
    
    
    // *********************************************
    // CLIENTES NÃO AGENDADOS
    //
    
    public function clientesNaoAgendados()
    {
          if (! \Application::isAuthorized(ucfirst(strtolower(\Application::getNameController())) , 'clientes_nao_agendados', 'ler')  
                    && ! \Application::isAuthorized(ucfirst(strtolower(\Application::getNameController())) , 'clientes_nao_agendados', 'escrever')
           )
            \Application::print404();
        
        $this->setView('clientesnaoagendados/index');
        
        $cliente = new Cliente();
        $result = $cliente->clientesNaoAgendados();
        if ($result !== false)
            $this->setParams('clientesnaoagendados', $result);
        
        $this->showContents();
        
    }
    
    
     // *********************************************
    // SUBSTATUS DO CONTRATO
    //
    
    public function substatusContrato()
    {
        if (! \Application::isAuthorized(ucfirst(strtolower(\Application::getNameController())) , 'substatus_contrato_menu', 'ler')  
                    && ! \Application::isAuthorized(ucfirst(strtolower(\Application::getNameController())) , 'substatus_contrato_menu', 'escrever')
           )
            \Application::print404();
        
        
        
        
         $this->setView('substatuscontrato/index');
        
        $substatus = new SubstatusContrato();
        $recordSet = $substatus->listarSubstatus();
        if($recordSet !== false)
            $this->setParams('substatus', $recordSet);
        
        $this->showContents();
        
    }
    
    public function SubstatusContratoCadastrar()
    {
        if (! \Application::isAuthorized(ucfirst(strtolower(\Application::getNameController())) , 'substatus_contrato_menu', 'ler')  
                    && ! \Application::isAuthorized(ucfirst(strtolower(\Application::getNameController())) , 'substatus_contrato_menu', 'escrever')
           )
            \Application::print404();
        
         $this->setView('substatuscontrato/cadastrar');
        
       
        
        $id = \Application::getUrlParams(0) != null ? \Application::getUrlParams(0) : null;
        
        $substatus = new SubstatusContrato();
        
        if ($id !== null)
        {
            $recordSet = $substatus->listarSubstatus($id);
            if($recordSet !== false && isset($recordSet[0]))
                $this->setParams('substatus', $recordSet[0]);
        }
        
        
        
        $this->showContents();
        
        
    }
    
    public function SalvarSubstatusContrato()
    {
        if (! \Application::isAuthorized(ucfirst(strtolower(\Application::getNameController())) , 'substatus_contrato_menu', 'remover'))
        {
             $json['success'] = false;
             $json['message'] = 'Usuário sem autorização';
             echo json_encode($json);
            exit;
        }
        
        $id = (! isset($_REQUEST['id']) || empty($_REQUEST['id'])) ? null : $_REQUEST['id'];
        $descricao = ($_REQUEST['descricao'] == '') ? null : $_REQUEST['descricao'];
        $status = (! isset($_REQUEST['status'])) ? null : $_REQUEST['status'];
        
        
        
         $substatus = new SubstatusContrato();
         $result = $substatus->salvar($descricao, $status, $id);
        
        if ($result === false)
        {
            $json['success'] = false;
            switch($substatus->getMysqlError())
            {
                case 1062 : $json['message'] = 'Não foi possível salvar o registro. Já existe um registro cadastrado com este nome'; break;
                default: $json['message'] = 'Não foi possível salvar o registro. '. $substatus->getMysqlError(); break;
            }
            
        }else
        {
             $json['success'] = true;
             $json['id'] = $result;
        }
        
        echo json_encode($json);
        
    }
    
    
     public function apagarSubstatusContrato()
    {
        if (! \Application::isAuthorized(ucfirst(strtolower(\Application::getNameController())) , 'substatus_contrato_menu', 'remover'))
        {
             $json['success'] = false;
             $json['message'] = 'Usuário sem autorização';
             echo json_encode($json);
            exit;
        }
        
        $id = trim($_REQUEST['id']);
        
         $substatus = new SubstatusContrato();
         
        $result = $substatus->excluir($id);
         
        if ($result === false)
        {
            $json['success'] = false;
            $json['message'] = 'Não foi possível salvar o registro. Codigo: '. $substatus->getMysqlError();
        }else
        {
             $json['success'] = true;
             $json['id'] = $result;
        }
        
        echo json_encode($json);
        exit;
        
    }
    
    
    
    /**
    * DESPESAS A PAGAR 
    **/
    
    public function despesasPagar()
	{
        
        if (! \Application::isAuthorized(ucfirst(strtolower(\Application::getNameController())) , 'despesas_pagar', 'ler')  
                    && ! \Application::isAuthorized(ucfirst(strtolower(\Application::getNameController())) , 'despesas_pagar', 'escrever')
           )
            \Application::print404();
        
        $this->setView('despesaspagar/index');
      
        
        /* $banco = new Banco();
        $result = $banco->listarBancos();
        if ($result !== false)
            $this->setParams('bancos', $result);
        
         $entidade = new Entidade();
         $result = $entidade->listarEntidades();
        if ($result !== false)
            $this->setParams('entidades', $result);
        
        
        $id = (\Application::getUrlParams(0) === null) ? null : \Application::getUrlParams(0);
        
        $roteiro = new Roteiro();
         $result = $roteiro->listarRoteiros($id);
        if ($result !== false)
            $this->setParams('roteiro', $result);
            */
        
        
     
        $this->showContents();
	}
    
    public function cadastrarDespesasPagar()
	{
        
        if (! \Application::isAuthorized(ucfirst(strtolower(\Application::getNameController())) , 'despesas_pagar', 'ler')  
                    && ! \Application::isAuthorized(ucfirst(strtolower(\Application::getNameController())) , 'despesas_pagar', 'escrever')
           )
            \Application::print404();
        
        $this->setView('despesaspagar/cadastrar_despesas');
      
        
        
        $id = (\Application::getUrlParams(0) === null) ? null : \Application::getUrlParams(0);
        $params = array('id' => $id);
        $despesas = new DespesasPagar();
         $result = $despesas->listarDespesas($params);
        if (isset($result[0]) && $id !== null)
            $this->setParams('despesa', $result[0]);
            
     
        $this->showContents();
	}
    
    public function jsonListarDespesasPagar()
    {
        if (! \Application::isAuthorized(ucfirst(strtolower(\Application::getNameController())) , 'despesas_pagar', 'ler')  
                    && ! \Application::isAuthorized(ucfirst(strtolower(\Application::getNameController())) , 'despesas_pagar', 'escrever')
           )
            \Application::print404();
        
        
        $descricao = (! empty($_REQUEST['descricao'])) ? $_REQUEST['descricao'] : null;
        $dataCriacaoInicio = (!empty($_REQUEST['datainiciocriacao'])) ? Utils::formatStringDate($_REQUEST['datainiciocriacao'], 'd/m/Y', 'Y-m-d') : null;
        $dataCriacaoFim = (!empty($_REQUEST['datafimcriacao'])) ? Utils::formatStringDate($_REQUEST['datafimcriacao'], 'd/m/Y', 'd/m/Y') : null;
        $dataVencimentoInicio = (!empty($_REQUEST['datainiciovencimento'])) ? Utils::formatStringDate($_REQUEST['datainiciovencimento'], 'd/m/Y', 'Y-m-d') : null;
        $dataVencimentoFim = (!empty($_REQUEST['datafimvencimento'])) ? Utils::formatStringDate($_REQUEST['datafimvencimento'], 'd/m/Y', 'd/m/Y') : null;
        //$dataPagamentoInicio = (!empty($_REQUEST['datacriacaoinicio'])) ? Utils::formatStringDate($_REQUEST['datacriacaoinicio'], 'd/m/Y', 'Y-m-d') : null;
       // $dataPagamentoFim = (!empty($_REQUEST['datacriacaofim'])) ? Utils::formatStringDate($_REQUEST['datacriacaofim'], 'd/m/Y', 'd/m/Y') : null;
        
        
        
        $params = array('limit' => 999999, 'descricao' => $descricao, 'datavencimentoinicio' => $dataVencimentoInicio, 'datavencimentofim' => $dataVencimentoFim,
                       'datacriacaoinicio' => $dataCriacaoInicio, 'datacriacaofim' => $dataCriacaoFim);
        
        $despesas = new DespesasPagar();
        
        $result = $despesas->listarDespesas($params);
       
        if (is_array($result))
            echo json_encode($result);
        else
            echo json_encode(array());
        
        
        exit;
    }
    
    
     public function salvarDespesasPagar()
    {

      if (! \Application::isAuthorized(ucfirst(strtolower(\Application::getNameController())) , 'despesas_pagar', 'escrever'))
          \Application::print404();

      
      $descricao = (! empty($_REQUEST['descricao'])) ? $_REQUEST['descricao'] : null;
      $vencimento = (! empty($_REQUEST['vencimento'])) ? Utils::formatStringDate($_POST['vencimento'], 'd/m/Y', 'Y-m-d') : null;
      $pagamento = (! empty($_REQUEST['pagamento'])) ? Utils::formatStringDate($_POST['pagamento'], 'd/m/Y', 'Y-m-d') : null;
      $valorDevido = (! empty($_REQUEST['valordevido'])) ? $_REQUEST['valordevido'] : null;
      $valorPago = (! empty($_REQUEST['valorpago'])) ? $_REQUEST['valorpago'] : null;
      $id = (! empty( $_REQUEST['id'] )) ? $_REQUEST['id'] : null;
         
      $params = array(
        'descricao' => $descricao,
        'vencimento' => $vencimento,
        'pagamento' => $pagamento,
        'valordevido'  => $valorDevido,
        'valorpago' => $valorPago,
        'id' => $id
      ); 
        

      $despesas = new DespesasPagar();
      $result = $despesas->salvar($params);
      if ($result === false)
          $response = array('success' => false, 'message' => 'Não foi possível salvar o registro. Erro '. $despesas->getMysqlError());
       else
           $response = array('success' => true, 'message' => '', 'id' => $result);

      echo json_encode($response);
      exit;
    }
    
    public function excluirDespesasPagar()
    {
      if (! \Application::isAuthorized(ucfirst(strtolower(\Application::getNameController())) , 'despesas_pagar', 'remover'))
        \Application::print404();

        $id = (! empty($_REQUEST['id'])) ? $_REQUEST['id'] : null;
        $despesas = new DespesasPagar();
        $result = $despesas->excluir($id);

        if ($result == false)
        {
            $response['success'] = false;
            $response['message'] = 'Não foi possível remover o registro. Código: ' . $despesas->getMysqlError();
        }else {
            $response['success'] = true;
            $response['message'] = 'Registro removido com sucesso';
        }

        echo json_encode($response);
        exit;
    }
    

}

?>