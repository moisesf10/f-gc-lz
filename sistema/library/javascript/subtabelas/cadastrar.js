tabelasConveniadas = [];
function initJsonTabela()
{
    
     
     for (var i in jsonTabela)
       {
           var t = {};
            t.idBanco = jsonTabela[i].idBanco;
            t.codigoBanco = jsonTabela[i].codigoBanco;
            t.nomeBanco = jsonTabela[i].nomeBanco;
            t.statusBanco = jsonTabela[i].statusBanco;
            t.idTabela = jsonTabela[i].idTabela;
            t.nomeTabela = jsonTabela[i].nomeTabela;
            t.idConvenio = jsonTabela[i].idConvenio;
            t.nomeConvenio = jsonTabela[i].nomeConvenio;
            tabelasConveniadas.push(t);
       }
   
    
    // adiciona os dados no selectElement do banco
     var selectedVal = $('#banco :selected').val();
     $('#banco').html('<option value=""></option>');
     for (var i in tabelasConveniadas)
       {
           if ( $("#banco option[value='"+ tabelasConveniadas[i].idBanco +"']").length < 1)
            {
               var option = document.createElement('option');
               option.value = tabelasConveniadas[i].idBanco;
               option.text = tabelasConveniadas[i].nomeBanco;
                if (selectedVal == option.value)
                    option.setAttribute('selected', 'selected');
               
               document.getElementById('banco').appendChild(option);
            }
       }
    
        
   // adiciona as escutas dos selectElements
    
    $('#banco').change(function(e){
    if ($(this).val() != '')
    {
        var selectedVal = $('#convenio').val();
         $('#convenio').html('<option value=""></option>');
        var idBanco = $(this).val();
        for (var i in tabelasConveniadas)
        {
            if (tabelasConveniadas[i].idBanco == idBanco)
            {
                 if ( $("#convenio option[value='"+ tabelasConveniadas[i].idConvenio +"']").length < 1)
                {
                     var option = document.createElement('option');
                     option.value = tabelasConveniadas[i].idConvenio;
                     option.text = tabelasConveniadas[i].nomeConvenio;
                    if (selectedVal == option.value)
                        option.setAttribute('selected', 'selected');
                     document.getElementById('convenio').appendChild(option);
                }
               
            }
        }
    }else
    {
        $('#convenio').html('<option value=""></option>');
        $('#operacao').html('<option value=""></option>');
        $('#tabela').html('<option value=""></option>');
        //$('#contratoSeguro').html('');
    }
});
    
    
    $('#convenio').change(function(e){ 
    if ($(this).val() != '')
    {
        var selectedVal = $('#tabela').val();
        $('#tabela').html('<option value=""></option>');
        var idBanco = $('#banco').val();
        var idConvenio = $(this).val();
        for (var i in tabelasConveniadas)
        { 
            if (tabelasConveniadas[i].idBanco == idBanco   && tabelasConveniadas[i].idConvenio == idConvenio    )
            {
                 if ( $("#tabela option[value='"+ tabelasConveniadas[i].idTabela +"']").length < 1)
                {
                     var option = document.createElement('option');
                     option.value = tabelasConveniadas[i].idTabela;
                     option.text = tabelasConveniadas[i].nomeTabela;
                    if (selectedVal == option.value)
                        option.setAttribute('selected', 'selected');
                     document.getElementById('tabela').appendChild(option);
                }
            }
        }
    }else
    {
     //   $('#contratoConvenio').html('<option value=""></option>');
        $('#operacao').html('<option value=""></option>');
        $('#tabela').html('<option value=""></option>');
        //$('#contratoSeguro').html('');
    }
  
});
    
    
   /* $('#tabela').change(function(e){ 
    if ($(this).val() != '')
    {
        $('#operacao').html('<option value=""></option>');
        var idBanco = $('#banco').val();
        var idConvenio = $('#convenio').val();
        var idTabela = $(this).val();
        for (var i in tabelasConveniadas)
        { 
            if (tabelasConveniadas[i].idBanco == idBanco   && tabelasConveniadas[i].idConvenio == idConvenio && tabelasConveniadas[i].idTabela == idTabela    )
            {
                 if ( $("#operacao option[value='"+ tabela[i].idOperacoesSubtabela +"']").length < 1)
                {
                     var option = document.createElement('option');
                     option.value = tabelasConveniadas[i].idOperacoesSubtabela;
                     option.text = tabelasConveniadas[i].nomeOperacoesSubtabela;
                     document.getElementById('operacao').appendChild(option);
                }
                
            }
        }
    }else
    {
     //   $('#contratoConvenio').html('<option value=""></option>');
       // $('#contratoOperacao').html('<option value=""></option>');
        $('#operacao').html('<option value=""></option>');
        //$('#contratoSeguro').html('');
    }
  
});*/
    
} // Fim function initJsonTabela




function adicionarGrupo()
{
    var dataUuidComissao = 'comissao-'+ new Date().getTime();
    var dataUuidGrupo = 'grupo-'+ new Date().getTime();
    var select = '<select class="form-control" data-Uuid="'+ dataUuidGrupo  +'"><option></option>';
    for (var i in grupos)
        select += '<option value="' + grupos[i].id + '" >' + grupos[i].nome + '</option>';
    select +=  '</select>';
    
    
    
    table.row.add( [
            select,
            '<input type="text" maxlength="6" class="form-control" data-Uuid="'+ dataUuidComissao  +'" />',
            '',
            '<button type="button"  class="mb-xs mt-xs mr-xs btn btn-warning" onclick="table.row( $(this).parents(\'tr\') ).remove().draw()">Remover</button>'
            
        ] ).draw( false );
    
     $('#datatable [data-uuid="'+  dataUuidComissao +'"]').maskMoney({prefix:'', allowNegative: false, thousands:',', decimal:'.', affixesStay: true});
     
                                                              
     //document.querySelector("[data-uuid='"+ dataUuidGrupo +"']").addEventListener('change', 'getSelectRecebeComissao',false);
    
    $('#datatable [data-uuid="'+  dataUuidGrupo +'"]').on('change', function(){
        
        var dataUuidOutroGrupo = 'outrogrupo-'+ new Date().getTime();
        var select = '<select class="form-control" multiple="multiple" data-Uuid="'+ dataUuidOutroGrupo  +'">';
        for (var i in grupos)
            if (grupos[i].id != $(this).val() )
                select += '<option value="' + grupos[i].id + '" >' + grupos[i].nome + '</option>';
        select +=  '</select>';
        
        $(this).closest('tr').find('td').eq(2).html(select);
        
        $('#datatable [data-uuid="'+  dataUuidOutroGrupo +'"]').multiselect({
            buttonWidth: '40rem',
            nonSelectedText: 'Selecione o grupo'
            
            });
        
    });
    
    
    /* $('#datatable [data-uuid="'+  dataUuidOutroGrupo +'"]').multiselect({
                onInitialized: function(select, container) {
                    alert('Initialized.');
                }
            });*/
}
    

function getSelectRecebeComissao(e)
{
    
    var dataUuidOutroGrupo = 'outrogrupo-'+ new Date().getTime();
    
    var select = '<select class="form-control" multiple="multiple" data-Uuid="'+ dataUuidOutroGrupo  +'">';
    for (var i in grupos)
        select += '<option value="' + grupos[i].id + '" >' + grupos[i].nome + '</option>';
    select +=  '</select>';
    
    
    
    
}





function salvar()
{
    var request = {};
    var error = [];
    request.id =    ( $.trim($('#id').val()) == '') ? null : $.trim($('#id').val());
    request.banco = $('#banco').val();
    request.convenio = $('#convenio').val();
    request.tabela = $('#tabela').val();
    request.operacao = $('#operacao').val();
    request.seguro = ($('#seguro').maskMoney('unmasked')[0] == '') ? 0.00 : $('#seguro').maskMoney('unmasked')[0];
    request.imposto = ($('#imposto').maskMoney('unmasked')[0] == '') ? 0.00 : $('#imposto').maskMoney('unmasked')[0];
    request.inicioVigencia = $('#iniciovigencia').val();
    request.fimVigencia = $('#fimvigencia').val();
    request.comissaoTotal = $('#comissaototal').maskMoney('unmasked')[0];
    request.prazos = [];
    request.valorPontuar = ($('#valorpontuar').maskMoney('unmasked')[0] == '') ? 0.00 : $('#valorpontuar').maskMoney('unmasked')[0];
    request.pontosGerar = ( $.trim( $('#pontosgerar').val()) == '') ? 0 : $('#pontosgerar').val();
    request.diasExpirar = ( $.trim($('#diasexpirar').val()) == '') ? 0 : $('#diasexpirar').val();
   
    $('.box-prazo .form-group').each(function(){
        var prazo = $(this).find('div').eq(0).find('input').val();
        var coeficiente = $(this).find('div').eq(1).find('input').val();
        var objPrazo = {'prazo': prazo, 'coeficiente': coeficiente};
        //impede a inserssão de prazo duplicado
        if (objPrazo.prazo != '' && objPrazo.coeficiente != '' && objPrazo.prazo != undefined && objPrazo.coeficiente != undefined )
        {
            for(var i in request.prazos)
                if (request.prazos[i].prazo == objPrazo.prazo)
                    error.push({'codigo': 'prazoduplicado', 'message': 'O prazo de '+ objPrazo.prazo + 'x está duplicado'} );
            
            request.prazos.push(objPrazo);
        }
         
       
    });
     // Ordena o array
    request.prazos.sort(function compare(a, b) {
        if (a.prazo < b.prazo) return -1;
        if (a.prazo > b.prazo) return 1;
        return 0;
    });
    
  
    
    request.comissoes = [];
    
    $('#datatable tbody tr').each(function(){
        
        var selectRecebeComissao = $(this).find('td').eq(2).find('select');
        var comissaoOutros = [];
       
        if (selectRecebeComissao.length > 0 )
            if (selectRecebeComissao.val() != null)
                comissaoOutros = selectRecebeComissao.val();
        
        var objComissoes = {'idGrupo': $(this).find('td').eq(0).find('select').val(), 'comissao': $(this).find('td').eq(1).find('input[type="text"]').val(), 'recebede': comissaoOutros };
        
        if ( objComissoes.idGrupo != '' && objComissoes.comissao != '' && objComissoes.idGrupo != undefined && objComissoes.comissao != undefined  )
        {
            for (var i in request.comissoes)
                if (request.comissoes[i].idGrupo == objComissoes.idGrupo && objComissoes.comissao != '')
                    error.push({'codigo': 'grupocomissaoduplicado', 'message': 'O grupo de comissão ' + $(this).find('select').text() + ' está duplicado.'});   
            
            request.comissoes.push(objComissoes);
        }
        
    });
    
    //console.log(JSON.stringify(request)); return false;
    
    // valida campos
    if (request.banco == '')
         error.push({'codigo': 'banconulo', 'message': 'O campo "Banco"; deve ser informado'});  
    if (request.convenio == '')
         error.push({'codigo': 'convenionulo', 'message': 'O campo "Convênio" deve ser informado'});
    if (request.tabela == '')
         error.push({'codigo': 'tabelanulo', 'message': 'O campo "Tabela" deve ser informado'});
    if (request.operacao == '')
         error.push({'codigo': 'operacaonulo', 'message': 'O campo "Operação" deve ser informado'});
   
  
    if (request.inicioVigencia == '')
         error.push({'codigo': 'iniciovigencianulo', 'message': 'O campo "Inicio Vigência" deve ser informado'});
    if (request.fimVigencia == '')
         error.push({'codigo': 'fimvigencianulo', 'message': 'O campo "Fim Vigência" deve ser informado'});
    if (request.comissaoTotal == '')
         error.push({'codigo': 'comissaototalnulo', 'message': 'O campo "Comissão Total" deve ser informado'});
 
    if (request.comissoes.length < 1)
         error.push({'codigo': 'gruposcomissoesnulo', 'message': 'Nenhum grupo de comissão foi definido'});
    
   
    
    if (error.length > 0)
    {
        var message = '';
        for(var i in error)
            message += "-  " + error[i].codigo + ' ( ' + error[i].message + ")\n"; 
        alert(message);
        return false;
    }
    
    
    
    $.ajax({
        type: "POST",
        url:  '/administracao/salvar-subtabela/',
        cache: false,
        dataType: "json",
        data: 'dados='+ JSON.stringify(request),
        success: function(json){
            if (json.success)
             {
                 alert('Registro salvo com sucesso');
                 if (json.success == true)
                    document.location = '/administracao/cadastrar-subtabela/' + json.id;
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
    if (! confirm('Deseja realmente excluir esta subtabela? A informação será removida para sempre.'))
        return false;
     var id = $('#id').val();
    $.ajax({
        type: "POST",
        url: '/administracao/apagar-subtabela/',
        cache: false,
        dataType: "json",
        data: 'id='+id,
        success: function(json){
            if (json.success)
             {
                 alert('Registro excluído com sucesso');
                 //$('#id').val(json.id);
                 document.location = '/administracao/subtabelas/';
             }
            else
            {
                alert('Não foi possível realizar a alteração\nMotivo: ' + json.message);
                //document.location.reload();
            }
        }
    });
    
}

