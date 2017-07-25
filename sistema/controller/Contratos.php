<?php

namespace Gauchacred\controller;



//use \controller\Controller as Controller;
//use \library\php\Blowfish as Blowfish;
use Gauchacred\library\php\Utils as Utils;
use Gauchacred\model\Tabela as Tabela;
use Gauchacred\model\Cliente as Cliente;
use Gauchacred\model\Subtabela as Subtabela;
use Gauchacred\model\GrupoUsuario as GrupoUsuario;
use Gauchacred\model\Contrato as Contrato;
use Gauchacred\model\Entidade as Entidade;
use Gauchacred\model\Entidade as Convenio;
use Gauchacred\model\OperacaoSubtabela as OperacaoSubtabela;
use Gauchacred\model\Usuario as Usuario;
use Gauchacred\model\TipoConvenio as TipoConvenio;
use Gauchacred\model\Banco as Banco;
use Gauchacred\model\Operacao as Operacao;
use Gauchacred\model\SubstatusContrato;


/**
 * @author moises
 * @version 1.0
 * @created 13-set-2015 23:08:59
 */
class Contratos extends Controller
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
       // $this->setView('usuarios/index');

        //$this->showContents();
	}
    
    
    public function listarContratos()
    {
        if (! \Application::isAuthorized('Contratos' , 'contrato', 'ler') && ! \Application::isAuthorized('Contratos' , 'contrato', 'escrever'))
            \Application::print404();
        
        $this->setView('contratos/index');
        
        if (\Application::isAuthorized('Contratos' , 'contrato_todos', 'ler'))
            $qualquerUsuario = true;
        else
            $qualquerUsuario = false;
        
        $contrato = new Contrato();
        $result = $contrato->listarContratos(null, null, null, null, null,  null, null, null, null, null,10, null, null, null, null,null, 1, 'desc', null, null, null, $qualquerUsuario);
        if ($result !== false)
            $this->setParams('contratos', $result);
        
        
        $operacao = new OperacaoSubtabela();
        $result = $operacao->listarOperacoes();
        if ($result != false)
            $this->setParams('operacoes', $result);
        
        $convenio = new Convenio();
        $result = $convenio->listarEntidades();
        if ($result != false)
            $this->setParams('convenios', $result);
        
        $usuario = new Usuario();
        $result = $usuario->listarUsuarios();
        if ($result !== false)
            $this->setParams('usuarios', $result);
        
         $substatus = new SubstatusContrato();
        $recordSet = $substatus->listarSubstatus();
        if($recordSet !== false)
            $this->setParams('substatus', $recordSet);

        $this->showContents();
    }
    
    public function pesquisarContratos()
    {
        if (\Application::isAuthorized('Contratos' , 'contrato_todos', 'ler'))
            $qualquerUsuario = true;
        else
            $qualquerUsuario = false;
        
        $numeroContrato = ($_REQUEST['numero'] == '') ? null : $_REQUEST['numero'];
        $nomeCliente = ($_REQUEST['nome']== '') ? null : $_REQUEST['nome'];
        $cpf = ($_REQUEST['cpf']== '') ? null : $_REQUEST['cpf'];
        $operacao = ($_REQUEST['operacao']== '') ? null : $_REQUEST['operacao'];
        $status = ($_REQUEST['status']== '') ? null : $_REQUEST['status'];
        $convenio = ($_REQUEST['convenio']== '') ? null : $_REQUEST['convenio'];
        $dataInicio = ($_REQUEST['datainicio']== '') ? null : Utils::formatStringDate($_REQUEST['datainicio'], $oldFormat = 'd/m/Y', $newFormat = 'Y-m-d');
         $dataFim = ($_REQUEST['datafim']== '') ? null : Utils::formatStringDate($_REQUEST['datafim'], $oldFormat = 'd/m/Y', $newFormat = 'Y-m-d');
        $dataVendedorInicio = ($_REQUEST['datavendedorinicio']== '') ? null : Utils::formatStringDate($_REQUEST['datavendedorinicio'], $oldFormat = 'd/m/Y', $newFormat = 'Y-m-d');
         $dataVendedorFim = ($_REQUEST['datavendedorfim']== '') ? null : Utils::formatStringDate($_REQUEST['datavendedorfim'], $oldFormat = 'd/m/Y', $newFormat = 'Y-m-d');
        $statusPagamento = (! isset($_REQUEST['statuspagamento'])) ? null : $_REQUEST['statuspagamento'];
        
        $dataComissaoBancoInicio = ($_REQUEST['datacomissaobancoinicio']== '') ? null : Utils::formatStringDate($_REQUEST['datacomissaobancoinicio'], $oldFormat = 'd/m/Y', $newFormat = 'Y-m-d');
        $dataComissaoBancoFim = ($_REQUEST['datacomissaobancofim']== '') ? null : Utils::formatStringDate($_REQUEST['datacomissaobancofim'], $oldFormat = 'd/m/Y', $newFormat = 'Y-m-d');
        $statusComissaoBanco = (! isset($_REQUEST['statuscomissaobanco'])) ? null : $_REQUEST['statuscomissaobanco'];
        
        
        $limit = (! empty($_REQUEST['limit'])) ? $_REQUEST['limit'] : null;
        $idUsuario = (! empty($_REQUEST['usuario'])) ? $_REQUEST['usuario'] : null;
        
        $contrato = new Contrato();
        $result = $contrato->listarContratos(null, $cpf, $numeroContrato, $nomeCliente, $idUsuario,  $operacao, $status, $convenio, null, null,   $limit, $dataInicio, $dataFim, $dataVendedorInicio, $dataVendedorFim, $statusPagamento, null, null, $dataComissaoBancoInicio, $dataComissaoBancoFim, $statusComissaoBanco, $qualquerUsuario);
        
        if ($result === false)
            $json = array();
        else
            $json = $result;
        
        echo json_encode($json);
        
    }
    
    
    
    public function cadastrar()
    {
        
        if (\Application::isAuthorized('Contratos' , 'contrato_todos', 'ler'))
            $qualquerUsuario = true;
        else
            $qualquerUsuario = false;
        
        
         $this->setView('contratos/cadastrar');
        
        if (\Application::getUrlParams(0) !== null)
             $idContrato = \Application::getUrlParams(0);
        else
            $idContrato = null;
        
        // PESQUISA CONTRATO
        $contrato =  new Contrato();
        
        $cpfCliente = null;
        $idSubtabela = null;
        if ($idContrato !== null)
        {
            $result = $contrato->listarContratos($idContrato,  null,  null,  null,  null,   null, null, null, null, null, 10000, null, null, null, null, null,1, 'asc', null, null, null, $qualquerUsuario);
            if ($result != false)
            {
                $this->setParams('contrato', $result);
                $cpfCliente = (isset($result[0]['cpf'])) ? $result[0]['cpf'] : null;
                $idSubtabela = (isset($result[0]['idSubtabela'])) ? $result[0]['idSubtabela'] : null;
            }
        }
        
        
        if ($cpfCliente != null)
        {
            $result = $contrato->listarContratos(null, $cpfCliente, null,  null,  null,   null, null, null, null, null, 10000, null, null, null, null, null,1, 'asc', null, null, null, true);
            if ($result != false)
                $this->setParams('todoscontratos',$result);
            
            $cliente = new Cliente();
            $result = $cliente->carregar($cpfCliente, 10000, 1, 'desc', true);
            if (is_array($result) && count($result) > 0)
                $this->setParams('cliente', $result);
            
            $subtabela = new Subtabela();
            $result = $subtabela->listarSubtabelas($idSubtabela);
            if (is_array($result) && count($result ) > 0)
                $this->setParams('subtabela', $result);
            
            
        }
        
        $tipoConvenio = new TipoConvenio();
        $result = $tipoConvenio->listarTipos();
        if (is_array($result))
            $this->setParams('tipoconvenios', $result);
        
        
         $substatus = new SubstatusContrato();
        $recordSet = $substatus->listarSubstatus();
        if($recordSet !== false)
            $this->setParams('substatus', $recordSet);
        
       $usuario = new Usuario();
        $result = $usuario->listarUsuarios();
        if ($result !== false)
            $this->setParams('usuarios', $result);
        
        
        
        
        
        
       

        $this->showContents();
    }
    
    public function obterTabelas()
    {
        
        
        $tabela = new Tabela();
        $result = $tabela->subtabelaCompleta();
        if ($result == false)
            $return = array();
        else
            $return = $result;
        
        echo json_encode($return);
    }
    
    
    public function buscarCliente()
    {
        $cpf = $_REQUEST['cpf'];
        
        if (! \Application::isAuthorized(ucfirst('clientes') , 'clientes_todos', 'ler')  )
            $qualquerCliente = false;
        else
            $qualquerCliente = true;
        
        
        if (\Application::isAuthorized('Contratos' , 'contrato_todos', 'ler'))
            $qualquerUsuario = true;
        else
            $qualquerUsuario = false;
        
         $cliente = new Cliente();
         $result = $cliente->carregar($cpf, 1, 1, 'desc', $qualquerCliente);
        
        $contrato = new Contrato();
        $co = $contrato->listarContratos(null, $cpf, null, null, null,   null,  null,  null,  null,  null, 100000, null, null, null, null,  null, 1, 'asc', null, null, null, $qualquerUsuario);
       
        $return['cliente'] = $result;
        $return['contratos'] = $co;
        
        if ($result === false && $co === false)
            $return = array();
       
        
        echo json_encode($return);
        
    }
    
    
    public function salvarContrato()
    {
        
        if (! \Application::isAuthorized('Contratos' , 'contrato', 'escrever'))
        {
             $json['success'] = false;
             $json['message'] = 'Usuário sem autorização';
             echo json_encode($json);
            exit;
        }
		
		if (! \Application::isAuthorized(ucfirst('clientes') , 'clientes_todos', 'ler')  )
            $qualquerCliente = false;
        else
            $qualquerCliente = true;
        
        $dados = json_decode($_REQUEST['dados'], true);
        
        
        // recupera subtabela
        
        $st = new Subtabela();
        $result = $st->listarSubtabelas($dados['idsubtabela']);
        if (! isset($result[0]['id']))
        {
            echo json_encode(array('success' => false, 'message' => 'Não foi possível recuperar a subtabela especificada'));
            exit;
        }else
            $subtabela = $result[0];
        
        
        
        // recupera o cliente
        $cli = new Cliente();
        $result = $cli->carregar($dados['cpf'], 1, 1, 'desc', $qualquerCliente);
        if (! is_array($result) || count($result) < 1)
        {
            echo json_encode(array('success' => false, 'message' => 'Cliente não encontrado'));
            exit;
        }else
            $cliente = $result[0];
        
        // recupera tabela
        
        $tb = new Tabela();
        $result = $tb->listarTabelas($subtabela['idTabela']);
        
        if (isset($result[0]))
            $tabela = $result[0];
        else
            $tabela = null;
        
        
        
        
        // verifica se é UPDATE ou INSERT
        if (isset($dados['idcontrato']))
        {
            $contrato = new Contrato();
            $contratoCarregado = $contrato->listarContratos($dados['idcontrato'], null, null, null, null,   null,  null,  null,  null,  null, 100000, null, null, null, null,  null, 1, 'asc', null, null, null, true);
            $contratoCarregado = (isset($contratoCarregado[0])) ? $contratoCarregado[0] : null;
            
        }else
            $contratoCarregado = null;
        
        $userId = (isset($contratoCarregado['idUsuario'])) ? $contratoCarregado['idUsuario'] : $_SESSION['userid'];
        
        
        // localiza em qual grupo da subtabela o usuário logado ou usuário que criou o contrato pertence
        $gr = new GrupoUsuario();
        $isGroupUser = false;
        if (is_array($subtabela['comissoes']))
            foreach($subtabela['comissoes'] as $i => $value)
            {
                $usersGroup = array();
                $isGroupUser = false;
                $result = $gr->listarAtribuicoes($value['idGrupo']);
                if (is_array($result) && count($result) > 0)
                    foreach($result as $b => $us)
                    {
                        array_push($usersGroup, $us);
                       
                        if ($userId == $us['idUsuario'])
                            $isGroupUser = true;
                            
                      
                    }
                
                if ($isGroupUser)
                    break;
            }
        
        if ($isGroupUser == false)
        {
            echo json_encode(array('success' => false, 'message' => 'Você não pertence a nenhum grupo desta tabela'));
            exit;
        }
        
        
        // ADICIONADO PARA USUARIOS VINCULADOS
        $userIdVinculed = (! empty($dados['idusuariovinculado'])) ? $dados['idusuariovinculado'] : null;
        $isGroupUserVinculed = false;
        if (is_array($subtabela['comissoes']) && $userIdVinculed !== null )
            foreach($subtabela['comissoes'] as $i => $value)
            {
                $usersGroupVinculed = array();
                $isGroupUserVinculed = false;
                $result = $gr->listarAtribuicoes($value['idGrupo']);
                if (is_array($result) && count($result) > 0)
                    foreach($result as $b => $us)
                    {
                        
                       
                        if ($userIdVinculed == $us['idUsuario'])
                        {
                            $isGroupUserVinculed = true;
                            array_push($usersGroupVinculed, $us);
                        }
                            
                      
                    }
                
                if ($isGroupUserVinculed)
                    break;
            }
        
        if ($userIdVinculed == $userId)
        {
            echo json_encode(array('success' => false, 'message' => 'O usuário do contrato e o usuário vinculado não podem ser a mesma pessoa'));
            exit;
        }
        
        
        if ($userIdVinculed !== null && $isGroupUserVinculed == false )
        {
            echo json_encode(array('success' => false, 'message' => 'O usuário vinculado não pertence a nenhum grupo desta tabela'));
            exit;
        }
        
        // FIM ADICIONADO PARA USUARIO VINCULADO
        
      //  var_dump($usersGroup); exit;
        // Localiza usuários que pertecem aos grupos que também receberão comissão pelo fechamento do contrato (exemplo grupo suporte)
        
        $usersOthers = array();
        if (is_array($subtabela['comissoes']))
            foreach($subtabela['comissoes'] as $i => $value)
            {
                
                if (in_array($usersGroup[0]['id'], $value['recebeComissaoDe']  ))
                {
                    $result = $gr->listarAtribuicoes($value['idGrupo']);
                    if (is_array($result) && count($result) > 0)
                        foreach($result as $b => $us)
                            array_push($usersOthers, $us);
                            
                        
                }
            }
        
         // Verifica a comissão de grupo de cada usuário APTO e adiciona o percentual de comissão no box final para o model
        $finalUsers = array();
       // var_dump($usersGroup); exit;
        foreach($usersGroup as $i => $value)
        {
            if ($value['indicaSupervisor'] == true || $value['idUsuario'] == $userId )
            {
                
                if($value['idUsuario'] == $userId)
                {
                    
                    $key = array_search($value['id'], array_column($subtabela['comissoes'], 'idGrupo'));
                    // Se for o vendedor do contrato, adiciona a comissão do grupo
                    if ($key !== false && isset($subtabela['comissoes'][$key]['comissao']))
                        $usersGroup[$i]['comissaoGrupo'] = $subtabela['comissoes'][$key]['comissao'];
                    else
                        $usersGroup[$i]['comissaoGrupo'] = 0.0;
                    
                    // PARA USUARIO VINCULADO A COMISSÂO DEVE SER DIVIDIDA COM O VENDEDOR
                    
                    if ($userIdVinculed !== null && isset($usersGroupVinculed[0]))
                    {
                        $novoValor = round(($usersGroup[$i]['comissaoGrupo'] / 2),2); // valor dividido entre vendedor e usuario vinculado
                        $usersGroupVinculed[0]['comissaoGrupo'] = $novoValor;
                        $usersGroupVinculed[0]['comissaoSupervisor'] = 0.0;
                        
                        $usersGroup[$i]['comissaoGrupo'] = $novoValor;
                        
                    }
                    
                    
                    
                    
                }
                else
                    $usersGroup[$i]['comissaoGrupo'] = 0.0;
                
                array_push($finalUsers, $usersGroup[$i]);
                
            }
            
        }
        
        
        
        if (isset($usersGroupVinculed) && $vinc = $usersGroupVinculed[0])
        {
            array_push($finalUsers, $vinc);
            
        }
            
        
        
        foreach($usersOthers as $i => $value)
        {
            
                 $key = array_search($value['id'], array_column($subtabela['comissoes'], 'idGrupo'));
                    // Se for o vendedor do contrato, adiciona a comissão do grupo
                    if ($key !== false && isset($subtabela['comissoes'][$key]['comissao']))
                        $usersOthers[$i]['comissaoGrupo'] = $subtabela['comissoes'][$key]['comissao'];
                    else
                        $usersOthers[$i]['comissaoGrupo'] = 0.0;
                
                array_push($finalUsers, $usersOthers[$i]);
        }
        
       $gruposSubtrairComissao = array();
        
        if (is_array($finalUsers))
            foreach($finalUsers as $pos => $user)
            {
                $indice = array_search($user['id'], array_column($gruposSubtrairComissao, 'id'));
                if ($indice === false)
                    array_push($gruposSubtrairComissao, array('idGrupo' => $user['id'], 'nomeGrupo' => $user['nomeGrupo'], 'percentual' => $user['comissaoGrupo']));
            }
        
        
        
        
        // FORMATA OS DADOS PARA SEREM ENVIADOS AO BANCO DE DADOS E PERSISTIDOS
        
       $comissaoTotal = $subtabela['comissaoTotal'];
       $comissaoLoja = $comissaoTotal;
       
       $imposto = $subtabela['imposto'];
       
       $comissaoLoja -= $imposto;
        
        // retira o percentual dos grupos
        foreach ($gruposSubtrairComissao as $pos => $value)
            $comissaoLoja -= $value['percentual'];
        
        // obtem os totais em reais e não em percentual como o trecho acima, pois no BD grava o percentual e também o monetario
        
        $valorComissaoTotal = ($comissaoTotal/100) * $dados['valortotal'];
        
        //$valorComissaoTotalSemImposto = ($valorComissaoTotal - ($valorComissaoTotal * ($imposto / 100) ));
        
       //  var_dump($comissaoLoja); exit;
        
        // desconta comissao dos supervisores
        /*
        * por solicitação do Luiz não será descontado através do sistema a comissão de supervisores no montante da loja
        *
        foreach($usersGroup as $i => $value)
            if ($value['indicaSupervisor'])
                $comissaoLoja -= $value['comissaoSupervisor'];
        */
        
        // desconta o percentual do grupo que o usuário está
        //echo '<pre>'; var_dump($subtabela); exit;
       /* if (is_array($subtabela['comissoes']))
            foreach($subtabela['comissoes'] as $i => $value)
            {
            //  if  ( array_search($value2['nomeGrupo'],  array_column($value['comissoes'], 'nomeGrupo'))   !== false || in_array($grupoUsuario['id'], explode(',',$value2['recebeDe'])  ) === true     )
                
                if ($value['idGrupo'] == $usersGroup[0]['id'])
                {
                    $comissaoLoja -= $value['comissao'];
                    break;
                }
            }*/
        
        
        
        // desconta o percentual dos grupos que recebem comissão também
       // $descontaOutrosGrupos = 0;
        /*
        foreach($usersOthers as $i => $value)
        {
            $key = array_search($value['id'], array_column($subtabela['comissoes'], 'idGrupo'));
            if ($key !== false && isset($subtabela['comissoes'][$key]['comissao']))
                 $descontaOutrosGrupos += $subtabela['comissoes'][$key]['comissao'];
        }*/
        
        
        
            //$comissaoLoja -= $descontaOutrosGrupos;
        
         
       
        
        //var_dump($finalUsers); exit;
        
        //var_dump($contratoCarregado); exit;
        $persist['contrato']['id'] = (isset($contratoCarregado['id'])) ? $contratoCarregado['id'] : null;
        $persist['contrato']['cpf'] = $cliente['dados']['cpf'];
        $persist['contrato']['idUsuario'] = $userId;
        $persist['contrato']['idUsuarioVinculado'] = $userIdVinculed;
        $persist['contrato']['idSubtabela'] = $subtabela['id'];
        $persist['contrato']['idEntidade'] = $tabela['idConvenio'];
        $persist['contrato']['nomeCliente'] = $cliente['dados']['nomeCliente'];
        $persist['contrato']['cep'] = $cliente['dados']['cep'];
        $persist['contrato']['rua'] = $cliente['dados']['rua'];
        $persist['contrato']['numeroRua'] = $cliente['dados']['numeroRua'];
        $persist['contrato']['complemento'] = $cliente['dados']['complemento'];
        $persist['contrato']['bairro'] = $cliente['dados']['bairro'];
        $persist['contrato']['uf'] = $cliente['dados']['uf'];
        $persist['contrato']['cidade'] = $cliente['dados']['cidade'];
        
        
        $key = array_search($dados['idcontabancariacliente'], array_column($cliente['contas'], 'idContaBancariaCliente'));
        $persist['contrato']['idContaBancariaCliente'] = $dados['idcontabancariacliente'];
        $persist['contrato']['codigoBancoCliente'] = ($key === false) ? null :  $cliente['contas'][$key]['codigoBanco'];
        $persist['contrato']['nomeBancoCliente'] = ($key === false) ? null :  $cliente['contas'][$key]['nomeBanco'];
        $persist['contrato']['contaBancoCliente'] = ($key === false) ? null :  $cliente['contas'][$key]['conta'];
        $persist['contrato']['agenciaBancoCliente'] = ($key === false) ? null :  $cliente['contas'][$key]['agencia'];
        $persist['contrato']['tipoContaBancoCliente'] = ($key === false) ? null :  $cliente['contas'][$key]['descricaoConta'];
        
        $persist['contrato']['codigoBancoConvenio'] = $tabela['codigoBanco'];
        $persist['contrato']['nomeBancoContrato'] = $tabela['nomeBanco'];
        $persist['contrato']['nomeConvenio'] = $tabela['nomeConvenio'];
        $persist['contrato']['nomeOperacao'] = $subtabela['nomeOperacao'];
        $persist['contrato']['nomeTabela'] = $tabela['nomeTabela'];
        
        $persist['contrato']['comissaoTotal'] = $subtabela['comissaoTotal'];
        $persist['contrato']['valorSeguro'] = $dados['seguro'];
        $persist['contrato']['percentualImposto'] = $subtabela['imposto'];
        $persist['contrato']['quantidadeParcelas'] = $dados['prazo'];
        $persist['contrato']['valorParcela'] = $dados['valorparcela'];
        $persist['contrato']['valorTotal'] = $dados['valortotal'];
        $persist['contrato']['valorLiquido'] = $dados['valorliquido'];
        $persist['contrato']['percentualLoja'] = $comissaoLoja;
        $persist['contrato']['valorLoja'] = round(($dados['valortotal']  * ($comissaoLoja/100)), 2)  ;
        $persist['contrato']['status'] = $dados['status'];
        $persist['contrato']['observacao'] = (empty($dados['observacao']))? null : $dados['observacao'];
        //$persist['contrato']['valorComissaoSemImposto'] = round($valorComissaoTotalSemImposto,2);
        $persist['contrato']['substatus'] = $dados['substatus'];
        $persist['contrato']['dataPagamento'] = (empty($dados['dataPagamento'])) ? 'null' : Utils::formatStringDate($dados['dataPagamento'], 'd/m/Y', 'Y-m-d');
        $persist['contrato']['dataPagamentoBanco'] = (empty($dados['dataPagamentoBanco'])) ? 'null' : Utils::formatStringDate($dados['dataPagamentoBanco'], 'd/m/Y', 'Y-m-d');
         $persist['contrato']['idTipoConvenio'] = (empty(trim($dados['idtipoconvenio']))) ? null : $dados['idtipoconvenio'];
      // var_dump($persist['contrato']['id']);exit;
        $persist['comissoes'] = $finalUsers;
        
        // distribuir comissão para os usuarios
        
        $contrato = new Contrato();
        if (empty($persist['contrato']['id']))
            $result = $contrato->inserir($persist);
        else
        {
            
            $result = $contrato->atualizar($persist);
            echo json_encode(array('success' => true, 'id' => 'update'));
            exit;
        }
        
        if ($result === false)
            $json = array('success' => false, 'message' => 'Não foi possível salvar o contrato. '. $contrato->getMysqlError());
        else
        {
            switch($result)
            {
                case -1: 
                    $json = array('success' => false, 'message' => 'Não foi possível obter um novo número de contrato');
                    break;
                case -2:
                    $json = array('success' => false, 'message' => 'Não foi possível atualizar o controlador de numeros de contratos');
                    break;
                    case -3:
                    $json = array('success' => false, 'message' => 'Não foi possível obter o bloqueio exclusivo para numero do contrato');
                    break;
                default:
                    $json = array('success' => true, 'id' => $result);
                    break;
            }
        }
        
        echo json_encode($json);
        
    }
    
    
     public function excluirContrato()
    {
        if (! \Application::isAuthorized('Contratos' , 'contrato', 'remover'))
        {
             $json['success'] = false;
             $json['message'] = 'Usuário sem autorização';
             echo json_encode($json);
            exit;
        }
        
        $id = trim($_REQUEST['id']);
        
        $contrato = new Contrato();
        
        $result = $contrato->excluir($id);
         
       
         
        if ($result === false)
        {
            $json['success'] = false;
            $json['message'] = 'Não foi possível remover o registro. '. $contrato->getMysqlError();
        }else 
        {
             $json['success'] = true;
            // $json['id'] = $result;
        }
        
        echo json_encode($json);
        exit;
        
    }
    
    
    
    public function mapaProducao()
    {
        if (! \Application::isAuthorized('Contratos' , 'mapa_producao', 'ler') && ! \Application::isAuthorized('Contratos' , 'mapa_producao', 'escrever'))
            \Application::print404();
        
        $this->setView('mapaproducao/index');
    
        
        $usuarios = new Usuario();
        $result = $usuarios->listarUsuarios();
        if ($result !== false)
            $this->setParams('usuarios', $result);
        
        $grupo = new GrupoUsuario();
        $result = $grupo->listarGrupos();
        if ($result !== false)
            $this->setParams('grupos', $result);
        
        $banco = new Banco();
        $result = $banco->listarBancos();
        if ($result !== false)
            $this->setParams('bancos', $result);
        
        
        $convenio = new Convenio();
        $result = $convenio->listarEntidades();
        if ($result != false)
            $this->setParams('convenios', $result);
        
        $operacao = new Operacao();
        $result = $operacao->listarOperacoes();
        if ($result !== false)
            $this->setParams('operacoes', $result);
        
        $tabela = new Tabela();
        $result = $tabela->listarTabelas();
        if ($result !== false)
            $this->setParams('tabelas', $result);
        
        $substatus = new SubstatusContrato();
        $recordSet = $substatus->listarSubstatus();
        if($recordSet !== false)
            $this->setParams('substatus', $recordSet);

        $this->showContents();
    }
    
    
    public function pesquisarMapaProducao()
    {
        
        if (! \Application::isAuthorized('Contratos' , 'mapa_producao', 'ler') && ! \Application::isAuthorized('Contratos' , 'mapa_producao', 'escrever'))
        {
             $json['success'] = false;
             $json['message'] = 'Usuário sem autorização';
             echo json_encode($json);
            exit;
        }
        
        
        
        
        $dataInicio = (empty($_REQUEST['datainicial'])) ? null : Utils::formatStringDate($_REQUEST['datainicial'], 'd/m/Y', 'Y-m-d');
        $dataFim = (empty($_REQUEST['datafinal'])) ? null : Utils::formatStringDate($_REQUEST['datafinal'], 'd/m/Y', 'Y-m-d');
        $dataInicioModificacao = (empty($_REQUEST['datainicialmodificacao'])) ? null : Utils::formatStringDate($_REQUEST['datainicialmodificacao'], 'd/m/Y', 'Y-m-d');
        $dataFimModificacao = (empty($_REQUEST['datafinalmodificacao'])) ? null : Utils::formatStringDate($_REQUEST['datafinalmodificacao'], 'd/m/Y', 'Y-m-d');
        
        
        $usuario = (empty($_REQUEST['usuario'])) ? null : $_REQUEST['usuario'];
        $grupo = (empty($_REQUEST['grupousuario'])) ? null : $_REQUEST['grupousuario'];
        $banco = (empty($_REQUEST['banco'])) ? null : $_REQUEST['banco'];
        $convenio = (empty($_REQUEST['convenio'])) ? null : $_REQUEST['convenio'];
        $operacao = (empty($_REQUEST['operacao'])) ? null : $_REQUEST['operacao'];
        $limit = (empty($_REQUEST['limit'])) ? null : $_REQUEST['limit'];
        $tabela = (empty($_REQUEST['tabela'])) ? null : $_REQUEST['tabela'];
        $pagovendedor = (empty($_REQUEST['pagovendedor'])) ? null : $_REQUEST['pagovendedor'];
        $recebidoComissaoBanco = (empty($_REQUEST['recebidocomissaobanco'])) ? null : $_REQUEST['recebidocomissaobanco'];
        $status = (empty($_REQUEST['status']) || count($_REQUEST['status']) < 1  ) ? null : $_REQUEST['status'];
        $subStatus = (empty($_REQUEST['substatus'])) ? null : $_REQUEST['substatus'];
        //var_dump($dataFim);exit;
        $contrato = new Contrato();
        $result = $contrato->listarMapaProducao($usuario, $grupo, $banco, $operacao,  $convenio, $tabela, $pagovendedor, $status, $dataInicio, $dataFim, $limit, 
                    $dataInicioModificacao, $dataFimModificacao, $recebidoComissaoBanco, $subStatus);
        
        if (is_array($result))
            echo json_encode($result);
        else
            echo json_encode(array());
        
    }

	

}

?>