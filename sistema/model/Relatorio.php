<?php
namespace Gauchacred\model;


/**
 * @author moises
 * @version 1.0
 * @created 10-nov-2015 16:24:32
 */

use Gauchacred\library\php\MySqlError as MySqlError;
use Gauchacred\library\php\Utils as Utils;

class Relatorio implements MySqlError
{

	private $errorCode = '';
    private $mysqlError = '';
    
    public function __construct()
    {
        
    }
    
    public function getMysqlError()
    {
        return $this->errorCode;
    }
    
    public function comissaoVendedor($idUsuario, $status = null, $dataInicial = null, $dataFinal = null, $nomeOperacao = null, $dataPagamentoInicio = null, $dataPagamentoFim = null, $statusPagamento = null, $dataBancoInicio = null, $dataBancoFim = null, $statusBanco = null)
    {
        
        
        $return = false;
        
        $status = ($status === null) ? '%' : $status;
        $dataInicial = ($dataInicial === null) ? '0001-01-01' : $dataInicial;
        $dataFinal = ($dataFinal === null) ? '2100-01-01' : $dataFinal;
        $dataPagamentoInicio = ($dataPagamentoInicio === null) ? '1900-01-01' : $dataPagamentoInicio;
        $dataPagamentoFim = ($dataPagamentoFim === null) ? '2100-01-01' : $dataPagamentoFim;
        $dataBancoInicio = ($dataBancoInicio === null) ? '1900-01-01' : $dataBancoInicio;
        $dataBancoFim = ($dataBancoFim === null) ? '2100-01-01' : $dataBancoFim;
        $nomeOperacao = ($nomeOperacao === null) ? '%' : $nomeOperacao;
        $statusPagamento = (empty($statusPagamento)) ? null : $statusPagamento;
        $statusBanco = (empty($statusBanco)) ? null : $statusBanco;
         switch($statusPagamento)
         {
             case null: $statusPagamento = '(c.datapagamento is null or c.datapagamento is not null)'; break;
             case 'Pago': $statusPagamento = '(c.datapagamento is not null)'; break;
             case 'Aberto': $statusPagamento = '(c.datapagamento is null)';  break;
             default: $statusPagamento = '(c.datapagamento is null or c.datapagamento is not null)'; break;
         }
        
        switch($statusBanco)
         {
             case null: $statusBanco = '(c.datapagamentobanco is null or c.datapagamentobanco is not null)'; break;
             case 'Sim': $statusBanco = '(c.datapagamentobanco is not null)'; break;
             case 'Nao': $statusBanco = '(c.datapagamentobanco is null)';  break;
             default: $statusBanco = '(c.datapagamentobanco is null or c.datapagamentobanco is not null)'; break;
         }
         
       
        $connection = \Application::getNewDataBaseInstance();
        $query = "
               select distinct
                cc.id, cc.contratos_id, cc.nomegrupo, cc.percentualgrupo, cc.valorgrupo, cc.percentualsupervisor, cc.valorsupervisor,
                u.cpf, u.nome as 'nomeusuario', c.nomeconvenio, c.nometabela, c.nomeoperacao, c.valortotal, c.status, c.codigobancoconvenio, c.nomebancocontrato, cli.nome as 'nomeCliente',
                c.quantidadeparcelas, c.valorparcela, c.datapagamento
                from comissoescontrato cc
                  inner join usuarios u on u.id = cc.usuarios_id
                  inner join contratos c on c.id = cc.contratos_id
                  inner join clientes cli on cli.cpf_cnpj = c.clientes_cpf

               where c.status like ?
               and (c.datapagamento between ? and ? or (? = '1900-01-01' and ? =  '2100-01-01'   ))
               and (c.datapagamentobanco between ? and ? or (? = '1900-01-01' and ? =  '2100-01-01'   ))
               and $statusPagamento 
               and $statusBanco 
               and c.modified between ? and ? 
               and cc.usuarios_id = ?
               and c.nomeoperacao like ?
        ";
       
        
      /*  printf( str_replace('?', '%s', $query), $status, $dataPagamentoInicio, $dataPagamentoFim, $dataPagamentoInicio, $dataPagamentoFim,
                             $dataBancoInicio, $dataBancoFim, $dataBancoInicio, $dataBancoFim,
                             $dataInicial, $dataFinal, $idUsuario, $nomeOperacao); exit;*/
        
        
         if ($stm = $connection->prepare($query))
        {
            $stm->bind_param('sssssssssssis',  $status, $dataPagamentoInicio, $dataPagamentoFim, $dataPagamentoInicio, $dataPagamentoFim,
                             $dataBancoInicio, $dataBancoFim, $dataBancoInicio, $dataBancoFim,
                             $dataInicial, $dataFinal, $idUsuario, $nomeOperacao);
            if ($stm->execute())
            {
                $stm->bind_result($id, $idContrato, $nomeGrupo, $percentualGrupo, $valorGrupo, $percentualSupervisor, $valorSupervisor, $cpf, $nomeUsuario, $nomeConvenio, $nomeTabela,
                                 $nomeOperacao, $valorTotal, $status, $codigoBancoConvenio, $nomeBancoContrato, $nomeCliente, $quantidadeParcelas, $valorParcela, $dataPagamento);
                
                $return = array();
                 while ($stm->fetch()) {
                     $v = array();
                     $v['id'] = $id;
                     $v['idContrato'] = $idContrato;
                     $v['nomeGrupo'] = $nomeGrupo;
                     $v['percentualGrupo'] = $percentualGrupo;
                     $v['valorGrupo'] = $valorGrupo;
                     $v['percentualSupervisor'] = $percentualSupervisor;
                     $v['valorSupervisor'] = $valorSupervisor;
                     $v['cpf'] = $cpf;
                     $v['nomeUsuario'] = $nomeUsuario;
                     $v['nomeConvenio'] = $nomeConvenio;
                     $v['nomeTabela'] = $nomeTabela;
                     $v['nomeOperacao'] = $nomeOperacao;
                     $v['valorTotal'] = $valorTotal;
                     $v['status'] = $status;
                     $v['codigoBancoConvenio'] = $codigoBancoConvenio;
                     $v['nomeBancoConvenio'] = $nomeBancoContrato;
                     $v['nomeCliente'] = $nomeCliente;
                     $v['quantidadeParcelas'] = $quantidadeParcelas;
                     $v['valorParcela'] = $valorParcela;
                     $v['dataPagamento'] = Utils::formatStringDate($dataPagamento, 'Y-m-d', 'd/m/Y');  
                     array_push($return, $v);           
                 }
            }
            
        }
         else
        {
            
            \Application::setMysqlLogQuery('Classe Relatorios; Método comissaoVendedor; Mysql '. $connection->error); 
             $this->mysqlError = $connection->errno;
        }
        return $return;
        
    }
    
    
     public function comissaoLoja($idConvenio = null, $idOperacao = null, $dataInicio = null, $dataFim = null, $status = null, $banco = null, $usuario = null, $statusPagamento = null, $dataPagamentoInicio = null, $dataPagamentoFim = null, $dataBancoInicio = null, $dataBancoFim = null, $statusBanco = null)
    {
         
         
         $idConvenio = ($idConvenio === null) ? '%' : $idConvenio ;
         $banco = ($banco === null) ? '%' : $banco ;
         $usuario = ($usuario === null) ? '%' : $usuario ;
         $idOperacao = ($idOperacao === null) ? '%' : $idOperacao;
         $dataInicio = ($dataInicio === null) ? '0001-01-01' : $dataInicio;
         $dataFim = ($dataFim === null) ? '2100-01-01' : $dataFim;
		 if ($status === null) 
			$status = array(1);
		 else
			if (! is_array($status))
				$status = array($status);
		
		
		$status = implode("','",$status);
		$status = "'". $status . "'";	
        
		$dataPagamentoInicio = ($dataPagamentoInicio === null) ? '0001-01-01' : $dataPagamentoInicio;
         $dataPagamentoFim = ($dataPagamentoFim === null) ? '2100-01-01' : $dataPagamentoFim;
        
         $dataBancoInicio = ($dataBancoInicio === null) ? '1900-01-01' : $dataBancoInicio;
        $dataBancoFim = ($dataBancoFim === null) ? '2100-01-01' : $dataBancoFim;
         $statusPagamento = (empty($statusPagamento)) ? null : $statusPagamento;
        $statusBanco = (empty($statusBanco)) ? null : $statusBanco;
		
		switch($statusPagamento)
         {
             case null: $statusPagamento = '(c.datapagamento is null or c.datapagamento is not null)'; break;
             case 'Pago': $statusPagamento = '(c.datapagamento is not null)'; break;
             case 'Aberto': $statusPagamento = '(c.datapagamento is null)';  break;
             default: $statusPagamento = '(c.datapagamento is null or c.datapagamento is not null)'; break;
         }
        
        
        switch($statusBanco)
         {
             case null: $statusBanco = '(c.datapagamentobanco is null or c.datapagamentobanco is not null)'; break;
             case 'Sim': $statusBanco = '(c.datapagamentobanco is not null)'; break;
             case 'Nao': $statusBanco = '(c.datapagamentobanco is null)';  break;
             default: $statusBanco = '(c.datapagamentobanco is null or c.datapagamentobanco is not null)'; break;
         }
         
		
		
         $return = false;
        
        $connection = \Application::getNewDataBaseInstance();
        $query = "
                 select distinct
                          c.id, c.clientes_cpf as 'cpf', c.subtabelas_id as 'idsubtabela', c.entidades_id as 'identidade', c.contabancariaclientes_id, c.nome, c.cep, c.rua, c.numero, c.complemento,
                          c.bairro, c.uf, c.cidade, c.codigobancocliente, c.nomebancocliente, c.contabancocliente, c.agenciabancocliente,
                          c.tipocontabancocliente, c.numerocontrato, c.created, c.modified, c.codigobancoconvenio, c.nomebancocontrato, c.nomeconvenio,
                          c.nomeoperacao, c.nometabela, c.comissaototal, c.valorseguro, c.percentualimposto, c.quantidadeparcelas, c.valorparcela,
                          c.valortotal, c.valorliquido, c.percentualloja, c.valorloja,
                          u.id as 'idusuario', u.nome as 'nomeusuario', c.status, cc.comissoes, co.comissoes

                          from contratos c
                            inner join usuarios u on u.id = c.usuarios_id
                            left join (
                              select distinct
                              cc.contratos_id, group_concat(cc.id, ',' , cc.contratos_id, ',' , cc.nomegrupo, ',', cc.percentualgrupo, ',', cc.valorgrupo, ',', cc.percentualsupervisor, ',', cc.valorsupervisor, ',',
                              us.id, ',', us.cpf, ',', us.nome SEPARATOR '|') as 'comissoes'
                              from comissoescontrato cc
                                inner join usuarios us on us.id = cc.usuarios_id
                              group by cc.contratos_id
                            ) cc on cc.contratos_id = c.id
                            left join clientes cli on cli.cpf_cnpj = c.clientes_cpf
                            inner join subtabelas su on su.id = c.subtabelas_id
                            inner join tabelas ta on ta.id = su.tabelas_id
                            left join (
                              select distinct
                              cs.subtabelas_id, group_concat(cs.subtabelas_id, ';',cs.comissao,';', cs.recebecomissao_grupos_id, ';', gu.id, ';', gu.nome SEPARATOR '|') as 'comissoes'
                              from comissoessubtabelas cs
                                inner join grupousuarios gu on gu.id = cs.grupousuarios_id
                                group by cs.subtabelas_id
                            ) co on co.subtabelas_id = c.subtabelas_id


                          where c.entidades_id like ?
						  and ( c.datapagamento between ? and ? or (? = '0001-01-01' and ? = '2100-01-01')  )
                          and (c.datapagamentobanco between ? and ? or (? = '1900-01-01' and ? =  '2100-01-01'   ))
						  and $statusPagamento
                          and $statusBanco 
                          and ta.bancos_id like ?
                          and (c.nomeoperacao = (select min(nome) from operacoessubtabelas where id like ?) or ? = '%')
                          and c.modified between ? and ?
                          and (c.status in( ". $status .") or \"$status\" = \"'1'\" )
                          and c.usuarios_id like ?
                          order by c.id

        ";
       
       
         if ($stm = $connection->prepare($query))
        {
            $stm->bind_param('sssssssssssssss',  $idConvenio, $dataPagamentoInicio, $dataPagamentoFim, $dataPagamentoInicio, $dataPagamentoFim,
                               $dataBancoInicio, $dataBancoFim, $dataBancoInicio, $dataBancoFim,
                             $banco, $idOperacao, $idOperacao, $dataInicio, $dataFim, $usuario);
            if ($stm->execute())
            {
                $stm->bind_result($id, $cpf, $idSubtabela, $idEntidade, $idContaBancariaCliente, $nomeCliente, $cep, $rua, $numeroRua, $complementoRua, $bairro, $uf, $cidade, $codigoBancoCliente, $nomeBancoCliente,  $contaBancoCliente, $agenciaBancoCliente, $tipoContaBancoCliente, $numeroContrato, $created, $modified, $codigoBancoConvenio, $nomeBancoContrato, $nomeConvenio,
                                 $nomeOperacao, $nomeTabela, $comissaoTotal, $valorSeguro, $percentualImposto, $quantidadeParcelas, $valorParcela, $valorTotal, $valorLiquido, $percentualLoja,
                                 $valorLoja, $idUsuario, $nomeUsuario, $status, $comissoes, $grupos);
                
                $return = array();
                 while ($stm->fetch()) {
                     
                     $v = array();
                     $v['id'] = $id;
                     $v['cpf'] = $cpf;
                     $v['idSubtabela'] = $idSubtabela;
                     $v['idConvenio'] = $idEntidade;
                     $v['idContaBancariaCliente'] = $idContaBancariaCliente;
                     $v['nomeCliente'] = $nomeCliente;
                     $v['cep'] = $cep;
                     $v['rua'] = $rua;
                     $v['numeroRua'] = $numeroRua;
                     $v['complementoRua'] = $complementoRua;
                     $v['bairro'] = $bairro;
                     $v['uf'] = $uf;
                     $v['cidade'] = $cidade;
                     $v['codigoBancoCliente'] = $codigoBancoCliente;
                     $v['nomeBancoCliente'] = $nomeBancoCliente;
                     $v['contaBancoCliente'] = $contaBancoCliente;
                     $v['agenciaBancoCliente'] = $agenciaBancoCliente;
                     $v['tipoContaBancoCliente'] = $tipoContaBancoCliente;
                     $v['numeroContrato'] = $numeroContrato;
                     $v['created'] = Utils::formatStringDate($created, 'Y-m-d H:i:s', 'd/m/Y H:i:s'); 
                     $v['modified'] = Utils::formatStringDate($modified, 'Y-m-d H:i:s', 'd/m/Y H:i:s'); 
                     $v['codigoBancoConvenio'] = $codigoBancoConvenio;
                     $v['nomeBancoContrato'] = $nomeBancoContrato;
                     $v['nomeConvenio'] = $nomeConvenio;
                     $v['nomeOperacao'] = $nomeOperacao;
                     $v['nomeTabela'] = $nomeTabela;
                     $v['comissaoTotal'] = $comissaoTotal;
                     $v['valorSeguro'] = $valorSeguro;
                     $v['percentualImposto'] = $percentualImposto;
                     $v['quantidadeParcelas'] = $quantidadeParcelas;
                     $v['valorParcela'] = $valorParcela;
                     $v['valorTotal'] = $valorTotal;
                     $v['valorLiquido'] = $valorLiquido;
                     $v['percentualLoja'] = $percentualLoja;
                     $v['valorLoja'] = $valorLoja;
                     $v['idUsuario'] = $idUsuario;
                     $v['nomeUsuario'] = $nomeUsuario;
                     $v['status'] = $status;
                     
                     $v['comissoes'] = array();
                     $comissoes = explode('|', $comissoes);
                     foreach($comissoes as $i => $comissao)
                     {
                         $comissao = explode(',', $comissao);
                         if (count($comissao) > 0)
                         {
                             $c = array();
                             $c['id'] = (isset($comissao[0])) ? $comissao[0] : null;
                             $c['idContrato'] = (isset($comissao[1])) ? $comissao[1] : null; 
                             $c['nomeGrupo'] = (isset($comissao[2])) ? $comissao[2] : null; 
                             $c['percentualGrupo'] = (isset($comissao[3])) ? $comissao[3] : null; 
                            $c['valorGrupo'] = (isset($comissao[4])) ? $comissao[4] : null;
                             $c['percentualSupervisor'] = (isset($comissao[5])) ? $comissao[5] : null; 
                             $c['valorSupervisor'] = (isset($comissao[6])) ? $comissao[6] : null; 
                             $c['idUsuario'] = (isset($comissao[7])) ? $comissao[7] : null; 
                             $c['cpfUsuario'] = (isset($comissao[8])) ? $comissao[8] : null;  
                             $c['nomeUsuario'] =(isset($comissao[9])) ? $comissao[9] : null; 
                             array_push($v['comissoes'], $c);
                         }
                     }
                         
                     
                     $v['grupos'] = array();
                     $grupos = explode('|', $grupos);
                     foreach($grupos as $i => $grupo)
                     {
                         $grupo = explode(';', $grupo);
                         if (count($grupo) > 0)
                         {
                             $c = array();
                             $c['idSubtabela'] = (isset($grupo[0])) ? $grupo[0] : null;
                             $c['comissao'] = (isset($grupo[1])) ? $grupo[1] : null; 
                             $c['recebeDe'] = (isset($grupo[2])) ? $grupo[2] : null; 
                             $c['idGrupo'] = (isset($grupo[3])) ? $grupo[3] : null; 
                            $c['nomeGrupo'] = (isset($grupo[4])) ? $grupo[4] : null;
                            
                             array_push($v['grupos'], $c);
                         }
                     }
                     array_push($return, $v);   
                     
                     
                 }
            }
            
        }
         else
        {
            \Application::setMysqlLogQuery('Classe Relatorios; Método comissaoLoja; Mysql '. $connection->error); 
             $this->mysqlError = $connection->errno;
        }
        return $return;
        
    }
    
    
    public function comissaoGrupo($idGrupo = null, $dataInicio = null, $dataFim = null)
    {
         
         
         $idGrupo = ($idGrupo === null) ? '%' : $idGrupo ;
         $dataInicio = ($dataInicio === null) ? '0001-01-01' : $dataInicio;
         $dataFim = ($dataFim === null) ? '2100-01-01' : $dataFim;
       
        
        $return = false;
        
        $connection = \Application::getNewDataBaseInstance();
        $query = "
                 select distinct
                    u.id as 'idusuario', u.nome as 'nomeusuario',
                    sum(c.valortotal) as 'valorvenda', sum((cc.percentualsupervisor/100) * c.valortotal) as 'valorsupervisor'
                    from grupousuarios gu
                      inner join comissoescontrato cc on cc.grupousuarios_id = gu.id
                      inner join contratos c on c.id = cc.contratos_id
                      inner join usuarios u on u.id = cc.usuarios_id


                    where c.status in ('Pago ao cliente','Recebido comissão do banco')
                    and c.created between ? and ?
                    and gu.id like ?
                    group by u.id, u.nome
        ";
       
       
         if ($stm = $connection->prepare($query))
        {
            $stm->bind_param('sss',  $dataInicio, $dataFim, $idGrupo);
            if ($stm->execute())
            {
                $stm->bind_result($idUsuario, $nomeUsuario, $valorVenda, $valorSupervisor);
                
                $return = array();
                 while ($stm->fetch()) {
                     
                     $v = array();
                     $v['idUsuario'] = $idUsuario;
                     $v['nomeUsuario'] = $nomeUsuario;
                     $v['valorVenda'] = $valorVenda;
                     $v['valorSupervisor'] = $valorSupervisor;
                     
                     array_push($return, $v);   
                     
                     
                 }
            }
            
        }
         else
        {
            \Application::setMysqlLogQuery('Classe Relatorios; Método comissaoGrupo; Mysql '. $connection->error); 
             $this->mysqlError = $connection->errno;
        }
        return $return;
        
    }
    
    
     public function clientesFichario($cpf = null,  $idEntidade = null, $nascimentoInicial = null, $nascimentoFinal = null, $nomeInicial = null, $nomeFinal = null, $idUsuario = null    )
    {
        
         $cpf = ($cpf === null) ? '%' : $cpf;
		 $idUsuario = ($idUsuario === null) ? '%' : $idUsuario;
         $idEntidade = ($idEntidade === null) ? '%' : $idEntidade;
         $nascimentoInicial = ($nascimentoInicial === null) ? '0001-01-01' : $nascimentoInicial;
         $nascimentoFinal = ($nascimentoFinal === null) ? '2100-01-01' : $nascimentoFinal;
         $nomeInicial = ($nomeInicial === null) ? 'a' : $nomeInicial;
         $nomeFinal = ($nomeFinal === null) ? 'z' : $nomeFinal;
        
        $return = false;
        
        $connection = \Application::getNewDataBaseInstance();
        $query = "
                select distinct
                    c.cpf_cnpj, c.nome, c.apelido, c.email, c.senha, c.dtnascimento,
                    c.cep, c.rua, c.numero, c.complemento, c.bairro, c.uf,
                    c.cidade, c.timefutebol, c.observacoes, c.created, c.modified,
                    group_concat(tel.numero,',',tel.referencia SEPARATOR ';') as 'telefone',
                    cc.convenio, co.conta, u.nome
                from clientes c
                    left join usuarios u on u.id = c.usuarios_id
                  left join (
                    select distinct
                    t.clientes_cpf_cnpj, t.numero, t.referencia
                    from telefonesclientes t
                  ) tel on tel.clientes_cpf_cnpj = c.cpf_cnpj

                  left join (
                    select distinct
                   cc.clientes_cpf_cnpj, group_concat(cc.id, ',', ifnull(e.id,''), ',', cc.nb, ',', ifnull(cc.matricula,''), ',', ifnull(cc.senha,''),',', ifnull(e.nome,'') ORDER BY cc.id ASC SEPARATOR ';' ) as 'convenio' 
                    from conveniocliente cc
                      left join entidades e on e.id = cc.entidade_id
                      group by cc.clientes_cpf_cnpj 
                  ) cc on cc.clientes_cpf_cnpj = c.cpf_cnpj

                  left join (
                    select distinct
                    cb.clientes_cpf_cnpj, group_concat(cb.id, ',', cb.bancos_id, ',', cb.tipocontabancaria_id, ',', cb.agencia, ',', cb.conta, ',', b.nome, ',', tcb.descricao, ',', b.codigo order by cb.id Separator ';') as 'conta'
                    from contabancariaclientes cb
                        inner join bancos b on b.id = cb.bancos_id
                        inner join tipocontabancaria tcb on tcb.id = cb.tipocontabancaria_id
                    group by cb.clientes_cpf_cnpj
                  ) co on co.clientes_cpf_cnpj = c.cpf_cnpj


                    where 

                    exists (select id from conveniocliente where clientes_cpf_cnpj = c.cpf_cnpj and entidade_id like ? )
                    and c.nome between ? and ?
                    and  c.dtnascimento between ? and ?
                    and c.cpf_cnpj like ? 
					and (c.usuarios_id like ? or ? = '%')

                    group by 
                c.cpf_cnpj, c.nome, c.apelido, c.email, c.senha, 
                c.dtnascimento, c.cep, c.rua, c.numero, c.complemento, 
                c.bairro, c.uf, c.cidade, c.timefutebol, c.observacoes, 
                c.created, c.modified, cc.convenio
                 order by 2 
        ";
       
        
         if ($stm = $connection->prepare($query))
        {
            $stm->bind_param('ssssssss',  $idEntidade, $nomeInicial, $nomeFinal, $nascimentoInicial, $nascimentoFinal, $cpf, $idUsuario, $idUsuario );
            if ($stm->execute())
            {
                $stm->bind_result($cpf, $nomeCliente, $apelido, $email, $senha, $dtNascimento, $cep, $rua, $numeroRua, $complemento, $bairro, $uf, $cidade, $timeFutebol, $observacoes, $created, $modified, $telefones, $convenios, $conta, $nomeUsuario);
                
                $return = array();
                $pos = 0;
                 while ($stm->fetch()) {
                     $v = array();
                     $v['cpf'] = $cpf;
                     
                     $v['nomeCliente'] = $nomeCliente;
                     $v['apelido'] =  $apelido;
                     $v['email'] = $email;
                     $v['senha'] = $senha;
                     $v['nascimento'] = Utils::formatStringDate($dtNascimento, 'Y-m-d', 'd/m/Y');
                     $v['cep'] = $cep;
                     $v['rua'] = $rua;
                      $v['numeroRua'] = $numeroRua;
                      $v['complemento'] = $complemento;
                     $v['bairro'] = $bairro;
                     $v['uf'] = $uf;
                     $v['cidade'] = $cidade;
                     $v['timeFutebol'] = $timeFutebol;
                     $v['observacoes'] = $observacoes;
                     $v['created'] = Utils::formatStringDate($created, 'Y-m-d H:i:s', 'd/m/Y H:i:s');
                     $v['modified'] = Utils::formatStringDate($modified, 'Y-m-d H:i:s', 'd/m/Y H:i:s');
                     
                      $return[$pos]['nomeUsuario'] = $nomeUsuario;
                      $return[$pos]['dados'] = $v;       
                     
                     
                     // Telefone
                    
                        $return[$pos]['telefones'] = array();
                     $aux = explode(';', $telefones);
                     if (is_array($aux))
                         foreach($aux as $i => $value)
                         {
                             $aux2 = explode(',', $value);
                             if (count($aux2) > 0)
                             {
                                 $t = array();
                                 $t['numero'] = (isset($aux2[0]))? $aux2[0] : '';
                                 $t['referencia'] = (isset($aux2[1]))? $aux2[1] : '';
                                 array_push($return[$pos]['telefones'], $t);
                             }
                         }
                     //echo $convenios; exit;
                     // Convenio
                     
                        $return[$pos]['convenios'] = array();
                     $aux = explode(';', $convenios);
                     if (is_array($aux))
                         foreach($aux as $i => $value)
                         {
                            
                             $aux2 = explode(',', $value);
                             if (count($aux2) > 0)
                             {
                                 $t = array();
                                 $t['idConvenio'] = (isset($aux2[1])) ?  $aux2[1] : '';
                                 $t['nb'] = (isset($aux2[2])) ? $aux2[2] : '';
                                 $t['matricula'] = (isset($aux2[3])) ? $aux2[3] : '';
                                 $t['senha'] = (isset($aux2[4])) ? $aux2[4] : '';
                                $t['nomeConvenio'] = (isset($aux2[5])) ? $aux2[5] : '';
                                 array_push($return[$pos]['convenios'], $t);
                             }
                         }
                     
                     
                     // Contas Bancárias
                  //   if ($conta != '')
                        $return[$pos]['contas'] = array();
                     $aux = explode(';', $conta);
                     if (is_array($aux))
                         foreach($aux as $i => $value)
                         {
                             $aux2 = explode(',', $value);
                             if (count($aux2) > 0)
                             {
                                 $t = array();
                                 $t['idContaBancariaCliente'] = (isset($aux2[0])) ? $aux2[0] : '';
                                 $t['idBanco'] = (isset($aux2[1])) ? $aux2[1] : '';
                                 $t['idTipoConta'] = (isset($aux2[2])) ? $aux2[2] : '';
                                 $t['agencia'] = (isset($aux2[3])) ? $aux2[3] : '';
                                 $t['conta'] = (isset($aux2[4])) ? $aux2[4] : '';
                                 $t['nomeBanco'] = (isset($aux2[5])) ? $aux2[5] : '';
                                 $t['descricaoConta'] = (isset($aux2[6])) ? $aux2[6] : '';
                                 $t['codigoBanco'] = (isset($aux2[7])) ? $aux2[7] : '';
                                 
                                 array_push($return[$pos]['contas'], $t);
                             }
                         }
                     
                     //echo '<pre>';var_dump($return);exit;
                     $pos++;
                 }
            }
            
        }
         else
        {
            \Application::setMysqlLogQuery('Classe Cliente; Método carregar; Mysql '. $connection->error); 
             $this->errorCode = $connection->errno;
        }
        return $return;
        
    }
    
    
    
    public function listarAgendamentos($idGrupo, $idConvenio, $idUsuario, $dataInicio, $dataFim, $dataAgendamentoInicio, $dataAgendamentoFim, $status)
    {
        
        
        $idGrupo = ($idGrupo == null) ? '%' : $idGrupo;
        $idConvenio = ($idConvenio == null) ? '%' : $idConvenio;
        $idUsuario = ($idUsuario == null) ? '%' : $idUsuario;
        $dataInicio = ($dataInicio == null) ? '2015-01-01' : $dataInicio;
        $dataFim = ($dataFim == null) ? '2100-01-01' : $dataFim;
        $dataAgendamentoInicio = ($dataAgendamentoInicio == null) ? '2015-01-01' : $dataAgendamentoInicio;
        $dataAgendamentoFim = ($dataAgendamentoFim == null) ? '2100-01-01' : $dataAgendamentoFim;
        $status = ($status == null) ? '%' : $status;
        
        
        
        $return = false;
        
        $connection = \Application::getNewDataBaseInstance();
        $query = "
                 select distinct
            a.id, a.cpf, a.nome as 'nomecliente', a.created, a.dataligacao, a.status, c.nome as 'nomeconvenio', u.nome as 'nomeusuario'

            from agenda a
              inner join usuarios u on u.id = a.usuarios_id
              left join entidades c on c.id = a.entidades_id
              left join (
                select distinct
                gu.id as 'idgrupo', gup.usuarios_id as 'idusuario'
                from grupousuarios gu
                  inner join grupousuariopertence gup on gup.grupousuarios_id = gu.id
              ) g on g.idusuario = u.id

             where (g.idgrupo like ? or ? = '%')
             and (a.entidades_id like ? or ? = '%')
             and a.created between ? and ADDDATE( ?, INTERVAL 1 DAY)  
             and ( a.dataligacao between ? and ADDDATE( ?, INTERVAL 1 DAY)  or (? = '2015-01-01' and ? = '2100-01-01')  )
             and a.usuarios_id like ?
             and a.status like ?
        ";
       
       
         if ($stm = $connection->prepare($query))
        {
            $stm->bind_param('ssssssssssss',  $idGrupo, $idGrupo, $idConvenio, $idConvenio, $dataInicio, $dataFim, $dataAgendamentoInicio, $dataAgendamentoFim, $dataAgendamentoInicio,
                            $dataAgendamentoFim, $idUsuario, $status);
            if ($stm->execute())
            {
                $stm->bind_result($id, $cpf, $nomeCliente, $created, $dataLigacao, $status, $nomeConvenio, $nomeUsuario);
                
                $return = array();
                 while ($stm->fetch()) {
                     
                     $v = array();
                     $v['id'] = $id;
                     $v['cpf'] = $cpf;
                     $v['nomeCliente'] = $nomeCliente;
                     $v['created'] = Utils::formatStringDate($created, 'Y-m-d H:i:s', 'd/m/Y H:i:s');;
                     $v['dataLigacao'] = Utils::formatStringDate($dataLigacao, 'Y-m-d H:i:s', 'd/m/Y H:i:s');
                     $v['status'] = $status;
                     $v['nomeConvenio'] = $nomeConvenio;
                     $v['nomeUsuario'] = $nomeUsuario;
                     
                     array_push($return, $v);   
                     
                     
                 }
            }else
            {
                \Application::setMysqlLogQuery('Classe Relatorios; Método listarAgendamentos; Mysql '. $connection->error); 
                 $this->mysqlError = $connection->errno;
            }
            
        }
         else
        {
            \Application::setMysqlLogQuery('Classe Relatorios; Método listarAgendamentos; Mysql '. $connection->error); 
             $this->mysqlError = $connection->errno;
        }
        return $return;
        
        
    }
    
    
    public function relatorioVendas($nomeGrupo,  $dataInicial, $dataFinal, $tipoRelatorio)
    {
        
        
        $nomeGrupo = ($nomeGrupo == null) ? '%' : $nomeGrupo;
        $dataInicial = ($dataInicial == null) ? '2015-01-01' : $dataInicial;
        $dataFinal = ($dataFinal == null) ? '2100-01-01' : $dataFinal;
        
        
        
        
        $return = false;
        
        $connection = \Application::getNewDataBaseInstance();
        
        if (strtolower($tipoRelatorio) == 'usuarios')
            $query = "
                     select distinct
                    u.nome as 'nome', c.nomeoperacao, sum(c.valortotal) as 'valor'
                    from contratos c
                      inner join usuarios u ON u.id = c.usuarios_id
                    where c.usuariovinculado_id is null
                    and c.datapagamento between ? and ADDDATE( ?, INTERVAL 1 DAY)
                    and exists (
                        select cc.contratos_id  from comissoescontrato cc where cc.contratos_id = c.id and cc.nomegrupo like ?
                    )
                    group by u.nome, c.nomeoperacao
                    order by 1, 2
            ";
        else
            $query = "
                    select distinct
                    gu.nomegrupo as 'nome', c.nomeoperacao, sum(c.valortotal) as 'valor'
                    from contratos c
                      inner join usuarios u ON u.id = c.usuarios_id
                      inner join (
                        select distinct
                        min(gu.nome) as 'nomegrupo', gp.usuarios_id
                        from    grupousuariopertence gp
                          inner join grupousuarios gu on gu.id = gp.grupousuarios_id
                        group by gp.usuarios_id
                       ) gu on gu.usuarios_id = c.usuarios_id
                     where  
                      c.datapagamento between ? and ADDDATE( ?, INTERVAL 1 DAY)
                      and gu.nomegrupo like ?
                     
                    group by gu.nomegrupo, c.nomeoperacao
                    order by 1, 2
            ";
       
       
         if ($stm = $connection->prepare($query))
        {
            $stm->bind_param('sss', $dataInicial, $dataFinal, $nomeGrupo);
            if ($stm->execute())
            {
                $stm->bind_result($nome, $operacao, $valor);
                
                $return = array();
                 while ($stm->fetch()) {
                     
                     $v = array();
                     $v['nome'] = $nome;
                     $v['operacao'] = $operacao;
                     $v['valor'] = $valor;
                     
                     
                     array_push($return, $v);   
                     
                     
                 }
            }else
            {
                \Application::setMysqlLogQuery('Classe Relatorios; Método relatorioVendas; Mysql '. $connection->error); 
                 $this->mysqlError = $connection->errno;
            }
            
        }
         else
        {
            \Application::setMysqlLogQuery('Classe Relatorios; Método relatorioVendas; Mysql '. $connection->error); 
             $this->mysqlError = $connection->errno;
        }
        return $return;
        
        
    }
    
    /*
    * Relatório de Gastos
    */
    
    public function totaisRelatorioGastos($params = array())
    {
        
        
        extract($params, EXTR_OVERWRITE);

        $datainicio =  (! empty($datainicio)) ? $datainicio : '1';
        $datafim =  (! empty($datafim)) ? $datafim : '1';
        $nomebanco =  (! empty($nomebanco)) ? $nomebanco : '%';
        $nomeoperacao =  (! empty($nomeoperacao)) ? $nomeoperacao : '%';
        
        
        $return = false;
        
        $connection = \Application::getNewDataBaseInstance();
        
      
            $query = "
                  select
                    ifnull(sum(round(((c.comissaototal/100) * c.valortotal  ),2)),0) as 'comissaototal',
                    ifnull(sum(round(((c.comissaototal/100) * c.valortotal * (c.percentualimposto / 100)   ),2)),0) as 'valorimposto'

                    from contratos c
                    where c.datapagamento between ? and ?
                    and c.nomebancocontrato like ? and c.nomeoperacao like ?

            ";
        
       
       
         if ($stm = $connection->prepare($query))
        {
            $stm->bind_param('ssss', $datainicio, $datafim, $nomebanco, $nomeoperacao);
            if ($stm->execute())
            {
                $stm->bind_result($comissaoTotal, $totalImpostos);
                
                $return = array();
                 if ($stm->fetch()) {
                     
                     
                     $return['comissaoBruta'] = $comissaoTotal;
                     $return['impostos'] = $totalImpostos;
                     
                     
                     array_push($return, $v);   
                     
                     
                 }
            }else
            {
                \Application::setMysqlLogQuery('Classe Relatorios; Método totaisRelatorioGastos; Mysql '. $connection->error); 
                 $this->mysqlError = $connection->errno;
            }
            
        }
         else
        {
            \Application::setMysqlLogQuery('Classe Relatorios; Método totaisRelatorioGastos; Mysql '. $connection->error); 
             $this->mysqlError = $connection->errno;
        }
        return $return;
        
        
    }
    
    
    public function listarDescontosSumarizados($params = array())
    {
        
        
        extract($params, EXTR_OVERWRITE);

        $datainicio =  (! empty($datainicio)) ? $datainicio : '1';
        $datafim =  (! empty($datafim)) ? $datafim : '1';
        
        
        
        $return = false;
        
        $connection = \Application::getNewDataBaseInstance();
        
      
            $query = "
                   select
                    u.nome, sum(d.valordescontos) as 'valordescontado'
                    from descontos d
                      inner join usuarios u on u.id = d.usuarios_id

                    where d.created between ? and ADDDATE( ?, INTERVAL 1 DAY)
                    group by u.id

            ";
        
       
       
         if ($stm = $connection->prepare($query))
        {
            $stm->bind_param('ss', $datainicio, $datafim);
            if ($stm->execute())
            {
                $stm->bind_result($nome, $valor);
                
                $return = array();
                 while ($stm->fetch()) {
                     
                     $v = array();
                     $v['nome'] = $nome;
                     $v['valorDescontado'] = $valor;
                     
                     
                     array_push($return, $v);   
                     
                     
                 }
            }else
            {
                \Application::setMysqlLogQuery('Classe Relatorios; Método ListarDescontosSumarizado; Mysql '. $connection->error); 
                 $this->mysqlError = $connection->errno;
            }
            
        }
         else
        {
            \Application::setMysqlLogQuery('Classe Relatorios; Método ListarDescontosSumarizado; Mysql '. $connection->error); 
             $this->mysqlError = $connection->errno;
        }
        return $return;
        
        
    }
    
    
}