<?php
namespace Gauchacred\model;


/**
 * @author moises
 * @version 1.0
 * @created 10-nov-2015 16:24:32
 */

use Gauchacred\library\php\MySqlError as MySqlError;

class SenhaBanco implements MySqlError
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
    
    public function listarSenhasBancos($id = null, $nomeBanco = null,  $limit = 10, $orderColumn = 1, $orderType = 'asc', $idBanco = null, $promotora = null)
    {
        
        $id = ($id === null) ? '%' : $id;
        $nomeBanco = ($nomeBanco === null) ? '%' : '%'. $nomeBanco . '%';
        $idBanco = ($idBanco === null) ? '%' : $idBanco;
        $promotora = ($promotora === null) ? '%' : '%'. $promotora . '%';
        
        $return = false;
        
        $connection = \Application::getNewDataBaseInstance();
        $query = "
                select distinct
                    s.id, s.linkportal, s.loginmaster, s.senhamaster, s.emailmaster, s.observacao, s.created, s.modified,
                    b.id as 'idbanco', b.codigo, b.nome as 'nomebanco', b.status,
                    s.promotora as 'nomepromotora', i.itens

                    from senhasbanco s
                      left join bancos b on b.id = s.bancos_id
                      
                      left join (
                        select 
                        it.senhasbanco_id, group_concat(it.id, ',', it.login, ',', it.senha, ',', it.nome SEPARATOR ';') as 'itens'
                        FROM itenssenhasbanco it
                        group by it.senhasbanco_id
                      ) i on i.senhasbanco_id = s.id

                    where s.id like ? and (b.nome like ? or ? = '%') and (b.id like ? or ? = '%') and s.promotora like ?
                    order by ?  $orderType 
                    limit ?
        ";
       
        
         if ($stm = $connection->prepare($query))
        {
            $stm->bind_param('ssssssii',  $id, $nomeBanco, $nomeBanco, $idBanco, $idBanco, $promotora, $orderColumn, $limit);
            if ($stm->execute())
            {
                $stm->bind_result($id, $link, $loginMaster, $senhaMaster, $emailMaster, $observacao, $created, $modified, $idBanco, $codigoBanco, $nomeBanco, $statusBanco,  $nomePromotora, $itens);
                
                $return = array();
                 while ($stm->fetch()) {
                     $v = array();
                     $v['id'] = $id;
                     $v['link'] = $link;
                     $v['loginMaster'] = $loginMaster;
                     $v['senhaMaster'] = $senhaMaster;
                     $v['emailMaster'] = $emailMaster;
                     $v['observacao'] = $observacao;
                     $v['created'] = $created;
                     $v['modified'] = $modified;
                     $v['idBanco'] = $idBanco;
                     $v['codigoBanco'] = $codigoBanco;
                     $v['nomeBanco'] = $nomeBanco;
                     $v['statusBanco'] = $statusBanco;
                    
                     $v['nomePromotora'] = $nomePromotora;
                     $v['senhas'] = array();
                     
                     $itens = explode(';', $itens);
                     if (is_array($itens))
                         foreach($itens as $i => $value)
                         {
                             $item = explode(',', $value);
                             $s = array();
                             $s['id'] = (isset($item[0])) ? $item[0] : '';
                             $s['login'] = (isset($item[1])) ? $item[1] : '';
                             $s['senha'] = (isset($item[2])) ? $item[2] : '';
                             $s['nome'] = (isset($item[3])) ? $item[3] : '';
                             array_push($v['senhas'], $s); 
                         }
                     
                     
                     array_push($return, $v);
                 }
            }
            
        }
         else
        {
            \Application::setMysqlLogQuery('Classe SenhaBanco; Método listarPromotoras; Mysql '. $connection->error); 
             $this->mysqlError = $connection->errno;
        }
        return $return;
        
    }
    
    public function salvar( $dados, $id = null)
    {
         $return = false;

        $connection = \Application::getNewDataBaseInstance();
        $connection->autocommit(false);
        //novo registro
        if ($id === null)
            $query = "
                    insert into senhasbanco (bancos_id , promotora ,usuarios_id ,linkportal ,loginmaster ,senhamaster  ,emailmaster
  ,observacao  ,created ) VALUES (?,?,?,?,?,?,?,?,(select now()))
            ";
        else
            // atualiza registro
            $query = "update senhasbanco SET
                    bancos_id = ?   ,promotora = ?   ,linkportal = ?   ,loginmaster = ?
                      ,senhamaster = ?   ,emailmaster = ?   ,observacao = ? WHERE id = ?
                    ";
        
        
      
        
         if ($stm = $connection->prepare($query))
         {
            if ($id === null)
                $stm->bind_param('isisssss', $dados['banco'], $dados['promotora'], $_SESSION['userid'], $dados['link'], $dados['loginmaster'], $dados['senhamaster'], $dados['emailmaster'], $dados['observacao']);
             else
                 $stm->bind_param('issssssi',   $dados['banco'], $dados['promotora'],  $dados['link'], $dados['loginmaster'], $dados['senhamaster'], $dados['emailmaster'], $dados['observacao'], $id);
            if ($stm->execute())
            {
                if($id === null)
                    $return = $connection->insert_id;
                else
                    $return = $id;
            }
             else
             {
                 \Application::setMysqlLogQuery('Classe SenhaBanco; Método salvar; Mysql '. $connection->error); 
                    $this->errorCode = $connection->errno;
             }

         }
         else
        {
            \Application::setMysqlLogQuery('Classe SenhaBanco; Método salvar; Mysql '. $connection->error); 
             $this->errorCode = $connection->errno;
        }
        
        
        if ($return !== false)
        {
            $query = "delete from itenssenhasbanco where senhasbanco_id = ?";
            if ($stm = $connection->prepare($query))
            {
                    $stm->bind_param('i', $return);
                    if (! $stm->execute())
                    {
                         \Application::setMysqlLogQuery('Classe SenhaBanco; Método salvar - query delete; Mysql '. $connection->error); 
                            $this->errorCode = $connection->errno;
                            $return = false;
                    }
            }
            else
             {
                 \Application::setMysqlLogQuery('Classe SenhaBanco; Método salvar - query delete; Mysql '. $connection->error); 
                    $this->errorCode = $connection->errno;
                $return = false;
             }
            
        }
        
        if ($return !== false)
        {
            $query = "insert into itenssenhasbanco (senhasbanco_id ,login ,senha ,nome) VALUES (?, ?, ?, ?)";
            if ($stm = $connection->prepare($query))
            {                
                    $stm->bind_param('isss', $return, $login, $senha, $nome);
                    foreach($dados['senhas'] as $i => $value)
                    {
                        $login = $value['login'];
                        $senha = $value['senha'];
                        $nome = $value['nome'];
                        if (! $stm->execute())
                        {
                             \Application::setMysqlLogQuery('Classe SenhaBanco; Método salvar - query insert itenssenhasbanco - linha '. $i .' ; Mysql '. $connection->error); 
                                $this->errorCode = $connection->errno;
                                $return = false;
                                break;
                        }
                        
                    }
                
                    
            }
            else
             {
                 \Application::setMysqlLogQuery('Classe SenhaBanco; Método salvar - query insert itenssenhasbanco - linha '. $i .' ;  Mysql '. $connection->error); 
                    $this->errorCode = $connection->errno;
                $return = false;
             }
        }
        
        if ($return === false)
            $connection->rollback();
        else
            $connection->commit();
        
        return $return;
        
    }
    
    
    
    
    public function excluir($id)
     {
        $return = false;
        
        $connection = \Application::getNewDataBaseInstance();

        $query = "delete from senhasbanco where id = ?";

         if ($stm = $connection->prepare($query))
         {
                $stm->bind_param('i', $id);
                if ($stm->execute())
                {
                        $return = true;
                }
                 else
                 {
                     \Application::setMysqlLogQuery('Classe Promotora; Método excluir; Mysql '. $connection->error); 
                        $this->errorCode = $connection->errno;
                 }
         }
         else
        {
            \Application::setMysqlLogQuery('Classe Promotora; Método excluir; Mysql '. $connection->error); 
             $this->errorCode = $connection->errno;
        }
        return $return;
        
     }
    
    
    
    
    
    

}
?>