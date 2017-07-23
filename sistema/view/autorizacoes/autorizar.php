<?php
$autorizacoes = $this->getParams('autorizacoes');
$recursos = $this->getParams('recursos');
$perfil = $this->getParams('infoperfil');
   //echo '<pre>'; var_dump($perfil); echo '</pre>';


// sai da página se não tiver recuperado informações do perfil
if ($perfil === null || count($perfil) < 1)
    \Application::print404();
?>
<style>
  
   
    
    
</style>
<h5>Autorizar Grupo</h5>
<hr>


<section class="panel">
        <header class="panel-heading">
            <h2 class="panel-title">Autoriza&ccedil;&otilde;es do grupo - <?php echo ucfirst(strtolower($perfil[0]['descricao'])); ?> </h2>
        </header>
        <div class="panel-body">
            
            <div class="row">
                <div class="col-md-2 box-button-save">
                <button type="button" class="mb-xs mt-xs mr-xs btn btn-danger" onclick="permitirTudo()"><i class="material-icons material-align-icons-button">&#xE5CA;</i>&nbsp;Permitir Tudo</button>
                </div>

                <div class="col-md-2 box-button-save">
                <button type="button" class="mb-xs mt-xs mr-xs btn btn-grey" onclick="negarTudo()"><i class="material-icons material-align-icons-button">&#xE14B;</i>&nbsp;Negar Tudo</button>
                </div>
            </div>
            
            <table id="datatable" class="display" cellspacing="0" width="100%">
                    <thead>
                        <tr>
                            <th>Nome do Recurso</th>
                            <th>Grupo de Recurso</th>
                            <th>Descri&ccedil;&atilde;o</th>
                            <th>Ler</th>
                            <th>Escrever</th>
                            <th>Remover</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                            if (is_array($recursos))
                                foreach($recursos as $i => $value)
                                { 
                                    // verifica a permissão do perfil em cada recurso
                                    $ler = ''; $escrever = ''; $remover = '';
                                    foreach ($autorizacoes as $d => $aut)
                                        if ($aut['idRecurso'] == $value['idRecurso'])
                                        {
                                            $ler = ($aut['ler']) ? 'checked' : '';
                                            $escrever = ($aut['escrever']) ? 'checked' : '';
                                            $remover = ($aut['remover']) ? 'checked' : '';
                                        }
                         ?>
                        <tr>
                            <td><?php echo $value['nomeRecurso']; ?></td>
                            <td><?php echo $value['descricaoGrupoRecurso']; ?></td>
                            <td><?php echo $value['descricaoRecurso']; ?></td>
                            <td><input class="toggle-button" type="checkbox" <?php echo $ler; ?> data-onstyle="danger" data-on="Sim" data-off="Não" data-size="small" data-recurso="<?php echo $value['idRecurso']; ?>" data-acao="ler">
</td>
                            <td><input class="toggle-button" type="checkbox" <?php echo $escrever; ?>  data-onstyle="danger" data-on="Sim" data-off="Não" data-size="small" data-recurso="<?php echo $value['idRecurso']; ?>" data-acao="escrever"></td>
                            <td><input class="toggle-button" type="checkbox"  <?php echo $remover; ?> data-onstyle="danger" data-on="Sim" data-off="Não" data-size="small" data-recurso="<?php echo $value['idRecurso']; ?>" data-acao="remover"></td>
                        </tr>
                        
                        <?php } ?>
                        
                </tbody>
            </table>
    </div>
</section>







<script>
$.getCSS('/library/jsvendor/datatables/css/jquery.dataTables.min.css');
$.getCSS('/library/jsvendor/datatables/css/responsive.dataTables.min.css');
    
$.getScript( "/library/jsvendor/datatables/js/jquery.dataTables.min.js", function(  ) {
     /**
     * Inicia DataTable... CALLBACK executado após baixar dataTables.min.js
     * 
     **/
    
    $.getScript("/library/jsvendor/datatables/js/dataTables.responsive.min.js", function(){
        $('#datatable').DataTable({
           "bStateSave": false,
            "BLengthChange" : true,
             "iDisplayLength": 60,
             "bInfo": false,
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
        
    });
    
    
    
    
}); // fim $.GetScript
    

    
$.getCSS('/library/jsvendor/bootstrap-toggle/css/bootstrap-toggle.min.css');
$.getScript('/library/jsvendor/bootstrap-toggle/js/bootstrap-toggle.min.js', function(){
    $('.toggle-button').bootstrapToggle();
});
    
var request = {};
id = <?php echo $perfil[0]['id']; ?>;
$('input.toggle-button').change(function(){
   
    request.recurso = $(this).data('recurso');
    
 //  console.log(  $(this).parent().parent().parent().find('input.toggle-button[data-acao="ler"]').prop('checked') );
 //  return false;
    request.ler = $(this).parent().parent().parent().find('input.toggle-button[data-acao="ler"]').prop('checked');
    request.escrever = $(this).parent().parent().parent().find('input.toggle-button[data-acao="escrever"]').prop('checked');
    request.remover = $(this).parent().parent().parent().find('input.toggle-button[data-acao="remover"]').prop('checked');
    

    $.ajax({
        type: "POST",
        url: '<?php echo '/'. strtolower(\Application::getNameController()) . '/salvar-autorizacao/'; ?>',
        cache: false,
        dataType: "json",
        data: 'id='+id + '&dados='+JSON.stringify(request),
        success: function(json){
            if (json.success)
                document.location.reload();
            else
            {
                alert('Não foi possível realizar a alteração\nMotivo: ' + json.message);
                document.location.reload();
            }
        }
    });

    
});
    
function permitirTudo()
{
    if(confirm('Tem certeza que deseja permirtir tudo para o perfil "<?php echo $perfil[0]['descricao']; ?>"'))
        $('.toggle-button').bootstrapToggle('on');
}
    
function negarTudo()
{
    if(confirm('Tem certeza que deseja negar todas as opções para o perfil "<?php echo $perfil[0]['descricao']; ?>"'))
        $('.toggle-button').bootstrapToggle('off');
}
    


</script>