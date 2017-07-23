<?php
namespace Gauchacred\model;


/**
 * @author moises
 * @version 1.0
 * @created 10-nov-2015 16:24:32
 */

use Gauchacred\library\php\MySqlError as MySqlError;
use Gauchacred\library\php\Utils as Utils;

class GadgetsHome implements MySqlError
{

	private $errorCode = '';
    
    public function __construct()
    {
        
    }
    
    public function getMysqlError()
    {
        return $this->errorCode;
    }

    
    public function metaMensal($idUsuario)
    {
        
       
        
        $return = false;
        
        $connection = \Application::getNewDataBaseInstance();
        $query = "
                select distinct
                    m.id, m.prazo, m.created, m.valor, m.modified,
                    us.id as 'idusuario', us.cpf, us.nome as 'nomeusuario', us.status
                    from metas m
                      inner join usuarios us on us.id = m.usuarios_id

                    where m.usuarios_id = ?
                    and month(m.prazo) = month(now()) and year(m.prazo) = year(now())
                    order by id desc
                    limit 1
        ";
       
        
         if ($stm = $connection->prepare($query))
        {
            $stm->bind_param('i',  $idUsuario);
            if ($stm->execute())
            {
                $stm->bind_result($id, $prazo, $created, $valor, $modified, $idUsuario, $cpfUsuario, $nomeUsuario, $statusUsuario);
                
                $return = array();
                 while ($stm->fetch()) {
                     $v = array();
                     $v['id'] = $id;
                     $v['prazo'] = Utils::formatStringDate($prazo, 'Y-m-d', 'd/m/Y');  
                     $v['created'] = Utils::formatStringDate($created, 'Y-m-d H:i:s', 'd/m/Y H:i:s'); 
                     $v['valor'] = $valor;
                     $v['modified'] = Utils::formatStringDate($modified, 'Y-m-d H:i:s', 'd/m/Y H:i:s'); 
                     $v['idUsuario'] = $idUsuario;
                     $v['cpfUsuario'] = $cpfUsuario;
                     $v['nomeUsuario'] = $nomeUsuario;
                     $v['statusUsuario'] =  $statusUsuario;
                     array_push($return, $v);           
                 }
            }
            
        }
         else
        {
            \Application::setMysqlLogQuery('Classe GadgetsHome; Método metaMensal; Mysql '. $connection->error); 
             $this->mysqlError = $connection->errno;
        }
        return $return;
        
    }
    
    
    public function comissaoSemanalNovo($idUsuario = null)
    {
        
        $return = false;
        $connection = \Application::getNewDataBaseInstance();
        
        $idUsuario = ($idUsuario === null) ? '%' : $idUsuario;
        
        $query = "
               select 
               sum(c.valortotal * (cc.percentualgrupo/100))  + sum(c.valortotal * (cc.percentualsupervisor/100)) as 'valor'
                from comissoescontrato cc
                  inner join contratos c on c.id = cc.contratos_id

                where year(c.datapagamento) = year(now())
                and month(c.datapagamento) = month(now())
                and WEEK(c.datapagamento) = WEEK(now())
                and cc.usuarios_id like ?
                
        ";
       
        
         if ($stm = $connection->prepare($query))
        {
            $stm->bind_param('s',  $idUsuario);
            if ($stm->execute())
            {
                $stm->bind_result($valor);
                
                 if ($stm->fetch()) {
                     $return = $valor;
                 }
            }
            
        }
         else
        {
            \Application::setMysqlLogQuery('Classe GadgetsHome; Método comissaoSemanalNovo; Mysql '. $connection->error); 
             $this->mysqlError = $connection->errno;
        }
		
        return $return;
        
    }
    
    public function getMetasNovo()
    {
        
       
        
        $return = false;
        
        $connection = \Application::getNewDataBaseInstance();
        $query = "
                select distinct
                    m.usuarios_id, m.grupousuarios_id, m.dtinicio, m.prazo, m.tipometa, m.valor
                    from metas m
                    where now() between m.dtinicio and ADDDATE(  m.prazo , INTERVAL 1 DAY)
                    order by 1, 2, 4 desc
        ";
       
        
         if ($stm = $connection->prepare($query))
        {
           // $stm->bind_param('i',  $idUsuario);
            if ($stm->execute())
            {
                $stm->bind_result($idUsuario, $idGrupo, $dtInicio, $dtFim, $tipoMeta, $valor);
                
                $return = array();
                 while ($stm->fetch()) {
                     $v = array();
                     $v['idUsuario'] = $idUsuario;
                     $v['idGrupo'] = $idGrupo;
                     $v['dtInicio'] = Utils::formatStringDate($dtInicio, 'Y-m-d', 'd/m/Y'); 
                     $v['dtFim'] = Utils::formatStringDate($dtFim, 'Y-m-d', 'd/m/Y');  
                     $v['valor'] = $valor;
                     $v['tipoMeta'] = $tipoMeta;
                    
                     array_push($return, $v);           
                 }
            }
            
        }
         else
        {
            \Application::setMysqlLogQuery('Classe GadgetsHome; Método getMetasNovo; Mysql '. $connection->error); 
             $this->mysqlError = $connection->errno;
        }
        return $return;
        
    }
    
    
    
    
    public function totalVendasDia($idUsuario = null)
    {
        
       $idUsuario = ($idUsuario === null) ? '%' : $idUsuario;
        
        $return = false;
        
        $connection = \Application::getNewDataBaseInstance();
        $query = "
               select distinct
                sum(c.valortotal) as 'valor'
                from contratos c
                where year(c.created) = year(now()) and month(c.created) = month(now())
                and day(c.created) = day(now())
                and c.usuarios_id like ?
        ";
       
        
         if ($stm = $connection->prepare($query))
        {
            $stm->bind_param('s',  $idUsuario);
            if ($stm->execute())
            {
                $stm->bind_result($quantidade);
                
                $return = 0;
                 if ($stm->fetch())
                    $return = $quantidade;
                             
                 
            }
            
        }
         else
        {
            \Application::setMysqlLogQuery('Classe GadgetsHome; Método totalVendasDia; Mysql '. $connection->error); 
             $this->mysqlError = $connection->errno;
        }
        return $return;
        
    }
    
    public function totalVendasPagasDia($idUsuario = null)
    {
        
       $idUsuario = ($idUsuario === null) ? '%' : $idUsuario;
        
        $return = false;
        
        $connection = \Application::getNewDataBaseInstance();
        $query = "
               select distinct
                ifnull(sum(c.valortotal), 0) as 'valor'
                from contratos c
                where year(c.datapagamento) = year(now()) and month(c.datapagamento) = month(now())
                and day(c.datapagamento) = day(now())
                and c.usuarios_id like ?
        ";
       
        
         if ($stm = $connection->prepare($query))
        {
            $stm->bind_param('s',  $idUsuario);
            if ($stm->execute())
            {
                $stm->bind_result($quantidade);
                
                $return = 0;
                 if ($stm->fetch())
                    $return = $quantidade;
                             
                 
            }
            
        }
         else
        {
            \Application::setMysqlLogQuery('Classe GadgetsHome; Método totalVendasPagasDia; Mysql '. $connection->error); 
             $this->mysqlError = $connection->errno;
        }
        return $return;
        
    }
    
    public function valorVendaSemana($idUsuario)
    {
        
       
        
        $return = false;
        
        $connection = \Application::getNewDataBaseInstance();
        $query = "
               select distinct
                sum(c.valortotal) as 'valor'
                from contratos c
                where week(c.created) = week(now()) and year(c.created) = year(now())
                and c.usuarios_id = ? and c.status in ('Pago ao Cliente', 'Recebido Comissão do Banco')

        ";
       
        
         if ($stm = $connection->prepare($query))
        {
            $stm->bind_param('i',  $idUsuario);
            if ($stm->execute())
            {
                $stm->bind_result($valor);
                
                $return = 0;
                 if ($stm->fetch())
                    $return = $valor;
                             
                 
            }
            
        }
         else
        {
            \Application::setMysqlLogQuery('Classe GadgetsHome; Método valorVendaSemana; Mysql '. $connection->error); 
             $this->mysqlError = $connection->errno;
        }
        return $return;
        
    }
    
    
     public function valorVendaMes($idUsuario = null)
    {
        
       $idUsuario = ($idUsuario === null) ? '%' : $idUsuario;
        
        $return = false;
        
        $connection = \Application::getNewDataBaseInstance();
        $query = "
               select distinct
                sum(c.valortotal) as 'valor'
                from contratos c
                where year(c.datapagamento) = year(now()) and month(c.datapagamento) = month(now())
                and c.usuarios_id like ? and c.status in ('Pago ao Cliente', 'Recebido Comissão do Banco')

        ";
       
        
         if ($stm = $connection->prepare($query))
        {
            $stm->bind_param('s',  $idUsuario);
            if ($stm->execute())
            {
                $stm->bind_result($valor);
                
                $return = 0;
                 if ($stm->fetch())
                    $return = $valor;
                             
                 
            }
            
        }
         else
        {
            \Application::setMysqlLogQuery('Classe GadgetsHome; Método valorVendaMes; Mysql '. $connection->error); 
             $this->mysqlError = $connection->errno;
        }
        return $return;
        
    }
    
    
    public function comissaoLoja()
    {
        
       
        $return = false;
        
        $connection = \Application::getNewDataBaseInstance();
        $query = "
               select
                sum(d.valor) as 'valor'
                from
                (
                  select 
                  ifnull(sum(c.valorloja),0) as 'valor'
                  from contratos c
                  where
                    (year(c.datapagamento) = year(curdate()) and  month(c.datapagamento) = month(curdate()) )
                    and c.status = 'Pago ao Cliente'
                  UNION
                  select
                  sum(a.valordescontado) as 'valor'
                  from adiantamentosdescontados a
                    inner join descontos de on de.id = a.descontos_id
                  where (year(de.created ) = year(curdate()) and  month(de.created) = month(curdate()) )
                 ) d
        ";
       
        
         if ($stm = $connection->prepare($query))
        {
            //$stm->bind_param('s',  $idUsuario);
            if ($stm->execute())
            {
                $stm->bind_result($valor);
                
                $return = 0;
                 if ($stm->fetch())
                    $return = $valor;
                             
                 
            }
            
        }
         else
        {
            \Application::setMysqlLogQuery('Classe GadgetsHome; Método comissaoLoja; Mysql '. $connection->error); 
             $this->mysqlError = $connection->errno;
        }
        return $return;
        
    }
    
    public function melhoresVendedores()
    {
        
       
        
        $return = false;
        
        $inicioMes = date('Y-m-') . '01';
        $fimMes = date("Y-m-t");
        
        $connection = \Application::getNewDataBaseInstance();
        $query = "
               select 
                 t.id, t.nome, sum(t.valormes) as 'valormes', sum(t.vendasemana) as 'vendasemana', 
                 sum(t.vendadia) as 'vendadia', sum(t.valorvinculado) as 'valorvinculado'
                 from (
                       select distinct
                         u.id, u.nome, sum(c.valortotal) as 'valormes',
                          sum(case when week(c.datapagamento) = week(now()) then c.valortotal else 0 end) as 'vendasemana',
                          sum(case when day(c.datapagamento) = day(now()) then c.valortotal else 0 end) as 'vendadia',
                          sum(0) as 'valorvinculado'

                          from contratos c
                            inner join usuarios u ON u.id = c.usuarios_id 

                          where 
                          c.datapagamento between ? and ?
                          group by u.id, u.nome
                          UNION
                          select
                           u.id, u.nome, sum(0) as 'valormes',
                          sum(0) as 'vendasemana',
                          sum(0) as 'vendadia',
                          sum(c.valortotal) as 'valorvinculado'
                          from contratos c
                            inner join usuarios u on u.id = c.usuariovinculado_id

                            where 
                          c.datapagamento between ? and ?
                          group by u.id, u.nome
                    ) t
                  group by t.id, t.nome        
                  order by 3 desc
        ";
       
        
         if ($stm = $connection->prepare($query))
        {
            $stm->bind_param('ssss',  $inicioMes, $fimMes, $inicioMes, $fimMes);
            if ($stm->execute())
            {
                $stm->bind_result($idUsuario, $nomeUsuario, $valorMes, $valorSemana, $valorDia, $valorVinculado);
                
                $return = array();
                 while ($stm->fetch()) {
                     $v = array();
                     $v['idUsuario'] = $idUsuario;
                     $v['nomeUsuario'] = $nomeUsuario;
                     $v['valorMes'] = $valorMes;
                     $v['valorSemana'] = $valorSemana;
                     $v['valorDia'] = $valorDia;
                     $v['valorVinculado'] = $valorVinculado;
                     array_push($return, $v);           
                 }
            }
            
        }
         else
        {
            \Application::setMysqlLogQuery('Classe GadgetsHome; Método melhoresVendedores; Mysql '. $connection->error); 
             $this->mysqlError = $connection->errno;
        }
        return $return;
        
    }
    
    
    
     public function totalContratosPendentesMes($idUsuario)
    {
        
       
        
        $return = false;
        
        $connection = \Application::getNewDataBaseInstance();
        $query = "
              select count(distinct c.id) from contratos c where c.status = 'Pendente' and c.usuarios_id = ?
        ";
       
        
         if ($stm = $connection->prepare($query))
        {
            $stm->bind_param('i',  $idUsuario);
            if ($stm->execute())
            {
                $stm->bind_result($valor);
                
                $return = 0;
                 if ($stm->fetch())
                    $return = $valor;
                             
                 
            }
            
        }
         else
        {
            \Application::setMysqlLogQuery('Classe GadgetsHome; Método totalContratosPendentesMes; Mysql '. $connection->error); 
             $this->mysqlError = $connection->errno;
        }
        return $return;
        
    }
    
    public function salvarNoticia($idUsuario, $message )
    {
        $return = false;
        
        $connection = \Application::getNewDataBaseInstance();
        $query = "
             INSERT INTO noticias(  usuarios_id  ,created  ,noticia) VALUES ( ? , (select now())  , ?)
        ";
       
        
         if ($stm = $connection->prepare($query))
        {
            $stm->bind_param('is',  $idUsuario, $message);
            if ($stm->execute())
            {
               // $stm->bind_result($valor);
                
                $return = true;
                // if ($stm->fetch())
                 //   $return = $valor;
                             
                 
            }
            
        }
         else
        {
            \Application::setMysqlLogQuery('Classe GadgetsHome; Método salvarNoticia; Mysql '. $connection->error); 
             $this->mysqlError = $connection->errno;
        }
        return $return;
    }
    
     public function listarNoticias()
    {
        
       
        
        $return = false;
        
        $connection = \Application::getNewDataBaseInstance();
        $query = "
               select distinct
                n.id, n.usuarios_id, n.created, n.noticia
                from noticias n
                where ltrim(n.noticia) <> ''
                order by n.id desc limit 4
        ";
       
        
         if ($stm = $connection->prepare($query))
        {
            //$stm->bind_param('i',  $idUsuario);
            if ($stm->execute())
            {
                $stm->bind_result($id, $idUsuario, $created, $noticia);
                
                $return = array();
                 while ($stm->fetch()) {
                     $v = array();
                     $v['idUsuario'] = $idUsuario;
                     $v['id'] = $id;
                     $v['created'] = Utils::formatStringDate($created, 'Y-m-d H:i:s', 'd/m/Y');;
                     $v['noticia'] = $noticia;
                     
                     array_push($return, $v);           
                 }
            }
            
        }
         else
        {
            \Application::setMysqlLogQuery('Classe GadgetsHome; Método listarNoticia; Mysql '. $connection->error); 
             $this->mysqlError = $connection->errno;
        }
        return $return;
        
    }
    
    
    //*********
    /** ADICIONADO EM 29/03/2017
    */
    
    
    
    
    public function metaMensalUsuario($idUsuario)
    {
        
       
        
        $return = false;
        
        $connection = \Application::getNewDataBaseInstance();
        $query = "
               select distinct
                me.dtinicio, me.prazo, me.valor, me.valorincremento, me.tipometa,
                (
                  select count(id)  from feriados where data between me.dtinicio and ADDDATE( me.prazo, INTERVAL 1 DAY)
                ) as 'totalferiados',
                TOTALDIASUTEIS(me.dtinicio, me.prazo) as 'totaldiasuteis',
                NUMDIAUTILNOMES(me.dtinicio) as 'diasuteiscorridos', me.created,
                NUMEROSEMANAINICIAL(me.dtinicio, me.prazo) as 'SemanaInicial',
                week(CURDATE()) as 'SemanaAtual',
                NUMEROSEMANAFINAL(me.dtinicio, me.prazo) as 'SemanaFinal'
                
                from metas me
                where
                me.tipometa = 'Mensal'
                and CURDATE() between me.dtinicio and  me.prazo
                and me.usuarios_id = ?
                order by me.created desc
                limit 1
        ";
       
        
         if ($stm = $connection->prepare($query))
        {
            $stm->bind_param('i',  $idUsuario);
            if ($stm->execute())
            {
                $stm->bind_result($dataInicio, $dataFim, $valor, $valorIncremento, $tipoMeta, $totalFeriados, $totalDiasUteis, $numDiaUltiDoMes, $created,
                                    $semanaInicial, $semanaAtual, $SemanaFinal);
                
                $return = null;
                 if ($stm->fetch()) {
                     $return['dataInicio'] = Utils::formatStringDate($dataInicio, 'Y-m-d', 'd/m/Y');
                     $return['dataFim'] = Utils::formatStringDate($dataFim, 'Y-m-d', 'd/m/Y');
                     $return['valor'] = $valor;
                     $return['valorIncremento'] = $valorIncremento;
                     $return['tipoMeta'] = $tipoMeta;    
                     $return['totalDiasUteis'] = $totalDiasUteis;  
                     $return['numDiaUtilDoMes'] = $numDiaUltiDoMes;  
                     $return['numSemanaInicial'] = $semanaInicial; 
                     $return['numSemanaAtual'] = $semanaAtual; 
                     $return['numSemanaFinal'] = $SemanaFinal; 
                 }
            }
            
        }
         else
        {
            \Application::setMysqlLogQuery('Classe GadgetsHome; Método metaMensalUsuario; Mysql '. $connection->error); 
             $this->mysqlError = $connection->errno;
        }
        return $return;
        
    }
    
    public function metaMensalLoja()
    {
        
       
        
        $return = false;
        
        $connection = \Application::getNewDataBaseInstance();
        $query = "
               select distinct
                 gu.nome, me.dtinicio, me.prazo, me.valor, me.valorincremento, me.tipometa
                from metas me
                  inner join grupousuarios gu on gu.id = me.grupousuarios_id
                where
                me.tipometa = 'Mensal'
                and CURDATE() between me.dtinicio and ADDDATE( me.prazo, INTERVAL 1 DAY)

        ";
       
        
         if ($stm = $connection->prepare($query))
        {
           // $stm->bind_param('i',  $idUsuario);
            if ($stm->execute())
            {
                $stm->bind_result($nomeGrupo, $dataInicio, $dataFim, $valor, $valorIncremento, $tipoMeta);
                
                $return = null;
                 $return = array();
                 while ($stm->fetch()) {
                     $v = array();
                     $v['nomeGrupo'] = $nomeGrupo;  
                     $v['dataInicio'] = Utils::formatStringDate($dataInicio, 'Y-m-d', 'd/m/Y');
                     $v['dataFim'] = Utils::formatStringDate($dataFim, 'Y-m-d', 'd/m/Y');
                     $v['valor'] = $valor;
                     $v['valorIncremento'] = $valorIncremento;
                     $v['tipoMeta'] = $tipoMeta;
                     array_push($return, $v);    
                 }
            }
            
        }
         else
        {
            \Application::setMysqlLogQuery('Classe GadgetsHome; Método metaMensalLoja; Mysql '. $connection->error); 
             $this->mysqlError = $connection->errno;
        }
        return $return;
        
    }
    
    
     public function getValoresGerais($idUsuario = null)
    {
        
       $idUsuario = ($idUsuario === null) ? '%' : $idUsuario;
        
        $return = false;
        
        $connection = \Application::getNewDataBaseInstance();
        $query = "
             select distinct
                c.id, c.clientes_cpf, c.nome, c.nomebancocontrato, c.valorparcela,
                c.valortotal, c.valorliquido, c.datapagamento, c.datapagamentobanco, c.status, u.nome

                from contratos c
                  inner join usuarios u on u.id = c.usuarios_id

                where c.usuarios_id like ?
                and
                (
                  (year(c.datapagamentobanco) = year(curdate()) and  month(c.datapagamentobanco) = month(curdate()) )
                            or
                  (year(c.datapagamento) = year(curdate()) and  month(c.datapagamento) = month(curdate()) )
                          or
                  (year(c.modified) = year(curdate()) and  month(c.modified) = month(curdate()) )
                )
                order by id

        ";
       
        
         if ($stm = $connection->prepare($query))
        {
            $stm->bind_param('s',  $idUsuario);
            if ($stm->execute())
            {
                $stm->bind_result($id, $cpf, $nomeCliente, $nomeBanco, $valorParcela, $valorTotal, $valorLiquido, $dataPagamento, $dataPagamentoBanco, $status, $nomeUsuario);
                
                $return = null;
                 $return = array();
                 while ($stm->fetch()) {
                     $v = array();
                     $v['id'] = $id;  
                     $v['cpf'] = $cpf;
                     $v['nomeCliente'] = $nomeCliente;
                     $v['nomeBanco'] = $nomeBanco;
                     $v['valorParcela'] = $valorParcela;
                     $v['valorTotal'] = $valorTotal;
                     $v['valorLiquido'] = $valorLiquido;
                     $v['dataPagamento'] = Utils::formatStringDate($dataPagamento, 'Y-m-d', 'd/m/Y'); 
                     $v['dataPagamentoBanco'] =  Utils::formatStringDate($dataPagamentoBanco, 'Y-m-d', 'd/m/Y');  
                     $v['status'] = $status;
                     $v['nomeUsuario'] = $nomeUsuario;
                     
                     array_push($return, $v);    
                 }
            }
            
        }
         else
        {
            \Application::setMysqlLogQuery('Classe GadgetsHome; Método getValoresGerais; Mysql '. $connection->error); 
             $this->mysqlError = $connection->errno;
        }
        return $return;
        
    }
    
    
    public function metaTodosGrupos($idUsuario)
    {
        
       $inicioMes = date('Y-m-') . '01';
        $fimMes = date("Y-m-t");
        
        $return = false;
        
        $connection = \Application::getNewDataBaseInstance();
        $query = "
               select distinct
                upper(gu.nome) as 'nomegrupo', sum(co.valortotal) as 'valor', me.valor as 'meta'

                from contratos co
                  inner join (
                    select min(cc.grupousuarios_id) as 'grupousuarios_id', cc.usuarios_id, cc.contratos_id from comissoescontrato cc 
                    group by cc.usuarios_id, cc.contratos_id
                  ) cc on cc.contratos_id = co.id and cc.usuarios_id = co.usuarios_id
                  inner join grupousuarios gu on gu.id = cc.grupousuarios_id
                  left join (
                      select distinct
                       gu.id ,  sum(me.valor) as 'valor'
                      from metas me
                        inner join grupousuarios gu on gu.id = me.grupousuarios_id
                      where
                      me.tipometa = 'Mensal'
                      and CURDATE() between me.dtinicio and  me.prazo
                      group by gu.id
                  ) me on  me.id = gu.id


                where co.datapagamento between ? and ?
                group by gu.nome, me.valor
                order by 2 desc
        ";
       
        
         if ($stm = $connection->prepare($query))
        {
            $stm->bind_param('ss',  $inicioMes, $fimMes);
            if ($stm->execute())
            {
                $stm->bind_result($nomeGrupo, $valor, $meta);
                
               
                 $return = array();
                 while ($stm->fetch()) {
                     $v = array();
                     $v['nomeGrupo'] = $nomeGrupo;  
                     $v['valor'] = $valor;
                     $v['meta'] = $meta;
                     array_push($return, $v);    
                 }
            }
            
        }
         else
        {
            \Application::setMysqlLogQuery('Classe GadgetsHome; Método metaTodosGrupos; Mysql '. $connection->error); 
             $this->mysqlError = $connection->errno;
        }
        return $return;
        
    }
    
    
    
    public function obterSomatorioDescontosDevidos($idUsuario)
    {
        
       
        
        $return = false;
        
        $connection = \Application::getNewDataBaseInstance();
        $query = "
                select
                'usuario' as 'tipo',
                ifnull(sum(a.valortotalpagar) - sum(a.valortotalpago),0) as 'devido'
                from adiantamentos a
                where a.encerrado = 0 and a.usuarios_id = ?
                UNION
                select
                'geral' as 'tipo',
                sum(a.valortotalpagar) - sum(a.valortotalpago) as 'devido'
                from adiantamentos a
                where a.encerrado = 0
        ";
       
        
         if ($stm = $connection->prepare($query))
        {
            $stm->bind_param('i',  $idUsuario);
            if ($stm->execute())
            {
                $stm->bind_result($tipo, $valor);
                
                $return = array();
                 while ($stm->fetch()) {
                     $v = array();
                     $v['tipo'] = $tipo;
                     $v['valor'] = $valor;
                     array_push($return, $v);           
                 }
            }
            
        }
         else
        {
            \Application::setMysqlLogQuery('Classe GadgetsHome; Método obterSomatorioDescontosDevidos; Mysql '. $connection->error); 
             $this->mysqlError = $connection->errno;
        }
        return $return;
        
    }
    
    
    
    
    
}