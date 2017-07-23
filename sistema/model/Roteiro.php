<?php
namespace Gauchacred\model;


/**
 * @author moises
 * @version 1.0
 * @created 10-nov-2015 16:24:32
 */

use Gauchacred\library\php\MySqlError as MySqlError;
use Gauchacred\library\php\Utils as Utils;

class Roteiro implements MySqlError
{

	private $errorCode = '';
    
    public function __construct()
    {
        
    }
    
    
    public function getMysqlError()
    {
        return $this->errorCode;
    }
    
    public function listarRoteiros($id = '%', $idBanco = '%', $idEntidade = '%', $limit = 10)
    {
        
        
        $return = false;
        
        $connection = \Application::getNewDataBaseInstance();
        $query = "
                select distinct 
                    r.id, r.descricao, r.created, r.modified,
                    b.id as 'idbanco', b.codigo, b.nome as 'nomebanco', b.status as 'statusbanco',
                    en.id as 'identidade', en.nome as 'nomeentidade'

                    from roteiros r 
                      inner join bancos b on b.id = r.bancos_id
                       inner join entidades en on en.id = r.entidades_id
                    where r.id like ? and b.id like ? and en.id like ?
                    limit ?
        ";
       
        
         if ($stm = $connection->prepare($query))
        {
            $stm->bind_param('sssi',  $id, $idBanco, $idEntidade, $limit);
            if ($stm->execute())
            {
                $stm->bind_result($id, $descricao,$created, $modified, $idBanco, $codigoBanco, $nomeBanco, $statusBanco, $idEntidade, $nomeEntidade);
                
                $return = array();
                 while ($stm->fetch()) {
                     $v = array();
                     $v['id'] = $id;
                     
                    
                     $v['descricao'] = $descricao;
                     $v['created'] =  Utils::formatStringDate($created, 'Y-m-d H:i:s', 'd/m/Y H:i:s');
                     $v['modified'] = $modified;
                     $v['idBanco'] = $idBanco;
                     $v['codigoBanco'] = $codigoBanco;
                     $v['nomeBanco'] = $nomeBanco;
                     $v['statusBanco'] = $statusBanco;
                      $v['idEntidade'] = $idEntidade;
                      $v['nomeEntidade'] = $nomeEntidade;
                     array_push($return, $v);           
                 }
            }
            
        }
         else
        {
            \Application::setMysqlLogQuery('Classe Roteiro; Método listarRoteiros; Mysql '. $connection->error); 
             $this->errorCode = $connection->errno;
        }
        return $return;
        
    }
    
    
    public function salvar($id=null, $banco, $entidade, $descricao)
    {
        
        
        $return = false;
        
        
        
        $connection = \Application::getNewDataBaseInstance();
        //novo registro
        if ($id === null)
            $query = "
                    insert into roteiros (bancos_id, entidades_id, descricao, created) values (?, ?, ?, (select now()))
            ";
        else
            // atualiza registro
            $query = "update roteiros set bancos_id = ?, entidades_id = ?, descricao = ? where id = ?";
        
        
       
        
         if ($stm = $connection->prepare($query))
         {
            if ($id === null)
                $stm->bind_param('iis',  $banco, $entidade, $descricao);
             else
                 $stm->bind_param('iisi', $banco, $entidade, $descricao, $id);
            if ($stm->execute())
            {
                if($id === null)
                    $return = $connection->insert_id;
                else
                    $return = $id;
            }
             else
             {
                 \Application::setMysqlLogQuery('Classe Roteiro; Método salvar; Mysql '. $connection->error); 
                    $this->errorCode = $connection->errno;
             }

         }
         else
        {
            \Application::setMysqlLogQuery('Classe Roteiro; Método salvar; Mysql '. $connection->error); 
             $this->errorCode = $connection->errno;
        }
        return $return;
        
    }
    
    
     public function excluir($id)
     {
        $return = false;
        
        $connection = \Application::getNewDataBaseInstance();

        $query = "delete from roteiros where id = ?";

         if ($stm = $connection->prepare($query))
         {
                $stm->bind_param('i', $id);
                if ($stm->execute())
                {
                        $return = true;
                }
                 else
                 {
                     \Application::setMysqlLogQuery('Classe Roteiro; Método excluir; Mysql '. $connection->error); 
                        $this->errorCode = $connection->errno;
                 }
         }
         else
        {
            \Application::setMysqlLogQuery('Classe Roteiro; Método excluir; Mysql '. $connection->error); 
             $this->errorCode = $connection->errno;
        }
        return $return;
        
     }
    
    

}
?>