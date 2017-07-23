<?php
$subtabelas = $this->getParams('subtabelas');
$convenios =  $this->getParams('convenios');
$bancos =  $this->getParams('bancos');
//echo '<pre>';var_dump($convenios); echo '</pre>';
?>
<style>
.panel{background-color: #fafafa; border: 1px solid #e0e0e0; padding-top: 3rem; }
</style>

<div class="row">
 <div class="col-md-2 box-button-save">
            <button type="button" class="mb-xs mt-xs mr-xs btn btn-grey" onclick="document.location='/administracao/cadastrar-subtabela'"><i class="material-icons material-align-icons-button">&#xE145;</i>&nbsp;Nova Subtabela</button>
    </div>
</div>
<form id="pesquisa">
    <section class="panel col-md-6">

            <div class="panel-body">
                <div class="row">Utilize os campos abaixo para pesquisar subtabelas</div>
            </div>
        <div class="panel-body">
            <label class="col-md-3">Banco</label>
            <div class="col-md-8">
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
            <label class="col-md-3">Entidade/Convênio</label>
            <div class="col-md-8">
                <select class="form-control" id="convenio" name="convenio">
                    <option></option> 
                    <?php
                    if (is_array($convenios))
                        foreach($convenios as $i => $value)
                            echo '<option value="' . $value['id'] . '">'. $value['nome'] . '</option>';
                    ?>
                </select>
            </div>
        </div>
        <div class="panel-body">
            <div class="form-group">
                            <label class="col-md-3 control-label">Vigência</label>
                            <div class="col-md-8">
                                <div class="input-daterange input-group" data-plugin-datepicker>
                                    <span class="input-group-addon">
                                        <i class="fa fa-calendar"></i>
                                    </span>
                                    <input type="text" class="form-control" name="datainicial" id="datainicial">
                                    <span class="input-group-addon">Até</span>
                                    <input type="text" class="form-control" name="datafinal" id="datafinal">
                                </div>
                            </div>
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
            <th>ID</th>
            <th>Tabela</th>
            <th>Banco</th>
            <th>Prazos</th>
            <th>Coeficientes</th>
            <th>Vig&ecirc;ncia</th>
            <?php
                  if (\Application::isAuthorized(ucfirst(strtolower(\Application::getNameController())) , 'subtabelas', 'escrever') )
                        echo '<th>Editar</th>';
            ?>
            <!-- <th>Visualizar</th> -->
        </tr>
    </thead>
    <tbody>
        <?php
            if (is_array($subtabelas))
                foreach($subtabelas as $i => $value)
                { 
                    $prazos = array();
                    $coeficientes = array();
                    if (is_array($value['prazos']))
                        foreach($value['prazos'] as $j => $prazo)
                        {
                            array_push($prazos, ((empty($prazo['prazo'])) ? '' : $prazo['prazo'] . 'x')    );
                            array_push($coeficientes, $prazo['coeficiente']);
                        }
            ?>
                    <tr>
                        <td><?php echo $value['id']; ?></td>
                        <td><?php echo $value['nomeTabela']; ?></td>
                        <td><?php echo $value['nomeBanco']; ?></td>
                        <td><?php echo implode(', ', $prazos); ?></td>
                        <td><?php echo implode(', ', $coeficientes); ?></td>
                        <td><?php echo $value['inicioVigencia']. ' à '. $value['fimVigencia']  ?></td>
                        <?php
                            if (\Application::isAuthorized(ucfirst(strtolower(\Application::getNameController())) , 'subtabelas', 'escrever') )
                              { ?>
                        <td><a href="/<?php echo strtolower(\Application::getNameController()). '/cadastrar-subtabela/' . $value['id'];?>"><i class="material-icons">&#xE254;</i></a></td> 
                        <?php } ?>
                        <!--  <td><a href="/<?php //echo strtolower(\Application::getNameController()). '/visualizar-subtabela/' . $value['id'];?>"><i class="material-icons">&#xE890;</i></a></td> -->
                    </tr>
        <?php } ?>
       
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



<script src="/library/jsvendor/bootstrap-datepicker/js/bootstrap-datepicker.js"></script>
<script src="/library/jsvendor/magnific-popup/magnific-popup.js"></script>
<script>
$.getCSS('/library/jsvendor/datatables/css/jquery.dataTables.min.css');
$.getCSS('/library/jsvendor/datatables/css/responsive.dataTables.min.css');
$.getCSS('/library/jsvendor/magnific-popup/magnific-popup.css');
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
})
    
    
    
    
     
function buscar()
{
    var dados = $('#pesquisa').serialize();
   // console.log(dados);

    $.magnificPopup.open({ 
        items: { src: '#modalSuccess'},
        type: 'inline',
		preloader: false,
		modal: true
                         
       });
	   
    $.ajax({
          type: "POST",
          url:  '/<?php echo  strtolower(\Application::getNameController()); ?>/pesquisar-subtabela/',
          data: dados ,
          dataType: 'json',
          success: function(data){
            $.magnificPopup.close();
             
            table.clear().draw();
            //table.rows.add($.parseJSON(data)).draw();
              for (var i in data)
              {
                  
                   var coeficientes = [];
                  var prazos = [];
                  for ( var a in data[i].prazos)
                    {
                        prazos.push(data[i].prazos[a].prazo);
                        coeficientes.push(data[i].prazos[a].coeficiente);
                    }
                  
                  table.row.add([data[i].id, data[i].nomeTabela, data[i].nomeBanco, prazos.join(','), coeficientes.join(','), data[i].inicioVigencia + ' à ' + data[i].fimVigencia ,
                            '<a href="/administracao/cadastrar-subtabela/'+ data[i].id + '"><i class="material-icons">&#xE254;</i></a>'     
                                ]);
              }
              
              table.draw(false);
            
        },
    });
}   
    
</script>