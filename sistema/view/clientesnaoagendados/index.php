<?php
$clientes = $this->getParams('clientesnaoagendados');

?>



<section class="panel">
        <header class="panel-heading">
            <h2 class="panel-title">Clientes Não Agendados</h2>
        </header>
        <div class="panel-body">

            <table id="datatable" class="display" cellspacing="0" width="100%">
                <thead>
                    <tr>
                        <th>CPF</th>
                        <th>Nome</th>
                        <th>Cadastrado em</th>
                        <th>Usuário</th>
                       
                    </tr>
                </thead>
                <tbody>
                    <?php
                        if ((is_array($clientes)))
                            foreach($clientes as $i => $value)
                            {
                                ?>
                                <tr>
                                     <td><?php echo $value['cpf']; ?></td>
                                     <td><?php echo $value['nomeCliente']; ?></td>
                                     <td><?php echo $value['created']; ?></td>
                                     <td><?php echo $value['nomeUsuario']; ?></td>
                                     
                                </tr>        

                        <?php
                            }
                    ?>

                </tbody>
            </table>
    </div>
</section>



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
                         "sZeroRecords": "Nenhum banco cadastrador" ,
                         "sInfo": "Mostrando página _PAGE_ de _PAGES_",
                         "sInfoFiltered": "(Filtrado _MAX_ do total)"
                      },
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