
function salvar()
{
   
    
   var descricao = $.trim( $('#descricao').val()) ;
   var dataVencimento = $('#datavencimento').val();
   var dataPagamento = $('#datapagamento').val();
   var valorDevido = $('#valordevido').maskMoney('unmasked')[0];
   var valorPago = $('#valorpago').maskMoney('unmasked')[0];
   

   if  (typeof key == 'undefined')
      var id = '';
   else
      var id = key;

   var error = '';

   if (descricao == '')
      error += '\n- Descrição';
   if (dataVencimento == '')
    error += '\n- Data de vencimento';
   


  if (error != '')
  {
    alert('Os seguintes campos são de preenchimento obrigatório\n'+ error);
    return false;
  }

  $.magnificPopup.open({
      items: { src: '#modalSuccess'},
      type: 'inline',
  		preloader: false,
  		modal: true
  });
    $.ajax({
        type: "POST",
        url: '/administracao/salvar-despesas-pagar',
        cache: false,
        dataType: "json",
        data: 'id='+id + '&descricao='+ encodeURIComponent(descricao) + '&vencimento=' + dataVencimento +'&pagamento=' + dataPagamento + '&valordevido=' + valorDevido + '&valorpago=' + valorPago,
        success: function(json){
          $.magnificPopup.close();
            if (json.success)
             {
                 alert('Registro salvo com sucesso');
                 //$('#id').val(json.id);
                 document.location = '/administracao/cadastrar-despesas-pagar/' + json.id;
             }
            else
            {
                alert('Não foi possível realizar a alteração\nMotivo: ' + json.message);
                //document.location.reload();
            }
        },
        error: function(r){
          $.magnificPopup.close();
          alert(r.responseText);
        }
    });

  }


  function excluir()
  {
    if  (typeof key == 'undefined')
    {
      alert ('Não foi possível ler o identificador do registro.');
      return false;
    }else {
         var id = key;
    }

    if (! confirm('Deseja realmente remover este registro?\nA ação não poderá ser desfeita'))
      return false;

    $.magnificPopup.open({
        items: { src: '#modalSuccess'},
        type: 'inline',
    		preloader: false,
    		modal: true
    });
      $.ajax({
          type: "POST",
          url: '/administracao/excluir-despesas-pagar',
          cache: false,
          dataType: "json",
          data: 'id='+id ,
          success: function(json){
            $.magnificPopup.close();
              if (json.success)
               {
                   alert('Registro removido com sucesso');
                   //$('#id').val(json.id);
                   document.location = '/administracao/despesas-pagar/';
               }
              else
              {
                  alert('Não foi possível realizar a alteração\nMotivo: ' + json.message);
                  //document.location.reload();
              }
          },
          error: function(r){
            $.magnificPopup.close();
            alert(r.responseText);
          }
      });
  }
