<?php
$usuarios = $this->getParams('usuarios');
?>
<section class="panel">
        <header class="panel-heading">
            <h2 class="panel-title">Adiantamentos</h2>
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
            <?php if (\Application::isAuthorized('Adiantamentos' , 'adiantamentos_cadastro', 'escrever')) { ?>
                <button type="button" class="mb-xs mt-xs mr-xs btn btn-grey" onclick="document.location='/adiantamentos/cadastrar-adiantamento'">Adicionar Adiantamento</button>
            <?php } ?>
            <button type="button" class="mb-xs mt-xs mr-xs btn btn-danger" onclick="pesquisar()">Pesquisar</button>
        </div>

    </div>

</section>

<table class="display" id="datatable"  cellspacing="0" width="100%">
    <thead>
        <tr>
            <th>#</th>
            <th>Nome</th>
            <th>Usuário a ser Descontado</th>
            <th>Parcelas</th>
            <th>Data Inicial do Desconto</th>
            <th>Acesar</th>


        </tr>
    </thead>
    <tbody>

    </tbody>
</table>


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
                         "iDisplayLength": 10,
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
    var dataInicio = $('#datainicio').val();
    var dataFim = $('#dataFim').val();
    // Carrega os Adiantamentos
        $.ajax({
              type: "POST",
              url:  '/adiantamentos/listar-adiantamentos/',
              data: '&usuario=' + usuario + '&datainici=' + dataInicio + '&datafim=' + dataFim +'&limit=1000000' ,
              dataType: 'json',
              cache: false,
              success: function(json){
               // $.magnificPopup.close();
                table.clear().draw();
                var valor = 0;
                  var total = 0;
                  for (var i in json)
                  {


                      table.row.add([
                        json[i].id,
                        json[i].descricao,
                        json[i].nomeUsuario,
                        (json[i].qtdParcelas == 0) ? '%' : json[i].qtdParcelas,
                        json[i].created,
                        '<a href="/adiantamentos/cadastrar-adiantamento/'+ json[i].id + '"><i class="material-icons">&#xE254;</i></a>'
                      ]);

                  }

                  table.draw(false);



                 // $('.valor').html(formatReal(valor) );
                 // $('.total').html(total);

            },
        });
}



</script>
