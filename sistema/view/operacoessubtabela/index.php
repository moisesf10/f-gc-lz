<?php
$operacoes = $this->getParams('operacoes');

?>
<style>
    .box-button-save {margin-top: 3rem; padding-bottom: 3rem;}

</style>
<div class="row">
 <div class="col-md-2 box-button-save">
            <button type="button" class="mb-xs mt-xs mr-xs btn btn-grey" onclick="document.location='/<?php echo strtolower(\Application::getNameController()); ?>/cadastrar-operacao-subtabela'"><i class="material-icons material-align-icons-button">&#xE145;</i>&nbsp;Nova Operação</button>
    </div>
</div>


<table id="datatable" class="display" cellspacing="0" width="100%">
    <thead>
        <tr>
            <th>#</th>
            <th>Nome</th>
            <th>Acessar</th>
        </tr>
    </thead>
    <tbody>
        <?php
            if ((is_array($operacoes)))
                foreach($operacoes as $i => $value)
                {
                    ?>
                    <tr>
                         <td><?php echo $value['id']; ?></td>
                         <td><?php echo $value['nome']; ?></td>
                         <td><a href="/<?php echo strtolower(\Application::getNameController()). '/cadastrar-operacao-subtabela/' .  $value['id'];?>"><i class="material-icons">&#xE254;</i></a></td>
                    </tr>        
        
            <?php
                }
        ?>
        
    </tbody>
</table>



<script>


    $.getCSS('/library/jsvendor/datatables/css/jquery.dataTables.min.css');
// Carregar JS de forma sincronizada. Desta forma primeiro carrega $.getScript e depois $.ajax como callback
$.getScript( "/library/jsvendor/datatables/js/jquery.dataTables.min.js", function(  ) {
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
                 "sZeroRecords": "Nenhuma operação cadastrada" ,
                 "sInfo": "Mostrando página _PAGE_ de _PAGES_",
                 "sInfoFiltered": "(Filtrado _MAX_ do total)"
              } 
        } );
}); // fim $.GetScript    
    
</script>