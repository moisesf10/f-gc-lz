<?php
namespace Gauchacred\model;


/**
 * @author moises
 * @version 1.0
 * @created 10-nov-2015 16:24:32
 */

use Gauchacred\library\php\MySqlError as MySqlError;

class TelefoneUtil implements MySqlError
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
    
    public function listarTelefones($id = null, $nome = null, $idUsuario = null, $limit = 10)
    {
        
        $id = ($id === null) ? '%' : $id;
        $nome = ($nome === '%') ? $nome : '%'. $nome . '%';
        $idUsuario = ($idUsuario === null) ? '%' : $idUsuario;
        
        
        $return = false;
        
        $connection = \Application::getNewDataBaseInstance();
        $query = "
                select distinct
                    t.id, t.nome, t.email, t.contato, t.cep, t.rua, t.numero, 
                    t.complemento, t.bairro, t.uf, t.cidade, 
                    t.telefone1, t.telefone2, t.site, t.observacao, t.created, t.modified,
                    u.id as 'idusuario', u.nome as 'nomeusuario'

                    from telefonesuteis t
                      left join usuarios u on u.id = t.usuarios_id
                      
                    where (t.nome like ?   or (? = '%') ) and  t.id like ? and (t.usuarios_id like ? or ? = '%')
                    order by t.nome 
                    limit ?
        ";
       
        
         if ($stm = $connection->prepare($query))
        {
            $stm->bind_param('sssssi',  $nome, $nome, $id, $idUsuario, $idUsuario, $limit);
            if ($stm->execute())
            {
                $stm->bind_result($id, $nome, $email, $contato, $cep, $rua, $numero, $complemento, $bairro, $uf, $cidade, $telefone1, $telefone2, $site, $observacao, $created, $modified, $idUsuario, $nomeUsuario );
                
                $return = array();
                 while ($stm->fetch()) {
                     $v = array();
                     $v['id'] = $id;
                     $v['nome'] = $nome;
                     $v['email'] = $email;
                     $v['contato'] = $contato;
                     $v['cep'] = $cep;
                     $v['rua'] = $rua;
                     $v['numero'] = $numero;
                     $v['complemento'] = $complemento;
                     $v['bairro'] = $bairro;
                     $v['uf'] = $uf;
                     $v['cidade'] = $cidade;
                     $v['telefone1'] = $telefone1;
                     $v['telefone2'] = $telefone2;
                     $v['site'] = $site;
                     $v['observacao'] = $observacao;
                     $v['created'] = $created;
                     $v['modified'] = $modified;
                     $v['idUsuario'] = $idUsuario;
                     $v['nomeUsuario'] = $nomeUsuario;
                     array_push($return, $v);           
                 }
            }
            
        }
         else
        {
            \Application::setMysqlLogQuery('Classe TelefoneUtil; Método listarTelefones; Mysql '. $connection->error); 
             $this->mysqlError = $connection->errno;
        }
        return $return;
        
    }
    
    public function salvar($dados, $id = null)
    {
         $return = false;

        $connection = \Application::getNewDataBaseInstance();
        //novo registro
        if ($id === null)
            $query = "
                   insert into telefonesuteis (
                       usuarios_id  ,nome  ,email  ,contato  ,cep  ,rua  ,numero  ,complemento  ,bairro  ,uf  ,cidade  ,telefone1  ,telefone2  ,site  ,observacao  ,created )
                       VALUES (   ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, (select now())
                    )
            ";
        else
            // atualiza registro
            $query = "update telefonesuteis SET
                  nome = ?   ,email = ?   ,contato = ?   ,cep = ?  ,rua = ?   ,numero = ?   ,complemento = ?   ,bairro = ? 
                  ,uf = ?   ,cidade = ?   ,telefone1 = ?  ,telefone2 = ?  ,site = ?   ,observacao = ?  WHERE id = ?";
        
        
       
        
         if ($stm = $connection->prepare($query))
         {
            if ($id === null)
                $stm->bind_param('issssssssssssss', $_SESSION['userid'], $dados['nome'], $dados['email'], $dados['contato'], $dados['cep'], $dados['rua'], $dados['numero'], $dados['complemento'], $dados['bairro'], $dados['uf'], $dados['cidade'], $dados['telefone1'], $dados['telefone2'], $dados['site'], $dados['observacao'] );
             else
                 $stm->bind_param('ssssssssssssssi',  $dados['nome'], $dados['email'], $dados['contato'], $dados['cep'], $dados['rua'], $dados['numero'], $dados['complemento'], $dados['bairro'], $dados['uf'], $dados['cidade'], $dados['telefone1'], $dados['telefone2'], $dados['site'], $dados['observacao'], $id);
            if ($stm->execute())
            {
                if($id === null)
                    $return = $connection->insert_id;
                else
                    $return = $id;
            }
             else
             {
                 \Application::setMysqlLogQuery('Classe TelefoneUtil; Método salvar; Mysql '. $connection->error); 
                    $this->errorCode = $connection->errno;
             }

         }
         else
        {
            \Application::setMysqlLogQuery('Classe TelefoneUtil; Método salvar; Mysql '. $connection->error); 
             $this->errorCode = $connection->errno;
        }
        return $return;
        
    }
    
    
    
    
    public function excluir($id)
     {
        $return = false;
        
        $connection = \Application::getNewDataBaseInstance();

        $query = "delete from telefonesuteis where id = ?";

         if ($stm = $connection->prepare($query))
         {
                $stm->bind_param('i', $id);
                if ($stm->execute())
                {
                        $return = true;
                }
                 else
                 {
                     \Application::setMysqlLogQuery('Classe TelefoneUtil; Método excluir; Mysql '. $connection->error); 
                        $this->errorCode = $connection->errno;
                 }
         }
         else
        {
            \Application::setMysqlLogQuery('Classe TelefoneUtil; Método excluir; Mysql '. $connection->error); 
             $this->errorCode = $connection->errno;
        }
        return $return;
        
     }
    
    
    
    
    
    

}
?>