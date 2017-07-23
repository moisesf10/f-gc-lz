<?php
$feriados = $this->getParams('feriados');



?>

<style>
    .box-button-save {margin-top: 3rem; padding-bottom: 3rem;}
.dataTables_wrapper .material-icons { 
    color: rgb(232, 36, 36); 
    }
</style>
<div class="row">
 <div class="col-md-2 box-button-save">
            <button type="button" class="mb-xs mt-xs mr-xs btn btn-grey" onclick="document.location='/sistema/cadastrar-feriado'"><i class="material-icons material-align-icons-button">&#xE145;</i>&nbsp;Cadastrar Feriado</button>
    </div>
</div>


<table id="datatable" class="display" cellspacing="0" width="100%">
    <thead>
        <tr>
            <th>#</th>
            <th>Data</th>
            <th>Descrição</th>
            <th>Alterar</th>
        </tr>
    </thead>
    <tbody>
        <?php
        if (is_array($feriados))
            foreach($feriados as $i => $value)
            { ?>
                <tr>
                    <td><?php echo $value['id']; ?></td>
                    <td><?php echo $value['data']; ?></td>
                    <td><?php echo $value['descricao']; ?></td>
                    <td><a href="/sistema/cadastrar-feriado/<?php echo $value['id']; ?>"><i class="material-icons">&#xE254;</i></a></td>
                </tr>
      <?php } ?>
       
    </tbody>
</table>



<script>
$.getCSS('/library/jsvendor/datatables/css/jquery.dataTables.min.css');
$.getScript( "/library/jsvendor/datatables/js/jquery.dataTables.min.js", function(  ) {
     /**
     * Inicia DataTable... CALLBACK executado após baixar dataTables.min.js
     * 
     **/
    
    $('#datatable').DataTable({
           "bStateSave": false,
            "BLengthChange" : true,
             "iDisplayLength": 10,
             "bInfo": true,
             "bSort": false,  
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
              } 
        } );
    
    
    
    
}); // fim $.GetScript
</script>