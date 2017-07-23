<?php
$contrato = (isset($this->getParams('contrato')[0])) ? $this->getParams('contrato')[0] : null;
$todosContratos = $this->getParams('todoscontratos');
$cliente = (isset($this->getParams('cliente')[0])) ? $this->getParams('cliente')[0] : null;
$subTabela = (isset($this->getParams('subtabela')[0])) ? $this->getParams('subtabela')[0] : null;
$tipoConvenios = $this->getParams('tipoconvenios');
$substatus = $this->getParams('substatus');
$usuarios = $this->getParams('usuarios');

$permiteLerStatus = (\Application::isAuthorized(ucfirst(strtolower(\Application::getNameController())) , 'alterar_status_contrato', 'ler') ) ? true : false;
$permiteEscreverStatus = (\Application::isAuthorized(ucfirst(strtolower(\Application::getNameController())) , 'alterar_status_contrato', 'escrever') ) ? true : false;

$permiteLerDataPagamento = (\Application::isAuthorized(ucfirst(strtolower(\Application::getNameController())) , 'datapagamento_contrato', 'ler') ) ? true : false;
$permiteEscreverDataPagamento = (\Application::isAuthorized(ucfirst(strtolower(\Application::getNameController())) , 'datapagamento_contrato', 'escrever') ) ? true : false;


$permiteLerDataPagamentoBanco = (\Application::isAuthorized(ucfirst(strtolower(\Application::getNameController())) , 'datapagamentobanco_contrato', 'ler') ) ? true : false;
$permiteEscreverDataPagamentoBanco = (\Application::isAuthorized(ucfirst(strtolower(\Application::getNameController())) , 'datapagamentobanco_contrato', 'escrever') ) ? true : false;


$permiteLerUsuarioVinculado = (\Application::isAuthorized(ucfirst(strtolower(\Application::getNameController())) , 'contrato_usuario_vinculado', 'ler') ) ? true : false;
$permiteEscreverUsuarioVinculado = (\Application::isAuthorized(ucfirst(strtolower(\Application::getNameController())) , 'contrato_usuario_vinculado', 'escrever') ) ? true : false;


if (\Application::getUrlParams(0) !== null && $contrato === null)
    \Application::print404();
?>

<style>
    .panel-dados-cliente div.row, .panel-dados-contrato div.row:nth-child(2) {padding-bottom: 2rem;}
    .panel-dados-cliente a {color: #0707a7; }
    #contratoObservacao {min-height: 15rem;}
    
    .box-button-save {margin-bottom: 4rem;}
    
    .mt-xs {
        margin-top: 0px !important;
    }
    
</style>

<h5>Cadastro de Contrato</h5>
<hr>


<section class="panel panel-dados-cliente">
    <header class="panel-heading">
        <h2 class="panel-title">Dados do cliente</h2>
    </header>
    <div class="panel-body">
        <div class="row">
            <div class="col-md-4">
                <div class="form-group">
                    <label class="col-sm-4 control-label">CPF: </label>
                    <div class="col-sm-8">
                        <input type="text" name="cpf" id="cpf"  class="form-control" value="<?php if (isset($contrato['cpf'])) echo $contrato['cpf']; ?>" >
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <button class="btn btn-grey" onclick="buscarCliente()">&nbsp;Carregar</button>
            </div>
        </div>
        
        <div class="row">
            <div class="col-md-8">
                <div class="form-group">
                    <label class="col-md-2 control-label">Nome: </label>
                    <div class="col-sm-8">
                        <input type="text" name="nomecliente" id="nomecliente"  class="form-control" disabled value="<?php if (isset($contrato['nomeCliente'])) echo $contrato['nomeCliente']; ?>" >
                    </div>
                </div>
            </div>
        </div>
        
        <div class="row">
            <div class="col-md-4">
                <div class="form-group">
                    <label class="col-sm-4 control-label">CEP: </label>
                    <div class="col-sm-8">
                        <input type="text" name="cep" id="cep"  class="form-control" disabled value="<?php if (isset($contrato['cep'])) echo $contrato['cep']; ?>" >
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-sm-4 control-label">Complemento: </label>
                    <div class="col-sm-8">
                        <input type="text" name="complemento" id="complemento"  class="form-control maiusculo" disabled value="<?php if (isset($contrato['complementoRua'])) echo $contrato['complementoRua']; ?>">
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-sm-4 control-label">Cidade: </label>
                    <div class="col-sm-8">
                        <input type="text" name="cidade" id="cidade"  class="form-control maiusculo" disabled value="<?php if (isset($contrato['cidade'])) echo $contrato['cidade']; ?>">
                    </div>
                </div>
            </div>
            
            <div class="col-md-4">
                <div class="form-group">
                    <label class="col-sm-4 control-label">Rua: </label>
                    <div class="col-sm-8">
                        <input type="text" name="rua" id="rua" class="form-control maiusculo" disabled value="<?php if (isset($contrato['rua'])) echo $contrato['rua']; ?>">
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-sm-4 control-label">Bairro: </label>
                    <div class="col-sm-8">
                        <input type="text" name="bairro" id="bairro"  class="form-control maiusculo" disabled value="<?php if (isset($contrato['bairro'])) echo $contrato['bairro']; ?>">
                    </div>
                </div>
                <div class="form-group">
                    <div class="col-sm-8">
                        <a href="" target="_blank" onclick="verCadastroCliente(this)" >ver cadastro completo do cliente</a>
                    </div>
                </div>
            </div>
            
             <div class="col-md-4">
                 <div class="form-group">
                    <label class="col-sm-4 control-label">N&uacute;mero: </label>
                    <div class="col-sm-8">
                        <input type="text" name="numerorua" id="numerorua"  class="form-control maiusculo" disabled value="<?php if (isset($contrato['numeroRua'])) echo $contrato['numeroRua']; ?>">
                    </div>
                </div>
                 <div class="form-group">
                    <label class="col-sm-4 control-label">UF: </label>
                    <div class="col-sm-8">
                        <input type="text" name="uf" id="uf"  class="form-control maiusculo" disabled value="<?php if (isset($contrato['uf'])) echo $contrato['uf']; ?>">
                    </div>
                </div>
            </div>
            
        </div>
        
        <hr />
        
        <br />
        <p><i>Contratos já feitos pelo cliente</i></p>
        
        <div class="row">
            <div class="col-md-12">
                <table id="datatable" class="display" cellspacing="0" width="100%">
                    <thead>
                        <tr>
                            <th>Contrato</th>
                            <th>Banco</th>
                            <th>Operação</th>
                            <th>N&ordm; de vezes</th>
                            <th>Parcela</th>
                            <th>Valor Todal</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        if (is_array($todosContratos))
                            foreach($todosContratos as $i => $value)
                                if ($value['id'] != $contrato['id'])
                                {
                        ?>
                                    <tr>
                                        <td><?php echo $value['numeroContrato']; ?></td>
                                        <td><?php echo $value['nomeBancoContrato']; ?></td>
                                        <td><?php echo $value['nomeOperacao']; ?></td>
                                        <td><?php echo $value['quantidadeParcelas']; ?></td>
                                        <td><?php echo 'R$ ' . Gauchacred\library\php\Utils::numberToMoney( $value['valorParcela']); ?></td>
                                        <td><?php echo 'R$ '. Gauchacred\library\php\Utils::numberToMoney($value['valorTotal']); ?></td>
                                        <td><?php echo $value['status']; ?></td>
                                    </tr>
                            <?php } ?>
                    </tbody>
                </table>
            </div>
        </div>
        
        <hr />
        
        <div class="row">
            <div class="col-md-2">
                <div class="form-group">
                    <div class="row">
                        <div class="col-sm-12">
                            <label>Conta Banc&aacute;ria:</label>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-12">
                            <select id="contabancaria" class="form-control" >
                                <option></option>
                                <?php
                                $cont = 0;
                                if (is_array($cliente['contas']))
                                    foreach($cliente['contas'] as $i => $value)    
                                    {
                                        $selected = ($value['idContaBancariaCliente'] == $contrato['idContaBancariaCliente']) ? 'selected="selected"' : '';
                                        echo '<option '. $selected .' value="'. $value['idContaBancariaCliente'] .'" >' . $value['conta'] .'</option>';
                                    }
                                ?>
                            </select>
                        </div>
                    </div>
                </div>
            </div>
            
            
            <div class="col-md-2">
                <div class="form-group">
                    <div class="row">
                        <div class="col-sm-12">
                            <label>Banco:</label>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-12">
                            <input type="text" class="form-control" disabled id="dadosBanco" value="<?php if (isset($contrato['nomeBancoCliente'])) echo $contrato['nomeBancoCliente']; ?>"   />
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="col-md-2">
                <div class="form-group">
                    <div class="row">
                        <div class="col-sm-12">
                            <label>Conta:</label>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-12">
                            <input type="text" class="form-control" disabled id="dadosConta" value="<?php if (isset($contrato['contaBancoCliente'])) echo $contrato['contaBancoCliente']; ?>" />
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="col-md-2">
                <div class="form-group">
                    <div class="row">
                        <div class="col-sm-12">
                            <label>Agencia:</label>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-12">
                            <input type="text" class="form-control" disabled id="dadosAgencia" value="<?php if (isset($contrato['agenciaBancoCliente'])) echo $contrato['agenciaBancoCliente']; ?>" />
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="col-md-2">
                <div class="form-group">
                    <div class="row">
                        <div class="col-sm-12">
                            <label>Tipo de Conta:</label>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-12">
                            <input type="text" class="form-control" disabled id="dadosTipoConta" value="<?php if (isset($contrato['tipoContaBancoCliente'])) echo $contrato['tipoContaBancoCliente']; ?>" />
                        </div>
                    </div>
                </div>
            </div>
            
        </div>
            
    </div>
</section>


<section class="panel panel-dados-contrato">
    <header class="panel-heading">
        <h2 class="panel-title">Dados do contrato</h2>
    </header>
    <div class="panel-body">
        <div class="row">
            <div class="col-md-2">
                <div class="form-group">
                    <div class="row">
                        <div class="col-sm-12">
                            <label>N&uacute;mero do contrato:</label>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-12">
                            <input type="text" class="form-control" disabled value="<?php if (isset($contrato['numeroContrato'])) echo $contrato['numeroContrato']; ?>" />
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="col-md-2">
                <div class="form-group">
                    <div class="row">
                        <div class="col-sm-12">
                            <label>Criado em:</label>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-12">
                            <input type="text" class="form-control" disabled value="<?php if (isset($contrato['created'])) echo $contrato['created']; ?>" />
                        </div>
                    </div>
                </div>
            </div>
			
			<div class="col-md-2">
                <div class="form-group">
                    <div class="row">
                        <div class="col-sm-12">
                            <label>Modificado em:</label>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-12">
                            <input type="text" class="form-control" disabled value="<?php if (isset($contrato['modified'])) echo $contrato['modified']; ?>" />
                        </div>
                    </div>
                </div>
            </div>
            
            <?php
                $disabled = ($permiteEscreverStatus == false) ? 'disabled' : '';
                $hidden = ($permiteLerStatus == false) ? 'hidden' : '';
            ?>
            <div class="col-md-2 <?php echo $hidden; ?>">
                <div class="form-group">
                    <div class="row">
                        <div class="col-sm-12">
                            <label>Status:</label>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-12">
                            
                            <select class="form-control" <?php if ($contrato != null) echo $disabled; else echo 'disabled'; ?> id="status">
                                <option></option>
                                <option value="Em Análise" <?php  if($contrato['status'] == 'Em Análise') echo 'selected="selected"'; ?> >Em Análise</option>
                                <option value="Pendente" <?php  if($contrato['status'] == 'Pendente') echo 'selected="selected"'; ?> >Pendente</option>
                                <option value="Reprovado" <?php  if($contrato['status'] == 'Reprovado') echo 'selected="selected"'; ?> >Reprovado</option>
                                <option value="Em Andamento" <?php  if($contrato['status'] == 'Em Andamento') echo 'selected="selected"'; ?> >Em Andamento</option>
                                <option value="Pago ao Cliente" <?php  if($contrato['status'] == 'Pago ao Cliente') echo 'selected="selected"'; ?> >Pago ao Cliente</option>
                                <option value="Recebido Comissão do Banco" disabled <?php  if($contrato['status'] == 'Recebido Comissão do Banco') echo 'selected="selected"'; ?> >Recebido Comissão do Banco</option>
                                <option value="Saldo Pago" <?php  if($contrato['status'] == 'Saldo Pago') echo 'selected="selected"'; ?> >Saldo Pago</option>
                                <option value="Aguardando Refim Port" <?php  if($contrato['status'] == 'Aguardando Refim Port') echo 'selected="selected"'; ?> >Aguardando Refim Port</option>
                                <option value="Aumento" <?php  if($contrato['status'] == 'Aumento') echo 'selected="selected"'; ?> >Aumento</option>
								<option value="Finalizado" <?php  if($contrato['status'] == 'Finalizado') echo 'selected="selected"'; ?> >Finalizado</option>
                            </select>
                        </div>
                    </div>
                </div>
            </div>
            
            
            <div class="col-md-2 box-substatus <?php echo $hidden; ?>" >
                <div class="form-group">
                    <div class="row">
                        <div class="col-sm-12">
                            <label>Substatus:</label>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-12">
                            
                            <select  class="form-control select2" <?php if ($substatus != null) echo $disabled; else echo 'disabled'; ?> id="substatus">
                                <option value=""></option>
                                
                                <?php
                                if (is_array($substatus))
                                    foreach($substatus as $i => $value){
                                ?>
                                    <option value="<?php echo $value['id']; ?>" <?php  if($contrato['idSubstatusContrato'] == $value['id']) echo 'selected="selected"'; if ($value['status'] == 0) echo 'disabled="disabled"'; ?> ><?php echo $value['descricao'];?></option>
								
                                <?php }  ?>
                            </select>
                        </div>
                    </div>
                </div>
            </div>
            
            
            
        </div>
        
        <div class="row">
            
            
             <?php
                $disabled = ($permiteEscreverDataPagamentoBanco == false) ? 'disabled' : '';
                $hidden = ($permiteLerDataPagamentoBanco == false) ? 'hidden' : '';
            
                if ($permiteEscreverDataPagamentoBanco !== false || $permiteLerDataPagamentoBanco !== false)
                {
            ?>
            
            <div class="col-md-2">
                <div class="form-group">
                    <div class="row">
                        <div class="col-sm-12">
                            <label>Recebido Comissão do Banco em:</label>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-12">
                            <input type="text" class="form-control" id="contratoDataPagamentoBanco" <?php if ($contrato != null) echo $disabled; else echo 'disabled'; ?> data-plugin-datepicker data-input-mask="99/99/9999" placeholder="__/__/____" value="<?php if (isset($contrato['dataPagamentoBanco'])) echo $contrato['dataPagamentoBanco']; ?>" />
                        </div>
                    </div>
                </div>
            </div>
            
            <?php } ?>
            
            
            
            
            <?php
                $disabled = ($permiteEscreverDataPagamento == false) ? 'disabled' : '';
                $hidden = ($permiteLerDataPagamento == false) ? 'hidden' : '';
            
                if ($permiteEscreverDataPagamento !== false || $permiteLerDataPagamento !== false)
                {
            ?>
            
            <div class="col-md-2">
                <div class="form-group">
                    <div class="row">
                        <div class="col-sm-12">
                            <label>Data do Pagamento ao Vendedor:</label>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-12">
                            <input type="text" class="form-control" id="contratoDataPagamento" <?php if ($contrato != null) echo $disabled; else echo 'disabled'; ?> data-plugin-datepicker data-input-mask="99/99/9999" placeholder="__/__/____" value="<?php if (isset($contrato['dataPagamento'])) echo $contrato['dataPagamento']; ?>" />
                        </div>
                    </div>
                </div>
            </div>
            
            <?php } ?>
        </div>
        
        <div class="row">
            <div class="col-md-10">
                <div class="form-group">
                    <div class="row">
                        <div class="col-sm-8">
                            <label>Usu&aacute;rio:</label>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-8">
                            <input type="text" class="form-control" disabled value="<?php if (isset($contrato['nomeUsuario'])) echo $contrato['nomeUsuario']; ?>" />
                        </div>
                      <!--  <div class="col-sm-4 button-trocar-usuario">
                            <button type="button" class="mb-xs mt-xs mr-xs btn btn-success" onclick="gerar('excel')">Alterar Usuário</button>
                        </div> -->
                    </div>
                </div>
            </div>
        </div>
        
        <div class="row">
            <div class="col-md-2">
                <div class="form-group">
                    <div class="row">
                        <div class="col-sm-12">
                            <label>Banco:</label>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-12">
                            <select class="form-control" id="contratoBanco" >
                                <option></option>
                            </select>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-2">
                <div class="form-group">
                    <div class="row">
                        <div class="col-sm-12">
                            <label>Conv&ecirc;nio:</label>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-12">
                            <select class="form-control"  id="contratoConvenio">
                                <option></option>
                                <?php
                                    if (is_array($subTabela))
                                        echo '<option selected="selected" value="' . $subTabela['idConvenio'] . '">'. $subTabela['nomeConvenio'] . '</option>';
                                ?>
                            </select>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="col-md-2">
                <div class="form-group">
                    <div class="row">
                        <div class="col-sm-12">
                            <label>Opera&ccedil;&atilde;o:</label>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-12">
                            <select class="form-control" id="contratoOperacao" >
                                <option></option>
                                <?php
                                    if (is_array($subTabela))
                                        echo '<option selected="selected" value="' . $subTabela['idOperacao'] . '">'. $subTabela['nomeOperacao'] . '</option>';
                                ?>
                            </select>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="col-md-2">
                <div class="form-group">
                    <div class="row">
                        <div class="col-sm-12">
                            <label>Tabela:</label>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-12">
                            <select class="form-control" id="contratoTabela" >
                                <option></option>
                                <?php
                                    if (is_array($subTabela))
                                        echo '<option selected="selected" data-subtabela="'. $subTabela['id'] .'" value="' . $subTabela['idTabela'] . '">'. $subTabela['nomeTabela'] . '</option>';
                                ?>
                            </select>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="col-md-2">
                <div class="form-group">
                    <div class="row">
                        <div class="col-sm-12">
                            <label>Seguro:</label>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-12">
                            <input class="form-control" id="contratoSeguro" value="<?php if (isset($contrato['valorSeguro'])) echo $contrato['valorSeguro']; ?>"  / >
                        </div>
                    </div>
                </div>
            </div>
        
        </div>
        
        <div class="row">
            <div class="col-md-2">
                <div class="form-group">
                    <div class="row">
                        <div class="col-sm-12">
                            <label>Quantidade parcelas:</label>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-12">
                            <select class="form-control" id="contratoPrazos" >
                                <option></option>
                                <?php
                                   if (is_array($subTabela))
                                       foreach($subTabela['prazos'] as $i => $value)
                                       {
                                           $selected = ($value['prazo'] == $contrato['quantidadeParcelas']) ? 'selected="selected"' : '';
                                           echo '<option '. $selected . ' value="'. $value['prazo'] . '">'. $value['prazo'] . 'x</option>';
                                       }
                                ?>
                            </select>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="col-md-2">
                <div class="form-group">
                    <div class="row">
                        <div class="col-sm-12">
                            <label>Valor parcela:</label>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-12">
                            <input type="text" class="form-control" id="contratoValorParcela" value="<?php if (isset($contrato['valorParcela'])) echo $contrato['valorParcela']; ?>" />
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="col-md-2">
                <div class="form-group">
                    <div class="row">
                        <div class="col-sm-12">
                            <label>Valor total:</label>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-12">
                            <input type="text" class="form-control"  id="contratoValorTotal" value="<?php if (isset($contrato['valorTotal'])) echo $contrato['valorTotal']; ?>" />
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="col-md-2">
                <div class="form-group">
                    <div class="row">
                        <div class="col-sm-12">
                            <label>Valor l&iacute;quido:</label>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-12">
                            <input type="text" class="form-control"   id="contratoValorLiquido" value="<?php if (isset($contrato['valorLiquido'])) echo $contrato['valorLiquido']; ?>" />
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="col-md-2">
                <div class="form-group">
                    <div class="row">
                        <div class="col-sm-12">
                            <label>Conv&ecirc;nio:</label>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-12">
                            <select id="tipoConvenio" class="form-control">
                                <option></option>
                                <?php
                                    if (is_array($tipoConvenios))
                                        foreach($tipoConvenios as $i => $value)
                                        {
                                            $selected = ($value['id'] == $contrato['idTipoConvenio']) ? 'selected="selected"' : '';
                                            echo '<option '. $selected . ' value="'. $value['id'] . '" >'. $value['descricao']. '</option>';
                                        }
                                ?>
                            </select>
                        </div>
                    </div>
                </div>
            </div>
        
        </div>
        
        <?php
            $disabled =  ($permiteEscreverUsuarioVinculado === true) ? '' : 'disabled="disabled"';
            if ($permiteLerUsuarioVinculado || $permiteEscreverUsuarioVinculado)
            { ?>
        <div class="row">
            <div class="col-md-4">
                <div class="form-group">
                    <div class="row">
                        <div class="col-sm-12">
                            <label>Usu&aacute;rio vinculado:</label>
                        </div>
                    </div>
                    
                    
                    <div class="row">
                        <div class="col-sm-12">
                             <select class="form-control select2" id="contratoUsuarioVinculado" <?php echo $disabled; ?> >
                                <option></option>
                                <?php
                                   if (is_array($usuarios))
                                       foreach($usuarios as $i => $value)
                                       {
                                           $selected = ($value['id'] == $contrato['idUsuarioVinculado']) ? 'selected="selected"' : '';
                                           echo '<option '. $selected . ' value="'. $value['id'] . '">'. ucwords(strtolower($value['nome'])) . '</option>';
                                       }
                                ?>
                            </select>
                        </div>
                    </div>
                    
                    
                </div>
            </div>
        </div>
        <?php } ?>
        
        
        <div class="row">
            <div class="col-md-10">
                <div class="form-group">
                    <div class="row">
                        <div class="col-sm-12">
                            <label>Observa&ccedil;&atilde;o:</label>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-12">
                            <textarea class="form-control"   id="contratoObservacao" ><?php if (isset($contrato['observacao'])) echo $contrato['observacao']; ?></textarea>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        
    </div>
</section>

<div class="row box-button-save">
    <div class="col-md-12 box-button-save">
         <?php
        if (\Application::isAuthorized(ucfirst(strtolower(\Application::getNameController())) , 'contrato', 'escrever') )
        {?>
        <button type="button" class="mb-xs mt-xs mr-xs btn btn-danger" onclick="salvar()"><i class="material-icons material-align-icons-button">&#xE877;</i>&nbsp;Salvar</button>
        <?php } ?>
        
        <button type="button" class="mb-xs mt-xs mr-xs btn btn-grey" onclick="document.location='/<?php echo strtolower(\Application::getNameController()); ?>/cadastrar/'"><i class="material-icons material-align-icons-button">&#xE145;</i>&nbsp;Novo</button>
        
         <?php
        if (\Application::getUrlParams(0) !== null &&  \Application::isAuthorized(ucfirst(strtolower(\Application::getNameController())) , 'contrato', 'remover') )
        {?>
            <button type="button" class="mb-xs mt-xs mr-xs btn btn-grey" onclick="remover()"><i class="material-icons material-align-icons-button">&#xE92B;</i>&nbsp;Remover</button>
        <?php } ?>
        
    </div>
    
    
</div>


<script src="/library/jsvendor/bootstrap-datepicker/js/bootstrap-datepicker.js" ></script>
<script src="/library/jsvendor/select2/select2.min.js"></script>




<script>
    
$.getCSS('/library/jsvendor/select2/select2.css');
$.getCSS('/library/jsvendor/select2/select2.custom.css');
    
/*function ordenaSelectStatus() {
    var itensOrdenados = $('#status option').sort(function (a, b) {
        return a.text < b.text ? -1 : 1;
    });
    console.log(itensOrdenados);
    $('#status').html(itensOrdenados);
} 
    
function ordenaSelectSubStatus() {
    var itensOrdenados = $('#substatus option').sort(function (a, b) {
        return a.text < b.text ? -1 : 1;
    });

    $('#substatus').html(itensOrdenados);
} 
    
$(function(){
    ordenaSelectStatus();
    ordenaSelectSubStatus();
});*/

$(function(){
$('.select2').select2({
    placeholder: '',
    allowClear: true
});

});

    
function verCadastroCliente(a)
{
    a.href = '/cliente/cadastrar/'+ $('#cpf').val().replace(/[\.-]/gi,'');
}
    
    
    $.getCSS('/library/jsvendor/datatables/css/jquery.dataTables.min.css');
$.getScript( "/library/jsvendor/datatables/js/jquery.dataTables.min.js", function(  ) {
     /**
     * Inicia DataTable... CALLBACK executado após baixar dataTables.min.js
     * 
     **/
   table =  $('#datatable').DataTable({
           "bStateSave": false,
            "BLengthChange" : false,
             "iDisplayLength": 50,
             "bInfo": false,
             "bSort": false,  
             "bLengthChange": false,
             "paging": false,
             "searching": false,
             "oLanguage": {
                 "oPaginate": {
                     "sNext": "Pr&oacute;ximo",
                     "sPrevious": "Anterior"

                  },  
                 "sInfoEmpty": "",
                 "sSearch": "Pesquisar:",
                 "sZeroRecords": " " ,
                 "sInfo": "Mostrando página _PAGE_ de _PAGES_",
                 "sInfoFiltered": "(Filtrado _MAX_ do total)"
              } 
        } );
}); // fim $.GetScript
    
$.getScript('/library/javascript/contratos/cadastrar.js');
    
    
$.getScript('/library/jsvendor/jquery-masked-input/jquery.maskedinput.min.js', function(){
    $('#cpf').mask('999.999.999-99',{placeholder:"___.___.___-__", autoclear: false});

});
    
$.getScript('/library/jsvendor/jquery-maskmoney/dist/jquery.maskMoney.min.js', function(){
     $('#contratoValorParcela').maskMoney({prefix:'R$ ', allowNegative: false, thousands:'.', decimal:',', affixesStay: true});
     $('#contratoSeguro').maskMoney({prefix:'R$ ', allowNegative: false, thousands:'.', decimal:',', affixesStay: true});
    $('#contratoValorTotal').maskMoney({prefix:'R$ ', allowNegative: false, thousands:'.', decimal:',', affixesStay: true});
    $('#contratoValorLiquido').maskMoney({prefix:'R$ ', allowNegative: false, thousands:'.', decimal:',', affixesStay: true});

});
    
        
  
<?php
   
    if (isset($contrato['id']) )
        echo 'key=' . $contrato['id']. ';';
    if (isset($subTabela['idBanco']))
        echo 'idBancoSubtabela=' . $subTabela['idBanco']. ';';
    if (isset($contrato['idContaBancariaCliente']))
        echo 'idContaBancariaCliente=' . $contrato['idContaBancariaCliente']. ';';
?>     
    
    
function remover()
{
    if (! confirm('Deseja realmente excluir este contrato? A informação será removida para sempre.'))
        return false;
    
    if (typeof key == undefined)
    {
        alert('Este contrato ainda não foi salvo');
        return false;
    }else
        var id = key;
    $.ajax({
        type: "POST",
        url: '<?php echo '/'. strtolower(\Application::getNameController()) . '/excluir-contrato/'; ?>',
        cache: false,
        dataType: "json",
        data: 'id='+id,
        success: function(json){
            if (json.success)
             {
                 alert('Registro excluído com sucesso');
                 //$('#id').val(json.id);
                 document.location = '<?php echo '/'. strtolower(\Application::getNameController()) . '/listar-contratos/'; ?>';
             }
            else
            {
                alert('Não foi possível realizar a alteração\nMotivo: ' + json.message);
                //document.location.reload();
            }
        }
    });
    
}
    
    
</script>