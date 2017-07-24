<?php
namespace Gauchacred\model;


/**
 * @author moises
 * @version 1.0
 * @created 10-nov-2015 16:24:32
 */

use Gauchacred\library\php\MySqlError as MySqlError;

class Perfil implements MySqlError
{

	private $errorCode = '';

    
    public function __construct()
    {
        
    }
    
    public function getMysqlError()
    {
        return $this->errorCode;
    }
    
    public function listarAutorizacao($idPerfil)
    {
        
        
        $return = false;
        
        $connection = \Application::getNewDataBaseInstance();
        $query = "
                select distinct
                p.id as 'idperfil', p.descricao as 'descricaoperfil',
                a.id as 'idautorizacao', a.ler, a.escrever, a.remover, a.created,
                r.id as 'idrecurso', r.nome as 'nomerecurso', r.descricao as 'descricaorecurso', r.pagina, r.indicamenu, r.nomemenu, r.tagicon,
                gr.id as 'idgruporecurso', gr.descricao as 'descricaogruporecurso'
                from perfil p
                  inner join autorizacoes a on a.perfil_id = p.id
                  inner join recurso r on r.id = a.recursos_id
                  inner join gruporecurso gr on gr.id = r.gruporecurso_id

                where p.id = ?
                order by a.created
        ";
       
        
         if ($stm = $connection->prepare($query))
        {
            $stm->bind_param('i',  $idPerfil);
            if ($stm->execute())
            {
                $stm->bind_result($idPerfil, $descricaoPerfil, $idAutorizacao, $ler, $escrever, $remover, $created, $idRecurso, $nomeRecurso, $descricaoRecurso, $pagina, $indicaMenu, $nomeMenu,
                                            $tagIcon, $idGrupoRecurso, $descricaoGrupoRecurso);
                
                $return = array();
                 while ($stm->fetch()) {
                     $v = array();
                     $v['idPerfil'] = $idPerfil;
                     $v['descricaoPerfil'] = $descricaoPerfil;
                     $v['idAutorizacao'] = $idAutorizacao;
                     $v['ler'] = $ler;
                     $v['escrever'] = $escrever;
                     $v['remover'] = $remover;
                     $v['created'] = $created;
                     $v['idRecurso'] = $idRecurso;
                     $v['nomeRecurso'] = $nomeRecurso;
                     $v['descricaoRecurso'] = $descricaoRecurso;
                     $v['pagina'] = $pagina;
                     $v['indicaMenu'] = $indicaMenu;
                     $v['nomeMenu'] = $nomeMenu;
                     $v['tagIcon'] = $tagIcon;
                     $v['idGrupoRecurso'] = $idGrupoRecurso;
                     $v['descricaoGrupoRecurso'] = $descricaoGrupoRecurso;
                     array_push($return, $v);           
                 }
            }
            
        }
         else
        {
            \Application::setMysqlLogQuery('Classe Perfil; Método listarAutorizacao; Mysql '. $connection->error); 
             $this->errorCode = $connection->errno;
        }
        
        return $return;
        
    }
    
    
    
    public function listarPerfilUsuario($idUsuario = null)
    {
        
        $idUsuario = ($idUsuario === null) ? '%' : $idUsuario;
        $return = false;
        
        $connection = \Application::getNewDataBaseInstance();
        $query = "
                select distinct
                u.id as 'idusuario', u.cpf, u.nome, u.email, u.status,
                p.id as 'idperfil', p.descricao as 'descricaoperfil'

                from perfilusuario pu
                  inner join usuarios u on u.id = pu.usuarios_id
                  inner join perfil p on p.id = pu.perfil_id

                where pu.usuarios_id like ?
                order by p.descricao
        ";
       
        
         if ($stm = $connection->prepare($query))
        {
            $stm->bind_param('s',  $idUsuario);
            if ($stm->execute())
            {
                $stm->bind_result($idUsuario, $cpf, $nomeUsuario, $email, $status, $idPerfil, $descricaoPerfil);
                
                $return = array();
                 while ($stm->fetch()) {
                     $v = array();
                     $v['idUsuario'] = $idUsuario;
                     $v['cpf'] = $cpf;
                     $v['nomeUsuario'] = $nomeUsuario;
                     $v['email'] = $email;
                     $v['status'] = $status;
                     $v['idPerfil'] = $idPerfil;
                     $v['descricaoPerfil'] = $descricaoPerfil;
                    
                     array_push($return, $v);           
                 }
            }
            
        }
         else
        {
            \Application::setMysqlLogQuery('Classe Perfil; Método listarPerfilUsuario; Mysql '. $connection->error); 
             $this->errorCode = $connection->errno;
        }
        
        return $return;
        
    }
    
    
    public function get($id = '%')
    {
           
        $return = false;
        
        $connection = \Application::getNewDataBaseInstance();
        $query = "
                select distinct
                p.id, p.descricao
                from perfil p
                where p.id like ?
                order by p.id
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
            \Application::setMysqlLogQuery('Classe Perfil; Método get; Mysql '. $connection->error); 
             $this->errorCode = $connection->errno;
        }
        
        return $return;
    }
    
    
     public function autorizar($id, $autorizacoes)
    {
           
        $return = true;
        
        $connection = \Application::getNewDataBaseInstance();
         $connection->autocommit(false);
        $query = "
               insert into autorizacoes (perfil_id, recursos_id, ler, escrever, remover)
                values (?, ?, ?, ?, ?)
                ON DUPLICATE KEY UPDATE ler = ?, escrever = ?, remover = ?;
        ";
       
        
         if ($stm = $connection->prepare($query))
        {
                
             foreach($autorizacoes as $i => $value)
             {
                    
                 $ler = (int) $value['ler'];
                 $escrever = (int) $value['escrever'];
                 $remover = (int) $value['remover'];
                 
                 $stm->bind_param('iiiiiiii',  $id, $value['recurso'], $ler, $escrever, $remover, $ler, $escrever, $remover   );
                    if (! $stm->execute())
                    {
                        \Application::setMysqlLogQuery('Classe Perfil; Método autorizar - linha '. $i+1 .'; Mysql '. $connection->error); 
                        $this->errorCode = $connection->errno;
                        $return = false;
                        break;
                    }
             }
             
        }
         else
        {
            \Application::setMysqlLogQuery('Classe Perfil; Método autorizar; Mysql '. $connection->error); 
             $this->errorCode = $connection->errno;
             $return = false;
        }
         
         if ($return)
             $connection->commit();
         else
             $connection->rollback();
        
        return $return;
    }
    
    
     public function salvar($descricao, $id = null)
    {
        $return = false;
        
        
        
        $connection = \Application::getNewDataBaseInstance();
        //novo registro
        if ($id === null)
            $query = "
                    INSERT INTO perfil (descricao) VALUES (?);
            ";
        else
            // atualiza registro
            $query = "update perfil set descricao = ? where id = ?";
        
        
       
        
         if ($stm = $connection->prepare($query))
         {
            if ($id === null)
                $stm->bind_param('s',  $descricao);
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
                 \Application::setMysqlLogQuery('Classe Perfil; Método salvar; Mysql '. $connection->error); 
                    $this->errorCode = $connection->errno;
             }

         }
         else
        {
            \Application::setMysqlLogQuery('Classe Perfil; Método salvar; Mysql '. $connection->error); 
             $this->errorCode = $connection->errno;
        }
        return $return;
        
    }
    
    
    public function excluir($id)
     {
        $return = false;
        
        $connection = \Application::getNewDataBaseInstance();

        $query = "delete from perfil where id = ?";

         if ($stm = $connection->prepare($query))
         {
                $stm->bind_param('i', $id);
                if ($stm->execute())
                {
                        $return = true;
                }
                 else
                 {
                     \Application::setMysqlLogQuery('Classe Perfil; Método excluir; Mysql '. $connection->error); 
                        $this->errorCode = $connection->errno;
                 }
         }
         else
        {
            \Application::setMysqlLogQuery('Classe Perfil; Método excluir; Mysql '. $connection->error); 
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