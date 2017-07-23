tabela = [];
contasCliente = [];

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
            if (json.length > 0)
            {
                
                $('#nomecliente').val(json[0].dados.nomeCliente);
                $('#cep').val(json[0].dados.cep);
                $('#rua').val(json[0].dados.rua);
                $('#numerorua').val(json[0].dados.numeroRua);
                $('#complemento').val(json[0].dados.complemento);
                $('#bairro').val(json[0].dados.bairro);
                $('#uf').val(json[0].dados.uf);
                $('#cidade').val(json[0].dados.cidade);
                for (var i in json[0].contas)
                {
                    contasCliente.push(
                        {"idContaBancariaCliente": json[0].contas[i].idContaBancariaCliente,"idBanco": json[0].contas[i].idBanco ,"idTipoConta": json[0].contas[i].idTipoConta,"agencia": json[0].contas[i].agencia,"conta": json[0].contas[i].conta,"nomeBanco": json[0].contas[i].nomeBanco,"descricaoConta": json[0].contas[i].descricaoConta}
                    );
                }
                
                $('#contabancaria').html('<option value=""></option>');
                
                if (contasCliente.length == 1)
                    $('#contabancaria').html('<option value="" data-position="-1"></option><option data-position="0" value="'+ contasCliente[0].idContaBancariaCliente +'">Conta 1</option>');
                else if (contasCliente.length == 2)
                    $('#contabancaria').html('<option data-position="-1" value=""></option><option data-position="0" value="'+ contasCliente[0].idContaBancariaCliente +'">Conta 1</option><option data-position="1" value="'+ contasCliente[1].idContaBancariaCliente +'">Conta 2</option>');
            }
            else
                alert('Falso');
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
                        var option = document.createElement('option');
                       option.value = tabela[i].idBanco;
                       option.text = tabela[i].nomeBanco;
                       document.getElementById('contratoBanco').appendChild(option);
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
        $('#contratoSeguro').html('<option value=""></option>');
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
        $('#contratoSeguro').html('<option value=""></option>');
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
        $('#contratoSeguro').html('<option value=""></option>');
    }
  
});


$('#contabancaria').change(function(e){
    var position = $('#contabancaria option:selected').data('position');
    console.log(position);
    if (position > -1)
    {
        $('#dadosBanco').val(contasCliente[position].nomeBanco);
        $('#dadosConta').val(contasCliente[position].conta);
        $('#dadosAgencia').val(contasCliente[position].agencia);
        $('#dadosTipoConta').val(contasCliente[position].descricaoConta);
    }else
        $('#dadosBanco, #dadosConta, #dadosAgencia, #dadosTipoConta').val('');
    
});





