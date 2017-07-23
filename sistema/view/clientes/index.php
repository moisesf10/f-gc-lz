<?php
$clientes = $this->getParams('cliente');
$convenio = $this->getParams('convenio');
$usuarios = $this->getParams('usuarios');
?>

<style>
    .box-button-save {margin-top: 3rem; padding-bottom: 3rem;}
.dataTables_wrapper .material-icons { 
    color: rgb(232, 36, 36); 
    }
</style>
<div class="row">
 <div class="col-md-2 box-button-save">
            <button type="button" class="mb-xs mt-xs mr-xs btn btn-grey" onclick="document.location='/cliente/cadastrar'"><i class="material-icons material-align-icons-button">&#xE145;</i>&nbsp;Novo Cadastro</button>
    </div>
</div>
<div class="panel panel-default">
  <div class="panel-body">
    <p>Utilize o formul&aacute;rio abaixo para encontrar cadastros de clientes</p>
      <form>
          <div class="row">
                <div class="col-md-4">
                        <div class="form-group">
                            <label class="col-sm-4 control-label">CPF: </label>
                            <div class="col-sm-8">
                                <input type="text" id="cpf"  class="form-control">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-4 control-label">Nascido em: </label>
                            <div class="col-sm-8">
                                <input type="text" id="nascimentoinicial"   class="form-control" data-plugin-datepicker data-input-mask="99/99/9999">
                            </div>
                        </div>
                    <div class="form-group">
                         <label class="col-sm-4 control-label">Nome do Usuário</label>
                            <div class="col-md-8">
                                <select data-plugin-selectTwo class="form-control " id="usuario" name="usuario">
                                    <optgroup label="Selecione o nome do usuário">
                                        <option></option>
                                        <?php
                                            if (is_array($usuarios))
                                                foreach($usuarios as $i => $value)
                                                    echo '<option value="'. $value['id'] . '">'. $value['nome'] . '</option>';
                                        ?>

                                    </optgroup>

                                </select>
                            </div>
                    
                    </div>
                </div>
              <div class="col-md-4">
                    <div class="form-group">
                        <label class="col-sm-4 control-label">Nome: </label>
                        <div class="col-sm-8">
                            <input type="text" id="nome"  class="form-control">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-4 control-label">At&eacute; </label>
                        <div class="col-sm-8">
                            <input type="text" id="nascimentofinal"  class="form-control" data-plugin-datepicker data-input-mask="99/99/9999">
                        </div>
                    </div>
                </div>
              <div class="col-md-4">
                    <div class="form-group">
                        <label class="col-sm-4 control-label">Entidade: </label>
                        <div class="col-sm-8">
                            <select id="entidade" class="form-control mb-md">
                                <option></option>
                                <?php 
                                        if (is_array($convenio))
                                            foreach($convenio as $i => $value)
                                            {
                                                $selected = (isset($cliente['convenios'][0]['idConvenio'])  && $cliente['convenios'][0]['idConvenio'] == $value['id']  ) ? 'selected="selected"' : '';
                                                echo '<option '. $selected .' value="'. $value['id'] .'">'. $value['nome'] .'</option>';
                                            }
                                    ?>
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-4 control-label">M&ecirc;s</label>
                        <div class="col-sm-8">
                            <input type="text" id="mes"  class="form-control">
                        </div>
                    </div>
                </div> 
          </div>
          
         
          
          
          <div class="row">
                    <div class="col-md-2 box-button-save">
                            <button type="button" class="mb-xs mt-xs mr-xs btn btn-danger" onclick="buscar()"><i class="material-icons material-align-icons-button">&#xE8B6;</i>&nbsp;Pesquisar</button>
                    </div>
                 
              </div>
        </form>
          </div>
  </div>

<table id="datatable" class="display" cellspacing="0" width="100%">
    <thead>
        <tr>
            <th>Nome</th>
            <th>CPF</th>
            <th>Apelido</th>
            <th>Entidade</th>
            <th>Nome Arquivo</th>
            <th>Data Importação</th>
            <th>Acessar</th>
        </tr>
    </thead>
    <tbody>
        <?php
            if (is_array($clientes))
                foreach($clientes as $i => $value)
                { 
                    $entidades = array();
                    if (is_array($value['convenios']))
                        foreach($value['convenios'] as $j => $conv)
                            if (array_search($conv['nomeConvenio'], $entidades)  === false  )
                                array_push($entidades, $conv['nomeConvenio']);
            ?>
                    <tr>
                        <td><?php echo $value['dados']['nomeCliente']; ?></td>
                        <td><?php echo $value['dados']['cpf']; ?></td>
                        <td><?php echo $value['dados']['apelido']; ?></td>
                        <td><?php echo implode(',', $entidades); ?></td>
                        <td><?php echo $value['dados']['nomeArquivo']; ?></td>
                        <td><?php echo $value['dados']['dataImportacao']; ?></td>
                        <td><a href="/<?php echo strtolower(\Application::getNameController()). '/cadastrar/' . preg_replace('/[\.-]/', '', $value['dados']['cpf']);?>"><i class="material-icons">&#xE254;</i></a></td>
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


<script src="/library/jsvendor/select2/select2.min.js"></script>
<script src="/library/jsvendor/bootstrap-datepicker/js/bootstrap-datepicker.js" ></script>
<script src="/library/jsvendor/magnific-popup/magnific-popup.js"></script>
<script>
    $.getCSS('/library/jsvendor/select2/select2.css');
    $.getCSS('/library/jsvendor/select2/select2.custom.css');
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
});
    
    
$.getScript('/library/jsvendor/jquery-masked-input/jquery.maskedinput.min.js', function(){
    $('#cpf').mask('999.999.999-99',{placeholder:"___.___.___-__", autoclear: true});
    $('#mes').mask('99',{ autoclear: false});
    $('#box-telefones div.panel-body input.fone').mask('(99)9999-99999?',{placeholder:"(__)____-_____", autoclear: false});
    $('.nb').mask('9999999999');
});
    
    
    
function buscar()
{
    var cpf = $('#cpf').val();
    var nome = $('#nome').val();
    var convenio = $('#entidade').val();
    var nascimentoinicial = $('#nascimentoinicial').val();
    var nascimentofinal = $('#nascimentofinal').val();
    var mes = $('#mes').val();
    var usuario = $('#usuario').val();
	
	$.magnificPopup.open({ 
        items: { src: '#modalSuccess'},
        type: 'inline',
		preloader: false,
		modal: true
                         
                         });
    
    $.ajax({
          type: "POST",
          url:  '/<?php echo  strtolower(\Application::getNameController())?>/carregar-pesquisa/',
          data: '&cpf='+cpf+'&nome='+nome+'&convenio='+convenio+'&nascimentoinicial='+nascimentoinicial+'&nascimentofinal='+nascimentofinal+ '&mes='+mes+'&usuario='+usuario+'&limit=100000' ,
          dataType: 'json',
          success: function(data){
            
			$.magnificPopup.close();
            table.clear().draw();
            //table.rows.add($.parseJSON(data)).draw();
              for (var i in data)
                    table.row.add([data[i].nomeCliente, data[i].cpf, data[i].apelido, data[i].convenios, data[i].nomeArquivo, data[i].dataImportacao, '<a href="/<?php echo strtolower(\Application::getNameController()). '/cadastrar/';?>'+ data[i].cpf.replace(/[\.-]/gi,'') + '"><i class="material-icons">&#xE254;</i></a>' ]);
            
              table.draw(false);
              
            //table.ajax.url(data).load();
            //table.fnClearTable();
          //  table.fnAddData(data);
        },
    });
}
    
</script>