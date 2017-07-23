<?php
namespace Gauchacred\model;


/**
 * @author moises
 * @version 1.0
 * @created 10-nov-2015 16:24:32
 */

use Gauchacred\library\php\MySqlError as MySqlError;
use Gauchacred\library\php\Utils as Utils;

class Subtabela implements MySqlError
{

	private $errorCode = '';
    
    public function __construct()
    {
        
    }
    
    public function getMysqlError()
    {
        return $this->errorCode;
    }
    
    
    public function listarSubtabelas($idSubtabela = null, $orderNumber = 1, $orderType = 'desc', $limit = 10, $idEntidade = null, $idBanco = null, $validadeInicial = null, $validadeFinal =null)
    {
        $idSubtabela = ($idSubtabela === null) ? '%': $idSubtabela;
        $idEntidade = ($idEntidade === null) ? '%' : $idEntidade;
        $idBanco = ($idBanco === null) ? '%' : $idBanco;
        $validadeInicial = ($validadeInicial === null) ? '0001-01-01' : $validadeInicial;
        $validadeFinal = ($validadeFinal === null) ? '2100-01-01' : $validadeFinal;
        
          $return = false;
        
        $connection = \Application::getNewDataBaseInstance();
        
        $query = "
            select distinct
            st.id, st.seguro, st.imposto, st.comissaototal, st.iniciovigencia, st.fimvigencia,
            ops.id as 'idoperacao', ops.nome as 'nomeoperacao',
            t.id as 'idtabela', t.nome as 'nometabela',
            b.id as 'idbanco', b.codigo as 'codigobanco', b.nome as 'nomebanco', b.status as 'statusbanco',
            en.id as 'idconvenio', en.nome as 'nomeconvenio',
            p.prazos as 'prazos',
            cst.comissoes ,valorvendagerarponto  ,quantidadepontosgerar  ,quantidadediasexpirarponto
            from subtabelas st
              inner join operacoessubtabelas ops on ops.id = st.operacoessubtabelas_id
              inner join tabelas t on t.id = st.tabelas_id
              inner join entidades en on en.id = t.entidades_id
              inner join bancos b on b.id = t.bancos_id
              left join (
                select distinct
                p.subtabelas_id, group_concat( p.prazo , ',', p.coeficiente, ',', p.id SEPARATOR ';') as 'prazos'
                from prazossubtabelas p group by p.subtabelas_id
              ) p on p.subtabelas_id = st.id
              left join (
                select distinct
                cst.subtabelas_id, group_concat( cst.grupousuarios_id, '|', gu.nome, '|', cst.comissao, '|', cst.id, '|', ifnull(cst.recebecomissao_grupos_id,'') order by cst.id SEPARATOR ';') as 'comissoes'
                from comissoessubtabelas cst
                  inner join grupousuarios gu on gu.id = cst.grupousuarios_id
                group by cst.subtabelas_id
              ) cst on cst.subtabelas_id = st.id

            where st.id like ?
            and en.id like ? and b.id like ? and st.iniciovigencia between ? and ? 
            order by ? $orderType
            limit ?
        ";
        
        
         if ($stm = $connection->prepare($query))
         {
            $stm->bind_param('sssssii',  $idSubtabela, $idEntidade, $idBanco, $validadeInicial, $validadeFinal, $orderNumber, $limit);
            if ($stm->execute())
            {
                $stm->bind_result($id, $seguro, $imposto, $comissaoTotal, $inicioVigencia, $fimVigencia, $idOperacao, $nomeOperacao, $idTabela, $nomeTabela, $idBanco, $codigoBanco, $nomeBanco, $statusBanco, $idConvenio, $nomeConvenio, $prazos, $comissoes, $valorVendaGerarPonto, $quantidadePontosGerar, $quantidadeDiasExpirarPontos  );
                $return = array();
                
                 while ($stm->fetch()) {
                     $v['id'] = $id;
                     $v['seguro'] = $seguro;
                     $v['imposto'] = $imposto;
                     $v['comissaoTotal'] = $comissaoTotal;
                     $v['inicioVigencia'] = Utils::formatStringDate($inicioVigencia, 'Y-m-d', 'd/m/Y');
                     $v['fimVigencia'] = Utils::formatStringDate($fimVigencia,  'Y-m-d', 'd/m/Y');
                     $v['idOperacao'] = $idOperacao;
                     $v['nomeOperacao'] = $nomeOperacao;
                     $v['idTabela'] = $idTabela;
                     $v['nomeTabela'] = $nomeTabela;
                     $v['idBanco'] = $idBanco;
                     $v['codigoBanco'] = $codigoBanco;
                     $v['nomeBanco'] = $nomeBanco;
                     $v['statusBanco'] = $statusBanco;
                     $v['idConvenio'] = $idConvenio;
                     $v['nomeConvenio'] = $nomeConvenio;
                     $v['valorVendaGerarPonto'] = $valorVendaGerarPonto;
                     $v['quantidadePontosGerar'] = $quantidadePontosGerar;
                     $v['quantidadeDiasExpirarPontos'] = $quantidadeDiasExpirarPontos;
                     
                     $v['prazos'] = array();
                     $v['comissoes'] = array();
                     
                     $prazos = explode(';', $prazos);
                     if (is_array($prazos))
                         foreach($prazos as $i => $prazo)
                         {
                             $prazo = explode(',', $prazo);
                             if (count($prazo) > 0)
                                array_push($v['prazos'], array(
                                    'prazo' => (isset($prazo[0]))? $prazo[0] : '',
                                    'coeficiente' => (isset($prazo[1]))? $prazo[1] : '', 
                                    'id' => (isset($prazo[2])) ? $prazo[2] : ''
                                ));
                         }
                     
                     $comissoes = explode(';', $comissoes);
                     
                     if (is_array($comissoes))
                         foreach($comissoes as $i => $comissao)
                         {
                             $comissao = explode('|', $comissao);
                             $comissao[4] = ((trim($comissao[4]) != '')) ? explode(',',$comissao[4]) : array();
                             if (count($comissao) > 0)
                                 array_push($v['comissoes'], array('idGrupo' => $comissao[0], 'nomeGrupo' => $comissao[1], 'comissao' => $comissao[2], 'idComissao' => $comissao[3], 'recebeComissaoDe' => $comissao[4]));
                         }

                     array_push($return, $v);
                     }

            }

         }
         else
         {
            \Application::setMysqlLogQuery('Classe Subtabelas; Método listarSubtabelas; Mysql '. $connection->error); 
             $this->mysqlError = $connection->errno;
         }
        
       // echo '<pre>'; print_r($return); exit;

        return $return;
        
        
        
        
        
    }
    
    
    public function salvar($dados)
    {
      
        if (! is_array($dados) )
            throw new \Exception('Parametro Invalido');
        else if (! isset($dados['id']))
            $dados['id'] = null;
        
        $dados['inicioVigencia'] = Utils::formatStringDate($dados['inicioVigencia'], 'd/m/Y', 'Y-m-d');
        $dados['fimVigencia'] = Utils::formatStringDate($dados['fimVigencia'], 'd/m/Y', 'Y-m-d');
        
        $return = false;
        $connection = \Application::getNewDataBaseInstance();
        $connection->autocommit(false);
        
        
        if ($dados['id'] == null || $dados['id'] == '' )
        {
            $dados['id'] = null;
            $query = "
                insert into subtabelas (
                   tabelas_id
                  ,operacoessubtabelas_id
                  ,seguro
                  ,imposto
                  ,comissaototal
                  ,iniciovigencia
                  ,fimvigencia
                  ,valorvendagerarponto
                  ,quantidadepontosgerar
                  ,quantidadediasexpirarponto
                ) VALUES (
                    ?, ?, ?, ?, ?,?, ?, ? ,?,?
                )
            ";
        }else
            $query = "
                update subtabelas SET
                  tabelas_id = ?
                  ,operacoessubtabelas_id = ?
                  ,seguro = ?
                  ,imposto = ?
                  ,comissaototal = ?
                  ,iniciovigencia = ?
                  ,fimvigencia = ?
                  ,valorvendagerarponto = ?
                  ,quantidadepontosgerar = ?
                  ,quantidadediasexpirarponto = ?
                WHERE id = ?
            ";
        
        
        // insere tabela
        if ($stm = $connection->prepare($query))
         {
            if ($dados['id'] === null)
                $stm->bind_param('iidddsssii', $dados['tabela'], $dados['operacao'], $dados['seguro'], $dados['imposto'], $dados['comissaoTotal'], $dados['inicioVigencia'], $dados['fimVigencia'], $dados['valorPontuar'], $dados['pontosGerar'], $dados['diasExpirar'] );
             else
                 $stm->bind_param('iidddsssiii', $dados['tabela'], $dados['operacao'], $dados['seguro'], $dados['imposto'], $dados['comissaoTotal'], $dados['inicioVigencia'], $dados['fimVigencia'], $dados['valorPontuar'], $dados['pontosGerar'], $dados['diasExpirar'], $dados['id']);
            if ($stm->execute())
            {
                if($dados['id'] === null)
                    $return = $connection->insert_id;
                else
                    $return = $dados['id'];
            }
             else
             {
                 \Application::setMysqlLogQuery('Classe Subtabela; Método salvar - Query insert subtabela; Mysql '. $connection->error); 
                 $this->errorCode = $connection->errno;
                 $return = false;
             }

         }
         else
        {
            \Application::setMysqlLogQuery('Classe Subtabela; Método salvar - Query insert subtabela; Mysql '. $connection->error); 
             $this->errorCode = $connection->errno;
             $return = false;
        }
        
        
        
        
        
        if ($return !== false)
        {
            // *******************************
            // Apaga Prazos
            $query = "
                delete from prazossubtabelas where subtabelas_id = ?;
            ";
            if ($stm = $connection->prepare($query))
             {
                $stm->bind_param('i', $return);
                if (! $stm->execute())
                 {
                     \Application::setMysqlLogQuery('Classe Subtabela; Método salvar - Query delete prazos; Mysql '. $connection->error); 
                     $this->errorCode = $connection->errno;
                     $return = false;
                 }

             }
             else
            {
                \Application::setMysqlLogQuery('Classe Subtabela; Método salvar - Query delete prazos; Mysql '. $connection->error); 
                 $this->errorCode = $connection->errno;
                 $return = false;
            }
                    
            
            // *******************************
            // Insere prazos
            
            $query = "
                insert into prazossubtabelas (subtabelas_id,prazo ,coeficiente) VALUES (?,?,?)
            ";
            if ($stm = $connection->prepare($query))
             {
                $stm->bind_param('iis', $return, $prazo, $coeficiente);
                
                foreach($dados['prazos'] as $i => $value)
                {
                    $prazo = $value['prazo'];
                    $coeficiente = $value['coeficiente'];
                    if (! $stm->execute())
                    {
                         \Application::setMysqlLogQuery('Classe Subtabela; Método salvar - Query Insert prazos; Mysql '. $connection->error); 
                         $this->errorCode = $connection->errno;
                         $return = false;
                         break;
                    }
                }
             }
             else
             {
                \Application::setMysqlLogQuery('Classe Subtabela; Método salvar - Query Insert prazos; Mysql '. $connection->error); 
                 $this->errorCode = $connection->errno;
                 $return = false;
             }
            
            
            // *******************************
            // Apaga comissoes
            $query = "
                delete from comissoessubtabelas where subtabelas_id = ?;
            ";
            if ($stm = $connection->prepare($query))
             {
                $stm->bind_param('i', $return);
                if (! $stm->execute())
                 {
                     \Application::setMysqlLogQuery('Classe Subtabela; Método salvar - Query delete comissoes; Mysql '. $connection->error); 
                     $this->errorCode = $connection->errno;
                     $return = false;
                 }

             }
             else
            {
                \Application::setMysqlLogQuery('Classe Subtabela; Método salvar - Query delete comissoes; Mysql '. $connection->error); 
                 $this->errorCode = $connection->errno;
                 $return = false;
            }
            
            // *******************************
            // Insere comissões
            
            $query = "
                insert into comissoessubtabelas ( subtabelas_id,grupousuarios_id,comissao, recebecomissao_grupos_id) VALUES (? ,?,?,? )
            ";
            if ($stm = $connection->prepare($query))
             {
                $stm->bind_param('iids', $return, $idGrupo, $comissao, $recebeDe);
                
                foreach($dados['comissoes'] as $i => $value)
                {
                    $idGrupo = $value['idGrupo'];
                    $comissao = $value['comissao'];
                    $recebeDe = (count($value['recebede']) > 0) ? implode(',', $value['recebede']) : '';
                    if (! $stm->execute())
                    {
                         \Application::setMysqlLogQuery('Classe Subtabela; Método salvar - Query Insert comissoes; Mysql '. $connection->error); 
                         $this->errorCode = $connection->errno;
                         $return = false;
                         break;
                    }
                }
             }
             else
             {
                \Application::setMysqlLogQuery('Classe Subtabela; Método salvar - Query insert commissoes; Mysql '. $connection->error); 
                 $this->errorCode = $connection->errno;
                 $return = false;
             }
             
            
            
            
            
        }
        
        if ($return === false)
            $connection->rollBack();
        else
            $connection->commit();
        
        return $return;

    }
    
     public function excluir($id)
     {
        $return = true;
        
        $connection = \Application::getNewDataBaseInstance();
         $connection->autocommit(false);
         
         // ******
         // deleta prazos
         $query = "delete from prazossubtabelas WHERE subtabelas_id =?";
         if ($stm = $connection->prepare($query))
         {
                $stm->bind_param('i', $id);
                if (! $stm->execute())
                 {
                     \Application::setMysqlLogQuery('Classe Subtabelas; Método excluir - query prazos; Mysql '. $connection->error); 
                     $this->errorCode = $connection->errno;
                     $return = false;
                 }
         }
         else
        {
            \Application::setMysqlLogQuery('Classe Subtabelas; Método excluir - query prazos; Mysql '. $connection->error); 
             $this->errorCode = $connection->errno;
              $return = false;
        }
         
         // ******
         // deleta comissoes
         $query = "delete from comissoessubtabelas WHERE subtabelas_id =?";
         if ($stm = $connection->prepare($query))
         {
                $stm->bind_param('i', $id);
                if (! $stm->execute())
                 {
                     \Application::setMysqlLogQuery('Classe Subtabelas; Método excluir - query comissoes; Mysql '. $connection->error); 
                     $this->errorCode = $connection->errno;
                     $return = false;
                 }
         }
         else
        {
            \Application::setMysqlLogQuery('Classe Subtabelas; Método excluir - query comissoes; Mysql '. $connection->error); 
             $this->errorCode = $connection->errno;
              $return = false;
        }
         
         // ******
         // deleta comissoes
         $query = "delete from subtabelas WHERE id =?";
         if ($stm = $connection->prepare($query))
         {
                $stm->bind_param('i', $id);
                if (! $stm->execute())
                 {
                     \Application::setMysqlLogQuery('Classe Subtabelas; Método excluir - query subtabelas; Mysql '. $connection->error); 
                     $this->errorCode = $connection->errno;
                     $return = false;
                 }
         }
         else
        {
            \Application::setMysqlLogQuery('Classe Subtabelas; Método excluir - query subtabelas; Mysql '. $connection->error); 
             $this->errorCode = $connection->errno;
              $return = false;
        }
         
         
       if ($return === true)
           $connection->commit();
      else
          $connection->rollback();
         
        return $return;
        
     }
    
    
    
}