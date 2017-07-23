<?php

namespace Gauchacred\controller;



//use \controller\Controller as Controller;
use Gauchacred\library\php\Blowfish as Blowfish;
use Gauchacred\model\Banco as Banco;
use Gauchacred\model\Perfil as Perfil;
use Gauchacred\model\Recurso as Recurso;
use Gauchacred\model\Entidade as Entidade;
use Gauchacred\model\Roteiro as Roteiro;
use Gauchacred\model\GrupoUsuario as GrupoUsuario;
use Gauchacred\model\Usuario as Usuario;
use Gauchacred\library\php\Utils as Utils;
use Gauchacred\model\ContaBancariaCliente as ContaBancariaCliente;
use Gauchacred\model\Feriado;
use Gauchacred\model\ConfiguraEmail;
/**
 * @author moises
 * @version 1.0
 * @created 13-set-2015 23:08:59
 */
class Sistema extends Controller
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
	public function autorizarPerfil()
	{

        if (! \Application::isAuthorized(ucfirst(strtolower(\Application::getNameController())) , 'autorizar_perfil', 'ler')
                    && ! \Application::isAuthorized(ucfirst(strtolower(\Application::getNameController())) , 'autorizar_perfil', 'escrever')
           )
            \Application::print404();

        $this->setView('autorizacoes/autorizar');
        $id = \Application::getUrlParams(0);
        $perfil = new Perfil();
        $autorizacoes = $perfil->listarAutorizacao($id);
        if ($autorizacoes !== false)
            $this->setParams('autorizacoes', $autorizacoes);
        $infoPerfil = $perfil->get($id);
        if ($infoPerfil !== false)
            $this->setParams('infoperfil', $infoPerfil);

        $recurso = new Recurso();
        $rec = $recurso->listarRecursos();
        if ($rec !== false)
            $this->setParams('recursos', $rec);

        $this->showContents();
	}

    public function listarAutorizacoesPerfil()
	{

        if (! \Application::isAuthorized(ucfirst(strtolower(\Application::getNameController())) , 'autorizar_perfil', 'ler')
                    && ! \Application::isAuthorized(ucfirst(strtolower(\Application::getNameController())) , 'autorizar_perfil', 'escrever')
           )
            \Application::print404();

        $this->setView('autorizacoes/listar_perfil');

        $perfil = new Perfil();
        $result = $perfil->get();
        if ($perfil !== false)
            $this->setParams('perfil',$result);

        $this->showContents();
	}

    public function listarPerfil()
    {
        $this->setView('perfil/index');
        $this->showContents();
    }


    public function salvarAutorizacao()
    {
        if (! \Application::isAuthorized(ucfirst(strtolower(\Application::getNameController())) , 'autorizar_perfil', 'escrever'))
        {
             $json['success'] = false;
             $json['message'] = 'Usuário sem autorização';
             echo json_encode($json);
            exit;
        }
        $dados =  json_decode( $_REQUEST['dados'], true);
        $id = $_REQUEST['id'];


        if ($id == 1)
        {
             $json['success'] = false;
             $json['message'] = 'Não é possível alterar o perfil Administrador';
             echo json_encode($json);
            exit;
        }


        $autorizacoes = array();
        if (array_key_exists('recurso', $dados))
            array_push($autorizacoes , $dados);
        else
            $autorizacoes = $dados;

        // normaliza boolean das permissões transformando em inteiro
        $perfil = new Perfil();
        $result = $perfil->autorizar($id, $autorizacoes);
        if (! $result)
        {
            $json['success'] = false;
            $json['message'] = 'Erro código: '. $perfil->getMysqlError();
        }else
            $json['success'] = true;

        echo  json_encode($json);
        exit;
    }


     // *********************************************
    // CADASTRO DE USUÁRIOS
    //

    public function usuarios()
    {
          if (! \Application::isAuthorized(ucfirst(strtolower(\Application::getNameController())) , 'usuarios', 'ler')
                    && ! \Application::isAuthorized(ucfirst(strtolower(\Application::getNameController())) , 'usuarios', 'escrever')
           )
            \Application::print404();

        $this->setView('usuarios/index');

        $usuario = new Usuario();
        $result = $usuario->listarUsuarios();
        if ($result !== false)
            $this->setParams('usuarios', $result);

        $this->showContents();

    }

    public function cadastrarUsuario()
    {
        if (! \Application::isAuthorized(ucfirst(strtolower(\Application::getNameController())) , 'usuarios', 'ler')
                    && ! \Application::isAuthorized(ucfirst(strtolower(\Application::getNameController())) , 'usuarios', 'escrever')
           )
            \Application::print404();

        $this->setView('usuarios/cadastro_usuario');


        $id = (\Application::getUrlParams(0) === null) ? null : \Application::getUrlParams(0);
        $usuario = new Usuario();
        if ($id !== null)
            $result = $usuario->listarUsuarios($id);
        else
            $result = false;

        if ($result !== false || $id === null)
            $this->setParams('usuario', $result);

        $contaBancaria = new ContaBancariaCliente();
        $result = $contaBancaria->listarTipos();
        if ($result !== false)
            $this->setParams('tiposdecontas', $result);

        $banco = new Banco();
        $result = $banco->listarBancos();
        if ($result !== false)
            $this->setParams('bancos', $result);

         $this->showContents();

    }


    public function salvarUsuario()
    {
        if (! \Application::isAuthorized(ucfirst(strtolower(\Application::getNameController())) , 'usuarios', 'escrever'))
        {
             $json['success'] = false;
             $json['message'] = 'Usuário sem autorização';
             echo json_encode($json);
            exit;
        }

        $id = (! isset($_REQUEST['id']) || trim($_REQUEST['id']) == '') ? null : $_REQUEST['id'];
        $cpf = $_REQUEST['cpf'];
        $nome = $_REQUEST['nome'];
        $email = $_REQUEST['email'];
        $senha = (trim($_REQUEST['senha']) == '') ? '' : Blowfish::crypt($_REQUEST['senha']);
        $nascimento = Utils::formatStringDate($_REQUEST['nascimento'],  'd/m/Y', 'Y-m-d');
        $status = $_REQUEST['status'];
        $telefone = $_REQUEST['telefone'];
        $celular = $_REQUEST['celular'];
        $cep = $_REQUEST['cep'];
        $rua = $_REQUEST['rua'];
        $numeroResidencia = $_REQUEST['numeroResidencia'];
        $uf = $_REQUEST['uf'];
        $complemento = $_REQUEST['complemento'];
        $bairro = $_REQUEST['bairro'];
        $cidade = $_REQUEST['cidade'];
        $tipoConta = $_REQUEST['tipoConta'];
        $banco = $_REQUEST['banco'];
        $agencia = $_REQUEST['agencia'];
        $numeroConta = $_REQUEST['numeroConta'];


        $usuario = new Usuario();
        if ($id == null)
            $result = $usuario->salvar($cpf, $nome, $email, $senha, $nascimento, $status, $telefone, $celular, $cep, $rua, $numeroResidencia, $uf, $complemento, $bairro, $cidade, $tipoConta, $banco, $agencia, $numeroConta);
        else
            $result = $usuario->salvar($cpf, $nome, $email, $senha, $nascimento, $status, $telefone, $celular, $cep, $rua, $numeroResidencia, $uf, $complemento, $bairro, $cidade, $tipoConta, $banco, $agencia, $numeroConta, $id);


        if ($result === false)
        {
            $json['success'] = false;
            switch($usuario->getMysqlError())
            {
                case 1062 : $json['message'] = 'Não foi possível salvar o registro. Já existe um registro cadastrado para este cpf'; break;
                default: $json['message'] = 'Não foi possível salvar o registro. Codigo: '. $usuario->getMysqlError(); break;
            }

        }else
        {
             $json['success'] = true;
             $json['id'] = $result;
        }

        echo json_encode($json);
    }

    public function apagarUsuario()
    {
        if (! \Application::isAuthorized(ucfirst(strtolower(\Application::getNameController())) , 'usuarios', 'remover'))
        {
             $json['success'] = false;
             $json['message'] = 'Usuário sem autorização';
             echo json_encode($json);
            exit;
        }

        $id = trim($_REQUEST['id']);

        $usuario = new Usuario();

        $result = $usuario->excluir($id);

        if ($result === false)
        {
            $json['success'] = false;
            switch($usuario->getMysqlError())
            {
                case 1451: $json['message'] = 'Não foi possível excluir o cadastro. Este registro possui ligação com outros cadastros, remova as ligações antes de excluir'; break;
                default: $json['message'] = 'Não foi possível salvar o registro. Codigo: '. $usuario->getMysqlError();break;
            }

        }else
        {
             $json['success'] = true;
             $json['id'] = $result;
        }

        echo json_encode($json);
        exit;

    }

    public function desativarUsuario()
    {
        if (! \Application::isAuthorized(ucfirst(strtolower(\Application::getNameController())) , 'usuarios', 'escrever'))
        {
             $json['success'] = false;
             $json['message'] = 'Usuário sem autorização';
             echo json_encode($json);
            exit;
        }

        $id = trim($_REQUEST['id']);

        $usuario = new Usuario();

        $result = $usuario->desativar($id);

        if ($result === false)
        {
            $json['success'] = false;
            switch($usuario->getMysqlError())
            {
                case 1451: $json['message'] = 'Não foi possível desativar o cadastro. Este registro possui ligação com outros cadastros, remova as ligações antes de excluir'; break;
                default: $json['message'] = 'Não foi possível salvar o registro. Codigo: '. $usuario->getMysqlError();break;
            }

        }else
        {
             $json['success'] = true;
             $json['id'] = $result;
        }

        echo json_encode($json);
        exit;

    }

    public function definirPerfilUsuario()
    {
          if (! \Application::isAuthorized(ucfirst(strtolower(\Application::getNameController())) , 'usuarios', 'ler')
                    && ! \Application::isAuthorized(ucfirst(strtolower(\Application::getNameController())) , 'usuarios', 'escrever')
           )
            \Application::print404();

        $this->setView('usuarios/definir_perfil');

         $id = (\Application::getUrlParams(0) === null) ? null : \Application::getUrlParams(0);

        $usuario = new Usuario();
        $result = $usuario->listarUsuarios($id);
        if ($result !== false)
            $this->setParams('usuario', $result);

        $perfil = new Perfil();
        $result = $perfil->listarPerfilUsuario($id);
        if ($result !== false)
            $this->setParams('perfilusuario', $result);


            $result = $perfil->get();
            if ($result !== false)
               $this->setParams('listaperfil', $result);

        $this->showContents();

    }

    public function salvarPerfilUsuario()
    {
        if (! \Application::isAuthorized(ucfirst(strtolower(\Application::getNameController())) , 'usuarios', 'escrever'))
        {
             $json['success'] = false;
             $json['message'] = 'Usuário sem autorização';
             echo json_encode($json);
            exit;
        }

        $id = (! isset($_REQUEST['id']) || trim($_REQUEST['id']) == '') ? null : $_REQUEST['id'];
        $listaPerfil = json_decode($_REQUEST['perfil'], true);

       // var_dump($listaPerfil);exit;

        if ($id == 1)
        {
             $json['success'] = false;
             $json['message'] = 'Não é possível alterar o perfil do Administrador do Sistema';
             echo json_encode($json);
            exit;
        }


        $perfil = new Perfil();
        if ($id !== null)
            $result = $perfil->definirPerfilUsuario($id, $listaPerfil);
        else
        {
            $json['success'] = false;
             $json['message'] = 'A identificação do usuário não pode ser recuperada';
            echo json_encode($json);
            exit;
        }


        if ($result === false)
        {
            $json['success'] = false;
            switch($perfil->getMysqlError())
            {
                case 1062 : $json['message'] = 'Não foi possível salvar o registro. Já existe um registro cadastrado para este nome'; break;
                default: $json['message'] = 'Não foi possível salvar o registro. Codigo: '. $perfil->getMysqlError(); break;
            }

        }else
        {
             $json['success'] = true;
             $json['id'] = $result;
        }

        echo json_encode($json);
    }



     // *********************************************
    // CADASTRO DE PERFIL
    //

    public function perfil()
    {
          if (! \Application::isAuthorized(ucfirst(strtolower(\Application::getNameController())) , 'perfil', 'ler')
                    && ! \Application::isAuthorized(ucfirst(strtolower(\Application::getNameController())) , 'perfil', 'escrever')
           )
            \Application::print404();

        $this->setView('perfil/index');

        $perfil = new Perfil();
        $result = $perfil->get();
        if ($result !== false)
            $this->setParams('perfil', $result);

        $this->showContents();

    }

    public function cadastrarPerfil()
    {
        if (! \Application::isAuthorized(ucfirst(strtolower(\Application::getNameController())) , 'perfil', 'ler')
                    && ! \Application::isAuthorized(ucfirst(strtolower(\Application::getNameController())) , 'perfil', 'escrever')
           )
            \Application::print404();

        $this->setView('perfil/cadastrar');


        $id = (\Application::getUrlParams(0) === null) ? null : \Application::getUrlParams(0);

        if ($id !== null)
        {
            $perfil = new Perfil();
            $result = $perfil->get($id);
            if ($result !== false)
               $this->setParams('perfil', $result);
        }

         $this->showContents();

    }

    public function salvarPerfil()
    {
        if (! \Application::isAuthorized(ucfirst(strtolower(\Application::getNameController())) , 'perfil', 'escrever'))
        {
             $json['success'] = false;
             $json['message'] = 'Usuário sem autorização';
             echo json_encode($json);
            exit;
        }

        $id = (! isset($_REQUEST['id']) || trim($_REQUEST['id']) == '') ? null : $_REQUEST['id'];
        $nome = $_REQUEST['nome'];

        if ($id == 1)
        {
             $json['success'] = false;
             $json['message'] = 'Não é possível alterar o perfil Administrador';
             echo json_encode($json);
            exit;
        }


        $perfil = new Perfil();
        if ($id == null)
            $result = $perfil->salvar($nome);
        else
            $result = $perfil->salvar($nome, $id);


        if ($result === false)
        {
            $json['success'] = false;
            switch($perfil->getMysqlError())
            {
                case 1062 : $json['message'] = 'Não foi possível salvar o registro. Já existe um registro cadastrado para este nome'; break;
                default: $json['message'] = 'Não foi possível salvar o registro. Codigo: '. $perfil->getMysqlError(); break;
            }

        }else
        {
             $json['success'] = true;
             $json['id'] = $result;
        }

        echo json_encode($json);
    }

    public function apagarPerfil()
    {
        if (! \Application::isAuthorized(ucfirst(strtolower(\Application::getNameController())) , 'perfil', 'remover'))
        {
             $json['success'] = false;
             $json['message'] = 'Usuário sem autorização';
             echo json_encode($json);
            exit;
        }

        $id = trim($_REQUEST['id']);

        if ($id == 1)
        {
             $json['success'] = false;
             $json['message'] = 'Não é possível remover o perfil Administrador';
             echo json_encode($json);
            exit;
        }

        $perfil = new Perfil();

        $result = $perfil->excluir($id);

        if ($result === false)
        {
            $json['success'] = false;
            switch($perfil->getMysqlError())
            {
                case 1451: $json['message'] = 'Não foi possível excluir o cadastro. Este registro possui ligação com outros cadastros, remova as ligações antes de excluir'; break;
                default: $json['message'] = 'Não foi possível salvar o registro. Codigo: '. $perfil->getMysqlError();break;
            }

        }else
        {
             $json['success'] = true;
             $json['id'] = $result;
        }

        echo json_encode($json);
        exit;

    }


      // *********************************************
    // CADASTRO DE FERIADOS
    //

    public function feriados()
    {
          if (! \Application::isAuthorized(ucfirst(strtolower(\Application::getNameController())) , 'feriados', 'ler')
                    && ! \Application::isAuthorized(ucfirst(strtolower(\Application::getNameController())) , 'feriados', 'escrever')
           )
            \Application::print404();

        $this->setView('feriados/index');

        $feriado = new Feriado();
        $result = $feriado->listarFeriados(null,null, null, 100000);
        if ($result !== false)
            $this->setParams('feriados', $result);

        $this->showContents();

    }

    public function cadastrarFeriado()
    {
        if (! \Application::isAuthorized(ucfirst(strtolower(\Application::getNameController())) , 'feriados', 'ler')
                    && ! \Application::isAuthorized(ucfirst(strtolower(\Application::getNameController())) , 'feriados', 'escrever')
           )
            \Application::print404();

        $this->setView('feriados/cadastrar');


        $id = (\Application::getUrlParams(0) === null) ? null : \Application::getUrlParams(0);

        if ($id !== null)
        {
            $feriado = new Feriado();
            $result = $feriado->listarFeriados($id);
            if ($result !== false)
               $this->setParams('feriado', $result);
        }

         $this->showContents();

    }


     public function salvarFeriado()
    {
        if (! \Application::isAuthorized(ucfirst(strtolower(\Application::getNameController())) , 'feriados', 'escrever'))
        {
             $json['success'] = false;
             $json['message'] = 'Usuário sem autorização';
             echo json_encode($json);
            exit;
        }

        $id = (! isset($_REQUEST['id']) || trim($_REQUEST['id']) == '') ? null : $_REQUEST['id'];
        $descricao = $_REQUEST['descricao'];
        $data = Utils::formatStringDate( $_REQUEST['data'], 'd/m/Y', 'Y-m-d');


        $feriado = new Feriado();
        if ($id == null)
            $result = $feriado->salvar($descricao, $data);
        else
            $result = $feriado->salvar($descricao, $data, $id);


        if ($result === false)
        {
            $json['success'] = false;
            switch($feriado->getMysqlError())
            {
                case 1062 : $json['message'] = 'Não foi possível salvar o registro. Já existe um registro cadastrado para esta data'; break;
                default: $json['message'] = 'Não foi possível salvar o registro. Codigo: '. $feriado->getMysqlError(); break;
            }

        }else
        {
             $json['success'] = true;
             $json['id'] = $result;
        }

        echo json_encode($json);
    }

    public function apagarFeriado()
    {
        if (! \Application::isAuthorized(ucfirst(strtolower(\Application::getNameController())) , 'feriados', 'remover'))
        {
             $json['success'] = false;
             $json['message'] = 'Usuário sem autorização';
             echo json_encode($json);
            exit;
        }

        $id = trim($_REQUEST['id']);



        $feriado = new Feriado();

        $result = $feriado->excluir($id);

        if ($result === false)
        {
            $json['success'] = false;
            switch($feriado->getMysqlError())
            {
                case 1451: $json['message'] = 'Não foi possível excluir o cadastro. Este registro possui ligação com outros cadastros, remova as ligações antes de excluir'; break;
                default: $json['message'] = 'Não foi possível salvar o registro. Codigo: '. $feriado->getMysqlError();break;
            }

        }else
        {
             $json['success'] = true;
             $json['id'] = $result;
        }

        echo json_encode($json);
        exit;

    }


		// *********************************************
	// CONFIGURAR EMAILS SISTEMA
	//

	public function configurarEmail()
	{
				if (! \Application::isAuthorized(ucfirst(strtolower(\Application::getNameController())) , 'configuracao_email_sistema', 'ler')
									&& ! \Application::isAuthorized(ucfirst(strtolower(\Application::getNameController())) , 'configuracao_email_sistema', 'escrever')
				 )
					\Application::print404();

			$this->setView('configuracao/email/index');

			$configEmail = new ConfiguraEmail();
			$result = $configEmail->listar();
			if ($result !== false)
					$this->setParams('email', $result);

			$this->showContents();

	}

	public function salvarConfiguracaoEmail()
	{
		if (! \Application::isAuthorized(ucfirst(strtolower(\Application::getNameController())) , 'configuracao_email_sistema', 'remover'))
		{
				 $json['success'] = false;
				 $json['message'] = 'Usuário sem autorização';
				 echo json_encode($json);
				exit;
		}

		$request = \Application::getRequest();
    $smtpServer = (isset($request['smtpserver'])) ? $request['smtpserver'] : null;
    $smtpPort = (isset($request['smtpport'])) ? $request['smtpport'] : null;
    $smtpSecurity = (isset($request['smtpsecurity'])) ? $request['smtpsecurity'] : null;
    $smtpLogin = (isset($request['smtplogin'])) ? $request['smtplogin'] : null;
    $smtpPassword= (isset($request['smtppassword'])) ? $request['smtppassword'] : null;
    $para = (isset($request['para'])) ? $request['para'] : null;

		$conteudo = json_encode(array(
      'smtpServer' => urldecode($smtpServer),
      'smtpPort' => $smtpPort,
      'smtpSecurity' => $smtpSecurity,
      'smtpLogin' => $smtpLogin,
      'smtpPassword' => $smtpPassword,
      'para' => urldecode($para)
    ));

    $configEmail = new ConfiguraEmail();
    $result = $configEmail->salvar($conteudo);

    if ($result === false)
    {
      $response['success'] = false;
      $response['message'] = 'Não foi possível salvar os dados. Código ' . $configEmail->getMysqlError();
    }else {
      $response['success'] = true;
      $response['success'] = 'Salvo com sucesso';
    }
    echo json_encode($response);
    exit;
	}


}

?>
