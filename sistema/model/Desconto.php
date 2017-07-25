<?php
namespace Gauchacred\model;


/**
 * @author moises
 * @version 1.0
 * @created 10-nov-2015 16:24:32
 */

use Gauchacred\library\php\MySqlError as MySqlError;
use Gauchacred\library\php\Utils;
use Gauchacred\library\php\ApiFile;


class Desconto implements MySqlError
{

	private $errorCode = '';


    public function __construct()
    {

    }

    public function getMysqlError()
    {
        return $this->errorCode;
    }



    public function listarResumoRelatorios($params = array())
    {
        extract($params, EXTR_OVERWRITE);

        $id = (! empty($id)) ? $id : '%';
        $idusuario = (! empty($idusuario)) ? $idusuario : '%';
        $datainicio =  (! empty($datainicio)) ? $datainicio : '1';
        $datafim =  (! empty($datafim)) ? $datafim : '1';
        $nomecliente = (! empty($nomecliente)) ? '%' . $nomecliente . '%' : '%';
        $limit =  (! empty($limit)) ? $limit : 10;

        $return = false;

        $connection = \Application::getNewDataBaseInstance();
        $query = "
                select distinct
                d.id, d.qtdcontratos, d.valorcontratos, d.valorcomissoes, d.valordescontos, d.descricao, d.nomearquivo, d.nomesistema, d.created, d.comissaobloqueada, u.nome as 'nomeusuario'
                from descontos d
                    left join usuarios u on u.id = d.usuarios_id
                  left join (
                    select distinct
                    dc.descontos_id, c.nome as 'nomecliente'
                    from descontos_contratos dc
                      inner join contratos c on c.id = dc.contratos_id
                  ) co on co.descontos_id = d.id

                where d.usuarios_id like ? and (d.created >= ? or ? = '1') and  (d.created <= ? or ? = '1')
                and d.id like ? and (co.nomecliente like ? or ? = '%' ) order by d.id desc limit ?
        ";


         if ($stm = $connection->prepare($query))
        {
            $stm->bind_param('ssssssssi', $idusuario, $datainicio, $datainicio, $datafim, $datafim, $id, $nomecliente, $nomecliente , $limit);
            if ($stm->execute())
            {
                $stm->bind_result($id, $qtdContratos, $valorContratos, $valorComissoes, $valorDescontos, $descricao, $nomeArquivo, $nomeArquivoSistema, $created, $comissaoBloqueada, $nomeUsuario );

                $return = array();
                 while ($stm->fetch()) {
                     $v = array();
                     $v['id'] = $id;
                     $v['qtdContratos'] = $qtdContratos;
                     $v['valorContratos'] = $valorContratos;
                     $v['valorComissoes'] = $valorComissoes;
                     $v['valorDescontos'] = $valorDescontos;
                     $v['descricao'] = $descricao;
                     $v['nomeArquivo'] = $nomeArquivo;
                     $v['nomeArquivoSistema'] = $nomeArquivoSistema;
                     $v['created'] = Utils::formatStringDate($created, 'Y-m-d H:i:s', 'd/m/Y H:i:s');
                     $v['comissaoBloqueada'] = (boolean) $comissaoBloqueada;

                     $v['nomeUsuario'] = $nomeUsuario;

                     array_push($return, $v);
                 }
            }

        }
         else
        {
            \Application::setMysqlLogQuery('Classe Desconto; Método listarResumoRelatorio Mysql '. $connection->error);
             $this->errorCode = $connection->errno;
        }

        return $return;


    }


    public function listarContratosDescontados($params = array())
    {
        extract($params, EXTR_OVERWRITE);

        $iddesconto = (! empty($iddesconto)) ? $iddesconto : '%';

        $limit =  (! empty($limit)) ? $limit : 10;

        $return = false;

        $connection = \Application::getNewDataBaseInstance();
        $query = "
                select
                d.nomesistema as 'nomearquivosistema',
                d.created,
                u.nome as 'nomeusuario',
                 d.comissaobloqueada,
                c.id as 'idcontrato',
                c.nomebancocontrato as 'banco',
                c.nomeconvenio as 'convenio',
                c.nometabela as 'tabela',
                c.nomeoperacao as 'operacao',
                c.nome as 'cliente',
                c.quantidadeparcelas as 'prazo',
                c.valorparcela,
                c.valortotal as 'valorcontrato',
                sum(cc.percentualgrupo) + sum(cc.percentualsupervisor) as 'percentualcomissao',
                sum(cc.valorgrupo) + sum(cc.valorsupervisor) as 'valorcomissao'

                from descontos d
                  left join usuarios u on u.id = d.usuarios_id
                  inner join descontos_contratos dc on dc.descontos_id = d.id
                  inner join contratos c on c.id = dc.contratos_id
                  left join comissoescontrato cc on cc.contratos_id = c.id and cc.usuarios_id = dc.usuarios_id

                where d.id = ?
                group by c.id, cc.usuarios_id limit ?
        ";


         if ($stm = $connection->prepare($query))
        {
            $stm->bind_param('ii', $iddesconto , $limit);
            if ($stm->execute())
            {
                $stm->bind_result($nomeArquivoSistema, $created, $nomeUsuario, $comissaoBloqueada, $idContrato, $banco, $convenio, $tabela, $operacao, $cliente, $prazo, $valorParcelas, $valorContrato, $percentualComissao, $valorComissao );

                $return = array();
                 while ($stm->fetch()) {
                     $v = array();
                     $v['nomeArquivoSistema'] = $nomeArquivoSistema;
                     $v['nomeUsuario'] = $nomeUsuario;
                     $v['comissaoBloqueada'] = (boolean) $comissaoBloqueada;
                     $v['idContrato'] = $idContrato;
                     $v['nomeBanco'] = $banco;
                     $v['nomeConvenio'] = $convenio;
                     $v['nomeTabela'] = $tabela;
                     $v['nomeOperacao'] = $operacao;
                     $v['nomeCliente'] = $cliente;
                     $v['prazo'] = $prazo;
                     $v['valorParcelas'] = $valorParcelas;
                     $v['valorContrato'] = $valorContrato;
                     $v['percentualComissao'] = $percentualComissao;
                     $v['valorComissao'] = $valorComissao;
                     $v['created'] = Utils::formatStringDate($created, 'Y-m-d H:i:s', 'd/m/Y H:i:s');
                     array_push($return, $v);
                 }
            }

        }
         else
        {
            \Application::setMysqlLogQuery('Classe Desconto; Método listarContratosDescontados Mysql '. $connection->error);
             $this->errorCode = $connection->errno;
        }

        return $return;


    }



    public function listarAdiantamentosDescontados($params = array())
    {
        extract($params, EXTR_OVERWRITE);

        $iddesconto = (! empty($iddesconto)) ? $iddesconto : '%';

        $limit =  (! empty($limit)) ? $limit : 10;

        $return = false;

        $connection = \Application::getNewDataBaseInstance();
        $query = "
                select
                a.id as 'idadiantamento',
                a.descricao as 'descricao',
                u.nome as 'nomeusuario',
                ad.numparcela as 'parcela',
                ad.valordescontado,
                mindata.data as 'primeiraparcela'

                from descontos d
                  inner join adiantamentosdescontados ad on ad.descontos_id = d.id
                  inner join adiantamentos a on a.id = ad.adiantamentos_id
                  inner join usuarios u on u.id = a.usuarios_id
                  left join (
                    select min(a1.modified) as 'data', a1.adiantamentos_id  from adiantamentosdescontados a1 group by a1.adiantamentos_id
                  ) as mindata on mindata.adiantamentos_id = a.id

                where d.id = ? limit ?
        ";


         if ($stm = $connection->prepare($query))
        {
            $stm->bind_param('ii', $iddesconto , $limit);
            if ($stm->execute())
            {
                $stm->bind_result($idAdiantamento, $descricao, $nomeUsuario, $parcela, $valorDescontado, $modified );

                $return = array();
                 while ($stm->fetch()) {
                     $v = array();
                     $v['idAdiantamento'] = $idAdiantamento;
                     $v['descricao'] = $descricao;
                     $v['nomeUsuario'] = $nomeUsuario;
                     $v['parcela'] = $parcela;
                     $v['valorDescontado'] = $valorDescontado;
                     $v['modified'] = Utils::formatStringDate($modified, 'Y-m-d H:i:s', 'd/m/Y H:i:s');


                     array_push($return, $v);
                 }
            }

        }
         else
        {
            \Application::setMysqlLogQuery('Classe Desconto; Método listarAdiantamentosDescontados Mysql '. $connection->error);
             $this->errorCode = $connection->errno;
        }

        return $return;


    }




     public function salvarRelatorio($idUsuario, $descricao, $comissaoBloqueada, $contratos, $descontos)
    {
        $return = false;
        $connection = \Application::getNewDataBaseInstance();
        $connection->autocommit(false);
        // converte o array para texto separado por virgula. ex: 5,10,7
        $contratos =  str_replace('"','', implode(',', $contratos));
        $descontos =  str_replace('"','', implode(',', $descontos));

        
        // SELECIONA OS CONTRATOS QUE FARÃO PARTE DO NEGÓCIO PARA OBTER OS VALORES
        $listaContratos = array();
        $query = "
        select distinct
        c.id,
        round( sum(cc.valorgrupo ) + sum(cc.valorsupervisor ),2) as 'comissao',
        c.valortotal as 'valorcontrato'
        from comissoescontrato cc
        inner join contratos c on c.id = cc.contratos_id

        where c.id in (". $contratos .")
        and cc.usuarios_id = ?
        group by c.id
        order by c.id
        ";


        if ($stm = $connection->prepare($query))
        {
           $stm->bind_param('i',  $idUsuario);
           if ($stm->execute())
           {
               $stm->bind_result($id, $comissao, $valorContrato);

               $return = array();
                while ($stm->fetch()) {
                    $v = array();
                    $v['id'] = $id;
                    $v['comissao'] = $comissao;
                    $v['valorContrato'] = $valorContrato;

                    array_push($listaContratos, $v);
                }
           }

        }
        else
       {
           \Application::setMysqlLogQuery('Classe Desconto; Método salvarRelatorio - listar contratos Mysql '. $connection->error);
            $this->errorCode = $connection->errno;
       }

       // SELECIONA OS DESCONTOS QUE FARÃO PARTE DO NEGÓCIO PARA OBTER OS VALORES
       $listaDescontos = array();
        if (! empty($descontos))
        {
            // Executa somente se tiver desconto selecionado
           $query = "
           select distinct
           a.id, a.valor , a.descontarpor, max(ifnull(ad.numparcela,0)) as 'ultimaparcela',
                 a.valortotalpagar, a.valortotalpago
           from adiantamentos a
             left join adiantamentosdescontados ad on ad.adiantamentos_id = a.id
           where a.id in (". $descontos .")
           group by a.id
           ";

           if ($stm = $connection->prepare($query))
           {
              //$stm->bind_param('i',  $idUsuario);
              if ($stm->execute())
              {
                  $stm->bind_result($id, $parcela, $descontarPor, $ultimaParcela, $valorTotalDevido, $valorPago);


                   while ($stm->fetch()) {
                       $v = array();
                       $v['id'] = $id;
                       $v['parcela'] = $parcela;
                       $v['descontarPor'] = $descontarPor;
                       $v['ultimaParcela'] = $ultimaParcela;
                                         $v['valorTotalDevido'] = $valorTotalDevido;
                                         $v['valorTotalPago'] = $valorPago;
                       array_push($listaDescontos, $v);
                   }
              }

           }
            else
           {
              \Application::setMysqlLogQuery('Classe Desconto; Método salvarRelatorio - listar descontos Mysql '. $connection->error);
               $this->errorCode = $connection->errno;
           }
        }


      if (count($listaContratos) < 1)
      {
        \Application::setMysqlLogQuery('Classe Desconto; Não foi possivel obter a lista de Contratos ');
        return false;
      }


      // OBTEM OS VALORES A SEREM SOMADOS
      $valorContratos = 0;
      $valorComissoes = 0;
      $valorDescontos = 0;

      foreach ($listaContratos as $i => $value) {
        $valorContratos += $value['valorContrato'];
        $valorComissoes += $value['comissao'];
      }

      foreach ($listaDescontos as $i => $value) {
         if ($value['descontarPor'] == 'Percentual')
				 {
					  // Verifica se deve descontar o máximo do percentual sobre a comissão ou somente o
						// retante devido
						$vlrDevido = $value['valorTotalDevido'] - $value['valorTotalPago'];
						$vlrDescontar = $valorComissoes * ($value['parcela']/100);
						if ($vlrDescontar > $vlrDevido)
							$vlrDescontar = $vlrDevido;

						$valorDescontos += $vlrDescontar;
				 }
         else
            $valorDescontos += $value['parcela'];
      }


      // SALVA O DESCONTO
       $idDesconto = null;
        $query = "INSERT INTO descontos(usuarios_id ,qtdcontratos ,valorcontratos  ,valorcomissoes  ,valordescontos
        ,descricao   ,nomesistema  ,created, comissaobloqueada)
        VALUES (?, ?, ?, ?,  ?, ?, ? , (select now()), ? )";

       $nomeArquivoSistema = str_replace('.','',ApiFile::normalizeName( substr($descricao,0,20) )) . '_' . uniqid() . '.pdf';
       $qtdContratos = count($listaContratos);

       if ($stm = $connection->prepare($query))
       {
           $stm->bind_param('iidddssi', $idUsuario, $qtdContratos, $valorContratos, $valorComissoes, $valorDescontos, $descricao, $nomeArquivoSistema, $comissaoBloqueada );
          if ($stm->execute())
          {
                  $idDesconto = $connection->insert_id;
          }
           else
           {
               \Application::setMysqlLogQuery('Classe Desconto; Método salvarRelatorio - inserir desconto; Mysql '. $connection->error);
                  $this->errorCode = $connection->errno;
           }

       }
       else
      {
          \Application::setMysqlLogQuery('Classe Desconto; Método salvarRelatorio - inserir desconto; Mysql '. $connection->error);
           $this->errorCode = $connection->errno;
      }
      // se não salvar o desconto sai
      if ($idDesconto == null)
        return false;

      // INSERE OS CONTRATOS
      $flag = true;

        $query = "INSERT INTO descontos_contratos(descontos_id  ,contratos_id  ,usuarios_id  ,totalcomissaocontrato)
          VALUES (?, ?, ?, ?)";


       if ($stm = $connection->prepare($query))
       {
          $stm->bind_param('iiid', $idDesconto, $idContrato, $idUsuario, $totalComissaoContrato );
         foreach ($listaContratos as $i => $value)
         {
             $idContrato = $value['id'];
             $totalComissaoContrato = $value['comissao'];
             if (! $stm->execute())
             {
                     $flag = false;
                     \Application::setMysqlLogQuery('Classe Desconto; Método salvarRelatorio - inserir contratos; Mysql '. $connection->error);
                      $this->errorCode = $connection->errno;
                     break;
             }
         } // fecha foreach
       }
       else
      {
         $flag = false;
          \Application::setMysqlLogQuery('Classe Desconto; Método salvarRelatorio - inserir contratos; Mysql '. $connection->error);
           $this->errorCode = $connection->errno;
      }

      if ($flag == false)
        return false;



        // INSERE OS ADIANTAMENTOS DESCONTADOS
        $flag = true;

        $query = "INSERT INTO adiantamentosdescontados(adiantamentos_id  ,descontos_id  ,numparcela  ,valordescontado)
VALUES (?, ?, ?, ?)";

        if (count($listaDescontos) > 0 )
        {
            // executa somente se houver adiantamentos para serem abatidos
             if ($stm = $connection->prepare($query))
             {
                $stm->bind_param('iiid', $idAdiantamento, $idDesconto, $ultimaParcela, $valorDescontado );
               foreach ($listaDescontos as $i => $value)
               {
                   $idAdiantamento = $value['id'];
                   $ultimaParcela = $value['ultimaParcela'] + 1;
                   if ($value['descontarPor'] == 'Percentual')
                                 {
                                     // Verifica se deve descontar o máximo do percentual sobre a comissão ou somente o
                                     // retante devido
                                     $vlrDevido = $value['valorTotalDevido'] - $value['valorTotalPago'];
                                     $vlrDescontar = $valorComissoes * ($value['parcela']/100);
                                     if ($vlrDescontar > $vlrDevido)
                                         $vlrDescontar = $vlrDevido;

                                         $valorDescontado =   $vlrDescontar;
                                 }
                   else
                    $valorDescontado = $value['parcela'];

                   if (! $stm->execute())
                   {
                           $flag = false;
                           \Application::setMysqlLogQuery('Classe Desconto; Método salvarRelatorio - inserir adiantamentosDescontados; Mysql '. $connection->error);
                            $this->errorCode = $connection->errno;
                           break;
                   }
               } // fecha foreach
             }
             else
            {
               $flag = false;
                \Application::setMysqlLogQuery('Classe Desconto; Método salvarRelatorio - inserir adiantamentosDescontados; Mysql '. $connection->error);
                 $this->errorCode = $connection->errno;
            }
        }

        if ($flag == false)
          return false;


          // ATUALIZA OS VALORES PAGOS DOS ADIANTAMENTOS
          $flag = true;

          $query = "UPDATE adiantamentos set valortotalpago = (select ifnull(sum(ad.valordescontado),0) from adiantamentosdescontados ad where ad.adiantamentos_id = ?) where id = ?;";


           if ($stm = $connection->prepare($query))
           {
              $stm->bind_param('ii', $idAdiantamento, $idAdiantamento );
             foreach ($listaDescontos as $i => $value)
             {
                 $idAdiantamento = $value['id'];

                 /*if ($value['descontarPor'] == 'Percentual')
                     $valorDescontado =   $valorComissoes   *    ($value['parcela'] / 100);
                 else
                  $valorDescontado = $value['parcela'];
									*/
                 if (! $stm->execute())
                 {
                         $flag = false;
                         \Application::setMysqlLogQuery('Classe Desconto; Método salvarRelatorio - atualizar adiantamento; Mysql '. $connection->error);
                          $this->errorCode = $connection->errno;
                         break;
                 }
             } // fecha foreach
           }
           else
          {
             $flag = false;
              \Application::setMysqlLogQuery('Classe Desconto; Método salvarRelatorio - atualizar adiantamento; Mysql '. $connection->error);
               $this->errorCode = $connection->errno;
          }

          if ($flag == false)
            return false;


          // ATUALIZA OS ADIANTAMENTOS PAGOS
          $flag = true;

            $query = "update adiantamentos a  set a.encerrado = 1  where a.valortotalpago >= a.valortotalpagar and a.valortotalpagar > 0";


           if ($stm = $connection->prepare($query))
           {
            //  $stm->bind_param('iiid', $idAdiantamento, $idDesconto, $idAdiantamento, $valorDescontado );
              if (! $stm->execute())
              {
                      $flag = false;
                      \Application::setMysqlLogQuery('Classe Desconto; Método salvarRelatorio - encerrar adiantamentos; Mysql '. $connection->error);
                       $this->errorCode = $connection->errno;
              }
           }
           else
          {
             $flag = false;
              \Application::setMysqlLogQuery('Classe Desconto; Método salvarRelatorio - encerrar adiantamentos; Mysql '. $connection->error);
               $this->errorCode = $connection->errno;
          }

          if ($flag == false)
            return false;



        if ($flag !== false)
            $connection->commit();
        else
            $connection->rollback();
        return $flag;

    }






}
?>
