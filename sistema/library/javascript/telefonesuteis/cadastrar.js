

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
    var request = {};
    var error = [];
    request.nome = $('#nome').val();
    request.contato = $('#contato').val();
    request.telefone1 = $('#telefone1').val().replace(/[_]/gi,'');
    request.telefone2 = $('#telefone2').val().replace(/[_]/gi,'');
    request.email = $('#email').val();
    request.cep = $('#cep').val();
    request.rua = $('#rua').val();
    request.numero = $('#numero').val();
    request.complemento = $('#complemento').val();
    request.bairro = $('#bairro').val();
    request.cidade = $('#cidade').val();
    request.uf = $('#uf').val();
    request.site = $('#site').val();
    
    var observacao = encodeURIComponent($('#observacao').val());
    
    

    

    if ($.trim(request.nome) == '')
        error.push({'code': 'Campo Vazio', 'message': 'O Nome do cliente é obrigatório\n'});

    if (error.length > 0)
    {
        var message = '';
        for (var i in error)
            message += error[i].message;
        
        alert(message);
        return false;
    }
    
    if (typeof key !== 'undefined')
        request.id = key;
    
    
    
   
    
    
    $.ajax({
        type: "POST",
        url:  '/cadastrosbasicos/salvar-telefone-util/',
        cache: false,
        dataType: "json",
        data: 'dados='+ JSON.stringify(request) + '&observacao=' + observacao,
        success: function(json){
            if (json.success)
             {
                 alert('Registro salvo com sucesso');
                 if (json.success == true)
                    document.location = '/cadastrosbasicos/cadastrar-telefone-util/' + json.id;
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
    if (! confirm('Deseja realmente excluir este cadastro? A informação será removida para sempre.'))
        return false;
    
    var id = false;
    if (typeof key !== 'undefined')
        id = key;
     
    $.ajax({
        type: "POST",
        url: '/cadastrosbasicos/apagar-telefone-util/',
        cache: false,
        dataType: "json",
        data: 'id='+id,
        success: function(json){
            if (json.success)
             {
                 alert('Registro excluído com sucesso');
                 //$('#id').val(json.id);
                 document.location = '/cadastrosbasicos/telefones-uteis/';
             }
            else
            {
                alert('Não foi possível realizar a alteração\nMotivo: ' + json.message);
                document.location.reload();
            }
        }
    });
    
}


