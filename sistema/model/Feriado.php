<?php
namespace Gauchacred\model;


/**
 * @author moises
 * @version 1.0
 * @created 10-nov-2015 16:24:32
 */

use Gauchacred\library\php\MySqlError as MySqlError;
use Gauchacred\library\php\Utils;

class Feriado implements MySqlError
{

	private $errorCode = '';

    
    public function __construct()
    {
        
    }
    
    public function getMysqlError()
    {
        return $this->errorCode;
    }
    
   
    
    
    
    public function listarFeriados($dataInicial = null, $dataFinal = null, $id = null, $limit = 10)
    {
        $dataInicial = ($dataInicial === null) ? '2011-01-01' : $dataInicial;
        $dataFinal = ($dataFinal === null) ? '2100-01-01' : $dataFinal;
        $id = ($id === null) ? '%' : $id;
        
        $return = false;
        
        $connection = \Application::getNewDataBaseInstance();
        $query = "
                SELECT id, data, descricao 
                FROM feriados
                where id like ?
                and data between ? and ADDDATE( ?, INTERVAL 1 DAY)
                order by id desc
        ";
       
        
         if ($stm = $connection->prepare($query))
        {
            $stm->bind_param('sss',  $id, $dataInicial, $dataFinal);
            if ($stm->execute())
            {
                $stm->bind_result($id, $data, $descricao);
                
                $return = array();
                 while ($stm->fetch()) {
                     $v = array();
                     $v['id'] = $id;
                     $v['data'] = Utils::formatStringDate($data, 'Y-m-d', 'd/m/Y');
                     $v['descricao'] = $descricao;
                    
                    
                     array_push($return, $v);           
                 }
            }
            
        }
         else
        {
            \Application::setMysqlLogQuery('Classe Perfil; Método listarFeriados; Mysql '. $connection->error); 
             $this->errorCode = $connection->errno;
        }
       
        return $return;
        
    }
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
     public function salvar($descricao, $data, $id = null)
    {
        $return = false;
        
        
        
        $connection = \Application::getNewDataBaseInstance();
        //novo registro
        if ($id === null)
            $query = "
                    INSERT INTO feriados (data, descricao) VALUES (?, ?);
            ";
        else
            // atualiza registro
            $query = "update feriados set data = ?, descricao = ? where id = ?";
        
        
       
        
         if ($stm = $connection->prepare($query))
         {
            if ($id === null)
                $stm->bind_param('ss', $data,  $descricao);
             else
                 $stm->bind_param('ssi', $data, $descricao, $id);
            if ($stm->execute())
            {
                if($id === null)
                    $return = $connection->insert_id;
                else
                    $return = $id;
            }
             else
             {
                 \Application::setMysqlLogQuery('Classe Feriado; Método salvar; Mysql '. $connection->error); 
                    $this->errorCode = $connection->errno;
             }

         }
         else
        {
            \Application::setMysqlLogQuery('Classe Feriado; Método salvar; Mysql '. $connection->error); 
             $this->errorCode = $connection->errno;
        }
        return $return;
        
    }
    
    
    public function excluir($id)
     {
        $return = false;
        
        $connection = \Application::getNewDataBaseInstance();

        $query = "delete from feriados where id = ?";

         if ($stm = $connection->prepare($query))
         {
                $stm->bind_param('i', $id);
                if ($stm->execute())
                {
                        $return = true;
                }
                 else
                 {
                     \Application::setMysqlLogQuery('Classe Feriado; Método excluir; Mysql '. $connection->error); 
                        $this->errorCode = $connection->errno;
                 }
         }
         else
        {
            \Application::setMysqlLogQuery('Classe Feriado; Método excluir; Mysql '. $connection->error); 
             $this->errorCode = $connection->errno;
        }
        return $return;
        
     }
    
    
    
    
    
    
     public function definirPerfilUsuario($id, $perfil)
    {
           
        if (! is_array($perfil))
            throw new \Exception('O parametro nao e um array');
         
        $return = true;
        
        $connection = \Application::getNewDataBaseInstance();
         $connection->autocommit(false);
         
         //Deleta os perfiz existentes para o usuário
         $query = "
               delete from perfilusuario where usuarios_id = ?;
        ";
       
        
         if ($stm = $connection->prepare($query))
         {
                
                               
                 $stm->bind_param('i',  $id );
                 if (! $stm->execute())
                 {
                    \Application::setMysqlLogQuery('Classe Perfil; Método definirPerfilUsuario - Delete; Mysql '. $connection->error); 
                    $this->errorCode = $connection->errno;
                    $return = false;
                    break;
                 }
             
             
        }
         else
        {
            \Application::setMysqlLogQuery('Classe Perfil; Método definirPerfilUsuario - Delete; Mysql '. $connection->error); 
             $this->errorCode = $connection->errno;
             $return = false;
        }
         
         
         
        $query = "
               insert into perfilusuario ( usuarios_id ,perfil_id) VALUES ( ?, ?)
        ";
       
        
         if ($stm = $connection->prepare($query))
        {
                
             foreach($perfil as $i => $value)
             {
                                 
                 $stm->bind_param('ii',  $id, $value   );
                    if (! $stm->execute())
                    {
                        \Application::setMysqlLogQuery('Classe Perfil; Método definirPerfilUsuario - linha '. $i+1 .' valor: '. $value .'; Mysql '. $connection->error); 
                        $this->errorCode = $connection->errno;
                        $return = false;
                        break;
                    }
             }
             
        }
         else
        {
            \Application::setMysqlLogQuery('Classe Perfil; Método definirPerfilUsuario - linha '. $i+1 .' valor: '. $value .'; Mysql '. $connection->error); 
             $this->errorCode = $connection->errno;
             $return = false;
        }
         
         if ($return)
             $connection->commit();
         else
             $connection->rollback();
        
        return $return;
    }
    
    
    
    

}
?>