<?php
$senhaBanco = $this->getParams('senhabanco');
$bancos = $this->getParams('bancos');
?>
<style>
    .panel{background-color: #fafafa; border: 1px solid #e0e0e0; padding-top: 3rem; margin-top: 2rem;}
    

</style>

<div class="row">
 <div class="col-md-4 box-button-save">
            <button type="button" class="mb-xs mt-xs mr-xs btn btn-grey" onclick="document.location='/<?php echo strtolower(\Application::getNameController()); ?>/cadastrar-senha-bancaria'"><i class="material-icons material-align-icons-button">&#xE145;</i>&nbsp;Novo Cadastro de Senhas</button>
    </div>
</div>

<form id="pesquisa">
    <section class="panel col-md-6">
        <div class="panel-body">
            <p>Utilize o formul&aacute;rio abaixo para encontrar bancos</p>
        </div>
        <div class="panel-body">
            <label class="col-md-2">Banco</label>
            <div class="col-md-6">
                <select class="form-control" id="banco" name="banco">
                        <option></option>
                        <?php
                        if (is_array($bancos))
                            foreach($bancos as $i => $value)
                                echo '<option value="' . $value['id'] . '">'. $value['nome'] . '</option>';
                        ?>
                    </select>
            </div>

        </div>
        <div class="panel-body">
            <label class="col-md-2">Promotora</label>
            <div class="col-md-6">
                        <input type="text" class="form-control" id="promotora" name="promotora" />
            </div>
        </div>
        <div class="panel-body">
             <button type="button" class="mb-xs mt-xs mr-xs btn btn-danger" onclick="buscar()"><i class="material-icons material-align-icons-button">&#xE8B6;</i>&nbsp;Pesquisar</button>
        </div>



    </section>
</form>




<table id="datatable" class="display" cellspacing="0" width="100%">
    <thead>
        <tr>
            <th>#</th>
            <th>Banco</th>
            <th>Promotora</th>
            <th>Acessar</th>
        </tr>
    </thead>
    <tbody>
        <?php
            if ((is_array($senhaBanco)))
                foreach($senhaBanco as $i => $value)
                {
                    ?>
                    <tr>
                        <td><?php echo $value['id']; ?></td>
                         <td><?php echo $value['nomeBanco']; ?></td>
                         <td><?php echo $value['nomePromotora']; ?></td>
                         <td><a href="/<?php echo strtolower(\Application::getNameController()). '/cadastrar-senha-bancaria/' .  $value['id'];?>"><i class="material-icons">&#xE254;</i></a></td>
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
$.getCSS('/library/jsvendor/magnific-popup/magnific-popup.css');
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
                 "sZeroRecords": "Nenhum convênio cadastrador" ,
                 "sInfo": "Mostrando página _PAGE_ de _PAGES_",
                 "sInfoFiltered": "(Filtrado _MAX_ do total)"
              } 
        } );
}); // fim $.GetScript    
    
    
    
function buscar()
{
    var nome = $('#nome').val();
    var nome = $('#nome').val();

    
    $.ajax({
          type: "POST",
          url:  '/<?php echo  strtolower(\Application::getNameController()); ?>/pesquisar-telefone-util/',
          data: '&nome='+nome+'&limit=100000' ,
          dataType: 'json',
          success: function(data){
            

            table.clear().draw();
            //table.rows.add($.parseJSON(data)).draw();
              for (var i in data)
              {
                  
                  table.row.add([data[i].id, data[i].nome, data[i].telefone1, data[i].telefone2, data[i].email, '<a href="/<?php echo strtolower(\Application::getNameController()). '/cadastrar-telefone-util/';?>'+ data[i].id + '"><i class="material-icons">&#xE254;</i></a>' ]).draw();
              }
            
        },
    });
}

    
    
function buscar()
{
    var dados = $('#pesquisa').serialize();
   $.magnificPopup.open({ 
        items: { src: '#modalSuccess'},
        type: 'inline',
		preloader: false,
		modal: true
                         
       });
	   
    $.ajax({
          type: "POST",
          url:  '/<?php echo  strtolower(\Application::getNameController()); ?>/pesquisar-senhas-bancarias/',
          data: dados ,
          dataType: 'json',
          success: function(data){
            $.magnificPopup.close();
             
            table.clear().draw();
            //table.rows.add($.parseJSON(data)).draw();
              for (var i in data)
              {
                                    
                  table.row.add([data[i].id, data[i].nomeBanco, data[i].nomePromotora, '<a href="/cadastrosbasicos/cadastrar-senha-bancaria/'+ data[i].id + '"><i class="material-icons">&#xE254;</i></a>'     
                                ]);
              }
            table.draw(false);
            
        },
    });
}   
    
    
</script>