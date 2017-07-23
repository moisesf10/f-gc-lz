function buscarCep()
{
    
    cep = $('#cep').val().replace('-','');
    if (cep != '')
        $.getJSON("//viacep.com.br/ws/"+ cep +"/json/?callback=?", function(dados) {

                if (!("erro" in dados)) {
                    //Atualiza os campos com os valores da consulta.
                    $("#rua").val(dados.logradouro);
                    $("#bairro").val(dados.bairro);
                    $("#cidade").val(dados.localidade);
                    $('#uf option').each(function(index){
                       
                       if ($(this).val() == dados.uf)
                        {
                            $(this).attr('selected', true);
                           console.log('entrou');
                        }
                    });
                    //$("#uf").val(dados.uf);
                    //$("#ibge").val(dados.ibge)
                   
                } //end if.
                else {
                    alert('CEP não encontrado.')
                }
            });
}
    
function adicionarEmail()
{

    var row = '<div class="row">'+
                '<div class="col-md-4">'+
                    '<div class="form-group">'+
                        '<label class="col-sm-4 control-label">E-mail: </label>'+
                        '<div class="col-sm-8">'+
                            '<input type="text"   class="form-control">'+
                        '</div>'+
                    '</div>'+
                '</div>'+
                '<div class="col-md-4">'+
                    '<div class="form-group">'+
                        '<label class="col-sm-4 control-label">Senha: </label>'+
                        '<div class="col-sm-8">'+
                            '<input type="text"  class="form-control">'+
                       '</div>'+
                    '</div>'+
                '</div>'+
                '<div class="col-md-4">'+
                        '<button type="button" class="mb-xs mt-xs mr-xs btn btn-warning" '+ 'onclick="$(this).parent().parent().remove()">Remover</button>'+
                '</div>'+
            '</div>';
    $('#box-emails div.panel-body').append(row);
    
  
}
    
function adicionarTelefones()
{
    var dataUuid = 'tel-'+ new Date().getTime();
    var row = '<div class="row">'+
                '<div class="col-md-4">'+
                    '<div class="form-group">'+
                        '<label class="col-sm-4 control-label">Telefone: </label>'+
                        '<div class="col-sm-8">'+
                            '<input type="text"   class="form-control" data-uuid="'+ dataUuid + '">'+
                        '</div>'+
                    '</div>'+
                '</div>'+
                '<div class="col-md-4">'+
                    '<div class="form-group">'+
                        '<label class="col-sm-4 control-label">Refer&ecirc;ncia: </label>'+
                        '<div class="col-sm-8">'+
                            '<input type="text"  class="form-control">'+
                       '</div>'+
                    '</div>'+
                '</div>'+
                '<div class="col-md-4">'+
                        '<button type="button" class="mb-xs mt-xs mr-xs btn btn-warning" '+ 'onclick="$(this).parent().parent().remove()">Remover</button>'+
                '</div>'+
            '</div>';
    
    
    $('#box-telefones div.panel-body').append(row);
    
     $('#box-telefones div.panel-body [data-uuid="'+  dataUuid +'"]').mask('(99)9999-99999?',{placeholder:"(__)____-_____", autoclear: false});
}

function adicionarConvenio()
{
    var optionsConvenios = '';
    for (var i in convenios)
    {
        optionsConvenios += '<option value="' + convenios[i].id + '">'+ convenios[i].nome + '</option>';
    }
    var row = '<div class="row">'+
                 '<div class="col-md-3">'+
                        '<div class="form-group">'+
                        '<label class="col-sm-3 control-label">Conv&ecirc;nio: </label>'+
                        '<div class="col-sm-8">'+
                            '<select class="form-control">'+
                                '<option></option>'+ optionsConvenios +
                            '</select>'+
                        '</div>'+
                        '</div>'+
               '</div>'+
                '<div class="col-md-3">'+
                    '<div class="form-group">'+
                            '<label class="col-sm-3 control-label">Matr&iacute;cula: </label>'+
                            '<div class="col-sm-8">'+
                                '<input type="text" class="form-control maiusculo">'+
                            '</div>'+
                        '</div>'+
                '</div>'+
                '<div class="col-md-3">'+
                        '<div class="form-group">'+
                            '<label class="col-sm-3 control-label">Senha: </label>'+
                            '<div class="col-sm-8">'+
                                '<input type="text" class="form-control maiusculo">'+
                            '</div>'+
                        '</div>'+
                '</div>'+
                '<div class="col-md-3">'+
                        '<button type="button" class="mb-xs mt-xs mr-xs btn btn-warning" onclick="$(this).parent().parent().remove()">Remover</button>'+
                '</div>'+
            '</div>';
    
    
    $('#box-convenios div.panel-body').append(row);
    
  
}

function adicionarConta()
{
    
    var optionsBancos = '';
    for (var i in bancos)
    {
        optionsBancos += '<option value="' + bancos[i].id + '">'+ bancos[i].nome + '</option>';
    }
    var optionsTipoContas = '';
    for (var i in tipoContasBancos)
    {
        optionsTipoContas += '<option value="' + tipoContasBancos[i].id + '">'+ tipoContasBancos[i].descricao + '</option>';
    }
    var row = '<div class="row">'+
                '<div class="col-md-3">'+
                        '<div class="form-group">'+
                            '<label class="col-sm-3 control-label">Banco: </label>'+
                            '<div class="col-sm-8">'+
                                '<select class="form-control maiusculo">'+
                                    '<option></option>'+ optionsBancos +
                                '</select>'+
                            '</div>'+
                        '</div>'+
                '</div>'+
                '<div class="col-md-2">'+
                        '<div class="form-group">'+
                            '<label class="col-sm-3 control-label">Ag&ecirc;ncia: </label>'+
                            '<div class="col-sm-8">'+
                                '<input type="text" class="form-control maiusculo"   >'+
                            '</div>'+
                        '</div>'+
                '</div>'+
                '<div class="col-md-2">'+
                        '<div class="form-group">'+
                            '<label class="col-sm-3 control-label">Conta: </label>'+
                            '<div class="col-sm-8">'+
                                '<input type="text" class="form-control maiusculo">'+
                            '</div>'+
                        '</div>'+
                '</div>'+
                '<div class="col-md-3">'+
                        '<div class="form-group">'+
                            '<label class="col-sm-3 control-label">Tipo: </label>'+
                            '<div class="col-sm-8">'+
                                '<select class="form-control maiusculo">'+
                                    '<option></option>'+ optionsTipoContas +
                                '</select>'+
                            '</div>'+
                        '</div>'+
                '</div>'+
                '<div class="col-md-2">'+
                        '<button type="button" class="mb-xs mt-xs mr-xs btn btn-warning" onclick="$(this).parent().parent().remove()">Remover</button>'+
                '</div>'+
            '</div>';
    
    
    $('#box-dados-bancarios div.panel-body').append(row);
    
  
}


function adicionarArquivo()
{
 
    var row = '<div class="row">'+
                '<label class="col-md-1">Selecione o Arquivo</label>'+
                '<div class="col-md-2">'+
                    '<input type="text" readonly class="form-control">'+
                '</div>'+
                '<label class="col-md-1">Nome do Arquivo</label>'+
                '<div class="col-md-2">'+
                    '<input type="text" class="form-control">'+
                '</div>'+
                '<div class="col-md-2">'+
                    '<button type="button" class="btn btn-info mr-xs mb-sm" onclick="$(this).parent().parent().find(\'input\').eq(2).click()">Carregar arquivo</button>'+
                    '<button type="button" class="mb-sm  mr-xs btn btn-warning" onclick="descarregarArquivo($(this).parent().parent())">Remover</button>'+
                '</div>'+
                '<div class="col-md-1">'+
                        '<input type="file" class="hidden" onchange="carregarArquivo(this.files, $(this).parent().parent())">'+
                '</div>'+
            '</div>';
    
    
    $('.box-arquivos div.panel-body div.load-files ').append(row);
}


files = [];
function carregarArquivo(file, $row)
{
    
    
    if (file[0])
        file = file[0];
    else
        file = null;
    
    if (file != null)
    {
        indicaImportado = false;
        for (var i = 0; i < files.length  ; i++) 
        {
            if (files[i] != '')
                if (files[i].name.indexOf(file.name) > -1 )
                {
                    indicaImportado = true;
                    break;
                }
        }
        if (indicaImportado)
              alert("O arquivo "+ escape(file.name) +' não será adicionado porque já está na lista!');
           else
           {
               files.push(file);
               $row.find('input').eq(0).val(file.name);

           }
    }
    
   // $row.find('input[type="file"]').click();
}


function descarregarArquivo($row)
{
    var name = $row.find('input').eq(0).val();
    for (var i = 0; i < files.length; i++)
        if (files[i].name == name  )
            files.splice(i, 1);
    
    $row.remove();
    console.log(files);
}



function deletarArquivo(id, file)
{
    $.magnificPopup.open({ 
        items: { src: '#modalSuccess'},
        type: 'inline',
		preloader: false,
		modal: true
                         
       });

    $.ajax({
          type: "POST",
          url:  "/cliente/deletar-arquivo-cliente/",
          data: '&id='+id+'&filename='+encodeURIComponent(file),
          dataType: 'json',
        success: function(json){
            $.magnificPopup.close();
            
             if (json.success == true)
            {
                alert('Arquivo excluida com sucesso');
                document.location.reload();
            }else
            {
                alert('Não foi possível excluir o arquivo '+ json.message);
            }
        },
        error: function(e){
            $.magnificPopup.close();
            alert('Não foi possível excluir o arquivo '+ e.responseText);
        }
    });
}





function salvar()
{
    
    var cliente = {};
    cliente.dados = {};
    cliente.dados.cpf = $('#cpf').val().toUpperCase();
    cliente.dados.nome = $('#nome').val().toUpperCase();
    //cliente.dados.apelido = $('#apelido').val().toUpperCase();
    //cliente.dados.email = $('#email').val();
   // cliente.dados.senha = $('#senha').val().toUpperCase();
    cliente.dados.nascimento = $('#nascimento').val().toUpperCase();
    cliente.dados.cep = $('#cep').val().toUpperCase();
    cliente.dados.rua = $('#rua').val().toUpperCase();
    cliente.dados.numerorua = $('#numerorua').val().toUpperCase();
    cliente.dados.complemento = $('#complemento').val().toUpperCase();
    cliente.dados.bairro = $('#bairro').val().toUpperCase();
    cliente.dados.uf = $('#uf').val().toUpperCase();
    cliente.dados.cidade = $('#cidade').val().toUpperCase();
   // cliente.dados.timefutebol = $('#timefutebol').val().toUpperCase();
    //cliente.dados.
    var observacoes = $('#textAreaObservacoes').val().toUpperCase();
    
    
    cliente.emails = [];
    
    $('#box-emails div.panel-body div.row').each(function(){
        var email =  $.trim($(this).find('input').eq(0).val());
        var senha =   $.trim($(this).find('input').eq(1).val());

        
        if (email != '' )
            cliente.emails.push({'email': email, 'senha': encodeURIComponent(senha)  });
        
    });
    
    cliente.telefones = [];
    
    $('#box-telefones div.panel-body div.row').each(function(){
        var tel =  $(this).find('input').eq(0).val().replace(/[_]/gi,'') ;
        var ref =    $(this).find('input').eq(1).val();

        
        if ($.trim(tel) != '' )
            cliente.telefones.push({'numero': tel, 'referencia': ref});
        
    });
    
    cliente.convenios = [];
    
    $('#box-convenios div.panel-body div.row').each(function(){
        var convenio = $(this).find('select').val();
        var matricula = $.trim( $(this).find('input').eq(0).val() );
        var senha = $.trim( $(this).find('input').eq(1).val() );
        
        if (convenio != '' && matricula != '' )
            cliente.convenios.push( {'convenio': convenio,  'matricula': matricula, 'senha': encodeURIComponent(senha) }   );
        
    });
    
    if (cliente.convenios.length < 1 )
    {
        alert('É obrigatório informar no mínimo um convênio');
        return false;
    }
    
   
    
    cliente.contabancaria = [];
    
     $('#box-dados-bancarios div.panel-body div.row').each(function(){
        var banco = $(this).find('select').eq(0).val();
        var agencia = $.trim( $(this).find('input').eq(0).val() );
        var conta = $.trim( $(this).find('input').eq(1).val() );
        var tipoConta = $.trim( $(this).find('select').eq(1).val() );
        
        if (banco != '' && agencia != '' && conta != '' )
            cliente.contabancaria.push( {'banco': banco, 'agencia': agencia, 'conta': conta, 'tipoconta': tipoConta}  );
        
    });
    
   if (cliente.contabancaria.length < 1 )
    {
        alert('É obrigatório informar no mínimo uma conta bancária');
        return false;
    }
    
    var observacoes = $('#textAreaObservacoes').val();
    
    
    if (cliente.dados.cpf == '' || cliente.dados.nome == ''  || cliente.dados.nascimento == '' || cliente.dados.cep == '' || cliente.dados.rua == '' || cliente.dados.cidade == '' || cliente.dados.uf == '' 
       || cliente.dados.numerorua == '' || cliente.dados.bairro == ''
       )
    {
        var message = '';
        
        if (cliente.dados.cpf == '')
            message += '- CPF obrigatório\n';
        if (cliente.dados.nome == '')
            message += '- Nome obrigatório\n';
        if (cliente.dados.nascimento == '')
            message += '- Nascimento obrigatório\n';
        if (cliente.dados.cep == '')
            message += '- CEP obrigatório\n';
        if (cliente.dados.rua == '')
            message += '- Rua obrigatório\n';
        if (cliente.dados.cidade == '')
            message += '- Cidade obrigatório\n';
        if (cliente.dados.uf == '')
            message += '- UF obrigatório\n';
        if (cliente.dados.numerorua == '')
            message += '- Numero do endereço obrigatório\n';
        if (cliente.dados.uf == '')
            message += '- Bairro obrigatório\n';

        alert(message);
        return false;
    }
    
        var formData = new FormData();
        formData.append('dados', JSON.stringify(cliente));
        formData.append('cpf', update);
        formData.append('observacoes',  encodeURIComponent(observacoes) );
    
    var fileDescriptor = [];
    for(var i in files)
    {
        var descricaoFile = '';
        var name = files[i].name;
        $('.box-arquivos div.panel-body div.load-files div.row').each(function(){
            if ($(this).find('input').eq(0).val() == name  )
                descricaoFile = $(this).find('input').eq(1).val();
        });
        fileDescriptor.push(descricaoFile);
        formData.append('file'+i, files[i]);
    }
        
    formData.append('descricaoFile', JSON.stringify(fileDescriptor) );
    
     $.ajax({
        type: "POST",
        url:  '/'+ application.getPage().toLowerCase()  + '/salvar-cliente/',
        cache: false,
        dataType: "json",
        cache       : false,
        contentType : false,
        processData : false,
        data: formData, // + '&cpf='+update + '&dados=' + JSON.stringify(cliente) + '&observacoes=' + encodeURIComponent(observacoes),
        success: function(json){
            if (json.success)
             {
                if (json.error != '')
                    alert('O arquivo '+ json.filename + ' não pode ser gravado\n' + json.error);
                            
               if (update == '')
                {
                     if( confirm("Registro salvo com sucesso\nDeseja fazer o agendamento"))
                        {
                            document.location = '/cliente/cadastrar-agenda/?&cliente='+ json.cpf.replace(/[.\/-]/gi, '');
                        }else
                        {
                            
                            if (json.cpf !== undefined)
                                document.location = '/'+ application.getPage() + '/cadastrar/' + json.cpf.replace(/[.\/-]/gi, '') ;
                        }
                }else
                {
                    alert('Salvo com sucesso');
                    if (json.cpf !== undefined)
                        document.location = '/'+ application.getPage() + '/cadastrar/' + json.cpf.replace(/[.\/-]/gi, '') ;
                }
                 
             }
            else
            {
                alert('Não foi possível realizar a alteração\nMotivo: ' + json.message);
                //document.location.reload();
            }
        }
    });
    
    
    
}


