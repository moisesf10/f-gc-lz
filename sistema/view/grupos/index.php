<?php
$bancos = $this->getParams('bancos');
$entidades = $this->getParams('entidades');
?>
<style>
    .box-button-save {margin-top: 3rem; padding-bottom: 3rem;}

</style>
<div class="row">
 <div class="col-md-2 box-button-save">
            <button type="button" class="mb-xs mt-xs mr-xs btn btn-grey" onclick="document.location='/<?php echo strtolower(\Application::getNameController()); ?>/cadastrar-grupo-usuario'"><i class="material-icons material-align-icons-button">&#xE145;</i>&nbsp;Novo Grupo</button>
    </div>
</div>


<!--
<div class="panel panel-default">
  <div class="panel-body">
    <p>Utilize o formul&aacute;rio abaixo para encontrar roteiros cadastrados</p>
      <form id="formPesquisa">
          <div class="row">
                <div class="col-md-4">
                        <div class="form-group">
                            <label class="col-sm-4 control-label">Banco: </label>
                            <div class="col-sm-8">
                                <select name="idBanco" id="idBanco">
                                    <option></option>
                                    <?php 
                                        
                                    //    if (is_array($bancos))
                                    //        foreach($bancos as $i => $value)
                                     //           echo '<option value="'. $value['id'] .'" '. $selected .'>'. $value['codigo']. ' - '. $value['nome'] .'</option>';
                                            
                                                
                                    ?>
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-4 control-label">Entidade: </label>
                            <div class="col-sm-8">
                                <select name="idEntidade" id="idEntidade">
                                    <option></option>
                                    <?php 
                                //        if (is_array($entidades))
                               //             foreach($entidades as $i => $value)
                                  //               echo '<option value="'. $value['id'] .'" '. $selected .'>'. $value['nome'] .'</option>';
                                            
                                               
                                        ?>
                                </select>
                            </div>
                        </div>
                </div>
             
          </div>
          <div class="row">
                    <div class="col-md-2 box-button-save">
                            <button type="button"  class="mb-xs mt-xs mr-xs btn btn-danger" onclick="pesquisar()"><i class="material-icons material-align-icons-button">&#xE8B6;</i>&nbsp;Pesquisar</button>
                    </div>
                 
              </div>
        </form>
          </div>
  </div>

-->

<table id="datatable" class="display" cellspacing="0" width="100%">
    <thead>
        <tr>
            <th>#</th>
            <th>Criado em</th>
            <th>Nome</th>
            <th>Acessar</th>
            <th>Atribuir Usuários</th>
        </tr>
    </thead>
    <tbody>
        
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
   
     table = $('#datatable').DataTable({
        "ajax": {
            "url": "/<?php echo strtolower(\Application::getNameController()); ?>/pesquisar-grupo-usuario/",
            "cache": true,
         },
        "columns": [
            { "data": "id" },
            { "data": "created" },
            { "data": "nome" },
            { "data": "id",
                    "fnCreatedCell": function (nTd, sData, oData, iRow, iCol) {
                        $(nTd).html('<a class="link-visualizar" href="/<?php echo strtolower(\Application::getNameController()); ?>/cadastrar-grupo-usuario/'+  oData.id+'"><i class="material-icons">&#xE254;</i></a>');
                    }
            },
            { "data": "id",
                    "fnCreatedCell": function (nTd, sData, oData, iRow, iCol) {
                        $(nTd).html('<a class="link-visualizar" href="/<?php echo strtolower(\Application::getNameController()); ?>/atribuir-usuario-grupo/'+  oData.id+'"><i class="material-icons">&#xE7FD;</i></a>');
                    }
            },
           
        ],
        "bStateSave": true,
		"BLengthChange" : true,
		 "iDisplayLength": 10,
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
    
    
function pesquisar()
{
    $.ajax({
          type: "POST",
          url:  "/<?php echo strtolower(\Application::getNameController()); ?>/pesquisar-roteiro/",
          data: $('#formPesquisa').serialize() + '&limit=1000'  ,
          dataType: 'text',
        success: function(data){
            
           //data = data.replace('{"data":','');
          //  data = data.replace(/[}]$/gi,'');
            data = data.replace(/([}])$|({"data"\:)/gi  ,'');
        
            table.clear().draw();
            table.rows.add($.parseJSON(data)).draw();
            
            
            //table.ajax.url(data).load();
            //table.fnClearTable();
          //  table.fnAddData(data);
        },
    });
}
</script>