<?php
namespace Gauchacred\model;


/**
 * @author moises
 * @version 1.0
 * @created 10-nov-2015 16:24:32
 */

use Gauchacred\library\php\MySqlError as MySqlError;
use Gauchacred\library\php\Utils;

class Adiantamento implements MySqlError
{

	private $errorCode = '';


    public function __construct()
    {

    }

    public function getMysqlError()
    {
        return $this->errorCode;
    }





    public function listarAdiantamentos($params = array())
    {
        extract($params, EXTR_OVERWRITE);

        $id = (! empty($id)) ? $id : '%';
        $idusuario = (! empty($idusuario)) ? $idusuario : '%';
        $datainicio =  (! empty($datainicio)) ? $datainicio : '1';
        $datafim =  (! empty($datafim)) ? $datafim : '1';
        $encerrado =  (isset($encerrado)  ) ? $encerrado : '%';
        $limit =  (! empty($limit)) ? $limit : 10;
        $return = false;

        $connection = \Application::getNewDataBaseInstance();
        $query = "
                SELECT a.id, a.usuarios_id, a.descontarpor, a.valor, a.qtdparccelas, a.valortotalpagar, a.valortotalpago, a.descricao, a.created, a.modified, a.encerrado, u.nome, ifnull(maxparcela.parcela,0)
                FROM adiantamentos a
                  inner join usuarios u on u.id = a.usuarios_id
                  left join (
                    select max(numparcela) as 'parcela', ad.adiantamentos_id
                    from adiantamentosdescontados ad
                  ) maxparcela on maxparcela.adiantamentos_id = a.id
                where a.usuarios_id like ? and (a.created >= ? or ? = '1') and  (a.created <= ? or ? = '1') and a.id like ? and a.encerrado like ? order by a.id desc limit ? ;
        ";


         if ($stm = $connection->prepare($query))
        {
            $stm->bind_param('sssssssi',  $idusuario, $datainicio, $datainicio, $datafim, $datafim, $id, $encerrado, $limit);
            if ($stm->execute())
            {
                $stm->bind_result($id, $idUsuario, $descontarPor, $valor, $qtdParcelas, $valorTotalPagar, $valorTotalPago, $descricao, $created, $modified, $encerrado, $nomeUsuario, $ultimaParcelaPaga);

                $return = array();
                 while ($stm->fetch()) {
                     $v = array();
                     $v['id'] = $id;
                     $v['idUsuario'] = $idUsuario;
                     $v['descontarPor'] = $descontarPor;
                     $v['valorParcela'] = $valor;
                     $v['qtdParcelas'] = $qtdParcelas;
                     $v['valorTotalPagar'] = $valorTotalPagar;
                     $v['valorTotalPago'] = $valorTotalPago;
                     $v['descricao'] = $descricao;
                     $v['created'] = Utils::formatStringDate($created, 'Y-m-d H:i:s', 'd/m/Y H:i:s');
                     $v['modified'] = Utils::formatStringDate($modified, 'Y-m-d H:i:s', 'd/m/Y H:i:s');
                     $v['encerrado'] = (boolean) $encerrado;
                     $v['nomeUsuario'] = $nomeUsuario;
                     $v['ultimaParcelaPaga'] = $ultimaParcelaPaga;


                     array_push($return, $v);
                 }
            }

        }
         else
        {
            \Application::setMysqlLogQuery('Classe Adiantamento; Método listarAdiantamentos Mysql '. $connection->error);
             $this->errorCode = $connection->errno;
        }

        return $return;

    }



    public function listarContratosNaoDescontados($params = array())
    {
        extract($params, EXTR_OVERWRITE);


        $idusuario = (! empty($idusuario)) ? $idusuario : '%';

        $limit =  (! empty($limit)) ? $limit : 10;

        $return = false;

        $connection = \Application::getNewDataBaseInstance();
        $query = "
                select distinct
                cc.percentualgrupo, cc.percentualsupervisor, c.percentualloja, c.id, c.datapagamento, c.datapagamentobanco, c.created,
                c.nome as 'nomecliente', c.nomebancocontrato as 'banco', c.nomeconvenio as 'convenio',
                c.nometabela as 'tabela', c.nomeoperacao as 'operacao', c.quantidadeparcelas as 'prazo', c.valorparcela, c.valortotal,
                round(sum(cc.valorgrupo) + sum(cc.valorsupervisor) ,2) as 'comissao',
                c.status, sut.descricao as 'substatus', u.nome as 'nomeusuario',
                round(sum(c.valorloja) ,2) as 'comissaoloja'

                from comissoescontrato cc
                  inner join contratos c on c.id = cc.contratos_id
                  left join contratossubstatus sut on sut.id = c.contratossubstatus_id
                  left join usuarios u on u.id = cc.usuarios_id

                where
                c.status = 'Pago ao Cliente'
                and not exists(select d.id from descontos_contratos d where d.usuarios_id = ? AND d.contratos_id = c.id )
                and cc.usuarios_id = ?
                group by cc.contratos_id, cc.usuarios_id
                order by id desc limit ?
        ";


         if ($stm = $connection->prepare($query))
        {
            $stm->bind_param('iii',  $idusuario, $idusuario,  $limit);
            if ($stm->execute())
            {
                $stm->bind_result($percGrupo, $percSupervisor, $percLoja, $id, $dataPagamento, $dataPagamentoBanco, $created, $nomeCliente, $banco, $convenio, $tabela, $operacao, $prazo,
                                 $valorParcela, $valorTotal, $comissao, $status, $substatus, $nomeUsuario, $comissaoLoja);

                $return = array();
                 while ($stm->fetch()) {
                     $v = array();
                     $v['percentualGrupo'] = $percGrupo;
                     $v['percentualSupervisor'] = $percSupervisor;
                     $v['percentualLoja'] = $percLoja;
                     $v['id'] = $id;
                     $v['dataPagamento'] = Utils::formatStringDate($dataPagamento, 'Y-m-d', 'd/m/Y');
                     $v['dataPagamentoBanco'] = Utils::formatStringDate($dataPagamentoBanco, 'Y-m-d', 'd/m/Y');
                     $v['created'] = Utils::formatStringDate($created, 'Y-m-d', 'd/m/Y');;
                     $v['nomeCliente'] = $nomeCliente;
                     $v['banco'] = $banco;
                     $v['convenio'] = $convenio;
                     $v['tabela'] = $tabela;
                     $v['operacao'] = $operacao;
                     $v['prazo'] = $prazo;
                     $v['valorParcela'] = $valorParcela;
                     $v['valorTotal'] = $valorTotal;
                     $v['comissao'] = $comissao;
                     $v['status'] = $status;
                     $v['substatus'] = $substatus;
                     $v['nomeUsuario'] = $nomeUsuario;
                     $v['comissaoLoja'] = $comissaoLoja;

                     array_push($return, $v);
                 }
            }

        }
         else
        {
            \Application::setMysqlLogQuery('Classe Adiantamento; Método listarContratosNaoDescontados Mysql '. $connection->error);
             $this->errorCode = $connection->errno;
        }

        return $return;

    }




     public function salvar($usuario, $descricao, $tipoPagamento, $valorParcela, $qtdParcelas, $valorPagar, $id = null)
    {
        $return = false;
        $connection = \Application::getNewDataBaseInstance();

				$qtdParcelas = ($qtdParcelas != null) ? $qtdParcelas : 0;

        //novo registro
        if ($id == null)
            $query = "
            INSERT INTO adiantamentos(usuarios_id ,descontarpor ,valor  ,qtdparccelas, valortotalpagar  ,descricao  ,created) VALUES (?,?,?,?,?,?, (select now()))
            ";
        else
            // atualiza registro
            $query = "UPDATE adiantamentos SET usuarios_id = ? ,descontarpor = ?, valor = ?  ,qtdparccelas = ?, descricao = ?,  valortotalpagar = ?  WHERE id = ?";




         if ($stm = $connection->prepare($query))
         {
            if ($id === null)
                $stm->bind_param('isdsds', $usuario, $tipoPagamento, $valorParcela, $qtdParcelas, $valorPagar, $descricao);
             else
                 $stm->bind_param('isdssdi', $usuario, $tipoPagamento, $valorParcela, $qtdParcelas,  $descricao,  $valorPagar, $id );
            if ($stm->execute())
            {
                if($id === null)
                    $return = $connection->insert_id;
                else
                    $return = $id;
            }
             else
             {
                 \Application::setMysqlLogQuery('Classe Adiantamento; Método salvar; Mysql '. $connection->error);
                    $this->errorCode = $connection->errno;
             }

         }
         else
        {
            \Application::setMysqlLogQuery('Classe Adiantamento; Método salvar; Mysql '. $connection->error);
             $this->errorCode = $connection->errno;
        }

        if ($return !== false)
            $connection->commit();
        else
            $connection->rollback();
        return $return;

    }


    public function excluir($id)
     {
        $return = false;

        $connection = \Application::getNewDataBaseInstance();

        $query = "delete from adiantamentos where id = ?";

         if ($stm = $connection->prepare($query))
         {
                $stm->bind_param('i', $id);
                if ($stm->execute())
                {
                        $return = true;
                }
                 else
                 {
                     \Application::setMysqlLogQuery('Classe Adiantamentos; Método excluir; Mysql '. $connection->error);
                        $this->errorCode = $connection->errno;
                 }
         }
         else
        {
            \Application::setMysqlLogQuery('Classe Adiantamentos; Método excluir; Mysql '. $connection->error);
             $this->errorCode = $connection->errno;
        }
        return $return;

     }



}
?>
