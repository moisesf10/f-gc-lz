<?php
$agenda = $this->getParams('agenda');
$convenio = $this->getParams('convenio');

?>
<style>
    .box-button-save {margin-top: 3rem; padding-bottom: 3rem;}

</style>
<div class="row">
 <div class="col-md-4 box-button-save">
            <button type="button" class="mb-xs mt-xs mr-xs btn btn-grey" onclick="document.location='/cliente/cadastrar-agenda'"><i class="material-icons material-align-icons-button">&#xE145;</i>&nbsp;Novo Agendamento</button>
    </div>
</div>

<div class="panel panel-default">
  <div class="panel-body">
    <p>Utilize o formul&aacute;rio abaixo para encontrar cadastros de clientes</p>
      <form>
          <div class="row">
                <div class="col-md-4">
                        <div class="form-group">
                            <label class="col-sm-4 control-label">CPF: </label>
                            <div class="col-sm-8">
                                <input type="text" id="cpf"  class="form-control">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-4 control-label">Agendado para: </label>
                            <div class="col-sm-8">
                                <input type="text" id="agendamentoinicial"   class="form-control" data-plugin-datepicker data-input-mask="99/99/9999">
                            </div>
                        </div>
                </div>
              <div class="col-md-4">
                    <div class="form-group">
                        <label class="col-sm-4 control-label">Nome Cliente: </label>
                        <div class="col-sm-8">
                            <input type="text" id="nome"  class="form-control">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-4 control-label">At&eacute; </label>
                        <div class="col-sm-8">
                            <input type="text" id="agendamentofinal"  class="form-control" data-plugin-datepicker data-input-mask="99/99/9999">
                        </div>
                    </div>
                </div>
              <div class="col-md-4">
                    <div class="form-group">
                        <label class="col-sm-4 control-label">Convênio: </label>
                        <div class="col-sm-8">
                            <select id="convenio" class="form-control mb-md">
                                <option></option>
                                <?php 
                                        if (is_array($convenio))
                                            foreach($convenio as $i => $value)
                                            {
                                                $selected = (isset($cliente['convenios'][0]['idConvenio'])  && $cliente['convenios'][0]['idConvenio'] == $value['id']  ) ? 'selected="selected"' : '';
                                                echo '<option '. $selected .' value="'. $value['id'] .'">'. $value['nome'] .'</option>';
                                            }
                                    ?>
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-4 control-label">Status</label>
                        <div class="col-sm-8">
                            <select class="form-control" id="status">
                                <option></option>
                                <option value="Pendente">Pendente</option>
                                <option value="Efetuada">Efetuada</option>
                            </select>
                        </div>
                    </div>
                </div>
          </div>
          <div class="row">
                    <div class="col-md-2 box-button-save">
                            <button type="button" class="mb-xs mt-xs mr-xs btn btn-danger" onclick="buscar()"><i class="material-icons material-align-icons-button">&#xE8B6;</i>&nbsp;Pesquisar</button>
                    </div>
                 
              </div>
        </form>
          </div>
  </div>


<table id="datatable" class="display" cellspacing="0" width="100%">
    <thead>
        <tr>
            <th>#</th>
            <th>Usuario</th>
            <th>Cliente</th>
            <th>Criado em</th>
            <th>Ligar em</th>
            <th>Telefones</th>
            <th>Editar</th>
        </tr>
    </thead>
    <tbody>
        <?php
            if ((is_array($agenda)))
                foreach($agenda as $i => $value)
                {
                    $telefones = array();
                    foreach($value['telefones'] as $j => $t)
                        array_push($telefones, $t['numero']);
                    ?>
                    <tr>
                         <td><?php echo $value['id']; ?></td>
                         <td><?php echo $value['nomeUsuario']; ?></td>
                        <td><?php echo $value['nomeCliente']; ?></td>
                        <td><?php echo $value['created']; ?></td>
                        <td><?php echo $value['dataLigacao']; ?></td>
                        <td><?php echo implode(', ', $telefones); ?></td>
                         <td><a href="/<?php echo strtolower(\Application::getNameController()). '/cadastrar-agenda/' .  $value['id'];?>"><i class="material-icons">&#xE254;</i></a></td>
                    </tr>        
        
            <?php
                }
        ?>
        
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


<script src="/library/jsvendor/bootstrap-datepicker/js/bootstrap-datepicker.js" ></script>
<script src="/library/jsvendor/magnific-popup/magnific-popup.js"></script>
<script>


    $.getCSS('/library/jsvendor/datatables/css/jquery.dataTables.min.css');
$.getCSS('/library/jsvendor/datatables/css/responsive.dataTables.min.css');
$.getCSS('/library/jsvendor/magnific-popup/magnific-popup.css');
// Carregar JS de forma sincronizada. Desta forma primeiro carrega $.getScript e depois $.ajax como callback
$.getScript( "/library/jsvendor/datatables/js/jquery.dataTables.min.js", function(  ) {
     /**
     * Inicia DataTable... CALLBACK executado após baixar dataTables.min.js
     * 
     **/
    
    $.getScript("/library/jsvendor/datatables/js/dataTables.responsive.min.js", function(){
               table =  $('#datatable').DataTable({
                        "bStateSave": true,
                        "BLengthChange" : true,
                         "iDisplayLength": 20,
                         "bInfo": false,
                         "bSort": true,  
                         "bLengthChange": false,
                        "searching": true,
                        "paging": true,
                         "oLanguage": {
                             "oPaginate": {
                                 "sNext": "Pr&oacute;ximo",
                                 "sPrevious": "Anterior"

                              },  
                             "sInfoEmpty": "Nenhum agendamento cadastrado",
                             "sSearch": "Pesquisar:",
                             "sZeroRecords": "Nenhum agendamento cadastrado" ,
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
    
$.getScript('/library/jsvendor/jquery-masked-input/jquery.maskedinput.min.js', function(){
    $('#cpf').mask('999.999.999-99',{placeholder:"___.___.___-__", autoclear: true});
   // $('#mes').mask('99',{ autoclear: false});
  //  $('#box-telefones div.panel-body input.fone').mask('(99)9999-99999?',{placeholder:"(__)____-_____", autoclear: false});
  //  $('.nb').mask('9999999999');
});
    
function buscar()
{
    var cpf = $('#cpf').val();
    var nome = $('#nome').val();
    var convenio = $('#convenio').val();
    var agendamentoinicial = $('#agendamentoinicial').val();
    var agendamentofinal = $('#agendamentofinal').val();
    var status = $('#status').val();
	
	$.magnificPopup.open({ 
        items: { src: '#modalSuccess'},
        type: 'inline',
		preloader: false,
		modal: true
                         
       });
    
    $.ajax({
          type: "POST",
          url:  '/<?php echo  strtolower(\Application::getNameController()); ?>/pesquisar-agenda/',
          data: '&cpf='+cpf+'&nome='+nome+'&convenio='+convenio+'&agendamentoinicial='+agendamentoinicial+'&agendamentofinal='+agendamentofinal+ '&status='+status+'&limit=100000' ,
          dataType: 'json',
          success: function(data){
            $.magnificPopup.close();

            table.clear().draw();
            //table.rows.add($.parseJSON(data)).draw();
              for (var i in data)
              {
                  var telefones = [];
                  for (var a in data[i].telefones)  
                    telefones.push(data[i].telefones[a].numero);
                  table.row.add([data[i].id, data[i].nomeUsuario, data[i].nomeCliente, data[i].created, data[i].dataLigacao, telefones.join(', '), '<a href="/<?php echo strtolower(\Application::getNameController()). '/cadastrar-agenda/';?>'+ data[i].id + '"><i class="material-icons">&#xE254;</i></a>' ]);
              }
              
              table.draw(false);
            //table.ajax.url(data).load();
            //table.fnClearTable();
          //  table.fnAddData(data);
        },
    });
}
    
</script>