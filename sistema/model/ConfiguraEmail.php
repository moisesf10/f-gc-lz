<?php
namespace Gauchacred\model;


/**
 * @author moises
 * @version 1.0
 * @created 10-nov-2015 16:24:32
 */

use Gauchacred\library\php\MySqlError as MySqlError;
use Gauchacred\library\php\Utils;

class ConfiguraEmail implements MySqlError
{

	private $errorCode = '';


    public function __construct()
    {

    }

    public function getMysqlError()
    {
        return $this->errorCode;
    }





    public function listar()
    {

        $return = false;

        $connection = \Application::getNewDataBaseInstance();
        $query = "
                select id, nome, conteudo, modified  from configuracao where nome = 'smtpconfig' limit 1;
        ";


         if ($stm = $connection->prepare($query))
        {
          //  $stm->bind_param('si',  $id, $limit);
            if ($stm->execute())
            {
                $stm->bind_result($id, $nome, $conteudo, $modified);

                $return = array();
                 if ($stm->fetch()) {
                     $v = array();
                     $v['id'] = $id;
                     $v['nome'] = $nome;
                     $v['modified'] = Utils::formatStringDate($modified, 'Y-m-d H:i:s', 'd/m/Y H:i:s');


                     $aux = json_decode($conteudo, true);

                     $v['smtpServer'] = (isset($aux['smtpServer'])) ? $aux['smtpServer'] : null;
                     $v['smtpPort'] = (isset($aux['smtpPort'])) ? $aux['smtpPort'] : null;
                     $v['smtpSecurity'] = (isset($aux['smtpSecurity'])) ? $aux['smtpSecurity'] : null;
                     $v['smtpLogin'] = (isset($aux['smtpLogin'])) ? $aux['smtpLogin'] : null;
                     $v['smtpPassword'] = (isset($aux['smtpPassword'])) ? $aux['smtpPassword'] : null;
                     $v['para'] = (isset($aux['para'])) ? $aux['para'] : null;
                     $return = $v;
                    // array_push($return, $v);
                 }
            }else
           {
               \Application::setMysqlLogQuery('Classe ConfiguraEmail; Método listar; Mysql '. $connection->error);
                $this->errorCode = $connection->errno;
           }

        }
         else
        {
            \Application::setMysqlLogQuery('Classe ConfiguraEmail; Método listar; Mysql '. $connection->error);
             $this->errorCode = $connection->errno;
        }

        return $return;

    }



     public function salvar($conteudo = null)
    {


         $return = false;



        $connection = \Application::getNewDataBaseInstance();

        $query = "
        INSERT INTO configuracao(nome ,conteudo) VALUES ('smtpconfig', ? ) on duplicate key update conteudo = ?
            ";


         if ($stm = $connection->prepare($query))
         {
            $stm->bind_param('ss', $conteudo, $conteudo);

            if ($stm->execute())
            {
                    $return = true;
            }
             else
             {
                 \Application::setMysqlLogQuery('Classe ConfiguraEmail; Método salvar; Mysql '. $connection->error);
                    $this->errorCode = $connection->errno;
             }

         }
         else
        {
            \Application::setMysqlLogQuery('Classe ConfiguraEmail; Método salvar; Mysql '. $connection->error);
             $this->errorCode = $connection->errno;
        }
        return $return;

    }


}
