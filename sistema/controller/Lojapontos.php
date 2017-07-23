<?php

namespace Gauchacred\controller;
// include PHPMailer
include \Application::getIndexPath() . DS . 'library' . DS . 'php' . DS . 'phpmailer' . DS . 'phpmailer' . DS . 'PHPMailerAutoload.php';

//use \controller\Controller as Controller;
//use \library\php\Blowfish as Blowfish;

use Gauchacred\library\php\Utils as Utils;
//use Gauchacred\model\TelefoneUtil as TelefoneUtil;
//use Gauchacred\model\Banco as Banco;
//use Gauchacred\model\Promotora as Promotora;

use Gauchacred\model\LojaPontos as LojaPontosModel;
use Gauchacred\model\PontoTroca;
use Gauchacred\model\Usuario;
use Gauchacred\model\ConfiguraEmail;
use Gauchacred\model\AvaliaProduto;
/**
 * @author moises
 * @version 1.0
 * @created 13-set-2015 23:08:59
 */
class Lojapontos extends Controller
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
    if (! \Application::isAuthorized('Troca de Pontos' , 'loja_pontos', 'ler')
            && ! \Application::isAuthorized('Troca de Pontos' , 'loja_pontos', 'escrever'))
        \Application::print404();

    $this->setHeaderInclude(false);
    $this->setFooterInclude(false);

    $loja = new LojaPontosModel();
    $params = array('limit' => 100000, 'incluirExpirado' => true  );
    $result = $loja->listar($params);
    if (is_array($result))
        $this->setParams('produtos', $result);

    $this->setView('lojapontos/loja/index');
    $this->showContents();

	}
  /**
  * Lista os produtos da loja para o vendedor
  *
  */
  public function visualizarProduto()
  {
    if (! \Application::isAuthorized('Troca de Pontos' , 'loja_pontos', 'ler')
            && ! \Application::isAuthorized('Troca de Pontos' , 'loja_pontos', 'escrever'))
        \Application::print404();

    $this->setHeaderInclude(false);
    $this->setFooterInclude(false);

    $id = \Application::getUrlParams(0);
    if ($id === null)
      \Application::print404();

    $loja = new LojaPontosModel();
    $params = array('id' => $id );
    $result = $loja->listar($params);
    if (isset($result[0]))
        $this->setParams('produto', $result[0]);


    $pontoTroca = new pontoTroca();
    $result = $pontoTroca->listar(array(
        'limit' => 1000000,
        'idUsuario' => $_SESSION['userid']
    ));
    if (is_array($result))
    {
      $totalPontos = 0;
      foreach ($result as $itemPonto) {
        $totalPontos += $itemPonto['pontosObtidos'] - $itemPonto['pontosResgatados'];
      }
      $this->setParams('pontos', $totalPontos);
    }

    // pega as avaliações
    $avaliaProduto = new AvaliaProduto();
    $result = $avaliaProduto->listar(array('id' => $id));
    if (is_array($result))
       $this->setParams('avaliacoes', $result);


    $this->setView('lojapontos/loja/visualizar_produto');
    $this->showContents();
  }

    public function produtos()
    {
        if (! \Application::isAuthorized('Troca de Pontos' , 'loja_pontos_cadastrar_produtos', 'ler')
                && ! \Application::isAuthorized('Troca de Pontos' , 'loja_pontos_cadastrar_produtos', 'escrever'))
            \Application::print404();

        $this->setView('lojapontos/cadastroprodutos/index');

        $loja = new LojaPontosModel();

        $params = array('limit' => 1000000, 'incluirExpirado' => true);
        $result = $loja->listar($params);
        if (is_array($result))
            $this->setParams('produtos', $result);

        $this->showContents();
    }

    public function efetuarTroca()
    {
      if (! \Application::isAuthorized('Troca de Pontos' , 'loja_pontos', 'ler')
              && ! \Application::isAuthorized('Troca de Pontos' , 'loja_pontos', 'escrever'))
        {
          echo json_encode(array('success' => false, 'message' => 'Usuário sem permissão'));
          exit;
        }
        $idProduto = (isset($_REQUEST['id'])) ? $_REQUEST['id'] : null;
        $quantidade = (isset($_REQUEST['quantidade'])) ? $_REQUEST['quantidade'] : null;
        $idUsuario = $_SESSION['userid'];

        // VERIFICAR SE OS PONTOS SÃO suficientes
        // pega informações do produto
        $loja = new LojaPontosModel();
        $params = array('id' => $idProduto );
        $result = $loja->listar($params);
        if (isset($result[0]))
          $produto = $result[0];
        else
          $produto = null;

        // pega os pontos disponiveis
        $pontoTroca = new PontoTroca();
        $result = $pontoTroca->listar(array(
            'limit' => 1000000,
            'idUsuario' => $idUsuario
        ));
        $itensConsumir = array();
        if (is_array($result))
        {
          $totalPontos = 0;
          foreach ($result as $itemPonto) {
            $totalPontos += $itemPonto['pontosObtidos'] - $itemPonto['pontosResgatados'];
            array_push($itensConsumir, $itemPonto);
            if ($totalPontos >= ($quantidade * $produto['pontos'] ))
              break;
          }

        }else {
          $totalPontos = 0;
        }



        // Verifica se está tudo ok para realizar calculo de consumo dos pontos
        if ($produto == null)
        {
          echo json_encode(array('success' => false, 'message' => 'Produto não localizado'));
          exit;
        }

        if ($totalPontos <  ($quantidade * $produto['pontos']) )
        {
          echo json_encode(array('success' => false, 'message' => 'Você não tem pontos suficientes'));
          exit;
        }
        // verifica se o consumo de pontos é total ou parcial
        if ($totalPontos == ($quantidade * $produto['pontos'] ))
          $tipoConsumo = 'total';
        else
          $tipoConsumo = 'parcial';

        $result = $pontoTroca->trocarPontos(array(
          'idUsuario' => $idUsuario,
          'produto' => $produto,
          'tipoConsumo' => $tipoConsumo,
          'itensConsumir' => $itensConsumir,
          'quantidade' => $quantidade,
          'totalPontos' => $totalPontos
        ));

        if ($result === false)
        {
          $response['success'] = false;
          switch ($pontoTroca->getMysqlError()) {
            case 'value':
              # code...
              break;

            default:
              $response['message'] = 'Ocorreram erros. Código: ' . $pontoTroca->getMysqlError();
              break;
          }
        }else {
           $response['success'] = true;
           $response['message'] = 'Salvo com sucesso';
           // PEGA OS DADOS DO USUARIO
           $usuario = new Usuario();
           $result = $usuario->listarUsuarios($_SESSION['userid']);

           if (isset($result[0]))
           {
             $email = $result[0]['email'];
             $nome = $result[0]['nome'];
           }else {
             $email = null;
             $nome = null;
           }

           // PEGA OS DADOS DO SMTP
           $configEmail = new ConfiguraEmail();
     			 $result = $configEmail->listar();

           if (is_array($result) && count($result) > 0)
           {
             $smtpServer = (! empty($result['smtpServer'])) ? $result['smtpServer'] : null;
             $smtpPort =  (! empty($result['smtpPort'])) ? $result['smtpPort'] : null;
             $smtpSecurity = (! empty($result['smtpSecurity'])) ? $result['smtpSecurity'] : null;
             $smtpLogin = (! empty($result['smtpLogin'])) ? $result['smtpLogin'] : null;
             $smtpPassword =  (! empty($result['smtpPassword'])) ? $result['smtpPassword'] : null;
             $para = (! empty($result['para'])) ? $result['para'] : null;

             // carrega template
             $pathTemplate = \Application::getIndexPath() . DS . 'templates' . DS . 'email' . DS . 'confirma_troca_pontos.html';
             if (file_exists($pathTemplate))
              $template = file_get_contents($pathTemplate);
             else
                $template = null;

              if ($template !== null && $smtpServer !== null && $smtpPort !== null && $nome !== null)
              {
                 $chavesTemplate = array('{usuario}','{url}','{produto}','{quantidade}','{pontos}');
                 $replace = array($nome, \Application::getUrlSite(), $produto['nome'], $quantidade, ($produto['pontos'] * $quantidade) );

                 $template = str_replace($chavesTemplate, $replace, $template);
                  $mail = new \PHPMailer();
              		$mail->isSMTP();                                      // Set mailer to use SMTP
              		$mail->Host = $smtpServer; 						  // Specify main and backup SMTP servers
              		$mail->SMTPAuth = true;                               // Enable SMTP authentication
              		$mail->Username = $smtpLogin ;                 // SMTP username
              		$mail->Password = $smtpPassword;                           // SMTP password
              		$mail->SMTPSecure = $smtpSecurity;                            // Enable TLS encryption, `ssl` also accepted
              		$mail->Port = $smtpPort;                                    // TCP port to connect to

              		$mail->setFrom($smtpLogin, 'GauchaCred');

                  $mail->addAddress($email);
                  // Cópia Oculta
              		$para = explode(';', $para);
                  if (is_array($para ))
                      foreach($para  as $i => $value)
                          $mail->addBCC($value);


              		//$mail->addAttachment('/tmp/image.jpg', 'new.jpg');    // Optional name
              		$mail->isHTML(true);                                  // Set email format to HTML

              		$mail->Subject = 'GauchaCred';
              		$mail->Body    = $template;

                  if(!$mail->send()) {
              			\Application::setMysqlLogQuery('Envio de E-mail no troca pontos; '. $mail->ErrorInfo);
              		}
              }

           }
        }

        echo json_encode($response);
        exit;

    }

    public function cadastrarProduto()
    {
        if (! \Application::isAuthorized('Troca de Pontos' , 'loja_pontos_cadastrar_produtos', 'ler')
                && ! \Application::isAuthorized('Troca de Pontos' , 'loja_pontos_cadastrar_produtos', 'escrever'))
            \Application::print404();

        $id = \Application::getUrlParams(0);
        if ($id != null)
        {
            $loja = new LojaPontosModel();
            $result = $loja->listar(array('id' => $id));
            if (is_array($result))
                $this->setParams('produto', $result[0]);
        }


        $this->setView('lojapontos/cadastroprodutos/cadastrar_produto');
         $this->showContents();

    }

    public function salvarProduto()
    {
        if ( ! \Application::isAuthorized('Troca de Pontos' , 'loja_pontos_cadastrar_produtos', 'escrever'))
        {
            echo json_encode(array('success' => false, 'message' => 'Usuário sem permissão'));
            exit;
        }


        $id = (! empty($_REQUEST['id'])) ? $_REQUEST['id'] : null;
        $nome = (! empty($_REQUEST['nome'])) ? $_REQUEST['nome'] : null;
        $link = (! empty($_REQUEST['link'])) ? urldecode($_REQUEST['link']) : null;
        $pontos = (! empty($_REQUEST['pontos'])) ? $_REQUEST['pontos'] : null;
        $dataInicial = (! empty($_REQUEST['datainicial'])) ? Utils::formatStringDate($_REQUEST['datainicial'], 'd/m/Y', 'Y-m-d')  : null;
        $dataFinal = (! empty($_REQUEST['datafinal'])) ? Utils::formatStringDate($_REQUEST['datafinal'], 'd/m/Y', 'Y-m-d')  : null;
        $descricao = (! empty($_REQUEST['descricao'])) ? urldecode($_REQUEST['descricao']) : null;


        $loja = new LojaPontosModel();

        $params = array('id' => $id, 'nome'=>$nome, 'link' => $link, 'pontos' => $pontos, 'descricao' => $descricao, 'inicioValidade' => $dataInicial, 'fimValidade' => $dataFinal);
        $result = $loja->salvarProduto($params);

        if ($result > 0)
        {
            $response['success'] = true;
            $response['message'] = 'Salvo com sucesso';
            $response['id'] = $result;
        }else
        {
            $response['success'] = false;
            $response['message'] = 'Erro ao salvar registro, contate o administrador.';
        }

        echo json_encode($response);
        exit;


    }


		public function apagarProduto()
		{
			if ( ! \Application::isAuthorized('Troca de Pontos' , 'loja_pontos_cadastrar_produtos', 'remover'))
			{
					echo json_encode(array('success' => false, 'message' => 'Usuário sem permissão'));
					exit;
			}

			$id = (! empty($_REQUEST['id'])) ? $_REQUEST['id'] : null;
			$loja = new LojaPontosModel();
			$result = $loja->excluirProduto($id);

			if ($result == false)
			{
				$response['success'] = false;
				$response['message'] = 'Não foi possível remover o registro. Código: ' . $loja->getMysqlError();
			}else {
				$response['success'] = true;
				$response['message'] = 'Registro removido com sucesso';
			}

			echo json_encode($response);
			exit;
		}


    public function gravarAvaliacao()
    {
      if (! \Application::isAuthorized('Troca de Pontos' , 'loja_pontos', 'ler')
              && ! \Application::isAuthorized('Troca de Pontos' , 'loja_pontos', 'escrever'))
          \Application::print404();

      $comentario = (isset($_REQUEST['comentario'])) ? urldecode($_REQUEST['comentario']) : null;
      $idProduto = (isset($_REQUEST['id'])) ? $_REQUEST['id'] : null;
      $idUsuario = $_SESSION['userid'];
    
      if ($comentario == null || $idProduto == null || $idUsuario == null)
      {
        echo json_encode(array('success' => false, 'message' => 'Parâmetros incorretos'));
        exit;
      }

      $avaliaProduto = new AvaliaProduto();
      $params = array('id' => $idProduto, 'idUsuario' => $idUsuario, 'comentario' => $comentario);
      $result = $avaliaProduto->salvar($params);

      if ($result == true)
      {
        $response = array('success' => true, 'message' => 'Avaliação efetuada com sucesso');
      }else {
        $response = array('success' => false, 'message' => 'Não foi possível realizar a avaliação\nContate o administrador');
      }

      echo json_encode($response);
      exit;

    }
    
    /*
    * HISTORICO DE PONTOS
    */
    
     public function historicoPontos()
    {
        if (! \Application::isAuthorized('Troca de Pontos' , 'loja_pontos_historico_pontos', 'ler')
                && ! \Application::isAuthorized('Troca de Pontos' , 'loja_pontos_historico_pontos', 'escrever'))
            \Application::print404();

        
        $this->setView('lojapontos/historico/historico_pontos');
         
         $usuario = new Usuario();
          $result = $usuario->listarUsuarios();
          if ($result !== false)
              $this->setParams('usuarios', $result);
         
         $this->showContents();

    }
    
    public function pesquisarHistoricoPontos()
    {
        if (! \Application::isAuthorized('Troca de Pontos' , 'loja_pontos_historico_pontos', 'ler')
                && ! \Application::isAuthorized('Troca de Pontos' , 'loja_pontos_historico_pontos', 'escrever'))
        {
            echo json_encode(array('success' => false, 'message' => 'Você não tem permissão'));
            exit;
        }
        
        $idUsuario = (! empty($_REQUEST['idusuario'])) ? $_REQUEST['idusuario'] : null;
        $dataInicial = ($_POST['datainicial'] == '') ? '1' : Utils::formatStringDate($_POST['datainicial'], 'd/m/Y', 'Y-m-d');
        $dataFinal = ($_POST['datafinal'] == '') ? '1' : Utils::formatStringDate($_POST['datafinal'], 'd/m/Y', 'Y-m-d');
        
        $pontoTroca = new PontoTroca();
        $params = array('idUsuario' => $idUsuario, 'validadeInicial' => $dataInicial, 'validadeFinal' => $dataFinal, 'limit' => 10000000);
        $result = $pontoTroca->listarHistorico($params);
        if ($result === false)
            $response = array('success' => false, 'message' => array());
        
        else
            $response = array('success' => true, 'message' => $result);
        
        echo json_encode($response);
        exit;
        
    }
    
    
    public function apagarHistoricoPontos()
    {
        if ( ! \Application::isAuthorized('Troca de Pontos' , 'loja_pontos_historico_pontos', 'remover'))
        {
            echo json_encode(array('success' => false, 'message' => 'Você não tem permissão'));
            exit;
        }
        
        $id = (! empty($_REQUEST['id'])) ? $_REQUEST['id'] : null;
        
        if ( $id === null)
            \Application::print404();
        
        $pontoTroca = new PontoTroca();
        $result = $pontoTroca->excluir($id);
        if ($result === false)
            $response = array('success' => false, 'message' => 'Não foi possível remover o registro.\nCódigo: ' . $pontoTroca->getMysqlError());
        
        else
            $response = array('success' => true, 'message' => 'Removido com sucesso');
        
        echo json_encode($response);
        exit;
    }
    
    /*
    * HISTORICO DE RESGATES DE PONTOS
    */
    
     public function resgates()
    {
        if (! \Application::isAuthorized('Troca de Pontos' , 'loja_pontos_resgates', 'ler')
                && ! \Application::isAuthorized('Troca de Pontos' , 'loja_pontos_resgates', 'escrever'))
            \Application::print404();

        
        $this->setView('lojapontos/resgates/resgates');
         
         $usuario = new Usuario();
          $result = $usuario->listarUsuarios();
          if ($result !== false)
              $this->setParams('usuarios', $result);
         
         $this->showContents();

    }
    
    public function pesquisarResgatesPontos()
    {
        if (! \Application::isAuthorized('Troca de Pontos' , 'loja_pontos_resgates', 'ler')
                && ! \Application::isAuthorized('Troca de Pontos' , 'loja_pontos_resgates', 'escrever'))
        {
            echo json_encode(array('success' => false, 'message' => 'Você não tem permissão'));
            exit;
        }
        
        $idUsuario = (! empty($_REQUEST['idusuario'])) ? $_REQUEST['idusuario'] : null;
        $dataInicial = ($_POST['datainicial'] == '') ? '1' : Utils::formatStringDate($_POST['datainicial'], 'd/m/Y', 'Y-m-d');
        $dataFinal = ($_POST['datafinal'] == '') ? '1' : Utils::formatStringDate($_POST['datafinal'], 'd/m/Y', 'Y-m-d');
        
        $pontoTroca = new PontoTroca();
        $params = array('idUsuario' => $idUsuario, 'dataInicial' => $dataInicial, 'dataFinal' => $dataFinal, 'limit' => 10000000);
        $result = $pontoTroca->listarResgates($params);
        if ($result === false)
            $response = array('success' => false, 'message' => array());
        
        else
            $response = array('success' => true, 'message' => $result);
        
        echo json_encode($response);
        exit;
        
    }
    
    /*
    public function apagarHistoricoPontos()
    {
        if ( ! \Application::isAuthorized('Troca de Pontos' , 'loja_pontos_historico_pontos', 'remover'))
        {
            echo json_encode(array('success' => false, 'message' => 'Você não tem permissão'));
            exit;
        }
        
        $id = (! empty($_REQUEST['id'])) ? $_REQUEST['id'] : null;
        
        if ( $id === null)
            \Application::print404();
        
        $pontoTroca = new PontoTroca();
        $result = $pontoTroca->excluir($id);
        if ($result === false)
            $response = array('success' => false, 'message' => 'Não foi possível remover o registro.\nCódigo: ' . $pontoTroca->getMysqlError());
        
        else
            $response = array('success' => true, 'message' => 'Removido com sucesso');
        
        echo json_encode($response);
        exit;
    }*/





}
