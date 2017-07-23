<?php
$usuarios = $this->getParams('usuarios');
$meta = (isset($this->getParams('meta')[0])) ? $this->getParams('meta')[0] : null;
$grupos = $this->getParams('grupos');
//echo '<pre>'; var_dump($meta); echo '</pre>';
?>

<style>
    .select2-container .select2-choice {height: inherit;}
</style>

<section class="panel">
    <header class="panel-heading">
        <h2 class="panel-title">Cadastro de Metas</h2>
    </header>
    <div class="panel-body">
        <div class="form-group">
            <label class="col-md-3 control-label">Nome do Vendedor/Grupo</label>
            <div class="col-md-3">
                <select data-plugin-selectTwo class="form-control " id="usuario" name="usuario" tabindex="1">
                    <optgroup label="Selecione o nome do vendedor">
                        <option></option>
                        <?php
                            if (is_array($usuarios))
                                foreach($usuarios as $i => $value)
                                {
                                    $selected = (isset($meta['idUsuario']) && $meta['idUsuario'] == $value['id']  ) ? 'selected="selected"' : '';
                                    echo '<option '. $selected .' value="Vendedor;'. $value['id'] . '">VENDEDOR: '. $value['nome'] . '</option>';
                                }
                            if (is_array($grupos))
                                foreach($grupos as $i => $value)
                                {
                                    $selected = (isset($meta['idGrupo']) && $meta['idGrupo'] == $value['id']  ) ? 'selected="selected"' : '';
                                    echo '<option '. $selected .' value="Grupo;'. $value['id'] . '">GRUPO: '. $value['nome'] . '</option>';
                                }
                        ?>

                    </optgroup>

                </select>
            </div>
        </div>
    </div>
    <div class="panel-body">
        <div class="form-group">
            <label class="col-md-3 control-label">Tipo da Meta</label>
            <div class="col-md-3">
                <select data-plugin-selectTwo class="form-control " id="tipometa" name="tipometa" tabindex="2">
                    <optgroup label="Selecione o nome do vendedor">
                        <option value="Mensal" <?php echo (isset($meta['tipoMeta']) && $meta['tipoMeta'] == 'Mensal') ? 'selected="selected"' : ''; ?>>Mensal</option>
                       <option disabled value="Semanal" <?php echo (isset($meta['tipoMeta']) && $meta['tipoMeta'] == 'Semanal') ? 'selected="selected"' : ''; ?>>Semanal</option>
                        <option disabled value="Diaria" <?php echo (isset($meta['tipoMeta']) && $meta['tipoMeta'] == 'Diaria') ? 'selected="selected"' : ''; ?>>Diaria</option>
                    </optgroup>

                </select>
            </div>
        </div>
    </div>
    <div class="panel-body">
        <div class="form-group">
            <label class="col-md-3 control-label">Data Inicio</label>
            <div class="col-md-3">
                <input type="text" id="datainicio" class="form-control" tabindex="3" data-plugin-datepicker value="<?php if(isset($meta['dtInicio'])) echo $meta['dtInicio']; ?>">
            </div>
        </div>
    </div>
    <div class="panel-body">
        <div class="form-group">
            <label class="col-md-3 control-label">Data Prazo Limite</label>
            <div class="col-md-3">
                <input type="text" id="data" class="form-control" tabindex="4" data-plugin-datepicker value="<?php if(isset($meta['prazo'])) echo $meta['prazo']; ?>">
            </div>
        </div>
    </div>
    
    <div class="panel-body">
        <div class="form-group">
            <label class="col-md-3 control-label">Valor da Meta</label>
            <div class="col-md-3">
                <input type="text" id="meta" class="form-control" tabindex="5" value="<?php if(isset($meta['valor'])) echo $meta['valor']; ?>">
            </div>
        </div>
    </div>
    
    <div class="panel-body">
        <div class="form-group">
            <label class="col-md-3 control-label">Valor Incremental</label>
            <div class="col-md-3">
                <input type="text" id="incremento" class="form-control" tabindex="5" value="<?php if(isset($meta['valorIncremento'])) echo $meta['valorIncremento']; ?>">
            </div>
        </div>
    </div>
    
     <div class="panel-body">
         <?php
        if (\Application::isAuthorized(ucfirst(strtolower(\Application::getNameController())) , 'cadastrar_metas', 'escrever') )
        {?>
        <button type="button" class="mb-xs mt-xs mr-xs btn btn-danger" onclick="salvar()"><i class="material-icons material-align-icons-button">&#xE877;</i>&nbsp;Salvar</button>
        <?php } ?>
        
        <?php
        if (\Application::getUrlParams(0) !== null &&  \Application::isAuthorized(ucfirst(strtolower(\Application::getNameController())) , 'cadastrar_metas', 'remover') )
        {?>
            <button type="button" class="mb-xs mt-xs mr-xs btn btn-grey" onclick="remover()"><i class="material-icons material-align-icons-button">&#xE92B;</i>&nbsp;Remover</button>
        <?php } ?>
        
         <button type="button" class="mb-xs mt-xs mr-xs btn btn-grey" onclick="document.location = '/administracao/cadastrar-meta'"><i class="material-icons material-align-icons-button">&#xE145;</i>&nbsp;Novo</button>
    </div>
    
    
</section>


<script src="/library/jsvendor/select2/select2.min.js"></script>
<script src="/library/jsvendor/bootstrap-datepicker/js/bootstrap-datepicker.js"></script>

<script>
$.getCSS('/library/jsvendor/select2/select2.css');
$.getCSS('/library/jsvendor/select2/select2.custom.css');
    $.getScript('/library/jsvendor/jquery-maskmoney/dist/jquery.maskMoney.min.js', function(){
     $('#meta').maskMoney({prefix:'R$ ', allowNegative: false, thousands:'.', decimal:',', affixesStay: true}).focus()   ;
        $('#incremento').maskMoney({prefix:'R$ ', allowNegative: false, allowZero: true, thousands:'.', decimal:',', affixesStay: true}).focus()   ;
});
    
    
function salvar()
{
    var usuario = $('#usuario').val();
    var dtInicio = $('#datainicio').val();
    var tipoMeta = $('#tipometa').val();
    var prazo = $('#data').val();
    var meta = $('#meta').maskMoney('unmasked')[0];
    var incremento = ($('#incremento').maskMoney('unmasked')[0] == '') ? 0 : $('#incremento').maskMoney('unmasked')[0];
    
 
    if (usuario == '' || prazo == '' || meta == '' || dtInicio == '' || tipoMeta == '')
    {
        alert('Preencha todos os campos');
        return false;
    }
    var id = '';
    if (typeof key != 'undefined')
        var id = key;
   
    
    
    $.ajax({
        type: "POST",
        url:  '/administracao/salvar-meta/',
        cache: false,
        dataType: "json",
        data: '&usuario='+usuario+'&datainicio='+ dtInicio + '&tipometa=' + tipoMeta +'&prazo='+prazo+'&meta='+meta+'&incremento='+incremento+'&id='+id,
        success: function(json){
            if (json.success)
             {
                 alert('Registro salvo com sucesso');
                 if (json.success == true)
                    document.location = '/administracao/cadastrar-meta/' + json.id;
             }
            else
            {
                alert('Não foi possível realizar a alteração\nMotivo: ' + json.message);
                //document.location.reload();
            }
        }
    });
    
    
}
    
<?php
if (is_array($meta))
    echo 'key=' . $meta['id']. ';';
?>
    
function remover()
{
    if (! confirm('Deseja realmente excluir esta meta? A informação será removida para sempre.'))
        return false;

    if (typeof key == undefined)
        return false;
    $.ajax({
        type: "POST",
        url: '<?php echo '/'. strtolower(\Application::getNameController()) . '/apagar-meta/'; ?>',
        cache: false,
        dataType: "json",
        data: 'id='+key,
        success: function(json){
            if (json.success)
             {
                 alert('Registro excluído com sucesso');
                 //$('#id').val(json.id);
                 document.location = '<?php echo '/'. strtolower(\Application::getNameController()) . '/listar-metas/'; ?>';
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