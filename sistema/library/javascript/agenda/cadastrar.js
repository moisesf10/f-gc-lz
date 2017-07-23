

function buscarCliente()
{
    var cpf = $('#cpf').val().replace(/[_]/gi,'');
    if (cpf.replace(/[^0-9]gi/,'') == '')
    {
        alert('Nenhum cliente encontrado');
        return false;
    }
    $.ajax({
        type: "POST",
        url: '/contratos/buscar-cliente/',
        cache: false,
        dataType: "json",
       cache: true,
        data: 'cpf='+cpf,
        success: function(json){
            if (json.cliente.length > 0)
            {
                
                $('#nome').val(json.cliente[0].dados.nomeCliente);
                $('#cep').val(json.cliente[0].dados.cep);
                $('#rua').val(json.cliente[0].dados.rua);
                $('#numero').val(json.cliente[0].dados.numeroRua);
                $('#complemento').val(json.cliente[0].dados.complemento);
                $('#bairro').val(json.cliente[0].dados.bairro);
                $('#uf').val(json.cliente[0].dados.uf);
                $('#cidade').val(json.cliente[0].dados.cidade);
                $('#nascimento').val(json.cliente[0].dados.nascimento);
                
                if (json.cliente[0].telefones.length > 0)
                {
                    
                    table.clear().draw();
                    for (var i in json.cliente[0].telefones)
                    {
                        var dataUuid = 'telefone-'+ new Date().getTime();
                         table.row.add( [
                            '<input type="text" class="form-control" data-Uuid="'+ dataUuid  +'" value="'+ json.cliente[0].telefones[i].numero  +'" />',
                            '<input type="text" class="form-control"  value="'+ json.cliente[0].telefones[i].referencia  +'" />',
                            '<button type="button" class="mb-xs mt-xs mr-xs btn btn-warning" onclick="table.row( $(this).parents(\'tr\') ).remove().draw()">Remover</button>'

                        ] ).draw( false );
                        $('#datatable [data-uuid="'+  dataUuid +'"]').mask('(99)9999-99999?',{placeholder:"(__)____-_____", autoclear: false});
                    }
                   
                }
            
            }
            else
                alert('Cliente não encontrado');
        }
        
    });
}

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
    request.cpf =  $('#cpf').val().replace(/[_]/gi,'');
    request.nome = $('#nome').val();
    request.dataNascimento = $('#nascimento').val();
    request.dataLigacao = $('#dataligacao').val();
    request.horaLigacao = ($.trim($('#horaligacao').val()) != '') ? $('#horaligacao').val() +':00' : '';
    request.cep = $('#cep').val();
    request.rua = $('#rua').val();
    request.numero = $('#numero').val();
    request.complemento = $('#complemento').val();
    request.bairro = $('#bairro').val();
    request.cidade = $('#cidade').val();
    request.uf = $('#uf').val();
    request.tipoCliente = $('#tipocliente').val();
    request.convenio = $('#convenio').val();
    request.email = $('#email').val();
    request.status = $('#status').val();
    
    var observacoes = encodeURIComponent($('#observacoes').val());
    
    
    request.telefones = [];
    
    $('#datatable tbody tr').each(function(){
        var numero = ($(this).find('td input').eq(0).val() != undefined) ? $(this).find('td input').eq(0).val() : '';
        var referencia = ($(this).find('td input').eq(1).val() != undefined) ? $(this).find('td input').eq(1).val() : '';
        var obj = {'numero': numero.replace(/[_]/gi,''), 'referencia': referencia};
        if ( obj.numero != '' )
        {
            for (var i in request.telefones)
                if (request.telefones[i].numero == obj.numero)
                    error.push({'codigo': 'Telefone Duplicado', 'message': 'O telefone ' + $(this).find('td input').eq(0).val() + ' está duplicado.'});   
            
            request.telefones.push(obj);
        }
        
    });
    
   // console.log(JSON.stringify(request)); return false;
    
    // console.log(request); return false;
    if ($.trim(request.cpf) == '')
        error.push({'code': 'Campo Vazio', 'message': 'O CPF é obrigatório\n'});
    if ($.trim(request.nome) == '')
        error.push({'code': 'Campo Vazio', 'message': 'O Nome do cliente é obrigatório\n'});
    if ($.trim(request.dataLigacao) == '')
        error.push({'code': 'Campo Vazio', 'message': 'A data de ligação é obrigatória\n'});
    if ($.trim(request.horaLigacao) == '')
        error.push({'code': 'Campo Vazio', 'message': 'A hora de ligação é obrigatória\n'});
    
    if ($.trim(request.status) == '')
        error.push({'code': 'Campo Vazio', 'message': 'O status da ligação é obrigatório\n'});
    
    if (request.telefones.length < 1)
        error.push({'code': 'Campo Vazio', 'message': 'É necessário informar no mínimo 1 telefone\n'});
    
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
        url:  '/cliente/salvar-agenda/',
        cache: false,
        dataType: "json",
        data: 'dados='+ JSON.stringify(request) + '&observacoes='+ observacoes,
        success: function(json){
            if (json.success)
             {
                 alert('Registro salvo com sucesso');
                 if (json.success == true)
                    document.location = '/cliente/cadastrar-agenda/' + json.id;
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
    if (! confirm('Deseja realmente excluir esta agenda? A informação será removida para sempre.'))
        return false;
    
    var id = false;
    if (typeof key !== 'undefined')
        id = key;
     
    $.ajax({
        type: "POST",
        url: '/cliente/apagar-agenda/',
        cache: false,
        dataType: "json",
        data: 'id='+id,
        success: function(json){
            if (json.success)
             {
                 alert('Registro excluído com sucesso');
                 //$('#id').val(json.id);
                 document.location = '/cliente/listar-agenda/';
             }
            else
            {
                alert('Não foi possível realizar a alteração\nMotivo: ' + json.message);
                document.location.reload();
            }
        }
    });
    
}


