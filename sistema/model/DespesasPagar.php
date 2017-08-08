<?php
namespace Gauchacred\model;


/**
 * @author moises
 * @version 1.0
 * @created 10-nov-2015 16:24:32
 */

use Gauchacred\library\php\MySqlError as MySqlError;
use Gauchacred\library\php\Utils;

class DespesasPagar implements MySqlError
{

	private $errorCode = '';


    public function __construct()
    {

    }

    public function getMysqlError()
    {
        return $this->errorCode;
    }





    public function listarDespesas($params = array())
    {
        extract($params, EXTR_OVERWRITE);

        $id = (! empty($id)) ? $id : '%';
        $descricao = (! empty($descricao)) ? $descricao : '%';
        $datapagamentoinicio =  (! empty($datapagamentoinicio)) ? $datapagamentoinicio : '1';
        $datapagamentofim =  (! empty($datapagamentofim)) ? $datapagamentofim : '1';
        $datavencimentoinicio =  (! empty($datavencimentoinicio)) ? $datavencimentoinicio : '1';
        $datavencimentofim =  (! empty($datavencimentofim)) ? $datavencimentofim : '1';
        $datacriacaoinicio =  (! empty($datacriacaoinicio)) ? $datacriacaoinicio : '1';
        $datacriacaofim =  (! empty($datacriacaofim)) ? $datacriacaofim : '1';
        $limit =  (! empty($limit)) ? $limit : 10;
        $return = false;

        $connection = \Application::getNewDataBaseInstance();
        $query = "
                select d.id, d.valordevido, d.valorpago, d.vencimento, d.pagamento, d.descricao, d.created, d.modified 
                from despesasapagar d
                where d.descricao like ? 
                AND ((d.pagamento >= ? or ? = '1') and (d.pagamento <= ? or ? = '1')) 
                AND ((d.vencimento >= ? or ? = '1') and (d.vencimento <= ? or ? = '1'))  
                AND ((d.created >= ? or ? = '1') and (d.created <= ? or ? = '1'))
                and d.id like ?
                limit ?
        ";


         if ($stm = $connection->prepare($query))
        {
            $stm->bind_param('ssssssssssssssi', $descricao, $datapagamentoinicio, $datapagamentoinicio, $datapagamentofim, $datapagamentofim, $datavencimentoinicio, $datavencimentoinicio,
                             $datavencimentofim, $datavencimentofim, $datacriacaoinicio, $datacriacaoinicio, $datacriacaofim, $datacriacaofim, $id, $limit);
            if ($stm->execute())
            {
                $stm->bind_result($id, $valorDevido, $valorPago, $vencimento, $pagamento, $descricao, $created, $modified );

                $return = array();
                 while ($stm->fetch()) {
                     $v = array();
                     $v['id'] = $id;
                     $v['valorDevido'] = $valorDevido;
                     $v['valorPago'] = $valorPago;
                     $v['vencimento'] = Utils::formatStringDate($vencimento, 'Y-m-d', 'd/m/Y');
                     $v['pagamento'] = Utils::formatStringDate($pagamento, 'Y-m-d', 'd/m/Y');
                     $v['descricao'] = $descricao;
                     $v['created'] = Utils::formatStringDate($created, 'Y-m-d H:i:s', 'd/m/Y H:i:s');
                     $v['modified'] = Utils::formatStringDate($modified, 'Y-m-d H:i:s', 'd/m/Y H:i:s');
         


                     array_push($return, $v);
                 }
            }

        }
         else
        {
            \Application::setMysqlLogQuery('Classe DespesasPagar; Método listarDespesas Mysql '. $connection->error);
             $this->errorCode = $connection->errno;
        }

        return $return;

    }



   
     public function salvar($params = array())
    {
        
         extract($params, EXTR_OVERWRITE);

        $id = (! empty($id)) ? $id : null;
        $descricao = (! empty($descricao)) ? $descricao : null;
        $vencimento =  (! empty($vencimento)) ? $vencimento : null;
        $pagamento =  (! empty($pagamento)) ? $pagamento : null;
        $valordevido =  (isset($valordevido)  ) ? $valordevido : 0;
        $valorpago =  (isset($valorpago)  ) ? $valorpago : 0;
         
        if ($descricao == null)
            throw new \Exception('Descricao nao informada');
         
        if ($vencimento == null)
            throw new \Exception('data de vencimento invalida');
         
         $return = false;
        $connection = \Application::getNewDataBaseInstance();

		

        //novo registro
        if ($id == null)
            $query = "
            INSERT INTO despesasapagar(valordevido ,valorpago  ,vencimento  ,pagamento  ,descricao  ,created) VALUES (?,?,?,?,?, (select now()) )
            ";
        else
            // atualiza registro
            $query = "UPDATE despesasapagar SET valordevido = ?, valorpago = ?, vencimento = ?, pagamento = ?, descricao = ? WHERE id = ?";




         if ($stm = $connection->prepare($query))
         {
            if ($id === null)
                $stm->bind_param('ddsss', $valordevido, $valorpago, $vencimento, $pagamento, $descricao);
             else
                 $stm->bind_param('ddsssi', $valordevido, $valorpago, $vencimento, $pagamento, $descricao, $id );
            if ($stm->execute())
            {
                if($id === null)
                    $return = $connection->insert_id;
                else
                    $return = $id;
            }
             else
             {
                 \Application::setMysqlLogQuery('Classe DespesasPagar; Método salvar; Mysql '. $connection->error);
                    $this->errorCode = $connection->errno;
             }

         }
         else
        {
            \Application::setMysqlLogQuery('Classe DespesasPagar; Método salvar; Mysql '. $connection->error);
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

        $query = "delete from despesasapagar where id = ?";

         if ($stm = $connection->prepare($query))
         {
                $stm->bind_param('i', $id);
                if ($stm->execute())
                {
                        $return = true;
                }
                 else
                 {
                     \Application::setMysqlLogQuery('Classe DespesasPagar; Método excluir; Mysql '. $connection->error);
                        $this->errorCode = $connection->errno;
                 }
         }
         else
        {
            \Application::setMysqlLogQuery('Classe DespesasPagar; Método excluir; Mysql '. $connection->error);
             $this->errorCode = $connection->errno;
        }
        return $return;

     }



}
?>
