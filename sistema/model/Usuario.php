<?php
namespace Gauchacred\model;


/**
 * @author moises
 * @version 1.0
 * @created 10-nov-2015 16:24:32
 */

use Gauchacred\library\php\MySqlError as MySqlError;
use Gauchacred\library\php\Utils as Utils;

class Usuario implements MySqlError
{

	private $errorCode = '';
    
    public function __construct()
    {
        
    }
    
    public function getMysqlError()
    {
        return $this->errorCode;
    }
    
    public function autenticar($cpf = null, $email = null)
    {
        
        
        $cpf = ($cpf === null) ? '%' : $cpf;
        $email = ($email === null) ? '%' : $email;
        
        if (($cpf === null && $email === null) || ($cpf === '%' && $email === '%') )
            return false;
        
        
        $return = false;
        
        $connection = \Application::getNewDataBaseInstance();
        $query = "
                select 
                    u.id, u.cpf, u.nome, u.email, u.senha, u.datanascimento, u.status
                    from usuarios u 
                where u.cpf like ? and u.email like ? and status = 1
                limit 1
        ";
       
        
         if ($stm = $connection->prepare($query))
        {
            $stm->bind_param('ss',  $cpf, $email);
            if ($stm->execute())
            {
                $stm->bind_result($id, $cpf, $nome, $email, $senha, $dataNascimento, $status);
               
                 if ($stm->fetch()) {
                     $return['id'] = $id;
                     $return['cpf'] = $cpf;
                     $return['nome'] = $nome;
                     $return['email'] = $email;
                     $return['senha'] = $senha;
                     $return['dataNascimento'] = $dataNascimento;
                     $return['status'] = $status;
                                     
                 }
            }
            
        }
         else
        {
            \Application::setMysqlLogQuery('Classe Usuario; Método autenticar; Mysql '. $connection->error); 
             $this->mysqlError = $connection->errno;
        }
        
        return $return;
        
    }
    
    
    public function aniversariantes($dia, $mes)
    {

        
        $return = false;
        
        $connection = \Application::getNewDataBaseInstance();
        $query = "
                select distinct
                u.id, u.cpf, u.nome, u.email, u.senha, u.datanascimento, u.status,
                u.telefone, u.celular, u.cep, u.rua, u.numeroresidencia, u.complemento, u.bairro, u.cidade, u.uf, u.agencia, u.numerocontabancaria,
                b.id as 'idbanco', b.codigo as 'codigobanco', b.nome as 'nomebanco', b.status as 'statusbanco', tc.id as 'idtipoconta', tc.descricao as 'descricaotipoconta'
                from usuarios u
                  left join bancos b on b.id = u.bancos_id
                  left join tipocontabancaria tc on tc.id = u.tipocontabancaria_id
                where day(u.datanascimento) = ? and month(u.datanascimento) = ?
                order by u.nome;
        ";
       
        
         if ($stm = $connection->prepare($query))
        {
            $stm->bind_param('ii', $dia, $mes);
            if ($stm->execute())
            {
                $stm->bind_result($id, $cpf, $nome, $email, $senha, $dataNascimento, $status, $telefone, $celular, $cep, $rua, $numeroResidencia, $complemento, $bairro, $cidade,   $uf, $agencia,$numeroConta, $idBanco, $codigoBanco, $nomeBanco, $statusBanco, $idTipoConta, $descricaoTipoConta);
                
                $return = array();
                 while ($stm->fetch()) {
                     $v = array();
                     $v['id'] = $id;
                     $v['cpf'] = $cpf;
                     $v['nome'] = $nome;
                     $v['email'] = $email;
                     $v['senha'] = $senha;
                     $v['dataNascimenaniversariantesto'] = Utils::formatStringDate($dataNascimento, 'Y-m-d', 'd/m/Y');
                     $v['status'] = $status;
                     $v['telefone'] = $telefone;
                     $v['celular'] = $celular;
                     $v['cep'] = $cep;
                     $v['rua'] = $rua;
                     $v['numeroResidencia'] = $numeroResidencia;
                     $v['uf'] = $uf;
                     $v['complemento'] = $complemento;
                     $v['bairro'] = $bairro;
                     $v['cidade'] = $cidade;
                     $v['idTipoContaBancaria'] = $idTipoConta;
                     $v['descricaoTipoContaBancaria'] = $descricaoTipoConta;
                     $v['idBanco'] = $idBanco;
                     $v['codigoBanco'] = $codigoBanco;
                     $v['nomeBanco'] = $nomeBanco;
                     $v['statusBanco'] = $statusBanco;
                     $v['agencia'] = $agencia;
                     $v['numeroConta'] = $numeroConta;
                    
                     array_push($return, $v);           
                 }
            }
            
        }
         else
        {
            \Application::setMysqlLogQuery('Classe Usuario; Método aniversariantes; Mysql '. $connection->error); 
             $this->errorCode = $connection->errno;
        }
        return $return;
        
    }
    
    
    
    public function listarUsuarios($id = null, $cpf = null, $nome = null, $dataNascimentoInicial = null, $dataNascimentoFinal = null)
    {

        $id = ($id === null) ? '%' : $id;
        $cpf = ($cpf === null) ? '%' : $cpf;
        $nome = ($nome === null) ? '%' : '%'. $nome . '%';
        $dataNascimentoInicial = ($dataNascimentoInicial === null) ? '0001-01-01' : $dataNascimentoInicial;
        $dataNascimentoFinal = ($dataNascimentoFinal === null) ? '2100-01-01' : $dataNascimentoFinal;
        
        $return = false;
        
        $connection = \Application::getNewDataBaseInstance();
        $query = "
                select distinct
                u.id, u.cpf, u.nome, u.email, u.senha, u.datanascimento, u.status,
                u.telefone, u.celular, u.cep, u.rua, u.numeroresidencia, u.complemento, u.bairro, u.cidade, u.uf, u.agencia, u.numerocontabancaria,
                b.id as 'idbanco', b.codigo as 'codigobanco', b.nome as 'nomebanco', b.status as 'statusbanco', tc.id as 'idtipoconta', tc.descricao as 'descricaotipoconta'
                from usuarios u
                  left join bancos b on b.id = u.bancos_id
                  left join tipocontabancaria tc on tc.id = u.tipocontabancaria_id
                where u.id like ? and u.cpf like ? and u.nome like ?
                and ( u.datanascimento between ? and ? or (? = '0001-01-01' and ? = '2100-01-01')  )
                order by u.nome;
        ";
       
        
         if ($stm = $connection->prepare($query))
        {
            $stm->bind_param('sssssss',  $id, $cpf, $nome, $dataNascimentoInicial, $dataNascimentoFinal, $dataNascimentoInicial, $dataNascimentoFinal);
            if ($stm->execute())
            {
                $stm->bind_result($id, $cpf, $nome, $email, $senha, $dataNascimento, $status, $telefone, $celular, $cep, $rua, $numeroResidencia, $complemento, $bairro, $cidade,   $uf, $agencia,$numeroConta, $idBanco, $codigoBanco, $nomeBanco, $statusBanco, $idTipoConta, $descricaoTipoConta);
                
                $return = array();
                 while ($stm->fetch()) {
                     $v = array();
                     $v['id'] = $id;
                     $v['cpf'] = $cpf;
                     $v['nome'] = $nome;
                     $v['email'] = $email;
                     $v['senha'] = $senha;
                     $v['dataNascimento'] = Utils::formatStringDate($dataNascimento, 'Y-m-d', 'd/m/Y');
                     $v['status'] = $status;
                     $v['telefone'] = $telefone;
                     $v['celular'] = $celular;
                     $v['cep'] = $cep;
                     $v['rua'] = $rua;
                     $v['numeroResidencia'] = $numeroResidencia;
                     $v['uf'] = $uf;
                     $v['complemento'] = $complemento;
                     $v['bairro'] = $bairro;
                     $v['cidade'] = $cidade;
                     $v['idTipoContaBancaria'] = $idTipoConta;
                     $v['descricaoTipoContaBancaria'] = $descricaoTipoConta;
                     $v['idBanco'] = $idBanco;
                     $v['codigoBanco'] = $codigoBanco;
                     $v['nomeBanco'] = $nomeBanco;
                     $v['statusBanco'] = $statusBanco;
                     $v['agencia'] = $agencia;
                     $v['numeroConta'] = $numeroConta;
                    
                     array_push($return, $v);           
                 }
            }
            
        }
         else
        {
            \Application::setMysqlLogQuery('Classe Usuario; Método listarUsuarios; Mysql '. $connection->error); 
             $this->errorCode = $connection->errno;
        }
        return $return;
        
    }
    
    public function salvar($cpf, $nome, $email, $senha, $nascimento, $status, $telefone, $celular, $cep, $rua, $numeroResidencia, $uf, $complemento, $bairro, $cidade, $tipoConta, $banco, $agencia, $numeroConta, $id = null)
    {
        
        
        $return = false;

        $connection = \Application::getNewDataBaseInstance();
        //novo registro
        if ($id === null)
            $query = "
                   insert into usuarios ( cpf,nome ,email ,senha ,datanascimento ,status, telefone, celular, cep, rua, numeroresidencia, uf, complemento, bairro, cidade, tipocontabancaria_id, bancos_id, agencia, numerocontabancaria) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
            ";
        else
            if ($senha == '')
                // atualiza sem alterar a senha
                $query = "update usuarios SET cpf =?  ,nome = ?  ,email = ?  ,datanascimento =?  ,status = ?, telefone = ?, celular = ?, cep = ?, rua = ?, numeroresidencia = ?, uf = ?, complemento = ? , bairro = ?, cidade = ?, tipocontabancaria_id = ?, bancos_id = ?, agencia = ?, numerocontabancaria = ? WHERE id = ?";
            else
            // atualiza alterando a senha
            $query = "update usuarios SET cpf =?  ,nome = ?  ,email = ?  ,senha = ?  ,datanascimento =?  ,status = ?, telefone = ?, celular = ?, cep = ?, rua = ?, numeroresidencia = ?, uf = ?, complemento = ? , bairro = ?, cidade = ?, tipocontabancaria_id = ?, bancos_id = ?, agencia = ?, numerocontabancaria = ? WHERE id = ?";
        
        
       
        
         if ($stm = $connection->prepare($query))
         {
            if ($id === null)
                $stm->bind_param('sssssisssssssssiiss',  $cpf, $nome, $email, $senha, $nascimento, $status, $telefone, $celular, $cep, $rua, $numeroResidencia, $uf, $complemento, $bairro, $cidade, $tipoConta, $banco, $agencia, $numeroConta);
             else
                 if ($senha == '')
                     // sem atualizar senha
                    $stm->bind_param('ssssisssssssssiissi', $cpf, $nome, $email, $nascimento, $status, $telefone, $celular, $cep, $rua, $numeroResidencia, $uf, $complemento, $bairro, $cidade, $tipoConta, $banco, $agencia, $numeroConta, $id);
                else
                    // atualiza a senha
                    $stm->bind_param('sssssisssssssssiissi', $cpf, $nome, $email, $senha, $nascimento, $status, $telefone, $celular, $cep, $rua, $numeroResidencia, $uf, $complemento, $bairro, $cidade, $tipoConta, $banco, $agencia, $numeroConta, $id);
            if ($stm->execute())
            {
                if($id === null)
                    $return = $connection->insert_id;
                else
                    $return = $id;
            }
             else
             {
                 \Application::setMysqlLogQuery('Classe Usuario; Método salvar; Mysql '. $connection->error); 
                    $this->errorCode = $connection->errno;
             }

         }
         else
        {
            \Application::setMysqlLogQuery('Classe Usuario; Método salvar; Mysql '. $connection->error); 
             $this->errorCode = $connection->errno;
        }
        return $return;
        
    }
    
    
    
     public function excluir($id)
     {
        $return = false;
        
        $connection = \Application::getNewDataBaseInstance();

        $query = "delete from usuarios where id = ?";

         if ($stm = $connection->prepare($query))
         {
                $stm->bind_param('i', $id);
                if ($stm->execute())
                {
                        $return = true;
                }
                 else
                 {
                     \Application::setMysqlLogQuery('Classe Usuario; Método excluir; Mysql '. $connection->error); 
                        $this->errorCode = $connection->errno;
                 }
         }
         else
        {
            \Application::setMysqlLogQuery('Classe Usuario; Método excluir; Mysql '. $connection->error); 
             $this->errorCode = $connection->errno;
        }
        return $return;
        
     }
    
    
     public function desativar($id)
    {
        
        
        $return = false;

        $connection = \Application::getNewDataBaseInstance();
      
            // atualiza registro
            $query = "update usuarios SET status  = 0  WHERE id = ?";
        
         if ($stm = $connection->prepare($query))
         {

                $stm->bind_param('i',  $id);
           
            if ($stm->execute())
                    $return = $id;
             else
             {
                 \Application::setMysqlLogQuery('Classe Usuario; Método salvar; Mysql '. $connection->error); 
                    $this->errorCode = $connection->errno;
             }

         }
         else
        {
            \Application::setMysqlLogQuery('Classe Usuario; Método salvar; Mysql '. $connection->error); 
             $this->errorCode = $connection->errno;
        }
        return $return;
        
    }
    
    
    
    public function alterarSenha($id, $senhaNova)
    {
        $return = false;

        $connection = \Application::getNewDataBaseInstance();
      
            // atualiza registro
            $query = "update usuarios SET senha = ?  WHERE id = ?";
        
         if ($stm = $connection->prepare($query))
         {

                $stm->bind_param('si', $senhaNova, $id);
           
            if (! $stm->execute())
             {
                 \Application::setMysqlLogQuery('Classe Usuario; Método alterarSenha; Mysql '. $connection->error); 
                    $this->errorCode = $connection->errno;
             }else
                $return = true;

         }
         else
        {
            \Application::setMysqlLogQuery('Classe Usuario; Método alterarSenha; Mysql '. $connection->error); 
             $this->errorCode = $connection->errno;
        }
        return $return;
        
    }
    

}
?>