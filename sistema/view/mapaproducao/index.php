<?php
$usuarios = $this->getParams('usuarios');
$grupos =  $this->getParams('grupos');
$bancos =  $this->getParams('bancos');
$convenios =  $this->getParams('convenios');
$operacoes = $this->getParams('operacoes');
$tabelas = $this->getParams('tabelas');
$substatus = $this->getParams('substatus');
?>

<style>
    #datatable, div.danger {margin-bottom: 20px;}
    .titulos-totais {font-weight: bold;}
    div.danger {
        color: #FFF;
        background-color: #d2322d;
        padding: 5px 0 5px 0;
    }
</style>

<form name="form1" id="form1" method="post" action="/relatorios/gerar-comissao-vendedor/" target="_blank">
<section class="panel">
        <header class="panel-heading">
            <h2 class="panel-title">Relatórios Vendedor</h2>
        </header>
        <div class="panel-body">
                    <div class="form-group">
                        <label class="col-md-3 control-label">Criado em</label>
                        <div class="col-md-3">
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
                    <div class="form-group">
                        <label class="col-md-3 control-label">Modificado em</label>
                        <div class="col-md-3">
                            <div class="input-daterange input-group" data-plugin-datepicker>
                                <span class="input-group-addon">
                                    <i class="fa fa-calendar"></i>
                                </span>
                                <input type="text" class="form-control" name="datainicialmodificacao" id="datainicialmodificacao">
                                <span class="input-group-addon">Até</span>
                                <input type="text" class="form-control" name="datafinalmodificacao" id="datafinalmodificacao">
                            </div>
                        </div>
                    </div>
            </div>
    
        <div class="panel-body">
                <div class="form-group">
                        <label class="col-md-3 control-label">Nome do Vendedor</label>
                        <div class="col-md-3">
                            <select data-plugin-selectTwo class="form-control " id="usuario" name="usuario">
                                <optgroup label="Selecione o nome do vendedor">
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
    
            <div class="panel-body">
                <div class="form-group">
                        <label class="col-md-3 control-label">Grupo</label>
                        <div class="col-md-3">
                            <select data-plugin-selectTwo class="form-control " id="grupousuario" name="grupousuario">
                                <optgroup label="Selecione o nome do grupo">
                                    <option></option>
                                    <?php
                                        if (is_array($grupos))
                                            foreach($grupos as $i => $value)
                                                echo '<option value="'. $value['id'] . '">'. $value['nome'] . '</option>';
                                    ?>
                                    
                                </optgroup>

                            </select>
                        </div>
                    </div>
            </div>
    
            <div class="panel-body">
                <div class="form-group">
                        <label class="col-md-3 control-label">Banco</label>
                        <div class="col-md-3">
                            <select data-plugin-selectTwo class="form-control " id="banco" name="banco">
                                <optgroup label="Selecione o nome do banco">
                                    <option></option>
                                    <?php
                                        if (is_array($bancos))
                                            foreach($bancos as $i => $value)
                                                echo '<option value="'. $value['nome'] . '">'. $value['nome'] . '</option>';
                                    ?>
                                    
                                </optgroup>

                            </select>
                        </div>
                    </div>
            </div>
    
        <div class="panel-body">
                <div class="form-group">
                        <label class="col-md-3 control-label">Convênios</label>
                        <div class="col-md-3">
                            <select data-plugin-selectTwo class="form-control " id="convenio" name="convenio">
                                <optgroup label="Selecione o nome do convenio">
                                    <option></option>
                                    <?php
                                        if (is_array($convenios))
                                            foreach($convenios as $i => $value)
                                                echo '<option value="'. $value['nome'] . '">'. $value['nome'] . '</option>';
                                    ?>
                                    
                                </optgroup>

                            </select>
                        </div>
                    </div>
            </div>
    
        <div class="panel-body">
                <div class="form-group">
                        <label class="col-md-3 control-label">Operação</label>
                        <div class="col-md-3">
                            <select data-plugin-selectTwo class="form-control " id="operacao" name="operacao">
                                <optgroup label="Selecione o nome da operação">
                                    <option></option>
                                    <?php
                                        if (is_array($operacoes))
                                            foreach($operacoes as $i => $value)
                                                echo '<option value="'. $value['nome'] . '">'. $value['nome'] . '</option>';
                                    ?>
                                    
                                </optgroup>

                            </select>
                        </div>
                    </div>
            </div>
    
            <div class="panel-body">
                <div class="form-group">
                        <label class="col-md-3 control-label">Tabela</label>
                        <div class="col-md-3">
                            <select data-plugin-selectTwo class="form-control " id="tabela" name="tabela">
                                <optgroup label="Selecione o nome da tabela">
                                    <option></option>
                                    <?php
                                        if (is_array($tabelas))
                                            foreach($tabelas as $i => $value)
                                                echo '<option value="'. $value['nomeTabela'] . '">'. $value['nomeTabela'] . '</option>';
                                    ?>
                                    
                                </optgroup>

                            </select>
                        </div>
                    </div>
            </div>
            <div class="panel-body">
                <div class="form-group">
                        <label class="col-md-3 control-label">Pago ao Vendedor</label>
                        <div class="col-md-3">
                            <select data-plugin-selectTwo class="form-control " id="pagovendedor" name="pagovendedor">
                                <optgroup label="Selecione o pagamento ao vendedor">
                                    <option>Todos</option>
                                    <option value="pago">Somente pagos</option>
                                    <option value="naopago">Somente não pagos</option>
                                </optgroup>

                            </select>
                        </div>
                    </div>
            </div>
    
        <div class="panel-body">
                <div class="form-group">
                        <label class="col-md-3 control-label">Recebido Comissão Banco</label>
                        <div class="col-md-3">
                            <select data-plugin-selectTwo class="form-control " id="recebidocomissaobanco" name="recebidocomissaobanco">
                                <optgroup label="Indique se deseja os recebidos comissão do banco">
                                    <option>Todos</option>
                                    <option value="sim">Sim</option>
                                    <option value="nao">Não</option>
                                </optgroup>

                            </select>
                        </div>
                    </div>
            </div>
    
    <div class="panel-body">
                <div class="form-group">
                        <label class="col-md-3 control-label">Substatus</label>
                        <div class="col-md-3">
                            <select data-plugin-selectTwo class="form-control " id="substatus" name="substatus">
                                <option value=""></option>
                                 <?php
                                if (is_array($substatus))
                                    foreach($substatus as $i => $value){
                                ?>
                                    <option value="<?php echo $value['id']; ?>"><?php echo $value['descricao'];?></option>
								
                                <?php }  ?>

                            </select>
                        </div>
                    </div>
            </div>
             
            <div class="panel-body">
                    <div class="form-group">
                        <label class="col-md-3 control-label">Status</label>
                        <div class="col-md-3">
                            <label><input type="checkbox" name="status[]" value="Pago ao Cliente"> Pago ao Cliente</label>
                          <label><input type="checkbox" name="status[]" value="Pendente"> Pendente</label> 
                            <label><input type="checkbox" name="status[]" value="Em Andamento"> Em andamento</label>
                            <label><input type="checkbox" name="status[]" value="Reprovado"> Reprovado</label>
                            <label><input type="checkbox" name="status[]" value="Em Análise"> Em Análise</label>
                            <label><input type="checkbox" name="status[]" value="Saldo Pago"> Saldo Pago</label>
                            <label><input type="checkbox" name="status[]" value="Aguardando Refim Port"> Aguardando Refim Port</label>
                            <label><input type="checkbox" name="status[]" value="Aumento"> Aumento</label>
                            
                        </div>
                    </div>
            </div>
    
        
    
    <div class="panel-body">
          <button type="button" class="mb-xs mt-xs mr-xs btn btn-danger" onclick="buscar();">Buscar Relatórios</button>
    </div>
    
</section>
</form>


<div class="panel-body">
        <div class="form-group">
            
            <table id="datatable" class="display" cellspacing="0" width="100%">
                <thead>
                    <tr>
                        <th>Número</th>
                        <th>Nome do Cliente</th>
                        <th>CPF</th>
                        <th>Banco</th>
                        <th>Parcelas</th>
                        <th>Valor Completo</th>
                        <th>Valor Liberado</th>
                        <th>Status</th>
                        <th>Substatus</th>
                        <th>Pago ao Vendedor</th>
                        <th>Recebido Comissão Banco</th>
                        <th>Usuário</th>
                      
                    </tr>
                </thead>
                <tbody>
                   

                </tbody>
            </table>
            
            <h2>Total </h2>
            <div class="row titulos-totais">
                <div class="col-md-6">Numero de Contato</div>
                <div class="col-md-6">Valor do AF</div>
            </div>
            <div class="row danger totais">
                <div class="col-md-6"><span class="total">0</span></div>
                <div class="col-md-6"><span class="valor">0,00</span></div>
            </div>
										
									
																				


            
            
        </div>
</div>




<script src="/library/jsvendor/select2/select2.min.js"></script>
<script src="/library/jsvendor/bootstrap-datepicker/js/bootstrap-datepicker.js"></script>

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
$.getCSS('/library/jsvendor/select2/select2.css');
$.getCSS('/library/jsvendor/select2/select2.custom.css');
$.getCSS('/library/jsvendor/magnific-popup/magnific-popup.css');
    
$.getCSS('/library/jsvendor/datatables/css/jquery.dataTables.min.css');
$.getCSS('/library/jsvendor/datatables/css/responsive.dataTables.min.css');
    
$.getScript( "/library/jsvendor/datatables/js/jquery.dataTables.min.js", function(  ) {
     /**
     * Inicia DataTable... CALLBACK executado após baixar dataTables.min.js
     * 
     **/
    
    $.getScript("/library/jsvendor/datatables/js/dataTables.responsive.min.js", function(){
               table =  $('#datatable').DataTable({
                       "bStateSave": false,
                        "BLengthChange" : false,
                         "iDisplayLength": 0,
                         "bInfo": false,
                         "bSort": false,  
                         "bLengthChange": false,
                         "paging": false,
                         "searching": true,
                   "deferRender": true,
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
    
function buscar()
{
    //console.log($('#form1').serialize() );
    
    
   // var nome = $('#nome').val();
    //var nome = $('#nome').val();
	 $.magnificPopup.open({ 
        items: { src: '#modalSuccess'},
        type: 'inline',
		preloader: false,
		modal: true
                         
       });
   
    
    $.ajax({
          type: "POST",
          url:  '/<?php echo  strtolower(\Application::getNameController()); ?>/pesquisar-mapa-producao/',
          data: $('#form1').serialize()+'&limit=100000' ,
          dataType: 'json',
          cache: false,
          success: function(data){
            $.magnificPopup.close();
            table.clear().draw();
            var valor = 0;
              var total = 0;
              for (var i in data)
              {
                  var pagoVendedor = (data[i].dataPagamento == null  ) ? 'Não' : 'Sim';
                  var recebidoComissaoBanco = (data[i].recebidoComissaoBanco == false  ) ? 'Não' : 'Sim';
                  table.row.add([data[i].numeroContrato, data[i].nomeCliente, data[i].cpf, data[i].nomeBancoContrato, data[i].quantidadeParcelas, formatReal(data[i].valorTotal), formatReal(data[i].valorLiquido), data[i].status, data[i].descricaoSubstatus, pagoVendedor, recebidoComissaoBanco, data[i].nomeUsuario ]);
                  valor += parseFloat(data[i].valorTotal);
                  total++;
              }
              
              table.draw(false);
              
            
                           
              $('.valor').html(formatReal(valor) );
              $('.total').html(total);
            
        },
    });
}
    

    
function formatReal(n) {
    n = parseFloat(n);
    return "R$ " + n.toFixed(2).replace('.', ',').replace(/(\d)(?=(\d{3})+\,)/g, "$1.");
}

    
    
</script>

