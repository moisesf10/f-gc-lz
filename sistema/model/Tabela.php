<?php
namespace Gauchacred\model;


/**
 * @author moises
 * @version 1.0
 * @created 10-nov-2015 16:24:32
 */

use Gauchacred\library\php\MySqlError as MySqlError;
use Gauchacred\library\php\Utils as Utils;

class Tabela implements MySqlError
{

	private $errorCode = '';
    
    public function __construct()
    {
        
    }
    
    public function getMysqlError()
    {
        return $this->errorCode;
    }
    
    
    public function listarTabelas($idTabela = null, $nomeTabela = null, $idConvenio = null, $idBanco = null, $limit = 10)
    {
        
        $idTabela = ($idTabela === null) ? '%' : $idTabela;
        $nomeTabela = ($nomeTabela === null) ? '%' : $nomeTabela;
        $idConvenio = ($idConvenio === null) ? '%' : $idConvenio;
        $idBanco = ($idBanco === null) ? '%' : $idBanco;
        
        if (! is_int($limit))
            throw new \Exception('O parametro limit não pode ser diferente de inteiro');
        
        
        $return = false;
        
        $connection = \Application::getNewDataBaseInstance();
        
        $query = "
            select distinct
                t.id, t.nome as 'nomeTabela',
                b.id, b.codigo, b.nome as 'nomebanco', b.status,
                e.id as 'idconvenio', e.nome as 'nomeconvenio'

                from tabelas t
                  inner join bancos b on b.id = t.bancos_id
                  inner join entidades e on e.id = t.entidades_id

                where t.bancos_id like ?
                and t.entidades_id like ?
                and t.nome like ?
                and t.id like ?
        ";
        
        
             if ($stm = $connection->prepare($query))
            {
                $stm->bind_param('ssss',  $idBanco, $idConvenio, $nomeTabela, $idTabela);
                if ($stm->execute())
                {
                    $stm->bind_result($idTabela, $nomeTabela, $idBanco, $codigoBanco, $nomeBanco, $statusBanco, $idConvenio, $nomeConvenio   );
                    $return = array();
                     while ($stm->fetch()) {
                         $v['idTabela'] = $idTabela;
                         $v['nomeTabela'] = $nomeTabela;
                         $v['idBanco'] = $idBanco;
                         $v['codigoBanco'] = $codigoBanco;
                         $v['nomeBanco'] = $nomeBanco;
                         $v['statusBanco'] = $statusBanco;
                         $v['idConvenio'] = $idConvenio;
                         $v['nomeConvenio'] = $nomeConvenio;
                         
                         array_push($return, $v);
                         }
                     
                }

            }
             else
            {
                \Application::setMysqlLogQuery('Classe Tabela; Método listarTabelas; Mysql '. $connection->error); 
                 $this->mysqlError = $connection->errno;
            }

        return $return;
        
    }
    
    
    public function subtabelaCompleta($idTabela= null, $idSubtabela = null)
    {
        $idTabela = ($idTabela === null) ? '%' : $idTabela;
        $idSubtabela = ($idSubtabela === null) ? '%' : $idSubtabela;
        
        
        $return = false;
        
        $connection = \Application::getNewDataBaseInstance();
        
        $query = "
            select distinct
                b.id, b.codigo, b.nome, b.status, t.id as 'idtabela', t.nome as 'nometabela',
                e.id as 'idconvenio', e.nome as 'nomeconvenio', st.id as 'idsubtabela', st.seguro, st.imposto, 
                st.comissaototal, st.iniciovigencia, st.fimvigencia,
                ops.id as 'idoperacoessubtabelas', ops.nome as 'nomeoperacoessubtabelas',
                pz.prazos, com.comissoes
                from bancos b
                  inner join tabelas t on t.bancos_id = b.id
                  inner join entidades e on e.id = t.entidades_id
                  inner join subtabelas st on st.tabelas_id = t.id
                  inner join operacoessubtabelas ops on ops.id = st.operacoessubtabelas_id
                  left join (
                    select distinct
                    prazsu.subtabelas_id, group_concat(prazsu.id, ',', prazsu.subtabelas_id, ',', prazsu.prazo SEPARATOR ';') as 'prazos'
                    from prazossubtabelas prazsu
                    group by prazsu.subtabelas_id
                  ) pz on pz.subtabelas_id = st.id
                  left join (
                    select distinct
                      csubt.subtabelas_id, group_concat(csubt.id, ',', csubt.subtabelas_id, ',', csubt.comissao, ',', gu.id,
                      ',', gu.nome SEPARATOR ';') as 'comissoes'
                    from comissoessubtabelas csubt
                      inner join grupousuarios gu on gu.id = csubt.grupousuarios_id
                    group by csubt.subtabelas_id
                  ) com on com.subtabelas_id = st.id

                where t.id like ? and st.id like ?
                 order by b.nome
        ";
        
        
             if ($stm = $connection->prepare($query))
            {
                $stm->bind_param('ss',  $idTabela, $idSubtabela);
                if ($stm->execute())
                {
                    $stm->bind_result($idBanco, $codigoBanco, $nomeBanco, $statusBanco, $idTabela, $nomeTabela, $idConvenio, $nomeConvenio, $idSubtabela,
                                    $seguro, $imposto, $comissaoTotal, $inicioVigencia, $fimVigencia, $idOperacoesSubtabela, $nomeOperacoesSubtabela, $prazos, $comissoes   );
                    $return = array();
                     while ($stm->fetch()) {
                         $v['idBanco'] = $idBanco;
                         $v['codigoBanco'] = $codigoBanco;
                         $v['nomeBanco'] = $nomeBanco;
                         $v['statusBanco'] = $statusBanco;
                         $v['idTabela'] = $idTabela;
                         $v['nomeTabela'] = $nomeTabela;
                         $v['idConvenio'] = $idConvenio;
                         $v['nomeConvenio'] = $nomeConvenio;
                         $v['idSubtabela'] = $idSubtabela;
                         $v['valorSeguro'] = $seguro;
                         $v['valorImposto'] = $imposto;
                         $v['valorComissaoTotal'] = $comissaoTotal;
                         $v['inicioVigencia'] = Utils::formatStringDate($inicioVigencia, 'Y-m-d', 'd/m/Y');
                         $v['fimVigencia'] = Utils::formatStringDate($fimVigencia, 'Y-m-d', 'd/m/Y');
                         $v['idOperacoesSubtabela'] = $idOperacoesSubtabela;
                         $v['nomeOperacoesSubtabela'] = $nomeOperacoesSubtabela;
                         $v['prazos'] = array();
                         $prazos = explode(';', $prazos);
                         if (is_array($prazos))
                         {
                             $prazo = null;
                             foreach($prazos as $i  => $value)
                             {
                                 $aux = explode(',', $value);
                                 $prazo['idPrazo'] = (isset($aux[0]))?$aux[0] : '';
                                 $prazo['idSubtabela'] = (isset($aux[1])) ? $aux[1] : '';
                                 $prazo['prazo'] = (isset($aux[2])) ? $aux[2] : '';
                                 if (count($prazo) > 1)
                                    array_push($v['prazos'], $prazo);
                             }
                             
                         }
                         
                         $v['comissoes'] = array();
                         $comissoes = explode(';', $comissoes);
                         if (is_array($comissoes))
                         {
                             $comissao = null;
                             foreach($comissoes as $i  => $value)
                             {
                                 $aux = explode(',', $value);
                                 $comissao['idComissoesSubtabela'] = (isset($aux[0])) ? $aux[0] : '';
                                 $comissao['idSubtabela'] = (isset($aux[1])) ? $aux[1] : '';
                                 $comissao['valorComissao'] = (isset($aux[2])) ? $aux[2] : '';
                                 $comissao['idGrupoUsuario'] = (isset($aux[3])) ? $aux[3] : '';
                                 $comissao['nomeGrupoUsuario'] = (isset($aux[4])) ? $aux[4] : '';
                             }
                             if (count($comissao) > 1)
                                 array_push($v['comissoes'], $comissao);
                         }
                         
                         
                         array_push($return, $v);
                     }
                }

            }
             else
            {
                \Application::setMysqlLogQuery('Classe Usuario; Método autenticar; Mysql '. $connection->error); 
                 $this->mysqlError = $connection->errno;
            }
        
       // echo '<pre>';var_dump($return); exit;
        return $return;
        
        
    }
    
    
    
    public function salvar($nome, $banco, $convenio, $id = null)
    {
        $return = false;
        
        
        
        $connection = \Application::getNewDataBaseInstance();
        //novo registro
        if ($id === null)
            $query = "
                    INSERT INTO tabelas (bancos_id, entidades_id, nome) VALUES (?, ?, ?);
            ";
        else
            // atualiza registro
            $query = "update tabelas set bancos_id = ?, entidades_id = ?, nome = ? where id = ?";
        
        
       
        
         if ($stm = $connection->prepare($query))
         {
            if ($id === null)
                $stm->bind_param('iis',  $banco, $convenio, $nome);
             else
                 $stm->bind_param('iisi', $banco, $convenio, $nome, $id);
            if ($stm->execute())
            {
                if($id === null)
                    $return = $connection->insert_id;
                else
                    $return = $id;
            }
             else
             {
                 \Application::setMysqlLogQuery('Classe Tabela; Método salvar; Mysql '. $connection->error); 
                    $this->errorCode = $connection->errno;
             }

         }
         else
        {
            \Application::setMysqlLogQuery('Classe Tabela; Método salvar; Mysql '. $connection->error); 
             $this->errorCode = $connection->errno;
        }
        return $return;
        
    }
    
    
    public function excluir($id)
     {
        $return = false;
        
        $connection = \Application::getNewDataBaseInstance();

        $query = "delete from tabelas where id = ?";

         if ($stm = $connection->prepare($query))
         {
                $stm->bind_param('i', $id);
                if ($stm->execute())
                {
                        $return = true;
                }
                 else
                 {
                     \Application::setMysqlLogQuery('Classe Tabelas; Método excluir; Mysql '. $connection->error); 
                        $this->errorCode = $connection->errno;
                 }
         }
         else
        {
            \Application::setMysqlLogQuery('Classe Tabelas; Método excluir; Mysql '. $connection->error); 
             $this->errorCode = $connection->errno;
        }
        return $return;
        
     }
    
    
}