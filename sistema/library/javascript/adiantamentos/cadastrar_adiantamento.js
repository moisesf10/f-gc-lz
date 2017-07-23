
function salvar()
{
   var usuario = $('#usuario').val();
   var descricao = $('#descricao').val();
   var tipoPagamento = $('#tipopagamento').val();
   var parcela = $('#parcela').maskMoney('unmasked')[0];
   var valorPagar = $('#totalpagar').maskMoney('unmasked')[0];
   var qtdParcelas = null;

   if  (typeof key == 'undefined')
      var id = '';
   else
      var id = key;

   var error = '';

   if (usuario == '')
      error += '\n- Nome do Usuário';
   if (descricao == '')
    error += '\n- Descrição do Adiantamento';
   if (tipoPagamento == '')
     error += '\n- Pagar Como';
   if (parcela == '')
     error += '\n- Parcela';
   if (valorPagar == '')
     error += '\n- Valor Total a Pagar';

   if (tipoPagamento == '%')
   {
      qtdParcelas = '';
      tipoPagamento = 'Percentual';
    }
   else
   {
       qtdParcelas = tipoPagamento;
       tipoPagamento = 'Valor';
   }

   if (tipoPagamento == 'Percentual' && parcela > 100 )
   {
     alert('Não é possível abater mais de 100% da comissão');
     return false;
   }


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
        url: '/adiantamentos/salvar-adiantamento',
        cache: false,
        dataType: "json",
        data: 'id='+id + '&usuario='+ usuario + '&descricao=' + encodeURIComponent(descricao) +'&tipopagamento=' + tipoPagamento + '&parcela=' + parcela + '&valorpagar=' + valorPagar + '&quantidadeparcelas='+qtdParcelas,
        success: function(json){
          $.magnificPopup.close();
            if (json.success)
             {
                 alert('Registro salvo com sucesso');
                 //$('#id').val(json.id);
                 document.location = '/adiantamentos/cadastrar-adiantamento/' + json.id;
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
          url: '/adiantamentos/excluir-adiantamento',
          cache: false,
          dataType: "json",
          data: 'id='+id ,
          success: function(json){
            $.magnificPopup.close();
              if (json.success)
               {
                   alert('Registro removido com sucesso');
                   //$('#id').val(json.id);
                   document.location = '/adiantamentos/pesquisa/';
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
