<?php
$usuarios = $this->getParams('usuarios');
?>
<section class="panel">
        <header class="panel-heading">
            <h2 class="panel-title">Relatório de Descontos</h2>
        </header>
        <div class="panel-body">


        <div class="panel-body">
            <div class="form-group">
                <label class="col-md-3 control-label">Nome do Usuário</label>
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
            <div class="form-group">
                <label class="col-md-3 control-label">Nome do Cliente</label>
                <div class="col-md-4">
                    <input type="text" class="form-control" name="nomecliente" id="nomecliente" />
                </div>
            </div>
        </div>
        <div class="panel-body">
            <div class="form-group">
                <label class="col-md-3 control-label">Data de lançamento do desconto</label>
                <div class="col-md-4">
                    <div class="input-daterange input-group" data-plugin-datepicker>
                        <span class="input-group-addon">
                            <i class="fa fa-calendar"></i>
                        </span>
                        <input type="text" class="form-control" name="datainicio" id="datainicio">
                        <span class="input-group-addon">Até</span>
                        <input type="text" class="form-control" name="datafim" id="datafim">
                    </div>
                </div>
            </div>
        </div>

        <div class="panel-body">
            <button type="button" class="mb-xs mt-xs mr-xs btn btn-danger" onclick="pesquisar()">Pesquisar</button>
        </div>

    </div>

</section>

<table class="display" id="datatable"  cellspacing="0" width="100%">
    <thead>
        <tr>
            <th>#</th>
            <th>Descrição</th>
            <th>Usuario</th>
            <th>Descontado</th>
            <th>Criado em</th>
            <th>Baixar</th>
        </tr>
    </thead>
    <tbody>

    </tbody>
</table>

<table class="table mb-none" id="subtotal">
	<thead>
		<tr>
			<th>Numero de Contrato</th>
			<th>Valor dos contratos</th>

			<th>Usuario </th>

			<th> Total de desconto</th>


		</tr>
	</thead>
	<tbody>

		<tr class="info">
			<td></td>
			<td></td>
			<td></td>
			<td></td>

	</tbody>
</table>

<br />






    <script src="/library/jsvendor/select2/select2.min.js"></script>
<script src="/library/jsvendor/bootstrap-datepicker/js/bootstrap-datepicker.js"></script>

<script src="/library/jsvendor/magnific-popup/magnific-popup.js"></script>
<script>
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
               table =  $('#datatable').DataTable({
                       "bStateSave": false,
                        "BLengthChange" : false,
                         "iDisplayLength": 15,
                         "bInfo": false,
                         "bSort": false,
                         "bLengthChange": false,
                         "paging": true,
                         "searching": true,

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
    
function pesquisar()
{
    var usuario = $('#usuario').val();
    var nomeCliente = $('#nomecliente').val();
    var dataInicio = $('#datainicio').val();
    var dataFim = $('#datafim').val();
    
    
    
     $.ajax({
          type: "POST",
          url:  '/adiantamentos/obter-json-lista-resumo-relatorio/',
          data: '&usuario=' + usuario + '&nomecliente=' + nomeCliente + '&datainicio='+ dataInicio +'&datafim='+ dataFim ,
          dataType: 'json',
          cache: false,
          success: function(json){
             // $.magnificPopup.close();
                table.clear().draw();
                var nomeUsuario = json[0].nomeUsuario;
                var valorContratos = 0;
                  var totalDescontos = 0;
                  for (var i in json)
                  {
                      if (nomeUsuario != json[i].nomeUsuario)
                          nomeUsuario = '';
                      valorContratos += (json[i].valorContratos * 1);
                      totalDescontos += (json[i].valorDescontos * 1);
                      table.row.add([
                        json[i].id,
                        json[i].descricao,
                          json[i].nomeUsuario,
                        formatReal(json[i].valorDescontos * 1),
                        json[i].created,
                        '<a href="/adiantamentos/download-relatorio/' + json[i].id + '" target="_BLANK" class="on-default remove-row"><i class="fa  fa-download"></i></a>'
                      ]);

                  }

                  table.draw(false);
                $('#subtotal tbody tr').find('td').eq(0).html(json.length);
              $('#subtotal tbody tr').find('td').eq(1).html(formatReal(valorContratos));
              $('#subtotal tbody tr').find('td').eq(2).html(nomeUsuario);
              $('#subtotal tbody tr').find('td').eq(3).html(formatReal(totalDescontos));


        },
    });
}

function formatReal(n) {
    n = parseFloat(n);
    return "R$ " + n.toFixed(2).replace('.', ',').replace(/(\d)(?=(\d{3})+\,)/g, "$1.");
}
</script>
