<?php
namespace Gauchacred\model;


/**
 * @author moises
 * @version 1.0
 * @created 10-nov-2015 16:24:32
 */

use Gauchacred\library\php\MySqlError as MySqlError;
use Gauchacred\library\php\Utils as Utils;

class SubstatusContrato implements MySqlError
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
    
    public function listarSubstatus($id = null)
    {
        
        $id = ($id === null) ? '%' : $id;
        
        $return = false;
        
        $connection = \Application::getNewDataBaseInstance();
        $query = "
               select distinct
                cs.id, cs.descricao, cs.created, cs.modified, cs.ativo
                from contratossubstatus cs
                where cs.id like ?
                order by cs.descricao
        ";
       
        
         if ($stm = $connection->prepare($query))
        {
            $stm->bind_param('s',  $id);
            if ($stm->execute())
            {
                $stm->bind_result($id, $descricao, $created, $modified, $status);
                
                $return = array();
                 while ($stm->fetch()) {
                     $v = array();
                     $v['id'] = $id;
                     $v['descricao'] = $descricao;
                     $v['created'] = Utils::formatStringDate($created, 'Y-m-d H:i:s', 'd/m/Y H:i:s');
                     $v['modified'] = Utils::formatStringDate($modified, 'Y-m-d H:i:s', 'd/m/Y H:i:s');
                     $v['status'] = $status == 1 ? true : false;
                     array_push($return, $v);           
                 }
            }
            
        }
         else
        {
            \Application::setMysqlLogQuery('Classe SubstatusContrato; Método listarSubstatus; Mysql '. $connection->error); 
             $this->mysqlError = $connection->errno;
        }
        return $return;
        
    }
    
    public function salvar($descricao,  $status, $id = null)
    {
         
        
        $return = false;

        $connection = \Application::getNewDataBaseInstance();
        //novo registro
        if ($id === null)
            $query = "
                    INSERT INTO contratossubstatus(descricao ,created ,ativo) VALUES (?, (select now()), ?)
            ";
        else
            // atualiza registro
            $query = "update contratossubstatus set descricao = ?, ativo = ? where id = ?";
        
        
       
        
         if ($stm = $connection->prepare($query))
         {
            if ($id === null)
                $stm->bind_param('si',  $descricao, $status);
             else
                 $stm->bind_param('sii', $descricao, $status, $id);
            if ($stm->execute())
            {
                if($id === null)
                    $return = $connection->insert_id;
                else
                    $return = $id;
            }
             else
             {
                 \Application::setMysqlLogQuery('Classe SubstatusContrato; Método salvar; Mysql '. $connection->error); 
                    $this->errorCode = $connection->errno;
             }

         }
         else
        {
            \Application::setMysqlLogQuery('Classe SubstatusContrato; Método salvar; Mysql '. $connection->error); 
             $this->errorCode = $connection->errno;
        }
        return $return;
        
    }
    
    
    
    
    public function excluir($id)
     {
        $return = false;
        
        $connection = \Application::getNewDataBaseInstance();

        $query = "delete from contratossubstatus where id = ?";

         if ($stm = $connection->prepare($query))
         {
                $stm->bind_param('i', $id);
                if ($stm->execute())
                {
                        $return = true;
                }
                 else
                 {
                     \Application::setMysqlLogQuery('Classe SubstatusContrato; Método excluir; Mysql '. $connection->error); 
                        $this->errorCode = $connection->errno;
                 }
         }
         else
        {
            \Application::setMysqlLogQuery('Classe SubstatusContrato; Método excluir; Mysql '. $connection->error); 
             $this->errorCode = $connection->errno;
        }
        return $return;
        
     }
    
    
    
    
    
    

}
?>