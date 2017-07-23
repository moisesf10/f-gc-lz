<?php
$convenios = $this->getParams('convenios');
$operacoes = $this->getParams('operacoes');
$bancos = $this->getParams('bancos');
$usuarios = $this->getParams('usuarios');
?>



<form name="form1" method="post" action="/relatorios/gerar-comissao-loja/" target="_blank">
<section class="panel">
        <header class="panel-heading">
            <h2 class="panel-title">Relatórios Comiss&atilde;o Loja</h2>
        </header>
        <div class="panel-body">
                <div class="form-group">
                        <label class="col-md-3 control-label">Banco</label>
                        <div class="col-md-4">
                            <select data-plugin-selectTwo class="form-control " id="banco" name="banco">
                                <optgroup label="Selecione o nome do vendedor">
                                    <option></option>
                                    <?php
                                        if (is_array($bancos))
                                            foreach($bancos as $i => $value)
                                                echo '<option value="'. $value['id'] . '">'. $value['nome'] . '</option>';
                                    ?>
                                    
                                </optgroup>

                            </select>
                        </div>
                    </div>
            
        </div>
    
        <div class="panel-body">
                <div class="form-group">
                        <label class="col-md-3 control-label">Entidade/Conv&ecirc;nio</label>
                        <div class="col-md-4">
                            <select data-plugin-selectTwo class="form-control " id="convenio" name="convenio">
                                <optgroup label="Selecione o nome do vendedor">
                                    <option></option>
                                    <?php
                                        if (is_array($convenios))
                                            foreach($convenios as $i => $value)
                                                echo '<option value="'. $value['id'] . '">'. $value['nome'] . '</option>';
                                    ?>
                                    
                                </optgroup>

                            </select>
                        </div>
                    </div>
            
        </div>
        <div class="panel-body">
                <div class="form-group">
                        <label class="col-md-3 control-label">Operação</label>
                        <div class="col-md-4">
                            <select data-plugin-selectTwo class="form-control " id="operacao" name="operacao">
                                <optgroup label="Selecione o nome do vendedor">
                                    <option></option>
                                    <?php
                                        if (is_array($operacoes))
                                            foreach($operacoes as $i => $value)
                                                echo '<option value="'. $value['id'] . '">'. $value['nome'] . '</option>';
                                    ?>
                                    
                                </optgroup>
                            </select>
                        </div>
                    </div>
        </div>
        <div class="panel-body">
                <div class="form-group">
                        <label class="col-md-3 control-label">Usuario</label>
                        <div class="col-md-4">
                            <select data-plugin-selectTwo class="form-control " id="usuario" name="usuario">
                                <optgroup label="Selecione o nome do vendedor">
                                    <option></option>
                                    <?php
                                        if (is_array($usuarios))
                                            foreach($usuarios as $i => $value)
                                                echo '<option value="'. $value['id'] . '">'. $value['nome'] . '</option>';
                                    ?>
                                    
                                </optgroup>

                            </select>
                        </div>
                    </div>
            
        </div>
        <div class="panel-body">
            <div class="form-group">
                        <label class="col-md-3 control-label">Período</label>
                        <div class="col-md-4">
                            <div class="input-daterange input-group" data-plugin-datepicker>
                                <span class="input-group-addon">
                                    <i class="fa fa-calendar"></i>
                                </span>
                                <input type="text" class="form-control" name="datainicial" id="datainicial">
                                <span class="input-group-addon">Até</span>
                                <input type="text" class="form-control" name="datafinal" id="datafinal">
                            </div>
                        </div>
                    </div>
        </div>
		
		<div class="panel-body">
                    <div class="form-group">
                        <label class="col-md-3 control-label">Status do Pagamento</label>
                        <div class="col-md-4">
                            <select class="form-control" id="statuspagamento" name="statuspagamento">
                                        <option></option>
                                        <option value="" >Todos</option>
                                        <option value="Pago" >Pago</option>
                                        <option value="Aberto" >Não Pago</option>
                                    </select>
                        </div>
                    </div>
            </div>
    
        <div class="panel-body">
                    <div class="form-group">
                        <label class="col-md-3 control-label">Data do Pagamento</label>
                        <div class="col-md-4">
                            <div class="input-daterange input-group" data-plugin-datepicker>
                                <span class="input-group-addon">
                                    <i class="fa fa-calendar"></i>
                                </span>
                                <input type="text" class="form-control" name="datapagamentoinicio" id="datapagamentoinicio">
                               <span class="input-group-addon">Até</span>
                                <input type="text" class="form-control" name="datapagamentofim" id="datapagamentofim">
                            </div>
                        </div>
                    </div>
            </div>
		
    
    <div class="panel-body">
                    <div class="form-group">
                        <label class="col-md-3 control-label">Recebido Comissão Banco</label>
                        <div class="col-md-4">
                            <select class="form-control" id="statusbanco" name="statusbanco">
                                        <option></option>
                                        <option value="" >Todos</option>
                                        <option value="Sim" >Sim</option>
                                        <option value="Nao" >Não</option>
                                    </select>
                        </div>
                    </div>
            </div>
    
        <div class="panel-body">
                    <div class="form-group">
                        <label class="col-md-3 control-label">Data da Comissão do Banco</label>
                        <div class="col-md-4">
                            <div class="input-daterange input-group" data-plugin-datepicker>
                                <span class="input-group-addon">
                                    <i class="fa fa-calendar"></i>
                                </span>
                                <input type="text" class="form-control" name="databancoinicio" id="databancoinicio">
                               <span class="input-group-addon">Até</span>
                                <input type="text" class="form-control" name="databancofim" id="databancofim">
                            </div>
                        </div>
                    </div>
            </div>
    
    
    <div class="panel-body">
                	<div class="form-group">
                                <label class="col-md-3 control-label" for="inputSuccess">Selecione o Status do Contrato</label>
                                <div class="col-md-6">
                                    <select multiple="" class="form-control" name="status[]" id="status">
                                        <option value="Pago ao Cliente">Pago ao cliente</option>
                                        <option value="Reprovado">Reprovado</option>
                                        <option value="Em Andamento">Em andamento </option>
                               
                                        <option value="Em Análise">Em Análise</option>
									<!--	<option value="Finalizado">Finalizado</option> -->
                                    </select>
                                </div>
                            </div>
                </div>
    
      
    
    <div class="panel-body">
          <button type="button" class="mb-xs mt-xs mr-xs btn btn-danger" onclick="gerar('pdf')">Gerar Relatório</button>
    </div>
    
    <input type="hidden" value="" name="type" id="type">
    
</section>
</form>
<script src="/library/jsvendor/select2/select2.min.js"></script>
<script src="/library/jsvendor/bootstrap-datepicker/js/bootstrap-datepicker.js"></script>

<script>
$.getCSS('/library/jsvendor/select2/select2.css');
$.getCSS('/library/jsvendor/select2/select2.custom.css');
$.getScript('/library/jsvendor/bootstrap-toggle/js/bootstrap-toggle.min.js', function(){
    $('.toggle-button').bootstrapToggle();
});
    
function gerar(type)
{
    $('#type').val(type);
   /* if ($('#datainicial').val() == '' || $('#datafinal').val() == '')
    {
        alert('Informe o período');
        return false;
    }
    
     if ($('#status').val() == null)
    {
        alert('Informe o status');
        return false;
    }
	*/
    document.form1.submit();
}
    
    
</script>

