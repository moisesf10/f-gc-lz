<?php
$feriado = isset($this->getParams('feriado')[0]) ? $this->getParams('feriado')[0] : null;


if (\Application::getUrlParams(0) !== null && $feriado === null)
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
            <h2 class="panel-title">Feriados</h2>
        </header>
        <div class="panel-body">
            <div class="row">
                <div class="col-md-4">
                            <div class="form-group">
                                <label class="col-sm-4 control-label">ID: </label>
                                <div class="col-sm-8">
                                    <input type="text" id="id" name="id"  class="form-control" disabled value="<?php  if (isset($feriado['id'])) echo $feriado['id']; ?>" >
                                </div>
                            </div>
                    </div>
            </div>
            <div class="row">
                <div class="col-md-4">
                            <div class="form-group">
                                <label class="col-sm-4 control-label">Descrição: </label>
                                <div class="col-sm-8">
                                    <input type="text" id="descricao" name="descricao" class="form-control" value="<?php  if (isset($feriado['descricao'])) echo $feriado['descricao']; ?>" >
                                </div>
                            </div>
                    </div>
            </div>
            
            <div class="row">
                <div class="col-md-4">
                            <div class="form-group">
                                <label class="col-sm-4 control-label">Data: </label>
                                <div class="col-sm-8">
                                     <input type="text" id="data" name="data" class="form-control" tabindex="3" data-plugin-datepicker value="<?php if(isset($feriado['data'])) echo $feriado['data']; ?>">
                                </div>
                            </div>
                    </div>
            </div>
     
    </div>
    
    
    <div class="row botoes">
        <?php
        if (\Application::isAuthorized(ucfirst(strtolower(\Application::getNameController())) , 'feriados', 'escrever') )
        {?>
        <button type="button" class="mb-xs mt-xs mr-xs btn btn-danger" onclick="salvar()"><i class="material-icons material-align-icons-button">&#xE877;</i>&nbsp;Salvar</button>
        <?php } ?>
        
        <?php
        if (\Application::getUrlParams(0) !== null &&  \Application::isAuthorized(ucfirst(strtolower(\Application::getNameController())) , 'feriados', 'remover') )
        {?>
            <button type="button" class="mb-xs mt-xs mr-xs btn btn-grey" onclick="remover()"><i class="material-icons material-align-icons-button">&#xE92B;</i>&nbsp;Remover</button>
        <?php } ?>
        
         <button type="button" class="mb-xs mt-xs mr-xs btn btn-grey" onclick="document.location='/<?php echo strtolower(\Application::getNameController()); ?>/cadastrar-feriado/'"><i class="material-icons material-align-icons-button">&#xE145;</i>&nbsp;Novo</button>
    
    </div>
    
</section>

</form>



<script src="/library/jsvendor/bootstrap-datepicker/js/bootstrap-datepicker.js"></script>

<script>



function salvar()
{
    var id = $('#id').val();
    var descricao = $('#descricao').val();
    var data = $('#data').val();
    
   
    
    if ($.trim(descricao) == '' )
    {
        alert('Preencha o nome corretamente');
        return false;
    }
    
    if ($.trim(data) == '' || data.indexOf('/') < 0 )
    {
        alert('Preencha a data corretamente');
        return false;
    }
   
    $.ajax({
        type: "POST",
        url: '<?php echo '/'. strtolower(\Application::getNameController()) . '/salvar-feriado/'; ?>',
        cache: false,
        dataType: "json",
        data: 'id='+id + '&descricao=' + descricao + '&data=' + data,
        success: function(json){
            if (json.success)
             {
                 alert('Registro salvo com sucesso');
                 //$('#id').val(json.id);
                 document.location = '<?php echo '/'. strtolower(\Application::getNameController()) . '/cadastrar-feriado/'; ?>' + json.id;
             }
            else
            {
                alert('Não foi possível realizar a alteração\nMotivo: ' + json.message);
                document.location.reload();
            }
        }
    });
    
}
    
    
function remover()
{
    if (! confirm('Deseja realmente excluir este feriado? A informação será removida para sempre.'))
        return false;
     var id = $('#id').val();
    $.ajax({
        type: "POST",
        url: '<?php echo '/'. strtolower(\Application::getNameController()) . '/apagar-feriado/'; ?>',
        cache: false,
        dataType: "json",
        data: 'id='+id,
        success: function(json){
            if (json.success)
             {
                 alert('Registro excluído com sucesso');
                 //$('#id').val(json.id);
                 document.location = '<?php echo '/'. strtolower(\Application::getNameController()) . '/feriados/'; ?>';
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