<?php
$usuarios = $this->getParams('usuarios');
?>
<style>
    .subtitulo {margin-top: 5rem;}
</style>
<section class="panel">
        <header class="panel-heading">
            <h2 class="panel-title">Cadastro de Adiantamento</h2>
        </header>
        <div class="panel-body">

            <div class="panel-body">
                <div class="form-group">
                    <label class="col-md-2 control-label">Nome do Usuário</label>
                    <div class="col-md-4">
                        <select data-plugin-selectTwo class="form-control " id="usuario" name="usuario">
                            <optgroup label="Selecione o nome do usuário">
                                <option></option>
                                <?php
                                    if (is_array($usuarios))
                                        foreach($usuarios as $i => $value)
                                            echo '<option value="'. $value['id'] . '">'. ucwords(strtolower($value['nome'])) . '</option>';
                                ?>

                            </optgroup>

                        </select>
                    </div>
                </div>

            </div>
            <div class="panel-body">
                <label class="col-md-2 control-label">Nome do Relatório</label>
                <div class="col-md-4">
                    <input type="text" class="form-control" name="nome" id="nome" />
                </div>
            </div>

            <div class="panel-body">
                <label class="col-md-2 control-label">Comissão Bloqueada?</label>
                <div class="col-md-4">
                    <input class="toggle-button" type="checkbox" data-onstyle="danger" data-on="Sim" data-off="Não" data-size="small" name="comissaobloqueada" id="comissaobloqueada" />
                </div>
            </div>

            <div class="panel-body">
                <button type="button" class="mb-xs mt-xs mr-xs btn btn-grey" onclick="carregar()">Carregar</button>
                <?php if (\Application::isAuthorized('Adiantamentos' , 'adiantamentos_cadastro', 'escrever')) { ?>
                   <button type="button" class="mb-xs mt-xs mr-xs btn btn-danger" disabled id="buttonSalvar" onclick="salvar()">Salvar e Gerar PDF</button>
                <?php } ?>
            </div>


            <div class="panel-body">
                <div class="col-md-12">
                    <h3 class="panel-title subtitulo">Contratos pagos</h3>
                </div>
            </div>


            <div class="panel-body">
                <div class="col-md-12">
                    <table class="display" id="datatablecontratos"  cellspacing="0" width="100%">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Nome</th>
                                <th>Banco</th>
                                <th>Convênio</th>
                                <th>Tabela</th>
                                <th>Operação</th>
                                <th>Prazo</th>
                                <th>Valor da Parcela</th>
                                <th>Valor do Contrato</th>
                                <th>Comissão</th>
                                <th>Data de Pagamento</th>
                                <th>Receb. Banco</th>
                                <th>Criado em</th>
                                <th>Status</th>
                                <th>Substatus</th>
                                <th>Comissão Loja</th>
                                <th>Comissão Grupo</th>
                                <th>Comissão Gerente</th>
                                <th>Usuário</th>
                                <th>Marcar Todos <input type="checkbox" /> </th>
                            </tr>
                        </thead>
                        <tbody>

                        </tbody>
                    </table>
                </div>
            </div>


            <div class="panel-body">
                <div class="col-md-12">
                    <h3 class="panel-title subtitulo">Descontos</h3>
                </div>
            </div>

            <div class="panel-body">
                <div class="col-md-12">
                    <table class="display" id="datatabledescontos"  cellspacing="0" width="100%">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Nome</th>
                                <th>Valor</th>
                                <th>Parcela / %</th>
                                <th>Parcela a Descontar</th>
                                <th>Usuário</th>
                                <th>Marcar Todos <input type="checkbox" /></th>
                            </tr>
                        </thead>
                        <tbody>

                        </tbody>
                    </table>
                </div>
            </div>

            <div class="panel-body">
                <div class="col-md-12">
                  <table class="table mb-none" id="subtotal">
                      <thead>
                        <tr>
                          <th>Números de Contrato</th>
                          <th>Valor dos Contratos</th>
                          <th>Comissão Vendedor</th>
                          <th>Usuario</th>
                          <th>Comissão Loja</th>
                          <th>Comissão Gerente</th>
                          <th>Total Descontos</th>


                        </tr>
                      </thead>
                      <tbody>

                        <tr class="info">
                          <td></td>
                          <td></td>
                          <td></td>
                          <td></td>
                          <td></td>
                          <td></td>
                          <td></td>
                      </tbody>
                    </table>
                </div>
            </div>



    </div>
</section>




<!-- Modal Progress -->
<div id="modalSuccess" class="modal-block modal-block-success mfp-hide">
    <section class="panel">
        <header class="panel-heading">
            <h2 class="panel-title">Aguarde, processando!</h2>
        </header>
        <div class="panel-body">
            <div class="modal-wrapper">

                <div class="modal-text">
                    <div class="progress progress-striped active" style="margin-bottom:0;">
                        <div class="progress-bar primary-danger" style="width: 100%"></div>
                    </div>
                </div>
            </div>
        </div>
        <footer class="panel-footer">

        </footer>
    </section>
</div>






<script src="/library/jsvendor/bootstrap-toggle/js/bootstrap-toggle.min.js"></script>
<script src="/library/jsvendor/select2/select2.min.js"></script>
<script src="/library/jsvendor/bootstrap-datepicker/js/bootstrap-datepicker.js"></script>
<script src="/library/jsvendor/magnific-popup/magnific-popup.js"></script>


<script>
    // request.ler = $(this).parent().parent().parent().find('input.toggle-button[data-acao="ler"]').prop('checked');
$.getCSS('/library/jsvendor/bootstrap-toggle/css/bootstrap-toggle.min.css');
$.getCSS('/library/jsvendor/select2/select2.css');
$.getCSS('/library/jsvendor/select2/select2.custom.css');
$.getCSS('/library/jsvendor/magnific-popup/magnific-popup.css');


$.getCSS('/library/jsvendor/datatables/css/jquery.dataTables.min.css');
$.getCSS('/library/jsvendor/datatables/css/responsive.dataTables.min.css');

$.getScript( "/library/jsvendor/datatables/js/jquery.dataTables.min.js", function(  ) {
     /**
     * Inicia DataTable... CALLBACK executado após baixar dataTables.min.js
     *
     **/

    $.getScript("/library/jsvendor/datatables/js/dataTables.responsive.min.js", function(){
       tableContratos =  $('#datatablecontratos').DataTable({
                "bStateSave": false,
                "BLengthChange" : false,
                 "iDisplayLength": 20,
                 "bInfo": false,
                 "bSort": false,
                 "bLengthChange": false,
                 "paging": false,
                 "searching": false,
                 "scrollX": true,
                 "deferRender": true,
                 "oLanguage": {
                     "oPaginate": {
                         "sNext": "Pr&oacute;ximo",
                         "sPrevious": "Anterior"

                      },
                     "sInfoEmpty": "",
                     "sSearch": "Pesquisar:",
                     "sZeroRecords": " " ,
                     "sInfo": "Mostrando página _PAGE_ de _PAGES_",
                     "sInfoFiltered": "(Filtrado _MAX_ do total)"
                  }
            } );

        tableDescontos =  $('#datatabledescontos').DataTable({
                "bStateSave": false,
                "BLengthChange" : false,
                 "iDisplayLength": 0,
                 "bInfo": false,
                 "bSort": false,
                 "bLengthChange": false,
                 "paging": false,
                 "searching": false,

           "deferRender": true,
                 "oLanguage": {
                     "oPaginate": {
                         "sNext": "Pr&oacute;ximo",
                         "sPrevious": "Anterior"

                      },
                     "sInfoEmpty": "",
                     "sSearch": "Pesquisar:",
                     "sZeroRecords": " " ,
                     "sInfo": "Mostrando página _PAGE_ de _PAGES_",
                     "sInfoFiltered": "(Filtrado _MAX_ do total)"
                  } ,
           responsive: {
                        details: {
                            display: $.fn.dataTable.Responsive.display.modal( {
                                header: function ( row ) {
                                    var data = row.data();
                                    return 'Detalhes para '+data[0]+' '+data[1];
                                }
                            } ),
                            renderer: $.fn.dataTable.Responsive.renderer.tableAll( {
                                tableClass: 'table'
                            } )
                        }
                    }
            } );
    }); // fim $.GetScript
});


$(function(){
   $('.toggle-button').bootstrapToggle();

    $('#datatablecontratos thead tr th input').change(function(){
       if ($(this).is(':checked'))
        {
            $('#datatablecontratos tbody tr td input').each(function(){
                $(this).prop('checked', true);
            });
        }else
        {
            $('#datatablecontratos tbody tr td input').each(function(){
                $(this).prop('checked', false);
            });
        }
    });

    $('#datatabledescontos thead tr th input').change(function(){
       if ($(this).is(':checked'))
        {
            $('#datatabledescontos tbody tr td input').each(function(){
                $(this).prop('checked', true);
            });
        }else
        {
            $('#datatabledescontos tbody tr td input').each(function(){
                $(this).prop('checked', false);
            });
        }
    });


});


function carregar()
{
    var usuario = $('#usuario').val();
    if (usuario == '')
    {
        alert('Escolha o usuário');
        return false;
    }


    $.magnificPopup.open({
        items: { src: '#modalSuccess'},
        type: 'inline',
		preloader: false,
		modal: true

       });


    // Carrega os Contratos
    $.ajax({
          type: "POST",
          url:  '/adiantamentos/obter-json-lista-contratos/',
          data: '&usuario=' + usuario +'&limit=1000000' ,
          dataType: 'json',
          cache: false,
          success: function(json){
            $.magnificPopup.close();
            tableContratos.clear().draw();

            var valorContratos = 0;
              var valorComissoes = 0;
              var totalDescontos = 0;
              var valorComissaoLoja = 0;
              var valorComissaoGerente = 0;
              for (var i in json)
              {


                  tableContratos.row.add([
                    json[i].id,
                    json[i].nomeCliente,
                    json[i].banco,
                    json[i].convenio,
                    json[i].tabela,
                    json[i].operacao,
                      json[i].prazo,
                      json[i].valorParcela,
                      json[i].valorTotal,
                      json[i].comissao,
                      json[i].dataPagamento,
                      json[i].dataPagamentoBanco,
                      json[i].created,
                      json[i].status,
                      json[i].substatus,
                      formatReal(json[i].comissaoLoja),
                      '',
                      '',
                      json[i].nomeUsuario,
                    '<input type="checkbox"  value="' + json[i].id + '" data-comissao="'+ json[i].comissao +'" />'
                  ]);

                  valorContratos = valorContratos + (json[i].valorTotal * 1);
                  valorComissoes += (json[i].comissao * 1);
                  valorComissaoLoja = valorComissaoLoja +  (json[i].comissaoLoja * 1)

              }

              tableContratos.draw(false);

            $('#subtotal tbody tr').find('td').eq(0).html(json.length);
            $('#subtotal tbody tr').find('td').eq(1).html(formatReal(valorContratos));
              $('#subtotal tbody tr').find('td').eq(2).html(formatReal(valorComissoes));
              $('#subtotal tbody tr').find('td').eq(3).html(json[0].nomeUsuario);
              $('#subtotal tbody tr').find('td').eq(4).html(formatReal(valorComissaoLoja));
              $('#subtotal tbody tr').find('td').eq(5).html(formatReal(valorComissaoGerente));
              $('#subtotal tbody tr').find('td').eq(6).html(formatReal(totalDescontos));


            if (json.length > 0)
                $('#buttonSalvar').attr('disabled', false);
            else
                $('#buttonSalvar').attr('disabled', true);
             // $('.valor').html(formatReal(valor) );
             // $('.total').html(total);

        },
        error: function(r)
        {
            tableDescontos.clear().draw();
        }
    });


    // Carrega os Adiantamentos
    $.ajax({
          type: "POST",
          url:  '/adiantamentos/obter-json-lista-adiantamentos/',
          data: '&usuario=' + usuario +'&limit=1000000' ,
          dataType: 'json',
          cache: false,
          success: function(json){
            $.magnificPopup.close();
            tableDescontos.clear().draw();
            var valor = 0;
              var total = 0;
              for (var i in json)
              {

                 var tipoValor = (json[i].qtdParcelas == 0) ? 'percentual' : 'valor';
                  tableDescontos.row.add([
                    json[i].id,
                    json[i].descricao,
                    json[i].valorParcela,
                    (json[i].qtdParcelas == 0) ? '%' : json[i].qtdParcelas,
                    (json[i].ultimaParcelaPaga + 1),
                    json[i].nomeUsuario,
                    '<input type="checkbox"  value="' + json[i].id + '" data-valor="'+ json[i].valorParcela +'" data-tipovalor="'+ tipoValor +'" data-valorpago="'+ json[i].valorTotalPago +'" data-valordevido="'+ json[i].valorTotalPagar  +'" />'
                  ]);

              }

              tableDescontos.draw(false);



             // $('.valor').html(formatReal(valor) );
             // $('.total').html(total);

        },
    });


}


function formatReal(n) {
    n = parseFloat(n);
    return "R$ " + n.toFixed(2).replace('.', ',').replace(/(\d)(?=(\d{3})+\,)/g, "$1.");
}


function salvar()
{
    var nome = $.trim($('#nome').val());
    var comissaoBloqueada = ($('#comissaobloqueada').is(':checked') ) ? 1 : 0;
    var somatorioComissao = 0;
    var somatorioDescontos = 0;
    var percentualDescontar = 0;
    var usuario = $('#usuario').val();

    var contratos = [];
    var descontos = [];
    $('#datatablecontratos tbody input[type="checkbox"]').each(function(){
        if ($(this).is(':checked') )
        {
            somatorioComissao += ($(this).data('comissao') * 1);
            contratos.push($(this).val());
        }
    });

    $('#datatabledescontos tbody input[type="checkbox"]').each(function(){
        if ($(this).is(':checked') )
        {
            if ($(this).data('tipovalor') == 'percentual')
            {
               // calcula o que ainda é devido
               var valorTotalDevido = ($(this).data('valordevido') * 1) - ($(this).data('valorpago') * 1);
               var valorCalculadoDeduzir = ( ($(this).data('valor') /100) * 1) * somatorioComissao;
               if (valorCalculadoDeduzir > valorTotalDevido)
                valorCalculadoDeduzir = valorTotalDevido;
              //  percentualDescontar += valorCalculadoDeduzir;
              somatorioDescontos += valorCalculadoDeduzir;
            }
            else
                somatorioDescontos += ($(this).data('valor') * 1);
            descontos.push($(this).val());
        }
    });

    // com o percentual a ser descontado somado, efetua o desconto por percentual
   //  somatorioDescontos += somatorioComissao * percentualDescontar;

    if (nome == '')
    {
      alert('Defina o nome que deseja salvar o relatório');
      return false;
    }

    if (contratos.length < 1)
    {
        alert('Não existem contratos selecionados para descontar');
        return false;
    }

    if (somatorioComissao < somatorioDescontos)
    {
        alert('Os descontos devem ser menores que a comissão.\n- Comissões: '+ formatReal(somatorioComissao) + '\n- Descontos: ' + formatReal(somatorioDescontos) );
        return false;
    }


    if (! confirm('Deseja efetivar a transação? A mesma não poderá ser desfeita.\n- Comissões: '+ formatReal(somatorioComissao) + '\n- Descontos: ' + formatReal(somatorioDescontos) ))
        return false;


    $.ajax({
          type: "POST",
          url:  '/adiantamentos/salvar-relatorio/',
          data: '&usuario=' + usuario + '&nome=' + encodeURIComponent(nome) + '&comissaobloqueada='+ comissaoBloqueada +'&contratos='+ JSON.stringify(contratos) + '&descontos=' + JSON.stringify(descontos) ,
          dataType: 'json',
          cache: false,
          success: function(json){
            if (json.success)
            {
              alert('O relatório foi gerado com sucesso');
              document.location.reload();
            }else {
              alert('Não foi possível gerar o relatório. Código: ' + json.message);
            }

        },
    });

}


</script>
