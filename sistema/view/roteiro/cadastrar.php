<?php
$bancos = $this->getParams('bancos');
$entidades = $this->getParams('entidades');
$roteiro = (isset($this->getParams('roteiro')[0])) ? $this->getParams('roteiro')[0] : null;


if (\Application::getUrlParams(0) !== null && $roteiro === null)
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
            <h2 class="panel-title">Roteiro</h2>
        </header>
        <div class="panel-body">
            <div class="row">
                <div class="col-md-4">
                            <div class="form-group">
                                <label class="col-sm-4 control-label">ID: </label>
                                <div class="col-sm-6">
                                    <input type="text" id="id" name="id"  class="form-control" disabled value="<?php  if (is_array($roteiro) && count($roteiro) > 0) echo $roteiro['id']; ?>" >
                                </div>
                            </div>
                    </div>
            </div>
            <div class="row">
                <div class="col-md-4">
                            <div class="form-group">
                                <label class="col-sm-4 control-label">Banco: </label>
                                <div class="col-sm-6">
                                    <select name="banco" id="banco" class="form-control mb-md">
                                        <option></option>
                                        <?php 
                                        
                                        if (is_array($bancos))
                                            foreach($bancos as $i => $value)
                                            {
                                                if (is_array($roteiro) && count($roteiro) > 0)
                                                {
                                                    if ($roteiro['idBanco'] == $value['id'] )
                                                        $selected = 'selected="selected"';
                                                    else
                                                        $selected = '';
                                                }else
                                                    $selected = '';
                                                
                                                echo '<option value="'. $value['id'] .'" '. $selected .'>'. $value['codigo']. ' - '. $value['nome'] .'</option>';
                                            }
                                                
                                    ?>
                                    </select>
                                </div>
                            </div>
                    </div>
            </div>
            <div class="row">
                <div class="col-md-4">
                            <div class="form-group">
                                <label class="col-sm-4 control-label">Entidade: </label>
                                <div class="col-sm-6">
                                    <select name="entidade" id="entidade" class="form-control mb-md">
                                        <option></option>
                                        <?php 
                                        if (is_array($entidades))
                                            foreach($entidades as $i => $value)
                                            {
                                                 if (is_array($roteiro) && count($roteiro) > 0)
                                                {
                                                    if ($roteiro['idEntidade'] == $value['id'] )
                                                        $selected = 'selected="selected"';
                                                    else
                                                        $selected = '';
                                                }else
                                                    $selected = '';
                                                
                                                 echo '<option value="'. $value['id'] .'" '. $selected .'>'. $value['nome'] .'</option>';
                                            }
                                               
                                        ?>
                                    </select>
                                </div>
                            </div>
                    </div>
            </div>
    </div>
     <textarea name="descricao" id="descricao" rows="20" cols="80"><?php 
        if (is_array($roteiro) && count($roteiro) > 0)
            echo $roteiro['descricao'];
    ?></textarea>
    
    <div class="row botoes">
        <?php
        if (\Application::isAuthorized(ucfirst(strtolower(\Application::getNameController())) , 'roteiros', 'escrever') )
        {?>
        <button type="button" class="mb-xs mt-xs mr-xs btn btn-danger" onclick="salvar()"><i class="material-icons material-align-icons-button">&#xE877;</i>&nbsp;Salvar</button>
        <?php } ?>
        
        <?php
        if (\Application::getUrlParams(0) !== null &&  \Application::isAuthorized(ucfirst(strtolower(\Application::getNameController())) , 'roteiros', 'remover') )
        {?>
            <button type="button" class="mb-xs mt-xs mr-xs btn btn-grey" onclick="remover()"><i class="material-icons material-align-icons-button"></i>&nbsp;Remover</button>
        <?php } ?>
    
    </div>
    
</section>

</form>


<script>
$.ajaxSetup({
  cache: true
});    

window.CKEDITOR_BASEPATH = '/library/jsvendor/ckeditor/';
$.getScript( "/library/jsvendor/ckeditor/ckeditor.js", function(  ) {
    CKEDITOR.config.language = "pt-br";   
    CKEDITOR.replace( 'descricao',{
     toolbarGroups: [
                { name: 'document', groups: [ 'mode', 'document', 'doctools' ] },
                { name: 'clipboard', groups: [ 'clipboard', 'undo' ] },
                { name: 'editing', groups: [ 'find', 'selection', 'spellchecker', 'editing' ] },
                { name: 'forms', groups: [ 'forms' ] },
                '/',
                { name: 'basicstyles', groups: [ 'basicstyles', 'cleanup' ] },
                { name: 'paragraph', groups: [ 'list', 'indent', 'blocks', 'align', 'bidi', 'paragraph' ] },
                { name: 'links', groups: [ 'links' ] },
                { name: 'insert', groups: [ 'insert' ] },
                '/',
                { name: 'styles', groups: [ 'styles' ] },
                { name: 'colors', groups: [ 'colors' ] },
                { name: 'tools', groups: [ 'tools' ] },
                { name: 'others', groups: [ 'others' ] },
                { name: 'about', groups: [ 'about' ] }
            ],
        removeButtons : 'Source,Save,NewPage,Form,Checkbox,Radio,TextField,Textarea,Select,Button,ImageButton,HiddenField,CreateDiv,Anchor,Image,Flash,Table,HorizontalRule,Smiley,SpecialChar,PageBreak,Iframe,ShowBlocks,About',

     } );
})


function salvar()
{
    var id = $('#id').val();
    var banco = $('#banco').val();
    var entidade = $('#entidade').val();
    
    var descricao = CKEDITOR.instances.descricao.getData();
    
    if (banco == '' || entidade == '' )
    {
        alert('Preencha todos os campos');
        return false;
    }
    
    $.ajax({
        type: "POST",
        url: '<?php echo '/'. strtolower(\Application::getNameController()) . '/salvar-roteiro/'; ?>',
        cache: false,
        dataType: "json",
        data: 'id='+id + '&banco=' + banco + '&entidade=' + entidade + '&descricao='+ encodeURIComponent(descricao),
        success: function(json){
            if (json.success)
             {
                 alert('Registro salvo com sucesso');
                 //$('#id').val(json.id);
                 document.location = '<?php echo '/'. strtolower(\Application::getNameController()) . '/cadastrar-roteiro/'; ?>' + json.id;
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
    if (! confirm('Deseja realmente excluir este roteiro? A informação será removida para sempre.'))
        return false;
     var id = $('#id').val();
    $.ajax({
        type: "POST",
        url: '<?php echo '/'. strtolower(\Application::getNameController()) . '/apagar-roteiro/'; ?>',
        cache: false,
        dataType: "json",
        data: 'id='+id,
        success: function(json){
            if (json.success)
             {
                 alert('Registro salvo com sucesso');
                 //$('#id').val(json.id);
                 document.location = '<?php echo '/'. strtolower(\Application::getNameController()) . '/listar-roteiro/'; ?>';
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