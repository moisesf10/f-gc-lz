<?php
$grupos = $this->getParams('grupos');

?>

<form name="form1" method="post" action="/relatorios/gerar-comissao-grupo/" target="_blank">
<section class="panel">
        <header class="panel-heading">
            <h2 class="panel-title">Relatório de Comissão de Grupo</h2>
        </header>
        <div class="panel-body">
                <div class="form-group">
                        <label class="col-md-2 control-label">Nome do Grupo</label>
                        <div class="col-md-4">
                            <select data-plugin-selectTwo class="form-control " id="grupo" name="grupo">
                                <optgroup label="Selecione o nome do vendedor">
                                    <option></option>
                                    <?php
                                        if (is_array($grupos))
                                            foreach($grupos as $i => $value)
                                                echo '<option value="'. $value['id'] . '">'. $value['nome'] . '</option>';
                                    ?>
                                    
                                </optgroup>

                            </select>
                        </div>
                    </div>
        </div>
        <div class="panel-body">
            <div class="form-group">
                        <label class="col-md-2 control-label">Período</label>
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
    
    function gerar(type)
{
    $('#type').val(type);
    if ($('#grupo').val() == '')
    {
        alert('Escolha o Grupo');
        return false;
    }
    
    
    document.form1.submit();
}
    
</script>