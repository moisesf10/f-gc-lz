function buscarCep()
{
    
    cep = $('#cep').val().replace('-','');
    if (cep != '')
        $.getJSON("//viacep.com.br/ws/"+ cep +"/json/?callback=?", function(dados) {

                if (!("erro" in dados)) {
                    //Atualiza os campos com os valores da consulta.
                    $("#rua").val(dados.logradouro);
                    $("#bairro").val(dados.bairro);
                    $("#cidade").val(dados.localidade);
                    
                    $('#uf option').each(function(index){
                       
                       if ($(this).val() == dados.uf)
                        {
                            $(this).attr('selected', true);
                           
                        }
                    });
                    //$("#uf").val(dados.uf);
                    //$("#ibge").val(dados.ibge)
                   
                } //end if.
                else {
                    alert('CEP não encontrado.')
                }
            });
}

function salvar()
{
    var id = $('#id').val();
    var cpf = $('#cpf').val().replace(/[_]/gi,'');
    var nome = $.trim($('#nome').val());
    var email = $.trim($('#email').val());
    var senha = $.trim($('#senha').val());
    var nascimento = $('#nascimento').val();
    var status = $('#status').val();
    var telefone = $('#telefone').val().replace(/[_]/gi,'');
    var celular = $('#celular').val().replace(/[_]/gi,'');
    var cep = $('#cep').val();
    var rua = $('#rua').val();
    var numeroResidencia = $('#numeroresidencia').val();
    var uf = $('#uf').val();
    var complemento = $('#complemento').val();
    var bairro = $('#bairro').val();
    var cidade = $('#cidade').val();
    var tipoConta = $('#tipoconta').val();
    var banco = $('#banco').val();
    var agencia = $('#agencia').val();
    var numeroConta = $('#numeroconta').val();
    
    if (cpf == '' || nome == '' || email == '' ||  nascimento == '' || status == '' || (senha == '' && id == '') )
    {
        alert('Cpf, Nome, E-mail, Data de Nascimento, Status e Senha são campos obrigatórios. OBS: Caso esteja atualizando um cadastro a senha deixa de ser obrigatória');
        return false;
    }
    

    
    $.ajax({
        type: "POST",
        url: '/sistema/salvar-usuario',
        cache: false,
        dataType: "json",
        data: 'id='+id + '&cpf='+ cpf + '&nome=' + nome +'&email=' + email + '&senha=' + senha + '&nascimento=' + nascimento + '&status='+ status + '&telefone=' + telefone + '&celular=' + celular + '&cep=' + cep + '&rua=' + rua + '&numeroResidencia=' + numeroResidencia + '&uf=' + uf + '&complemento=' + complemento + '&bairro=' + bairro + '&cidade=' + cidade + '&tipoConta=' + tipoConta + '&banco=' + banco + '&agencia=' + agencia + '&numeroConta=' + numeroConta,
        success: function(json){
            if (json.success)
             {
                 alert('Registro salvo com sucesso');
                 //$('#id').val(json.id);
                 document.location = '/sistema/cadastrar-usuario/' + json.id;
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
    if (! confirm('Deseja realmente excluir este usuario? A informação será removida para sempre.'))
        return false;
     var id = $('#id').val();
    $.ajax({
        type: "POST",
        url: '/sistema/apagar-usuario/',
        cache: false,
        dataType: "json",
        data: 'id='+id,
        success: function(json){
            if (json.success)
             {
                 alert('Registro excluído com sucesso');
                 //$('#id').val(json.id);
                 document.location = '/sistema/usuarios/';
             }
            else
            {
                alert('Não foi possível realizar a alteração\nMotivo: ' + json.message);
                //document.location.reload();
            }
        }
    });
    
}
    
    function desativar()
{
    if (! confirm('O cadastro do usuário será desativado!'))
        return false;
     var id = $('#id').val();
    $.ajax({
        type: "POST",
        url: '/sistema/desativar-usuario/',
        cache: false,
        dataType: "json",
        data: 'id='+id,
        success: function(json){
            if (json.success)
             {
                 alert('Registro desativado com sucesso');
                 //$('#id').val(json.id);
                 document.location = '/sistema/cadastrar-usuario/' + json.id;
             }
            else
            {
                alert('Não foi possível realizar a alteração\nMotivo: ' + json.message);
                //document.location.reload();
            }
        }
    });
    
}