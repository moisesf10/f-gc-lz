<?php
namespace Gauchacred\model;


/**
 * @author moises
 * @version 1.0
 * @created 10-nov-2015 16:24:32
 */

use Gauchacred\library\php\MySqlError as MySqlError;
use Gauchacred\library\php\Utils as Utils;

class Cliente implements MySqlError
{

	private $errorCode = '';
    
    public function __construct()
    {
        
    }
    
    
    public function getMysqlError()
    {
        return $this->errorCode;
    }
    
    
    public function aniversariantes($dia, $mes)
    {
        
        
        $connection = \Application::getNewDataBaseInstance();
        $query = "
            select distinct
                c.cpf_cnpj, c.nome, c.apelido, c.email, c.senha, c.dtnascimento, 
                c.cep, c.rua, c.numero, c.complemento, c.bairro, c.uf, c.cidade, 
                c.timefutebol, c.observacoes, c.created, c.modified, u.nome as 'nomeusuario'

                from clientes c
                    left join usuarios u on u.id = c.usuarios_id
                 

                where  day(c.dtnascimento) = ? and month(c.dtnascimento) = ?
                order by c.nome
      
        ";
        
        $return = false;
        
         if ($stm = $connection->prepare($query))
        {
           
             $stm->bind_param('ii',  $dia, $mes);
            if ($stm->execute())
            {
                $stm->bind_result($cpf, $nomeCliente, $apelido, $email, $senha, $dtNascimento, $cep, $rua, $numeroRua, $complemento, $bairro, $uf, $cidade, $timeFutebol, $observacoes, $created, $modified, $nomeUsuario);
                 
                $return = array();
                $pos = 0;
                
                 while ($stm->fetch()) {
                     $c['cpf'] =  $cpf;
                     $c['nomeCliente'] = $nomeCliente ;
                     $c['apelido'] = $apelido  ;
                     $c['email'] = $email ;
                     $c['senha'] = $senha ;
                     $c['nascimento'] =Utils::formatStringDate($dtNascimento, 'Y-m-d', 'd/m/Y');
                     $c['cep'] = $cep ;
                     $c['rua'] = $rua ;
                     $c['numeroRua'] = $numeroRua ;
                     $c['complemento'] = $complemento ;
                     $c['bairro'] = $bairro ;
                     $c['uf'] = $uf ;
                     $c['cidade'] = $cidade ;
                     $c['timeFutebol'] = $timeFutebol ;
                     $c['observacoes'] = $observacoes ;
                     $c['created'] = Utils::formatStringDate($created, 'Y-m-d H:i:s', 'd/m/Y H:i:s');
                     $c['modified'] = Utils::formatStringDate($modified, 'Y-m-d H:i:s', 'd/m/Y H:i:s');
                     $c['nomeUsuario'] = $nomeUsuario;
                     
                     
                     array_push($return, $c);
                    
                 }
                
            }
             
         } else
        {
            \Application::setMysqlLogQuery('Classe Cliente; Método aniversariantes; Mysql '. $connection->error); 
             $this->errorCode = $connection->errno;
        }
        
        return $return;
        
    }
    
    
    
    
    public function getResumo($cpf = null, $nome = null, $idUsuario = null, $convenio = null, $nascimentoInicial = null, $nascimentoFinal = null, $mes = null, $limit = null, $qualquerCliente = false)
    {
        $cpf = ($cpf !== null)? $cpf : '%';
        $nome = ($nome !== null) ?'%'. $nome .'%' : '%';
        $convenio = ($convenio !== null) ? $convenio : '%';
        $nascimentoInicial = ($nascimentoInicial !== null) ? $nascimentoInicial : '0001-01-01';
        $nascimentoFinal = ($nascimentoFinal !== null) ? $nascimentoFinal : '2100-01-01';
        $mes = ($mes !== null) ? $mes : '%';
        $idUsuario = ($idUsuario !== null) ? $idUsuario : '%';
        $limit = ($limit !== null) ? $limit : 10000;
        
        if ($qualquerCliente == false)
            $idUsuario = $_SESSION['userid'];
        
        $connection = \Application::getNewDataBaseInstance();
        $query = "
            select distinct
                c.cpf_cnpj, c.nome, c.apelido, c.email, c.senha, c.dtnascimento, 
                c.cep, c.rua, c.numero, c.complemento, c.bairro, c.uf, c.cidade, 
                c.timefutebol, c.observacoes, c.created, c.modified,
                con.convenios, c.nomearquivo, c.dataimportacao

                from clientes c
                  left join (
                    select distinct
                    cc.clientes_cpf_cnpj, group_concat(e.nome SEPARATOR ';') as 'convenios'
                    from conveniocliente cc
                      inner join entidades e on e.id = cc.entidade_id
                    where e.id like ?
                     group by cc.clientes_cpf_cnpj
                  ) con on con.clientes_cpf_cnpj = c.cpf_cnpj

                where  c.nome like ?
                and ((c.dtnascimento between ? and ?) or (? = '0001-01-01' and ? = '2100-01-01'))
                and c.cpf_cnpj like ? and c.usuarios_id like ?
                order by c.created desc
                limit ?
        ";
        
        $return = false;
        
         if ($stm = $connection->prepare($query))
        {
           
             $stm->bind_param('ssssssssi',  $convenio, $nome, $nascimentoInicial, $nascimentoFinal, $nascimentoInicial, $nascimentoFinal, $cpf, $idUsuario, $limit);
            if ($stm->execute())
            {
                $stm->bind_result($cpf, $nomeCliente, $apelido, $email, $senha, $dtNascimento, $cep, $rua, $numeroRua, $complemento, $bairro, $uf, $cidade, $timeFutebol, $observacoes, $created, $modified, $convenios,  $nomeArquivo, $dataImportacao);
                 
                $return = array();
                $pos = 0;
                
                 while ($stm->fetch()) {
                     $c['cpf'] =  $cpf;
                     $c['nomeCliente'] = $nomeCliente ;
                     $c['apelido'] = $apelido  ;
                     $c['email'] = $email ;
                     $c['senha'] = $senha ;
                     $c['nascimento'] =Utils::formatStringDate($dtNascimento, 'Y-m-d', 'd/m/Y');
                     $c['cep'] = $cep ;
                     $c['rua'] = $rua ;
                     $c['numeroRua'] = $numeroRua ;
                     $c['complemento'] = $complemento ;
                     $c['bairro'] = $bairro ;
                     $c['uf'] = $uf ;
                     $c['cidade'] = $cidade ;
                     $c['timeFutebol'] = $timeFutebol ;
                     $c['observacoes'] = $observacoes ;
                     $c['created'] = Utils::formatStringDate($created, 'Y-m-d H:i:s', 'd/m/Y H:i:s');
                     $c['modified'] = Utils::formatStringDate($modified, 'Y-m-d H:i:s', 'd/m/Y H:i:s');
                     $c['dataImportacao'] = Utils::formatStringDate($dataImportacao, 'Y-m-d H:i:s', 'd/m/Y H:i:s');
                     $c['nomeArquivo'] = $nomeArquivo;
                     $convenios = explode(';', $convenios);
                     $conv = array();
                     if (is_array($convenios))
                         foreach($convenios as $j => $value)
                             if (array_search($value, $conv) === false)
                                 array_push($conv, $value);
                     
                     $c['convenios'] = $conv;
                     
                     array_push($return, $c);
                    
                 }
                
            }
             
         }
        
        return $return;
        
    }
    
    
     public function carregar($cpf = '%', $limit = 10, $orderColumn = 1, $orderType = 'desc', $qualquerCliente = false)
    {
        
        
        $return = false;
        
        $connection = \Application::getNewDataBaseInstance();
         
        $qualquerCliente = ($qualquerCliente == true) ? '%' : $_SESSION['userid'];
        
         
        $query = "
                select distinct
                    c.cpf_cnpj, c.nome, c.apelido, c.email, c.senha, c.dtnascimento,
                    c.cep, c.rua, c.numero, c.complemento, c.bairro, c.uf,
                    c.cidade, c.timefutebol, c.observacoes, c.created, c.modified,
                    group_concat(tel.numero,',',tel.referencia SEPARATOR ';') as 'telefone',
                    cc.convenio, co.conta, u.nome, c.nomearquivo, c.dataimportacao,
                    email.email, ua.nome as 'usuariomodificacao',
                    ac.file
                from clientes c
                    left join usuarios u on u.id = c.usuarios_id
                    left join usuarios ua on ua.id = c.usuarios_id_alteracao
                  left join (
                    select distinct
                    t.clientes_cpf_cnpj, ifnull(t.numero,'') as 'numero', ifnull(t.referencia,'') as 'referencia'
                    from telefonesclientes t
                     group by t.clientes_cpf_cnpj, t.numero
                  ) tel on tel.clientes_cpf_cnpj = c.cpf_cnpj
                  
                  left join (
                    select distinct
                   cc.clientes_cpf_cnpj, group_concat(cc.id, ',', ifnull(e.id,''), ',', ifnull(cc.nb,''), ',', ifnull(cc.matricula,''), ',', ifnull(cc.senha,''),',', ifnull(e.nome,'') ORDER BY cc.id ASC SEPARATOR ';' ) as 'convenio' 
                    from conveniocliente cc
                      left join entidades e on e.id = cc.entidade_id
                      group by cc.clientes_cpf_cnpj 
                  ) cc on cc.clientes_cpf_cnpj = c.cpf_cnpj
                  
                  left join (
                    select distinct
                    cb.clientes_cpf_cnpj, group_concat(cb.id, ',', cb.bancos_id, ',', ifnull(cb.tipocontabancaria_id,''), ',', cb.agencia, ',', cb.conta, ',', b.nome, ',', ifnull(tcb.descricao,''), ',', b.codigo order by cb.id Separator ';') as 'conta'
                    from contabancariaclientes cb
                        inner join bancos b on b.id = cb.bancos_id
                        left join tipocontabancaria tcb on tcb.id = cb.tipocontabancaria_id
                    group by cb.clientes_cpf_cnpj
                  ) co on co.clientes_cpf_cnpj = c.cpf_cnpj
                  
                   left join (
                    select distinct
                    e.clientes_cpf as 'cpf', group_concat(e.email, ',', e.senha  SEPARATOR ';') as 'email'
                    from emailsclientes e
                    group by e.clientes_cpf
                  ) email on email.cpf = c.cpf_cnpj
                  
                  left join (
                  select 
                  ac.clientes_cpf, group_concat(ac.id, ',', ifnull(ac.descricao,''), ',', ac.mime, ',', ac.filename, ',', ac.systemfilename, ',', ac.created, ',', ac.modified SEPARATOR ';') as 'file'
                  
                  from arquivosclientes ac 
                  group by ac.clientes_cpf
                  
                  ) ac on ac.clientes_cpf = c.cpf_cnpj
                    
                    where c.cpf_cnpj like ? and c.usuarios_id like ?
                    group by 
                c.cpf_cnpj, c.nome, c.apelido, c.email, c.senha, 
                c.dtnascimento, c.cep, c.rua, c.numero, c.complemento, 
                c.bairro, c.uf, c.cidade, c.timefutebol, c.observacoes, 
                c.created, c.modified, cc.convenio
                 order by ? $orderType limit ?
        ";
       
        
         if ($stm = $connection->prepare($query))
        {
            $stm->bind_param('ssii',  $cpf, $qualquerCliente, $orderColumn, $limit);
            if ($stm->execute())
            {
                $stm->bind_result($cpf, $nomeCliente, $apelido, $email, $senha, $dtNascimento, $cep, $rua, $numeroRua, $complemento, $bairro, $uf, $cidade, $timeFutebol, $observacoes, $created, $modified, $telefones, $convenios, $conta, $nomeUsuario, $nomeArquivo, $dataImportacao, $emails, $usuarioModificacao, $files);
                
                $return = array();
                $pos = 0;
                 while ($stm->fetch()) {
                     $v = array();
                     $v['cpf'] = $cpf;
                     
                     $v['nomeCliente'] = $nomeCliente;
                     $v['apelido'] =  $apelido;
                     $v['email'] = $email;
                     $v['senha'] = $senha;
                     $v['nascimento'] = Utils::formatStringDate($dtNascimento, 'Y-m-d', 'd/m/Y');
                     $v['cep'] = $cep;
                     $v['rua'] = $rua;
                      $v['numeroRua'] = $numeroRua;
                      $v['complemento'] = $complemento;
                     $v['bairro'] = $bairro;
                     $v['uf'] = $uf;
                     $v['cidade'] = $cidade;
                     $v['timeFutebol'] = $timeFutebol;
                     $v['observacoes'] = $observacoes;
                     $v['created'] = Utils::formatStringDate($created, 'Y-m-d H:i:s', 'd/m/Y H:i:s');
                     $v['modified'] = Utils::formatStringDate($modified, 'Y-m-d H:i:s', 'd/m/Y H:i:s');
                     $v['dataImportacao'] = Utils::formatStringDate($dataImportacao, 'Y-m-d H:i:s', 'd/m/Y H:i:s');
                     $v['nomeArquivo'] = $nomeArquivo;
                     $v['nomeUsuarioModificacao'] = $usuarioModificacao;
                      $return[$pos]['nomeUsuario'] = $nomeUsuario;
                      $return[$pos]['dados'] = $v;       
                     
                     
                     // Telefone
                    
                        $return[$pos]['telefones'] = array();
                     $aux = explode(';', $telefones);
                     if (is_array($aux))
                         foreach($aux as $i => $value)
                         {
                             $aux2 = explode(',', $value);
                             if (count($aux2) > 0)
                             {
                                 $t = array();
                                 $t['numero'] = (isset($aux2[0]))? $aux2[0] : '';
                                 $t['referencia'] = (isset($aux2[1]))? $aux2[1] : '';
                                 array_push($return[$pos]['telefones'], $t);
                             }
                         }
                     //echo $convenios; exit;
                     // Convenio
                     
                        $return[$pos]['convenios'] = array();
                     $aux = explode(';', $convenios);
                     if (is_array($aux))
                         foreach($aux as $i => $value)
                         {
                            
                             $aux2 = explode(',', $value);
                             if (count($aux2) > 0)
                             {
                                 $t = array();
                                 $t['idConvenio'] = (isset($aux2[1])) ?  $aux2[1] : '';
                                 $t['nb'] = (isset($aux2[2])) ? $aux2[2] : '';
                                 $t['matricula'] = (isset($aux2[3])) ? $aux2[3] : '';
                                 $t['senha'] = (isset($aux2[4])) ? $aux2[4] : '';
                                $t['nomeConvenio'] = (isset($aux2[5])) ? $aux2[5] : '';
                                 array_push($return[$pos]['convenios'], $t);
                             }
                         }
                     
                     
                     // Contas Bancárias
                  //   if ($conta != '')
                        $return[$pos]['contas'] = array();
                     $aux = explode(';', $conta);
                     if (is_array($aux))
                         foreach($aux as $i => $value)
                         {
                             $aux2 = explode(',', $value);
                             if (count($aux2) > 0)
                             {
                                 $t = array();
                                 $t['idContaBancariaCliente'] = (isset($aux2[0])) ? $aux2[0] : '';
                                 $t['idBanco'] = (isset($aux2[1])) ? $aux2[1] : '';
                                 $t['idTipoConta'] = (isset($aux2[2])) ? $aux2[2] : '';
                                 $t['agencia'] = (isset($aux2[3])) ? $aux2[3] : '';
                                 $t['conta'] = (isset($aux2[4])) ? $aux2[4] : '';
                                 $t['nomeBanco'] = (isset($aux2[5])) ? $aux2[5] : '';
                                 $t['descricaoConta'] = (isset($aux2[6])) ? $aux2[6] : '';
                                 $t['codigoBanco'] = (isset($aux2[7])) ? $aux2[7] : '';
                                 
                                 array_push($return[$pos]['contas'], $t);
                             }
                         }
                     
                      // Contas Bancárias
                     //   if ($conta != '')
                    $return[$pos]['emails'] = array();
                     $aux = explode(';', $emails);
                     if (is_array($aux))
                         foreach($aux as $i => $value)
                         {
                             $aux2 = explode(',', $value);
                             if (count($aux2) > 0)
                             {
                                 $t = array();
                                 $t['email'] = (isset($aux2[0])) ? $aux2[0] : '';
                                 $t['senha'] = (isset($aux2[1])) ? $aux2[1] : '';
                                 
                                 array_push($return[$pos]['emails'], $t);
                             }
                         }
                    
                    // ARQUIVOS
                    $return[$pos]['files'] = array(); 
                    $aux = explode(';', $files);
                    if (is_array($aux) && count($aux) > 0)
                        foreach($aux as $i => $value)
                        {
                            $aux2 = explode(',', $value);
                            if (count($aux2) > 0)
                            {
                                $f = array();
                                $f['id'] = (isset($aux2[0])) ? $aux2[0] : '';
                                $f['descricao'] = (isset($aux2[1])) ? $aux2[1] : '';
                                $f['mime'] = (isset($aux2[2])) ? $aux2[2] : '';
                                $f['name'] = (isset($aux2[3])) ? $aux2[3] : '';
                                $f['fileName'] = (isset($aux2[4])) ? $aux2[4] : '';
                                $f['created'] = (isset($aux2[5])) ? Utils::formatStringDate( $aux2[5], 'Y-m-d H:i:s', 'd/m/Y H:i:s') : '';
                                $f['modified'] = (isset($aux2[6])) ? Utils::formatStringDate( $aux2[6], 'Y-m-d H:i:s', 'd/m/Y H:i:s') : '';
                                
                                array_push($return[$pos]['files'], $f);
                            }
                        }
                     //echo '<pre>';var_dump($return);exit;
                     $pos++;
                 }
            }else
            {
                \Application::setMysqlLogQuery('Classe Cliente; Método carregar; Mysql '. $connection->error); 
                 $this->errorCode = $connection->errno;
            }
            
        }
         else
        {
            \Application::setMysqlLogQuery('Classe Cliente; Método carregar; Mysql '. $connection->error); 
             $this->errorCode = $connection->errno;
        }
        
        return $return;
        
    }
    
    
    public function removerArquivoCliente($id)
    {
        $return = false;
        $connection = \Application::getNewDataBaseInstance();
        $connection->autocommit(false);
        
        $query = 'DELETE FROM arquivosclientes WHERE id = ?';
        if ($stm = $connection->prepare($query))
        {
            $stm->bind_param('i', $id);
            if (! $stm->execute())
            {
                 \Application::setMysqlLogQuery('Classe Cliente; Método removerArquivoCliente; Mysql '. $connection->error); 
                 $this->errorCode = $connection->errno;
            }else
                $return = true;
        }else
        {
               \Application::setMysqlLogQuery('Classe Cliente; Método removerArquivoCliente; Mysql '. $connection->error); 
                 $this->errorCode = $connection->errno;
        }
        
        if (! $return)
            $connection->rollback();
        else
            $connection->commit();
        
        return $return;
        
    }
   
    
    
    public function salvar($cliente, $cpf = null, $filesMoved = null)
    {
                
        $return = false;
        
                
        $connection = \Application::getNewDataBaseInstance();
        $connection->autocommit(false);
        //novo registro
        if ($cpf === null)
            $query = "
                   insert into clientes (cpf_cnpj,nome,dtnascimento,cep,rua,numero,complemento
                  ,bairro,uf,cidade,observacoes,created, usuarios_id
                ) VALUES (
                   ? -- cpf_cnpj - IN varchar(20)
                  ,? -- nome - IN varchar(255)
                  ,? -- dtnascimento - IN date
                  ,?  -- cep - IN varchar(10)
                  ,?  -- rua - IN varchar(255)
                  ,?  -- numero - IN varchar(10)
                  ,?  -- complemento - IN varchar(45)
                  ,?  -- bairro - IN varchar(128)
                  ,?  -- uf - IN varchar(2)
                  ,?  -- cidade - IN varchar(128)
                  ,?   -- observacoes - IN tinytext
                  ,(select now()) -- created - IN datetime
                  , ?
                )
            ";
        else
            // atualiza registro
            $query = "
                update clientes SET cpf_cnpj = ?,nome = ?, dtnascimento = ? ,cep = ?
                      ,rua = ? ,numero = ? ,complemento = ? ,bairro = ? ,uf = ? ,cidade = ? , observacoes = ?, usuarios_id_alteracao = ?
                    WHERE cpf_cnpj = ?
            ";
       
        
         if ($stm = $connection->prepare($query))
         {
            if ($cpf === null)
                $stm->bind_param('sssssssssssi',  $cliente['dados']['cpf'], $cliente['dados']['nome'], $cliente['dados']['nascimento'], $cliente['dados']['cep'], $cliente['dados']['rua'], $cliente['dados']['numerorua'], $cliente['dados']['complemento'], $cliente['dados']['bairro'], $cliente['dados']['uf'], $cliente['dados']['cidade'],$cliente['dados']['observacoes'], $_SESSION['userid'] );
             else
                 $stm->bind_param('sssssssssssis', $cliente['dados']['cpf'], $cliente['dados']['nome'], $cliente['dados']['nascimento'], $cliente['dados']['cep'], $cliente['dados']['rua'], $cliente['dados']['numerorua'], $cliente['dados']['complemento'], $cliente['dados']['bairro'], $cliente['dados']['uf'], $cliente['dados']['cidade'], $cliente['dados']['observacoes'], $_SESSION['userid'] ,  $cpf);
            if ($stm->execute())
            {
                $return = true;
				$cpf = $cliente['dados']['cpf'];
            }
             else
             {
                 \Application::setMysqlLogQuery('Classe Cliente; Método salvar; Mysql '. $connection->error); 
                    $this->errorCode = $connection->errno;
             }

         }
         else
        {
            \Application::setMysqlLogQuery('Classe Cliente; Método salvar; Mysql '. $connection->error); 
             $this->errorCode = $connection->errno;
        }
        
        
        if ($return)
        {
            // Grava Telefones
            $query = "delete from telefonesclientes where clientes_cpf_cnpj = ?";
            if ($stm = $connection->prepare($query))
            {
                $stm->bind_param('s', $cliente['dados']['cpf']);
                if (! $stm->execute())
                {
                     \Application::setMysqlLogQuery('Classe Cliente; Método salvar - delete from telefone; Mysql '. $connection->error); 
                     $this->errorCode = $connection->errno;
                     $return = false;
                }
            }else
            {
                   \Application::setMysqlLogQuery('Classe Cliente; Método salvar - delete from telefones; Mysql '. $connection->error); 
                     $this->errorCode = $connection->errno;
                     $return = false;
            }
            
            $query = "insert into telefonesclientes (clientes_cpf_cnpj, numero, referencia) values(?,?,?)";
            if (is_array($cliente['telefones']))
            {
                if ($stm = $connection->prepare($query))
                {
                    foreach($cliente['telefones'] as $i => $value)
                    {
                        $stm->bind_param('sss', $cliente['dados']['cpf'], $value['numero'], $value['referencia'] );
                        if (! $stm->execute())
                        {
                             \Application::setMysqlLogQuery('Classe Cliente; Método salvar - insert into telefones; Mysql '. $connection->error); 
                             $this->errorCode = $connection->errno;
                             $return = false;
                            break;
                        }
                    }
                        
                }
            }
            
            
            // GRAVA CONVENIOS
            
            $query = "delete from conveniocliente where clientes_cpf_cnpj = ?";
            if ($stm = $connection->prepare($query))
            {
                $stm->bind_param('s', $cliente['dados']['cpf']);
                if (! $stm->execute())
                {
                     \Application::setMysqlLogQuery('Classe Cliente; Método salvar - delete from conveniocliente; Mysql '. $connection->error); 
                     $this->errorCode = $connection->errno;
                     $return = false;
                }
            }else
            {
                   \Application::setMysqlLogQuery('Classe Cliente; Método salvar - delete from conveniocliente; Mysql '. $connection->error); 
                     $this->errorCode = $connection->errno;
                     $return = false;
            }
            
            $query = "insert into conveniocliente (clientes_cpf_cnpj, entidade_id, matricula, senha) values(?,?,?,?)";
            if (is_array($cliente['convenios']))
            {
                if ($stm = $connection->prepare($query))
                {
                    foreach($cliente['convenios'] as $i => $value)
                    {
                        $convenio = (empty($value['convenio']) ) ? null : $value['convenio'];
                        $senha = urldecode($value['senha']);
                        $stm->bind_param('ssss', $cliente['dados']['cpf'], $convenio, $value['matricula'], $senha );
                        if (! $stm->execute())
                        {
                             \Application::setMysqlLogQuery('Classe Cliente; Método salvar - insert into conveniocliente; Mysql '. $connection->error); 
                             $this->errorCode = $connection->errno;
                             $return = false;
                            break;
                        }
                    }
                        
                }
            }
            
            
            // GRAVA CONTA BANCÁRIA
            
            $query = "delete from contabancariaclientes where clientes_cpf_cnpj = ?";
            if ($stm = $connection->prepare($query))
            {
                $stm->bind_param('s', $cliente['dados']['cpf']);
                if (! $stm->execute())
                {
                     \Application::setMysqlLogQuery('Classe Cliente; Método salvar - delete from contabancariaclientes; Mysql '. $connection->error); 
                     $this->errorCode = $connection->errno;
                     $return = false;
                }
            }else
            {
                   \Application::setMysqlLogQuery('Classe Cliente; Método salvar - delete from contabancariaclientes; Mysql '. $connection->error); 
                     $this->errorCode = $connection->errno;
                     $return = false;
            }
          
            $query = "insert into contabancariaclientes (clientes_cpf_cnpj, bancos_id, tipocontabancaria_id, agencia, conta) values(?,?,?,?,?)";
            if (is_array($cliente['contabancaria']))
            {
                if ($stm = $connection->prepare($query))
                {
                    foreach($cliente['contabancaria'] as $i => $value)
                    {
                        $tipoConta = (empty($value['tipoconta'])) ? null : $value['tipoconta'];
                        $stm->bind_param('sisss', $cliente['dados']['cpf'], $value['banco'], $tipoConta, $value['agencia'], $value['conta'] );
                        if (! $stm->execute())
                        {
                             \Application::setMysqlLogQuery('Classe Cliente; Método salvar - insert into contabancariaclientes; Mysql '. $connection->error); 
                             $this->errorCode = $connection->errno;
                             $return = false;
                            break;
                        }
                    }
                        
                }
            }
            
            // GRAVA EMAILS
            
            $query = "delete from emailsclientes where clientes_cpf = ?";
            if ($stm = $connection->prepare($query))
            {
                $stm->bind_param('s', $cliente['dados']['cpf']);
                if (! $stm->execute())
                {
                     \Application::setMysqlLogQuery('Classe Cliente; Método salvar - delete from emailsclientes; Mysql '. $connection->error); 
                     $this->errorCode = $connection->errno;
                     $return = false;
                }
            }else
            {
                   \Application::setMysqlLogQuery('Classe Cliente; Método salvar - delete from emailsclientes; Mysql '. $connection->error); 
                     $this->errorCode = $connection->errno;
                     $return = false;
            }
          
            $query = "insert into emailsclientes (clientes_cpf, email, senha) values(?,?,?)";
            if (is_array($cliente['emails']))
            {
                if ($stm = $connection->prepare($query))
                {
                    foreach($cliente['emails'] as $i => $value)
                    {
                        $senha = urldecode($value['senha']);
                        $stm->bind_param('sss', $cliente['dados']['cpf'], $value['email'], $senha );
                        if (! $stm->execute())
                        {
                             \Application::setMysqlLogQuery('Classe Cliente; Método salvar - insert into emailsclientes; Mysql '. $connection->error); 
                             $this->errorCode = $connection->errno;
                             $return = false;
                            break;
                        }
                    }
                        
                }
            } 
            
        }
        
        if ($filesMoved != null && is_array($filesMoved)  )
        {
            $query = 'INSERT INTO arquivosclientes( clientes_cpf  ,descricao  ,mime  ,filename  ,systemfilename  ,created )VALUES ( ? , ? , ?, ? , ?, (select now()))';
            if ($stm = $connection->prepare($query))
            {
                foreach($filesMoved as $i => $value)
                {
                    //$senha = urldecode($value['senha']);
                    $stm->bind_param('sssss', $cliente['dados']['cpf'], $value['descricao'], $value['mime'], $value['nome'], $value['nomeSistema'] );
                    if (! $stm->execute())
                    {
                         \Application::setMysqlLogQuery('Classe Cliente; Método salvar - upload file; Mysql '. $connection->error); 
                         $this->errorCode = $connection->errno;
                         $return = false;
                        break;
                    }
                }

            }else
            {
                \Application::setMysqlLogQuery('Classe Cliente; Método salvar - upload file; Mysql '. $connection->error); 
                $this->errorCode = $connection->errno;
            }
            
        }
        
        if ($return)
        {
            $connection->commit();
            $return = $cliente['dados']['cpf'];
        }
        else
            $connection->rollback();
        return $return;
        
    }
    
    
    
    public function importar($dados)
    {
                
        $return = false;
        
                
        $connection = \Application::getNewDataBaseInstance();
      //  $connection->autocommit(false);
      
            $query = "
                   insert into clientes (cpf_cnpj,nome,email,senha, cep ,rua
                  ,bairro,uf,cidade,dtnascimento,created, usuarios_id, nomearquivo, dataimportacao
                ) VALUES (
                   ? -- cpf_cnpj - IN varchar(20)
                  ,? -- nome - IN varchar(255)
                  ,? -- email - IN varchar(255)
                  ,? -- senha - IN varchar(128)
                  ,?  -- cep - IN varchar(10)
                  ,?  -- rua - IN varchar(255)
                  ,?  -- bairro - IN varchar(128)
                  ,?  -- uf - IN varchar(2)
                  ,?  -- cidade - IN varchar(128)
                  ,?   -- nascimento - IN tinytext
                  ,? -- data cadastro
                  ,? -- idusuario
                  ,? -- nomeArquivo
                  ,(select now()) -- dataImportacao
                )
            ";
        
         if ($stm = $connection->prepare($query))
         {
                $stm->bind_param('sssssssssssis',  $dados['cpf'], $dados['nome'],  $dados['email'], $dados['senha'],  $dados['cep'], $dados['rua'],   $dados['bairro'], $dados['estado'], $dados['cidade'],  $dados['nascimento'], $dados['created'], $dados['idUsuario'], $dados['nomeArquivo'] );
             
              if ($stm->execute())
                {
                    $return =  $connection->insert_id;
                }
                 else
                 {
                     \Application::setMysqlLogQuery('Classe Cliente; Método importar; Mysql '. $connection->error); 
                        $this->errorCode = $connection->errno;
                 }

         }
         else
        {
            \Application::setMysqlLogQuery('Classe Cliente; Método importar; Mysql '. $connection->error); 
             $this->errorCode = $connection->errno;
        }
        
        
        return $return;
     
    }
    
    
    public function salvarTelefone($cpf, $numero, $referencia, $id = null)
    {
                
        $return = false;
        
                
        $connection = \Application::getNewDataBaseInstance();
      //  $connection->autocommit(false);
       if ($id === null)
            $query = "
                 insert into telefonesclientes (clientes_cpf_cnpj, numero, referencia) values(?,?,?);
            ";
        else
            $query = "update telefonesclientes set clientes_cpf_cnpj = ?, numero = ?, referencia = ? where id = ?";
        
         if ($stm = $connection->prepare($query))
         {
             if ($id === null)
                $stm->bind_param('sss',  $cpf, $numero, $referencia );
             else
                 $stm->bind_param('sssi',  $cpf, $numero, $referencia, $id );
             
              if ($stm->execute())
                {
                  if ($id === null)
                     $return =  $connection->insert_id;
                  else
                      $return = $id;
                }
                 else
                 {
                     \Application::setMysqlLogQuery('Classe Cliente; Método salvarTelefone; Mysql '. $connection->error); 
                        $this->errorCode = $connection->errno;
                 }

             }
             else
            {
                \Application::setMysqlLogQuery('Classe Cliente; Método salvarTelefone; Mysql '. $connection->error); 
                 $this->errorCode = $connection->errno;
            }
        
        
        return $return;
     
    }
    
     public function salvarNb($cpf, $idEntidade, $nb, $matricula, $senha)
    {
         $return = false;

        $connection = \Application::getNewDataBaseInstance();
         
          $query = "insert into conveniocliente (clientes_cpf_cnpj, entidade_id, nb, matricula, senha) values(?,?,?,?,?)";
          if ($stm = $connection->prepare($query))
            {
                
                    $stm->bind_param('sisss', $cpf, $idEntidade, $nb, $matricula, $senha );
                    if (! $stm->execute())
                    {
                         \Application::setMysqlLogQuery('Classe Cliente; Método salvarNb; Mysql '. $connection->error); 
                         $this->errorCode = $connection->errno;
                         $return = false;
                      
                    }
                

            }else
          {
                \Application::setMysqlLogQuery('Classe Cliente; Método salvarNb; Mysql '. $connection->error); 
                 $this->errorCode = $connection->errno;
                 $return = false;
                        
          }
        
        return $return;
    }
    
    
    
     public function salvarLogImportacao($mime, $nomeArquivo, $nomeSistema)
    {
         $return = false;

        $connection = \Application::getNewDataBaseInstance();
   
            $query = "
                   insert into logimportacaocliente (         mime    ,nomearquivo      ,nomesistema         ,created        ) VALUES (
                      ?  -- mime - IN varchar(45)
                      ,? -- nomearquivo - IN varchar(255)
                      ,? -- nomesistema - IN varchar(255)
                      , (select now())
                    )
            ";
        
        
         if ($stm = $connection->prepare($query))
         {
       
                $stm->bind_param('sss', $mime, $nomeArquivo, $nomeSistema);
           
            if ($stm->execute())
            {
                
                    $return = $connection->insert_id;
               
            }
             else
             {
                 \Application::setMysqlLogQuery('Classe cliente; Método salvarLogImportacao; Mysql '. $connection->error); 
                    $this->errorCode = $connection->errno;
             }

         }
         else
        {
            \Application::setMysqlLogQuery('Classe Cliente; Método salvarLogImportacao; Mysql '. $connection->error); 
             $this->errorCode = $connection->errno;
        }
        return $return;
        
    }
    
    
    public function listarLogImportacao($id = '%')
    {
        
        
        $return = false;
        
        $connection = \Application::getNewDataBaseInstance();
        $query = "
            select distinct
                lo.id, lo.mime, lo.nomearquivo, lo.nomesistema, lo.created

                from logimportacaocliente lo

                order by lo.id desc
        ";
       
        
         if ($stm = $connection->prepare($query))
        {
          //  $stm->bind_param('s',  $id);
            if ($stm->execute())
            {
                $stm->bind_result($id, $mime, $nomeArquivo, $nomeArquivoSistema, $created);
                
                $return = array();
                 while ($stm->fetch()) {
                     $v = array();
                     $v['id'] = $id;
                     $v['mime'] = $mime;
                     $v['nomeArquivo'] = $nomeArquivo;
                     $v['nomeArquivoSistema'] = $nomeArquivoSistema;
                     $v['created'] = Utils::formatStringDate($created, 'Y-m-d H:i:s', 'd/m/Y H:i:s'); 
                     
                     array_push($return, $v);           
                 }
            }
            
        }
         else
        {
            \Application::setMysqlLogQuery('Classe cliente; Método listarLogImportacao; Mysql '. $connection->error); 
             $this->mysqlError = $connection->errno;
        }
        return $return;
        
    }
    
    
    public function clientesNaoAgendados()
    {
        
        
        $return = false;
        
        $connection = \Application::getNewDataBaseInstance();
        $query = "
                select distinct
                c.cpf_cnpj as 'cpf', c.nome as 'nomeCliente', c.created, 
                u.id as 'idusuario', u.nome as 'nomeusuario'

                from clientes c
                  inner join usuarios u on u.id = c.usuarios_id
                where not exists (
                  select 
                  a.id
                  from agenda a
                  where a.cpf = c.cpf_cnpj 
                )
                order by c.created desc
        ";
       
        
         if ($stm = $connection->prepare($query))
        {
            //$stm->bind_param('s',  $id);
            if ($stm->execute())
            {
                $stm->bind_result($cpf, $nomeCliente, $created, $idUsuario, $nomeUsuario);
                
                $return = array();
                 while ($stm->fetch()) {
                     $v = array();
                     $v['cpf'] = $cpf;
                     $v['nomeCliente'] = $nomeCliente;
                     $v['created'] = Utils::formatStringDate($created, 'Y-m-d H:i:s', 'd/m/Y H:i:s'); ;
                     $v['idUsuario'] = $idUsuario;
                     $v['nomeUsuario'] = $nomeUsuario;
                     array_push($return, $v);           
                 }
            }
            
        }
         else
        {
            \Application::setMysqlLogQuery('Classe Banco; Método clientesNaoAgendados; Mysql '. $connection->error); 
             $this->mysqlError = $connection->errno;
        }
        return $return;
        
    }
    
    
     public function excluir($cpf)
     {
        $return = false;
        
        $connection = \Application::getNewDataBaseInstance();

        $query = "delete from clientes where cpf_cnpj = ?";

         if ($stm = $connection->prepare($query))
         {
                $stm->bind_param('s', $cpf);
                if ($stm->execute())
                {
                        $return = true;
                }
                 else
                 {
                     \Application::setMysqlLogQuery('Classe Cliente; Método excluir; Mysql '. $connection->error); 
                        $this->errorCode = $connection->errno;
                 }
         }
         else
        {
            \Application::setMysqlLogQuery('Classe Cliente; Método excluir; Mysql '. $connection->error); 
             $this->errorCode = $connection->errno;
        }
        return $return;
        
     }
    
    
    
    

}
?>