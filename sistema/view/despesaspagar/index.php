<?php
$usuarios = $this->getParams('usuarios');
?>
<form id="form1">
    <section class="panel">
            <header class="panel-heading">
                <h2 class="panel-title">Despesas a Pagar</h2>
            </header>
            <div class="panel-body">

            <div class="panel-body">
                <div class="form-group">
                    <label class="col-md-3 control-label">Criado em</label>
                    <div class="col-md-4">
                        <div class="input-daterange input-group" data-plugin-datepicker>
                            <span class="input-group-addon">
                                <i class="fa fa-calendar"></i>
                            </span>
                            <input type="text" class="form-control" name="datainiciocriacao" id="datainiciocriacao">
                            <span class="input-group-addon">Até</span>
                            <input type="text" class="form-control" name="datafimcriacao" id="datafimcriacao">
                        </div>
                    </div>
                </div>
            </div>


            <div class="panel-body">
                <div class="form-group">
                    <label class="col-md-3 control-label">Vence em</label>
                    <div class="col-md-4">
                        <div class="input-daterange input-group" data-plugin-datepicker>
                            <span class="input-group-addon">
                                <i class="fa fa-calendar"></i>
                            </span>
                            <input type="text" class="form-control" name="datainiciovencimento" id="datainiciovencimento">
                            <span class="input-group-addon">Até</span>
                            <input type="text" class="form-control" name="datafimvencimento" id="datafimvencimento">
                        </div>
                    </div>
                </div>
            </div>

            <div class="panel-body">
                <div class="form-group">
                    <label class="col-md-3 control-label">Descrição da Despesa</label>
                    <div class="col-md-4">
                        <input type="text" id="descricao" name="descricao" class="form-control">
                    </div>
                </div>

            </div>





            <div class="panel-body">
                <?php if (\Application::isAuthorized('Administracao' , 'despesas_pagar', 'escrever')) { ?>
                    <button type="button" class="mb-xs mt-xs mr-xs btn btn-grey" onclick="document.location='/administracao/cadastrar-despesas-pagar'">Adicionar Despesa</button>
                <?php } ?>
                <button type="button" class="mb-xs mt-xs mr-xs btn btn-danger" onclick="pesquisar()">Pesquisar</button>
            </div>

        </div>

    </section>
</form>


<table class="display" id="datatable"  cellspacing="0" width="100%">
    <thead>
        <tr>
            <th>#</th>
            <th>Descrição</th>
            <th>Criado em</th>
            <th>Vence em</th>
            <th>Valor Devido</th>
            <th>Valor Pago</th>
            <th>Acesar</th>


        </tr>
    </thead>
    <tbody>

    </tbody>
</table>

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
    $.magnificPopup.open({ 
        items: { src: '#modalSuccess'},
        type: 'inline',
		preloader: false,
		modal: true
                         
       });
    
    // Carrega os Adiantamentos
        $.ajax({
              type: "POST",
              url:  '/administracao/json-listar-despesas-pagar/',
              data: $('#form1').serialize() +'&limit=999999' ,
              dataType: 'json',
              cache: false,
              success: function(json){
                $.magnificPopup.close();
                table.clear().draw();
               
                  for (var i in json)
                  {

                      table.row.add([
                        json[i].id,
                        json[i].descricao,
                        json[i].created,
                        json[i].vencimento,
                        formatReal(json[i].valorDevido),
                        formatReal(json[i].valorPago),
                        '<a href="/administracao/cadastrar-despesas-pagar/'+ json[i].id + '"><i class="material-icons">&#xE254;</i></a>'
                      ]);

                  }

                  table.draw(false);


            },
            error: function(r){
                $.magnificPopup.close();
                alert(r.responseText);
            }
        });
}

function formatReal(n) {
    n = parseFloat(n);
    return "R$ " + n.toFixed(2).replace('.', ',').replace(/(\d)(?=(\d{3})+\,)/g, "$1.");
}

</script>
