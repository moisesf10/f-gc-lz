<?php
namespace Gauchacred\model;


/**
 * @author moises
 * @version 1.0
 * @created 10-nov-2015 16:24:32
 */

use Gauchacred\library\php\MySqlError as MySqlError;
use Gauchacred\library\php\Utils as Utils;
use Gauchacred\model\PontoTroca;
use Gauchacred\model\Subtabela;

class Contrato implements MySqlError
{

	private $errorCode = '';
    private $mysqlError = '';

    public function __construct()
    {

    }

    public function getMysqlError()
    {
        return $this->errorCode;
    }


     public function listarContratos($id = null, $cpfCliente = null, $numeroContrato = null, $nomeCliente = null, $idUsuario = null,  $operacao = null, $status = null, $convenio = null, $dataInicio = null, $dataFim = null,   $limit = 10, $inicioModificacao = null, $fimModificacao = null, $inicioPagamento = null, $fimPagamento = null, $statusPagamento = null,
                $columnOrder = 1, $typeOrder = 'asc', $dataComissaoBancoInicio = null, $dataComissaoBancoFim = null, $statusComissaoBanco = null, $qualquerUsuario = false)
    {


         $id = ($id === null) ? '%' : $id;
         $cpfCliente = ($cpfCliente === null) ? '%' : $cpfCliente;
        $numeroContrato = ($numeroContrato === null) ? '%' : $numeroContrato;
         $nomeCliente = ($nomeCliente === null) ? '%' : '%'. $nomeCliente. '%' ;
         $operacao = ($operacao === null) ? '%' : $operacao;
         $status = ($status === null) ? '%' : $status;
         $convenio = ($convenio === null) ? '%' : $convenio;
         $dataInicio = ($dataInicio === null) ? '0001-01-01' : $dataInicio;
         $dataFim = ($dataFim === null) ? '2100-01-01 23:59:59' : $dataFim . ' 23:59:59';
		 $inicioModificacao = ($inicioModificacao === null) ? '0001-01-01' : $inicioModificacao;
         $fimModificacao = ($fimModificacao === null) ? '2100-01-01 23:59:59' : $fimModificacao . ' 23:59:59';
         $idUsuario = ($idUsuario === null) ? '%' : $idUsuario;
         $columnOrder = (empty($columnOrder)) ? 1 : $columnOrder;
         $typeOrder = (empty($typeOrder)) ? 'asc' : $typeOrder;

         if ($qualquerUsuario == false)
             $idUsuario = $_SESSION['userid'];


         $inicioPagamento = ($inicioPagamento === null) ? '0001-01-01' : $inicioPagamento;
         $fimPagamento = ($fimPagamento === null) ? '2100-01-01' : $fimPagamento;
         $statusPagamento = (empty($statusPagamento)) ? null : $statusPagamento;

         $dataComissaoBancoInicio = ($dataComissaoBancoInicio === null) ? '0001-01-01' : $dataComissaoBancoInicio;
         $dataComissaoBancoFim = ($dataComissaoBancoFim === null) ? '2100-01-01' : $dataComissaoBancoFim;
         $statusComissaoBanco = (empty($statusComissaoBanco)) ? null : $statusComissaoBanco;

         switch($statusPagamento)
         {
             case null: $statusPagamento = '(c.datapagamento is null or c.datapagamento is not null)'; break;
             case 'Pago': $statusPagamento = '(c.datapagamento is not null)'; break;
             case 'Aberto': $statusPagamento = '(c.datapagamento is null)';  break;
             default: $statusPagamento = '(c.datapagamento is null or c.datapagamento is not null)'; break;
         }

        switch($statusComissaoBanco)
         {
             case null: $statusComissaoBanco = '(c.datapagamentobanco is null or c.datapagamentobanco is not null)'; break;
             case 'Sim': $statusComissaoBanco = '(c.datapagamentobanco is not null)'; break;
             case 'Nao': $statusComissaoBanco = '(c.datapagamentobanco is null)';  break;
             default: $statusComissaoBanco = '(c.datapagamentobanco is null or c.datapagamentobanco is not null)'; break;
         }

        $return = false;

        $connection = \Application::getNewDataBaseInstance();
        $query = "
                select distinct
                c.id, c.clientes_cpf as 'cpf', c.subtabelas_id as 'idsubtabela', c.entidades_id as 'identidade', c.contabancariaclientes_id, c.nome, c.cep, c.rua, c.numero, c.complemento,
                c.bairro, c.uf, c.cidade, c.codigobancocliente, c.nomebancocliente, c.contabancocliente, c.agenciabancocliente,
                c.tipocontabancocliente, c.numerocontrato, c.created, c.modified, c.codigobancoconvenio, c.nomebancocontrato, c.nomeconvenio,
                c.nomeoperacao, c.nometabela, c.comissaototal, c.valorseguro, c.percentualimposto, c.quantidadeparcelas, c.valorparcela,
                c.valortotal, c.valorliquido, c.percentualloja, c.valorloja,
                u.id as 'idusuario', u.nome as 'nomeusuario', c.status, c.tipoconvenio_id, cc.comissoes, c.datapagamento, c.datapagamentobanco, c.substatus, c.contratossubstatus_id,
                c.usuariovinculado_id, uvi.nome as 'usuariovinculado', c.observacao, csub.descricao

                from contratos c
                  inner join usuarios u on u.id = c.usuarios_id
                  left join usuarios uvi on uvi.id = c.usuariovinculado_id
                  left join (
                    select distinct
                    cc.contratos_id, group_concat(cc.id, ',' , cc.contratos_id, ',' , cc.nomegrupo, ',', cc.percentualgrupo, ',', cc.valorgrupo, ',', cc.percentualsupervisor, ',', cc.valorsupervisor, ',',
                    us.id, ',', us.cpf, ',', us.nome SEPARATOR '|') as 'comissoes'
                    from comissoescontrato cc
                      inner join usuarios us on us.id = cc.usuarios_id
                    group by cc.contratos_id
                  ) cc on cc.contratos_id = c.id
                  left join clientes cli on cli.cpf_cnpj = c.clientes_cpf
				  left join contratossubstatus csub on csub.id = c.contratossubstatus_id

                where c.id like ? and c.clientes_cpf like ?
                and ( c.datapagamento between ? and ? or (? = '0001-01-01' and ? = '2100-01-01')  )
                and ( c.datapagamentobanco between ? and ? or (? = '0001-01-01' and ? = '2100-01-01')  )
                and $statusPagamento
                and $statusComissaoBanco
                and c.numerocontrato like ? and (cli.nome like ? or ? = '%')
                and c.nomeconvenio like ? and ( c.created between ? and ? or (? = '0001-01-01' and ? = '2100-01-01')  )
                and c.status like ? and c.usuarios_id like ?
				and c.modified between ? and ?
				and c.nomeoperacao like ?

                order by $columnOrder $typeOrder
                limit ?
        ";


         if ($stm = $connection->prepare($query))
        {
            $stm->bind_param('sssssssssssssssssssssssi',  $id, $cpfCliente, $inicioPagamento, $fimPagamento, $inicioPagamento, $fimPagamento, $dataComissaoBancoInicio, $dataComissaoBancoFim, $dataComissaoBancoInicio, $dataComissaoBancoFim, $numeroContrato, $nomeCliente, $nomeCliente, $convenio, $dataInicio, $dataFim, $dataInicio, $dataFim, $status, $idUsuario, $inicioModificacao, $fimModificacao, $operacao,  $limit);
            if ($stm->execute())
            {
                $stm->bind_result($id, $cpf, $idSubtabela, $idEntidade, $idContaBancariaCliente, $nomeCliente, $cep, $rua, $numeroRua, $complementoRua, $bairro, $uf, $cidade, $codigoBancoCliente, $nomeBancoCliente,  $contaBancoCliente, $agenciaBancoCliente, $tipoContaBancoCliente, $numeroContrato, $created, $modified, $codigoBancoConvenio, $nomeBancoContrato, $nomeConvenio,
                                 $nomeOperacao, $nomeTabela, $comissaoTotal, $valorSeguro, $percentualImposto, $quantidadeParcelas, $valorParcela, $valorTotal, $valorLiquido, $percentualLoja,$valorLoja, $idUsuario, $nomeUsuario, $status, $idTipoConvenio, $comissoes, $dataPagamento, $dataPagamentoBanco, $subStatus, $idSubstatusContrato, $idUsuarioVinculado, $nomeUsuarioVinculado, $observacao, $descricaoSubstatus);

                $return = array();
                 while ($stm->fetch()) {
                     $v = array();
                     $v['id'] = $id;
                     $v['cpf'] = $cpf;
                     $v['idSubtabela'] = $idSubtabela;
                     $v['idConvenio'] = $idEntidade;
                     $v['idContaBancariaCliente'] = $idContaBancariaCliente;
                     $v['idSubstatusContrato'] = $idSubstatusContrato;
                     $v['nomeCliente'] = $nomeCliente;
                     $v['cep'] = $cep;
                     $v['rua'] = $rua;
                     $v['numeroRua'] = $numeroRua;
                     $v['complementoRua'] = $complementoRua;
                     $v['bairro'] = $bairro;
                     $v['uf'] = $uf;
                     $v['cidade'] = $cidade;
                     $v['codigoBancoCliente'] = $codigoBancoCliente;
                     $v['nomeBancoCliente'] = $nomeBancoCliente;
                     $v['contaBancoCliente'] = $contaBancoCliente;
                     $v['agenciaBancoCliente'] = $agenciaBancoCliente;
                     $v['tipoContaBancoCliente'] = $tipoContaBancoCliente;
                     $v['numeroContrato'] = $numeroContrato;
                     $v['created'] = Utils::formatStringDate($created, 'Y-m-d H:i:s', 'd/m/Y H:i:s');
                     $v['modified'] = Utils::formatStringDate($modified, 'Y-m-d H:i:s', 'd/m/Y H:i:s');
                     $v['codigoBancoConvenio'] = $codigoBancoConvenio;
                     $v['nomeBancoContrato'] = $nomeBancoContrato;
                     $v['nomeConvenio'] = $nomeConvenio;
                     $v['nomeOperacao'] = $nomeOperacao;
                     $v['nomeTabela'] = $nomeTabela;
                     $v['comissaoTotal'] = $comissaoTotal;
                     $v['valorSeguro'] = $valorSeguro;
                     $v['percentualImposto'] = $percentualImposto;
                     $v['quantidadeParcelas'] = $quantidadeParcelas;
                     $v['valorParcela'] = $valorParcela;
                     $v['valorTotal'] = $valorTotal;
                     $v['valorLiquido'] = $valorLiquido;
                     $v['percentualLoja'] = $percentualLoja;
                     $v['valorLoja'] = $valorLoja;
                     $v['idUsuario'] = $idUsuario;
                     $v['nomeUsuario'] = $nomeUsuario;
                     $v['idUsuarioVinculado'] = $idUsuarioVinculado;
                     $v['nomeUsuarioVinculado'] = $nomeUsuarioVinculado;
                     $v['observacao'] = $observacao;
					 $v['descricaoSubstatus'] = $descricaoSubstatus;
                     $v['status'] = $status;
                     $v['subStatus'] = $subStatus;
                     $v['idTipoConvenio'] = $idTipoConvenio;
                     $v['dataPagamento'] = Utils::formatStringDate($dataPagamento, 'Y-m-d', 'd/m/Y');
                     $v['dataPagamentoBanco'] = Utils::formatStringDate($dataPagamentoBanco, 'Y-m-d', 'd/m/Y');
                     $v['pagoVendedor'] = (empty($dataPagamento)) ? false : true;
                     $v['recebidoComissaoBanco'] = (empty($dataPagamentoBanco)) ? false : true;

                     $v['comissoes'] = array();
                     $comissoes = explode('|', $comissoes);
                     foreach($comissoes as $i => $comissao)
                     {
                         $comissao = explode(',', $comissao);
                         if (count($comissao) > 0)
                         {
                             $c = array();
                             $c['id'] = (isset($comissao[0])) ? $comissao[0] : null;
                             $c['idContrato'] = (isset($comissao[1])) ? $comissao[1] : null;
                             $c['nomeGrupo'] = (isset($comissao[2])) ? $comissao[2] : null;
                             $c['percentualGrupo'] = (isset($comissao[3])) ? $comissao[3] : null;
                            $c['valorGrupo'] = (isset($comissao[4])) ? $comissao[4] : null;
                             $c['percentualSupervisor'] = (isset($comissao[5])) ? $comissao[5] : null;
                             $c['valorSupervisor'] = (isset($comissao[6])) ? $comissao[6] : null;
                             $c['idUsuario'] = (isset($comissao[7])) ? $comissao[7] : null;
                             $c['cpfUsuario'] = (isset($comissao[8])) ? $comissao[8] : null;
                             $c['nomeUsuario'] =(isset($comissao[9])) ? $comissao[9] : null;
                             array_push($v['comissoes'], $c);
                         }
                     }
                     array_push($return, $v);
                 }
            }

        }
         else
        {
            \Application::setMysqlLogQuery('Classe Contrato; Método listarContratos; Mysql '. $connection->error);
             $this->mysqlError = $connection->errno;
        }
        return $return;

    }



    public function listarMapaProducao($idUsuario = null, $idGrupo = null, $nomeBancoContrato = null, $nomeOperacao = null,  $nomeConvenio = null, $nomeTabela, $pagoVendedor = null, $status = null, $dataInicio = null, $dataFim = null, $limit = 10, $dataInicioModificacao = null, $dataFimModificacao = null, $recebidoComissaoBanco = null, $subStatus = null)
    {



         $nomeOperacao = ($nomeOperacao === null) ? '%' : $nomeOperacao;
         $nomeConvenio = ($nomeConvenio === null) ? '%' : $nomeConvenio;
         $nomeBancoContrato = ($nomeBancoContrato === null) ? '%' : $nomeBancoContrato;
         $nomeTabela = ($nomeTabela === null) ? '%' : $nomeTabela;
         $dataInicio = ($dataInicio === null) ? '2013-01-01' : $dataInicio;
         $dataFim = ($dataFim === null) ? '2100-01-01 23:59:59' : $dataFim . ' 23:59:59';
         $dataInicioModificacao = ($dataInicioModificacao === null) ? '2013-01-01' : $dataInicioModificacao;
         $dataFimModificacao = ($dataFimModificacao === null) ? '2100-01-01 23:59:59' : $dataFimModificacao . ' 23:59:59';
         $idUsuario = ($idUsuario === null) ? '%' : $idUsuario;
         $idGrupo = ($idGrupo === null) ? '%' : $idGrupo;
         $pagoVendedor = ($pagoVendedor === null) ? '%' : $pagoVendedor;
         $recebidoComissaoBanco = ($recebidoComissaoBanco === null) ? '%' : $recebidoComissaoBanco;
         $subStatus = ($subStatus === null) ? '%' : $subStatus;

         $axPagoVendedor = '';
         switch($pagoVendedor)
         {
             case 'pago':
                 $axPagoVendedor = ' and  c.datapagamento is not null ';
                 break;
             case 'naopago':
                 $axPagoVendedor = ' and  c.datapagamento is null ';
                 break;
         }

         $pagoVendedor = $axPagoVendedor;


        switch($recebidoComissaoBanco)
         {
             case 'sim':
                 $recebidoComissaoBanco = ' and  c.datapagamentobanco is not null ';
                 break;
             case 'nao':
                 $recebidoComissaoBanco = ' and  c.datapagamentobanco is null ';
                 break;
             default:
                 $recebidoComissaoBanco = '';
                 break;
         }





         $axStatus = '';
         if (is_array($status) && count($status) > 0  )
         {

            $axStatus = "and c.status in (";
            foreach($status as $i => $value)
                    $axStatus .= "'". $value . "',";


            $axStatus = preg_replace('/[,]$/','', $axStatus);
            $axStatus .= ")";



         }else if ($status !== null)
             $axStatus = "and c.status = '$status'";
            else
                $axStatus = "and c.status like '%'";

         $status = $axStatus;

        //var_dump($dataInicio); exit;

        /*
         switch($statusPagamento)
         {
             case null: $statusPagamento = '(c.datapagamento is null or c.datapagamento is not null)'; break;
             case 'Pago': $statusPagamento = '(c.datapagamento is not null)'; break;
             case 'Aberto': $statusPagamento = '(c.datapagamento is null)';  break;
             default: $statusPagamento = '(c.datapagamento is null or c.datapagamento is not null)'; break;
         }*/

        $return = false;

        $connection = \Application::getNewDataBaseInstance();
        $query = "
                select distinct
                c.id, c.clientes_cpf as 'cpf', c.subtabelas_id as 'idsubtabela', c.entidades_id as 'identidade', c.contabancariaclientes_id, c.nome, c.cep, c.rua, c.numero, c.complemento,
                c.bairro, c.uf, c.cidade, c.codigobancocliente, c.nomebancocliente, c.contabancocliente, c.agenciabancocliente,
                c.tipocontabancocliente, c.numerocontrato, c.created, c.modified, c.codigobancoconvenio, c.nomebancocontrato, c.nomeconvenio,
                c.nomeoperacao, c.nometabela, c.comissaototal, c.valorseguro, c.percentualimposto, c.quantidadeparcelas, c.valorparcela,
                c.valortotal, c.valorliquido, c.percentualloja, c.valorloja,
                u.id as 'idusuario', u.nome as 'nomeusuario', c.status, c.tipoconvenio_id, cc.comissoes, c.datapagamento, c.datapagamentobanco, c.substatus, c.contratossubstatus_id,
                cs.descricao

                from contratos c
                  inner join usuarios u on u.id = c.usuarios_id
                  left join (
                    select distinct
                    cc.contratos_id, group_concat(cc.id, ',' , cc.contratos_id, ',' , cc.nomegrupo, ',', cc.percentualgrupo, ',', cc.valorgrupo, ',', cc.percentualsupervisor, ',', cc.valorsupervisor, ',',
                    us.id, ',', us.cpf, ',', us.nome SEPARATOR '|') as 'comissoes'
                    from comissoescontrato cc
                      inner join usuarios us on us.id = cc.usuarios_id
                    where cc.grupousuarios_id like ?
                    group by cc.contratos_id
                  ) cc on cc.contratos_id = c.id
                  left join clientes cli on cli.cpf_cnpj = c.clientes_cpf
                  left join contratossubstatus cs on cs.id = c.contratossubstatus_id

                where (cc.comissoes is not null or ? = '%')
                $status
                $pagoVendedor
                $recebidoComissaoBanco
                and c.nometabela like ?
                and c.nomeoperacao like ?
                and c.nomeconvenio like ?
                and c.nomebancocontrato like ?
				and c.created between ? and ?
                and c.modified between ? and ?
                and c.usuarios_id like ?
                and (c.contratossubstatus_id like ? or ? = '%')
                order by 1
                limit ?
        ";
       //var_dump($query); exit;

         if ($stm = $connection->prepare($query))
        {
            $stm->bind_param('sssssssssssssi',  $idGrupo, $idGrupo, $nomeTabela, $nomeOperacao, $nomeConvenio, $nomeBancoContrato, $dataInicio, $dataFim, $dataInicioModificacao, $dataFimModificacao, $idUsuario, $subStatus, $subStatus, $limit );
            if ($stm->execute())
            {
                $stm->bind_result($id, $cpf, $idSubtabela, $idEntidade, $idContaBancariaCliente, $nomeCliente, $cep, $rua, $numeroRua, $complementoRua, $bairro, $uf, $cidade, $codigoBancoCliente, $nomeBancoCliente,  $contaBancoCliente, $agenciaBancoCliente, $tipoContaBancoCliente, $numeroContrato, $created, $modified, $codigoBancoConvenio, $nomeBancoContrato, $nomeConvenio,
                                 $nomeOperacao, $nomeTabela, $comissaoTotal, $valorSeguro, $percentualImposto, $quantidadeParcelas, $valorParcela, $valorTotal, $valorLiquido, $percentualLoja,$valorLoja, $idUsuario, $nomeUsuario, $status, $idTipoConvenio, $comissoes, $dataPagamento, $dataPagamentoBanco, $subStatus, $idSubstatusContrato, $descricaoSubstatus);

                $return = array();
                 while ($stm->fetch()) {
                     $v = array();
                     $v['id'] = $id;
                     $v['cpf'] = $cpf;
                     $v['idSubtabela'] = $idSubtabela;
                     $v['idConvenio'] = $idEntidade;
                     $v['idContaBancariaCliente'] = $idContaBancariaCliente;
                     $v['idSubstatusContrato'] = $idSubstatusContrato;
                     $v['descricaoSubstatus'] = $descricaoSubstatus;
                     $v['nomeCliente'] = $nomeCliente;
                     $v['cep'] = $cep;
                     $v['rua'] = $rua;
                     $v['numeroRua'] = $numeroRua;
                     $v['complementoRua'] = $complementoRua;
                     $v['bairro'] = $bairro;
                     $v['uf'] = $uf;
                     $v['cidade'] = $cidade;
                     $v['codigoBancoCliente'] = $codigoBancoCliente;
                     $v['nomeBancoCliente'] = $nomeBancoCliente;
                     $v['contaBancoCliente'] = $contaBancoCliente;
                     $v['agenciaBancoCliente'] = $agenciaBancoCliente;
                     $v['tipoContaBancoCliente'] = $tipoContaBancoCliente;
                     $v['numeroContrato'] = $numeroContrato;
                     $v['created'] = Utils::formatStringDate($created, 'Y-m-d H:i:s', 'd/m/Y H:i:s');
                     $v['modified'] = Utils::formatStringDate($modified, 'Y-m-d H:i:s', 'd/m/Y H:i:s');
                     $v['codigoBancoConvenio'] = $codigoBancoConvenio;
                     $v['nomeBancoContrato'] = $nomeBancoContrato;
                     $v['nomeConvenio'] = $nomeConvenio;
                     $v['nomeOperacao'] = $nomeOperacao;
                     $v['nomeTabela'] = $nomeTabela;
                     $v['comissaoTotal'] = $comissaoTotal;
                     $v['valorSeguro'] = $valorSeguro;
                     $v['percentualImposto'] = $percentualImposto;
                     $v['quantidadeParcelas'] = $quantidadeParcelas;
                     $v['valorParcela'] = $valorParcela;
                     $v['valorTotal'] = $valorTotal;
                     $v['valorLiquido'] = $valorLiquido;
                     $v['percentualLoja'] = $percentualLoja;
                     $v['valorLoja'] = $valorLoja;
                     $v['idUsuario'] = $idUsuario;
                     $v['nomeUsuario'] = $nomeUsuario;
                     $v['status'] = $status;
                     $v['subStatus'] = $subStatus;
                     $v['idTipoConvenio'] = $idTipoConvenio;
                     $v['dataPagamento'] = Utils::formatStringDate($dataPagamento, 'Y-m-d', 'd/m/Y');
                     $v['dataPagamentoBanco'] = Utils::formatStringDate($dataPagamentoBanco, 'Y-m-d', 'd/m/Y');
                     $v['pagoVendedor'] = (empty($dataPagamento)) ? false : true;
                     $v['recebidoComissaoBanco'] = (empty($dataPagamentoBanco)) ? false : true;

                     $v['comissoes'] = array();
                     $comissoes = explode('|', $comissoes);
                     foreach($comissoes as $i => $comissao)
                     {
                         $comissao = explode(',', $comissao);
                         if (count($comissao) > 0)
                         {
                             $c = array();
                             $c['id'] = (isset($comissao[0])) ? $comissao[0] : null;
                             $c['idContrato'] = (isset($comissao[1])) ? $comissao[1] : null;
                             $c['nomeGrupo'] = (isset($comissao[2])) ? $comissao[2] : null;
                             $c['percentualGrupo'] = (isset($comissao[3])) ? $comissao[3] : null;
                            $c['valorGrupo'] = (isset($comissao[4])) ? $comissao[4] : null;
                             $c['percentualSupervisor'] = (isset($comissao[5])) ? $comissao[5] : null;
                             $c['valorSupervisor'] = (isset($comissao[6])) ? $comissao[6] : null;
                             $c['idUsuario'] = (isset($comissao[7])) ? $comissao[7] : null;
                             $c['cpfUsuario'] = (isset($comissao[8])) ? $comissao[8] : null;
                             $c['nomeUsuario'] =(isset($comissao[9])) ? $comissao[9] : null;
                             array_push($v['comissoes'], $c);
                         }
                     }
                     array_push($return, $v);
                 }
            }

        }
         else
        {
            \Application::setMysqlLogQuery('Classe Contrato; Método listarMapaProduçcao; Mysql '. $connection->error);
             $this->mysqlError = $connection->errno;
        }
        return $return;

    }



    public function reservarNumero()
    {


        $return = false;

        $connection = \Application::getNewDataBaseInstance();
        $query = "
                select contador from geradoroid where nome = 'numerocontrato' for update;
        ";


         if ($stm = $connection->prepare($query))
        {
           // $stm->bind_param('s',  $id);
            if ($stm->execute())
            {
                $stm->bind_result($contador);

                $return = array();
                if($stm->fetch())
                    $return = $contador;
            }

        }
         else
        {
            \Application::setMysqlLogQuery('Classe Contrato; Método reservarNumero; Mysql '. $connection->error);
             $this->mysqlError = $connection->errno;
        }
        return $return;

    }


    public function inserir($dados)
    {
        $return = false;
        $contador = null;

        $connection = \Application::getNewDataBaseInstance();
        $connection->autocommit(false);


        // bloqueia tabela geradora de OID para evitar concorrencia
        $result = $connection->query('LOCK TABLES geradoroid  WRITE');
        if ($result == false)
        {

            \Application::setMysqlLogQuery('Classe Contrato; Método inserir - bloquear tabela; Mysql '. $connection->error);
             $this->mysqlError = $connection->errno;
            return -3;
        }

        $connection->begin_transaction();



        // reserva o próximo numero de contrato
        $result = $connection->query("select contador from geradoroid where nome = 'numerocontrato'");
        if ($row=$result->fetch_array())
        {
              $contador = $row['contador'];
        }else
        {
              \Application::setMysqlLogQuery('Classe Contrato; Método inserir - reservar numero; Mysql '. $connection->error);
              $this->mysqlError = $connection->errno;
              return -1;
        }



        // INSERE OS DADOS DE CONTRATO

		// remove aspas do nome
		$dados['contrato']['cidade'] =  str_replace(array("'", '"') , array('',''), $dados['contrato']['cidade']   );
		$dados['contrato']['uf'] =  str_replace(array("'", '"') , array('',''), $dados['contrato']['uf']   );
        $dados['contrato']['rua'] =  str_replace(array("'", '"') , array('',''), $dados['contrato']['rua']   );
		$dados['contrato']['dataPagamento'] = ($dados['contrato']['dataPagamento'] == 'null') ? 'null' : "'". $dados['contrato']['dataPagamento'] . "'";
        $dados['contrato']['dataPagamentoBanco'] = ($dados['contrato']['dataPagamentoBanco'] == 'null') ? 'null' : "'". $dados['contrato']['dataPagamentoBanco'] . "'";

        ++$contador;

            $query = "
                    insert into contratos ( clientes_cpf ,usuarios_id ,subtabelas_id ,entidades_id, contabancariaclientes_id ,nome ,cep ,rua ,numero
                      ,complemento  ,bairro  ,uf  ,cidade  ,codigobancocliente  ,nomebancocliente  ,contabancocliente  ,agenciabancocliente
                      ,tipocontabancocliente  ,numerocontrato  ,created  ,codigobancoconvenio  ,nomebancocontrato  ,nomeconvenio
                      ,nomeoperacao  ,nometabela  ,comissaototal  ,valorseguro  ,percentualimposto  ,quantidadeparcelas  ,valorparcela
                      ,valortotal  ,valorliquido  ,percentualloja  ,valorloja, tipoconvenio_id, datapagamento, datapagamentobanco, usuariovinculado_id, observacao
                    ) VALUES (

                                '{$dados['contrato']['cpf']}',
                                {$dados['contrato']['idUsuario']},
                                {$dados['contrato']['idSubtabela']},
                                {$dados['contrato']['idEntidade']},
                                {$dados['contrato']['idContaBancariaCliente']},
                                '{$dados['contrato']['nomeCliente']}',
                                '{$dados['contrato']['cep']}',
                                '{$dados['contrato']['rua']}',
                                '{$dados['contrato']['numeroRua']}',
                                '{$dados['contrato']['complemento']}',
                                '{$dados['contrato']['bairro']}',
                                '{$dados['contrato']['uf']}',
                                '{$dados['contrato']['cidade']}',
                                '{$dados['contrato']['codigoBancoCliente']}',
                                '{$dados['contrato']['nomeBancoCliente']}',
                                '{$dados['contrato']['contaBancoCliente']}',
                                '{$dados['contrato']['agenciaBancoCliente']}',
                                '{$dados['contrato']['tipoContaBancoCliente']}',
                                {$contador} ,
                                (select now()),
                                '{$dados['contrato']['codigoBancoConvenio']}',
                                '{$dados['contrato']['nomeBancoContrato']}',
                                '{$dados['contrato']['nomeConvenio']}',
                                '{$dados['contrato']['nomeOperacao']}',
                                '{$dados['contrato']['nomeTabela']}',
                                {$dados['contrato']['comissaoTotal']},
                                {$dados['contrato']['valorSeguro']},
                                {$dados['contrato']['percentualImposto']},
                                {$dados['contrato']['quantidadeParcelas']},
                                {$dados['contrato']['valorParcela']},
                                {$dados['contrato']['valorTotal']},
                                {$dados['contrato']['valorLiquido']},
                                {$dados['contrato']['percentualLoja']},
                                {$dados['contrato']['valorLoja']},
                                '". $dados['contrato']['idTipoConvenio'] ."',
                                {$dados['contrato']['dataPagamento']},
                                {$dados['contrato']['dataPagamentoBanco']},
                                ". (($dados['contrato']['idUsuarioVinculado'] == null) ? 'null' : $dados['contrato']['idUsuarioVinculado']) . ",
                                ". (($dados['contrato']['observacao'] == null) ? 'null' : "'" . $dados['contrato']['observacao'] . "'") . "
                                )
            ";
       // echo $query; exit;
            $result = $connection->query($query);
            if ($result == false)
            {
                 \Application::setMysqlLogQuery('Classe Contrato; Método inserir - inserir contrato; Mysql '. $connection->error);
                 $this->mysqlError = $connection->errno;
            }else
               $return = $connection->insert_id;


            if ($return !== false)
            {
                $result = $connection->query("update geradoroid set contador = {$contador} where nome = 'numerocontrato'  ");
                 if ($result == false)
                 {
                      \Application::setMysqlLogQuery('Classe Contrato; Método inserir - atualizar numeração OID; Mysql '. $connection->error);
                        $this->mysqlError = $connection->errno;
                        return -2;
                 }

            }

         if ($return !== false)
         {

                if (is_array($dados['comissoes']))
                            foreach($dados['comissoes'] as $i => $value)
                            {
                                $idUsuario = $value['idUsuario'];
                                $nomeGrupo = $value['nomeGrupo'];
                                $percentualGrupo = $value['comissaoGrupo'];
                                $idGrupoPertence = $value['id'];
                                $valorGrupo = round($dados['contrato']['valorTotal'] * ($value['comissaoGrupo'] /100),2) ;
                                $percentualSupervisor = $value['comissaoSupervisor'];
                                $valorSupervisor = round($dados['contrato']['valorTotal'] * ($value['comissaoSupervisor'] /100),2);
                                $query = "insert into comissoescontrato ( contratos_id  ,usuarios_id, grupousuarios_id  ,nomegrupo  ,percentualgrupo   ,valorgrupo  ,percentualsupervisor  ,valorsupervisor
                    ) VALUES ({$return}, $idUsuario, $idGrupoPertence, '{$nomeGrupo}', {$percentualGrupo}, {$valorGrupo}, {$percentualSupervisor}, {$valorSupervisor})";
                             //   var_dump($query); exit;
                                if (!  $connection->query($query))
                                {
                                   // echo $query;
                                    \Application::setMysqlLogQuery('Classe Contrato; Método inserir - inserir comissões; Mysql '. $connection->error);
                                    $this->mysqlError = $connection->errno;
                                    $return = false;
                                    break;
                                }
                            }

                //$result = $connection->query($query);
         }


         // Remove bloqueio exclusivo
        $connection->query("unlock tables");


        if ($return === false)
            $connection->rollback();
        else
            $connection->commit();

        $connection->close();
        return $return;

    }


    public function atualizar($dados)
    {
        $return = false;

        $connection = \Application::getNewDataBaseInstance();
        $connection->autocommit(false);

		// remove acentos especiais
        $dados['contrato']['cidade'] =  str_replace(array("'", '"') , array('',''), $dados['contrato']['cidade']   );
		$dados['contrato']['uf'] =  str_replace(array("'", '"') , array('',''), $dados['contrato']['uf']   );
        $dados['contrato']['rua'] =  str_replace(array("'", '"') , array('',''), $dados['contrato']['rua']   );
	    $dados['contrato']['dataPagamento'] = ($dados['contrato']['dataPagamento'] == 'null') ? null : "'". $dados['contrato']['dataPagamento'] . "'";
        $dados['contrato']['dataPagamentoBanco'] = ($dados['contrato']['dataPagamentoBanco'] == 'null') ? 'null' : "'". $dados['contrato']['dataPagamentoBanco'] . "'";

        
        if (strtolower($dados['contrato']['status']) == 'pago ao cliente' ||  strtolower($dados['contrato']['status']) == 'recebido comissão do banco' )
        {

        $query = "
            update contratos set clientes_cpf = '{$dados['contrato']['cpf']}', subtabelas_id = {$dados['contrato']['idSubtabela']}, entidades_id = {$dados['contrato']['idEntidade']}, contabancariaclientes_id = {$dados['contrato']['idContaBancariaCliente']}, nome = '{$dados['contrato']['nomeCliente']}',
            cep = '{$dados['contrato']['cep']}', rua = '{$dados['contrato']['rua']}', numero = '{$dados['contrato']['numeroRua']}', complemento = '{$dados['contrato']['complemento']}', bairro = '{$dados['contrato']['bairro']}', uf = '{$dados['contrato']['uf']}', cidade = '{$dados['contrato']['cidade']}', codigobancocliente = '{$dados['contrato']['codigoBancoCliente']}', nomebancocliente = '{$dados['contrato']['nomeBancoCliente']}', contabancocliente = '{$dados['contrato']['contaBancoCliente']}',
            agenciabancocliente = '{$dados['contrato']['agenciaBancoCliente']}', tipocontabancocliente = '{$dados['contrato']['tipoContaBancoCliente']}',  codigobancoconvenio = '{$dados['contrato']['codigoBancoConvenio']}', nomebancocontrato = '{$dados['contrato']['nomeBancoContrato']}', nomeconvenio = '{$dados['contrato']['nomeConvenio']}',
            nomeoperacao = '{$dados['contrato']['nomeOperacao']}', nometabela = '{$dados['contrato']['nomeTabela']}', comissaototal = {$dados['contrato']['comissaoTotal']}, valorseguro = {$dados['contrato']['valorSeguro']}, percentualimposto = {$dados['contrato']['percentualImposto']}, quantidadeparcelas = {$dados['contrato']['quantidadeParcelas']}, valorparcela = {$dados['contrato']['valorParcela']},
            valortotal = {$dados['contrato']['valorTotal']}, valorliquido = {$dados['contrato']['valorLiquido']}, percentualloja = {$dados['contrato']['percentualLoja']}, valorloja = {$dados['contrato']['valorLoja']}, status = '{$dados['contrato']['status']}', tipoconvenio_id = '". $dados['contrato']['idTipoConvenio'] . "', datapagamento = case when datapagamento is null then ". ((! empty($dados['contrato']['dataPagamento'])) ? ($dados['contrato']['dataPagamento']) : '(select now())') . " else datapagamento end ,
            datapagamentobanco = {$dados['contrato']['dataPagamentoBanco']}, contratossubstatus_id = ". (($dados['contrato']['substatus'] != null) ? "'". $dados['contrato']['substatus']. "'" : 'null') . ", usuariovinculado_id = ". (($dados['contrato']['idUsuarioVinculado'] == null) ? 'null' : $dados['contrato']['idUsuarioVinculado']) . ", observacao = " . (($dados['contrato']['observacao'] == null) ? 'null' : "'" . $dados['contrato']['observacao'] . "'"  ) . "
            where id = {$dados['contrato']['id']}
        ";
        }else if (strtolower($dados['contrato']['status']) == 'pendente')
        {
            // Limpa a data de pagamento
            $query = "
            update contratos set clientes_cpf = '{$dados['contrato']['cpf']}', subtabelas_id = {$dados['contrato']['idSubtabela']}, entidades_id = {$dados['contrato']['idEntidade']}, contabancariaclientes_id = {$dados['contrato']['idContaBancariaCliente']}, nome = '{$dados['contrato']['nomeCliente']}',
            cep = '{$dados['contrato']['cep']}', rua = '{$dados['contrato']['rua']}', numero = '{$dados['contrato']['numeroRua']}', complemento = '{$dados['contrato']['complemento']}', bairro = '{$dados['contrato']['bairro']}', uf = '{$dados['contrato']['uf']}', cidade = '{$dados['contrato']['cidade']}', codigobancocliente = '{$dados['contrato']['codigoBancoCliente']}', nomebancocliente = '{$dados['contrato']['nomeBancoCliente']}', contabancocliente = '{$dados['contrato']['contaBancoCliente']}',
            agenciabancocliente = '{$dados['contrato']['agenciaBancoCliente']}', tipocontabancocliente = '{$dados['contrato']['tipoContaBancoCliente']}',  codigobancoconvenio = '{$dados['contrato']['codigoBancoConvenio']}', nomebancocontrato = '{$dados['contrato']['nomeBancoContrato']}', nomeconvenio = '{$dados['contrato']['nomeConvenio']}',
            nomeoperacao = '{$dados['contrato']['nomeOperacao']}', nometabela = '{$dados['contrato']['nomeTabela']}', comissaototal = {$dados['contrato']['comissaoTotal']}, valorseguro = {$dados['contrato']['valorSeguro']}, percentualimposto = {$dados['contrato']['percentualImposto']}, quantidadeparcelas = {$dados['contrato']['quantidadeParcelas']}, valorparcela = {$dados['contrato']['valorParcela']},
            valortotal = {$dados['contrato']['valorTotal']}, valorliquido = {$dados['contrato']['valorLiquido']}, percentualloja = {$dados['contrato']['percentualLoja']}, valorloja = {$dados['contrato']['valorLoja']}, status = '{$dados['contrato']['status']}', tipoconvenio_id = '". $dados['contrato']['idTipoConvenio'] . "', datapagamento = null,
            datapagamentobanco = {$dados['contrato']['dataPagamentoBanco']}, contratossubstatus_id = ". (($dados['contrato']['substatus'] != null) ? "'". $dados['contrato']['substatus']. "'" : 'null') . " , usuariovinculado_id = ". (($dados['contrato']['idUsuarioVinculado'] == null) ? 'null' : $dados['contrato']['idUsuarioVinculado']) . ", observacao = " . (($dados['contrato']['observacao'] == null) ? 'null' : "'" . $dados['contrato']['observacao'] . "'") . "
            where id = {$dados['contrato']['id']}
        ";
        }else

        {

            $query = "
            update contratos set clientes_cpf = '{$dados['contrato']['cpf']}', subtabelas_id = {$dados['contrato']['idSubtabela']}, entidades_id = {$dados['contrato']['idEntidade']}, contabancariaclientes_id = {$dados['contrato']['idContaBancariaCliente']}, nome = '{$dados['contrato']['nomeCliente']}',
            cep = '{$dados['contrato']['cep']}', rua = '{$dados['contrato']['rua']}', numero = '{$dados['contrato']['numeroRua']}', complemento = '{$dados['contrato']['complemento']}', bairro = '{$dados['contrato']['bairro']}', uf = '{$dados['contrato']['uf']}', cidade = '{$dados['contrato']['cidade']}', codigobancocliente = '{$dados['contrato']['codigoBancoCliente']}', nomebancocliente = '{$dados['contrato']['nomeBancoCliente']}', contabancocliente = '{$dados['contrato']['contaBancoCliente']}',
            agenciabancocliente = '{$dados['contrato']['agenciaBancoCliente']}', tipocontabancocliente = '{$dados['contrato']['tipoContaBancoCliente']}',  codigobancoconvenio = '{$dados['contrato']['codigoBancoConvenio']}', nomebancocontrato = '{$dados['contrato']['nomeBancoContrato']}', nomeconvenio = '{$dados['contrato']['nomeConvenio']}',
            nomeoperacao = '{$dados['contrato']['nomeOperacao']}', nometabela = '{$dados['contrato']['nomeTabela']}', comissaototal = {$dados['contrato']['comissaoTotal']}, valorseguro = {$dados['contrato']['valorSeguro']}, percentualimposto = {$dados['contrato']['percentualImposto']}, quantidadeparcelas = {$dados['contrato']['quantidadeParcelas']}, valorparcela = {$dados['contrato']['valorParcela']},
            valortotal = {$dados['contrato']['valorTotal']}, valorliquido = {$dados['contrato']['valorLiquido']}, percentualloja = {$dados['contrato']['percentualLoja']}, valorloja = {$dados['contrato']['valorLoja']}, status = '{$dados['contrato']['status']}', tipoconvenio_id = '". $dados['contrato']['idTipoConvenio'] . "',
            datapagamentobanco = {$dados['contrato']['dataPagamentoBanco']}, contratossubstatus_id = ". (($dados['contrato']['substatus'] != null) ? "'". $dados['contrato']['substatus']. "'" : 'null') . " , usuariovinculado_id = ". (($dados['contrato']['idUsuarioVinculado'] == null) ? 'null' : $dados['contrato']['idUsuarioVinculado']) . ", observacao = " . (($dados['contrato']['observacao'] == null) ? 'null' : "'" . $dados['contrato']['observacao'] . "'") . "
            where id = {$dados['contrato']['id']}
        ";

        }

      // var_dump($query); exit;

          //echo $query; exit;
            $result = $connection->query($query);
            if ($result == false)
            {
                 \Application::setMysqlLogQuery('Classe Contrato; Método atualizar - atualizar contrato; Mysql '. $connection->error);
                 $this->mysqlError = $connection->errno;
            }else
               $return = true;




         if ($return !== false)
         {

            // Deleta todos as comissões exceto a de quem criou o contrato
             $query = "delete from comissoescontrato where contratos_id = {$dados['contrato']['id']}";
             //var_dump($query); exit;
             if (!  $connection->query($query))
             {
               // echo $query;
                \Application::setMysqlLogQuery('Classe Contrato; Método atualizar - delete comissões; Mysql '. $connection->error);
                $this->mysqlError = $connection->errno;
                $return = false;
                break;
             }

             if (is_array($dados['comissoes']))
                    foreach($dados['comissoes'] as $i => $value)
                    {
                        $idUsuario = $value['idUsuario'];
                        $nomeGrupo = $value['nomeGrupo'];
                        $percentualGrupo = $value['comissaoGrupo'];
                        $idGrupoPertence = $value['id'];
                        $valorGrupo = round($dados['contrato']['valorTotal'] * ($value['comissaoGrupo'] /100), 2);
                        $percentualSupervisor = $value['comissaoSupervisor'];
                        $valorSupervisor = round($dados['contrato']['valorTotal'] * ($value['comissaoSupervisor'] /100), 2);
                        $query = "insert into comissoescontrato ( contratos_id  ,usuarios_id , grupousuarios_id ,nomegrupo  ,percentualgrupo   ,valorgrupo  ,percentualsupervisor  ,valorsupervisor
            ) VALUES ({$dados['contrato']['id']}, $idUsuario, $idGrupoPertence, '{$nomeGrupo}', {$percentualGrupo}, {$valorGrupo}, {$percentualSupervisor}, {$valorSupervisor})";
                        //var_dump($query); exit;
                        if (!  $connection->query($query))
                        {
                           // echo $query;
                            \Application::setMysqlLogQuery('Classe Contrato; Método atualizar - inserir comissões; Mysql '. $connection->error);
                            $this->mysqlError = $connection->errno;
                            $return = false;
                            break;
                        }
                    }

                //$result = $connection->query($query);
         }

				 // REALIZA A INCLUSAO DOS PONTOS DE TROCA
				 $idSubtabelaPontos = $dados['contrato']['idSubtabela'];
				 if (! empty($idSubtabelaPontos) && strtolower($dados['contrato']['status']) == 'pago ao cliente' )
				 {
					 $dtPagamento = ($dados['contrato']['dataPagamento'] != null) ? $dados['contrato']['dataPagamento'] : date('Y-m-d 00:00:00');
					 // seleciona o ID do Usuario
						 $query = "select usuarios_id from contratos where id = {$dados['contrato']['id']} ";
						 if ($stm =  $connection->query($query))
						 {
								if ($row = $stm->fetch_array())
									$idUsuario = $row[0];
								else
									$idUsuario = null;

						 }else
								\Application::setMysqlLogQuery('Classe Contrato; Método atualizar - Selecionar IdUsuario para PontoTroca; Mysql '. $connection->error);

						 // OBTEM DADOS DA SUBTABELA
						 $subTabela = new Subtabela();
						 $sub = $subTabela->listarSubtabelas($idSubtabelaPontos);
						 if (isset($sub[0]))
						 		$sub = $sub[0];
						 else
						 		$sub = null;
						 // VERIFICA SE JÁ EXISTEM PONTOS PARA O CONTRATO
						 $pontoTroca = new PontoTroca();
						 $listaPontosDoContrato = $pontoTroca->listar(array(
								 'limit' => 1000000,
								 'idContrato' => $dados['contrato']['id'],
								 'idUsuario' => $idUsuario
							 ));

							 if ($sub != null && $idUsuario != null && count($listaPontosDoContrato) == 0)
							 {
								 // manda inserir os pontos do usuario
								 $valorContrato = $dados['contrato']['valorTotal'];
								 $valorPonto = $sub['valorVendaGerarPonto'];
								 $totalPontosGerados = ($valorPonto > 0) ? intval(($valorContrato / $valorPonto)) : 0;
								 // multiplicador do ponto
								 $totalPontosGerados = $totalPontosGerados * $sub['quantidadePontosGerar'];
								 if ($totalPontosGerados > 0)
								 {
										 $resultPontuacao = $pontoTroca->salvar(array(
											 'idUsuario' => $idUsuario,
											 'idContrato' => $dados['contrato']['id'],
											 'created' => $dtPagamento,
											 'quantidadeDiasValidos' => $sub['quantidadeDiasExpirarPontos'],
											 'valorPonto' => $valorPonto,
											 'pontosObtidos' => $totalPontosGerados,
											 'pontosResgatados' => 0
										 ));
								 }
							 }
				 }




        if ($return === false)
            $connection->rollback();
        else
            $connection->commit();

        $connection->close();
        return $return;

    }

    public function excluir($id)
     {
        $return = false;

        $connection = \Application::getNewDataBaseInstance();

        $query = "delete from contratos where id = ?";

         if ($stm = $connection->prepare($query))
         {
                $stm->bind_param('i', $id);
                if ($stm->execute())
                {
                        $return = true;
                }
                 else
                 {
                     \Application::setMysqlLogQuery('Classe Contrato; Método excluir; Mysql '. $connection->error);
                        $this->errorCode = $connection->errno;
                 }
         }
         else
        {
            \Application::setMysqlLogQuery('Classe Contrato; Método excluir; Mysql '. $connection->error);
             $this->errorCode = $connection->errno;
        }
        return $return;

     }


}
