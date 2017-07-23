<?php
namespace Gauchacred\model;


/**
 * @author moises
 * @version 1.0
 * @created 10-nov-2015 16:24:32
 */

use Gauchacred\library\php\MySqlError as MySqlError;

class Recurso implements MySqlError
{

	private $errorCode = '';
    
    public function __construct()
    {
        
    }
    
    public function getMysqlError()
    {
        return $this->errorCode;
    }
    
    public function listarRecursos($id = '%')
    {
        
        
        $return = false;
        
        $connection = \Application::getNewDataBaseInstance();
        $query = "
                select distinct
                gr.id as 'idgruporecurso', gr.descricao as 'descricaogruporecurso',
                r.id as 'idrecurso', r.nome as 'nomerecurso', r.descricao as 'descricaorecurso', r.pagina, r.indicamenu, r.nomemenu, r.tagicon

                from recurso r
                  inner join gruporecurso gr on gr.id = r.gruporecurso_id
                where r.id like ?
        ";
       
        
         if ($stm = $connection->prepare($query))
        {
            $stm->bind_param('s',  $id);
            if ($stm->execute())
            {
                $stm->bind_result($idGrupoRecurso, $descricaoGrupoRecurso, $idRecurso, $nomeRecurso, $descricaoRecurso, $pagina, $indicaMenu, $nomeMenu, $tagIcon);
                
                $return = array();
                 while ($stm->fetch()) {
                     $v = array();
                     $v['idGrupoRecurso'] = $idGrupoRecurso;
                     $v['descricaoGrupoRecurso'] = $descricaoGrupoRecurso;
                     $v['idRecurso'] = $idRecurso;
                     $v['nomeRecurso'] = $nomeRecurso;
                     $v['descricaoRecurso'] = $descricaoRecurso;
                     $v['pagina'] = $pagina;
                     $v['indicaMenu'] = $indicaMenu;
                     $v['nomeMenu'] = $nomeMenu;
                     $v['tagIcon'] = $tagIcon;
                     array_push($return, $v);           
                 }
            }
            
        }
         else
        {
            \Application::setMysqlLogQuery('Classe Recurso; Método listarRecursos; Mysql '. $connection->error); 
             $this->mysqlError = $connection->errno;
        }
        
        return $return;
        
    }
    
    
    
    

}
?>