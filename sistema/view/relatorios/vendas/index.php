<?php
$grupos = $this->getParams('grupos');


?>



<form name="form1" method="post" action="/relatorios/gerar-vendas/" target="_blank">
<section class="panel">
        <header class="panel-heading">
            <h2 class="panel-title">Relatório de Vendas</h2>
        </header>
        <div class="panel-body">
                <div class="form-group">
                        <label class="col-md-3 control-label">Grupo</label>
                        <div class="col-md-4">
                            <select data-plugin-selectTwo class="form-control " id="grupo" name="grupo" >
                                <optgroup label="Selecione o nome do grupo">
                                    <option></option>
                                    <?php
                                        if (is_array($grupos))
                                            foreach($grupos as $i => $value)
                                                echo '<option value="'. $value['nome'] . '">'. strtoupper($value['nome']) . '</option>';
                                    ?>
                                    
                                </optgroup>

                            </select>
                        </div>
                    </div>
            
        </div>
		
		
        
        <div class="panel-body">
            <div class="form-group">
                        <label class="col-md-3 control-label">Data de Pagamento</label>
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
          <button type="button" class="mb-xs mt-xs mr-xs btn btn-danger" onclick="gerar()">Gerar Relatório</button>
        <!--  <button type="button" class="mb-xs mt-xs mr-xs btn btn-success" onclick="gerar('excel')">Gerar Relatório em Excel</button>
          <button type="button" class="mb-xs mt-xs mr-xs btn btn-info" onclick="gerar('etiquetas')">Gerar Relatório em Etiquetas</button> -->
    </div>
    
    <input type="hidden" value="" name="type" id="type">
    
</section>
</form>
<script src="/library/jsvendor/select2/select2.min.js"></script>
<script src="/library/jsvendor/bootstrap-datepicker/js/bootstrap-datepicker.js"></script>

<script>
$.getCSS('/library/jsvendor/select2/select2.css');
$.getCSS('/library/jsvendor/select2/select2.custom.css');

    

    

   
    

    
    
function gerar()
{
  
    if ($('#datainicial').val() == ''  || $.trim($('#datafinal').val()) == ''  )
    {
        alert('Por favor, informe a data de pagamento');
        return false;
    }
    
    document.form1.submit();
}
    
    
</script>

