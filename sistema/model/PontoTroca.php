<?php
namespace Gauchacred\model;


/**
 * @author moises
 * @version 1.0
 * @created 10-nov-2015 16:24:32
 */

use Gauchacred\library\php\MySqlError as MySqlError;
use Gauchacred\library\php\Utils;

class PontoTroca implements MySqlError
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
        $idUsuario = (isset($idUsuario)) ? $idUsuario : '%';
        $idContrato = (isset($idContrato)) ? $idContrato : '%';
        $inicioValidade = (isset($inicioValidade)) ? $inicioValidade : '2012-01-01';
        $fimValidade = (isset($fimValidade)) ? $fimValidade : '2100-01-01';
				$incluirExpirado = (isset($incluirExpirado) && $incluirExpirado == true) ? true : false;
				$incluirUsados = (isset($incluirUsados)  && $incluirUsados == true  ) ? true : false;

        $limit = (isset($limit)) ? $limit : 10;




        $return = false;

        $connection = \Application::getNewDataBaseInstance();
        $query = "
        SELECT id, contratos_id, usuarios_id, valorporponto, pontosobtidos, pontosresgatados, datavalidade,
created, modified FROM trocapontos_pontos where ";
        if ($incluirExpirado == false)
          $query .= " (now() >= created and  now() <= datavalidade ) and ";
				if ($incluirUsados == false)
					$query .= ($incluirExpirado == true) ? " and pontosresgatados < pontosobtidos and pontosobtidos > 0 and " : " pontosresgatados < pontosobtidos and pontosobtidos > 0 and ";
        $query .= " contratos_id like ? and usuarios_id like ? and id like ? order by 1 limit ?; ";


         if ($stm = $connection->prepare($query))
        {
            $stm->bind_param('sssi',  $idContrato, $idUsuario, $id, $limit);
            if ($stm->execute())
            {
                $stm->bind_result($id, $idContrato, $idUsuario, $valorPonto, $pontosObtidos, $pontosResgatados, $dataValidade, $created, $modified);

                $return = array();
                 while ($stm->fetch()) {
                     $v = array();
                     $v['id'] = $id;
                     $v['idContrato'] = $idContrato;
                     $v['idUsuario'] = $idUsuario;
                     $v['valorPonto'] = $valorPonto;
                     $v['pontosObtidos'] = $pontosObtidos;
                     $v['pontosResgatados'] = $pontosResgatados;
                     $v['validade'] = Utils::formatStringDate($dataValidade, 'Y-m-d', 'd/m/Y');
                     $v['created'] = Utils::formatStringDate($created, 'Y-m-d H:i:s', 'd/m/Y H:i:s');
                     $v['modified'] = Utils::formatStringDate($modified, 'Y-m-d H:i:s', 'd/m/Y H:i:s');
                     array_push($return, $v);
                 }
            }

        }
         else
        {
            \Application::setMysqlLogQuery('Classe PontoTroca; Método listar; Mysql '. $connection->error);
             $this->errorCode = $connection->errno;
        }

        return $return;

    }
    
    
    public function listarHistorico($params = array())
    {

        extract($params, EXTR_OVERWRITE);

        $idUsuario = (isset($idUsuario) && $idUsuario !== null) ? $idUsuario : '%';
        $validadeInicial = (isset($validadeInicial)) ? $validadeInicial : '1';
        $validadeFinal = (isset($validadeFinal)) ? $validadeFinal : '1';

        $limit = (isset($limit)) ? $limit : 10;




        $return = false;

        $connection = \Application::getNewDataBaseInstance();
        $query = "
            SELECT tp.id, tp.contratos_id, tp.valorporponto, tp.pontosobtidos, tp.pontosresgatados, tp.datavalidade,
            tp.created, tp.modified, u.id as 'idusuario', u.nome as 'nomeusuario'
            FROM trocapontos_pontos tp
              inner join usuarios u on u.id = tp.usuarios_id 
            where (( tp.created >= ? or ? = '1' )  and  (tp.datavalidade <= ? or ? = '1')   )
            and u.id like ? order by 1 desc limit ?";


         if ($stm = $connection->prepare($query))
        {
            $stm->bind_param('sssssi',  $validadeInicial, $validadeInicial, $validadeFinal, $validadeFinal, $idUsuario, $limit);
            if ($stm->execute())
            {
                $stm->bind_result($id, $idContrato, $valorPonto, $pontosObtidos, $pontosResgatados, $dataValidade, $created, $modified, $idUsuario, $nomeUsuario);

                $return = array();
                 while ($stm->fetch()) {
                     $v = array();
                     $v['id'] = $id;
                     $v['idContrato'] = $idContrato;
                     $v['idUsuario'] = $idUsuario;
                     $v['nomeUsuario'] = $nomeUsuario;
                     $v['valorPonto'] = $valorPonto;
                     $v['pontosObtidos'] = $pontosObtidos;
                     $v['pontosResgatados'] = $pontosResgatados;
                     $v['validade'] = Utils::formatStringDate($dataValidade, 'Y-m-d', 'd/m/Y');
                     $v['created'] = Utils::formatStringDate($created, 'Y-m-d H:i:s', 'd/m/Y');
                     $v['modified'] = Utils::formatStringDate($modified, 'Y-m-d H:i:s', 'd/m/Y H:i:s');
                     array_push($return, $v);
                 }
            }

        }
         else
        {
            \Application::setMysqlLogQuery('Classe PontoTroca; Método listarHistorico; Mysql '. $connection->error);
             $this->errorCode = $connection->errno;
        }

        return $return;

    }
    
    
    
    public function listarResgates($params = array())
    {

        extract($params, EXTR_OVERWRITE);

        $idUsuario = (isset($idUsuario) && $idUsuario !== null) ? $idUsuario : '%';
        $dataInicial = (isset($dataInicial)) ? $dataInicial : '1';
        $dataFinal = (isset($dataFinal)) ? $dataFinal : '1';

        $limit = (isset($limit)) ? $limit : 10;




        $return = false;

        $connection = \Application::getNewDataBaseInstance();
        $query = "
            select 
                u.nome as 'usuario',
                p.nome as 'produto',
                r.created as 'datatroca',
                count(distinct r.id) as 'quantidade',
                sum(r.pontosutilizados) as 'pontosutilizados',
                p.id as 'idproduto'

                from trocapontos_resgate r
                  inner join trocapontos_produtos p on p.id = r.trocapontos_produtos_id
                  inner join usuarios u on u.id = r.usuarios_id
                where u.id like ?
                and ((r.created >= ? or ? = '1') and  (r.created <= ? or ? = '1'))
                group by u.id, p.id, date_format(r.created, '%Y%m%d%H%i') order by 3 desc limit ?";


         if ($stm = $connection->prepare($query))
        {
            $stm->bind_param('sssssi', $idUsuario, $dataInicial, $dataInicial, $dataFinal, $dataFinal,  $limit);
            if ($stm->execute())
            {
                $stm->bind_result($nomeUsuario, $nomeProduto, $dataTroca, $quantidade, $pontosUtilizados, $idProduto);

                $return = array();
                 while ($stm->fetch()) {
                     $v = array();
                     $v['nomeUsuario'] = $nomeUsuario;
                     $v['nomeProduto'] = $nomeProduto;
                     $v['quantidade'] = $quantidade;
                     $v['nomeUsuario'] = $nomeUsuario;
                     $v['pontosUtilizados'] = $pontosUtilizados;
                     $v['idProduto'] = $idProduto;
                     $v['dataTroca'] = Utils::formatStringDate($dataTroca, 'Y-m-d H:i:s', 'd/m/Y H:i:s');
                     array_push($return, $v);
                 }
            }

        }
         else
        {
            \Application::setMysqlLogQuery('Classe PontoTroca; Método listarResgates; Mysql '. $connection->error);
             $this->errorCode = $connection->errno;
        }

        return $return;

    }
    
    public function excluir($id)
     {
        $return = false;
        
        $connection = \Application::getNewDataBaseInstance();

        $query = "DELETE FROM trocapontos_pontos WHERE id = ?";

         if ($stm = $connection->prepare($query))
         {
                $stm->bind_param('i', $id);
                if ($stm->execute())
                {
                        $return = true;
                }
                 else
                 {
                     \Application::setMysqlLogQuery('Classe PontoTroca; Método excluir; Mysql '. $connection->error); 
                        $this->errorCode = $connection->errno;
                 }
         }
         else
        {
            \Application::setMysqlLogQuery('Classe PontoTroca; Método excluir; Mysql '. $connection->error); 
             $this->errorCode = $connection->errno;
        }
        return $return;
        
     }


    public function salvar($params = array())
    {
      extract($params, EXTR_OVERWRITE);

      $idUsuario = (isset($idUsuario)) ? $idUsuario : null;
      $idContrato = (isset($idContrato)) ? $idContrato : null;
      $created = (isset($created)) ? str_replace("'","", $created) : null;
      $quantidadeDiasValidos = (isset($quantidadeDiasValidos)) ? $quantidadeDiasValidos : 0;
      $valorPonto = (isset($valorPonto)) ? $valorPonto : null;
      $pontosObtidos = (isset($pontosObtidos)) ? $pontosObtidos : null;
      $pontosResgatados = (isset($pontosResgatados)) ? $pontosResgatados : null;

			if ($created === null)
			{
				\Application::setMysqlLogQuery('Classe PontoTroca; Método salvar - Data de criação não definida;');
				return false;
			}

      $return = false;
      $connection = \Application::getNewDataBaseInstance();

      $query = "INSERT INTO trocapontos_pontos(contratos_id  ,usuarios_id  ,valorporponto  ,pontosobtidos   , datavalidade  , created )
       VALUES (?,?,?,?, (ADDDATE( ?, INTERVAL ? DAY)), ? )";



      if ($stm = $connection->prepare($query))
      {
         $stm->bind_param('iidisis',  $idContrato, $idUsuario, $valorPonto, $pontosObtidos, $created, $quantidadeDiasValidos, $created);
         if ($stm->execute())
         {
           $return = true;
         }else
        {
            \Application::setMysqlLogQuery('Classe PontoTroca; Método salvar; Mysql '. $connection->error);
             $this->errorCode = $connection->errno;
        }
      }else{
          \Application::setMysqlLogQuery('Classe PontoTroca; Método salvar; Mysql '. $connection->error);
           $this->errorCode = $connection->errno;
      }

      return $return;
    }


		public function trocarPontos($params = array())
		{
			extract($params, EXTR_OVERWRITE);

      $idUsuario = (isset($idUsuario)) ? $idUsuario : null;
      $produto = (isset($produto)) ? $produto : null;
			$tipoConsumo = (isset($tipoConsumo)) ? $tipoConsumo : null;
			$itensConsumir = (isset($itensConsumir)) ? $itensConsumir : null;
			$quantidade = (isset($quantidade)) ? $quantidade : null;
			$totalPontos = (isset($totalPontos)) ? $totalPontos : null;

			$return = false;
      $connection = \Application::getNewDataBaseInstance();
			$connection->autocommit(false);

			// INSERE A INFORMAÇÃO DE RESGATE
			$query = "INSERT INTO trocapontos_resgate(usuarios_id  ,trocapontos_produtos_id  ,pontosutilizados  ,created) VALUES ( ?, ?, ?, (select now()) )";
			$insertResgate = null;
			if ($stm = $connection->prepare($query))
      {
				$insertResgate = true;
         $stm->bind_param('iii',   $idUsuario, $idProduto, $pontosUtilizados);
				 for ($i = 0; $i < $quantidade; $i++)
				 {
					   $idProduto = $produto['id'];
						 $pontosUtilizados = $produto['pontos'];
						 if ($stm->execute())
		         {
		           $return = true;
		         }else
		         {
		            \Application::setMysqlLogQuery('Classe PontoTroca; Método trocarPontos - INSERIR RESGATE; Mysql '. $connection->error);
		             $this->errorCode = $connection->errno;
								 $insertResgate = false;
								 break;
		         }
				 }

      }else{
          \Application::setMysqlLogQuery('Classe PontoTroca; Método trocarPontos - INSERIR RESGATE; Mysql '. $connection->error);
           $this->errorCode = $connection->errno;
					 $insertResgate = false;
      }

			// ATUALIZA OS PONTOS PARA JÁ UTILIZADOS
			$query = "UPDATE trocapontos_pontos SET  pontosresgatados = pontosobtidos WHERE id = ?";
			$updatePontos = true;
			$idItemParcial = 0;
			$pontosSomados = 0;
			$residual = 0;
			if ($tipoConsumo == 'total' || ($tipoConsumo == 'parcial' && count($itensConsumir) > 1))
				if ($stm = $connection->prepare($query))
	      {
	         $stm->bind_param('i',   $id);
					 foreach ($itensConsumir as $i => $item)
					 {
						  if ($tipoConsumo == 'parcial' && count($itensConsumir) == ($i+1) )
							{
								$idItemParcial = $i;
								break;
							}


							 $id = $item['id'];
							 if (! $stm->execute())
			         {
									\Application::setMysqlLogQuery('Classe PontoTroca; Método trocarPontos - ATUALIZAR PONTOS; Mysql '. $connection->error);
			             $this->errorCode = $connection->errno;
									 $updatePontos = false;
									 break;
			         }
							 $pontosSomados += $item['pontosObtidos'] - $item['pontosResgatados'];
					 }

	      }else{
	          \Application::setMysqlLogQuery('Classe PontoTroca; Método trocarPontos - ATUALIZAR PONTOS; Mysql '. $connection->error);
	           $this->errorCode = $connection->errno;
						 $insertResgate = false;
	      }



			// REALIZA UPDATE DOS PONTOS PARCIAIS
			$updatePontosParciais = true;
			if ($tipoConsumo == 'parcial' && count($itensConsumir) > 0)
			{

				//$pontosParciais = $totalPontos - ($quantidade * $produto['pontos']);
				$item = $itensConsumir[$idItemParcial];

				if (count($itensConsumir) == 1)
					$pontosParciais = ($quantidade * $produto['pontos']);
				else
					$pontosParciais = ($quantidade * $produto['pontos']) - $pontosSomados;


				$query = "UPDATE trocapontos_pontos SET  pontosresgatados = (pontosresgatados + ?) WHERE id = ?";

				if ($stm = $connection->prepare($query))
	      {
	         $stm->bind_param('ii',  $pontosParciais, $item['id']);
	         if ($stm->execute())
	         {
	           $return = true;
	         }else
		       {
		            \Application::setMysqlLogQuery('Classe PontoTroca; Método salvar - UPDATE PARCIAL; Mysql '. $connection->error);
		             $this->errorCode = $connection->errno;
								 $updatePontosParciais = false;
		        }
		      }else{
		          \Application::setMysqlLogQuery('Classe PontoTroca; Método salvar - UPDATE PARCIAL; Mysql '. $connection->error);
		           $this->errorCode = $connection->errno;
							 $updatePontosParciais = false;
		      }
			}

			if ($insertResgate == true && $updatePontos == true && $updatePontosParciais == true)
			{
				$connection->commit();
				return true;
			}
			else {
				$connection->rollback();
				return false;
			}



		}

  }
