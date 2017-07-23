function mvalor(v){
 //   utilizado para dar o INIT dos campos moedas
    v=v.replace(/\D/g,"");//Remove tudo o que não é dígito
    v=v.replace(/(\d)(\d{8})$/,"$1.$2");//coloca o ponto dos milhões
    v=v.replace(/(\d)(\d{5})$/,"$1.$2");//coloca o ponto dos milhares

    v=v.replace(/(\d)(\d{2})$/,"$1,$2");//coloca a virgula antes dos 2 últimos dígitos
    return v;
}


tabela = [];

contasCliente = [];

//contratos[];

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
                
                $('#nomecliente').val(json.cliente[0].dados.nomeCliente);
                $('#cep').val(json.cliente[0].dados.cep);
                $('#rua').val(json.cliente[0].dados.rua);
                $('#numerorua').val(json.cliente[0].dados.numeroRua);
                $('#complemento').val(json.cliente[0].dados.complemento);
                $('#bairro').val(json.cliente[0].dados.bairro);
                $('#uf').val(json.cliente[0].dados.uf);
                $('#cidade').val(json.cliente[0].dados.cidade);
                for (var i in json.cliente[0].contas)
                {
                    
                    contasCliente.push(
                        {"idContaBancariaCliente": json.cliente[0].contas[i].idContaBancariaCliente,"idBanco": json.cliente[0].contas[i].idBanco ,"idTipoConta": json.cliente[0].contas[i].idTipoConta,"agencia": json.cliente[0].contas[i].agencia,"conta": json.cliente[0].contas[i].conta,"nomeBanco": json.cliente[0].contas[i].nomeBanco,"descricaoConta": json.cliente[0].contas[i].descricaoConta}
                    );
                }
                //console.log(json.contratos);//446.564.830-87
                if (typeof key == 'undefined')
                {
                
                       table.clear().draw();
                        for (var i in json.contratos)
                        {
                           // console.log(json.contratos[i].numeroContrato);
                                table.row.add([
                                    json.contratos[i].numeroContrato,
                                    json.contratos[i].nomeBancoContrato,
                                    json.contratos[i].nomeOperacao,
                                    json.contratos[i].quantidadeParcelas + 'x',
                                    json.contratos[i].valorParcela,
                                    json.contratos[i].valorTotal,
                                    json.contratos[i].status
                                ]).draw();

                        }
                    }
                
                
                //$('#contabancaria').html('<option value=""></option>');
				var htmlContasBancarias = '<option value="" data-position="-1"></option>';
				for (pos in contasCliente)
					htmlContasBancarias += '<option data-position="'+ pos+'" value="'+ contasCliente[pos].idContaBancariaCliente +'">'+ contasCliente[pos].conta  +'</option>';
				$('#contabancaria').html(htmlContasBancarias);
				
				
            }
            else
                alert('Cliente não encontrado');
        }
        
    });
}




$.ajax({
        type: "GET",
        url: '/contratos/obter-tabelas/',
        cache: false,
        dataType: "json",
       cache: true,
      //  data: 'id='+id,
        success: function(json){
            if (json.length > 0)
            {
               for (var i in json)
               {
                       var t = {};
                        t.idBanco = json[i].idBanco;
                        t.codigoBanco = json[i].codigoBanco;
                        t.nomeBanco = json[i].nomeBanco;
                        t.statusBanco = json[i].statusBanco;
                        t.idTabela = json[i].idTabela;
                        t.nomeTabela = json[i].nomeTabela;
                        t.idConvenio = json[i].idConvenio;
                        t.nomeConvenio = json[i].nomeConvenio;
                        t.idSubtabela = json[i].idSubtabela;
                        t.valorSeguro = json[i].valorSeguro;
                        t.valorImposto = json[i].valorImposto;
                        t.valorComissaoTotal = json[i].valorComissaoTotal;
                        t.inicioVigencia = json[i].inicioVigencia;
                        t.fimVigencia = json[i].fimVigencia;
                        t.idOperacoesSubtabela = json[i].idOperacoesSubtabela;
                        t.nomeOperacoesSubtabela = json[i].nomeOperacoesSubtabela;
                        t.prazos = [];
                        t.comissoes = [];

                        for (var j in json[i].prazos )
                            t.prazos.push({'idPrazo': json[i].prazos[j].idPrazo, 'idSubtabela': json[i].prazos[j].idSubtabela, 'prazo': json[i].prazos[j].prazo});

                       for (var j in json[i].comissoes)
                           t.comissoes.push(
                            {
                                'idComissoesSubtabela': json[i].comissoes[j].idComissoesSubtabela,
                                'idSubtabela': json[i].comissoes[j].idSubtabela,
                                'valorComissao': json[i].comissoes[j].valorComissao,
                                'idGrupoUsuario': json[i].comissoes[j].idGrupoUsuario,
                                'nomeGrupoUsuario': json[i].comissoes[j].nomeGrupoUsuario
                            }
                           );

                tabela.push(t);
                    
               }
                
               for (var i in tabela)
               {
                  if ( $("#contratoBanco option[value='"+ tabela[i].idBanco +"']").length < 1)
                    {
                             var partesData = tabela[i].inicioVigencia.split("/");
                            var data = new Date(partesData[2], partesData[1] - 1, partesData[0]);
                            var inicioVigencia = data;
                           var partesData = tabela[i].fimVigencia.split("/");
                            var data = new Date(partesData[2], partesData[1] - 1, partesData[0]);
                           var fimVigencia = data;
                           var hoje = new Date();
                        // if ( (hoje >= inicioVigencia && hoje <= fimVigencia) ||  (typeof idBancoSubtabela != 'undefined' && tabela[i]['idBanco'] == idBancoSubtabela)    )
                          //  {

                                        var option = document.createElement('option');
                                       option.value = tabela[i].idBanco;
                                       option.text = tabela[i].nomeBanco;
                                        if (typeof idBancoSubtabela != 'undefined' && tabela[i]['idBanco'] == idBancoSubtabela)
                                            option.setAttribute('selected', 'selected');
                                       document.getElementById('contratoBanco').appendChild(option);
                            //}
                    }
               }
                
            } // fim json.length > 0
        }
    });


$('#contratoBanco').change(function(e){
    if ($(this).val() != '')
    {
        $('#contratoConvenio').html('<option value=""></option>');
        var idBanco = $(this).val();
        for (var i in tabela)
        {
            if (tabela[i].idBanco == idBanco)
            {
                 if ( $("#contratoConvenio option[value='"+ tabela[i].idConvenio +"']").length < 1)
                {
                     var option = document.createElement('option');
                     option.value = tabela[i].idConvenio;
                     option.text = tabela[i].nomeConvenio;
                     document.getElementById('contratoConvenio').appendChild(option);
                }
               
            }
        }
    }else
    {
        $('#contratoConvenio').html('<option value=""></option>');
        $('#contratoOperacao').html('<option value=""></option>');
        $('#contratoTabela').html('<option value=""></option>');
        $('#contratoSeguro').html('');
    }
});

$('#contratoConvenio').change(function(e){ 
    if ($(this).val() != '')
    {
        $('#contratoOperacao').html('<option value=""></option>');
        var idBanco = $('#contratoBanco').val();
        var idConvenio = $(this).val();
        for (var i in tabela)
        { 
            if (tabela[i].idBanco == idBanco   && tabela[i].idConvenio == idConvenio    )
            {
                 if ( $("#contratoOperacao option[value='"+ tabela[i].idOperacoesSubtabela +"']").length < 1)
                {
                     var option = document.createElement('option');
                     option.value = tabela[i].idOperacoesSubtabela;
                     option.text = tabela[i].nomeOperacoesSubtabela;
                     document.getElementById('contratoOperacao').appendChild(option);
                }
            }
        }
    }else
    {
     //   $('#contratoConvenio').html('<option value=""></option>');
        $('#contratoOperacao').html('<option value=""></option>');
        $('#contratoTabela').html('<option value=""></option>');
        $('#contratoSeguro').html('');
    }
  
});


$('#contratoOperacao').change(function(e){ 
    if ($(this).val() != '')
    {
        $('#contratoTabela').html('<option value=""></option>');
        var idBanco = $('#contratoBanco').val();
        var idConvenio = $('#contratoConvenio').val();
        var idOperacao = $(this).val();
        for (var i in tabela)
        { 
            if (tabela[i].idBanco == idBanco   && tabela[i].idConvenio == idConvenio && tabela[i].idOperacoesSubtabela == idOperacao    )
            {
               
                 if ( $("#contratoTabela option[value='"+ tabela[i].idTabela +"']").length < 1)
                {
                     var option = document.createElement('option');
                     option.value = tabela[i].idTabela;
                     option.setAttribute('data-subtabela' , tabela[i].idSubtabela)
                     option.text = tabela[i].nomeTabela;
                     document.getElementById('contratoTabela').appendChild(option);
                }
                
            }
        }
    }else
    {
     //   $('#contratoConvenio').html('<option value=""></option>');
       // $('#contratoOperacao').html('<option value=""></option>');
        $('#contratoTabela').html('<option value=""></option>');
        $('#contratoSeguro').html('');
    }
  
});


$('#contratoTabela').change(function(e){ 
    if ($(this).val() != '')
    {
        $('#contratoSeguro').html('');
        var idBanco = $('#contratoBanco').val();
        var idConvenio = $('#contratoConvenio').val();
        var idOperacao = $('#contratoOperacao').val();
        var idTabela = $(this).val();
        for (var i in tabela)
        { 
            if (tabela[i].idBanco == idBanco   && tabela[i].idConvenio == idConvenio && tabela[i].idOperacoesSubtabela == idOperacao && tabela[i].idTabela == idTabela   )
            {
                 document.getElementById('contratoSeguro').value = 'R$ ' + mvalor(tabela[i].valorSeguro);
                 $('#contratoPrazos').html('<option value=""></option>');
                 for (var j in tabela[i].prazos)
                {
                     if ( $("#contratoPrazos option[value='"+ tabela[i].prazos[j].prazo +"']").length < 1)
                    {
                         var option = document.createElement('option');
                         option.value = tabela[i].prazos[j].prazo;
                         option.text = tabela[i].prazos[j].prazo + 'x';
                         document.getElementById('contratoPrazos').appendChild(option);
                    }
                }
                
            }
                
            
        }
    }else
    {
        $('#contratoSeguro').html('');
    }
  
});


$('#contabancaria').change(function(e){
    var position = $('#contabancaria option:selected').data('position');
  
    if (position > -1)
    {
        $('#dadosBanco').val(contasCliente[position].nomeBanco);
        $('#dadosConta').val(contasCliente[position].conta);
        $('#dadosAgencia').val(contasCliente[position].agencia);
        $('#dadosTipoConta').val(contasCliente[position].descricaoConta);
    }else
        $('#dadosBanco, #dadosConta, #dadosAgencia, #dadosTipoConta').val('');
    
});



function salvar()
{
    var request = {};
    request.cpf = $.trim($('#cpf').val());
    request.idcontabancariacliente = $('#contabancaria').val();    
    request.status = $('#status').val(); 
    request.substatus = $('#substatus').val();
    request.idsubtabela = $('#contratoTabela option:selected').data('subtabela');
    request.seguro = $('#contratoSeguro').maskMoney('unmasked')[0];
    request.prazo =  $('#contratoPrazos').val();
    request.valorparcela = $('#contratoValorParcela').maskMoney('unmasked')[0]; 
    request.valortotal = $('#contratoValorTotal').maskMoney('unmasked')[0]; 
    request.valorliquido = $('#contratoValorLiquido').maskMoney('unmasked')[0]; 
    request.idtipoconvenio = $('#tipoConvenio').val();
    request.dataPagamento = $('#contratoDataPagamento').val();
    request.dataPagamentoBanco = $('#contratoDataPagamentoBanco').val();
    request.idusuariovinculado = $('#contratoUsuarioVinculado').val();
    request.observacao = encodeURIComponent( $('#contratoObservacao').val());
    
    var error = [];
    if (request.cep == '')
        error.push('- Informe o CPF do cliente');
    if (request.idcontabancariacliente == '')
        error.push('- Informe a conta bancária do cliente');
    if (request.idsubtabela == undefined)
        error.push('- É necessário definir a tabela a ser utilizada');

    if (request.valortotal == '')
        error.push('- O valor total deve ser informado');
   // if (request.valorliquido == '')
     //   error.push('- O valor liquido deve ser informado');
    
    if (error.length > 0)
    {
        var message = '';
        for(var i in error)
            message += error[i] + "\n";
        alert(message);
        return false;
    }
    
    
     if (typeof key !== 'undefined')
        request.idcontrato = key;
    
  
    $.ajax({
        type: "POST",
        url: '/contratos/salvar-contrato/',
        cache: false,
        dataType: "json",
       cache: true,
        data: 'dados='+JSON.stringify(request),
        success: function(json){
            if (json.success)
             {
                 alert('Registro salvo com sucesso');
                 if (json.success == true && json.id != 'update')
                    document.location = '/contratos/cadastrar/' + json.id;
                 else if (json.success == true && json.id == 'update')
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



