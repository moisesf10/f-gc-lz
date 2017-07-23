
file = null;

$(function(){
    document.getElementById('file').addEventListener('change', obterArquivos, false);
})


function carregarArquivo()
{
    $('#file').click();
}


function obterArquivos(evt)
{
	
    file = evt.target.files[0]; // FileList object
    
    if (file.type != 'application/vnd.ms-excel' && file.type != 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet' && file.type != 'text/x-csv'   )
    {
        alert('Arquivo inválido para importação. Escolha um arquivo CSV ou EXCEL');
        file = null;
        $('#filename').val('');
        return false;
    }
    
    $('#filename').val(file.name);
    
    
}


function importar()
{
    var nomeImportacao = $.trim( $('#nomeimportacao').val());
    var convenio = $('#convenio').val();
    var usuarios = $('#usuarios').val();
    
    if (file == null)
    {
        alert('Você não informou o arquivo');
        return false;
    }
    
    if (convenio == '')
    {
        alert('O convênio é obrigatório');
        return false;
    }
    
    if (! confirm('Deseja realmente importar o arquivo sem definir um nome de importação'))
        return false;
    
     var form = $('#form1')[0];
     // Create an FormData object
     var data = new FormData(form);
		// If you want to add an extra field for the FormData
     data.append("usuarios", JSON.stringify(usuarios) );
    
     // disabled the importar button
        $("#buttonimportar").prop("disabled", true);
    
      $.ajax({
            type: "POST",
            enctype: 'multipart/form-data',
            url: "/cliente/telemarketing-salvar-arquivo-importacao",
            data: data,
            dataType: "json",
            processData: false,
            contentType: false,
            cache: false,
            timeout: 600000,
            success: function (data) {
                
                if (data.success == true)
                {
                    alert('Importado com sucesso');
                    document.location.reload();
                }else
                {
                     alert('Ocorreram erros. ' + data.message  );
                     $("#buttonimportar").prop("disabled", false);
                }
               

            },
            error: function (e) {

                alert('Ocorreram erros. Favor contate o suporte\n' + e  );
                $("#buttonimportar").prop("disabled", false);

            }
        });
    
}