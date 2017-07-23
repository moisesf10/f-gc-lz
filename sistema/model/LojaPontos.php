<?php
namespace Gauchacred\model;


/**
 * @author moises
 * @version 1.0
 * @created 10-nov-2015 16:24:32
 */

use Gauchacred\library\php\MySqlError as MySqlError;
use Gauchacred\library\php\Utils;

class LojaPontos implements MySqlError
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
        $nome = (isset($nome)) ? $nome : '%';
        $link = (isset($link)) ? $link : '%';
        $pontos = (isset($pontos)) ? $pontos : '%';
        $inicioValidade = (isset($inicioValidade)) ? $inicioValidade : '0001-01-01';
        $fimValidade = (isset($fimValidade)) ? $fimValidade : '2900-01-01';
        $descricao = (isset($descricao)) ? $descricao : '%';
        $incluirExpirado = (isset($incluirExpirado) && $incluirExpirado == true) ? 1 : 0;
        $limit = (isset($limit)) ? $limit : 10;




        $return = false;

        $connection = \Application::getNewDataBaseInstance();
        $query = "
                SELECT id, nome, linkimagem, pontosnecessarios, descricao, iniciovalidade, fimvalidade, created, modified
FROM trocapontos_produtos tp where tp.id like ?  and ((iniciovalidade > ? and fimvalidade < ?) or ? = 1)  order by 1 desc  limit ?  ;
        ";


         if ($stm = $connection->prepare($query))
        {
            $stm->bind_param('sssii',  $id, $inicioValidade, $fimValidade, $incluirExpirado, $limit);
            if ($stm->execute())
            {
                $stm->bind_result($id, $nome, $link, $pontos, $descricao, $inicioValidade, $fimValidade, $created, $modified);

                $return = array();
                 while ($stm->fetch()) {
                     $v = array();
                     $v['id'] = $id;
                     $v['nome'] = $nome;
                     $v['link'] = $link;
                     $v['pontos'] = $pontos;
                     $v['descricao'] = $descricao;

                     $v['inicioValidade'] = Utils::formatStringDate($inicioValidade, 'Y-m-d', 'd/m/Y');
                     $v['fimValidade'] = Utils::formatStringDate($fimValidade, 'Y-m-d', 'd/m/Y');
                     $v['descricao'] = $descricao;
                     $v['created'] = Utils::formatStringDate($created, 'Y-m-d', 'd/m/Y');
                     $v['modified'] = Utils::formatStringDate($modified, 'Y-m-d', 'd/m/Y');


                     array_push($return, $v);
                 }
            }

        }
         else
        {
            \Application::setMysqlLogQuery('Classe LojaPontos; Método listar; Mysql '. $connection->error);
             $this->errorCode = $connection->errno;
        }

        return $return;

    }



     public function salvarProduto($array = array())
    {

        extract($array, EXTR_OVERWRITE);

        $id = (isset($id)) ? $id : null;
        $nome = (isset($nome)) ? $nome : null;
        $link = (isset($link)) ? $link : null;
        $pontos = (isset($pontos)) ? $pontos : null;
        $inicioValidade = (isset($inicioValidade)) ? $inicioValidade : null;
        $fimValidade = (isset($fimValidade)) ? $fimValidade : null;
        $descricao = (isset($descricao)) ? $descricao : null;




         $return = false;



        $connection = \Application::getNewDataBaseInstance();
        //novo registro
        if ($id === null)
            $query = "
                    INSERT INTO trocapontos_produtos( nome ,linkimagem,pontosnecessarios  ,descricao  ,iniciovalidade  ,fimvalidade  ,created ) VALUES (?,?,?,?,?,?,(select now()))
            ";
        else
            // atualiza registro
            $query = "UPDATE trocapontos_produtos SET nome = ?  ,linkimagem = ? ,pontosnecessarios = ? ,descricao = ? ,iniciovalidade = ?,fimvalidade = ? WHERE id = ?";



         if ($stm = $connection->prepare($query))
         {
            if ($id === null)
                $stm->bind_param('ssisss', $nome, $link, $pontos, $descricao, $inicioValidade, $fimValidade);
             else
                 $stm->bind_param('ssisssi', $nome, $link, $pontos, $descricao, $inicioValidade, $fimValidade, $id);
            if ($stm->execute())
            {
                if($id === null)
                    $return = $connection->insert_id;
                else
                    $return = $id;
            }
             else
             {
                 \Application::setMysqlLogQuery('Classe LojaPontos; Método salvar; Mysql '. $connection->error);
                    $this->errorCode = $connection->errno;
             }

         }
         else
        {
            \Application::setMysqlLogQuery('Classe LojaPontos; Método salvar; Mysql '. $connection->error);
             $this->errorCode = $connection->errno;
        }
        return $return;

    }


    public function excluirProduto($id)
     {
        $return = false;

        $connection = \Application::getNewDataBaseInstance();

        $query = "delete from trocapontos_produtos where id = ?";

         if ($stm = $connection->prepare($query))
         {
                $stm->bind_param('i', $id);
                if ($stm->execute())
                {
                        $return = true;
                }
                 else
                 {
                     \Application::setMysqlLogQuery('Classe LojaPontos; Método excluir; Mysql '. $connection->error);
                        $this->errorCode = $connection->errno;
                 }
         }
         else
        {
            \Application::setMysqlLogQuery('Classe LojaPontos; Método excluir; Mysql '. $connection->error);
             $this->errorCode = $connection->errno;
        }
        return $return;

     }


}
