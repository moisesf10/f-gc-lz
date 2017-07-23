<?php
namespace Gauchacred\model;


/**
 * @author moises
 * @version 1.0
 * @created 10-nov-2015 16:24:32
 */

use Gauchacred\library\php\MySqlError as MySqlError;
use Gauchacred\library\php\Utils as Utils;

class Agenda implements MySqlError
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
    
    
    // o parâmetro $qualquerUsuario indica se irá listar somente do usuário da sessão ou outros
    public function listarAgenda($id = null, $qualquerUsuario = false, $limit = 1000, $cpfCliente = null, $nomeCliente = null, $convenio = null, $dataInicio = null, $dataFim = null, $status = null,  $orderColumn = 1, $orderType = 'asc')
    {
        $id = ($id === null) ? '%' : $id;
        
        $cpfCliente = ($cpfCliente === null) ? '%' : $cpfCliente;
        $nomeCliente = ($nomeCliente === null) ? '%' : '%' . $nomeCliente . '%';
        $convenio = ($convenio === null) ? '%' : $convenio;
        $dataInicio = ($dataInicio === null) ? '0001-01-01' : $dataInicio ;
        $dataFim = ($dataFim === null) ? '2100-01-01' : $dataFim;
        $status = ($status === null) ? '%' : $status;
        $limit = ($limit === null) ? 1000 : $limit;
        
        $return = false;
        
        $connection = \Application::getNewDataBaseInstance();
        
        if ($qualquerUsuario === false)
            $query = "
                select distinct
                    a.id as 'idagenda', a.cpf as 'cpfcliente', a.nome as 'nomecliente', a.datanascimento, a.email, a.cep, a.rua, a.numero, a.complemento,
                    a.bairro, a.cidade, a.uf, a.created, a.modified, a.dataligacao, a.tipocliente, a.observacoes, a.status,
                    u.id as 'idusuario', u.cpf as 'cpfusuario', u.nome as 'nomeusuario',
                    t.telefones, e.id as 'identidade', e.nome as 'nomeentidade'
                    from agenda a
                      inner join usuarios u on u.id = a.usuarios_id
                      left join entidades e on e.id = a.entidades_id
                      left join (
                        select distinct
                        ta.agenda_id, group_concat(ta.id, ',', ta.numero, ',', ta.referencia  SEPARATOR ';') as 'telefones'
                        from telefonesagenda ta
                        group by ta.agenda_id
                      ) t on t.agenda_id = a.id

                    where a.id like ? and u.id = ? and a.cpf like ? and a.nome like ? and a.entidades_id like ? and a.status like ?
                    and a.dataligacao between ? and ?  
                    order by ? {$orderType}
                    limit ?
                    
        ";
        else
            $query = "
                select distinct
                    a.id as 'idagenda', a.cpf as 'cpfcliente', a.nome as 'nomecliente', a.datanascimento, a.email, a.cep, a.rua, a.numero, a.complemento,
                    a.bairro, a.cidade, a.uf, a.created, a.modified, a.dataligacao, a.tipocliente, a.observacoes, a.status,
                    u.id as 'idusuario', u.cpf as 'cpfusuario', u.nome as 'nomeusuario',
                    t.telefones,  e.id as 'identidade', e.nome as 'nomeentidade'
                    from agenda a
                      inner join usuarios u on u.id = a.usuarios_id
                      left join entidades e on e.id = a.entidades_id
                      left join (
                        select distinct
                        ta.agenda_id, group_concat(ta.id, ',', ta.numero, ',', ta.referencia  SEPARATOR ';') as 'telefones'
                        from telefonesagenda ta 
                        group by ta.agenda_id
                      ) t on t.agenda_id = a.id

                    where a.id like ? and a.cpf like ? and a.nome like ? and a.entidades_id like ? and a.status like ?
                    and a.dataligacao between ? and ?  
                    order by ? {$orderType} 
                    limit ?
        ";
       
       
         if ($stm = $connection->prepare($query))
        {
            if ($qualquerUsuario === false)
                $stm->bind_param('sissssssii',  $id, $_SESSION['userid'], $cpfCliente, $nomeCliente, $convenio, $status, $dataInicio, $dataFim,  $orderColumn,  $limit);
             else
                 $stm->bind_param('sssssssii',  $id, $cpfCliente, $nomeCliente, $convenio, $status, $dataInicio, $dataFim, $orderColumn,  $limit);
            if ($stm->execute())
            {
                $stm->bind_result($idAgenda, $cpfCliente, $nomeCliente, $nascimentoCliente, $emailCliente, $cep, $rua, $numero, $complemento, $bairro, $cidade, $uf, $created, $modified, $dataLigacao, $tipoCliente, $observacoes, $status, $idUsuario, $cpfUsuario, $nomeUsuario, $telefones, $idConvenio, $nomeConvenio);
                
                $return = array();
                 while ($stm->fetch()) {
                     $v = array();
                     $v['id'] = $idAgenda;
                     $v['cpfCliente'] = $cpfCliente;
                     $v['nomeCliente'] = $nomeCliente;
                     $v['nascimentoCliente'] = Utils::formatStringDate($nascimentoCliente, 'Y-m-d', 'd/m/Y');
                     $v['emailCliente'] = $emailCliente;
                     $v['cep'] = $cep;
                     $v['rua'] = $rua;
                     $v['numero'] = $numero;
                     $v['complemento'] = $complemento;
                     $v['bairro'] = $bairro;
                     $v['cidade'] = $cidade;
                     $v['uf'] = $uf; 
                     $v['created'] = Utils::formatStringDate($created, 'Y-m-d H:i:s', 'd/m/Y H:i:s');
                     $v['modified'] = Utils::formatStringDate($modified, 'Y-m-d H:i:s', 'd/m/Y H:i:s');
                     $v['dataLigacao'] = Utils::formatStringDate($dataLigacao, 'Y-m-d H:i:s', 'd/m/Y H:i:s');
                     $v['tipoCliente'] = $tipoCliente;
                     $v['observacoes'] = $observacoes;
                     $v['status'] = $status;
                     $v['idUsuario'] = $idUsuario;
                     $v['cpfUsuario'] = $cpfUsuario;
                     $v['nomeUsuario'] = $nomeUsuario;
                     $v['idConvenio'] = $idConvenio;
                     $v['descricaoConvenio'] = $nomeConvenio;
                     $v['telefones'] = array();
                     $tele = explode(';', $telefones);
                     
                     if (is_array($tele))
                         foreach($tele as $i => $value)
                         {
                            $telefone = explode(',', $value);
                            if (is_array($telefone) && isset($telefone[1]) && isset($telefone[2]) )
                            {
                              
                                $t = array();
                                $t['id'] =  $telefone[0];
                                $t['numero'] = $telefone[1];
                                $t['referencia'] = $telefone[2];
                                array_push($v['telefones'], $t);
                            }
                                
                         }
                     array_push($return, $v);      
               
                 }
            }
            
        }
         else
        {
            \Application::setMysqlLogQuery('Classe Agenda; Método listarAgenda; Mysql '. $connection->error); 
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
                    insert into agenda (entidades_id ,usuarios_id,cpf,nome,datanascimento ,email ,cep ,rua ,numero ,complemento ,bairro ,cidade ,uf ,created, dataligacao ,tipocliente ,observacoes ,status)   VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,(select now()),?,?,?,?)
            ";
        else
            // atualiza registro
            $query = "update agenda set entidades_id = ?, cpf = ?, nome = ?, datanascimento = ?, email = ?, cep = ?, rua = ?, numero = ?, complemento = ?, bairro = ?, cidade = ?, uf = ?, dataligacao = ?, tipocliente = ?, observacoes = ?, status = ? where id = ?";
        

        
         if ($stm = $connection->prepare($query))
         {
            if ($id === null)
                $stm->bind_param('iisssssssssssssss',  $dados['convenio'],  $_SESSION['userid'], $dados['cpf'], $dados['nome'], $dados['dataNascimento'], $dados['email'], $dados['cep'],  $dados['rua'],  $dados['numero'], $dados['complemento'], $dados['bairro'], $dados['cidade'], $dados['uf'], $dados['dataLigacao'], $dados['tipoCliente'], $dados['observacoes'], $dados['status']);
             else
                 $stm->bind_param('isssssssssssssssi', $dados['convenio'],  $dados['cpf'], $dados['nome'], $dados['dataNascimento'], $dados['email'], $dados['cep'],  $dados['rua'],  $dados['numero'], $dados['complemento'], $dados['bairro'], $dados['cidade'], $dados['uf'], $dados['dataLigacao'], $dados['tipoCliente'], $dados['observacoes'], $dados['status'], $dados['id']);
            if ($stm->execute())
            {
                if($id === null)
                    $return = $connection->insert_id;
                else
                    $return = $id;
            }
             else
             {
                 \Application::setMysqlLogQuery('Classe Agenda; Método salvar; Mysql '. $connection->error); 
                    $this->errorCode = $connection->errno;
             }

         }
         else
        {
            \Application::setMysqlLogQuery('Classe Agenda; Método salvar; Mysql '. $connection->error); 
             $this->errorCode = $connection->errno;
        }
        
        
        if ($return !== false)
        {
            // Atualiza os telefones de contato
            
            $query = "delete from telefonesagenda where agenda_id = ?";
               if ($stm = $connection->prepare($query))
               {
                    $stm->bind_param('i', $return);
                    if (! $stm->execute())
                     {
                         \Application::setMysqlLogQuery('Classe Agenda; Método salvar - query delete telefones; Mysql '. $connection->error); 
                            $this->errorCode = $connection->errno;
                        $return = false;
                     }
               }
              else
              {
                \Application::setMysqlLogQuery('Classe Agenda; Método salvar; Mysql - query delete telefones'. $connection->error); 
                 $this->errorCode = $connection->errno;
                  $return = false;
              }
            
         }
        
            // INSERE OS TELEFONES
        if ($return !== false)
        {
            $query = "insert into telefonesagenda (agenda_id, numero, referencia) values (?,?,?);";
             if ($stm = $connection->prepare($query))
             {
                  $stm->bind_param('iss', $return, $numeroTelefone, $referencia);
                  if (is_array($dados['telefones']))
                      foreach($dados['telefones'] as $i => $telefone)
                      {
                          $numeroTelefone = $telefone['numero'];
                          $referencia = $telefone['referencia'];
                          if (! $stm->execute())
                          {
                             \Application::setMysqlLogQuery('Classe Agenda; Método salvar - query insere telefones '. $numeroTelefone  .'; Mysql '. $connection->error); 
                             $this->errorCode = $connection->errno;
                             $return = false;
                              break;
                          }
                      }
                 
                 
              }  else
              {
                 \Application::setMysqlLogQuery('Classe Agenda; Método salvar; Mysql - query insere telefones. Erro estrutura query '. $connection->error); 
                 $this->errorCode = $connection->errno;
                 $return = false;
              }
        }
            
        
        
        
        return $return;
        
    }
    
    
    
    public function excluir($id, $qualquerUsuario = false)
     {
        $return = false;
        
        $connection = \Application::getNewDataBaseInstance();
        
        if ($qualquerUsuario == false)
            $query = "delete from agenda where id = ? and usuarios_id = ?";
        else
            $query = "delete from agenda where id = ?";

         if ($stm = $connection->prepare($query))
         {
             if ($qualquerUsuario == false)   
                $stm->bind_param('ii', $id, $_SESSION['userid']);
             else
                 $stm->bind_param('i', $id);
                if ($stm->execute())
                {
                    if ($connection->affected_rows > 0)    
                        $return = true;
                    else
                        $return = 1;
                }
                 else
                 {
                     \Application::setMysqlLogQuery('Classe Agenda; Método excluir; Mysql '. $connection->error); 
                        $this->errorCode = $connection->errno;
                 }
         }
         else
        {
            \Application::setMysqlLogQuery('Classe Agenda; Método excluir; Mysql '. $connection->error); 
             $this->errorCode = $connection->errno;
        }
        return $return;
        
     }
    
    
    public function reagendar($id, $dataLigacao)
    {
        $return = false;
        
        $connection = \Application::getNewDataBaseInstance();
        
        $query = "update agenda set dataligacao = ? where id = ?";

         if ($stm = $connection->prepare($query))
         { 
                $stm->bind_param('si', $dataLigacao, $id);

                if ($stm->execute())
                {
                    if ($connection->affected_rows > 0)    
                        $return = true;
                    else
                        $return = 1;
                }
                 else
                 {
                     \Application::setMysqlLogQuery('Classe Agenda; Método reagendar; Mysql '. $connection->error); 
                        $this->errorCode = $connection->errno;
                 }
         }
         else
        {
            \Application::setMysqlLogQuery('Classe Agenda; Método reagendar; Mysql '. $connection->error); 
             $this->errorCode = $connection->errno;
        }
        return $return;
        
    }
    
    
    
    
    
    

}
?>