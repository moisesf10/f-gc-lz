<?php
$tabela = $this->getParams('tabela');
?>

<div class="row">
 <div class="col-md-2 box-button-save">
            <button type="button" class="mb-xs mt-xs mr-xs btn btn-grey" onclick="document.location='/administracao/cadastrar-tabela'"><i class="material-icons material-align-icons-button">&#xE145;</i>&nbsp;Nova Tabela</button>
    </div>
</div>


<table id="datatable" class="display" cellspacing="0" width="100%">
    <thead>
        <tr>
            <th>ID</th>
            <th>Tabela</th>
            <th>Banco</th>
            <th>Convênio</th>
            <th>Acessar</th>
        </tr>
    </thead>
    <tbody>
        <?php
            if (is_array($tabela))
                foreach($tabela as $i => $value)
                { 
                  /*  $entidades = array();
                    if (is_array($value['convenios']))
                        foreach($value['convenios'] as $j => $conv)
                            if (array_search($conv['nomeConvenio'], $entidades)  === false  )
                                array_push($entidades, $conv['nomeConvenio']);*/
            ?>
                    <tr>
                        <td><?php echo $value['idTabela']; ?></td>
                        <td><?php echo $value['nomeTabela']; ?></td>
                        <td><?php echo $value['nomeBanco']; ?></td>
                        <td><?php echo $value['nomeConvenio']; ?></td>
                        <td><a href="/<?php echo strtolower(\Application::getNameController()). '/cadastrar-tabela/' . preg_replace('/[\.-]/', '', $value['idTabela']);?>"><i class="material-icons">&#xE254;</i></a></td>
                    </tr>
        <?php } ?>
       
    </tbody>
</table>




<script>
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
                        "BLengthChange" : true,
                         "iDisplayLength": 15,
                         "bInfo": true,
                         "bSort": true,  
                         "bLengthChange": false,
                         "oLanguage": {
                             "oPaginate": {
                                 "sNext": "Pr&oacute;ximo",
                                 "sPrevious": "Anterior"

                              },  
                             "sInfoEmpty": "",
                             "sSearch": "Pesquisar:",
                             "sZeroRecords": "Nenhum registro encontrado" ,
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