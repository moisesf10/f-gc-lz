
function salvar()
{
    if (typeof UID == undefined)
    {
        alert('O ID da importação não foi definido corretamente');
        return false;
    }
    
    var usuarios =  $('#usuarios').val();
   
    
    $.ajax({
        type: "POST",
        url:  '/cliente/telemarketing-salvar-atribuicao-usuario',
        cache: false,
        dataType: "json",
        data: 'usuarios='+ JSON.stringify(usuarios) + '&id=' + UID,
        success: function(json){
            if (json.success == true)
             {
                 alert('Registro salvo com sucesso');
                 document.location.reload();
             }
            else
            {
                alert('Não foi possível realizar a alteração\nMotivo: ' + json.message);
                //document.location.reload();
            }
        }
    });
    
}