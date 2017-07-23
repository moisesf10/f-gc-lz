<?php
namespace Gauchacred\model;


/**
 * @author moises
 * @version 1.0
 * @created 10-nov-2015 16:24:32
 */

use Gauchacred\library\php\MySqlError as MySqlError;

class Entidade implements MySqlError
{

	private $errorCode = '';

    
    public function __construct()
    {
        
    }
    
    public function getMysqlError()
    {
        return $this->errorCode;
    }
    
    public function listarEntidades($id = '%')
    {
        
        
        $return = false;
        
        $connection = \Application::getNewDataBaseInstance();
        $query = "
                select distinct
                    e.id, e.nome, e.created
                    from entidades e
                    where e.id like ?
                order by e.nome
        ";
       
        
         if ($stm = $connection->prepare($query))
        {
            $stm->bind_param('s',  $id);
            if ($stm->execute())
            {
                $stm->bind_result($id, $nome, $created);
                
                $return = array();
                 while ($stm->fetch()) {
                     $v = array();
                     $v['id'] = $id;
                     $v['nome'] = $nome;
                     $v['created'] = $created;
                     array_push($return, $v);           
                 }
            }
            
        }
         else
        {
            \Application::setMysqlLogQuery('Classe Entidade; Método listarEntidades; Mysql '. $connection->error); 
             $this->errorCode = $connection->errno;
        }
        return $return;
        
    }
    
    
     public function salvar($nome, $id = null)
    {
         $return = false;

        $connection = \Application::getNewDataBaseInstance();
        //novo registro
        if ($id === null)
            $query = "
                    insert into entidades (nome, created) values (?, (select now()))
            ";
        else
            // atualiza registro
            $query = "update entidades set nome = ? where id = ?";
        
        
       
        
         if ($stm = $connection->prepare($query))
         {
            if ($id === null)
                $stm->bind_param('s',  $nome);
             else
                 $stm->bind_param('si', $nome, $id);
            if ($stm->execute())
            {
                if($id === null)
                    $return = $connection->insert_id;
                else
                    $return = $id;
            }
             else
             {
                 \Application::setMysqlLogQuery('Classe Entidadde; Método salvar; Mysql '. $connection->error); 
                    $this->errorCode = $connection->errno;
             }

         }
         else
        {
            \Application::setMysqlLogQuery('Classe Banco; Entidade salvar; Mysql '. $connection->error); 
             $this->errorCode = $connection->errno;
        }
        return $return;
        
    }
    
    
    
    
    public function excluir($id)
     {
        $return = false;
        
        $connection = \Application::getNewDataBaseInstance();

        $query = "delete from entidades where id = ?";

         if ($stm = $connection->prepare($query))
         {
                $stm->bind_param('i', $id);
                if ($stm->execute())
                {
                        $return = true;
                }
                 else
                 {
                     \Application::setMysqlLogQuery('Classe Entidade; Método excluir; Mysql '. $connection->error); 
                        $this->errorCode = $connection->errno;
                 }
         }
         else
        {
            \Application::setMysqlLogQuery('Classe Banco; Entidade excluir; Mysql '. $connection->error); 
             $this->errorCode = $connection->errno;
        }
        return $return;
        
     }
    
    
    
    
    
    

}
?>