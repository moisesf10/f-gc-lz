



function salvar()
{
    var request = {};
    var error = [];
    request.banco =  $('#banco').val();
    request.promotora = encodeURIComponent($('#promotora').val());
    
    request.loginmaster =  encodeURIComponent( $('#loginmaster').val());
    request.senhamaster = encodeURIComponent( $('#senhamaster').val());
    request.emailmaster = encodeURIComponent( $('#emailmaster').val());
   
    request.senhas = [];
    
     var observacao = encodeURIComponent($('#observacao').val());
    var link = encodeURIComponent($('#link').val());
//console.log(request.senhamaster);
    
    $('#datatable tbody tr').each(function(){
        var obj = {'login': $(this).find('td input').eq(0).val(), 'senha': $(this).find('td input').eq(1).val(), 'nome': $(this).find('td input').eq(2).val()};
        if ( obj.senha != '' && obj.senha != undefined  )  
            request.senhas.push(obj);
    });
    
   // console.log(JSON.stringify(request)); return false;
    
   if ($.trim(request.banco) == '')
    {
        alert('Escolha o banco');
        return false;
    }
    
    
    if (typeof key !== 'undefined')
        request.id = key;
    
    
    
   
    
    
    $.ajax({
        type: "POST",
        url:  '/cadastrosbasicos/salvar-senha-bancaria/',
        cache: false,
        dataType: "json",
        data: 'dados='+ JSON.stringify(request)+ '&observacao=' + observacao + '&link=' + link,
        success: function(json){
            if (json.success)
             {
                 alert('Registro salvo com sucesso');
                 if (json.success == true)
                    document.location = '/cadastrosbasicos/cadastrar-senha-bancaria/' + json.id;
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
        url: '/cadastrosbasicos/apagar-senha-bancaria/',
        cache: false,
        dataType: "json",
        data: 'id='+id,
        success: function(json){
            if (json.success)
             {
                 alert('Registro excluído com sucesso');
                 //$('#id').val(json.id);
                 document.location = '/cadastrosbasicos/senhas-bancarias/';
             }
            else
            {
                alert('Não foi possível realizar a alteração\nMotivo: ' + json.message);
                document.location.reload();
            }
        }
    });
    
}


