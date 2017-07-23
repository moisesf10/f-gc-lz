<?php

namespace Gauchacred\controller;

require_once  \Application::getIndexPath().  '/library/php/mpdf/vendor/autoload.php' ;

use Gauchacred\library\php\Utils as Utils;
use Gauchacred\model\Usuario;
use Gauchacred\model\Adiantamento;
use Gauchacred\model\Desconto;
use \Mpdf\Mpdf as Mpdf;
/**
 * @author moises
 * @version 1.0
 * @created 10-jul-2017 20:49
 */
class Adiantamentos extends Controller
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


  public function pesquisa()
  {
    if (! \Application::isAuthorized('Adiantamentos' , 'adiantamentos_cadastro', 'ler')
            && ! \Application::isAuthorized('Adiantamentos' , 'adiantamentos_cadastro', 'escrever'))
        \Application::print404();

    $usuario = new Usuario();
    $listaUsuario = $usuario->listarUsuarios();
    if (is_array($listaUsuario))
        $this->setParams('usuarios', $listaUsuario);

    $this->setView('adiantamentos/index');
    $this->showContents();
  }

    public function cadastrarAdiantamento()
    {
        if (! \Application::isAuthorized('Adiantamentos' , 'adiantamentos_cadastro', 'ler')
                && ! \Application::isAuthorized('Adiantamentos' , 'adiantamentos_cadastro', 'escrever'))
            \Application::print404();

        $id = \Application::getUrlParams(0);


        $params = array('id' => $id);

        $adiantamento = new Adiantamento();
        if ($id != null)
            $result = $adiantamento->listarAdiantamentos($params);
        else
            $result = null;

        if (is_array($result) && count($result) > 0)
            $this->setParams('adiantamento', $result[0]);


        $usuario = new Usuario();
        $listaUsuario = $usuario->listarUsuarios();
        if (is_array($listaUsuario))
            $this->setParams('usuarios', $listaUsuario);


        $this->setView('adiantamentos/cadastrar_adiantamento');
        $this->showContents();
    }

    public function salvarAdiantamento()
    {

      if (! \Application::isAuthorized('Adiantamentos' , 'adiantamentos_cadastro', 'escrever'))
          \Application::print404();

      $usuario = (! empty($_REQUEST['usuario'])) ? $_REQUEST['usuario'] : null;
      $descricao = (! empty($_REQUEST['descricao'])) ? $_REQUEST['descricao'] : null;
      $tipoPagamento = (! empty($_REQUEST['tipopagamento'])) ? $_REQUEST['tipopagamento'] : null;
      $valorParcela = (! empty($_REQUEST['parcela'])) ? $_REQUEST['parcela'] : null;
      $qtdParcelas = (! empty($_REQUEST['quantidadeparcelas'])) ? $_REQUEST['quantidadeparcelas'] : null;
      $valorPagar = (! empty($_REQUEST['valorpagar'])) ? $_REQUEST['valorpagar'] : null;
      $id = (! empty( $_REQUEST['id'] )) ? $_REQUEST['id'] : null;

      $adiantamento = new Adiantamento();
      $result = $adiantamento->salvar($usuario, $descricao, $tipoPagamento, $valorParcela, $qtdParcelas, $valorPagar, $id);
      if ($result === false)
          $response = array('success' => false, 'message' => 'Não foi possível salvar o registro. Erro '. $adiantamento->getMysqlError());
       else
           $response = array('success' => true, 'message' => '', 'id' => $result);

      echo json_encode($response);
      exit;
    }

    public function excluirAdiantamento()
		{
      if (! \Application::isAuthorized('Adiantamentos' , 'adiantamentos_cadastro', 'remover'))
          \Application::print404();

			$id = (! empty($_REQUEST['id'])) ? $_REQUEST['id'] : null;
			$adiantamento = new Adiantamento();
      $result = $adiantamento->excluir($id);

			if ($result == false)
			{
				$response['success'] = false;
				$response['message'] = 'Não foi possível remover o registro. Código: ' . $adiantamento->getMysqlError();
			}else {
				$response['success'] = true;
				$response['message'] = 'Registro removido com sucesso';
			}

			echo json_encode($response);
			exit;
		}


    public function descontos()
    {
        if (! \Application::isAuthorized('Adiantamentos' , 'adiantamentos_descontos', 'ler')
                && ! \Application::isAuthorized('Adiantamentos' , 'adiantamentos_descontos', 'escrever'))
            \Application::print404();

        $usuario = new Usuario();
        $listaUsuario = $usuario->listarUsuarios();
        if (is_array($listaUsuario))
            $this->setParams('usuarios', $listaUsuario);

        $this->setView('adiantamentos/descontos');
        $this->showContents();
        exit;
    }


    public function relatorioDescontos()
    {
      if (! \Application::isAuthorized('Adiantamentos' , 'adiantamentos_relatorio_descontos', 'ler')
              && ! \Application::isAuthorized('Adiantamentos' , 'adiantamentos_relatorio_descontos', 'escrever'))
          \Application::print404();


          $usuario = new Usuario();
          $listaUsuario = $usuario->listarUsuarios();
          if (is_array($listaUsuario))
              $this->setParams('usuarios', $listaUsuario);

          $this->setView('adiantamentos/relatorio_descontos');
          $this->showContents();
        exit;
    }



     public function listarAdiantamentos()
    {
        if (! \Application::isAuthorized('Adiantamentos' , 'adiantamentos_descontos', 'ler')
                && ! \Application::isAuthorized('Adiantamentos' , 'adiantamentos_descontos', 'escrever'))
            \Application::print404();

        $idUsuario = (!empty($_REQUEST['usuario'])) ? $_REQUEST['usuario'] : null;
        $dataInicio = (!empty($_REQUEST['usuario'])) ? $_REQUEST['usuario'] : null;
        $dataFim = (!empty($_REQUEST['usuario'])) ? $_REQUEST['usuario'] : null;

        $dataInicio = Utils::formatStringDate($dataInicio, 'Y-m-d', 'd/m/Y');
        $dataFim = Utils::formatStringDate($dataFim, 'Y-m-d', 'd/m/Y');


        $params = array('idusuario' => $idUsuario, 'datainicio' => $dataInicio, 'datafim' => $dataFim, 'limit' => 1000000, 'encerrado' => '0');

        $adiantamento = new Adiantamento();
        $result = $adiantamento->listarAdiantamentos($params);

        if (! is_array($result))
            echo json_encode(array());
        else
            echo json_encode($result);

        exit;

    }



    public function obterJsonListaAdiantamentos()
    {
        if (! \Application::isAuthorized('Adiantamentos' , 'adiantamentos_descontos', 'ler')
                && ! \Application::isAuthorized('Adiantamentos' , 'adiantamentos_descontos', 'escrever'))
            \Application::print404();

        $idUsuario = (!empty($_REQUEST['usuario'])) ? $_REQUEST['usuario'] : null;

        if ($idUsuario == null)
            \Application::print404();

        $params = array('idusuario' => $idUsuario, 'limit' => 1000000, 'encerrado' => '0');

        $adiantamento = new Adiantamento();
        $result = $adiantamento->listarAdiantamentos($params);

        if (! is_array($result))
            echo json_encode(array());
        else
            echo json_encode($result);

        exit;

    }

    public function obterJsonListaContratos()
    {
        if (! \Application::isAuthorized('Adiantamentos' , 'adiantamentos_descontos', 'ler')
                && ! \Application::isAuthorized('Adiantamentos' , 'adiantamentos_descontos', 'escrever'))
            \Application::print404();

        $idUsuario = (!empty($_REQUEST['usuario'])) ? $_REQUEST['usuario'] : null;

        if ($idUsuario == null)
            \Application::print404();

        $params = array('idusuario' => $idUsuario, 'limit' => 1000000);

        $adiantamento = new Adiantamento();
        $result = $adiantamento->listarContratosNaoDescontados($params);

        if (! is_array($result))
            echo json_encode(array());
        else
            echo json_encode($result);

        exit;

    }

    public function obterJsonListaResumoRelatorio()
    {
        if (! \Application::isAuthorized('Adiantamentos' , 'adiantamentos_relatorio_descontos', 'ler')
                && ! \Application::isAuthorized('Adiantamentos' , 'adiantamentos_relatorio_descontos', 'escrever'))
            \Application::print404();

        $idUsuario = (!empty($_REQUEST['usuario'])) ? $_REQUEST['usuario'] : null;
        $nomeCliente = (!empty($_REQUEST['nomecliente'])) ? $_REQUEST['nomecliente'] : null;
        $dataInicio = (!empty($_REQUEST['datainicio'])) ? $_REQUEST['datainicio'] : null;
        $dataFim = (!empty($_REQUEST['datafim'])) ? $_REQUEST['datafim'] : null;

        $dataInicio = Utils::formatStringDate($dataInicio, 'Y-m-d', 'd/m/Y');
        $dataFim = Utils::formatStringDate($dataFim, 'Y-m-d', 'd/m/Y');


        $params = array('idusuario' => $idUsuario, 'limit' => 1000000, 'nomecliente' => $nomeCliente, 'datainicio' => $dataInicio, 'datafim' => $dataFim );



        $adiantamento = new Desconto();
        $result = $adiantamento->listarResumoRelatorios($params);

        if (! is_array($result))
            echo json_encode(array());
        else
            echo json_encode($result);

        exit;

    }


    public function salvarRelatorio()
    {
      if (! \Application::isAuthorized('Adiantamentos' , 'adiantamentos_descontos', 'ler')
              && ! \Application::isAuthorized('Adiantamentos' , 'adiantamentos_descontos', 'escrever'))
      {
        echo json_encode(array('success' => false, 'message' => 'Usuário sem permissão'));
        exit;
      }

      $nome = (! empty($_REQUEST['nome'])) ? $_REQUEST['nome'] : null;
      $usuario = (! empty($_REQUEST['usuario'])) ? $_REQUEST['usuario'] : null;
      $comissaoBloqueada = ( isset($_REQUEST['comissaobloqueada'])) ? $_REQUEST['comissaobloqueada'] : null;
      $contratos = json_decode($_REQUEST['contratos']);
      $descontos = json_decode($_REQUEST['descontos']);


      $desc = new Desconto();
      $result = $desc->salvarRelatorio($usuario, $nome, $comissaoBloqueada, $contratos, $descontos);

      if ($result == false)
        $response = array('success' => false, 'message' => $desc->getMysqlError());
      else
        $response = array('success' => true, 'message' => '');

      echo json_encode($response);
      exit;
    }


     public function downloadRelatorio()
    {
        if (! \Application::isAuthorized('Adiantamentos' , 'adiantamentos_relatorio_descontos', 'ler')
                && ! \Application::isAuthorized('Adiantamentos' , 'adiantamentos_relatorio_descontos', 'escrever'))
            \Application::print404();
         
         
        ini_set("memory_limit", "724M");
		ini_set('max_execution_time', 680);

        $type = (! isset($type)) ? 'pdf' : $type;
        $id = \Application::getUrlParams(0);

        if ($id == null)
            \Application::print404();

        $params = array('iddesconto' => $id, 'limit' => 1000000);
        $desc = new Desconto();
        $contratos = $desc->listarContratosDescontados($params);

        if (! is_array($contratos) || count($contratos) < 1)
            \Application::print404();

        $fileName = $contratos[0]['nomeArquivoSistema'];
        $directory = \Application::getIndexPath() . DS . 'arquivos' . DS . 'descontos';
        $filePath = $directory . DS . $fileName;



        if (! file_exists($filePath))
        {
            if (! file_exists($directory))
                mkdir($directory);




            $params = array('iddesconto' => $id, 'limit' => 1000000);
            $descontos = $desc->listarAdiantamentosDescontados($params);


            $valorTotalContrato = 0;
            $valorTotalComissao = 0;
            $htmlDinamicoContratos = '';
            if (is_array($contratos))
                foreach($contratos as $i => $value)
                {
                    $htmlDinamicoContratos .= '
                        <tr>
                            <td>'. $value['nomeBanco'] . '</td>
                            <td>'. $value['nomeConvenio'] .'</td>
                            <td>'. $value['nomeTabela'] .'</td>
                            <td>'. $value['nomeOperacao'] .'</td>
                            <td>'. $value['nomeCliente'] .'</td>
                            <td>'. $value['prazo'] .'x</td>
                            <td>R$ '. Utils::numberToMoney($value['valorParcelas']) .'</td>
                            <td>R$ '. Utils::numberToMoney($value['valorContrato']) .'</td>
                            <td>'. $value['percentualComissao'] .'</td>
                            <td>R$ '. Utils::numberToMoney($value['valorComissao']) .'</td>
                        </tr>

                    ';

                    $valorTotalComissao +=  $value['valorComissao']   ;
                    $valorTotalContrato += $value['valorContrato'];
                }



            // Descontos
            $htmlDinamicoDescontos = '';
            $valorTotalDescontado = 0;
            if (is_array($descontos))
                foreach($descontos as $i => $value)
                {
                    $htmlDinamicoDescontos .= '
                        <tr>
                            <td>'. $value['idAdiantamento'] . '</td>
                            <td>'. $value['descricao'] .'</td>
                            <td>'. $value['nomeUsuario'] .'</td>
                            <td>'. $value['parcela'] .'</td>
                            <td>R$ '. Utils::numberToMoney( $value['valorDescontado']) .'</td>
                            <td>'. Utils::formatStringDate($value['modified'], 'd/m/Y H:i:s', 'd/m/Y') .'</td>

                        </tr>

                    ';

                    $valorTotalDescontado +=  $value['valorDescontado']   ;

                }

            $valorReceber = $valorTotalComissao - $valorTotalDescontado;

            $valorReceber = Utils::numberToMoney($valorReceber );
            $valorTotalComissao = Utils::numberToMoney($valorTotalComissao );
            $valorTotalContrato = Utils::numberToMoney($valorTotalContrato);
            $valorTotalDescontado = Utils::numberToMoney($valorTotalDescontado );



            if ($type == 'pdf')
            {
                // Volta data para PT-br
               $data = Utils::formatStringDate($contratos[0]['created'], 'd/m/Y H:i:s', 'd/m/Y');
               $nomeUsuario = $contratos[0]['nomeUsuario'];

                $comissaoBloqueada = $contratos[0]['comissaoBloqueada'];

              //  $dataFinal =  Utils::formatStringDate($dataFinal, 'Y-m-d', 'd/m/Y');
                $htmlHeader =  file_get_contents(\Application::getIndexPath(). '/templates/adiantamentos/header.html');
                $htmlContent = file_get_contents(\Application::getIndexPath(). '/templates/adiantamentos/content.html');
                //echo '<pre>'. htmlentities($htmlDinamico); exit;
               $comissaoBloqueada =  ($comissaoBloqueada === true) ? 'style="background:url(\'/images/comissaoblooqueada.png\') no-repeat; background-image-resize:6;"' : '';

               // $htmlHeader = str_replace(array('{dataInicial}', '{dataFinal}', '{nomeVendedor}', '{nomeGrupo}'), array($dataInicial, $dataFinal, $nomeVendedor, $grupoVendedor), $htmlHeader);
                 $htmlContent = str_replace(array('{contentContratos}', '{valorTotalComissao}', '{valorTotalContrato}', '{data}','{comissaoBloqueada}', '{contentDescontos}', '{valorTotalDescontos}', '{valorReceber}','{data}', '{nomeUsuario}'),
                                           array($htmlDinamicoContratos, $valorTotalComissao, $valorTotalContrato, $data,  $comissaoBloqueada, $htmlDinamicoDescontos, $valorTotalDescontado, $valorReceber,
                                    $data, $nomeUsuario)
                    , $htmlContent);

                $mpdf = new Mpdf(array('format' => 'A3-L', 'margin_top' => 65));
                $mpdf->SetHTMLHeader($htmlHeader);
                $mpdf->WriteHTML($htmlContent);
                $mpdf->Output($filePath,'F');


            }else
            {

            }


        }


         header("Content-type:application/pdf");

        // It will be called downloaded.pdf
        header("Content-Disposition:attachment;filename='". $fileName ."'");

        // The PDF source is in original.pdf
        readfile($filePath);



        exit;

    }




}
