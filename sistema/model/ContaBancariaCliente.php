<?php
namespace Gauchacred\model;


/**
 * @author moises
 * @version 1.0
 * @created 14-ago-2016 16:24:32
 */

use Gauchacred\library\php\MySqlError as MySqlError;

class ContaBancariaCliente implements MySqlError
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
    
    public function listarTipos($id = '%')
    {
        
        
        $return = false;
        
        $connection = \Application::getNewDataBaseInstance();
        $query = "
                select distinct
                t.id, t.descricao
                from tipocontabancaria t
                where t.id like ?
                order by t.descricao
        ";
       
        
         if ($stm = $connection->prepare($query))
        {
            $stm->bind_param('s',  $id);
            if ($stm->execute())
            {
                $stm->bind_result($id, $descricao);
                
                $return = array();
                 while ($stm->fetch()) {
                     $v = array();
                     $v['id'] = $id;
                     $v['descricao'] = $descricao;
                     
                     array_push($return, $v);           
                 }
            }
            
        }
         else
        {
            \Application::setMysqlLogQuery('Classe TipoContaBancaria; Método listarTipos; Mysql '. $connection->error); 
             $this->mysqlError = $connection->errno;
        }
        return $return;
        
    }
    
    
    public function salvarTipos($descricao,  $id = null)
    {
         $return = false;

        $connection = \Application::getNewDataBaseInstance();
        //novo registro
        if ($id === null)
            $query = "
                    insert into tipocontabancaria (descricao) values (?)
            ";
        else
            // atualiza registro
            $query = "update tipocontabancaria set descricao = ? where id = ?";
        
        
       
        
         if ($stm = $connection->prepare($query))
         {
            if ($id === null)
                $stm->bind_param('s', $descricao);
             else
                 $stm->bind_param('si', $descricao, $id);
            if ($stm->execute())
            {
                if($id === null)
                    $return = $connection->insert_id;
                else
                    $return = $id;
            }
             else
             {
                 \Application::setMysqlLogQuery('Classe TipoContaBancaria; Método salvar; Mysql '. $connection->error); 
                    $this->errorCode = $connection->errno;
             }

         }
         else
        {
            \Application::setMysqlLogQuery('Classe TipoContaBancaria; Método salvar; Mysql '. $connection->error); 
             $this->errorCode = $connection->errno;
        }
        return $return;
        
    }
    
    
     public function salvarContaCliente($cpf, $idBanco, $idTipoConta, $agencia, $conta,  $id = null)
    {
         $return = false;

        $connection = \Application::getNewDataBaseInstance();
        //novo registro
        if ($id === null)
            $query = "
                    insert into contabancariaclientes (clientes_cpf_cnpj, bancos_id, tipocontabancaria_id, agencia, conta) values (?,?,?,?,?)
            ";
        else
            // atualiza registro
            $query = "update tipocontabancaria set clientes_cpf_cnpj = ?  , bancos_id = ?,   tipocontabancaria_id = ?,     agencia = ?  , conta = ?     where id = ?";
        
        
       
        
         if ($stm = $connection->prepare($query))
         {
            if ($id === null)
                $stm->bind_param('siiss', $cpf, $idBanco, $idTipoConta, $agencia, $conta);
             else
                 $stm->bind_param('siissi', $cpf, $idBanco, $idTipoConta, $agencia, $conta, $id);
            if ($stm->execute())
            {
                if($id === null)
                    $return = $connection->insert_id;
                else
                    $return = $id;
            }
             else
             {
                 \Application::setMysqlLogQuery('Classe TipoContaBancaria; Método salvarContaCliente; Mysql '. $connection->error); 
                    $this->errorCode = $connection->errno;
             }

         }
         else
        {
            \Application::setMysqlLogQuery('Classe TipoContaBancaria; Método salvarContaCliente; Mysql '. $connection->error); 
             $this->errorCode = $connection->errno;
        }
        return $return;
        
    }
    
   
    
    
    

}
?>