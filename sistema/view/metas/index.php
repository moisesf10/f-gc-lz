<?php
$metas = $this->getParams('metas');

?>
<style>
    .box-button-save {margin-top: 3rem; padding-bottom: 3rem;}

</style>
<div class="row">
 <div class="col-md-2 box-button-save">
            <button type="button" class="mb-xs mt-xs mr-xs btn btn-grey" onclick="document.location='/<?php echo strtolower(\Application::getNameController()); ?>/cadastrar-meta'"><i class="material-icons material-align-icons-button">&#xE145;</i>&nbsp;Nova Meta</button>
    </div>
</div>


<table id="datatable" class="display" cellspacing="0" width="100%">
    <thead>
        <tr>
            <th>#</th>
            <th>Cadastrado em</th>
            <th>Vendedor/Grupo</th>
            <th>Data Inicio</th>
            <th>Data Fim</th>
            <th>Tipo da Meta</th>
            <th>Valor Meta</th>
            <th>Editar</th>
        </tr>
    </thead>
    <tbody>
        <?php
            if ((is_array($metas)))
                foreach($metas as $i => $value)
                {
                    ?>
                    <tr>
                         <td><?php echo $value['id']; ?></td>
                         <td><?php echo $value['created']; ?></td>
                        <td><?php echo (empty($value['nomeUsuario'])) ? $value['nomeGrupo'] : $value['nomeUsuario'] ; ?></td>
                        <td><?php echo $value['dtInicio']; ?></td>
                        <td><?php echo $value['prazo']; ?></td>
                        <td><?php echo $value['tipoMeta']; ?></td>
                        <td><?php echo 'R$ '. \Gauchacred\library\php\Utils::numberToMoney($value['valor']); ?></td>
                         <td><a href="/<?php echo strtolower(\Application::getNameController()). '/cadastrar-meta/' .  $value['id'];?>"><i class="material-icons">&#xE254;</i></a></td>
                    </tr>        
        
            <?php
                }
        ?>
        
    </tbody>
</table>



<script>


    $.getCSS('/library/jsvendor/datatables/css/jquery.dataTables.min.css');
    $.getCSS('/library/jsvendor/datatables/css/responsive.dataTables.min.css');
// Carregar JS de forma sincronizada. Desta forma primeiro carrega $.getScript e depois $.ajax como callback
$.getScript( "/library/jsvendor/datatables/js/jquery.dataTables.min.js", function(  ) {
     /**
     * Inicia DataTable... CALLBACK executado após baixar dataTables.min.js
     * 
     **/
    
    $.getScript("/library/jsvendor/datatables/js/dataTables.responsive.min.js", function(){
                 /**
                 * Inicia DataTable... CALLBACK executado após baixar dataTables.min.js
                 * 
                 **/
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
    
</script>