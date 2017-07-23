<?php
$convenios = $this->getParams('convenios');
//$operacoes = $this->getParams('operacoes');
$usuarios = $this->getParams('usuarios');

?>



<form name="form1" method="post" action="/relatorios/gerar-cliente-fichario/" target="_blank">
<section class="panel">
        <header class="panel-heading">
            <h2 class="panel-title">Relatório Cliente Fichário</h2>
        </header>
        <div class="panel-body">
                <div class="form-group">
                        <label class="col-md-3 control-label">Entidade/Conv&ecirc;nio</label>
                        <div class="col-md-4">
                            <select data-plugin-selectTwo class="form-control " id="convenio" name="convenio" >
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
                        <label class="col-md-3 control-label">Usuário</label>
                        <div class="col-md-4">
                            <select data-plugin-selectTwo class="form-control " id="usuario" name="usuario" >
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
                        <label class="col-md-3 control-label">Data de Nascimento</label>
                        <div class="col-md-4">
                            <div class="input-daterange input-group" data-plugin-datepicker>
                                <span class="input-group-addon">
                                    <i class="fa fa-calendar"></i>
                                </span>
                                <input type="text" class="form-control" name="datainicial" id="datainicial" onblur="carregarClientes()">
                                <span class="input-group-addon">Até</span>
                                <input type="text" class="form-control" name="datafinal" id="datafinal" onblur="carregarClientes()" >
                            </div>
                        </div>
                    </div>
        </div>
    
    <div class="panel-body">
                <div class="form-group">
                        <label class="col-md-3 control-label">Nome Inicial</label>
                        <div class="col-md-4">
                            <select data-plugin-selectTwo class="form-control " id="nomeinicial" name="nomeinicial">
                                <optgroup label="Selecione o nome do cliente">
                                    <option></option>
                                    
                                </optgroup>

                            </select>
                        </div>
                    </div>
            
        </div>
    <div class="panel-body">
                <div class="form-group">
                        <label class="col-md-3 control-label">Nome Final</label>
                        <div class="col-md-4">
                            <select data-plugin-selectTwo class="form-control " id="nomefinal" name="nomefinal">
                                <optgroup label="Selecione o nome do cliente">
                                    <option></option>
                                                                       
                                </optgroup>

                            </select>
                        </div>
                    </div>
            
        </div>
    
   
    
      
    
    <div class="panel-body">
          <button type="button" class="mb-xs mt-xs mr-xs btn btn-danger" onclick="gerar('pdf')">Gerar Relatório em PDF</button>
          <button type="button" class="mb-xs mt-xs mr-xs btn btn-success" onclick="gerar('excel')">Gerar Relatório em Excel</button>
          <button type="button" class="mb-xs mt-xs mr-xs btn btn-info" onclick="gerar('etiquetas')">Gerar Relatório em Etiquetas</button>
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
    
    
 $(function(){
     
    $('#convenio').change(carregarClientes);
	$('#usuario').change(carregarClientes);
     
 });  
    

   
function carregarClientes()
{
    var convenio = $('#convenio').val();
	var usuario = $('#usuario').val();
    var dataInicial = $.trim($('#datainicial').val());
    var dataFinal = $.trim($('#datafinal').val());
    
    if (convenio != '')
    {
        // se os 3 primeiros campos estiverem preenchidos, tenta localizar os clientes
         $.ajax({
              type: "POST",
              url:  '/<?php echo  strtolower(\Application::getNameController()); ?>/pesquisar-cliente-fichario/',
              data: '&convenio=' + convenio + '&datainicial=' + dataInicial + '&datafinal=' + dataFinal + '&usuario=' + usuario ,
              dataType: 'json',
              success: function(json){

                  var usuarios = '<optgroup label="Selecione o nome do cliente"><option value=""> </option>';
                  for (var i in json)
                    usuarios += '<option value="' + json[i].dados.nomeCliente + '">' + json[i].dados.nomeCliente + '</option>';
                  usuarios += '</optgroup>';
                $('#nomeinicial').select2('destroy');
                $('#nomeinicial').html(usuarios);
                $('#nomeinicial').select2();
                $('#nomefinal').select2('destroy');
                $('#nomefinal').html(usuarios);
                $('#nomefinal').select2();


              },
        });
    }else
    {
        $('#nomeinicial').select2('destroy');
        $('#nomeinicial').html('<option value=""> </option>');
        $('#nomeinicial').select2();
        $('#nomefinal').select2('destroy');
        $('#nomefinal').html('<option value=""> </option>');
        $('#nomefinal').select2();

    }

}
    

    
    
function gerar(type)
{
    $('#type').val(type);
    if ($('#convenio').val() == ''  || $.trim($('#nomeinicial').val()) == '' ||  $.trim($('#nomefinal').val()) == ''   )
    {
        alert('Somente data inicial e data final não são de preenchimento obrigatório');
        return false;
    }
    
    document.form1.submit();
}
    
    
</script>

