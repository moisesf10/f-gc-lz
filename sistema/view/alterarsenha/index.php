

<section class="panel">
    <header class="panel-heading">
        <h2 class="panel-title">Alterar Senha</h2>
    </header>
    <div class="panel-body">
        <div class="row">
            <label class="col-md-1">Senha Atual</label>
            <div class="col-md-2">
                <input type="password" class="form-control" id="senhaatual">
            </div>
        </div>
    </div>
    <div class="panel-body">
        <div class="row">
            <label class="col-md-1">Senha Nova</label>
            <div class="col-md-2">
                <input type="password" class="form-control" id="senhanova">
            </div>
        </div>
    </div>
    <div class="panel-body">
        <div class="row">
            <label class="col-md-1">Repetir Senha Nova</label>
            <div class="col-md-2">
                <input type="password" class="form-control" id="repetirsenha">
            </div>
        </div>
    </div>
    <div class="panel-body">
        <button type="button" class="mb-xs mt-xs mr-xs btn btn-danger" onclick="salvar()"><i class="material-icons material-align-icons-button">&#xE877;</i>&nbsp;Salvar</button>
    </div>
</section>


<script>
function salvar()
{
    var senhaAtual = $('#senhaatual').val();
    var senhaNova = $('#senhanova').val();
    var repetirSenha = $('#repetirsenha').val();
    
    if (senhaNova != repetirSenha)
        {
            alert('A senha nova não confere com sua repetição');
            return false;
        }
    
    $.ajax({
        type: "POST",
        url: '<?php echo '/'. strtolower(\Application::getNameController()) . '/salvar-alteracao-senha'; ?>',
        cache: false,
        dataType: "json",
        data: '&senhaatual='+senhaAtual + '&senhanova='+ senhaNova,
        success: function(json){
            if (json.success)
             {
                 alert('Senha alterada com sucesso');
                 
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