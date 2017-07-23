<?php
namespace Gauchacred\model;


/**
 * @author moises
 * @version 1.0
 * @created 10-nov-2015 16:24:32
 */

use Gauchacred\library\php\MySqlError as MySqlError;
use Gauchacred\library\php\Utils as Utils;

class Meta implements MySqlError
{

	private $errorCode = '';
    
    public function __construct()
    {
        
    }
    
    public function getMysqlError()
    {
        return $this->errorCode;
    }

    
    public function listarMetas($id = null, $tipoMeta = null, $idUsuario = null, $idGrupo = null,  $limit = 10)
    {
        
        $id = ($id === null) ? '%' : $id;
        $tipoMeta = ($tipoMeta === null) ? '%' : $tipoMeta;
        $idUsuario = ($idUsuario === null) ? '%' : $idUsuario;
        $idGrupo = ($idGrupo === null) ? '%' : $idGrupo;
        
        $return = false;
        
        $connection = \Application::getNewDataBaseInstance();
        $query = "
                select distinct
                   m.id, m.dtinicio, m.prazo, m.valor, m.tipometa, 
                  us.id as 'idusuario', us.nome as 'nomeusuario',
                  gu.id as 'idgrupo', gu.nome as 'nomegrupo', m.created,  m.modified, m.valorincremento
                  from metas m
                    left join usuarios us on us.id = m.usuarios_id
                    left join grupousuarios gu on gu.id = m.grupousuarios_id

                where (m.usuarios_id like ? or '%' = ?)
                and (m.grupousuarios_id like ? or '%' = ?)
                and m.tipometa like ?
                and m.id like ?
                order by m.id desc
                limit ?
        ";
       
        
         if ($stm = $connection->prepare($query))
        {
            $stm->bind_param('ssssssi',  $idUsuario, $idUsuario, $idGrupo, $idGrupo, $tipoMeta, $id, $limit);
            if ($stm->execute())
            {
                $stm->bind_result($id, $dtInicio, $prazo, $valor, $tipoMeta, $idUsuario,  $nomeUsuario, $idGrupo, $nomeGrupo, $created, $modified, $incremento);
                
                $return = array();
                 while ($stm->fetch()) {
                     $v = array();
                     $v['id'] = $id;
                     $v['dtInicio'] = Utils::formatStringDate($dtInicio, 'Y-m-d', 'd/m/Y'); 
                     $v['prazo'] = Utils::formatStringDate($prazo, 'Y-m-d', 'd/m/Y');  
                     $v['created'] = Utils::formatStringDate($created, 'Y-m-d H:i:s', 'd/m/Y H:i:s'); 
                     $v['valor'] = $valor;
                     $v['valorIncremento'] = $incremento;
                     $v['modified'] = Utils::formatStringDate($modified, 'Y-m-d H:i:s', 'd/m/Y H:i:s'); 
                     $v['tipoMeta'] = $tipoMeta;
                     $v['idUsuario'] = $idUsuario;
                     $v['nomeUsuario'] = $nomeUsuario;
                     $v['idGrupo'] = $idGrupo;
                     $v['nomeGrupo'] =  $nomeGrupo;
                     array_push($return, $v);           
                 }
            }
            
        }
         else
        {
            \Application::setMysqlLogQuery('Classe Meta; Método listarMetas; Mysql '. $connection->error); 
             $this->mysqlError = $connection->errno;
        }
        return $return;
        
    }



public function salvar($idUsuario, $idGrupo, $tipoMeta, $dtInicio, $prazo, $meta, $incremento, $id = null)
    {
        $return = false;
        
      
        
        $connection = \Application::getNewDataBaseInstance();
        //novo registro
        if ($id === null)
            $query = "
                    insert into metas (
                          usuarios_id
                          ,grupousuarios_id
                          ,tipometa
                          ,dtinicio
                          ,prazo
                          ,created
                          ,valor
                          ,valorincremento
                        ) VALUES (
                           ? -- usuarios_id - IN int(11) unsigned
                           ,? -- grupousuarios_id
                           ,? -- tipometa
                           ,?
                          ,? -- prazo - IN date
                          ,(select now())
                          ,? -- valor - IN decimal(14,2)
                          ,? -- valor do incremento
                        )
            ";
        else
            // atualiza registro
            $query = "update metas set usuarios_id = ?, grupousuarios_id = ?, tipometa = ?, dtinicio = ?, prazo = ?, valor = ?, valorincremento = ? where id = ?";
        
        
      // var_dump(array($idUsuario, $idGrupo, $tipoMeta, $dtInicio, $prazo, $meta)); exit;
        
         if ($stm = $connection->prepare($query))
         {
            if ($id === null)
                $stm->bind_param('sssssdd',   $idUsuario, $idGrupo, $tipoMeta, $dtInicio, $prazo, $meta, $incremento);
             else
                 $stm->bind_param('sssssddi', $idUsuario, $idGrupo, $tipoMeta, $dtInicio, $prazo, $meta, $incremento, $id);
            if ($stm->execute())
            {
                if($id === null)
                    $return = $connection->insert_id;
                else
                    $return = $id;
            }
             else
             {
                 \Application::setMysqlLogQuery('Classe Meta; Método salvar; Mysql '. $connection->error); 
                    $this->errorCode = $connection->errno;
             }

         }
         else
        {
            \Application::setMysqlLogQuery('Classe Meta; Método salvar; Mysql '. $connection->error); 
             $this->errorCode = $connection->errno;
        }
        return $return;
        
    }
    
    
    public function excluir($id)
     {
        $return = false;
        
        $connection = \Application::getNewDataBaseInstance();

        $query = "delete from metas where id = ?";

         if ($stm = $connection->prepare($query))
         {
                $stm->bind_param('i', $id);
                if ($stm->execute())
                {
                        $return = true;
                }
                 else
                 {
                     \Application::setMysqlLogQuery('Classe Metas; Método excluir; Mysql '. $connection->error); 
                        $this->errorCode = $connection->errno;
                 }
         }
         else
        {
            \Application::setMysqlLogQuery('Classe Metas; Método excluir; Mysql '. $connection->error); 
             $this->errorCode = $connection->errno;
        }
        return $return;
        
     }
}
    
?>