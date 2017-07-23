<?php
$convenios = (isset($this->getParams('convenios')[0])) ? $this->getParams('convenios')[0] : null;


if (\Application::getUrlParams(0) !== null && $convenios === null)
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
            <h2 class="panel-title">Cadastro de Convênios/Entidades</h2>
        </header>
        <div class="panel-body">
            <div class="row">
                <div class="col-md-6">
                            <div class="form-group">
                                <label class="col-sm-2 control-label">ID: </label>
                                <div class="col-sm-6">
                                    <input type="text" id="id" name="id"  class="form-control" disabled value="<?php  if (is_array($convenios) && count($convenios) > 0) echo $convenios['id']; ?>" >
                                </div>
                            </div>
                    </div>
            </div>
          
        
            <div class="row">
                <div class="col-md-6">
                            <div class="form-group">
                                <label class="col-sm-2 control-label">Nome: </label>
                                <div class="col-sm-6">
                                    <input type="text" id="nome" name="nome" class="form-control" value="<?php  if (is_array($convenios) && count($convenios) > 0) echo $convenios['nome']; ?>" >
                                </div>
                            </div>
                    </div>
            </div>

     
    </div>
    
    
    <div class="row botoes">
        <?php
        if (\Application::isAuthorized(ucfirst(strtolower(\Application::getNameController())) , 'convenio_entidades', 'escrever') )
        {?>
        <button type="button" class="mb-xs mt-xs mr-xs btn btn-danger" onclick="salvar()"><i class="material-icons material-align-icons-button">&#xE877;</i>&nbsp;Salvar</button>
        <?php } ?>
        
        <?php
        if (\Application::getUrlParams(0) !== null &&  \Application::isAuthorized(ucfirst(strtolower(\Application::getNameController())) , 'convenio_entidades', 'remover') )
        {?>
            <button type="button" class="mb-xs mt-xs mr-xs btn btn-grey" onclick="remover()"><i class="material-icons material-align-icons-button">&#xE92B;</i>&nbsp;Remover</button>
        <?php } ?>
        
         <button type="button" class="mb-xs mt-xs mr-xs btn btn-grey" onclick="document.location = '/administracao/cadastrar-convenio'"><i class="material-icons material-align-icons-button">&#xE145;</i>&nbsp;Novo</button>
        
      
    
    </div>
    
</section>

</form>


<script>



function salvar()
{
    var id = $('#id').val();

    var nome = $('#nome').val();
  
     
    
    if ($.trim(nome) == '' )
    {
        alert('Preencha o nome corretamente');
        return false;
    }
    
    $.ajax({
        type: "POST",
        url: '<?php echo '/'. strtolower(\Application::getNameController()) . '/salvar-convenio'; ?>',
        cache: false,
        dataType: "json",
        data: 'id='+id +  '&nome=' + nome,
        success: function(json){
            if (json.success)
             {
                 alert('Registro salvo com sucesso');
                 //$('#id').val(json.id);
                 document.location = '<?php echo '/'. strtolower(\Application::getNameController()) . '/cadastrar-convenio/'; ?>' + json.id;
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
    if (! confirm('Deseja realmente excluir este convênio/entidade? A informação será removida para sempre.'))
        return false;
     var id = $('#id').val();
    $.ajax({
        type: "POST",
        url: '<?php echo '/'. strtolower(\Application::getNameController()) . '/apagar-convenio/'; ?>',
        cache: false,
        dataType: "json",
        data: 'id='+id,
        success: function(json){
            if (json.success)
             {
                 alert('Registro excluído com sucesso');
                 //$('#id').val(json.id);
                 document.location = '<?php echo '/'. strtolower(\Application::getNameController()) . '/convenios/'; ?>';
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