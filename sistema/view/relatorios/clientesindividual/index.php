<?php
$clientes = $this->getParams('clientes');
//echo '<pre>'; var_dump($clientes); echo '</pre>';
?>

<form name="form1" method="post" action="/relatorios/gerar-cliente-individual/" target="_blank">
    <section class="panel">
            <header class="panel-heading">
                <h2 class="panel-title">Relatório Cliente Individual</h2>
            </header>
            <div class="panel-body">
                        <div class="form-group">
                            <label class="col-md-3 control-label">Nome do Cliente</label>
                            <div class="col-md-6">
                                <select data-plugin-selectTwo class="form-control populate" name="cliente" id="cliente">
                                    <optgroup label="Selecione o nome do Cliente">
                                        <option value=""></option>
                                        <?php
                                            if (is_array($clientes))
                                                foreach($clientes as $i => $value)
                                                    echo '<option value="'. $value['dados']['cpf'] . '">'. $value['dados']['nomeCliente'] . '</option>';
                                        ?>

                                    </optgroup>

                                </select>
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
            <button type="button" class="btn btn-danger mr-xs mb-sm" onclick="gerarRelatorio('pdf')">Gerar Relatório</button>
           
        </div>

    </section>
    <input type="hidden" value="" name="type" id="type">
</form>  
    
    
    
    <script src="/library/jsvendor/select2/select2.min.js"></script>
    <script>
        $.getCSS('/library/jsvendor/select2/select2.css');
        $.getCSS('/library/jsvendor/select2/select2.custom.css');
        
 function gerarRelatorio(type)
{
    $('#type').val(type);
    if ($('#cliente').val() == '')
    {
        alert('Escolha o nome do cliente');
        return false;
    }
    document.form1.submit();
}
        
    </script>