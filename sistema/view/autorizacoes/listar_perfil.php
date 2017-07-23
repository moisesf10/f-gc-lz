<?php
$perfil = $this->getParams('perfil');

?>

<style>
.material-icons { 
    color: rgb(232, 36, 36); 
    }
</style>

<h5>Pesquisar Perfil de Autoriza&ccedil;&atilde;o</h5>
<hr>
<p>Escolha o perfil que deseja alterar as permiss&ccedil;&otilde;s de acesso</p>

<table id="datatable" class="display" cellspacing="0" width="100%">
    <thead>
        <tr>
            <th>#</th>
            <th>Nome do Grupo</th>
            <th>Acessar</th>
        </tr>
    </thead>
    <tbody>
        <?php
            if (is_array($perfil))
                foreach($perfil as $i => $value)
                {
                    ?>
                    <tr>
                        <td><?php echo $value['id']; ?></td>
                        <td><?php echo $value['descricao'] ?></td>
                        <td><a href="/sistema/autorizar-perfil/<?php echo $value['id']; ?>"><i class="material-icons">&#xE254;</i></a></td>
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
             "iDisplayLength": 5,
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
              } 
        } );
    
    
    
    
}); // fim $.GetScript
</script>