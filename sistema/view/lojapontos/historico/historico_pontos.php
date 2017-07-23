<?php
$usuarios = $this->getParams('usuarios');


?>

<style>
    .box-button-save {margin-top: 3rem; padding-bottom: 3rem;}
.dataTables_wrapper .material-icons { 
    color: rgb(232, 36, 36); 
    }
</style>


<form name="form1" method="post" action="/relatorios/gerar-comissao-loja/" target="_blank">
<section class="panel">
        <header class="panel-heading">
            <h2 class="panel-title">Histórico de Pontos</h2>
        </header>
        
        
        <div class="panel-body">
                <div class="form-group">
                        <label class="col-md-3 control-label">Usuario</label>
                        <div class="col-md-4">
                            <select data-plugin-selectTwo class="form-control " id="usuario" name="usuario">
                                <optgroup label="Selecione o nome do vendedor">
                                    <option></option>
                                    <?php
                                        if (is_array($usuarios))
                                            foreach($usuarios as $i => $value)
                                                echo '<option value="'. $value['id'] . '">'. ucwords(strtolower($value['nome'])) . '</option>';
                                    ?>
                                    
                                </optgroup>

                            </select>
                        </div>
                    </div>
            
        </div>
        <div class="panel-body">
            <div class="form-group">
                        <label class="col-md-3 control-label">Validade dos Pontos</label>
                        <div class="col-md-4">
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
          <button type="button" class="mb-xs mt-xs mr-xs btn btn-danger" onclick="pesquisar()">Pesquisar</button>
    </div>
    
    <input type="hidden" value="" name="type" id="type">
    
</section>
</form>

<table id="datatable" class="display" cellspacing="0" width="100%">
    <thead>
        <tr>
            <th>#</th>
            <th>Contrato</th>
            <th>Pontos Recebidos</th>
            <th>Pontos Consumidos</th>
            <th>Usuário</th>
            <th>Válido de</th>
            <th>Válido até</th>
            <th>Remover</th>
        </tr>
    </thead>
    <tbody>
       
       
    </tbody>
</table>


<script src="/library/jsvendor/select2/select2.min.js"></script>
<script src="/library/jsvendor/bootstrap-datepicker/js/bootstrap-datepicker.js"></script>



<script>
    $.getCSS('/library/jsvendor/select2/select2.css');
$.getCSS('/library/jsvendor/select2/select2.custom.css');
$.getCSS('/library/jsvendor/datatables/css/jquery.dataTables.min.css');
$.getScript( "/library/jsvendor/datatables/js/jquery.dataTables.min.js", function(  ) {
     /**
     * Inicia DataTable... CALLBACK executado após baixar dataTables.min.js
     * 
     **/
    
   table =  $('#datatable').DataTable({
           "bStateSave": false,
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
    var idUsuario = $('#usuario').val();
    var dataInicial = $('#datainicial').val();
    var dataFinal = $('#datafinal').val();
    
    $.ajax({
        type: "POST",
        url: '/lojapontos/pesquisar-historico-pontos/',
        cache: false,
        dataType: "json",
       cache: true,
        data: '&idusuario='+idUsuario + '&datainicial='+dataInicial + '&datafinal=' + dataFinal,
        success: function(json){
            if (json.success)
            {
                
               table.clear().draw();
                for (var i in json.message)
                {
                     table.row.add( [
                        json.message[i].id,
                        json.message[i].idContrato,
                        json.message[i].pontosObtidos,
                        json.message[i].pontosResgatados,
                        json.message[i].nomeUsuario,
                        json.message[i].created,
                        json.message[i].validade,                     
                        (json.message[i].pontosResgatados == 0) ? '<button type="button" class="mb-xs mt-xs mr-xs btn btn-warning" onclick="remover('+json.message[i].id +', $(this).parents(\'tr\'))">Remover</button>' : '',

                    ] ).node();
                   
                }
                table.draw(false);
            
            }
            else
                table.clear().draw();
        }
        
    });
}    
    
function remover(id, $tr)
{
    if (! confirm('Deseja realmente remover a pontuação numero '+ id + '? A informação será removida para sempre.'))
        return false;
     
    $.ajax({
        type: "POST",
        url: '/lojapontos/apagar-historico-pontos/',
        cache: false,
        dataType: "json",
        data: 'id='+id,
        success: function(json){
            if (json.success)
             {
                 alert('Registro removido com sucesso');
                 //$('#id').val(json.id);
                 table.row( $tr ).remove().draw()
             }
            else
            {
                alert('Não foi possível realizar a alteração\nMotivo: ' + json.message);
                //document.location.reload();
            }
        }
    });
    
}
    
    //table.row( $(this).parents(\'tr\') ).remove().draw()
    
</script>