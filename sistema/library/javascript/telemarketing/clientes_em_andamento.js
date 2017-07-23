/*$(function(){
   $('#status').change(function(){
      if ($(this).val() == 4)
          $('#dataligacao, #horaligacao').attr('readonly', false);
       else
           $('#dataligacao, #horaligacao').attr('readonly', true);
      
   });
});
*/


function adicionarTelefones()
{
    var $lastDiv = $('.box-telefone div.grupo-telefone:last-child');
    var rowNumber = $lastDiv.find('input').data('number') + 1;
    
    if ( $lastDiv.find('input').val() == ''  )
        return false;
   
    var html = '<div class="col-md-6 col-sm-6 grupo-telefone">'
                +'<div class="form-group">'
                    +'<label class="col-sm-2 control-label">Telefone</label>'
                    +'<div class="col-sm-4">'
                        +'<input type="text" name="telefone'+ rowNumber  +'" id="telefone'+ rowNumber +'"  class="form-control" value="" data-number="'+ rowNumber +'" onBlur="adicionarTelefones()">'
                    +'</div>'
                    +'<div class="col-md-6">'
                        +'<label class="checkbox-inline">'
                            +'<input type="radio" id="radiotelefone'+ rowNumber +'"  name="radiotelefone'+ rowNumber +'" value="1"  checked> Certo'
                        +'</label>'
                        +'<label class="checkbox-inline">'
                            +'<input type="radio" id="radiotelefone'+ rowNumber +'"  name="radiotelefone'+rowNumber +'" value="0">  Errado'
                        +'</label>'
                    +'</div>	'
                +'</div>'
            +'</div>';
    
    $('.box-telefone').append(html);
}


function salvar()
{
    var telefone = [];
    var status = $('#status').val();
    
    $('.box-telefone div.grupo-telefone').each(function(){ 
        var row = $(this).find('input').data('number');
        var fone = ($.trim($('#telefone'+ row).val())  != '') ? $('#telefone'+ row).val() : null ;
        var certo = $('#radiotelefone'+ row).val() ;
        if (fone != null)
            telefone.push({'telefone': fone, 'certo' : certo });
    });
    
      
    
    var form = $('#form1')[0];
     // Create an FormData object
     var data = new FormData(form);
		// If you want to add an extra field for the FormData
     data.append("telefones", JSON.stringify(telefone) );
     data.append("id", UID );
    
   
    
    $("#buttonSalvar").prop("disabled", true);
    $.ajax({
        type: "POST",
        url:  '/cliente/telemarketing-salvar-cliente-em-andamento',
        dataType: "json",
        data: data,
        processData: false,
        contentType: false,
        cache: false,
        timeout: 600000,
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
            $("#buttonSalvar").prop("disabled", true);
        },
        error: function(text){
            $("#buttonSalvar").prop("disabled", true);
            alert('Não foi possível realizar a alteração\nMotivo: ' + text);
            
        }
    });
  
}

