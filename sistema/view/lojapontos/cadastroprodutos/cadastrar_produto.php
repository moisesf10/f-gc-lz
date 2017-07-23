<?php
$produto =  $this->getParams('produto');


if (\Application::getUrlParams(0) !== null && $produto === null)
    \Application::print404();
?>
<style>
    section.panel div.panel-body div.row {padding-bottom: 1rem;}
    div.botoes {margin-left: 0.2rem;}
    div.botoes div {padding-right: 1rem; }
    #descricao {height: 20rem;}
  
</style>
<form>
<section class="panel">
        <header class="panel-heading">
            <h2 class="panel-title">Cadastro de Produtos</h2>
        </header>
        <div class="panel-body">
            <div class="row">
                <div class="col-md-6">
                            <div class="form-group">
                                <label class="col-sm-2 control-label">ID: </label>
                                <div class="col-sm-6">
                                    <input type="text" id="id" name="id"  class="form-control" disabled value="<?php  if (isset($produto['id'])) echo $produto['id']; ?>" >
                                </div>
                            </div>
                    </div>
            </div>
          
            <div class="row">
                <div class="col-md-6">
                            <div class="form-group">
                                <label class="col-sm-2 control-label">Nome: </label>
                                <div class="col-sm-10">
                                    <input type="text" id="nome" name="nome" class="form-control" value="<?php  if (isset($produto['nome'])) echo $produto['nome']; ?>" >
                                </div>
                            </div>
                    </div>
            </div>
            
            <div class="row">
                <div class="col-md-6">
                            <div class="form-group">
                                <label class="col-sm-2 control-label">Link da Imagem: </label>
                                <div class="col-sm-10">
                                    <input type="text" id="link" name="link" class="form-control" value="<?php  if (isset($produto['link'])) echo $produto['link']; ?>" >
                                </div>
                            </div>
                    </div>
            </div>
            
            <div class="row">
                <div class="col-md-6">
                            <div class="form-group">
                                <label class="col-sm-2 control-label">Pontos Necessários: </label>
                                <div class="col-sm-6">
                                    <input type="text" id="pontos" name="pontos" class="form-control" value="<?php  if (isset($produto['pontos'])) echo $produto['pontos']; ?>" >
                                </div>
                            </div>
                    </div>
            </div>
            
            <div class="row">
                <div class="col-md-6">
                            <div class="form-group">
                                <label class="col-sm-2 control-label">Disponível Entre: </label>
                                <div class="col-sm-6">
                                    <div class="input-daterange input-group" data-plugin-datepicker>
                                        <span class="input-group-addon">
                                            <i class="fa fa-calendar"></i>
                                        </span>
                                        <input type="text" class="form-control" name="datainicial" id="datainicial" value="<?php  if (isset($produto['inicioValidade'])) echo $produto['inicioValidade']; ?>">
                                        <span class="input-group-addon">Até</span>
                                        <input type="text" class="form-control" name="datafinal" id="datafinal" value="<?php  if (isset($produto['fimValidade'])) echo $produto['fimValidade']; ?>">
                                    </div>
                                </div>
                            </div>
                    </div>
            </div>
            
            <div class="row">
                <div class="col-md-6">
                            <div class="form-group">
                                <label class="col-sm-2 control-label">Descrição: </label>
                                <div class="col-sm-10">
                                    <textarea id="descricao" name="descricao" class="form-control"><?php  if (isset($produto['descricao'])) echo $produto['descricao']; ?></textarea>
                                </div>
                            </div>
                    </div>
            </div>
     
    </div>
    
    
    <div class="row botoes">
        <?php
        if (\Application::isAuthorized('Troca de Pontos' , 'loja_pontos_cadastrar_produtos', 'escrever') )
        {?>
        <button type="button" class="mb-xs mt-xs mr-xs btn btn-danger" onclick="salvar()"><i class="material-icons material-align-icons-button">&#xE877;</i>&nbsp;Salvar</button>
        <?php } ?>
        
        <?php
        if (\Application::getUrlParams(0) !== null &&  \Application::isAuthorized('Troca de Pontos' , 'loja_pontos_cadastrar_produtos', 'remover') )
        {?>
            <button type="button" class="mb-xs mt-xs mr-xs btn btn-grey" onclick="remover()"><i class="material-icons material-align-icons-button">&#xE92B;</i>&nbsp;Remover</button>
        <?php } ?>
        
         <button type="button" class="mb-xs mt-xs mr-xs btn btn-grey" onclick="document.location = '/administracao/cadastrar-banco'"><i class="material-icons material-align-icons-button">&#xE145;</i>&nbsp;Novo</button>
        
      
    
    </div>
    
</section>

</form>



<script src="/library/jsvendor/bootstrap-datepicker/js/bootstrap-datepicker.js"></script>

<script>

$.getScript('/library/jsvendor/jquery-masked-input/jquery.maskedinput.min.js', function(){
    $('#pontos').mask('999999999',{placeholder:"", autoclear: false});
});

function salvar()
{
    var id = $('#id').val();
    var nome = $('#nome').val();
    var link = $('#link').val();
    var pontos = $('#pontos').val();
    var dataInicial = $('#datainicial').val();
    var dataFinal = $('#datafinal').val();
    var descricao = $('#descricao').val();
    
    var error = '';
    
    if (nome == '' )
        error += '- Nome\n';
    if (nome == '' )
        error += '- Link da Imagem\n';
    if (nome == '' )
       error += '- Pontos Necessários\n';
    if (nome == '' )
       error += '- Descrição\n';
    
    
    if (error != '')
    {
        alert('Os seguintes campos são obrigatórios\n\n' + error);
        return false;
    }
   
    
    
    $.ajax({
        type: "POST",
        url: '<?php echo '/'. strtolower(\Application::getNameController()) . '/salvar-produto'; ?>',
        cache: false,
        dataType: "json",
        data: 'id='+id + '&nome='+ nome + '&link=' + encodeURIComponent(link) + '&pontos='+ pontos +'&datainicial=' + dataInicial + '&datafinal=' + dataFinal + '&descricao=' + encodeURIComponent(descricao),
        success: function(json){
            if (json.success)
             {
                 alert('Registro salvo com sucesso');
                 //$('#id').val(json.id);
                 document.location = '<?php echo '/'. strtolower(\Application::getNameController()) . '/cadastrar-produto/'; ?>' + json.id;
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
        url: '<?php echo '/'. strtolower(\Application::getNameController()) . '/apagar-produto/'; ?>',
        cache: false,
        dataType: "json",
        data: 'id='+id,
        success: function(json){
            if (json.success)
             {
                 alert('Registro excluído com sucesso');
                 //$('#id').val(json.id);
                 document.location = '<?php echo '/'. strtolower(\Application::getNameController()) . '/produtos/'; ?>';
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