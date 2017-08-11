<?php

$operacoes = $this->getParams('operacoes');
$bancos = $this->getParams('bancos');

?>



<form name="form1" method="post" action="/relatorios/gerar-despesas-pagar/" target="_blank">
<section class="panel">
        <header class="panel-heading">
            <h2 class="panel-title">Relatórios de Despesas a Pagar</h2>
        </header>
    
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
                        <label class="col-md-3 control-label">Banco</label>
                        <div class="col-md-4">
                            <select data-plugin-selectTwo class="form-control " id="banco" name="banco">
                                <optgroup label="Selecione o nome do vendedor">
                                    <option></option>
                                    <?php
                                        if (is_array($bancos))
                                            foreach($bancos as $i => $value)
                                                echo '<option value="'. $value['nome'] . '">'. $value['nome'] . '</option>';
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
                                                echo '<option value="'. $value['nome'] . '">'. $value['nome'] . '</option>';
                                    ?>
                                    
                                </optgroup>
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
    var dataInicial = $('#datainicial').val();
    var dataFinal = $('#datafinal').val();
    if (dataInicial == '' || dataFinal == '')
    {
        alert('A data é obrigatória');
        return false;
    }
    
    document.form1.submit();
}
    
    
</script>

