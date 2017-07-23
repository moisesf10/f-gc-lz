<?php
namespace Gauchacred\model;


/**
 * @author moises
 * @version 1.0
 * @created 10-nov-2015 16:24:32
 */

use Gauchacred\library\php\MySqlError as MySqlError;
use Gauchacred\library\php\Utils;

class AvaliaProduto implements MySqlError
{

	private $errorCode = '';


    public function __construct()
    {

    }

    public function getMysqlError()
    {
        return $this->errorCode;
    }





    public function listar($array = array())
    {

        extract($array, EXTR_OVERWRITE);

        $id = (isset($id)) ? $id : '%';
        /*$idUsuario = (isset($idUsuario)) ? $idUsuario : '%';
        $idContrato = (isset($idContrato)) ? $idContrato : '%';
        $inicioValidade = (isset($inicioValidade)) ? $inicioValidade : '2012-01-01';
        $fimValidade = (isset($fimValidade)) ? $fimValidade : '2100-01-01';
				$incluirExpirado = (isset($incluirExpirado) && $incluirExpirado == true) ? true : false;
				$incluirUsados = (isset($incluirUsados)  && $incluirUsados == true  ) ? true : false;

        $limit = (isset($limit)) ? $limit : 10;*/


        $return = false;

        $connection = \Application::getNewDataBaseInstance();
        $query = "
        SELECT a.id, a.usuarios_id, a.trocapontos_produtos_id, a.comentario, a.created, u.nome
          FROM trocapontos_avaliacoes a
            inner join usuarios u on u.id = a.usuarios_id
          where a.trocapontos_produtos_id like ? ;";

         if ($stm = $connection->prepare($query))
        {
            $stm->bind_param('s',  $id);
            if ($stm->execute())
            {
                $stm->bind_result($id, $idUsuario, $idProduto, $comentario, $created, $nomeUsuario);

                $return = array();
                 while ($stm->fetch()) {
                     $v = array();
                     $v['id'] = $id;
                     $v['idUsuario'] = $idUsuario;
                     $v['idProduto'] = $idProduto;
                     $v['comentario'] = $comentario;
                     $v['created'] = Utils::formatStringDate($created, 'Y-m-d H:i:s', 'd/m/Y H:i:s');
                     $v['nomeUsuario'] = $nomeUsuario;

                     array_push($return, $v);
                 }
            }

        }
         else
        {
            \Application::setMysqlLogQuery('Classe AvaliaProduto; Método listar; Mysql '. $connection->error);
             $this->errorCode = $connection->errno;
        }

        return $return;

    }


    public function salvar($array = array())
    {

      extract($array, EXTR_OVERWRITE);
      $id = (isset($id)) ? $id : null;
      $idUsuario = (isset($idUsuario)) ? $idUsuario : null;
      $comentario = (isset($comentario)) ? $comentario : null;

      $return = false;

      $connection = \Application::getNewDataBaseInstance();

       $query = "
            INSERT INTO trocapontos_avaliacoes(usuarios_id  ,trocapontos_produtos_id  ,comentario  ,created)
            VALUES ( ?,?,?,(select now()))
       ";


      if ($stm = $connection->prepare($query))
      {
         $stm->bind_param('iis',  $idUsuario, $id, $comentario);
         if ($stm->execute())
         {
                 $return = true;
         }
          else
          {
              \Application::setMysqlLogQuery('Classe AvaliaProduto; Método salvar; Mysql '. $connection->error);
                 $this->errorCode = $connection->errno;
          }

      }
      else
     {
         \Application::setMysqlLogQuery('Classe AvaliaProduto; Método salvar; Mysql '. $connection->error);
          $this->errorCode = $connection->errno;
     }
     return $return;
    }


  }

  ?>
