<?php
namespace Gauchacred\model;


/**
 * @author moises
 * @version 1.0
 * @created 10-nov-2015 16:24:32
 */

use Gauchacred\library\php\MySqlError as MySqlError;
use Gauchacred\library\php\Utils as Utils;

class GrupoUsuario implements MySqlError
{

	private $errorCode = '';

    
    public function __construct()
    {
        
    }
    
    public function getMysqlError()
    {
        return $this->errorCode;
    }
    
    public function listarGrupos($id = '%', $nome = '%', $limit = 10)
    {
        
        
        $return = false;
        
        $connection = \Application::getNewDataBaseInstance();
        $query = "
                select distinct
                g.id, g.nome, g.created
                from grupousuarios g
                where g.id like ? and g.nome like ?
                limit ?
        ";
       
        
         if ($stm = $connection->prepare($query))
        {
            $stm->bind_param('ssi',  $id, $nome, $limit);
            if ($stm->execute())
            {
                $stm->bind_result($id, $nome, $created);
                
                $return = array();
                 while ($stm->fetch()) {
                     $v = array();
                     $v['id'] = $id;
                     $v['nome'] = $nome;
                     $v['created'] = Utils::formatStringDate($created, 'Y-m-d H:i:s', 'd/m/Y H:i:s');
                     array_push($return, $v);           
                 }
            }
            
        }
         else
        {
            \Application::setMysqlLogQuery('Classe Grupos; Método listarGrupos; Mysql '. $connection->error); 
             $this->errorCode = $connection->errno;
        }
        return $return;
        
    }
    
     public function gruposDoUsuario($idUsuario)
    {
        
        
        $return = false;
        
        $connection = \Application::getNewDataBaseInstance();
        $query = "
               select distinct
                gu.id, gu.nome, gup.created
                from grupousuariopertence gup
                  inner join grupousuarios gu on gu.id = gup.grupousuarios_id
                where gup.usuarios_id = ?
                order by gup.created desc
        ";
       
        
         if ($stm = $connection->prepare($query))
        {
            $stm->bind_param('i', $idUsuario);
            if ($stm->execute())
            {
                $stm->bind_result($id, $nome, $created);
                
                $return = array();
                 while ($stm->fetch()) {
                     $v = array();
                     $v['id'] = $id;
                     $v['nome'] = $nome;
                     $v['created'] = Utils::formatStringDate($created, 'Y-m-d H:i:s', 'd/m/Y H:i:s');
                     array_push($return, $v);           
                 }
            }
            
        }
         else
        {
            \Application::setMysqlLogQuery('Classe Grupos; Método gruposDoUsuario; Mysql '. $connection->error); 
             $this->errorCode = $connection->errno;
        }
        return $return;
        
    }
    
    
    public function listarAtribuicoes($idGrupo)
    {
        
        
        $return = false;
        
        $connection = \Application::getNewDataBaseInstance();
        $query = "
                select distinct
                gu.id, gu.nome as 'nomegrupo', gu.created as 'createdgrupo' ,
                gp.id as 'idgrupopertence', gp.created as 'createdgrupopertence', gp.indica_supervisor, gp.comissao_supervisor,
                u.id as 'idusuario', u.cpf, u.nome as 'nomeusuario', u.email, u.datanascimento, u.status as 'statususuario'
                from grupousuarios gu
                  inner join grupousuariopertence gp on gp.grupousuarios_id = gu.id
                  inner join usuarios u on u.id = gp.usuarios_id

                where gu.id = ?
        ";
       
        
         if ($stm = $connection->prepare($query))
        {
            $stm->bind_param('i',  $idGrupo);
            if ($stm->execute())
            {
                $stm->bind_result($id, $nomeGrupo, $createdGrupo, $idGrupoPertence, $createdGrupoPertence, $indicaSupervisor, $comissaoSupervisor, $idUsuario,
                                    $cpf, $nomeUsuario, $email, $dataNascimento, $statusUsuario);
                
                $return = array();
                 while ($stm->fetch()) {
                     $v = array();
                     $v['id'] = $id;
                     $v['nomeGrupo'] = $nomeGrupo;
                     $v['createdGrupo'] = Utils::formatStringDate($createdGrupo, 'Y-m-d H:i:s', 'd/m/Y H:i:s');
                     $v['idGrupoPertence'] = $idGrupoPertence;
                     $v['createdGrupoPertence'] = Utils::formatStringDate($createdGrupoPertence, 'Y-m-d H:i:s', 'd/m/Y H:i:s');
                     $v['indicaSupervisor'] = (bool) $indicaSupervisor;
                     $v['comissaoSupervisor'] = $comissaoSupervisor;
              
                     $v['idUsuario'] = $idUsuario;
                     $v['cpf'] = $cpf;
                     $v['nomeUsuario'] = $nomeUsuario;
                     $v['email'] = $email;
                     $v['dataNascimento'] = Utils::formatStringDate($dataNascimento, 'Y-m-d H:i:s', 'd/m/Y H:i:s');
                     $v['statusUsuario'] = $statusUsuario;
                     array_push($return, $v);           
                 }
            }
            
        }
         else
        {
            \Application::setMysqlLogQuery('Classe Grupos; Método listarAtribuicoes; Mysql '. $connection->error); 
             $this->errorCode = $connection->errno;
        }
        return $return;
        
    }
    
    
    
    public function salvar($id=null,$nome)
    {
        
        
        $return = false;
        
        
        
        $connection = \Application::getNewDataBaseInstance();
        //novo registro
        if ($id === null)
            $query = "
                    insert into grupousuarios (nome, created) values (?, (select now()))
            ";
        else
            // atualiza registro
            $query = "update grupousuarios set nome = ? where id = ?";
        
        
       
        
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
                 \Application::setMysqlLogQuery('Classe GrupoUsuario; Método salvar; Mysql '. $connection->error); 
                    $this->errorCode = $connection->errno;
             }

         }
         else
        {
            \Application::setMysqlLogQuery('Classe GrupoUsuario; Método salvar; Mysql '. $connection->error); 
             $this->errorCode = $connection->errno;
        }
        return $return;
        
    }
    
    
    
     public function excluir($id)
     {
        $return = false;
        
        $connection = \Application::getNewDataBaseInstance();

        $query = "delete from grupousuarios where id = ?";

         if ($stm = $connection->prepare($query))
         {
                $stm->bind_param('i', $id);
                if ($stm->execute())
                {
                        $return = true;
                }
                 else
                 {
                     \Application::setMysqlLogQuery('Classe GrupoUsuarios; Método excluir; Mysql '. $connection->error); 
                        $this->errorCode = $connection->errno;
                 }
         }
         else
        {
            \Application::setMysqlLogQuery('Classe GrupoUsuarios; Método excluir; Mysql '. $connection->error); 
             $this->errorCode = $connection->errno;
        }
        return $return;
        
     }
    
    
    public function atribuirUsuarios($idGrupo, $autorizacoes)
    {
         
        if (! is_array($autorizacoes))
            throw new \Exception('O segundo parametro e invalido');
        
       
        //var_dump($autorizacoes); exit;
        $return = false;
        $connection = \Application::getNewDataBaseInstance();
         $connection->autocommit(false);
        $query = "delete from grupousuariopertence where grupousuarios_id = ?;";
        
             if ($stm = $connection->prepare($query))
             {
                    $stm->bind_param('i', $idGrupo);
                    if ($stm->execute())
                            $return = true;
                    
                     else
                     {
                         \Application::setMysqlLogQuery('Classe GrupoUsuarios; Método atribuirUsuarios - Delete; Mysql '. $connection->error); 
                            $this->errorCode = $connection->errno;
                     }
             }
             else
            {
                \Application::setMysqlLogQuery('Classe GrupoUsuarios; Método atribuirUsuarios - Delete; Mysql '. $connection->error); 
                 $this->errorCode = $connection->errno;
            }
        
        
            if ($return == true)
            {
                $query = "INSERT INTO grupousuariopertence ( grupousuarios_id, usuarios_id, created, indica_supervisor,comissao_supervisor) VALUES (?, ?, (select now()), ?, ?);";
                
                          if ($stm = $connection->prepare($query))
                         {
                                $stm->bind_param('iiid', $idGrupo, $idUsuario, $indicaSupervisor, $bonus);
                               foreach($autorizacoes as $i => $value)
                               {
                                   $idUsuario = $value['id'];
                                   $indicaSupervisor = (strtolower($value['tipo']) == 'supervisor') ? 1 : 0;
                                   $bonus = ($value['bonus'] == 0) ? 0.00 : $value['bonus'];
                                 
                                    if (! $stm->execute())
                                    {
                                            \Application::setMysqlLogQuery('Classe GrupoUsuarios; Método atribuirUsuarios - Insert - linha. ' . $i .' ; Mysql '. $connection->error); 
                                            $this->errorCode = $connection->errno;
                                        $return = false;
                                        break;
                                    }
                                   
                               }
                                   
                         }
                         else
                        {
                            \Application::setMysqlLogQuery('Classe GrupoUsuarios; Método atribuirUsuarios - Insert; Mysql '. $connection->error); 
                             $this->errorCode = $connection->errno;
                        }
            }
        
        
        if ($return == true)
            $connection->commit();
        else
            $connection->rollback();
        
        return $return;
        
    }
    

}
?>