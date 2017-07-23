<?php

namespace Gauchacred\controller;



//use \controller\Controller as Controller;
//use \library\php\Blowfish as Blowfish;

use Gauchacred\library\php\Utils as Utils;
use Gauchacred\model\Agenda as Agenda;
use Gauchacred\model\Usuario as Usuario;
use Gauchacred\model\Cliente as Cliente;
use Gauchacred\model\GadgetsHome as Gadgets;
use Gauchacred\model\GrupoUsuario;
use Gauchacred\model\Contrato;
use Gauchacred\model\PontoTroca;

/**
 * @author moises
 * @version 1.0
 * @created 13-set-2015 23:08:59
 */
class Home extends Controller
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
        $this->setView('home/index');
       // $this->setHeaderInclude(false);
       // $this->setFooterInclude(false);
      //  $this->addCss('bootstrap');
        //$this->addCss('style');
    //    $this->addJs('responsiveslides.min');

        $agenda = new Agenda();
        // data de amanhã para passar no parametro de data final
        $dataAmanha = strftime ("%Y-%m-%d", mktime (0, 0, 0, date("m")  , date("d")+1, date("Y")));
        $result = $agenda->listarAgenda(null, false, 100, null, null, null, date('Y-m-d'), $dataAmanha, null, 15);
       // var_dump($result); exit;
        if ($result !== false)
            $this->setParams('agenda', $result);


        // recupera aniversariantes




        $cliente = new Cliente();
        $result = $cliente->aniversariantes(date('d'), date('m'));
        if ($result !== false)
            $this->setParams('aniversariantes', $result);


        $gadGets = new Gadgets();
        $result = $gadGets->metaMensal($_SESSION['userid']);
        if ($result !== false)
            $this->setParams('metamensal', $result);

        if (! \Application::isAuthorized(ucfirst(strtolower('home')) , 'pagina_inicial_vendas_admin', 'ler'))
            $result = $gadGets->totalVendasDia($_SESSION['userid']);
        else
            $result = $gadGets->totalVendasDia();
        if ($result !== false)
            $this->setParams('totalvendasdia', $result);


        if (! \Application::isAuthorized(ucfirst(strtolower('home')) , 'pagina_inicial_vendas_admin', 'ler'))
            $result = $gadGets->totalVendasPagasDia($_SESSION['userid']);
        else
            $result = $gadGets->totalVendasPagasDia();
        if ($result !== false)
            $this->setParams('totalvendaspagasdia', $result);


        $result = $gadGets->valorVendaSemana($_SESSION['userid']);
        if ($result !== false)
            $this->setParams('valorvendasemana', $result);


        if (! \Application::isAuthorized(ucfirst(strtolower('home')) , 'pagina_inicial_vendas_admin', 'ler'))
            $result = $gadGets->valorVendaMes($_SESSION['userid']);
        else
            $result = $gadGets->valorVendaMes();
        if ($result !== false)
            $this->setParams('valorvendames', $result);


        // pontos de troca disponíveis
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
		      $this->setParams('pontostroca', $totalPontos);
		    }



        $result = $gadGets->melhoresVendedores();
        if ($result !== false)
            $this->setParams('melhoresvendedores', $result);

        $result = $gadGets->totalContratosPendentesMes($_SESSION['userid']);
        if ($result !== false)
            $this->setParams('totalcontratospendentesmes', $result);

        $result = $gadGets->listarNoticias();
        if ($result !== false)
            $this->setParams('noticias', $result);

        $result = $gadGets->comissaoSemanalNovo($_SESSION['userid']);
        if ($result !== false)
            $this->setParams('comissaosemanal', $result);

        $result = $gadGets->comissaoSemanalNovo();
        if ($result !== false)
            $this->setParams('comissaosemanaltodos', $result);
        
        $result = $gadGets->comissaoLoja();
        if ($result !== false)
            $this->setParams('comissaoloja', $result);

        $result = $gadGets->getMetasNovo($_SESSION['userid']);
        if ($result !== false)
            $this->setParams('metasnovo', $result);


        $grupoUsuario = new GrupoUsuario();
        $result = $grupoUsuario->gruposDoUsuario($_SESSION['userid']);
        if ($result !== false )
            $this->setParams('gruposdousuario', $result);



        //******
        /** Adicionados a partir de 29/03/2017
        */

        $result = $gadGets->metaMensalUsuario($_SESSION['userid']);
        if ($result !== false)
            $this->setParams('metamesusuario', $result);

        $result = $gadGets->metaMensalLoja();
        if ($result !== false)
            $this->setParams('metamesloja', $result);


        if (! \Application::isAuthorized(ucfirst(strtolower('home')) , 'pagina_inicial_spoiler_admin', 'ler'))
            $result = $gadGets->getValoresGerais($_SESSION['userid']);
        else
            $result = $gadGets->getValoresGerais();
        if ($result !== false)
            $this->setParams('valoresgerais', $result);


         $result = $gadGets->metaTodosGrupos($_SESSION['userid']);
        if ($result !== false)
            $this->setParams('metatodosgrupos', $result);
        
        //*****
        /** Adicionados em 16/07/2017
        */
        
        $result = $gadGets->obterSomatorioDescontosDevidos($_SESSION['userid']);
        if (is_array($result))
            $this->setParams('descontosdevidos', $result);


        $this->showContents();
	}



    public function reagendarLigacao()
    {
        if (! \Application::isAuthorized(ucfirst(strtolower('clientes')) , 'agenda', 'escrever'))
        {
             $json['success'] = false;
             $json['message'] = 'Usuário sem autorização';
             echo json_encode($json);
            exit;
        }

         $id = trim($_REQUEST['id']);

        $agenda = new Agenda();
        $result = $agenda->listarAgenda($id);
        if ($result != false && is_array($result[0]))
            $dataAtual = Utils::formatStringDate($result[0]['dataLigacao'], 'd/m/Y H:i:s', 'Y-m-d H:i:s');
        else
        {
            $json['success'] = false;
             $json['message'] = 'Não foi possível cnverter a data atual para uma nova data';
             echo json_encode($json);
            exit;
        }

        $hora = explode(' ', $dataAtual);
        $dataFutura = strftime ("%Y-%m-%d", mktime (0, 0, 0, date("m")  , date("d")+5, date("Y")));

        // verifica se o dia da semana é 0 = domingo, caso seja move para a segunda
        if (date('w', strtotime($dataFutura)) == 0  )
            $dataFutura = strftime ("%Y-%m-%d", mktime (0, 0, 0, date("m")  , date("d")+6, date("Y")));

        $dataFutura = $dataFutura . ' ' . $hora[1];



        $result = $agenda->reagendar($id, $dataFutura);

        if ($result === false)
        {
            $json['success'] = false;
            $json['message'] = 'Não foi possível alterar a agenda. '. $agenda->getMysqlError();
        }else if ($result === 1)
        {
            $json['success'] = true;
            $json['message'] = 'Nenhum registro encontrado';
        }else
        {
             $json['success'] = true;
             $json['id'] = $result;
        }

        echo json_encode($json);
        exit;

    }


    public function salvarNoticia()
    {

        $message = $_POST['noticia'];


        $gadGets = new Gadgets();
        $result = $gadGets->salvarNoticia($_SESSION['userid'], $message );

         if ($result === false)
        {
            $json['success'] = false;
            $json['message'] = 'Não foi possível alterar a agenda. '. $agenda->getMysqlError();
        }else
        {
             $json['success'] = true;
             //$json['id'] = $result;
        }
        echo json_encode($json);
        exit;

    }



}

?>
