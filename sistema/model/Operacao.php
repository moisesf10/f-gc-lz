<?php
namespace Gauchacred\model;


/**
 * @author moises
 * @version 1.0
 * @created 10-nov-2015 16:24:32
 */

use Gauchacred\library\php\MySqlError as MySqlError;
use Gauchacred\library\php\Utils as Utils;

class Operacao implements MySqlError
{

	private $errorCode = '';
    
    public function __construct()
    {
        
    }
    
    public function getMysqlError()
    {
        return $this->errorCode;
    }
    




    public function listarOperacoes($id = null, $nome = null,  $limit = 10)
    {
        
        $id = ($id === null) ? '%' : $id;
        $nome = ($nome === null) ? '%' : '%'. $nome .'%';
        $return = false;
        
        $connection = \Application::getNewDataBaseInstance();
        $query = "
                select distinct
                ost.id, ost.nome
                from operacoessubtabelas ost
                where ost.nome like ? and ost.id like ? 
                order by ost.nome
                limit ?
        ";
       
        
         if ($stm = $connection->prepare($query))
        {
            $stm->bind_param('ssi',  $nome, $id,  $limit);
            if ($stm->execute())
            {
                $stm->bind_result($id, $nome);
                
                $return = array();
                 while ($stm->fetch()) {
                     $v = array();
                     $v['id'] = $id;
                     $v['nome'] = $nome;
                     array_push($return, $v);           
                 }
            }
            
        }
         else
        {
            \Application::setMysqlLogQuery('Classe Operacao; Método listarOperacoes; Mysql '. $connection->error); 
             $this->errorCode = $connection->errno;
        }
        return $return;
        
    }
    
    
}