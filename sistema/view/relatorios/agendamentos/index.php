<?php
$convenios = $this->getParams('convenios');
$operacoes = $this->getParams('operacoes');
$grupos = $this->getParams('grupos');
$usuarios = $this->getParams('usuarios');
?>

<style>
.danger {
        color: #FFF !important;
        background-color: #d2322d !important;
        padding: 5px 0 5px 0;
    }
</style>
 


<form name="form1" id="form1" method="post" action="/relatorios/gerar-comissao-loja/" target="_blank">
<section class="panel">
        <header class="panel-heading">
            <h2 class="panel-title">Relatório de Agendamentos</h2>
        </header>
        <div class="panel-body">
                <div class="form-group">
                        <label class="col-md-3 control-label">Grupo</label>
                        <div class="col-md-4">
                            <select data-plugin-selectTwo class="form-control " id="grupo" name="grupo">
                                <optgroup label="Selecione o nome do vendedor">
                                    <option></option>
                                    <?php
                                        if (is_array($grupos))
                                            foreach($grupos as $i => $value)
                                                echo '<option value="'. $value['id'] . '">'. strtoupper($value['nome']) . '</option>';
                                    ?>
                                    
                                </optgroup>

                            </select>
                        </div>
                    </div>
            
        </div>
    
        <div class="panel-body">
                <div class="form-group">
                        <label class="col-md-3 control-label">Entidade/Conv&ecirc;nio</label>
                        <div class="col-md-4">
                            <select data-plugin-selectTwo class="form-control " id="convenio" name="convenio">
                                <optgroup label="Selecione o nome do vendedor">
                                    <option></option>
                                    <?php
                                        if (is_array($convenios))
                                            foreach($convenios as $i => $value)
                                                echo '<option value="'. $value['id'] . '">'. $value['nome'] . '</option>';
                                    ?>
                                    
                                </optgroup>

                            </select>
                        </div>
                    </div>
            
        </div>
        
        <div class="panel-body">
                <div class="form-group">
                        <label class="col-md-3 control-label">Vendedor</label>
                        <div class="col-md-4">
                            <select data-plugin-selectTwo class="form-control " id="vendedor" name="vendedor">
                                <optgroup label="Selecione o nome do vendedor">
                                    <option></option>
                                    <?php
                                        if (is_array($usuarios))
                                            foreach($usuarios as $i => $value)
                                                echo '<option value="'. $value['id'] . '">'. $value['nome'] . '</option>';
                                    ?>
                                    
                                </optgroup>

                            </select>
                        </div>
                    </div>
            
        </div>
        <div class="panel-body">
            <div class="form-group">
                        <label class="col-md-3 control-label">Criado em</label>
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
                    <div class="form-group">
                        <label class="col-md-3 control-label">Agendado para</label>
                        <div class="col-md-4">
                            <div class="input-daterange input-group" data-plugin-datepicker>
                                <span class="input-group-addon">
                                    <i class="fa fa-calendar"></i>
                                </span>
                                <input type="text" class="form-control" name="dataagendamentoinicio" id="dataagendamentoinicio">
                               <span class="input-group-addon">Até</span>
                                <input type="text" class="form-control" name="dataagendamentofim" id="dataagendamentofim">
                            </div>
                        </div>
                    </div>
            </div>
		
    
    <div class="panel-body">
                    <div class="form-group">
                        <label class="col-md-3 control-label">Status da ligação</label>
                        <div class="col-md-4">
                            <select class="form-control" id="status" name="status">
                                        <option></option>
                                        <option value="" >Todos</option>
                                        <option value="Pendente" >Pendente</option>
                                        <option value="Efetuada" >Efetuada</option>
                                    </select>
                        </div>
                    </div>
            </div>
    
      
    
    
    
      
    
    <div class="panel-body">
          <button type="button" class="mb-xs mt-xs mr-xs btn btn-danger" onclick="gerar()">Pesquisar</button>
    </div>
    
   
    
    
              
                    <table class="display" id="datatable"  cellspacing="0" width="100%">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Nome</th>
                                <th>CPF</th>
                                <th>Convênio</th>
                                <th>Usuario</th>
                                <th>Status da Ligação</th>
                                <th>Acesar</th>


                            </tr>
                        </thead>
                        <tbody>
                           
                        </tbody>
                    </table>

            

        <div class="panel-body">
            <div class="table-responsive">
                <table class="table mb-none ">
                    <thead>
                        <tr>
                            <th>Numero de agendamentos</th>


                        </tr>
                    </thead>
                    <tbody>

                        <tr class="info ">
                            <td class="danger"><label id="totalagendamentos"></label> </td>

                    </tbody>
                </table>
            </div>
        </div>

    
    
    
</section>
</form>



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
                         "iDisplayLength": 0,
                         "bInfo": false,
                         "bSort": false,  
                         "bLengthChange": false,
                         "paging": false,
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
    
    
    
    
    
    
    
    
    
    
    
function gerar()
{
   
    if ($('#datainicio').val() == '' && $('#dataagendamentoinicio').val() == '')
    {
        alert('A data de criação ou a data do agendamento precisa ser informada');
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
          url:  '/<?php echo  strtolower(\Application::getNameController()); ?>/pesquisar-agendamentos/',
          data: $('#form1').serialize() ,
          dataType: 'json',
          success: function(data){
            $.magnificPopup.close();

            table.clear().draw();
            //table.rows.add($.parseJSON(data)).draw();
              for (var i in data)
              {

                  table.row.add([data[i].id, data[i].nomeCliente, data[i].cpf, data[i].nomeConvenio, data[i].nomeUsuario, data[i].status, '<a href="/<?php echo strtolower('cliente'). '/cadastrar-agenda/';?>'+ data[i].id + '"><i class="material-icons">&#xE254;</i></a>' ]);
              }
              
              table.draw(false);
              
              $('#totalagendamentos').html(data.length);
            //table.ajax.url(data).load();
            //table.fnClearTable();
          //  table.fnAddData(data);
        },
    });
}
    
    
</script>

