



function enviarArquivo()
{
   // console.log(file);
    
    if (file == null || (file.type != 'application/vnd.ms-excel' && file.type != 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'  ))
    {
        alert('É necessário selecionar um arquivo Excel');
        return false;
    }
    
    var formData = new FormData();
    
   // var nameFile = encodeURIComponent(file.name.replace(/^\s+/,""));
    formData.append('arquivo',file);
    
    //console.log(formData);
   // return false;
    
  
    $.magnificPopup.open({ 
        items: { src: '#modalSuccess'},
        type: 'inline',
		preloader: false,
		modal: true
                         
                         });
    
    $.ajax({
        type: "POST",
        url:  '/cliente/upload-importar-cliente/',
        cache: false,
        dataType: "json",
        data: formData,
        processData: false, // para enviar formData sem erro
        contentType: false, // para enviar formData sem erro
        success: function(json){
           
            if (json.success)
             {
                 alert('Importação concluída com sucesso');
                 document.location.reload();
             }
            else
            {
                alert('Não foi possível realizar a alteração\nMotivo: ' + json.message);
                //document.location.reload();
            }
        },
         error: function (jqXHR, textStatus, errorThrown) {
                alert('ocorreram erros para salvar o arquivo de log, no entanto, talvez algum cliente tenha sido salvo.\nFavor conferir o cadastro no menu clientes ');
                $.magnificPopup.close();
            }
    }).done(function() {
           $.magnificPopup.close();
        });
}
