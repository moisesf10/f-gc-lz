<?php

$substatus = $this->getParams('substatus');



?>

<style>
    section.panel div.panel-body div.row {padding-bottom: 1rem;}
    div.botoes {margin-left: 0.2rem;}
    div.botoes div {padding-right: 1rem; }
  
</style>
<form>
<section class="panel">
        <header class="panel-heading">
            <h2 class="panel-title">Cadastro de Banco</h2>
        </header>
        <div class="panel-body">
            <div class="row">
                <div class="col-md-6">
                            <div class="form-group">
                                <label class="col-sm-2 control-label">ID: </label>
                                <div class="col-sm-6">
                                    <input type="text" id="id" name="id"  class="form-control" disabled value="<?php  if (is_array($substatus) && count($substatus) > 0) echo $substatus['id']; ?>" >
                                </div>
                            </div>
                    </div>
            </div>
          

            
            <div class="row">
                <div class="col-md-6">
                            <div class="form-group">
                                <label class="col-sm-2 control-label">Descrição: </label>
                                <div class="col-sm-6">
                                    <input type="text" id="descricao" name="descricao" class="form-control" value="<?php  if (is_array($substatus) && count($substatus) > 0) echo $substatus['descricao']; ?>" >
                                </div>
                            </div>
                    </div>
            </div>
            
            <div class="row">
                <div class="col-md-6">
                            <div class="form-group">
                                <label class="col-sm-2 control-label">Status: </label>
                                <div class="col-sm-6">
                                    <select class="form-control" id="status">
                                        <option <?php echo ($substatus != null && $substatus['status'] == true) ? 'selected="selected"' : ''; ?> value="1">Ativo</option>
                                        <option <?php echo ($substatus != null &&  $substatus['status'] == false) ? 'selected="selected"' : ''; ?> value="0">Desabilitado</option>
                                    </select>
                                </div>
                            </div>
                    </div>
            </div>
     
    </div>
    
    
    <div class="row botoes">
        <?php
        if (\Application::isAuthorized(ucfirst(strtolower(\Application::getNameController())) , 'substatus_contrato_menu', 'escrever') )
        {?>
        <button type="button" class="mb-xs mt-xs mr-xs btn btn-danger" onclick="salvar()"><i class="material-icons material-align-icons-button">&#xE877;</i>&nbsp;Salvar</button>
        <?php } ?>
        
        <?php
        if (\Application::getUrlParams(0) !== null &&  \Application::isAuthorized(ucfirst(strtolower(\Application::getNameController())) , 'substatus_contrato_menu', 'remover') )
        {?>
            <button type="button" class="mb-xs mt-xs mr-xs btn btn-grey" onclick="remover()"><i class="material-icons material-align-icons-button">&#xE92B;</i>&nbsp;Remover</button>
        <?php } ?>
        
         <button type="button" class="mb-xs mt-xs mr-xs btn btn-grey" onclick="document.location = '/administracao/substatus-contrato-cadastrar'"><i class="material-icons material-align-icons-button">&#xE145;</i>&nbsp;Novo</button>
        
      
    
    </div>
    
</section>

</form>


<script>



function salvar()
{
    var id = $('#id').val();
  
    var descricao = $.trim( $('#descricao').val());
    var status = $('#status').val();
    
    if (descricao == '' )
    {
        alert('A descrição é obrigatória');
        return false;
    }
    
   
    
    $.ajax({
        type: "POST",
        url: '<?php echo '/'. strtolower(\Application::getNameController()) . '/salvar-substatus-contrato'; ?>',
        cache: false,
        dataType: "json",
        data: 'id='+id +  '&descricao=' + descricao + '&status='+ status,
        success: function(json){
            if (json.success)
             {
                 alert('Registro salvo com sucesso');
                 //$('#id').val(json.id);
                 document.location = '<?php echo '/'. strtolower(\Application::getNameController()) . '/substatus-contrato-cadastrar/'; ?>' + json.id;
             }
            else
            {
                alert('Não foi possível realizar a alteração\nMotivo: ' + json.message);
                //document.location.reload();
            }
        }
    });
    
}
    
    
function remover()
{
    if (! confirm('Deseja realmente excluir este status? A informação será removida para sempre.'))
        return false;
     var id = $('#id').val();
    $.ajax({
        type: "POST",
        url: '<?php echo '/'. strtolower(\Application::getNameController()) . '/apagar-substatus-contrato/'; ?>',
        cache: false,
        dataType: "json",
        data: 'id='+id,
        success: function(json){
            if (json.success)
             {
                 alert('Registro excluído com sucesso');
                 //$('#id').val(json.id);
                 document.location = '<?php echo '/'. strtolower(\Application::getNameController()) . '/substatus-contrato-cadastrar/'; ?>';
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