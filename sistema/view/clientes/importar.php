<?php
$logs = $this->getParams('logs');

?>

<style>
.btn-enviar {
    vertical-align: middle;
    line-height: 21px;
    margin-left: -5px;
    padding: 5px;
}
</style>

<div class="form-group">
    <div class="row">
            <label class="col-md-3 control-label">Escolha o arquivo excel a ser importado</label>
        </div>
        <div class="row">
                <div class="col-md-6">
                    <div class="fileupload fileupload-new" data-provides="fileupload">
                        <div class="input-append">
                            <div class="uneditable-input">
                                <i class="fa fa-file fileupload-exists"></i>
                                <span class="fileupload-preview"></span>
                            </div>
                            <span class="btn btn-default btn-file">
                                <span class="fileupload-exists">Selecionar</span>
                                <span class="fileupload-new">Escolher Arquivo</span>
                                <input type="file" id="file" accept="application/vnd.openxmlformats-officedocument.spreadsheetml.sheet, application/vnd.ms-excel" />
                            </span>
                            <a href="#" class="btn btn-default fileupload-exists" data-dismiss="fileupload">Remover</a>
                            <button type="button" class="btn btn-default fileupload-exists btn btn-grey btn-enviar" onclick="enviarArquivo()"><i class="material-icons material-align-icons-button">&#xE163;</i>&nbsp;Importar</button>
                        </div>
                    </div>
                </div>
              
                
            
            </div>
</div>



<table id="datatable" class="display" cellspacing="0" width="100%">
    <thead>
        <tr>
            <th>#</th>
            <th>Data Importação</th>
             <th>Nome Arquivo</th>
            <th>Acessar</th>
        </tr>
    </thead>
    <tbody>
        <?php
            if ((is_array($logs)))
                foreach($logs as $i => $value)
                {
                    ?>
                    <tr>
                         <td><?php echo $value['id']; ?></td>
                         <td><?php echo $value['created']; ?></td>
                        <td><?php echo $value['nomeArquivo']; ?></td>
                         <td><a target="_blank" href="/arquivos/clientes/<?php echo $value['nomeArquivoSistema'];?>"><i class="material-icons">&#xE254;</i></a></td>
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



<script src="/library/javascript/clientes/importar.js"></script>
<script src="/library/jsvendor/bootstrap-fileupload/bootstrap-fileupload.min.js"></script>
<script src="/library/jsvendor/magnific-popup/magnific-popup.js"></script>
<script>
$.getCSS('/library/jsvendor/magnific-popup/magnific-popup.css');
$.getCSS('/library/jsvendor/bootstrap-fileupload/bootstrap-fileupload.min.css');
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
                 "sZeroRecords": "Nenhum arquivo importado" ,
                 "sInfo": "Mostrando página _PAGE_ de _PAGES_",
                 "sInfoFiltered": "(Filtrado _MAX_ do total)"
              } 
        } );
}); // fim $.GetScript   
    
    
 file = null;   
$(function(){
   
    
    $('#file').change(function(e){
         
        file = (typeof(e.target.files[0]) == 'undefined') ? null : e.target.files[0];
        
    });
    
    
});
    
</script>