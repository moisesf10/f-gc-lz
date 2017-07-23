<?php
$tabela = (isset($this->getParams('tabela')[0])) ? $this->getParams('tabela')[0] : null;
$convenios = $this->getParams('convenios');
$bancos = $this->getParams('bancos');

if (\Application::getUrlParams(0) !== null && $tabela === null)
    \Application::print404();
?>
<style>
    section.panel div.panel-body div.row {padding-bottom: 1rem;}
    div.botoes {margin-left: 0.2rem;}
    div.botoes div {padding-right: 1rem; }
</style>

<section class="panel">
        <header class="panel-heading">
            <h2 class="panel-title">Cadastro de Tabela</h2>
        </header>
        <div class="panel-body">
            <div class="row">
                <div class="col-md-4">
                            <div class="form-group">
                                <label class="col-sm-4 control-label">ID: </label>
                                <div class="col-sm-6">
                                    <input type="text" id="id" name="id"  class="form-control" disabled value="<?php  if (is_array($tabela) && count($tabela) > 0) echo $tabela['idTabela']; ?>" >
                                </div>
                            </div>
                    </div>
            </div>
            <div class="row">
                <div class="col-md-4">
                            <div class="form-group">
                                <label class="col-sm-4 control-label">Banco: </label>
                                <div class="col-sm-6">
                                    <select class="form-control" id="banco">
                                        <option></option>
                                        <?php
                                            if (is_array($bancos))
                                                foreach($bancos as $i => $value)
                                                {
                                                    $selected = ($value['id'] == $tabela['idBanco']) ? 'SELECTED="selected"' : '';
                                                    
                                                ?>
                                                    <option <?php echo $selected; ?> value="<?php echo $value['id']; ?>"><?php echo $value['nome']; ?></option>
                                           
                                        
                                            <?php }     ?>
                                    </select>
                                </div>
                            </div>
                    </div>
            </div>
            <div class="row">
                <div class="col-md-4">
                            <div class="form-group">
                                <label class="col-sm-4 control-label">Convênio: </label>
                                <div class="col-sm-6">
                                    <select class="form-control" id="convenio">
                                        <option></option>
                                        <?php
                                            if (is_array($convenios))
                                                foreach($convenios as $i => $value)
                                                {
                                                    $selected = ($value['id'] == $tabela['idConvenio']) ? 'SELECTED="selected"' : '';
                                                    
                                                ?>
                                                    <option <?php echo $selected; ?> value="<?php echo $value['id']; ?>"><?php echo $value['nome']; ?></option>
                                           
                                        
                                            <?php }     ?>
                                    </select>
                                </div>
                            </div>
                    </div>
            </div>
            <div class="row">
                <div class="col-md-4">
                            <div class="form-group">
                                <label class="col-sm-4 control-label">Nome Tabela: </label>
                                <div class="col-sm-6">
                                    <input type="text" id="nome" name="nome" class="form-control" value="<?php  if (is_array($tabela) && count($tabela) > 0) echo $tabela['nomeTabela']; ?>" >
                                </div>
                            </div>
                    </div>
            </div>
     
    </div>
    
    
    <div class="row botoes">
        <?php
        if (\Application::isAuthorized(ucfirst(strtolower(\Application::getNameController())) , 'grupos', 'escrever') )
        {?>
        <button type="button" class="mb-xs mt-xs mr-xs btn btn-danger" onclick="salvar()"><i class="material-icons material-align-icons-button">&#xE877;</i>&nbsp;Salvar</button>
        <?php } ?>
        
        <?php
        if (\Application::getUrlParams(0) !== null &&  \Application::isAuthorized(ucfirst(strtolower(\Application::getNameController())) , 'tabelas', 'remover') )
        {?>
            <button type="button" class="mb-xs mt-xs mr-xs btn btn-grey" onclick="remover()"><i class="material-icons material-align-icons-button"></i>&nbsp;Remover</button>
        <?php } ?>
        
      
        <button type="button" class="mb-xs mt-xs mr-xs btn btn-grey" onclick="document.location='/<?php echo strtolower(\Application::getNameController()); ?>/cadastrar-tabela/'"><i class="material-icons material-align-icons-button">&#xE145;</i>&nbsp;Novo</button>
        
    
    </div>
    
</section>

<script>



function salvar()
{
    var id = $('#id').val();
    var banco = $('#banco').val();
    var convenio = $('#convenio').val();
    var nome = $.trim($('#nome').val());
    
   if (banco == '' || convenio == '' || nome == '')
    {
        alert('Todos os campos são de preenchimento obrigatório');
        return false;
    }
    

    
    $.ajax({
        type: "POST",
        url: '<?php echo '/'. strtolower(\Application::getNameController()) . '/salvar-tabela/'; ?>',
        cache: false,
        dataType: "json",
        data: 'id='+id + '&banco=' + banco + '&convenio=' + convenio + '&nome=' + nome,
        success: function(json){
            if (json.success)
             {
                 alert('Registro salvo com sucesso');
                 //$('#id').val(json.id);
                 document.location = '<?php echo '/'. strtolower(\Application::getNameController()) . '/cadastrar-tabela/'; ?>' + json.id;
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
    if (! confirm('Deseja realmente excluir esta tabela? A informação será removida para sempre.'))
        return false;
     var id = $('#id').val();
    $.ajax({
        type: "POST",
        url: '<?php echo '/'. strtolower(\Application::getNameController()) . '/apagar-tabela/'; ?>',
        cache: false,
        dataType: "json",
        data: 'id='+id,
        success: function(json){
            if (json.success)
             {
                 alert('Registro excluído com sucesso');
                 //$('#id').val(json.id);
                 document.location = '<?php echo '/'. strtolower(\Application::getNameController()) . '/tabelas/'; ?>';
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