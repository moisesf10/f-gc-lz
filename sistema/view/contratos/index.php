<?php
$contratos = $this->getParams('contratos');
$convenios = $this->getParams('convenios');
$operacoes = $this->getParams('operacoes');
$usuarios = $this->getParams('usuarios');
$substatus = $this->getParams('substatus');

?>
<style>
    .panel-body{background-color: #fafafa; border: 1px solid #e0e0e0; padding-top: 3rem;}
    .box-button-save {margin-top: 3rem; margin-left: 1.7rem; padding-bottom: 3rem;}
    .panel-body div.row {margin-top: 1rem;}
    .box-dataTable {padding-bottom: 3rem;}
    
    .status-pendente {background-color: #fcb96b;}
    .status-reprovado {background-color: #f80707; color: #fff;}
</style>

<div class="row">
 <div class="col-md-2 box-button-save">
            <button type="button" class="mb-xs mt-xs mr-xs btn btn-grey" onclick="document.location='/<?php echo strtolower(\Application::getNameController()); ?>/cadastrar'"><i class="material-icons material-align-icons-button">&#xE145;</i>&nbsp;Novo Contrato</button>
    </div>
</div>
<form id="formPesquisa">
    <div class="row">
        <div class="panel panel-default col-md-12">
          <div class="panel-body">
            <p>Utilize o formul&aacute;rio abaixo para encontrar o contrato desejado</p>
                  <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label class="col-sm-2 control-label">Numero Contrato: </label>
                                <div class="col-sm-3">
                                    <input type="text" id="numero" name="numero"  class="form-control">
                                </div>
                            </div>
                        </div>
                  </div>
                  <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label class="col-sm-2 control-label">Nome Cliente: </label>
                                <div class="col-sm-6">
                                    <input type="text" id="nome" name="nome"  class="form-control">
                                </div>
                            </div>
                        </div>
                  </div>
                  <div class="row">
                        <div class="col-md-12">
                            <div class="form-group ">
                                <label class="col-sm-2 control-label">CPF Cliente: </label>
                                <div class="col-sm-3">
                                    <input type="text" id="cpf" name="cpf"  class="form-control">
                                </div>
                            </div>
                        </div>
                  </div>
              
                 <div class="row">
                     <div class="col-md-12">
                        <label class="col-sm-2 control-label">Nome do Usuário</label>
                            <div class="col-md-6">
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
              
                  <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label class="col-sm-2 control-label">Operação: </label>
                                <div class="col-sm-5">
                                    <select class="form-control" id="operacao" name="operacao">
                                        <option></option>
                                         <?php
                                            if (is_array($operacoes))
                                                foreach($operacoes as $i => $value)
                                                    echo '<option value="'. $value['nome'] . '">'. $value['nome'] . '</option>';
                                        ?>
                                    </select>
                                </div>
                            </div>
                        </div>
                  </div>
                  <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label class="col-sm-2 control-label" >Status: </label>
                                <div class="col-sm-5">
                                    <select class="form-control" id="status" name="status">
                                        <option></option>
                                        <option value="Em Análise" >Em Análise</option>
                                        <option value="Pendente" >Pendente</option>
                                        <option value="Reprovado" >Reprovado</option>
                                        <option value="Em Andamento" >Em Andamento</option>
                                        <option value="Pago ao Cliente">Pago ao Cliente</option>
                                        <option value="Recebido Comissão do Banco" >Recebido Comissão do Banco</option>
                                        <option value="Saldo Pago" >Saldo Pago</option>
                                        <option value="Aguardando Refim Port" >Aguardando Refim Port</option>
                                        <option value="Aumento" >Aumento</option>
										<option value="Finalizado" >Finalizado</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                  </div>
                  <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label class="col-sm-2 control-label">Convênio: </label>
                                <div class="col-sm-5">
                                    <select class="form-control" id="convenio" name="convenio">
                                        <option></option>
                                        <?php
                                            if (is_array($convenios))
                                                foreach($convenios as $i => $value)
                                                    echo '<option value="'. $value['nome'] . '">'. $value['nome'] . '</option>';
                                        ?>
                                    </select>
                                </div>
                            </div>
                        </div>
                  </div>
                  <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label class="col-sm-2 control-label">Período: </label>
                                <div class="col-sm-3">
                                    <input type="text" class="form-control" placeholder="Data Inicial" id="datainicio" name="datainicio" data-plugin-datepicker data-input-mask="99/99/9999" placeholder="__/__/____">
                                </div>
                                <div class="col-sm-3">
                                    <input type="text" class="form-control" placeholder="Data Final" id="datafim" name="datafim" data-plugin-datepicker data-input-mask="99/99/9999" placeholder="__/__/____">
                                </div>
                            </div>
                        </div>
                  </div>
              <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label class="col-sm-2 control-label">Pagamento Vendedor: </label>
                                <div class="col-sm-3">
                                    <input type="text" class="form-control" placeholder="Data Inicial" id="datavendedorinicio" name="datavendedorinicio" data-plugin-datepicker data-input-mask="99/99/9999" placeholder="__/__/____">
                                </div>
                                <div class="col-sm-3">
                                    <input type="text" class="form-control" placeholder="Data Final" id="datavendedorfim" name="datavendedorfim" data-plugin-datepicker data-input-mask="99/99/9999" placeholder="__/__/____">
                                </div>
                            </div>
                        </div>
                  </div>
                <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label class="col-sm-2 control-label" >Status do Pagamento: </label>
                                <div class="col-sm-5">
                                    <select class="form-control" id="statuspagamento" name="statuspagamento">
                                        <option></option>
                                        <option value="" >Todos</option>
                                        <option value="Pago" >Pago</option>
                                        <option value="Aberto" >Não Pago</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                  </div>
              
              <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label class="col-sm-2 control-label">Recebimento Comissão Banco: </label>
                                <div class="col-sm-3">
                                    <input type="text" class="form-control" placeholder="Data Inicial" id="datacomissaobancoinicio" name="datacomissaobancoinicio" data-plugin-datepicker data-input-mask="99/99/9999" placeholder="__/__/____">
                                </div>
                                <div class="col-sm-3">
                                    <input type="text" class="form-control" placeholder="Data Final" id="datacomissaobancofim" name="datacomissaobancofim" data-plugin-datepicker data-input-mask="99/99/9999" placeholder="__/__/____">
                                </div>
                            </div>
                        </div>
                  </div>
                <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label class="col-sm-2 control-label" >Recebido Comissão Banco: </label>
                                <div class="col-sm-5">
                                    <select class="form-control" id="statuscomissaobanco" name="statuscomissaobanco">
                                        <option></option>
                                        <option value="" >Todos</option>
                                        <option value="Sim" >Sim</option>
                                        <option value="Nao" >Não</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                  </div>


                  <div class="row">
                            <div class="col-md-12 box-button-save">
                                    <button type="button" class="mb-xs mt-xs mr-xs btn btn-danger" onclick="pesquisar()"><i class="material-icons material-align-icons-button">&#xE8B6;</i>&nbsp;Pesquisar</button>
                            </div>

                      </div>
                  </div>
          </div>
    </div>
</form>
<div class="row box-dataTable">
    <div class="col-md-12">
        <table id="datatable" class="display" cellspacing="0" width="100%">
                <thead>
                    <tr>
                        <th>Número</th>
                        <th>Nome do Cliente</th>
                        <th>CPF</th>
                        <th>Banco</th>
                        <th>Valor Parcela</th>
                        <th>Valor Completo</th>
                        <th>Valor Liberado</th>
                        <th>Status</th>
                        <th>Substatus</th>
                        <th>Pago ao Vendedor</th>
                        <th>Rec. Comissão Banco</th>
                        <th>Usuário</th>
                        <th>Editar</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                        if ((is_array($contratos)))
                            foreach($contratos as $i => $value)
                            {
                               
                                switch($value['status'])
                                {
                                    case 'Pendente': $colorClass = 'status-pendente'; break;
                                    case 'Reprovado': $colorClass = 'status-reprovado'; break;
                                    default: $colorClass = '';
                                        
                                   /* $key = array_search($value['idSubstatusContrato'], array_column($substatus, 'id'));
                                        if ($key !== false)
                                            $descricaoSubstatus = $substatus[$key]['descricao'];
                                        else
                                            $descricaoSubstatus = '';*/
                                }
                                ?>
                                <tr>
                                     <td><?php echo $value['numeroContrato']; ?></td>
                                     <td><?php echo $value['nomeCliente']; ?></td>
                                     <td><?php echo $value['cpf']; ?></td>
                                     <td><?php echo $value['nomeBancoContrato']; ?></td>
                                    <td><?php echo 'R$ ' . Gauchacred\library\php\Utils::numberToMoney($value['valorParcela']); ?></td>
                                    <td><?php echo 'R$ ' . Gauchacred\library\php\Utils::numberToMoney($value['valorTotal']); ?></td>
                                    <td><?php echo 'R$ ' . Gauchacred\library\php\Utils::numberToMoney($value['valorLiquido']); ?></td>
                                    <td  class="<?php echo $colorClass; ?>"><?php echo $value['status']; ?></td>
                                    <td><?php echo $value['descricaoSubstatus']; ?></td>
                                    <td><?php echo ($value['pagoVendedor'] == true) ? 'Sim' : 'Não'; ?></td>
                                    <td><?php echo ($value['recebidoComissaoBanco'] == true) ? 'Sim' : 'Não'; ?></td>
                                    <td><?php echo $value['nomeUsuario']; ?></td>
                                     <td><a href="/<?php echo strtolower(\Application::getNameController()). '/cadastrar/' .  $value['id'];?>"><i class="material-icons">&#xE254;</i></a></td>
                                </tr>        

                        <?php
                            }
                    ?>

                </tbody>
            </table>
    </div>
</div>



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
                       "bStateSave": true,
                        "BLengthChange" : true,
                         "iDisplayLength": 20,
                         "bInfo": true,
                         "bSort": true,  
                         "bLengthChange": false,
                         "paging": true,
                         "searching": true,
                         "oLanguage": {
                             "oPaginate": {
                                 "sNext": "Pr&oacute;ximo",
                                 "sPrevious": "Anterior"

                              },  
                             "sInfoEmpty": "",
                             "sSearch": "Pesquisar:",
                             "sZeroRecords": " " ,
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
    $('#cpf').mask('999.999.999-99',{placeholder:"___.___.___-__", autoclear: false});
});

    
    
    
function pesquisar()
{
	$.magnificPopup.open({ 
        items: { src: '#modalSuccess'},
        type: 'inline',
		preloader: false,
		modal: true
                         
       });

    $.ajax({
          type: "POST",
          url:  "/<?php echo strtolower(\Application::getNameController()); ?>/pesquisar-contratos/",
          data: $('#formPesquisa').serialize() + '&limit=100000'  ,
          dataType: 'json',
        success: function(data){
            $.magnificPopup.close();
            table.clear().draw();
            //table.rows.add($.parseJSON(data)).draw();
              for (var i in data)
              {
                  var telefones = [];
                 
                 var rowNode = table.row.add([
                      data[i].numeroContrato, 
                      data[i].nomeCliente, 
                      data[i].cpf, 
                      data[i].nomeBancoContrato,
                      'R$ ' + formatReal(data[i].valorParcela),
                      'R$ ' + formatReal(data[i].valorTotal),
                      'R$ ' + formatReal(data[i].valorLiquido),
                      data[i].status,
                      data[i].descricaoSubstatus,
                      ((data[i].pagoVendedor == true) ? 'Sim' : 'Não'),
                      ((data[i].recebidoComissaoBanco == true) ? 'Sim' : 'Não'),
                      data[i].nomeUsuario,
                      '<a href="/<?php echo strtolower(\Application::getNameController()). '/cadastrar/';?>'+ data[i].id + '"><i class="material-icons">&#xE254;</i></a>' 
                  ]).node();
                  
                 
                  switch(data[i].status)
                  {
                      case 'Pendente': $( rowNode ).find('td').eq(7).addClass('status-pendente'); break;
                      case 'Reprovado': $( rowNode ).find('td').eq(7).addClass('status-reprovado'); break;
                      
                  }
              }
            
            table.draw(false);
            
           //data = data.replace('{"data":','');
          //  data = data.replace(/[}]$/gi,'');
          //  data = data.replace(/([}])$|({"data"\:)/gi  ,'');
        
           // table.clear().draw();
            //table.rows.add($.parseJSON(data)).draw();
            
            
            //table.ajax.url(data).load();
            //table.fnClearTable();
          //  table.fnAddData(data);
        },
    });
}
    
    function formatReal( valor )
{
       valor = valor.toString().replace(/\D/g,"");
    valor = valor.toString().replace(/(\d)(\d{8})$/,"$1.$2");
    valor = valor.toString().replace(/(\d)(\d{5})$/,"$1.$2");
    valor = valor.toString().replace(/(\d)(\d{2})$/,"$1,$2");
    return valor  
}
    
    
</script>