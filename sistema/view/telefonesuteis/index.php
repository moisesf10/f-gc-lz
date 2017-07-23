<?php
$telefones = $this->getParams('telefones');

?>
<style>
    .panel-body{background-color: #fafafa; border: 1px solid #e0e0e0; padding-top: 3rem;}
    .box-button-save {margin-top: 3rem; margin-left: 1.7rem; padding-bottom: 3rem;}

</style>

<div class="row">
 <div class="col-md-2 box-button-save">
            <button type="button" class="mb-xs mt-xs mr-xs btn btn-grey" onclick="document.location='/<?php echo strtolower(\Application::getNameController()); ?>/cadastrar-telefone-util'"><i class="material-icons material-align-icons-button">&#xE145;</i>&nbsp;Novo Telefone</button>
    </div>
</div>



<div class="panel panel-default col-md-6">
  <div class="panel-body">
    <p>Utilize o formul&aacute;rio abaixo para encontrar a pessoa</p>
      <form>
          <div class="row">
                <div class="col-md-12">
                        <div class="form-group">
                            <label class="col-sm-1 control-label">Nome: </label>
                            <div class="col-sm-6">
                                <input type="text" id="nome"  class="form-control">
                            </div>
                        </div>
                </div>
              
          </div>
          <div class="row">
                    <div class="col-md-12 box-button-save">
                            <button type="button" class="mb-xs mt-xs mr-xs btn btn-danger" onclick="buscar()"><i class="material-icons material-align-icons-button">&#xE8B6;</i>&nbsp;Pesquisar</button>
                    </div>
                 
              </div>
        </form>
          </div>
  </div>





<table id="datatable" class="display" cellspacing="0" width="100%">
    <thead>
        <tr>
            <th>Nome</th>
            <th>Telefone 1</th>
            <th>Telefone 2</th>
             <th>E-mail</th>
            <th>Acessar</th>
        </tr>
    </thead>
    <tbody>
        <?php
            if ((is_array($telefones)))
                foreach($telefones as $i => $value)
                {
                    ?>
                    <tr>
                         <td><?php echo $value['nome']; ?></td>
                         <td><?php echo $value['telefone1']; ?></td>
                        <td><?php echo $value['telefone2']; ?></td>
                        <td><?php echo $value['email']; ?></td>
                         <td><a href="/<?php echo strtolower(\Application::getNameController()). '/cadastrar-telefone-util/' .  $value['id'];?>"><i class="material-icons">&#xE254;</i></a></td>
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
                             "sInfoEmpty": "",
                             "sSearch": "Pesquisar:",
                             "sZeroRecords": "Nenhum convênio cadastrador" ,
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
    
    
    
function buscar()
{
    var nome = $('#nome').val();
    var nome = $('#nome').val();
	 $.magnificPopup.open({ 
        items: { src: '#modalSuccess'},
        type: 'inline',
		preloader: false,
		modal: true
                         
       });
    
    $.ajax({
          type: "POST",
          url:  '/<?php echo  strtolower(\Application::getNameController()); ?>/pesquisar-telefone-util/',
          data: '&nome='+nome+'&limit=100000' ,
          dataType: 'json',
          success: function(data){
            $.magnificPopup.close();

            table.clear().draw();
            //table.rows.add($.parseJSON(data)).draw();
              for (var i in data)
              {
                  
                  table.row.add([data[i].nome, data[i].telefone1, data[i].telefone2, data[i].email, '<a href="/<?php echo strtolower(\Application::getNameController()). '/cadastrar-telefone-util/';?>'+ data[i].id + '"><i class="material-icons">&#xE254;</i></a>' ]);
              }
            table.draw(false);
        },
    });
}
    
    
    
</script>