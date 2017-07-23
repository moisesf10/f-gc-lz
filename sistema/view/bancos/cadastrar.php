<?php
$banco = (isset($this->getParams('banco')[0])) ? $this->getParams('banco')[0] : null;


if (\Application::getUrlParams(0) !== null && $banco === null)
    \Application::print404();
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
                                    <input type="text" id="id" name="id"  class="form-control" disabled value="<?php  if (is_array($banco) && count($banco) > 0) echo $banco['id']; ?>" >
                                </div>
                            </div>
                    </div>
            </div>
          
            <div class="row">
                <div class="col-md-6">
                            <div class="form-group">
                                <label class="col-sm-2 control-label">Codigo: </label>
                                <div class="col-sm-6">
                                    <input type="text" id="codigo" name="codigo" class="form-control" value="<?php  if (is_array($banco) && count($banco) > 0) echo $banco['codigo']; ?>" >
                                </div>
                            </div>
                    </div>
            </div>
            
            <div class="row">
                <div class="col-md-6">
                            <div class="form-group">
                                <label class="col-sm-2 control-label">Nome: </label>
                                <div class="col-sm-6">
                                    <input type="text" id="nome" name="nome" class="form-control" value="<?php  if (is_array($banco) && count($banco) > 0) echo $banco['nome']; ?>" >
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
                                        <option <?php echo ($banco != null && $banco['status'] == 1) ? 'selected="selected"' : ''; ?> value="1">Ativo</option>
                                        <option <?php echo ($banco != null &&  $banco['status'] == 0) ? 'selected="selected"' : ''; ?> value="0">Desabilitado</option>
                                    </select>
                                </div>
                            </div>
                    </div>
            </div>
     
    </div>
    
    
    <div class="row botoes">
        <?php
        if (\Application::isAuthorized(ucfirst(strtolower(\Application::getNameController())) , 'bancos', 'escrever') )
        {?>
        <button type="button" class="mb-xs mt-xs mr-xs btn btn-danger" onclick="salvar()"><i class="material-icons material-align-icons-button">&#xE877;</i>&nbsp;Salvar</button>
        <?php } ?>
        
        <?php
        if (\Application::getUrlParams(0) !== null &&  \Application::isAuthorized(ucfirst(strtolower(\Application::getNameController())) , 'bancos', 'remover') )
        {?>
            <button type="button" class="mb-xs mt-xs mr-xs btn btn-grey" onclick="remover()"><i class="material-icons material-align-icons-button">&#xE92B;</i>&nbsp;Remover</button>
        <?php } ?>
        
         <button type="button" class="mb-xs mt-xs mr-xs btn btn-grey" onclick="document.location = '/administracao/cadastrar-banco'"><i class="material-icons material-align-icons-button">&#xE145;</i>&nbsp;Novo</button>
        
      
    
    </div>
    
</section>

</form>


<script>



function salvar()
{
    var id = $('#id').val();
    var codigo = $('#codigo').val();
    var nome = $('#nome').val();
    var status = $('#status').val();
    
    if (nome == '' )
    {
        alert('O nome é obrigatório');
        return false;
    }
    
   
    
    if ($.trim(nome) == '' )
    {
        alert('Preencha o nome corretamente');
        return false;
    }
    
    $.ajax({
        type: "POST",
        url: '<?php echo '/'. strtolower(\Application::getNameController()) . '/salvar-banco'; ?>',
        cache: false,
        dataType: "json",
        data: 'id='+id + '&codigo='+ codigo + '&nome=' + nome + '&status='+ status,
        success: function(json){
            if (json.success)
             {
                 alert('Registro salvo com sucesso');
                 //$('#id').val(json.id);
                 document.location = '<?php echo '/'. strtolower(\Application::getNameController()) . '/cadastrar-banco/'; ?>' + json.id;
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
    if (! confirm('Deseja realmente excluir este banco? A informação será removida para sempre.'))
        return false;
     var id = $('#id').val();
    $.ajax({
        type: "POST",
        url: '<?php echo '/'. strtolower(\Application::getNameController()) . '/apagar-banco/'; ?>',
        cache: false,
        dataType: "json",
        data: 'id='+id,
        success: function(json){
            if (json.success)
             {
                 alert('Registro excluído com sucesso');
                 //$('#id').val(json.id);
                 document.location = '<?php echo '/'. strtolower(\Application::getNameController()) . '/bancos/'; ?>';
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