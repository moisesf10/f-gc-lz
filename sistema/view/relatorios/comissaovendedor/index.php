<?php
$usuarios = $this->getParams('usuarios');
$operacoes = $this->getParams('operacoes');
?>

<form name="form1" method="post" action="/relatorios/gerar-comissao-vendedor/" target="_blank">
<section class="panel">
        <header class="panel-heading">
            <h2 class="panel-title">Relatórios Vendedor</h2>
        </header>
        <div class="panel-body">
                <div class="form-group">
                        <label class="col-md-3 control-label">Nome do Vendedor</label>
                        <div class="col-md-3">
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
                        <label class="col-md-3 control-label">Modificado em</label>
                        <div class="col-md-3">
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
                        <div class="col-md-3">
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
                        <div class="col-md-3">
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
                        <div class="col-md-3">
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
                        <div class="col-md-3">
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
                                    <select multiple="" class="form-control" name="status" id="status">
                                        <option value="Pago ao Cliente">Pago ao cliente</option>
                                        <option value="Reprovado">Reprovado</option>
                                        <option value="Em Andamento">Em andamento </option>
                                       
                                        <option value="Em Análise">Em Análise</option>
                                    </select>
                                </div>
                            </div>
                </div>
    
        <div class="panel-body">
             <div class="form-group">
                        <label class="col-md-3 control-label">Operação</label>
                        <div class="col-md-3">
                            <select data-plugin-selectTwo class="form-control " id="operacao" name="operacao">
                                <optgroup label="Selecione a operacao">
                                    <option></option>
                                    <?php
                                        if (is_array($operacoes))
                                            foreach($operacoes as $i => $value)
                                                echo '<option value="'. $value['nome'] . '">'. $value['nome'] . '</option>';
                                    ?>
                                    
                                </optgroup>

                            </select>
                        </div>
                    </div>
    
        </div>
    
        <div class="panel-body">
            <div class="form-group">
                <label class="control-label col-md-3">Comissão bloqueada ?</label>
                <div class="col-md-9">
                    <div class="switch switch-lg switch-primary">

                    </div>
                    <div class="switch switch-lg switch-danger">
                        <input value="Sim" type="checkbox" name="comissaobloqueada" id="comissaobloqueada" class="toggle-button"  data-onstyle="danger" data-on="Sim" data-off="Não" data-size="small" checked />
                    </div>

                    </div>
                </div>
            </div>
    
        <div class="panel-body">
                <div class="form-group">
                    <label class="col-md-3 control-label" for="textareaDefault">Observações</label>
                    <div class="col-md-6">
                        <textarea class="form-control" rows="3" id="observacoes" name="observacoes"></textarea>
                    </div>
                </div>

        </div>
    
    <div class="panel-body">
          <button type="button" class="mb-xs mt-xs mr-xs btn btn-danger" onclick="gerarPdf()">Gerar Relatório</button>
    </div>
    
    <input type="hidden" value="" name="type" id="type">
    
</section>
</form>
<script src="/library/jsvendor/select2/select2.min.js"></script>
<script src="/library/jsvendor/bootstrap-datepicker/js/bootstrap-datepicker.js"></script>

<script>
$.getCSS('/library/jsvendor/select2/select2.css');
    $.getCSS('/library/jsvendor/select2/select2.custom.css');
$.getCSS('/library/jsvendor/bootstrap-toggle/css/bootstrap-toggle.min.css');
$.getScript('/library/jsvendor/bootstrap-toggle/js/bootstrap-toggle.min.js', function(){
    $('.toggle-button').bootstrapToggle();
});
    
function gerarPdf()
{
    $('#type').val('pdf');
    if ($('#usuario').val() == '')
    {
        alert('Escolha o usuário');
        return false;
    }
    document.form1.submit();
}
    
    
</script>

