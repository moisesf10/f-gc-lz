<?php
$subtabela = (isset($this->getParams('subtabela')[0])) ? $this->getParams('subtabela')[0] : null;
$tabelaCompleta = $this->getParams('tabelacompleta');
$operacao = $this->getParams('operacao');
$grupos = $this->getParams('grupos');

?>

<style>
    section.panel div.panel-body div.row {padding-bottom: 1rem;}
    div.botoes {margin-left: 0.2rem;}
    div.botoes div {padding-right: 1rem; }
    .row-datatable {margin-top: 4rem;}
</style>





<section class="panel">
        <header class="panel-heading">
            <h2 class="panel-title">Cadastro de Subtabela</h2>
        </header>
        <div class="panel-body">
           
             <div class="row">
                <div class="col-md-6">
                            <div class="form-group">
                                <label class="col-sm-2 control-label">ID: </label>
                                <div class="col-sm-6">
                                    <input type="text" disabled id="id" class="form-control" value="<?php if(isset($subtabela['id'])) echo $subtabela['id'] ?>" />
                                </div>
                            </div>
                    </div>
            </div>
            
            <div class="row">
                <div class="col-md-6">
                            <div class="form-group">
                                <label class="col-sm-2 control-label">Banco: </label>
                                <div class="col-sm-6">
                                    <select class="form-control" id="banco">
                                        <?php
                                            if (isset($subtabela['idBanco']))
                                                echo '<option value="'. $subtabela['idBanco'] . '">' . $subtabela['nomeBanco'] . '</option>';
                                           else
                                               echo '<option></option>';
                                           ?>
                                    </select>
                                </div>
                            </div>
                    </div>
                <div class="col-md-6">
                            <div class="form-group">
                                <label class="col-sm-2 control-label">Tabela: </label>
                                <div class="col-sm-6">
                                   <select class="form-control" id="tabela">
                                        <?php
                                            if (isset($subtabela['idTabela']))
                                                echo '<option value="'. $subtabela['idTabela'] . '">' . $subtabela['nomeTabela'] . '</option>';
                                           else
                                               echo '<option></option>';
                                           ?>
                                    </select>
                                </div>
                            </div>
                    </div>
            </div>
            
            <div class="row">
                <div class="col-md-6">
                            <div class="form-group">
                                <label class="col-sm-2 control-label">Convênio: </label>
                                <div class="col-sm-6">
                                    <select class="form-control" id="convenio">
                                        <?php
                                            if (isset($subtabela['idConvenio']))
                                                echo '<option value="'. $subtabela['idConvenio'] . '">' . $subtabela['nomeConvenio'] . '</option>';
                                           else
                                               echo '<option></option>';
                                           ?>
                                    </select>
                                </div>
                            </div>
                    </div>
                <div class="col-md-6">
                            <div class="form-group">
                                <label class="col-sm-2 control-label">Operação: </label>
                                <div class="col-sm-6">
                                   <select class="form-control" id="operacao">
                                        <option></option>
                                       <?php
                                       
                                        if (is_array($operacao))
                                            foreach($operacao as $i => $value)
                                            { 
                                                $selected = ($subtabela['idOperacao'] == $value['id']) ? 'selected="selected"' : '';
                                            ?>
                                                <option <?php echo $selected; ?> value="<?php echo $value['id']; ?>"><?php echo $value['nome'];  ?></option>
                                      <?php }
                                       ?>
                                    </select>
                                </div>
                            </div>
                    </div>
            </div>
            
            <div class="row">
                <div class="col-md-6">
                            <div class="form-group">
                                <label class="col-sm-2 control-label">Seguro: </label>
                                <div class="col-sm-6">
                                    <input type="text" class="form-control" id="seguro" value="<?php if (isset($subtabela['seguro'])) echo $subtabela['seguro']; ?>">
                                </div>
                            </div>
                    </div>
                <div class="col-md-6">
                            <div class="form-group">
                                <label class="col-sm-2 control-label">Imposto (%): </label>
                                <div class="col-sm-6">
                                   <input type="text" class="form-control" id="imposto" value="<?php if (isset($subtabela['imposto'])) echo $subtabela['imposto']; else echo '0.00'; ?>">
                                </div>
                            </div>
                    </div>
            </div>
            
            <div class="row">
                <div class="col-md-6">
                            <div class="form-group">
                                <label class="col-sm-2 control-label">Inicio Vigência: </label>
                                <div class="col-sm-6">
                                    <input type="text" class="form-control" id="iniciovigencia" data-plugin-datepicker data-input-mask="99/99/9999" placeholder="__/__/____" value="<?php if (isset($subtabela['inicioVigencia'])) echo $subtabela['inicioVigencia']; ?>">
                                </div>
                            </div>
                    </div>
                <div class="col-md-6">
                            <div class="form-group">
                                <label class="col-sm-2 control-label">Fim Vigência: </label>
                                <div class="col-sm-6">
                                   <input type="text" class="form-control" id="fimvigencia" data-plugin-datepicker data-input-mask="99/99/9999" placeholder="__/__/____" value="<?php if (isset($subtabela['fimVigencia'])) echo $subtabela['fimVigencia']; ?>">
                                </div>
                            </div>
                    </div>
            </div>
            
            <div class="row">
                <div class="col-md-6">
                            <div class="form-group">
                                <label class="col-sm-2 control-label">Comissão Total (%): </label>
                                <div class="col-sm-6">
                                    <input type="text" class="form-control" id="comissaototal" value="<?php if (isset($subtabela['comissaoTotal'])) echo $subtabela['comissaoTotal']; ?>">
                                </div>
                            </div>
                    </div>
            </div>
            
            <div class="row">
                <div class="col-md-12 box-prazo">
                    <div class="col-sm-1">
                        <div class="row"><label class="control-label">Prazo: </label></div>
                        <div class="row"><label class="control-label">Coeficiente: </label></div>
                    </div>       
                    
                   
                                
                    <div class="col-sm-1 form-group">
                        <div class="row">
                            <input type="text" class="form-control prazo" value="<?php if (isset($subtabela['prazos'][0]['prazo'])) echo $subtabela['prazos'][0]['prazo']; ?>">
                        </div>
                        <div class="row">
                            <input type="text" class="form-control coeficiente" value="<?php if (isset($subtabela['prazos'][0]['coeficiente'])) echo $subtabela['prazos'][0]['coeficiente']; ?>">
                        </div>
                    </div>
                    <div class="col-sm-1 form-group">
                        <div class="row">
                            <input type="text" class="form-control prazo" value="<?php if (isset($subtabela['prazos'][1]['prazo'])) echo $subtabela['prazos'][1]['prazo']; ?>">
                        </div>
                        <div class="row">
                            <input type="text" class="form-control coeficiente" value="<?php if (isset($subtabela['prazos'][1]['coeficiente'])) echo $subtabela['prazos'][1]['coeficiente']; ?>">
                        </div>
                    </div>
                    <div class="col-sm-1 form-group">
                        <div class="row">
                            <input type="text" class="form-control prazo" value="<?php if (isset($subtabela['prazos'][2]['prazo'])) echo $subtabela['prazos'][2]['prazo']; ?>">
                        </div>
                        <div class="row">
                            <input type="text" class="form-control coeficiente" value="<?php if (isset($subtabela['prazos'][2]['coeficiente'])) echo $subtabela['prazos'][2]['coeficiente']; ?>">
                        </div>
                    </div>
                    <div class="col-sm-1 form-group">
                        <div class="row">
                            <input type="text" class="form-control prazo" value="<?php if (isset($subtabela['prazos'][3]['prazo'])) echo $subtabela['prazos'][3]['prazo']; ?>">
                        </div>
                        <div class="row">
                            <input type="text" class="form-control coeficiente" value="<?php if (isset($subtabela['prazos'][3]['coeficiente'])) echo $subtabela['prazos'][3]['coeficiente']; ?>">
                        </div>
                    </div>
                    <div class="col-sm-1 form-group">
                        <div class="row">
                            <input type="text" class="form-control prazo" value="<?php if (isset($subtabela['prazos'][4]['prazo'])) echo $subtabela['prazos'][4]['prazo']; ?>">
                        </div>
                        <div class="row">
                            <input type="text" class="form-control coeficiente" value="<?php if (isset($subtabela['prazos'][4]['coeficiente'])) echo $subtabela['prazos'][4]['coeficiente']; ?>">
                        </div>
                    </div>
                    <div class="col-sm-1 form-group">
                        <div class="row">
                            <input type="text" class="form-control prazo" value="<?php if (isset($subtabela['prazos'][5]['prazo'])) echo $subtabela['prazos'][5]['prazo']; ?>">
                        </div>
                        <div class="row">
                            <input type="text" class="form-control coeficiente" value="<?php if (isset($subtabela['prazos'][5]['coeficiente'])) echo $subtabela['prazos'][5]['coeficiente']; ?>">
                        </div>
                    </div>
                    <div class="col-sm-1 form-group">
                        <div class="row">
                            <input type="text" class="form-control prazo" value="<?php if (isset($subtabela['prazos'][6]['prazo'])) echo $subtabela['prazos'][6]['prazo']; ?>">
                        </div>
                        <div class="row">
                            <input type="text" class="form-control coeficiente" value="<?php if (isset($subtabela['prazos'][6]['coeficiente'])) echo $subtabela['prazos'][6]['coeficiente']; ?>">
                        </div>
                    </div>
                    <div class="col-sm-1 form-group">
                        <div class="row">
                            <input type="text" class="form-control prazo" value="<?php if (isset($subtabela['prazos'][7]['prazo'])) echo $subtabela['prazos'][7]['prazo']; ?>">
                        </div>
                        <div class="row">
                            <input type="text" class="form-control coeficiente" value="<?php if (isset($subtabela['prazos'][7]['coeficiente'])) echo $subtabela['prazos'][7]['coeficiente']; ?>">
                        </div>
                    </div>
                    <div class="col-sm-1 form-group">
                        <div class="row">
                            <input type="text" class="form-control prazo" value="<?php if (isset($subtabela['prazos'][8]['prazo'])) echo $subtabela['prazos'][8]['prazo']; ?>">
                        </div>
                        <div class="row">
                            <input type="text" class="form-control coeficiente" value="<?php if (isset($subtabela['prazos'][8]['coeficiente'])) echo $subtabela['prazos'][8]['coeficiente']; ?>">
                        </div>
                    </div>
                    <div class="col-sm-1 form-group">
                        <div class="row">
                            <input type="text" class="form-control prazo" value="<?php if (isset($subtabela['prazos'][9]['prazo'])) echo $subtabela['prazos'][9]['prazo']; ?>">
                        </div>
                        <div class="row">
                            <input type="text" class="form-control coeficiente" value="<?php if (isset($subtabela['prazos'][9]['coeficiente'])) echo $subtabela['prazos'][9]['coeficiente']; ?>">
                        </div>
                    </div>
                                
                </div>
                   
            </div>
            
            
            <div class="row">
                <div class="col-md-3">
                            <div class="form-group">
                                <label class="col-sm-5 control-label">Valor Vendido Para Pontuar: </label>
                                <div class="col-sm-6">
                                    <input type="text" class="form-control money" id="valorpontuar" value="<?php if (isset($subtabela['valorVendaGerarPonto'])) echo $subtabela['valorVendaGerarPonto']; ?>">
                                </div>
                            </div>
                    </div>
                <div class="col-md-3">
                            <div class="form-group">
                                <label class="col-sm-5 control-label">Total de Pontos Gerar: </label>
                                <div class="col-sm-6">
                                   <input type="text" class="form-control number" id="pontosgerar" value="<?php if (isset($subtabela['quantidadePontosGerar'])) echo $subtabela['quantidadePontosGerar']; else echo '0.00'; ?>">
                                </div>
                            </div>
                    </div>
                <div class="col-md-3">
                            <div class="form-group">
                                <label class="col-sm-5 control-label">Quantidade Dias Expirar: </label>
                                <div class="col-sm-6">
                                   <input type="text" class="form-control number" id="diasexpirar" value="<?php if (isset($subtabela['quantidadeDiasExpirarPontos'])) echo $subtabela['quantidadeDiasExpirarPontos']; else echo '0.00'; ?>">
                                </div>
                            </div>
                    </div>
            </div>
            
            <div class="row row-datatable">
                <div class="col-md-12">
                            <div class="row">
                                <div class="col-md-6">
                                    <label class="col-sm-5 control-label">Grupos e Comissões: </label>
                                </div>
                                <div class="col-md-6 text-right">
                                    <button class="mb-xs mt-xs mr-xs btn btn-success" onclick="adicionarGrupo()">+ Adicionar Grupo</button> 
                                </div>
                            </div>
                            <div class="row">
                                    <table id="datatable" class="display" cellspacing="0" width="100%">
                                            <thead>
                                                <tr>
                                                    <th>Grupo</th>
                                                    <th>Comissão (%)</th>
                                                    <th>Recebe Comissão de</th>
                                                    <th>Excluir</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                               <?php
                                                if (isset($subtabela['comissoes']) && count($subtabela['comissoes']) > 0)
                                                    foreach($subtabela['comissoes'] as $i => $value)
                                                        if (isset($value['idGrupo']))
                                                        {
                                                            
                                                            ?>
                                                            <tr>
                                                                <td>
                                                                    <?php 
                                                                        echo '<select class="form-control">';
                                                                        if (is_array($grupos))
                                                                        {
                                                                            foreach($grupos as $j => $grupo)
                                                                            {
                                                                                $selected = ($grupo['id'] == $value['idGrupo']) ? 'selected="selected"': '';
                                                                                echo '<option value="'. $grupo['id']  .'" '. $selected .'>'. $grupo['nome'] . '</option>';
                                                                            }
                                                                        }else
                                                                            echo '<option value="'. $value['idGrupo']  .'">'. $value['nomeGrupo']  . '</option>';
                                                                        echo '</select>';
                                                                
                                                                    ?>
                                                                    
                                                                </td>
                                                                <td>
                                                                    <input type="text" maxlength="6" class="form-control" data-uuid="comissao-1" value="<?php echo $value['comissao']; ?>">
                                                                </td>
                                                                <td>
                                                                    <?php 
                                                                        echo '<select class="form-control multiselect" multiple="multiple" data-plugin-multiselect>';
                                                                        if (is_array($grupos)  )
                                                                        {
                                                                            foreach($grupos as $j => $grupo)
                                                                            {
                                                                
                                                                                $selected = (in_array($grupo['id'], $value['recebeComissaoDe'] )) ? 'selected="selected"': '';
                                                                                
                                                                                if ($grupo['id'] != $value['idGrupo'])
                                                                                    echo '<option value="'. $grupo['id']  .'" '. $selected .'>'. $grupo['nome'] . '</option>';
                                                                            }
                                                                        }
                                                                        echo '</select>';
                                                                
                                                                    ?>
                                                                </td>
                                                                <td>
                                                                    <button type="button"  class="mb-xs mt-xs mr-xs btn btn-warning" onclick="table.row( $(this).parents('tr')).remove().draw()">Remover</button>
                                                                </td>
                                                            </tr>
                                                            
                                                <?php
                                                        }
                                                
                                                ?>
                                            </tbody>
                                        </table>
                            </div>
                    </div>
            </div>
            
           
     
    </div>
    
    
    <div class="row botoes">
        <?php
        if (\Application::isAuthorized(ucfirst(strtolower(\Application::getNameController())) , 'subtabelas', 'escrever') )
        {?>
        <button type="button" class="mb-xs mt-xs mr-xs btn btn-danger" onclick="salvar()"><i class="material-icons material-align-icons-button">&#xE877;</i>&nbsp;Salvar</button>
        <?php } ?>
        
        <?php
        if (\Application::getUrlParams(0) !== null &&  \Application::isAuthorized(ucfirst(strtolower(\Application::getNameController())) , 'subtabelas', 'remover') )
        {?>
            <button type="button" class="mb-xs mt-xs mr-xs btn btn-grey" onclick="remover()"><i class="material-icons material-align-icons-button"></i>&nbsp;Remover</button>
        <?php } ?>
        
      
        <button type="button" class="mb-xs mt-xs mr-xs btn btn-grey" onclick="document.location='/<?php echo strtolower(\Application::getNameController()); ?>/cadastrar-subtabela/'"><i class="material-icons material-align-icons-button">&#xE145;</i>&nbsp;Novo</button>
        
    
    </div>
    
</section>


<script src="/library/jsvendor/bootstrap-datepicker/js/bootstrap-datepicker.js" ></script>


<script>
$.getCSS('/library/jsvendor/bootstrap-multiselect/bootstrap-multiselect.css');
    
$.getScript('/library/javascript/subtabelas/cadastrar.js?_=<?php echo uniqid(); ?>', function(){
    initJsonTabela();
});
    
$.getScript('/library/jsvendor/jquery-maskmoney/dist/jquery.maskMoney.min.js', function(){
     $('#seguro, .money').maskMoney({prefix:'R$ ', allowNegative: false, thousands:'.', decimal:',', affixesStay: true}).focus()   ;
     $('#imposto').maskMoney({prefix:'', allowNegative: false, thousands:',', decimal:'.', affixesStay: true, allowZero: false});
    $('#comissaototal').maskMoney({prefix:'', allowNegative: false, thousands:',', decimal:'.', affixesStay: true, allowZero: true});
    //$('.box-prazo .coeficiente').maskMoney({prefix:'', allowNegative: false, thousands:'.', decimal:',', affixesStay: true, allowZero: true, precision: 10});
    $('#datatable [data-uuid="comissao-1"]').maskMoney({prefix:'', allowNegative: false, thousands:',', decimal:'.', affixesStay: true});
    
    $('#banco').focus();
});
    
$.getScript('/library/jsvendor/jquery-masked-input/jquery.maskedinput.min.js', function(){
    $('.box-prazo .prazo').mask('99',{placeholder:"", autoclear: false});
    $('.number').mask('9999',{placeholder:"", autoclear: false});
});
    
    
    
$.getScript("/library/jsvendor/bootstrap-multiselect/bootstrap-multiselect.js",function(){   
    
    $('.multiselect').multiselect({
            buttonWidth: '40rem',
            nonSelectedText: 'Selecione o grupo'
            
            })
});
    
    
    
$.getCSS('/library/jsvendor/datatables/css/jquery.dataTables.min.css');
$.getScript( "/library/jsvendor/datatables/js/jquery.dataTables.min.js", function(  ) {
     /**
     * Inicia DataTable... CALLBACK executado após baixar dataTables.min.js
     * 
     **/
   table =  $('#datatable').DataTable({
            "bStateSave": false,
            "BLengthChange" : true,
             "iDisplayLength": 20,
             "bInfo": false,
             "bSort": false,  
             "bLengthChange": false,
            "searching": false,
            "paging": false,
             "oLanguage": {
                 "oPaginate": {
                     "sNext": "Pr&oacute;ximo",
                     "sPrevious": "Anterior"

                  },  
                 "sInfoEmpty": "",
                 "sSearch": "Pesquisar:",
                 "sZeroRecords": "Nenhuma comissão de grupo cadastrada. Clique no botão acima &quot;Adicionar Grupo&quot; para cadastrar" ,
                 "sInfo": "Mostrando página _PAGE_ de _PAGES_",
                 "sInfoFiltered": "(Filtrado _MAX_ do total)"
              } 
        } );
}); // fim $.GetScript    

    
<?php
    
    $jsVar = 'grupos = [';
    if (is_array($grupos))
        foreach($grupos as $i => $value)
            $jsVar .= '{"id": '. $value['id'] .', "nome": "'. $value['nome'] . '"},';
    $jsVar .= "];";
    echo  $jsVar;
    
    if (is_array($tabelaCompleta))
        echo 'jsonTabela = ' . json_encode($tabelaCompleta) . ';';
    else
        echo 'jsonTabela = []';
    
    
?>

    
</script>