<?php
$usuario = (isset($this->getParams('usuario')[0])) ? $this->getParams('usuario')[0] : null;
$perfilUsuario = $this->getParams('perfilusuario');
$listaPerfil = $this->getParams('listaperfil');

if (\Application::getUrlParams(0) !== null && $usuario === null)
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
            <h2 class="panel-title">Definir Perfil do Usuário</h2>
        </header>
        <div class="panel-body">
            <div class="row">
                <div class="col-md-9">
                            <div class="form-group">
                                <label class="col-sm-1 control-label">ID: </label>
                                <div class="col-sm-2">
                                    <input type="text" id="id" name="id"  class="form-control" disabled value="<?php  if (is_array($usuario) && count($usuario) > 0) echo $usuario['id']; ?>" >
                                </div>
                            </div>
                    </div>
            </div>
          
            <div class="row">
                <div class="col-md-9">
                            <div class="form-group">
                                <label class="col-sm-1 control-label">CPF: </label>
                                <div class="col-sm-4">
                                    <input type="text" id="cpf" name="cpf" disabled class="form-control" value="<?php  if (is_array($usuario) && count($usuario) > 0) echo $usuario['cpf']; ?>" >
                                </div>
                            </div>
                    </div>
            </div>
            
            <div class="row">
                <div class="col-md-9">
                            <div class="form-group">
                                <label class="col-sm-1 control-label">Nome: </label>
                                <div class="col-sm-6">
                                    <input type="text" disabled id="nome" name="nome" class="form-control" value="<?php  if (is_array($usuario) && count($usuario) > 0) echo $usuario['nome']; ?>" >
                                </div>
                            </div>
                    </div>
            </div>
            
            <hr />
            
           <div class="row">
               <div class="col-md-12">
                <i>Escolha abaixo os perfis de acesso que o usuário deverá ter e clique em salvar</i>
               </div>
            </div>
            
            <div class="row">
               <div class="col-md-12">
                    <select id='multiselect-custom-headers' multiple='multiple'>
                        <?php
                            if (is_array($listaPerfil))
                                foreach($listaPerfil as $i => $value)
                                {
                                    $selected = (array_search($value['id'], array_column($perfilUsuario, 'idPerfil')) !== false) ? 'selected="selected"' : '';
                                    echo '<option '. $selected . ' value="'. $value['id'] . '">'. $value['descricao'] . '</option>';
                                }
                        ?>
                    </select>
                </div>
            </div>
     
    </div>
    
    
    <div class="row botoes">
        <?php
        if (\Application::isAuthorized(ucfirst(strtolower(\Application::getNameController())) , 'usuarios', 'escrever') )
        {?>
        <button type="button" class="mb-xs mt-xs mr-xs btn btn-danger" onclick="salvar()"><i class="material-icons material-align-icons-button">&#xE877;</i>&nbsp;Salvar</button>
        <?php } ?>
        
      
      
    
    </div>
    
</section>

</form>

<script>
$.getCSS('/library/jsvendor/lou-multiselect/css/multi-select.dist.css');
    
$.getScript('/library/jsvendor/lou-multiselect/js/jquery.multi-select.js', function(){
    $('#multiselect-custom-headers').multiSelect({
          selectableHeader: "<div class='custom-header'>Perfis Disponíveis</div>",
          selectionHeader: "<div class='custom-header'>Perfis Atribuidos</div>",
          selectableFooter: "<div class='custom-header'></div>",
          selectionFooter: "<div class='custom-header'></div>",
            afterSelect: function(value){
                var valor = value[0];
                if (perfil.indexOf(valor) < 0)
                    perfil.push(valor);
                
              },
              afterDeselect: function(value){
                  var valor = value[0];
                for (var i in perfil)
                    if (perfil[i] == valor)
                        perfil.splice(i, 1);
                  
              }
        });
});
    


function salvar()
{
    var id = $('#id').val();
    if (id == '')
    {
        alert('Parâmetros incorretos. Contate o suporte');
        return false;
    }
       
    $.ajax({
        type: "POST",
        url: '/sistema/salvar-perfil-usuario',
        cache: false,
        dataType: "json",
        data: 'id='+id + '&perfil='+ JSON.stringify(perfil),
        success: function(json){
            if (json.success)
             {
                 alert('Registro salvo com sucesso');
                 //$('#id').val(json.id);
                 document.location = '/sistema/definir-perfil-usuario/' + id;
             }
            else
            {
                alert('Não foi possível realizar a alteração\nMotivo: ' + json.message);
                document.location.reload();
            }
        }
    });
    
}
    
    
    
perfil = [];
<?php
if (is_array($perfilUsuario))
    foreach($perfilUsuario as $i => $value)
        echo "perfil.push('". $value['idPerfil'] . "');";
?>

</script>