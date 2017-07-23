<?php
namespace Gauchacred\model;


/**
 * @author moises
 * @version 1.0
 * @created 10-nov-2015 16:24:32
 */

use Gauchacred\library\php\MySqlError as MySqlError;
use Gauchacred\library\php\Utils;
    
    
class Telemarketing implements MySqlError
{

	private $errorCode = '';

    
    public function __construct()
    {
        
    }
    
    public function getMysqlError()
    {
        return $this->errorCode;
    }
    
    
     public function importar($nomeImportacao, $convenio, $usuarios, $file)
    {
        $return = false;

        $connection = \Application::getNewDataBaseInstance();
        $connection->autocommit(false);
         
         $fileName = $file['fileName'];
         $systemFileName = $file['systemFileName'];
         $totalClientes = count($file['content']);
         $query = '
            INSERT INTO telemarketing_importacao( entidade_id ,totalclientesimportados , nomeimportacao ,created ,filename ,systemfilename) VALUES ( ?, ?, ?, (select now()), ?, ?  );
         ';
         
         $idImportacao = null;
        // Gravar importacao
         if ($stm = $connection->prepare($query))
         {
            $stm->bind_param('iisss', $convenio, $totalClientes, $nomeImportacao, $fileName, $systemFileName);
            if ($stm->execute())
            {
                $idImportacao = $connection->insert_id;
            }
             else
             {
                 \Application::setMysqlLogQuery('Classe Telemarketing; Método importar - arquivo; Mysql '. $connection->error); 
                    $this->errorCode = $connection->errno;
             }

         }
         else
        {
            \Application::setMysqlLogQuery('Classe Banco; Telemarketing importar - arquivo; Mysql '. $connection->error); 
             $this->errorCode = $connection->errno;
        }
         
         
        
        if ($idImportacao == null)
        {
            $connection->rollback();
            return false;
        }
         
         // GRAVA USUARIOS CASO EXISTAM
         if (is_array($usuarios) )
         {
             
             $query = '
                INSERT INTO telemarketing_usuariossorteio(usuarios_id  ,telemarketing_importacao_id) VALUES (?, ?);
             ';
             
              if ($stm = $connection->prepare($query))
              {
                    $stm->bind_param('ii', $idUsuario, $idImportacao);
                    foreach($usuarios as $idUsuario)
                    {
                        if (! $stm->execute())
                         {
                             \Application::setMysqlLogQuery('Classe Telemarketing; Método importar - usuario sorteio ; Mysql '. $connection->error); 
                                $this->errorCode = $connection->errno;
                         }
                        
                    }
                    
              }
              else
              {
                 \Application::setMysqlLogQuery('Classe Banco; Telemarketing importar - usuario sorteio; Mysql '. $connection->error); 
                 $this->errorCode = $connection->errno;
              }
             
         }
         
         
         
         // GRAVA CLIENTES
         
          if (is_array($file['content']))
          {
              $query = '
                INSERT INTO telemarketing_clientes(   telemarketing_importacao_id  ,cpf  ,nome  ,nascimento  ,dadosextras ) VALUES (?, ?, ?, ?, ? );
             ';
             
              if ($stm = $connection->prepare($query))
              {
                    $stm->bind_param('issss', $idImportacao, $cpf, $nome, $nascimento, $dadosExtras);
                    foreach($file['content'] as $i => $cliente)
                    {
                        $cpf = $cliente['dados']['cpf'];
                        $nome = $cliente['dados']['nome'];
                        $nascimento = $cliente['dados']['nascimento'];
                        $dadosExtras = $cliente['dados']['dadosExtra'];
                        
                        if (! $stm->execute())
                         {
                             \Application::setMysqlLogQuery('Classe Telemarketing; Método importar - cliente linha '. $i .'; Mysql '. $connection->error); 
                             $this->errorCode = $connection->errno;
                             $connection->rollback();
                             return false;
                         }else
                         {
                            $idCliente = $connection->insert_id;
                            // grava os telefones do cliente
                            $queryFone = 'INSERT INTO telemarketing_telefoneclientes( telemarketing_clientes_id  ,telefone) VALUES (?, ?)';
                             if ($stm2 = $connection->prepare($queryFone))
                             {
                                 $stm2->bind_param('is', $idCliente, $telefone);
                                 if (is_array($cliente['telefones']))
                                     foreach($cliente['telefones']  as $col => $telefone )
                                     {
                                         if (! $stm2->execute())
                                         {
                                             \Application::setMysqlLogQuery('Classe Telemarketing; Método importar - cliente linha '. $i .' coluna '. ($col + 1) . '; Mysql '. $connection->error); 
                                             $this->errorCode = $connection->errno;
                                         }
                                     }
                             } else
                             {
                                 \Application::setMysqlLogQuery('Classe Banco; Telemarketing importar - telefone client linha '. $i .' coluna '. ($col + 1) .'; Mysql '. $connection->error); 
                                 $this->errorCode = $connection->errno;
                             }
                         }
                        
                        
                    }
                    
              }
              else
              {
                 \Application::setMysqlLogQuery('Classe Banco; Telemarketing importar - cliente; Mysql '. $connection->error); 
                 $this->errorCode = $connection->errno;
              }
          }
        if (is_array($file['content']) && count($file['content']) > 0 )
            $connection->commit();
        return true;
        
    }
    
    
    
     public function listarStatus($id = null)
    {
        
        $id = ($id === null) ? '%' : $id;
         
        $return = false;
        
        $connection = \Application::getNewDataBaseInstance();
        $query = "
               SELECT id, descricao FROM telemarketing_statusagenda where id like ? order by descricao ;
        ";
       
        
         if ($stm = $connection->prepare($query))
        {
            $stm->bind_param('s',  $id);
            if ($stm->execute())
            {
                $stm->bind_result($id, $descricao  );
                
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
            \Application::setMysqlLogQuery('Classe Telemarketing; Método listarStaus; Mysql '. $connection->error); 
             $this->errorCode = $connection->errno;
        }
        return $return;
        
    }
    
    /*
    * @param Integer ID da Importação
    *
    **/
    
     public function listarRelatorio($id)
    {
        
        $id = ($id === null) ? null : $id;
         
        $return = false;
        
        $connection = \Application::getNewDataBaseInstance();
        $query = "
               select distinct
                u.nome as 'usuario', u.email as 'email', count(c.id) as 'clientescomfoco',
                count(case when c.telemarketing_statusagenda_id is not null then 1 else null end) as 'clientestrabalhados',
                count(case when c.telemarketing_statusagenda_id is null then 1 else null end) as 'clientespendentes',
                count(case when c.telemarketing_statusagenda_id = 1 then 1 else null end) as 'Seminteresse',
                count(case when c.telemarketing_statusagenda_id = 2 then 1 else null end) as 'naoatendeu',
                count(case when c.telemarketing_statusagenda_id = 3 then 1 else null end) as 'numeroerrado',
                count(case when c.telemarketing_statusagenda_id = 4 then 1 else null end) as 'agendar',
                count(case when c.telemarketing_statusagenda_id = 5 then 1 else null end) as 'margemnegativa',
                count(case when c.telemarketing_statusagenda_id = 6 then 1 else null end) as 'naocontatado',
                min(f.created) as 'inicio', max(f.modified) as 'ultimaentradabase'

                from telemarketing_importacao i
                  inner join telemarketing_clientes c on c.telemarketing_importacao_id = i.id
                  inner join telemarketing_usuariosfoco f on f.telemarketing_clientes_id  = c.id
                  inner join usuarios u on u.id = f.usuarios_id

                where i.id = ?

                group by u.id
        ";
       
        
         if ($stm = $connection->prepare($query))
        {
            $stm->bind_param('i',  $id);
            if ($stm->execute())
            {
                $stm->bind_result($nome, $email, $clientesComFoco, $clientesTrabalhados, $clientesPendentes, $semInteresse, $naoAtendeu, $numeroErrado, $agendar, $margemNegativa, $naoContatado,
                                    $dataInicio, $ultimaEntradaBase);
                
                $return = array();
                 while ($stm->fetch()) {
                     $v = array();
                     $v['nome'] = $nome;
                     $v['email'] = $email;
                     $v['clientesComFoco'] = $clientesComFoco;
                     $v['clientesTrabalhados'] = $clientesTrabalhados;
                     $v['clientesPendentes'] = $clientesPendentes;
                     $v['semInteresse'] = $semInteresse;
                     $v['naoAtendeu'] = $naoAtendeu;
                     $v['numeroErrado'] = $numeroErrado;
                     $v['agendar'] = $agendar;
                     $v['margemNegativa'] = $margemNegativa;
                     $v['naoContatado'] = $naoContatado;
                     $v['dataInicio'] = Utils::formatStringDate($dataInicio, 'Y-m-d H:i:s', 'd/m/Y H:i:s');
                     $v['dataFim'] = Utils::formatStringDate($ultimaEntradaBase, 'Y-m-d H:i:s', 'd/m/Y H:i:s');
                    
                     array_push($return, $v);           
                 }
            }
            
        }
         else
        {
            \Application::setMysqlLogQuery('Classe Telemarketing; Método listarRelatorio; Mysql '. $connection->error); 
             $this->errorCode = $connection->errno;
        }
        return $return;
        
    }
    

    
    public function listarImportacao($id = '%')
    {
        
        $return = false;
        
        $connection = \Application::getNewDataBaseInstance();
        $query = "
                select distinct
                c.id as 'idconvenio' ,c.nome as 'nomeconvenio', i.id, i.totalclientesimportados, i.totalclientestrabalhados, 
                i.nomeimportacao, i.created, i.modified, i.filename, i.systemfilename

                from telemarketing_importacao i
                  inner join entidades c on c.id = i.entidade_id
                where i.id like ?
                order by i.id desc

        ";
       
        
         if ($stm = $connection->prepare($query))
        {
            $stm->bind_param('s',  $id);
            if ($stm->execute())
            {
                $stm->bind_result($idConvenio, $nomeConvenio, $id, $totalClientesImportados, $totalClientesTrabalhados, $nomeImportacao, $created, $modified, $fileName, $systemFileName);
                
                $return = array();
                 while ($stm->fetch()) {
                     $v = array();
                     $v['idConvenio'] = $idConvenio;
                     $v['nomeConvenio'] = $nomeConvenio;
                     $v['id'] = $id;
                     $v['totalClientesImportados'] = $totalClientesImportados;
                     $v['totalClientesTrabalhados'] = $totalClientesTrabalhados;
                     $v['nomeImportacao'] = $nomeImportacao;
                     $v['created'] = Utils::formatStringDate($created, 'Y-m-d H:i:s', 'd/m/Y H:i:s');
                     $v['modified'] = Utils::formatStringDate($modified, 'Y-m-d H:i:s', 'd/m/Y H:i:s');
                     $v['fileName'] = $fileName;
                     $v['systemFileName'] = $systemFileName;
                     array_push($return, $v);           
                 }
            }
            
        }
         else
        {
            \Application::setMysqlLogQuery('Classe Telemarketing; Método listarImportacao; Mysql '. $connection->error); 
             $this->errorCode = $connection->errno;
        }
        return $return;
        
    }
    
    
    
     public function listarUsuariosSorteio($id)
    {
        
        $return = false;
        
        $connection = \Application::getNewDataBaseInstance();
        $query = "
                select distinct
                    u.usuarios_id
                    from telemarketing_usuariossorteio u
                    where u.telemarketing_importacao_id = ?

        ";
       
        
         if ($stm = $connection->prepare($query))
        {
            $stm->bind_param('i',  $id);
            if ($stm->execute())
            {
                $stm->bind_result($idUsuario);
                
                $return = array();
                 while ($stm->fetch()) {
                     $v = array();
                     $v['idUsuario'] = $idUsuario;
                     
                     array_push($return, $v);           
                 }
            }else
            {
                \Application::setMysqlLogQuery('Classe Telemarketing; Método listarUsuariosSorteio; Mysql '. $connection->error); 
                 $this->errorCode = $connection->errno;
            }
            
        }
         else
        {
            \Application::setMysqlLogQuery('Classe Telemarketing; Método listarUsuariosSorteio; Mysql '. $connection->error); 
             $this->errorCode = $connection->errno;
        }
        return $return;
        
    }
    
    
    
     public function listarClienteFoco($idImportacao, $idUsuario)
    {
        
        $return = false;
        
        $connection = \Application::getNewDataBaseInstance();
        $query = "
                select distinct
                c.id, c.telemarketing_importacao_id, c.telemarketing_statusagenda_id, c.cpf, c.nome, c.nomeconvenio, c.nascimento,
                TIMESTAMPDIFF(YEAR, c.nascimento, (select now())) as 'idade', c.dadosextras, 
                c.tipocliente, c.dataligacao, c.observacoes, c.lock_telemarketing_usuariofoco_id, tel.telefone,
                i.nomeimportacao, i.created,
                e.id as 'idconvenioimportacao', e.nome as 'nomeconvenioimportacao', c.telemarketing_statusagenda_id

                from telemarketing_clientes c
                  inner join telemarketing_importacao i on i.id = c.telemarketing_importacao_id
                  inner join telemarketing_usuariosfoco fo on fo.telemarketing_clientes_id = c.id 
                  left join entidades e on e.id = i.entidade_id
                  left join (
                    select distinct
                     t.telemarketing_clientes_id, group_concat( t.telefone, ',', t.telefonecerto SEPARATOR ';') as 'telefone'
                    from telemarketing_telefoneclientes t
                     group by t.telemarketing_clientes_id
                  ) tel on tel.telemarketing_clientes_id = c.id

                where fo.focoativo = 1
                and c.telemarketing_importacao_id = ?
                and c.lock_telemarketing_usuariofoco_id = ?
                and c.telemarketing_statusagenda_id is null
                order by c.id limit 1

        ";
       
        
         if ($stm = $connection->prepare($query))
        {
            $stm->bind_param('ii',  $idImportacao, $idUsuario);
            if ($stm->execute())
            {
                $stm->bind_result($idCliente, $idImportacao, $idStatusAgenda, $cpf, $nomeCliente, $nomeConvenio, $nascimento, $idade, $dadosExtras, $tipoCliente, $dataLigacao, $observacoes,
                                 $idUsuarioLock, $telefone, $nomeImportacao, $dataImportacao, $idConvenioImportacao, $nomeConvenioImportacao, $status  );
                
                $return = array();
                 while ($stm->fetch()) {
                     $v = array();
                     $v['idCliente'] = $idCliente;
                     $v['idImportacao'] = $idImportacao;
                     $v['idStatusAgenda'] = $idStatusAgenda;
                     $v['cpf'] = $cpf;
                     $v['nomeCliente'] = $nomeCliente;
                     $v['nomeConvenio'] = $nomeConvenio;
                     $v['nascimento'] = Utils::formatStringDate($nascimento, 'Y-m-d', 'd/m/Y');
                     $v['idade'] = $idade;
                     $v['dadosExtras'] = $dadosExtras;
                     $v['tipoCliente'] = $tipoCliente;
                     $v['dataLigacao'] = Utils::formatStringDate($dataLigacao, 'Y-m-d H:i:s', 'd/m/Y H:i:s');
                     $v['observacoes'] = $observacoes;
                     $v['idUsuarioLock'] = $idUsuarioLock;
                     $v['nomeImportacao'] = $nomeImportacao;
                     $v['dataimportacao'] = Utils::formatStringDate($dataImportacao, 'Y-m-d H:i:s', 'd/m/Y H:i:s');
                     $v['idConvenioImportacao'] = $idConvenioImportacao;
                     $v['nomeConvenioImportacao'] = $nomeConvenioImportacao;
                     $v['status'] = $status;
                     $v['telefones'] = array();
                     $aux = explode(';', $telefone);
                   
                     
                     if (is_array($aux))
                         foreach($aux as $fones)
                         {
                             $aux2 = explode(',', $fones);
                             $fone = (isset($aux2[0])) ? $aux2[0] : null;
                             $certo = (isset($aux2[1])) ? $aux2[1] : null;
                             if ($fone != null)
                                 array_push($v['telefones'], array('numero' => $fone, 'certo' => $certo));
                         }
                   
                     array_push($return, $v);           
                 }
            }
            
        }
         else
        {
            \Application::setMysqlLogQuery('Classe Telemarketing; Método listarClienteFoco; Mysql '. $connection->error); 
             $this->errorCode = $connection->errno;
        }
        return $return;
        
    }
    

    
     public function atribuirUsuarios($id, $usuarios)
    {
        $return = false;

        $connection = \Application::getNewDataBaseInstance();
        $connection->autocommit(false);
        
        $query = 'DELETE FROM telemarketing_usuariossorteio WHERE telemarketing_importacao_id = ? ;';

         if ($stm = $connection->prepare($query))
         {
            $stm->bind_param('i',  $id);

            if ($stm->execute())
            {
                $return = true;
            }
             else
             {
                 \Application::setMysqlLogQuery('Classe Telemarketing; Método atribuirUsuarios - DELETE; Mysql '. $connection->error); 
                $this->errorCode = $connection->errno;
             }

         }
         else
        {
            \Application::setMysqlLogQuery('Classe Banco; Telemarketing atribuirUsuarios - DELETE; Mysql '. $connection->error); 
             $this->errorCode = $connection->errno;
        }
        
        
        // SE o DELETE OCORREU NORMALMENTE
        if ($return === true)
        {
            $query = 'INSERT INTO telemarketing_usuariossorteio( usuarios_id  ,telemarketing_importacao_id) VALUES ( ?, ? );';

             if ($stm = $connection->prepare($query))
             {
                $stm->bind_param('ii',  $idUsuario, $id);
                if (is_array($usuarios))
                     foreach($usuarios as $idUsuario)
                     {
                        if ($stm->execute())
                        {
                            $return = true;
                        }
                         else
                         {
                             \Application::setMysqlLogQuery('Classe Telemarketing; Método atribuirUsuarios - INSERT; idUsuario = '. $idUsuario . ' idImportacao = ' . $id . ' ; Mysql '. $connection->error); 
                             $this->errorCode = $connection->errno;
                             $return = false;
                             break;
                         }
                     }
             }
             else
            {
                \Application::setMysqlLogQuery('Classe Banco; Telemarketing atribuirUsuarios - INSERT; Mysql '. $connection->error); 
                 $this->errorCode = $connection->errno;
            }
         }
        
        if ($return === true)
            $connection->commit();
        else
            $connection->rollback();
        
        return $return;
        
    }
    
    
    
    
    public function distribuirFoco($id )
    {
        
        $return = false;

        $connection = \Application::getNewDataBaseInstance();
        $connection->autocommit(false);
        
        
        // PEGA OS USUARIOS PARA SEREM SORTEADOS A FIM DE SABER QUAIS SÃO E QUANTOS SÃO
        // POSTERIORMENTE OS CLIENTES QUE NÃO ESTÃO SENDO FOCADOS SERÃO SELECIONADOS DE ACORDO COM ESTA QUANTIDADE
        
        $query = "
            SELECT distinct s.id, s.usuarios_id, s.telemarketing_importacao_id 
            FROM telemarketing_usuariossorteio s 
            where s.telemarketing_importacao_id = ? 
            and not exists (
              select fo.id 
              from telemarketing_usuariosfoco fo 
                inner join telemarketing_clientes c on c.id = fo.telemarketing_clientes_id
              where fo.focoativo = 1 and fo.usuarios_id = s.usuarios_id  and c.telemarketing_importacao_id = s.telemarketing_importacao_id
            )
            order by id;
        ";
        
        $usuariosSorteio = array();
        if ($stm = $connection->prepare($query))
        {
            $stm->bind_param('i',  $id);

            if ($stm->execute())
            {
                $stm->bind_result($usersortId, $usersortIdUsuario, $usersortIdImportacao);
                
                 while ($stm->fetch()) {
                     $v = array();
                     $v['id'] = $usersortId;
                     $v['idUsuario'] = $usersortIdUsuario;
                     $v['idImportacao'] = $usersortIdImportacao;
                     array_push($usuariosSorteio, $v);           
                 }
            }
            else
            {
                 \Application::setMysqlLogQuery('Classe Telemarketing; Método distribuirFoco - SELECT USUARIOSORTEIO; Mysql '. $connection->error); 
                $this->errorCode = $connection->errno;
            }

         }
         else
         {
            \Application::setMysqlLogQuery('Classe Telemarketing; Método distribuirFoco - SELECT USUARIOSORTEIO; Mysql '. $connection->error); 
             $this->errorCode = $connection->errno;
         }
        
       
        if(count($usuariosSorteio) < 1)
            return -3; // -3 = NÂO EXISTEM USUARIOS PARA SORTEAR
        
       
        // ANTES DE PEGAR OS PROXIMOS CLIENTES QUE ESTÃO SEM LOCK NO FOCO, EXECUTA UM LOCK NA TABELA PARA EVITAR CONCORRENCIA
        
        $result = $connection->query('LOCK TABLES telemarketing_clientes  WRITE');
        if ($result == false)
        {
            
            \Application::setMysqlLogQuery('Classe Telemarketing; Método distribuirFoco - LOCK TABELA CLIENTES; Mysql '. $connection->error); 
             $this->mysqlError = $connection->errno;
            return -2; // NÂO FOI POSSIVEL OBTER O LOCK DA TABELA DE CLIENTES
        }
        
                
        // REALIZA A CONSULTA DOS PRÓXIMOS CLIENTES SEM FOCO (LOCK)
        $limitClientes = count($usuariosSorteio);
        
        $query = "
            SELECT id, telemarketing_importacao_id 
            FROM telemarketing_clientes where lock_telemarketing_usuariofoco_id is null and telemarketing_importacao_id = ? and telemarketing_statusagenda_id is null order by id limit ? ;
        ";
        
        $clientesSemLock = array();
        if ($stm = $connection->prepare($query))
        {
            $stm->bind_param('ii',  $id, $limitClientes);

            if ($stm->execute())
            {
                $stm->bind_result($cId, $cIdImportacao);
                
                 while ($stm->fetch()) {
                     $v = array();
                     $v['id'] = $cId;
                     $v['idImportacao'] = $cIdImportacao;
                     array_push($clientesSemLock, $v);           
                 }
            }
            else
            {
                 \Application::setMysqlLogQuery('Classe Telemarketing; Método distribuirFoco - SELECT CLIENTES SEM LOCK; Mysql '. $connection->error); 
                $this->errorCode = $connection->errno;
            }

         }
         else
         {
            \Application::setMysqlLogQuery('Classe Telemarketing; Método distribuirFoco - SELECT CLIENTES SEM LOCK; Mysql '. $connection->error); 
             $this->errorCode = $connection->errno;
         }
        
        
         // SE NÃO EXISTIR CLIENTES SEM LOCK EXECUTA O UNLOCK DA TABELA
        if (count($clientesSemLock) < 1)
        {
            $result = $connection->query('UNLOCK TABLES');
            return -1; // -1 NÃO EXISTEM CLIENTES PARA FOCAR
        }
        
        
        // INICIA A TRANSAÇÃO
        $connection->begin_transaction();  
       
        
       
        
        
        // REALIZA O SORTEIO DO FOCO
        $positionArrayCliente = 0;
        $result = true;
        if (is_array($usuariosSorteio))
            foreach($usuariosSorteio as $usuario)
            {
                if (! isset($clientesSemLock[$positionArrayCliente] ))
                    break;
                else
                    $cliente = $clientesSemLock[$positionArrayCliente];
                
                // atualiza tabela de clientes
                $query = "
                    UPDATE telemarketing_clientes SET lock_telemarketing_usuariofoco_id = ? WHERE id = ?;
                ";
                
                if ($stm = $connection->prepare($query))
                {
                    $stm->bind_param('ii', $usuario['idUsuario'], $cliente['id']);

                    if (! $stm->execute())
                    {
                        \Application::setMysqlLogQuery('Classe Telemarketing; Método distribuirFoco - UPDATE CLIENTES; Mysql '. $connection->error); 
                        $this->errorCode = $connection->errno;
                        $connection->query('UNLOCK TABLES');
                        return false;

                    }

                 }
                 else
                 {
                    \Application::setMysqlLogQuery('Classe Telemarketing; Método distribuirFoco - UPDATE CLIENTES; Mysql '. $connection->error); 
                     $this->errorCode = $connection->errno;
                     $connection->query('UNLOCK TABLES');
                     return false;
                 }
                
                
                
                // atualiza tabela usuariofoco
                
                $query = "
                    INSERT INTO telemarketing_usuariosfoco( usuarios_id ,telemarketing_clientes_id  ,focoativo)
                    VALUES (?, ?, 1)
                ";
                
                if ($stm = $connection->prepare($query))
                {
                    $stm->bind_param('ii', $usuario['idUsuario'], $cliente['id']);

                    if (! $stm->execute())
                    {
                        \Application::setMysqlLogQuery('Classe Telemarketing; Método distribuirFoco - INSERT USUARIOFOCO; Mysql '. $connection->error); 
                        $this->errorCode = $connection->errno;
                        $connection->query('UNLOCK TABLES');
                        return false;

                    }

                 }
                 else
                 {
                    \Application::setMysqlLogQuery('Classe Telemarketing; Método distribuirFoco - INSERT USUARIOFOCO; Mysql '. $connection->error); 
                     $this->errorCode = $connection->errno;
                     $connection->query('UNLOCK TABLES');
                     return false;
                 }
                
                
                $positionArrayCliente++;
            }
        
        
        $connection->query('UNLOCK TABLES');
        $connection->commit();
        return true;
        
        
    }
    
    
    
    public function salvarCliente($idCliente, $tipoCliente = null, $status = null, $observacoes = null, $dataLigacao = null, $telefones = null)
    {
        
        
        $return = false;

        $connection = \Application::getNewDataBaseInstance();
        $connection->autocommit(false);
        $connection->begin_transaction(); 
        
        // ATUALIZA OS CLIENTES
        $query = "
                    UPDATE telemarketing_clientes
                    SET tipocliente = ? ,dataligacao = ? ,observacoes = ?, telemarketing_statusagenda_id = ? WHERE id = ?
        ";

        if ($stm = $connection->prepare($query))
        {
            $stm->bind_param('sssii', $tipoCliente, $dataLigacao, $observacoes, $status, $idCliente);

            if (! $stm->execute())
            {
                \Application::setMysqlLogQuery('Classe Telemarketing; Método salvarCliente - UPDATE CLIENTES; Mysql '. $connection->error); 
                $this->errorCode = $connection->errno;

            }else
                $return = true;

        }
        else
        {
            \Application::setMysqlLogQuery('Classe Telemarketing; Método salvarCliente - UPDATE CLIENTES; Mysql '. $connection->error); 
             $this->errorCode = $connection->errno;
        }
        
        // ATUALIZA O TELEFONE DOS CLIENTES
        
        $query = "
                 DELETE FROM telemarketing_telefoneclientes WHERE  telemarketing_clientes_id = ? ;
        ";

        if ($stm = $connection->prepare($query))
        {
            $stm->bind_param('i', $idCliente);

            if (! $stm->execute())
            {
                \Application::setMysqlLogQuery('Classe Telemarketing; Método salvarCliente - DELETE TELEFONE; Mysql '. $connection->error); 
                $this->errorCode = $connection->errno;
                $return = false;

            }else
                $return = true;

        }
        else
        {
            \Application::setMysqlLogQuery('Classe Telemarketing; Método salvarCliente - DELETE TELEFONE; Mysql '. $connection->error); 
             $this->errorCode = $connection->errno;
            $return = false;
        }
        
        if ($return == false)
        {
            $connection->rollback();
            return false;
        }
        
        $query = "
                 INSERT INTO telemarketing_telefoneclientes(   telemarketing_clientes_id  ,telefone  ,telefonecerto) VALUES (?,?,?);
        ";

        if ($stm = $connection->prepare($query))
        {
            $stm->bind_param('isi', $idCliente, $numeroTelefone, $telefoneCerto);
            if (is_array($telefones))
                foreach($telefones as $telefone)
                {
                    $numeroTelefone = $telefone['telefone'];
                    $telefoneCerto = $telefone['certo'];
                    if (! $stm->execute())
                    {
                        \Application::setMysqlLogQuery('Classe Telemarketing; Método salvarCliente - INSERT TELEFONE NUMERO '. $numeroTelefone . '; Mysql '. $connection->error); 
                        $this->errorCode = $connection->errno;
                        $return = false;

                    }else
                        $return = true;
                    
                }
            

        }
        else
        {
            \Application::setMysqlLogQuery('Classe Telemarketing; Método salvarCliente - DELETE TELEFONE; Mysql '. $connection->error); 
             $this->errorCode = $connection->errno;
            $return = false;
        }
        
        if ($return == false)
        {
            $connection->rollback();
            return false;
        }
        
        
        
        // REALIZA AGENDAMENTO SE houver data de ligação
        if ($dataLigacao !== null)
        {
            $idAgenda = null;
            $query = "
                    INSERT INTO agenda(entidades_id  ,usuarios_id  ,cpf  ,nome  ,datanascimento  ,created  ,dataligacao  ,tipocliente  ,observacoes  ,status) 
                    select 
                    i.entidade_id, ?, c.cpf, c.nome, c.nascimento, i.created, c.dataligacao, c.tipocliente, c.observacoes, 'Pendente'
                    from telemarketing_clientes c
                      inner join telemarketing_importacao i on i.id = c.telemarketing_importacao_id
                    where c.id = ?
            ";

            if ($stm = $connection->prepare($query))
            {
                $stm->bind_param('ii', $_SESSION['userid'], $idCliente);

                if (! $stm->execute())
                {
                    \Application::setMysqlLogQuery('Classe Telemarketing; Método salvarCliente - INSERT AGENDA; Mysql '. $connection->error); 
                    $this->errorCode = $connection->errno;
                    $return = false;

                }else
                {
                    $idAgenda = $connection->insert_id;
                    $return = true;
                }

            }
            else
            {
                \Application::setMysqlLogQuery('Classe Telemarketing; Método salvarCliente - INSERT AGENDA; Mysql '. $connection->error); 
                 $this->errorCode = $connection->errno;
                $return = false;
            }
            
            // Adiciona os telefones
            if ($idAgenda != null)
            {
                $query = "
                       INSERT INTO telefonesagenda(agenda_id ,numero)
                       select distinct ?, telefone from telemarketing_telefoneclientes where telefonecerto = 1 and telemarketing_clientes_id = ?;
                ";

                if ($stm = $connection->prepare($query))
                {
                    $stm->bind_param('ii', $idAgenda, $idCliente);

                    if (! $stm->execute())
                    {
                        \Application::setMysqlLogQuery('Classe Telemarketing; Método salvarCliente - INSERT TELEFONE AGENDA; Mysql '. $connection->error); 
                        $this->errorCode = $connection->errno;
                        $return = false;

                    }else
                    {
                        $idAgenda = $connection->insert_id;
                        $return = true;
                    }

                }
                else
                {
                    \Application::setMysqlLogQuery('Classe Telemarketing; Método salvarCliente - INSERT TELEFONE AGENDA; Mysql '. $connection->error); 
                     $this->errorCode = $connection->errno;
                    $return = false;
                }
            }
            
        }
        
        
        // ATUALIZA A LISTA DE FOCO MARCANDO ENCERRADO
        if ($return == false)
        {
            $connection->rollback();
            return false;
        }
        
        if ($status != null)
        {
            $query = "
                        UPDATE telemarketing_usuariosfoco
                        SET focoativo = 0 WHERE usuarios_id = ? and telemarketing_clientes_id = ?
            ";

            if ($stm = $connection->prepare($query))
            {
                $stm->bind_param('ii', $_SESSION['userid'], $idCliente);

                if (! $stm->execute())
                {
                    \Application::setMysqlLogQuery('Classe Telemarketing; Método salvarCliente - UPDATE USUARIOSFOCO; Mysql '. $connection->error); 
                    $this->errorCode = $connection->errno;
                    $return = false;
                }else
                    $return = true;

            }
            else
            {
                \Application::setMysqlLogQuery('Classe Telemarketing; Método salvarCliente - UPDATE USUARIOSFOCO; Mysql '. $connection->error); 
                 $this->errorCode = $connection->errno;
            }
        }
        
        // altera o total de clientes trabalhados
        if ($return == false)
        {
            $connection->rollback();
            return false;
        }
        
        if ($status != null)
        {
            $query = "
                        UPDATE telemarketing_importacao i
                          inner join telemarketing_clientes c on c.telemarketing_importacao_id = i.id
                        SET   i.totalclientestrabalhados = (i.totalclientestrabalhados + 1) 
                        WHERE c.id = ?
            ";

            if ($stm = $connection->prepare($query))
            {
                $stm->bind_param('i', $idCliente);

                if (! $stm->execute())
                {
                    \Application::setMysqlLogQuery('Classe Telemarketing; Método salvarCliente - UPDATE IMPORTACAO; Mysql '. $connection->error); 
                    $this->errorCode = $connection->errno;
                    $return = false;
                }else
                    $return = true;

            }
            else
            {
                \Application::setMysqlLogQuery('Classe Telemarketing; Método salvarCliente - UPDATE IMPORTACAO; Mysql '. $connection->error); 
                 $this->errorCode = $connection->errno;
            }
        }
        
        if ($return == false)
            $connection->rollback();
        else
            $connection->commit();
        
        return $return;
        
        
        
    }
    
    
    
    
     public function reprocessar($id)
     {
        $return = false;
        
        $connection = \Application::getNewDataBaseInstance();
        $connection->autocommit(false);
        $connection->begin_transaction(); 
        
        // ATUALIZA CLIENTES LIBERANDO O FOCO E VOLTANDO STATUS
        $query = "UPDATE telemarketing_clientes SET   telemarketing_statusagenda_id = null  ,lock_telemarketing_usuariofoco_id = null WHERE telemarketing_importacao_id = ?";

         if ($stm = $connection->prepare($query))
         {
                $stm->bind_param('i', $id);
                if ($stm->execute())
                {
                        $return = true;
                }
                 else
                 {
                     \Application::setMysqlLogQuery('Classe Entidade; Método reprocessar - CLIENTES; Mysql '. $connection->error); 
                        $this->errorCode = $connection->errno;
                 }
         }
         else
        {
            \Application::setMysqlLogQuery('Classe Banco; Entidade reprocessar - CLIENTES; Mysql '. $connection->error); 
             $this->errorCode = $connection->errno;
        }
         
        if ($return == true)
        {
            // ATUALIZA CLIENTES LIBERANDO O FOCO E VOLTANDO STATUS
            $query = "UPDATE telemarketing_usuariosfoco u 
                  inner join telemarketing_clientes tc on tc.id = u.telemarketing_clientes_id
                SET  u.focoativo = 0
                WHERE u.focoativo = 1 and tc.telemarketing_importacao_id = ?";

             if ($stm = $connection->prepare($query))
             {
                    $stm->bind_param('i', $id);
                    if ($stm->execute())
                    {
                            $return = true;
                    }
                     else
                     {
                         \Application::setMysqlLogQuery('Classe Telemarketing; Método reprocessar - USUARIOSFOCO; Mysql '. $connection->error); 
                         $this->errorCode = $connection->errno;
                         $return = false;
                     }
             }
             else
            {
                \Application::setMysqlLogQuery('Classe Telemarketing; Método reprocessar - USUARIOSFOCO; Mysql '. $connection->error); 
                 $this->errorCode = $connection->errno;
                 $return = false;
            }
            
            if ($return == false)
            {
                $connection->rollback();
                return false;
            }
            
            
            // ATUALIZA TOTAL DE CLIENTES TRABALHADOS DA IMPORTAÇÂO
            $query = "UPDATE telemarketing_importacao SET  totalclientestrabalhados = 0 WHERE id = ?";

             if ($stm = $connection->prepare($query))
             {
                    $stm->bind_param('i', $id);
                    if ($stm->execute())
                    {
                            $return = true;
                    }
                     else
                     {
                         \Application::setMysqlLogQuery('Classe Telemarketing; Método reprocessar - IMPOORTACAO; Mysql '. $connection->error); 
                         $this->errorCode = $connection->errno;
                         $return = false;
                     }
             }
             else
            {
                \Application::setMysqlLogQuery('Classe Telemarketing; Método reprocessar - IMPORTACAO; Mysql '. $connection->error); 
                 $this->errorCode = $connection->errno;
                 $return = false;
            }
            
            
            
            
            
        }
         
        if ($return == true)
            $connection->commit();
        else
            $connection->rollback();
         
         
        return $return;
        
     }
    
    
    
    
    
    
    public function excluir($id)
     {
        $return = false;
        
        $connection = \Application::getNewDataBaseInstance();
        
        $query = "DELETE FROM telemarketing_importacao WHERE id = ?";

         if ($stm = $connection->prepare($query))
         {
                $stm->bind_param('i', $id);
                if ($stm->execute())
                {
                        $return = true;
                }
                 else
                 {
                     \Application::setMysqlLogQuery('Classe Telemarketing; Método excluir; Mysql '. $connection->error); 
                        $this->errorCode = $connection->errno;
                 }
         }
         else
        {
            \Application::setMysqlLogQuery('Classe Banco; Telemarketing excluir; Mysql '. $connection->error); 
             $this->errorCode = $connection->errno;
        }
        return $return;
        
     }
    
    
    
    
    
    

}
?>