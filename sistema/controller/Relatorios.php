<?php

namespace Gauchacred\controller;

require_once  \Application::getIndexPath().  '/library/php/mpdf/vendor/autoload.php' ;

//use \controller\Controller as Controller;
//use \library\php\Blowfish as Blowfish;
use \Gauchacred\library\php\Utils as Utils;
use \Gauchacred\model\Banco as Banco;
use \Gauchacred\model\Perfil as Perfil;
use \Gauchacred\model\Recurso as Recurso;
use \Gauchacred\model\Entidade as Entidade;
use \Gauchacred\model\Roteiro as Roteiro;
use \Gauchacred\model\GrupoUsuario as GrupoUsuario;
use \Gauchacred\model\Usuario as Usuario;
use \Gauchacred\model\Tabela as Tabela;
use \Gauchacred\model\Operacao as Operacao;
use \Gauchacred\model\Subtabela as Subtabela;
use \Gauchacred\model\OperacaoSubtabela as OperacaoSubtabela;
use \Gauchacred\model\Relatorio as Relatorio;
use \Gauchacred\model\Cliente as Cliente;
use \Gauchacred\model\Contrato as Contrato;
use \Gauchacred\model\DespesasPagar;

//use \library\php\dompdf\src\Autoloader as DompdfAutoloader;
use \Mpdf\Mpdf as Mpdf;
/**
 * @author moises
 * @version 1.0
 * @created 13-set-2015 23:08:59
 */
class Relatorios extends Controller
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
        //echo 'moasdasdas'; exit;
    }
    
    public function tabelas()
    {
        if (! \Application::isAuthorized(ucfirst(strtolower(\Application::getNameController())) , 'relatorio_tabelas', 'ler')  
                    && ! \Application::isAuthorized(ucfirst(strtolower(\Application::getNameController())) , 'relatorio_tabelas', 'escrever')
           )
            \Application::print404();
        
        $this->setView('relatorios/tabelas/tabelas');
        
        $subtabela = new Subtabela();
        $result = $subtabela->listarSubtabelas(null, null, null, 10000);
        if ($result !== false)
            $this->setParams('tabelas', $result);
        
        $grupoUsuario = new GrupoUsuario();
        $return = $grupoUsuario->gruposDoUsuario($_SESSION['userid']);
        if ($return !== false)
            $this->setParams('gruposdousuario', $return);
        
        $this->showContents();
    }
    
    public function comissaoVendedor()
    {
        if (! \Application::isAuthorized(ucfirst(strtolower(\Application::getNameController())) , 'relatorio_comissao_vendedor', 'ler')  
                    && ! \Application::isAuthorized(ucfirst(strtolower(\Application::getNameController())) , 'relatorio_comissao_vendedor', 'escrever')
           )
            \Application::print404();
        
        $this->setView('relatorios/comissaovendedor/index');
        
      //  $subtabela = new Subtabela();
       // $result = $subtabela->listarSubtabelas(null, null, null, 10000);
      //  if ($result !== false)
        //    $this->setParams('tabelas', $result);
        
        $usuarios = new Usuario();
        $result = $usuarios->listarUsuarios();
        if ($result !== false)
            $this->setParams('usuarios', $result);
        
        $operacao = new Operacao();
        $result = $operacao->listarOperacoes();
        if ($result !== false)
            $this->setParams('operacoes', $result);
        
        $this->showContents();
    }
    
    public function gerarComissaoVendedor()
    {
        $usuario = (isset($_POST['usuario']))? $_POST['usuario'] : null;
        $status = (isset($_POST['status']) && $_POST['status'] != '') ? $_POST['status'] : null;
        $comissaoBloqueada = (isset($_POST['comissaobloqueada'])) ? true : false;
        $dataInicial = ($_POST['datainicial'] == '') ? '0001-01-01' : Utils::formatStringDate($_POST['datainicial'], 'd/m/Y', 'Y-m-d');
        $dataFinal = ($_POST['datafinal'] == '') ? '2100-01-01' : Utils::formatStringDate($_POST['datafinal'], 'd/m/Y', 'Y-m-d');
        $dataPagamentoInicio = ($_POST['datapagamentoinicio'] == '') ? null : Utils::formatStringDate($_POST['datapagamentoinicio'], 'd/m/Y', 'Y-m-d');
        $dataPagamentoFim = ($_POST['datapagamentofim'] == '') ? null : Utils::formatStringDate($_POST['datapagamentofim'], 'd/m/Y', 'Y-m-d');
        $dataBancoInicio = ($_POST['databancoinicio'] == '') ? null : Utils::formatStringDate($_POST['databancoinicio'], 'd/m/Y', 'Y-m-d');
        $dataBancoFim = ($_POST['databancofim'] == '') ? null : Utils::formatStringDate($_POST['databancofim'], 'd/m/Y', 'Y-m-d');
        $observacoes = (isset($_POST['observacoes'])) ? $_POST['observacoes'] : '';
        $operacao = (! empty($_POST['operacao'])) ? $_POST['operacao'] : null;
        $statusPagamento = (! empty($_POST['statuspagamento'])) ? $_POST['statuspagamento'] : null;
        $statusBanco = (! empty($_POST['statusbanco'])) ? $_POST['statusbanco'] : null;
        $type = $_POST['type'];
        
        
        $r = new Relatorio();
        
        //var_dump($statusBanco); exit;
        $relatorio = $r->comissaoVendedor($usuario, $status, $dataInicial, $dataFinal, $operacao, $dataPagamentoInicio, $dataPagamentoFim, $statusPagamento, $dataBancoInicio, $dataBancoFim, $statusBanco);
        
        if (isset($relatorio[0]))
        {
            $nomeVendedor = $relatorio[0]['nomeUsuario'];
            $grupoVendedor = $relatorio[0]['nomeGrupo'];
        }else
        {
            $nomeVendedor = '';
            $grupoVendedor = '';
        }
        
        if ($type == 'pdf')
        {
           
            $valorTotalContrato = 0;
            $valorTotalComissao = 0;
            $htmlDinamico = '';
            if (is_array($relatorio))
                foreach($relatorio as $i => $value)
                {
                    $htmlDinamico .= '
                        <tr>
                            <td>'. $value['codigoBancoConvenio'] .' - '. $value['nomeBancoConvenio'] . '</td>
                            <td>'. $value['nomeConvenio'] .'</td>
                            <td>'. $value['nomeTabela'] .'</td>
                            <td>'. $value['nomeOperacao'] .'</td>
                            <td>'. $value['nomeCliente'] .'</td>
                            <td>'. $value['quantidadeParcelas'] .'x</td>
                            <td>R$ '. Utils::numberToMoney($value['valorParcela']) .'</td>
                            <td>R$ '. Utils::numberToMoney($value['valorTotal']) .'</td>
                            <td>'. $value['percentualGrupo'] .'</td>
                            <td>R$ '. Utils::numberToMoney($value['valorTotal'] * ($value['percentualGrupo']/100)) .'</td>
                        </tr>
                        
                    ';
                    
                    $valorTotalComissao += ( $value['valorTotal'] * ($value['percentualGrupo']/100))   ;
                    $valorTotalContrato += $value['valorTotal'];
                }
            
            $valorTotalComissao = Utils::numberToMoney($valorTotalComissao );
            $valorTotalContrato = Utils::numberToMoney($valorTotalContrato);
            // Volta data para PT-br
            $dataInicial = Utils::formatStringDate($dataInicial, 'Y-m-d', 'd/m/Y');
            $dataFinal =  Utils::formatStringDate($dataFinal, 'Y-m-d', 'd/m/Y');
            $htmlHeader =  file_get_contents(\Application::getIndexPath(). '/templates/relatorios/pdf/comissaovendedor/header.html');
            $htmlContent = file_get_contents(\Application::getIndexPath(). '/templates/relatorios/pdf/comissaovendedor/content.html');
            //echo '<pre>'. htmlentities($htmlDinamico); exit;
            $comissaoBloqueada =  ($comissaoBloqueada == true) ? 'style="background:url(\'/images/comissaoblooqueada.png\') no-repeat; background-image-resize:6;"' : '';
            
            $htmlHeader = str_replace(array('{dataInicial}', '{dataFinal}', '{nomeVendedor}', '{nomeGrupo}'), array($dataInicial, $dataFinal, $nomeVendedor, $grupoVendedor), $htmlHeader);
            $htmlContent = str_replace(array('{content}', '{valorTotalComissao}', '{valorTotalContrato}', '{observacao}','{comissaoBloqueada}'), 
                                       array($htmlDinamico, $valorTotalComissao, $valorTotalContrato, $observacoes,  $comissaoBloqueada)
                , $htmlContent);
            $mpdf = new Mpdf(array('format' => 'A3-L', 'margin_top' => 85));
            $mpdf->SetHTMLHeader($htmlHeader);
            $mpdf->WriteHTML($htmlContent);
            $mpdf->Output();
            
            
        }else
        {
            
        }
        
        
    }
    
    
    /**
    * CLIENTES INDIVIDUAL
    * 
    * */
     
    
      public function listarClienteIndividual()
    {
        if (! \Application::isAuthorized(ucfirst(strtolower(\Application::getNameController())) , 'relatorio_cliente_individual', 'ler')  
                    && ! \Application::isAuthorized(ucfirst(strtolower(\Application::getNameController())) , 'relatorio_cliente_individual', 'escrever')
           )
            \Application::print404();
        
        $this->setView('relatorios/clientesindividual/index');
        
      //  $subtabela = new Subtabela();
       // $result = $subtabela->listarSubtabelas(null, null, null, 10000);
      //  if ($result !== false)
        //    $this->setParams('tabelas', $result);
        
        $cliente = new Cliente();
        $result = $cliente->carregar('%', 100000, 2, 'asc', true);
        if ($result !== false)
            $this->setParams('clientes', $result);
        
        $this->showContents();
    }
    
    
     public function gerarClienteIndividual()
    {
        $cpf = (isset($_POST['cliente']))? $_POST['cliente'] : null;
        $observacoes = (isset($_POST['observacoes'])) ? $_POST['observacoes'] : '';
        $type = $_POST['type'];
         
         
         $c = new Cliente();
         $cliente = $c->carregar($cpf, 100000, 2, 'asc', true);
         $cliente = $cliente[0];
         
         $con = new Contrato();
         $contrato = $con->listarContratos(null, $cpf, null, null, null,  null, null, null, null, null,1000000, null, null, null, null,null, 1, 'desc', null, null, null, true);
         
         //echo '<pre>';var_dump($contrato); exit;
         if ($type == 'pdf')
         {
             
             
             $htmlHeader =  file_get_contents(\Application::getIndexPath(). '/templates/relatorios/pdf/clienteindividual/header.html');
             $htmlContent = file_get_contents(\Application::getIndexPath(). '/templates/relatorios/pdf/clienteindividual/content.html');
             
             $htmlTelefones = '';
             if (is_array($cliente['telefones']))
                 foreach($cliente['telefones'] as $i => $value)
                     $htmlTelefones .= '<div class="telefones"><div style="width: 30%">'. $value['numero'] .'</div><div style="width: 30%">'.$value['referencia'] .'</div></div>';
             
             $htmlContasBancarias = '';
             if (is_array($cliente['contas']))
                 foreach($cliente['contas'] as $i => $value)
                     $htmlContasBancarias .= '<div class="contasBancarias"> <div style="width: 25%"><span>Banco: </span>'. $value['nomeBanco'].'</div> <div style="width: 15%"><span>Agencia: </span>'. $value['agencia'] .'</div><div style="width: 25%"><span>Conta: </span>'.$value['conta']  .'</div> <div style="width: 30%"><span>Tipo de Conta: </span>'.$value['descricaoConta'] .'</div></div>';
             
             $htmlContratos = '';
             if (is_array($contrato))
                 foreach($contrato as $i => $value)
                     $htmlContratos .= '
                     <tr>
                         <td>'. $value['nomeConvenio'].'</td>
                         <td>Contrato n&ordm; '. $value['numeroContrato'] .'</td>
                         <td>'.$value['nomeBancoContrato'] .'</td>
                         <td>R$ '. Utils::numberToMoney($value['valorLiquido']) .'</td>
                         <td>R$ '. Utils::numberToMoney($value['valorTotal']) .'</td>
                         <td>'. $value['nomeOperacao'] .'</td>
                          <td>'. $value['quantidadeParcelas'] .'x</td>
                           <td>R$ '. Utils::numberToMoney($value['valorParcela']) .'</td>
                         <td> R$ '. Utils::numberToMoney($value['valorSeguro']) .'</td>
                         <td>'. Utils::formatStringDate( $value['created'], 'd/m/Y H:i:s', 'd/m/Y') .'</td>
                         <td>'. ((strtolower($value['status']) == 'recebido comissão do banco') ? 'Pago ao Cliente' : $value['status']) .'</td>
                    </tr>';
             
             
             $htmlContent = str_replace(array(
                 '{nomeCliente}', '{cpfCliente}', '{endereco}', '{numeroEndereco}', '{bairro}', '{cep}', '{cidade}', '{uf}', '{telefones}', '{contasBancarias}', '{tabela}', '{observacoes}'
                ), array(
                 $cliente['dados']['nomeCliente'], $cpf, $cliente['dados']['rua'], $cliente['dados']['numeroRua'], $cliente['dados']['bairro'],
                 $cliente['dados']['cep'], $cliente['dados']['cidade'], $cliente['dados']['uf'], $htmlTelefones, $htmlContasBancarias, $htmlContratos, $observacoes
                ), $htmlContent
             );
             
             
             $mpdf = new Mpdf(array('format' => 'A3', 'margin_top' => 75, 'margin_left' => 5, 'margin_right' => 5));
             $mpdf->SetHTMLHeader($htmlHeader);
             $mpdf->WriteHTML($htmlContent);
             $mpdf->Output();
         }
         
     }
    
    
    
    /**
    * COMISSÃO LOJA
    * 
    * */
     
    
      public function comissaoLoja()
    {
        if (! \Application::isAuthorized(ucfirst(strtolower(\Application::getNameController())) , 'relatorio_comissao_loja', 'ler')  
                    && ! \Application::isAuthorized(ucfirst(strtolower(\Application::getNameController())) , 'relatorio_comissao_loja', 'escrever')
           )
            \Application::print404();
        
        $this->setView('relatorios/comissaoloja/index');
        
      //  $subtabela = new Subtabela();
       // $result = $subtabela->listarSubtabelas(null, null, null, 10000);
      //  if ($result !== false)
        //    $this->setParams('tabelas', $result);
          
          $banco = new Banco();
          $result = $banco->listarBancos();
          if ($result !== false)
              $this->setParams('bancos', $result);
          
          $entidade = new Entidade();
          $result = $entidade->listarEntidades();
          if ($result !== false)
              $this->setParams('convenios', $result);
          
          $operacao = new Operacao();
          $result = $operacao->listarOperacoes();
          if ($result !== false)
              $this->setParams('operacoes', $result);
          
          $usuario = new Usuario();
          $result = $usuario->listarUsuarios();
          if ($result !== false)
              $this->setParams('usuarios', $result);
          
        
        $cliente = new Cliente();
        $result = $cliente->carregar('%', 100000, 2, 'asc', true);
        if ($result !== false)
            $this->setParams('clientes', $result);
        
        $this->showContents();
    }
    
     public function gerarComissaoLoja()
    {
        
         $idConvenio = (empty($_POST['convenio'])) ? null : $_POST['convenio'];
         $idOperacao = (empty($_POST['operacao']))? null : $_POST['operacao'];
         $status = (empty($_POST['status']) || empty($_POST['status'][0]) )? null : $_POST['status'];
         $banco = (empty($_POST['banco']))? null : $_POST['banco'];
         $usuario = (empty($_POST['usuario']))? null : $_POST['usuario'];
		 //var_dump($status); exit;
         $dataInicial = ($_POST['datainicial'] == '') ? '0001-01-01' : Utils::formatStringDate($_POST['datainicial'], 'd/m/Y', 'Y-m-d');
         $dataFinal = ($_POST['datafinal'] == '') ? '2100-01-01' : Utils::formatStringDate($_POST['datafinal'], 'd/m/Y', 'Y-m-d');
		 
		$dataPagamentoInicio = (empty($_POST['datapagamentoinicio']) ) ? null : Utils::formatStringDate($_POST['datapagamentoinicio'], 'd/m/Y', 'Y-m-d');
		$dataPagamentoFim = ( empty($_POST['datapagamentofim'])) ? null : Utils::formatStringDate($_POST['datapagamentofim'], 'd/m/Y', 'Y-m-d');
        $dataBancoInicio = ($_POST['databancoinicio'] == '') ? null : Utils::formatStringDate($_POST['databancoinicio'], 'd/m/Y', 'Y-m-d');
        $dataBancoFim = ($_POST['databancofim'] == '') ? null : Utils::formatStringDate($_POST['databancofim'], 'd/m/Y', 'Y-m-d');
        
		$statusPagamento = (! empty($_POST['statuspagamento'])) ? $_POST['statuspagamento'] : null;
		 $statusBanco = (! empty($_POST['statusbanco'])) ? $_POST['statusbanco'] : null;
        $type = $_POST['type'];
         
         
         $gu = new GrupoUsuario();
         $grupoUsuario = $gu->gruposDoUsuario($_SESSION['userid']);
         $grupoUsuario = (isset($grupoUsuario[0])) ? $grupoUsuario[0] : null;
         
         $rel = new Relatorio();
         $contratos = $rel->comissaoLoja($idConvenio, $idOperacao, $dataInicial, $dataFinal, $status, $banco, $usuario, $statusPagamento, $dataPagamentoInicio, $dataPagamentoFim,
                                        $dataBancoInicio, $dataBancoFim, $statusBanco);
        
        // Corrige data a ser renderizada como período do relatório
        if ($dataInicial == '0001-01-01')
            if ($dataPagamentoInicio != null)
                $dataInicial = $dataPagamentoInicio;
            else
                if ($dataBancoInicio != null)
                    $dataInicial = $dataBancoInicio;
        
        if ($dataFinal == '2100-01-01')
            if ($dataPagamentoFim != null)
                $dataFinal = $dataPagamentoFim;
            else
                if ($dataBancoFim != null)
                    $dataFinal = $dataBancoFim;
         
       //echo '<pre>';var_dump($contratos); exit;
     
         if ($type == 'pdf')
         {
             
             
             $htmlHeader =  file_get_contents(\Application::getIndexPath(). '/templates/relatorios/pdf/comissaoloja/header.html');
             $htmlContent = file_get_contents(\Application::getIndexPath(). '/templates/relatorios/pdf/comissaoloja/content.html');
             
            $stylesheet = file_get_contents( \Application::getIndexPath(). '/library/jsvendor/bootstrap/css/bootstrap.min.css');
             
             $nomeUsuario = $_SESSION['nome'];
             $nomeGrupoUsuario = (isset($grupoUsuario['nome'])) ? $grupoUsuario['nome'] : '';
             $dataInicial = Utils::formatStringDate($dataInicial, 'Y-m-d', 'd/m/Y');
            $dataFinal =  Utils::formatStringDate($dataFinal, 'Y-m-d', 'd/m/Y');
            // var_dump($grupoUsuario); exit;
             $arrayGrupos = array();
             $tHeadGrupos = '';
             //echo '<pre>';var_dump($contratos);
             //foreach($contratos as $j )
            //     sort($contratos[$j]['grupos'], 'nomeGrupo', SORT_ASC);
           //  var_dump($contratos['grupos']);
             
           //  exit;
             
             $cabecOrder = array(); // indica a ordem do cabecalho
             if (is_array($contratos))
                 foreach($contratos as $i => $value)
                     if (is_array($value['grupos']))
                         foreach($value['grupos'] as $j => $value2)
                         {
                             //  var_dump( $value2['nomeGrupo']    );
                             //echo '<pre>'; var_dump( $value2['recebeDe']   ); echo '<pre>';
                           //  echo '<pre>'; var_dump( array_search($value2['nomeGrupo'],  array_column($value['comissoes'], 'nomeGrupo'))   ); echo '<pre>';
                                if (strpos($tHeadGrupos, $value2['nomeGrupo']) === false
                                   && ( array_search($value2['nomeGrupo'],  array_column($value['comissoes'], 'nomeGrupo'))   !== false 
                                                    || (in_array($grupoUsuario['id'], explode(',',$value2['recebeDe'])  ) === true    
                                                         && array_search($value2['nomeGrupo'],  array_column($value['comissoes'], 'nomeGrupo'))   !== false 
                                                            )
                                        )
                                   // && array_search($value2['nomeGrupo'],  array_column($value['comissoes'], 'nomeGrupo'))   !== false
                                   )
                                   {
                                   // echo 'entrou<br>';
                                        $tHeadGrupos .= '<th>'. $value2['nomeGrupo'] . '</th>';
                                        array_push($cabecOrder, $value2['nomeGrupo']);                                   
                                    }
                             
                                if ( (array_search($value2['nomeGrupo'],  array_column($arrayGrupos, 'nomeGrupo')  )  === false)
                                   && ( array_search($value2['nomeGrupo'],  array_column($value['comissoes'], 'nomeGrupo'))   !== false 
                                                    || (in_array($grupoUsuario['id'], explode(',',$value2['recebeDe'])  ) === true     
                                                            && array_search($value2['nomeGrupo'],  array_column($value['comissoes'], 'nomeGrupo'))   !== false 
                                                        )
                                         )
                                   )
                                    array_push($arrayGrupos, array('nomeGrupo' => $value2['nomeGrupo'], 'valor' => 0));
                             
                         }
                     //  exit;
            // echo '<pre>';var_dump($arrayGrupos); exit;
             
             $tContent = '';
			 $vTotalContrato = 0;
             $vComissaoRecebido = 0;
             $vComissaoLoja = 0;
             $vValorImposto = 0;
             if (is_array($contratos))
             {
                 foreach($contratos as $i => $value)
                 {
                     $keyUsuario = array_search($value['idUsuario'], array_column($value['comissoes'], 'idUsuario'));
                     
                     $percentualUsuario = ($keyUsuario !== false)  ? $value['comissoes'][$keyUsuario]['percentualGrupo'] : 0;
					 $vTotalContrato += $value['valorTotal'];
                     $vComissaoRecebido += (($value['comissaoTotal']/100) * $value['valorTotal']);
                     $vComissaoLoja += (($value['percentualLoja']/100) * $value['valorTotal']);
                     $vValorImposto +=  (($value['percentualImposto']/100) * (($value['comissaoTotal']/100) * $value['valorTotal']))       ;
                      $tContent .= '
                         <tr>
                             <td>'. $value['nomeBancoContrato'] .'</td>
                             <td>'. $value['nomeConvenio'] .'</td>
                             <td>'. $value['nomeTabela'] .'</td>
                             <td>'. $value['nomeOperacao'] .'</td>
                             <td>' . $value['nomeCliente']  .'</td>
                             <td>R$ '. Utils::numberToMoney( $value['valorTotal']) .'</td>
                             <td>'. Utils::numberToMoney($value['comissaoTotal']) .'%</td>
                             <td>R$ '. Utils::numberToMoney(($value['valorTotal'] * ($value['comissaoTotal']/100))) .'</td>
                             <td>'. Utils::numberToMoney( $value['percentualImposto']) .'%</td>
                        ';
                     $qtdComumnWrite = 0;
                     if (is_array($value['grupos']))
					 {
                         $auxtContent = array();
                         foreach($value['grupos'] as $a => $perc)
                         {
                            
                             if (
									( array_search($perc['nomeGrupo'],  array_column($value['comissoes'], 'nomeGrupo'))   !== false 
                                        || (in_array($grupoUsuario['id'], explode(',',$perc['recebeDe'])  ) === true     )
                                                && array_search($perc['nomeGrupo'],  array_column($value['comissoes'], 'nomeGrupo'))   !== false 
                                    )
                             )
							 {
                                 $ordpos = array_search($perc['nomeGrupo'], $cabecOrder ) ;
                                 
                                 if($ordpos  !== false )
                                 {
                                     $auxtContent[$ordpos] = '<td>'. Utils::numberToMoney($perc['comissao']) . '%</td>';
                                 }
                                 //$tContent .= '<td>'. Utils::numberToMoney($perc['comissao']) . '%</td>';
								 //$qtdComumnWrite++;
							 }
                             else
                                 if ( array_search($perc['nomeGrupo'],  array_column($arrayGrupos, 'nomeGrupo'))    )
								 {
                                     $ordpos = array_search($perc['nomeGrupo'], $cabecOrder ) ;
                                     //var_dump($ordpos); echo '  mois   ';
                                     if($ordpos  !== false )
                                     {
                                         $auxtContent[$ordpos] = '<td>'. Utils::numberToMoney('0') . '%</td>';
                                     }
                                     
                                     
                                     //$tContent .= '<td>'. Utils::numberToMoney('0') . '%</td>';
									 //$qtdComumnWrite++;
								 }
                             
                             if (is_array($arrayGrupos))
                                 foreach($arrayGrupos as $p => $d)
                                    if ($d['nomeGrupo'] == $perc['nomeGrupo'])
                                        $arrayGrupos[$p]['valor'] +=  ($value['valorTotal'] * ($perc['comissao']/100));
                         }
						 
			             // ordena o array com os grupos já na posição correta
                        
                         for ($posauxc = 0; $posauxc < count($cabecOrder); $posauxc++)
                             if (!isset($auxtContent[$posauxc]) || $auxtContent[$posauxc] == '')
                                 $auxtContent[$posauxc] = '<td>'. Utils::numberToMoney('0') . '%</td>';
						 
                          
					 }//else
                       // $tContent .= '<td>'. Utils::numberToMoney('0') . '%</td>';
                     // insere os grupos na linha
                     ksort($auxtContent);
                    // echo '<pre>';var_dump($cabecOrder);
                    // var_dump($auxtContent);
                    // var_dump(htmlentities(implode('', $auxtContent)));
                    // echo '< /pre>';
                     
                     $tContent .= implode('', $auxtContent);
                     $tContent .= '</tr>';
                 }//exit;
                
             }
             
            // echo '<pre>'.  htmlentities($tContent); exit;
              
             // normaliza os totais
             $vComissaoGrupos = '';
             if (is_array($arrayGrupos))
                foreach($arrayGrupos as $i => $value)
                    $vComissaoGrupos .= '<b>Valor de comiss&atilde;o do grupo '. $value['nomeGrupo'] .':</b> R$ '. Utils::numberToMoney( $value['valor']) .'<br />';
             
             $vTotalContrato = Utils::numberToMoney( $vTotalContrato);
             $vComissaoLoja = Utils::numberToMoney($vComissaoLoja);
             $vValorImposto = Utils::numberToMoney($vValorImposto);
             $vComissaoRecebido = Utils::numberToMoney($vComissaoRecebido);
             $htmlContent = str_replace(array(
                 '{dataInicial}', '{dataFinal}', '{nomeUsuario}', '{nomeGrupoUsuario}', '{tHeadGrupos}', '{tContent}', '{vComissaoGrupos}', '{vComissaoLoja}', '{vComissaoRecebido}',
                 '{vValorImposto}','{vTotalContrato}'
                ), array(
                     $dataInicial, $dataFinal,  $nomeUsuario, $nomeGrupoUsuario, $tHeadGrupos, $tContent, $vComissaoGrupos, $vComissaoLoja, $vComissaoRecebido, $vValorImposto, $vTotalContrato
                ), $htmlContent
             );
             
             
             
             
             
             $mpdf = new Mpdf(array('format' => 'A2-L', 'margin_top' => 95, 'margin_left' => 5, 'margin_right' => 5));
             $mpdf->SetTitle('Comissões da Loja');
            $mpdf->SetHTMLHeader($htmlHeader);
            $mpdf->packTableData = true;
             $mpdf->WriteHTML($htmlContent);
             $mpdf->Output();
         }
         
     }
    
    
    
     
    /**
    * COMISSÃO GRUPO
    * 
    * */
     
    
      public function comissaoGrupo()
    {
        if (! \Application::isAuthorized(ucfirst(strtolower(\Application::getNameController())) , 'relatorio_comissao_grupo', 'ler')  
                    && ! \Application::isAuthorized(ucfirst(strtolower(\Application::getNameController())) , 'relatorio_comissao_grupo', 'escrever')
           )
            \Application::print404();
        
        $this->setView('relatorios/comissaogrupos/index');
        
      //  $subtabela = new Subtabela();
       // $result = $subtabela->listarSubtabelas(null, null, null, 10000);
      //  if ($result !== false)
        //    $this->setParams('tabelas', $result);
        
        $grupos = new GrupoUsuario();
        $result = $grupos->listarGrupos('%','%', 100000);
        if ($result !== false)
            $this->setParams('grupos', $result);
        
        $this->showContents();
    }
    
      public function gerarComissaoGrupo()
    {
        
         $idGrupo = (empty($_POST['grupo'])) ? null : $_POST['grupo'];

         $dataInicio = ($_POST['datainicial'] == '') ? '0001-01-01' : Utils::formatStringDate($_POST['datainicial'], 'd/m/Y', 'Y-m-d');
         $dataFim = ($_POST['datafinal'] == '') ? '2100-01-01' : Utils::formatStringDate($_POST['datafinal'], 'd/m/Y', 'Y-m-d');
        $type = $_POST['type'];
         
          $gr = new GrupoUsuario();
          $grupos = $gr->listarGrupos($idGrupo); 
          $grupos = (isset($grupos[0])) ? $grupos[0] : false;
         
          $rel = new Relatorio();
          $comissao = $rel->comissaoGrupo($idGrupo, $dataInicio, $dataFim);
          
          
           $nomeGrupo = ( isset($grupos['nome'])) ? $grupos['nome'] : '';
     
         if ($type == 'pdf')
         {
             
             
             $htmlHeader =  file_get_contents(\Application::getIndexPath(). '/templates/relatorios/pdf/comissaogrupos/header.html');
             $htmlContent = file_get_contents(\Application::getIndexPath(). '/templates/relatorios/pdf/comissaogrupos/content.html');
             
            $dataInicio = Utils::formatStringDate($dataInicio, 'Y-m-d', 'd/m/Y');
            $dataFim =  Utils::formatStringDate($dataFim, 'Y-m-d', 'd/m/Y');
             
             $contentGrupo = '';
             $valorComissao = 0;
             if (is_array($comissao))
                 foreach($comissao as $i => $value)
                 {
                     $valorComissao += $value['valorComissao'];
                     $nomeUsuario = explode(' ', $value['nomeUsuario'] );
                     $nomeUsuario = (isset($nomeUsuario[0])) ? $nomeUsuario[0] : '';
                     $contentGrupo .= '
                            <div class="box-content">
                                <div class="nome">'. $nomeUsuario .'</div>
                                <div>
                                        <label>TOTAL DE VENDAS</label>
                                        <div class="valor">R$ '. Utils::numberToMoney( $value['valorVenda'])   .'</div>
                                </div>
                            </div>
                     ';
                 }
             
             $valorComissao = Utils::numberToMoney($valorComissao);
            
             $htmlContent = str_replace(array(
                 '{nomeGrupo}', '{dataInicial}', '{dataFinal}', '{contentGrupo}', '{valorComissao}'
                ), array(
                     $nomeGrupo, $dataInicio, $dataFim, $contentGrupo, $valorComissao
                ), $htmlContent
             );
            
             
             
             
             $mpdf = new Mpdf(array('format' => 'A3-L', 'margin_top' => 75, 'margin_left' => 5, 'margin_right' => 5));
             $mpdf->SetTitle('Comissões do Grupo');
            $mpdf->SetHTMLHeader($htmlHeader);
           //  $mpdf->WriteHTML($stylesheet,1);
             $mpdf->WriteHTML($htmlContent);
             $mpdf->Output();
         }
         
     }
    
    /**
    * CLIENTE FICHARIO
    * 
    * */
     
    
      public function clienteFichario()
    {
        if (! \Application::isAuthorized(ucfirst(strtolower(\Application::getNameController())) , 'relatorio_cliente_fichario', 'ler')  
                    && ! \Application::isAuthorized(ucfirst(strtolower(\Application::getNameController())) , 'relatorio_cliente_fichario', 'escrever')
           )
            \Application::print404();
        
        $this->setView('relatorios/clientesfichario/index');
        
      //  $subtabela = new Subtabela();
       // $result = $subtabela->listarSubtabelas(null, null, null, 10000);
      //  if ($result !== false)
        //    $this->setParams('tabelas', $result);
        
        $entidade = new Entidade();
        $result = $entidade->listarEntidades();
        if ($result !== false)
            $this->setParams('convenios', $result);
			
		$usuarios = new Usuario();
		$result = $usuarios->listarUsuarios();
		if ($result !== false)
			$this->setParams('usuarios', $result);
			
        
        $this->showContents();
    }
    
    
    public function pesquisarClienteFichario()
    {
        if (! \Application::isAuthorized(ucfirst(strtolower(\Application::getNameController())) , 'relatorio_cliente_fichario', 'ler')  
                    && ! \Application::isAuthorized(ucfirst(strtolower(\Application::getNameController())) , 'relatorio_cliente_fichario', 'escrever')
           )
        {
            header("HTTP/1.0 404 Not Found");
            exit;
        }
        
        
        $convenio = $_REQUEST['convenio'];
		$usuario = (empty($_REQUEST['usuario'])) ? null : $_REQUEST['usuario'];
        $dataInicial = Utils::formatStringDate($_REQUEST['datainicial'], 'd/m/Y', 'Y-m-d'); 
        $dataFinal = Utils::formatStringDate($_REQUEST['datafinal'], 'd/m/Y', 'Y-m-d'); 
        
        $relatorio = new Relatorio();
        $result = $relatorio->clientesFichario(null,  $convenio, $dataInicial, $dataFinal, null, null, $usuario);
        if ($result === false)
            $json = array();
        else
            $json = $result;
        
        echo json_encode($json);
        
        
    }
    
    
    public function gerarClienteFichario()
    {
		ini_set("memory_limit", "512M");
		ini_set('max_execution_time', 680);
		//ini_set('pcre.backtrack_limit', 1300000);
		
        $convenio = (empty($_POST['convenio'])) ? null : $_POST['convenio'];
		$usuario = (empty($_POST['usuario'])) ? null : $_POST['usuario'];
        $dataInicial =   (empty($_POST['datainicial'])) ? null :  Utils::formatStringDate($_POST['datainicial'], 'd/m/Y', 'Y-m-d'); 
        $dataFinal = (empty($_POST['datafinal'])) ? null : Utils::formatStringDate($_POST['datafinal'], 'd/m/Y', 'Y-m-d'); 
        $nomeIncial = (empty($_POST['nomeinicial'])) ? null : $_POST['nomeinicial'];
        $nomeFinal = (empty($_POST['nomefinal'])) ? null : $_POST['nomefinal'];
        $type = $_POST['type'];
        
        $r = new Relatorio();
        $relatorio = $r->clientesFichario(null,  $convenio, $dataInicial, $dataFinal, $nomeIncial, $nomeFinal, $usuario);
       //echo '<pre>'; var_dump($relatorio); exit;
        switch($type)
        {
            case 'pdf':
                
                
                $htmlHeader =  file_get_contents(\Application::getIndexPath(). '/templates/relatorios/pdf/clientefichario/header.html');
                $htmlContent = file_get_contents(\Application::getIndexPath(). '/templates/relatorios/pdf/clientefichario/content.html');
                $templateContent = file_get_contents(\Application::getIndexPath(). '/templates/relatorios/pdf/clientefichario/templatecontent.html');
                
                if (! is_array($relatorio) || count($relatorio) == 0)
                    $content = '';
                else
                {
                    $content = '';
                    $cont = 1;
                    foreach($relatorio as $i => $value)
                    {
                        $htmlCliente = $templateContent;
                        
                        // Busca os convenios
                        $entidades = '';
                        if (is_array($value['convenios']))
                        {
                            $pos = 1;
                            foreach($value['convenios'] as $b => $convenio)
                            {
                                $entidade = '';
                                if (! empty($convenio['nomeConvenio']))
                                {
                                    if ($pos == 1)
                                        $entidade .= '<div class="row">';
                                    $entidade .= '
                                            <div class="col-md-2 right"><b>Entidade</b></div>
                                            <div class="col-md-3">'.$convenio['nomeConvenio']  .'</div>  
                                    ';
                                    if ($pos == 2 || ! isset($value['convenios'][$b + 1]))
                                    {
                                        $entidade .= '</div>';
                                        $pos = 1;   
                                    }else
                                        $pos++;
                                    
                                }
                                
                                $entidades .= $entidade;
                            }
                        }
                        
                        
                        // Busca os telefones
                        
                        $telefones = '';
                        if (is_array($value['telefones']))
                        {
                           
                            foreach($value['telefones'] as $b => $tel)
                            {
                                if (! empty($tel['numero']))
                                    $telefones .= '<div class="row">
                                                <div class="col-md-2 right"><b>Telefone</b></div>
                                                <div class="col-md-3">'.$tel['numero']  .'</div>  
                                                <div class="col-md-2 right"><b>Referência</b></div>
                                                <div class="col-md-3">'. $tel['referencia'] .'</div>
                                            </div>
                                    ';
                                   
                                    
                                
                                
                                
                            }
                        }
                        
                        // REPLACES DOS CAMPOS DO TEMPLATECONTENT
                        
                        $htmlCliente = str_replace(array(
                             '{nomeCliente}', '{cpfCliente}','{entidades}', '{telefones}', '{endereco}', '{bairro}', '{cidade}', '{uf}', '{cep}', 'observacao'
                                ), array(
                                    $value['dados']['nomeCliente'], 
                                    $value['dados']['cpf'],
                                    $entidades,
                                    $telefones,
                                    $value['dados']['rua'] . ', ' . $value['dados']['numeroRua'],
                                    $value['dados']['bairro'],
                                    $value['dados']['cidade'],
                                    $value['dados']['uf'],
                                    $value['dados']['cep'],
                                    ''
                                ), $htmlCliente
                        );
                        
                        $content .= $htmlCliente;
						if ($cont == 3 && isset($relatorio[$i + 1]))
						{
							$content .= '<pagebreak>';
							$cont = 1;
						}
						else
							$cont++;
                    }
                    
                }
                
                
                // REPLACE DO CONTENT
                $htmlContent = str_replace(array(
                 '{content}'
                    ), array(
                         $content
                    ), $htmlContent
                 );
                 //var_dump(html_entities($htmlContent)); exit;
                $mpdf = new Mpdf(array('format' => 'A4', 'margin_top' => 75, 'margin_left' => 5, 'margin_right' => 5));
                $mpdf->SetTitle('Cliente Fichário');
                $mpdf->SetHTMLHeader($htmlHeader);
               //  $mpdf->WriteHTML($stylesheet,1);
                $mpdf->WriteHTML($htmlContent);
                $mpdf->Output();
                
            break;
                
            case 'excel':
                
                
                
                $objPHPExcel = new \PHPExcel();
                // Set properties
                $objPHPExcel->getProperties()->setCreator("Gaucha Cred");
                $objPHPExcel->getProperties()->setLastModifiedBy("Gaucha Cred");
                $objPHPExcel->getProperties()->setTitle("Relatório Cliente Fichário");
                $objPHPExcel->getProperties()->setSubject("Relatório Cliente Fichário");
                $objPHPExcel->getProperties()->setDescription("Relatório gerado pelo sistema Gaucha Cred.");
                
                $sheet = $objPHPExcel->getActiveSheet();
                $sheet->setTitle('Cliente Fichário');
                
                
                // localiza a quantidade máxima de convenios e telefones
                $qtdMaxTelefones = 0;
                $qtdMaxConvenios = 0;
                
                if (is_array($relatorio))
                    foreach($relatorio as $i => $value)
                    {
                        if (count($value['convenios']) > $qtdMaxConvenios  )
                            $qtdMaxConvenios = count($value['convenios']);
                        if (count($value['telefones']) > $qtdMaxTelefones  )
                            $qtdMaxTelefones = count($value['telefones']);
                    }
                
                
                $cabecalho = array();
                array_push($cabecalho, 'CPF', 'NOME');
                
                if ($qtdMaxConvenios == 0)
                    array_push($cabecalho, 'CONVENIO1');
                else
                {
                    for ($i = 1; $i <= $qtdMaxConvenios; $i++)
                        array_push($cabecalho, 'CONVENIO'. $i, 'NB' . $i, 'MATRICULA'. $i, 'SENHA' . $i);
                }
                    
                if ($qtdMaxTelefones == 0)
                    array_push($cabecalho, 'TELEFONE1', 'REFERENCIA1');
                else
                {
                    for ($i = 1; $i <= $qtdMaxTelefones; $i++)
                        array_push($cabecalho, 'TELEFONE'. $i, 'REFERÊNCIA'. $i);
                }
                
                array_push($cabecalho, 'ENDERECO', 'BAIRRO', 'CIDADE', 'ESTADO', 'CEP', 'PAIS','OBSERVAÇÃO');
                
                // SETA O CABEÇALHO
                $sheet->fromArray($cabecalho, NULL, 'A1');
                
                
                // adiciona os valores
                $content = array();
                if (is_array($relatorio))
                    foreach($relatorio as $i => $value)
                    {
                        $v = array();
                        array_push($v, $value['dados']['cpf'], $value['dados']['nomeCliente']);
                        // adiciona convenios
                        for ($a = 0; $a < $qtdMaxConvenios; $a++)
                            if (isset($value['convenios'][$a]))
                                array_push($v, $value['convenios'][$a]['nomeConvenio'], $value['convenios'][$a]['nb'], $value['convenios'][$a]['matricula'], $value['convenios'][$a]['senha']      );
                            else
                                array_push($v, '');
                        // adiciona telefones
                        for ($a = 0; $a < $qtdMaxTelefones; $a++)
                            if (isset($value['telefones'][$a]))
                                array_push($v, $value['telefones'][$a]['numero'], $value['telefones'][$a]['referencia']);
                            else
                                array_push($v, '', '');
                        
                        // seta restante dos dados
                         array_push($v, $value['dados']['rua'] . ', '. $value['dados']['numeroRua'], $value['dados']['bairro'], $value['dados']['cidade'], $value['dados']['uf'], $value['dados']['cep'],
                                   'Brasil', ''
                                   );
                        
                        array_push($content , $v);
                        
                        
                    }
                
                $sheet->fromArray($content, NULL, 'A2');
                
                
                // redirect output to client browser 
                header('Content-Type: application/vnd.openxmlformatsofficedocument.spreadsheetml.sheet'); 
                header('Content-Disposition: attachment;filename="Cliente Fichário.xlsx"'); 
                header('Cache-Control: max-age=0'); 
                 $objWriter = \PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
               
                $objWriter->save('php://output');

            break;
                
            case 'etiquetas':
                
                
                //$htmlHeader =  file_get_contents(\Application::getIndexPath(). '/templates/relatorios/pdf/clientefichario/header.html');
                $htmlContent = file_get_contents(\Application::getIndexPath(). '/templates/relatorios/pdf/clientefichario/etiquetas.html');
                
                
                $totalRegistrosRelatorio = count($relatorio);
                $totalEtiquetasPaginas = 10;
                $totalLinhasEscrevidas = 0;
                $row = 0;
                $col = 0;
                $content = '';
                
                if (is_array($relatorio))
                    foreach($relatorio as $i => $rel)
                    {
                        $col++;
                        if ($col == 1 )
                            $content .= '<div class="row">';
                        // adiciona o conteudo texto
                        $content .= '
                            <div class="col-md-4 etiqueta">
                                <p><b>'. $rel['dados']['nomeCliente'] .'</b></p>
                                <p>'. $rel['dados']['rua'] .', N&ordm; '. $rel['dados']['numeroRua'] . '</p>
                                <p>'.$rel['dados']['bairro'] .'-'. $rel['dados']['cidade'] .'/'. $rel['dados']['uf'] .'</p>
                                <P>CEP: '. $rel['dados']['cep'] .'</P>
                            </div>
                        ';
                        
                        
                        
                        if ($col == 3   || ! isset($relatorio[$i + 1]) )
                            if ($col == 3)
                            {
                                $content .= '</div>';
                                $totalLinhasEscrevidas++;
                                $col = 0;
                            }
                            else
                            {
                                if  ( $col == 2 )
                                {
                                    // significa que já escreveu 2
                                    $content .= '
                                        <div class="col-md-4 etiqueta">
                                            <p><b>&nbsp;</b></p>
                                            <p>&nbsp;</p>
                                            <p>&nbsp;</p>
                                            <P>&nbsp;</P>
                                        </div>
                                    </div>
                                    ';
                                    $totalLinhasEscrevidas++;
                                    $col = 0;
                                }else
                                {
                                        $content .= '
                                            <div class="col-md-4 etiqueta">
                                                <p><b>&nbsp;</b></p>
                                                <p>&nbsp;</p>
                                                <p>&nbsp;</p>
                                                <P>&nbsp;</P>
                                            </div>
                                            <div class="col-md-3 etiqueta">
                                                <p><b>&nbsp;</b></p>
                                                <p>&nbsp;</p>
                                                <p>&nbsp;</p>
                                                <P>&nbsp;</P>
                                            </div>
                                        </div>
                                        ';
                                    $totalLinhasEscrevidas++;
                                    $col = 0;
                                }
                            }
                        
                        if ( $totalLinhasEscrevidas > 0 &&  ($totalLinhasEscrevidas % $totalEtiquetasPaginas == 0))
                        {
                            $content .= '<pagebreak>';
                            $totalLinhasEscrevidas = 0;
                        }
                        
                        
                    }
               // var_dump($content); exit;
                
                
                while ($totalLinhasEscrevidas % $totalEtiquetasPaginas != 0 )
                {
                    
                    $content .= '
                        <div class="row">
                            <div class="col-md-3 etiqueta">
                                <p><b>&nbsp;</b></p>
                                <p>&nbsp;</p>
                                <p>&nbsp;</p>
                                <P>&nbsp;</P>
                            </div>
                            <div class="col-md-3 etiqueta">
                                <p><b>&nbsp;</b></p>
                                <p>&nbsp;</p>
                                <p>&nbsp;</p>
                                <P>&nbsp;</P>
                            </div>
                            <div class="col-md-3 etiqueta">
                                <p><b>&nbsp;</b></p>
                                <p>&nbsp;</p>
                                <p>&nbsp;</p>
                                <P>&nbsp;</P>
                            </div>
                        </div>
                    ';
                    $totalLinhasEscrevidas++;
                }
                
                 $htmlContent = str_replace(array(
                 '{content}'
                    ), array(
                         $content
                    ), $htmlContent
                 );
                
           // var_dump($htmlContent);
                
                  $mpdf = new Mpdf(array('format' => 'A4', 'margin_top' => 4, 'margin_left' => 5, 'margin_right' => 5, 'margin_bottom' => 3));
                 $mpdf->SetTitle('Cliente Fichário');
                 //$mpdf->SetHTMLHeader($htmlHeader);
                //  $mpdf->WriteHTML($stylesheet,1);
                 $mpdf->WriteHTML($htmlContent);
                 $mpdf->Output();
            break;
                
                
        }
        
        
    }
    
    
    
    
     /**
    * AGENDAMENTOS
    * 
    * */
     
    
      public function agendamentos()
    {
        if (! \Application::isAuthorized(ucfirst(strtolower(\Application::getNameController())) , 'relatorio_agendamento', 'ler')  
                    && ! \Application::isAuthorized(ucfirst(strtolower(\Application::getNameController())) , 'relatorio_agendamento', 'escrever')
           )
            \Application::print404();
        
        $this->setView('relatorios/agendamentos/index');
        
      //  $subtabela = new Subtabela();
       // $result = $subtabela->listarSubtabelas(null, null, null, 10000);
      //  if ($result !== false)
        //    $this->setParams('tabelas', $result);
          
          $grupo = new GrupoUsuario();
          $result = $grupo->listarGrupos();
          if ($result !== false)
              $this->setParams('grupos', $result);
          
          $entidade = new Entidade();
          $result = $entidade->listarEntidades();
          if ($result !== false)
              $this->setParams('convenios', $result);
          
          $operacao = new Operacao();
          $result = $operacao->listarOperacoes();
          if ($result !== false)
              $this->setParams('operacoes', $result);
          
          $usuario = new Usuario();
          $result = $usuario->listarUsuarios();
          if ($result !== false)
              $this->setParams('usuarios', $result);
          
        
        $cliente = new Cliente();
        $result = $cliente->carregar('%', 100000, 2, 'asc', true);
        if ($result !== false)
            $this->setParams('clientes', $result);
        
        $this->showContents();
    }
    
    
    public function pesquisarAgendamentos()
    {
        if (! \Application::isAuthorized(ucfirst(strtolower(\Application::getNameController())) , 'relatorio_agendamento', 'ler')  
                    && ! \Application::isAuthorized(ucfirst(strtolower(\Application::getNameController())) , 'relatorio_agendamento', 'escrever')
           )
            \Application::print404();
        
        
        $grupo = (empty($_REQUEST['grupo'])) ? null : $_REQUEST['grupo'];
        $convenio = (empty($_REQUEST['convenio'])) ? null : $_REQUEST['convenio'];
        $usuario = (empty($_REQUEST['vendedor'])) ? null : $_REQUEST['vendedor'];
        $dataInicio =   (empty($_POST['datainicio'])) ? null :  Utils::formatStringDate($_POST['datainicio'], 'd/m/Y', 'Y-m-d'); 
        $dataFim = (empty($_POST['datafim'])) ? null : Utils::formatStringDate($_POST['datafim'], 'd/m/Y', 'Y-m-d'); 
        $dataAgendamentoInicio =   (empty($_POST['dataagendamentoinicio'])) ? null :  Utils::formatStringDate($_POST['dataagendamentoinicio'], 'd/m/Y', 'Y-m-d'); 
        $dataAgendamentoFim = (empty($_POST['dataagendamentofim'])) ? null : Utils::formatStringDate($_POST['dataagendamentofim'], 'd/m/Y', 'Y-m-d'); 
        $status = (empty($_REQUEST['status'])) ? null : $_REQUEST['status'];
        
        $relatorio = new Relatorio();
        $result = $relatorio->listarAgendamentos($grupo, $convenio, $usuario, $dataInicio, $dataFim, $dataAgendamentoInicio, $dataAgendamentoFim, $status);
        if ($result == false)
            echo json_encode(array());
        else
            echo json_encode($result);
        
    }
    
    
    /**
    * RELATÓRIO DE VENDAS
    * 
    * */
     
    
      public function vendas()
    {
        if (! \Application::isAuthorized(ucfirst(strtolower(\Application::getNameController())) , 'relatorio_vendas', 'ler')  
                    && ! \Application::isAuthorized(ucfirst(strtolower(\Application::getNameController())) , 'relatorio_vendas', 'escrever')
           )
            \Application::print404();
        
        $this->setView('relatorios/vendas/index');
        
      //  $subtabela = new Subtabela();
       // $result = $subtabela->listarSubtabelas(null, null, null, 10000);
      //  if ($result !== false)
        //    $this->setParams('tabelas', $result);
        
        $grupo = new GrupoUsuario();
        $result = $grupo->listarGrupos();
        if ($result !== false)
            $this->setParams('grupos', $result);
			
		
        
        $this->showContents();
    }
    
    
    public function gerarVendas()
    {
        if (! \Application::isAuthorized(ucfirst(strtolower(\Application::getNameController())) , 'relatorio_vendas', 'ler')  
                    && ! \Application::isAuthorized(ucfirst(strtolower(\Application::getNameController())) , 'relatorio_vendas', 'escrever')
           )
            \Application::print404();
        
        
        $nomeGrupo = (empty($_POST['grupo'])) ? null : $_POST['grupo'];
        $dataInicial =   (empty($_POST['datainicial'])) ? null :  Utils::formatStringDate($_POST['datainicial'], 'd/m/Y', 'Y-m-d'); 
        $dataFinal = (empty($_POST['datafinal'])) ? null : Utils::formatStringDate($_POST['datafinal'], 'd/m/Y', 'Y-m-d'); 
        
        
        $r = new Relatorio();
        $vendasUsuarios = $r->relatorioVendas($nomeGrupo,  $dataInicial, $dataFinal, 'usuarios');
        $vendasGrupos = $r->relatorioVendas($nomeGrupo,  $dataInicial, $dataFinal, 'grupos');
        
        
       
        
         $objPHPExcel = new \PHPExcel();
        // Set properties
        $objPHPExcel->getProperties()->setCreator("Gaucha Cred");
        $objPHPExcel->getProperties()->setLastModifiedBy("Gaucha Cred");
        $objPHPExcel->getProperties()->setTitle("Relatório de Vendas");
        $objPHPExcel->getProperties()->setSubject("Relatório de Vendas");
        $objPHPExcel->getProperties()->setDescription("Relatório gerado pelo sistema Gaucha Cred.");

        $sheet = $objPHPExcel->getActiveSheet();
        $sheet->setTitle('Relatório de Vendas');
        
		$columnIndex = 2;
		$rowIndex = 3;
        // Cria o indice que as operações terão na planilha
        $operacoesHead = array();
        $rowNumber = $rowIndex;
        foreach($vendasUsuarios as $i => $value)
        {
            $key = array_search(strtoupper($value['operacao']), array_column($operacoesHead, 'operacao'));
            if ($key === false)
                array_push($operacoesHead, array('operacao' => strtoupper($value['operacao']), 'rowNumber' => $rowNumber++ )   );
           // else
              //  $operacoesHead[$key]['rowNumber'] = $rowNumber++; 
        }
        
        // Cria o indice de colunas que nomes dos usuários terão na planilha
        $usuariosHead = array();
        $columnNumber = $columnIndex + 1;
        foreach($vendasUsuarios as $i => $value)
        {
            // Para nomes não ficarem muito grandes nas celulas reduz eles para o formato Nome UltimoNome
            $aux = explode(' ', $value['nome']);
            if (count($aux) < 2 )
                $nome = (isset($aux[0])) ? $aux[0] : ''  ;
            else
                $nome = $aux[0] . ' ' . array_pop($aux);
            
            $key = array_search(strtoupper($nome), array_column($usuariosHead, 'nome'));
            if ($key === false)
                array_push($usuariosHead, array('nome' => strtoupper($nome), 'columnNumber' => $columnNumber++ )   );
            
        }
       
        // Adiciona valores ao EXCEL. 
		
		// Adiciona titulo das operações
		$sheet->setCellValueByColumnAndRow($columnIndex, $rowIndex, 'Operação' );
		
		// Adiciona OPERAÇÕES
        $rowNumber = $rowIndex + 1;
        foreach($operacoesHead as $i => $value)
            $sheet->setCellValueByColumnAndRow(2, $rowNumber++, $value['operacao'] );
        
        // Adiciona valores ao EXCEL. 
		// Adiciona NOMES
        $columnNumber = $columnIndex + 1;
        foreach($usuariosHead as $i => $value)
            $sheet->setCellValueByColumnAndRow($columnNumber++, 3, $value['nome'] );
        
        // Adiciona os valores ao EXCEL
        foreach($vendasUsuarios as $i => $value)
        {
            $aux = explode(' ', $value['nome']);
            if (count($aux) < 2 )
                $nome = (isset($aux[0])) ? $aux[0] : ''  ;
            else
                $nome = $aux[0] . ' ' . array_pop($aux);
            
            $keyColumn = array_search(strtoupper($nome), array_column($usuariosHead, 'nome'));
            $keyRow = array_search(strtoupper($value['operacao']), array_column($operacoesHead, 'operacao'));
           
           
            
            if ($keyColumn === false || $keyRow === false)
                continue;
            else
            {
                $rowNumber = $operacoesHead[$keyRow]['rowNumber']  + 1;
                $columnNumber = $usuariosHead[$keyColumn]['columnNumber'];
                $sheet->setCellValueByColumnAndRow($columnNumber, $rowNumber, $value['valor']);
                $sheet->getCellByColumnAndRow($columnNumber, $rowNumber)->getStyle(\PHPExcel_Cell::stringFromColumnIndex($columnNumber) . $rowNumber)->getNumberFormat()->setFormatCode('_ "R$ "* #,##.00_ ;_ "R$ "* -#.##0,00_ ;_ "R$ "* "-"??_ ;_ @_ ');
            }
        }
        
        
        // Escreve O nome VALOR TOTAL PARA AS COLUNAS
        $rowNumberTotal1 = $rowIndex + count($operacoesHead) + 2;
        $sheet->setCellValueByColumnAndRow($columnIndex, $rowNumberTotal1, 'TOTAL');
        
        // Adiciona os valores para TOTAL 1
        for ($colIndex = $columnIndex + 1; $colIndex <= (count($usuariosHead) + $columnIndex); $colIndex++ )
        {
            $formula = "=SUM(". \PHPExcel_Cell::stringFromColumnIndex($colIndex). 4 . ":". \PHPExcel_Cell::stringFromColumnIndex($colIndex). ($rowNumberTotal1 - 1) . ")";
            $sheet->getCellByColumnAndRow($colIndex, $rowNumberTotal1)->setValue($formula);
            $sheet->getStyleByColumnAndRow($colIndex, $rowNumberTotal1)->getNumberFormat()->setFormatCode('R$ #,##.##');
        }
		
        // Adicona Segundo Total
		$rowNumberTotal2 = $rowNumberTotal1 + 2;
		$sheet->setCellValueByColumnAndRow($columnIndex, $rowNumberTotal2, 'TOTAL');
        
        
        // mescla colunas ou linhas
		// mescla o titulo do TOTAL GERAL
        $highestColumn = $columnIndex + count($usuariosHead) + 1;
        $columnLetter = \PHPExcel_Cell::stringFromColumnIndex($highestColumn );
        $sheet->mergeCells($columnLetter . 3 .':'. $columnLetter . $rowNumberTotal1);
        $sheet->setCellValue($columnLetter . 3 , 'TOTAL' );
		// mescla o valor do TOTAL GERAL
		$formula = "=SUM(". \PHPExcel_Cell::stringFromColumnIndex($columnIndex+1). $rowNumberTotal1 . ":". \PHPExcel_Cell::stringFromColumnIndex($highestColumn - 1). ($rowNumberTotal1) . ")";
		
        //$columnLetter = \PHPExcel_Cell::stringFromColumnIndex($highestColumn );
        $sheet->mergeCells($columnLetter . ($rowNumberTotal1 + 1) .':'. $columnLetter . $rowNumberTotal2);
		$sheet->getCellByColumnAndRow($highestColumn, ($rowNumberTotal1 + 1) )->setValue($formula);
		// Mescla a celula A1
		$sheet->mergeCells('A'. ($rowIndex-2) . ':B' . $rowNumberTotal2);
		$sheet->setCellValue('A'. ($rowIndex-2) , 'Contratos vinculados não são colocados nesse calculo' );
        // Escreve a data do relatório
		$data = '';
		$nomeMes = '';
		$ano = '';
		$aux = explode('-', $dataInicial);
		if (isset($aux[2]))
		{
			$data .= $aux[2] . '/' . $aux[1];
			$ano = $aux[0];
			// CASE PEGA O NOME DO MES PARA UTILIZAR MAIS ABAIXO
			switch($aux[1])
			{
				case 1: $nomeMes = 'JANEIRO'; break;
				case 2: $nomeMes = 'FEVEREIRO'; break;
				case 3: $nomeMes = 'MARÇO'; break;
				case 4: $nomeMes = 'ABRIL'; break;
				case 5: $nomeMes = 'MAIO'; break;
				case 6: $nomeMes = 'JUNHO'; break;
				case 7: $nomeMes = 'JULHO'; break;
				case 8: $nomeMes = 'AGOSTO'; break;
				case 9: $nomeMes = 'SETEMBRO'; break;
				case 10: $nomeMes = 'OUTUBRO'; break;
				case 11: $nomeMes = 'NOVEMBRO'; break;
				case 12: $nomeMes = 'DEZEMBRO'; break;
			}
		}
		$aux = explode('-', $dataFinal);
		if (isset($aux[2])) $data .= ' à ' . $aux[2] . '/' . $aux[1]; 
		$sheet->setCellValue(\PHPExcel_Cell::stringFromColumnIndex($columnIndex ). ($rowIndex-1) , $data );
		
		// Mescla a celulas do titulo geral
		$sheet->mergeCells(\PHPExcel_Cell::stringFromColumnIndex($columnIndex ). ($rowIndex-2) . ':' . \PHPExcel_Cell::stringFromColumnIndex($highestColumn) . ($rowIndex-2));
		$sheet->setCellValue(\PHPExcel_Cell::stringFromColumnIndex($columnIndex ). ($rowIndex-2) , ' VENDAS  '. $nomeMes . '_' . $ano . ' USUARIOS' );
		
		//*
		// *** Adiciona NEGRITOS
		// negrito do titulo
		$sheet->getStyle(\PHPExcel_Cell::stringFromColumnIndex($columnIndex ). ($rowIndex-2))->getFont()->setBold(true);
		// negrito das operacoes
		$sheet->getStyle(\PHPExcel_Cell::stringFromColumnIndex($columnIndex ). ($rowIndex+1) . ':' . \PHPExcel_Cell::stringFromColumnIndex($columnIndex ). $rowNumberTotal2 )->getFont()->setBold(true);
        // Negrito dos Nomes
        $sheet->getStyle(\PHPExcel_Cell::stringFromColumnIndex( ($columnIndex + 1) ). ($rowIndex) . ':' . \PHPExcel_Cell::stringFromColumnIndex($highestColumn ). $rowIndex)->getFont()->setBold(true);
        // Negrito da linha do Total
        $sheet->getStyle(\PHPExcel_Cell::stringFromColumnIndex($columnIndex ). ($rowNumberTotal1) . ':' .     \PHPExcel_Cell::stringFromColumnIndex($highestColumn ). ($rowNumberTotal1) )->getFont()->setBold(true);
        // Negrito do valor do Total Geral
        $sheet->getStyle(\PHPExcel_Cell::stringFromColumnIndex($highestColumn ). ($rowNumberTotal2-1) )->getFont()->setBold(true);
        
        
        
        //*
        // *** Seta a FONTE
        // Fonte todos os campos
        
        $styleArray = array(
            'font'  => array(
                'size'  => 14,
                'name'  => 'Arial'
            ));
        $sheet->getStyle('A'. ($rowIndex -2) . ':' . \PHPExcel_Cell::stringFromColumnIndex($highestColumn ). ($rowNumberTotal2)  )->applyFromArray($styleArray);
        
        
        
        //*
        // *** Faz o alinhamento
        // alinha titulo
        $sheet->getStyle(\PHPExcel_Cell::stringFromColumnIndex($columnIndex ). ($rowIndex-2))->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        // Alinhamento da coluna A
        $sheet->getStyle('A' . ($rowIndex-2))->getAlignment()->applyFromArray(
            array('horizontal' => \PHPExcel_Style_Alignment::HORIZONTAL_CENTER, 'vertical' => \PHPExcel_Style_Alignment::VERTICAL_CENTER  )
        );
        
        
        //*
        // Quebra automatica de texto
        // Quebra A1
        $sheet->getStyle('A1')->getAlignment()->setWrapText(true);
        
        
        
        //*
        // *** Seta as cores
        // linha titulo
        $sheet->getStyle(\PHPExcel_Cell::stringFromColumnIndex($columnIndex ). ($rowIndex-2))->applyFromArray(
            array(
                'fill' => array(
                        'type' => \PHPExcel_Style_Fill::FILL_SOLID,
                        'color' => array('rgb'=>'FFFF00'),
                )
            )    
        );
        // cor acima dos nomes
        $sheet->getStyle(\PHPExcel_Cell::stringFromColumnIndex($columnIndex+1 ). ($rowIndex-1) . ':' . \PHPExcel_Cell::stringFromColumnIndex($highestColumn ). ($rowIndex - 1)  )->applyFromArray(
            array(
                'fill' => array(
                        'type' => \PHPExcel_Style_Fill::FILL_SOLID,
                        'color' => array('rgb'=>'00FF00'),
                )
            )    
        );
        // cor dos nomes
        $sheet->getStyle(\PHPExcel_Cell::stringFromColumnIndex($columnIndex+1 ). ($rowIndex) . ':' . \PHPExcel_Cell::stringFromColumnIndex($highestColumn-1 ). ($rowIndex)  )->applyFromArray(
            array(
                'fill' => array(
                        'type' => \PHPExcel_Style_Fill::FILL_SOLID,
                        'color' => array('rgb'=>'E0FFFF'),
                )
            )    
        );
         // cor das operações
        $sheet->getStyle(\PHPExcel_Cell::stringFromColumnIndex($columnIndex ). ($rowIndex+1) . ':' . \PHPExcel_Cell::stringFromColumnIndex($columnIndex ). ($rowNumberTotal2)  )->applyFromArray(
            array(
                'fill' => array(
                        'type' => \PHPExcel_Style_Fill::FILL_SOLID,
                        'color' => array('rgb'=>'B4EEB4'),
                )
            )    
        );
        
        
        //*
        // *** Adiciona Bordas
        
        $sheet->getStyle(\PHPExcel_Cell::stringFromColumnIndex($columnIndex ). ($rowIndex-1) . ':' . \PHPExcel_Cell::stringFromColumnIndex($highestColumn ). ($rowNumberTotal2)  )->applyFromArray(
            array(
                    'borders' => array(
                      'allborders' => array(
                          'style' => \PHPExcel_Style_Border::BORDER_THIN
                      )
                )  
            )
        );
        
        
      
        
       
        
        
        
        
        //**********************************************************************
        // *************** INICIA A PARTE DE GRUPOS
        // *******************************************************************************************************************************************
        
        
        
         
        
        $columnIndex = 2;
		$rowIndex = $rowNumberTotal2 + 4;
        // Cria o indice que as operações terão na planilha
        $operacoesHead = array();
       
        $rowNumber = $rowIndex;
        foreach($vendasGrupos as $i => $value)
        {
            $key = array_search(strtoupper($value['operacao']), array_column($operacoesHead, 'operacao'));
            if ($key === false)
                array_push($operacoesHead, array('operacao' => strtoupper($value['operacao']), 'rowNumber' => $rowNumber++ )   );
           // else
              //  $operacoesHead[$key]['rowNumber'] = $rowNumber++; 
        }
       
        // Cria o indice de colunas que nomes dos usuários terão na planilha
        $usuariosHead = array();
        $columnNumber = $columnIndex + 1;
        
        foreach($vendasGrupos as $i => $value)
        {
            
            
            $key = array_search(strtoupper($value['nome']), array_column($usuariosHead, 'nome'));
            if ($key === false)
                array_push($usuariosHead, array('nome' => strtoupper($value['nome']), 'columnNumber' => $columnNumber++ )   );
            
        }
       
        // Adiciona valores ao EXCEL. 
		
		// Adiciona titulo das operações
		$sheet->setCellValueByColumnAndRow($columnIndex, $rowIndex, 'Operação' );
		
		// Adiciona OPERAÇÕES
        $rowNumber = $rowIndex + 1;
        foreach($operacoesHead as $i => $value)
            $sheet->setCellValueByColumnAndRow(2, $rowNumber++, $value['operacao'] );
        
        // Adiciona valores ao EXCEL. 
		// Adiciona NOMES
        $columnNumber = $columnIndex + 1;
        foreach($usuariosHead as $i => $value)
            $sheet->setCellValueByColumnAndRow($columnNumber++, $rowIndex, $value['nome'] );
        
        // Adiciona os valores ao EXCEL
        foreach($vendasGrupos as $i => $value)
        {
            
            
            $keyColumn = array_search(strtoupper($value['nome']), array_column($usuariosHead, 'nome'));
            $keyRow = array_search(strtoupper($value['operacao']), array_column($operacoesHead, 'operacao'));
           
           
          
            if ($keyColumn === false || $keyRow === false)
                continue;
            else
            {
                $rowNumber = $operacoesHead[$keyRow]['rowNumber']  + 1;
                $columnNumber = $usuariosHead[$keyColumn]['columnNumber'];
                $sheet->setCellValueByColumnAndRow($columnNumber, $rowNumber, $value['valor']);
                $sheet->getCellByColumnAndRow($columnNumber, $rowNumber)->getStyle(\PHPExcel_Cell::stringFromColumnIndex($columnNumber) . $rowNumber)->getNumberFormat()->setFormatCode('_ "R$ "* #,##.00_ ;_ "R$ "* -#.##0,00_ ;_ "R$ "* "-"??_ ;_ @_ ');
            }
        }
        
        
        // Escreve O nome VALOR TOTAL PARA AS COLUNAS
        $rowNumberTotal1 = $rowIndex + count($operacoesHead) + 2;
        $sheet->setCellValueByColumnAndRow($columnIndex, $rowNumberTotal1, 'TOTAL');
        
        // Adiciona os valores para TOTAL 1
        for ($colIndex = $columnIndex + 1; $colIndex <= (count($usuariosHead) + $columnIndex); $colIndex++ )
        {
            $formula = "=SUM(". \PHPExcel_Cell::stringFromColumnIndex($colIndex). ($rowIndex + 1) . ":". \PHPExcel_Cell::stringFromColumnIndex($colIndex). ($rowNumberTotal1 - 1) . ")";
            $sheet->getCellByColumnAndRow($colIndex, $rowNumberTotal1)->setValue($formula);
            $sheet->getStyleByColumnAndRow($colIndex, $rowNumberTotal1)->getNumberFormat()->setFormatCode('R$ #,##.##');
        }
		
        // Adicona Segundo Total
		$rowNumberTotal2 = $rowNumberTotal1 + 2;
		$sheet->setCellValueByColumnAndRow($columnIndex, $rowNumberTotal2, 'TOTAL');
        
        
        // mescla colunas ou linhas
		// mescla o titulo do TOTAL GERAL
        $highestColumn = $columnIndex + count($usuariosHead) + 1;
        $columnLetter = \PHPExcel_Cell::stringFromColumnIndex($highestColumn );
        $sheet->mergeCells($columnLetter . $rowIndex .':'. $columnLetter . $rowNumberTotal1);
       
        $sheet->setCellValue($columnLetter . ($rowIndex) , 'TOTAL' );
		// mescla o valor do TOTAL GERAL
		$formula = "=SUM(". \PHPExcel_Cell::stringFromColumnIndex($columnIndex+1). $rowNumberTotal1 . ":". \PHPExcel_Cell::stringFromColumnIndex($highestColumn - 1). ($rowNumberTotal1) . ")";
		
        
        $sheet->mergeCells($columnLetter . ($rowNumberTotal1 + 1) .':'. $columnLetter . $rowNumberTotal2);
		$sheet->getCellByColumnAndRow($highestColumn, ($rowNumberTotal1 + 1) )->setValue($formula);
		// Mescla a celula A1
		$sheet->mergeCells('A'. ($rowIndex-2) . ':B' . $rowNumberTotal2);
		$sheet->setCellValue('A'. ($rowIndex-2) , 'CONTRATOS VINCULADOS ENTRAM NESSE CALCULO' );
        // Escreve a data do relatório
		$data = '';
		$nomeMes = '';
		$ano = '';
		$aux = explode('-', $dataInicial);
		if (isset($aux[2]))
		{
			$data .= $aux[2] . '/' . $aux[1];
			$ano = $aux[0];
			// CASE PEGA O NOME DO MES PARA UTILIZAR MAIS ABAIXO
			switch($aux[1])
			{
				case 1: $nomeMes = 'JANEIRO'; break;
				case 2: $nomeMes = 'FEVEREIRO'; break;
				case 3: $nomeMes = 'MARÇO'; break;
				case 4: $nomeMes = 'ABRIL'; break;
				case 5: $nomeMes = 'MAIO'; break;
				case 6: $nomeMes = 'JUNHO'; break;
				case 7: $nomeMes = 'JULHO'; break;
				case 8: $nomeMes = 'AGOSTO'; break;
				case 9: $nomeMes = 'SETEMBRO'; break;
				case 10: $nomeMes = 'OUTUBRO'; break;
				case 11: $nomeMes = 'NOVEMBRO'; break;
				case 12: $nomeMes = 'DEZEMBRO'; break;
			}
		}
		$aux = explode('-', $dataFinal);
		if (isset($aux[2])) $data .= ' à ' . $aux[2] . '/' . $aux[1]; 
		$sheet->setCellValue(\PHPExcel_Cell::stringFromColumnIndex($columnIndex ). ($rowIndex-1) , $data );
		
		// Mescla a celulas do titulo geral
		$sheet->mergeCells(\PHPExcel_Cell::stringFromColumnIndex($columnIndex ). ($rowIndex-2) . ':' . \PHPExcel_Cell::stringFromColumnIndex($highestColumn) . ($rowIndex-2));
		$sheet->setCellValue(\PHPExcel_Cell::stringFromColumnIndex($columnIndex ). ($rowIndex-2) , ' VENDAS  '. $nomeMes . '_' . $ano . ' GRUPOS' );
		
		//*
		// *** Adiciona NEGRITOS
		// negrito do titulo
		$sheet->getStyle(\PHPExcel_Cell::stringFromColumnIndex($columnIndex ). ($rowIndex-2))->getFont()->setBold(true);
		// negrito das operacoes
		$sheet->getStyle(\PHPExcel_Cell::stringFromColumnIndex($columnIndex ). ($rowIndex+1) . ':' . \PHPExcel_Cell::stringFromColumnIndex($columnIndex ). $rowNumberTotal2 )->getFont()->setBold(true);
        // Negrito dos Nomes
        $sheet->getStyle(\PHPExcel_Cell::stringFromColumnIndex( ($columnIndex + 1) ). ($rowIndex) . ':' . \PHPExcel_Cell::stringFromColumnIndex($highestColumn ). $rowIndex)->getFont()->setBold(true);
        // Negrito da linha do Total
        $sheet->getStyle(\PHPExcel_Cell::stringFromColumnIndex($columnIndex ). ($rowNumberTotal1) . ':' .     \PHPExcel_Cell::stringFromColumnIndex($highestColumn ). ($rowNumberTotal1) )->getFont()->setBold(true);
        // Negrito do valor do Total Geral
        $sheet->getStyle(\PHPExcel_Cell::stringFromColumnIndex($highestColumn ). ($rowNumberTotal2-1) )->getFont()->setBold(true);
        
        
        
        //*
        // *** Seta a FONTE
        // Fonte todos os campos
        
        $styleArray = array(
            'font'  => array(
                'size'  => 14,
                'name'  => 'Arial'
            ));
        $sheet->getStyle('A'. ($rowIndex -2) . ':' . \PHPExcel_Cell::stringFromColumnIndex($highestColumn ). ($rowNumberTotal2)  )->applyFromArray($styleArray);
        
        
        
        //*
        // *** Faz o alinhamento
        // alinha titulo
        $sheet->getStyle(\PHPExcel_Cell::stringFromColumnIndex($columnIndex ). ($rowIndex-2))->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        // Alinhamento da coluna A
        $sheet->getStyle('A' . ($rowIndex-2))->getAlignment()->applyFromArray(
            array('horizontal' => \PHPExcel_Style_Alignment::HORIZONTAL_CENTER, 'vertical' => \PHPExcel_Style_Alignment::VERTICAL_CENTER  )
        );
        
        
        //*
        // Quebra automatica de texto
        // Quebra A1
       
        $sheet->getStyle('A'. ($rowIndex - 2) )->getAlignment()->setWrapText(true);
        
        
        
        //*
        // *** Seta as cores
        // linha titulo
        $sheet->getStyle(\PHPExcel_Cell::stringFromColumnIndex($columnIndex ). ($rowIndex-2))->applyFromArray(
            array(
                'fill' => array(
                        'type' => \PHPExcel_Style_Fill::FILL_SOLID,
                        'color' => array('rgb'=>'FFFF00'),
                )
            )    
        );
        // cor acima dos nomes
        $sheet->getStyle(\PHPExcel_Cell::stringFromColumnIndex($columnIndex+1 ). ($rowIndex-1) . ':' . \PHPExcel_Cell::stringFromColumnIndex($highestColumn ). ($rowIndex - 1)  )->applyFromArray(
            array(
                'fill' => array(
                        'type' => \PHPExcel_Style_Fill::FILL_SOLID,
                        'color' => array('rgb'=>'FF0000'),
                )
            )    
        );
        // cor dos nomes
        $sheet->getStyle(\PHPExcel_Cell::stringFromColumnIndex($columnIndex+1 ). ($rowIndex) . ':' . \PHPExcel_Cell::stringFromColumnIndex($highestColumn-1 ). ($rowIndex)  )->applyFromArray(
            array(
                'fill' => array(
                        'type' => \PHPExcel_Style_Fill::FILL_SOLID,
                        'color' => array('rgb'=>'E0FFFF'),
                )
            )    
        );
         // cor das operações
        $sheet->getStyle(\PHPExcel_Cell::stringFromColumnIndex($columnIndex ). ($rowIndex+1) . ':' . \PHPExcel_Cell::stringFromColumnIndex($columnIndex ). ($rowNumberTotal2)  )->applyFromArray(
            array(
                'fill' => array(
                        'type' => \PHPExcel_Style_Fill::FILL_SOLID,
                        'color' => array('rgb'=>'B4EEB4'),
                )
            )    
        );
        
        
        //*
        // *** Adiciona Bordas
        
        $sheet->getStyle(\PHPExcel_Cell::stringFromColumnIndex($columnIndex ). ($rowIndex-1) . ':' . \PHPExcel_Cell::stringFromColumnIndex($highestColumn ). ($rowNumberTotal2)  )->applyFromArray(
            array(
                    'borders' => array(
                      'allborders' => array(
                          'style' => \PHPExcel_Style_Border::BORDER_THIN
                      )
                )  
            )
        );
        
        
        //*
        // *** Auto width das celulas
        //
        
        foreach (range('A', $sheet->getHighestDataColumn()) as $col) {
                $sheet->getColumnDimension($col)->setAutoSize(true);
        } 
        
        
        
        
        
        
        
        // redirect output to client browser 
        header('Content-Type: application/vnd.openxmlformatsofficedocument.spreadsheetml.sheet'); 
        header('Content-Disposition: attachment;filename="Relatório de Vendas.xlsx"'); 
        header('Cache-Control: max-age=0'); 
         $objWriter = \PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');

        $objWriter->save('php://output');
        
                
        
    }
    
    
    
    /**
    * DESPESAS A PAGAR
    */
        
    public function despesasPagar()
	{
        
        if (! \Application::isAuthorized(ucfirst(strtolower(\Application::getNameController())) , 'relatorio_despesas_pagar', 'ler')  
                    && ! \Application::isAuthorized(ucfirst(strtolower(\Application::getNameController())) , 'relatorio_despesas_pagar', 'escrever')
           )
            \Application::print404();
        
        $this->setView('relatorios/despesaspagar/index');
      
        
        
        $banco = new Banco();
          $result = $banco->listarBancos();
          if ($result !== false)
              $this->setParams('bancos', $result);
          
          $operacao = new Operacao();
          $result = $operacao->listarOperacoes();
          if ($result !== false)
              $this->setParams('operacoes', $result);
            
     
        $this->showContents();
	}
    
    
    public function gerarDespesasPagar()
    {
         if (! \Application::isAuthorized(ucfirst(strtolower(\Application::getNameController())) , 'relatorio_despesas_pagar', 'ler')  
                    && ! \Application::isAuthorized(ucfirst(strtolower(\Application::getNameController())) , 'relatorio_despesas_pagar', 'escrever')
           )
            \Application::print404();
        
        
        $nomeBanco = (empty($_POST['banco'])) ? null : $_POST['banco'];
        $nomeOperacao = (empty($_POST['operacao'])) ? null : $_POST['operacao'];
        $dataInicial =   (empty($_POST['datainicial'])) ? null :  Utils::formatStringDate($_POST['datainicial'], 'd/m/Y', 'Y-m-d'); 
        $dataFinal = (empty($_POST['datafinal'])) ? null : Utils::formatStringDate($_POST['datafinal'], 'd/m/Y', 'Y-m-d'); 
        
        $params = array(
            'datavencimentoinicio' => $dataInicial,
            'datavencimentofim' => $dataFinal
        );
        
        $despesasPagar = new DespesasPagar();
        $despesas = $despesasPagar->listarDespesas($params);
        
        
        $params = array(
            'datainicio' => $dataInicial,
            'datafim' => $dataFinal,
            'nomebanco' => $nomeBanco,
            'nomeoperacao' => $nomeOperacao
        );
        $rel = new relatorio();
        $descontos = $rel->listarDescontosSumarizados($params);
        
        $totais = $rel->totaisRelatorioGastos($params);
        
        // obtem HTML das despesas
        $vDespesas = 0;
        $textoDespesas = '';
        if (is_array($despesas))
            foreach($despesas as $i => $despesa)
            {
                $textoDespesas .=  '<div class="itens-float-left border-all">'. $despesa['descricao'] .'</div><div class="itens-float-left border-all">R$ '. Utils::numberToMoney( $despesa['valorDevido']) .'</div>';
                $vDespesas += $despesa['valorDevido'];
            }
        
        // obtem HTML dos descontos
        $vComissoes = 0;
        $textoDescontos = '';
        if (is_array($descontos))
            foreach($descontos as $i => $desconto)
            {
                $textoDescontos .=  '<div class="itens-float-left border-all">'. $desconto['nome'] .'</div><div class="itens-float-left border-all">R$ '. Utils::numberToMoney( $desconto['valorDescontado']) .'</div>';
                $vComissoes +=  $desconto['valorDescontado'];
            }
        
        
        $vDespesas = Utils::numberToMoney($vDespesas);
        $vComissoes = Utils::numberToMoney($vComissoes);
        
        $htmlHeader =  file_get_contents(\Application::getIndexPath(). '/templates/relatorios/pdf/despesaspagar/header.html');
        $htmlContent = file_get_contents(\Application::getIndexPath(). '/templates/relatorios/pdf/despesaspagar/content.html');
             
        
        $dataInicio = Utils::formatStringDate($dataInicial, 'Y-m-d', 'd/m/Y');
        $dataFim =  Utils::formatStringDate($dataFinal, 'Y-m-d', 'd/m/Y');
        
    
       
        // $valorComissao = Utils::numberToMoney($valorComissao);
        
         $htmlContent = str_replace(array(
             '{descontos}', '{dataInicial}', '{dataFinal}', '{despesas}', '{valorImposto}', '{totalComissao}', '{vBruto}', '{vImposto}', '{vDespesas}', '{vComissoes}'
            ), array(
                 $textoDescontos, $dataInicio, $dataFim, $textoDespesas, Utils::numberToMoney( $totais['impostos']), Utils::numberToMoney( $totais['comissaoBruta']), Utils::numberToMoney( $totais['comissaoBruta']), Utils::numberToMoney( $totais['impostos']), $vDespesas, $vComissoes
            ), $htmlContent
         );




         $mpdf = new Mpdf(array('format' => 'A3-L', 'margin_top' => 75, 'margin_left' => 5, 'margin_right' => 5));
         $mpdf->SetTitle('Comissões do Grupo');
        
         // obtem o CSS
       // $stylesheet = file_get_contents(\Application::getIndexPath() . '/library/jsvendor/bootstrap/css/bootstrap.css');
        
        $mpdf->SetHTMLHeader($htmlHeader);
        
        
        
       // $mpdf->WriteHTML($stylesheet,1);
         $mpdf->WriteHTML($htmlContent);
         $mpdf->Output();
        
        
    }
    
    
    
    
}