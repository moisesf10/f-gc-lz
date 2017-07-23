<style>
    .panel-dados-cliente div.row, .panel-dados-contrato div.row:nth-child(2) {padding-bottom: 2rem;}
    .panel-dados-cliente a {color: #0707a7; }
    .box-button-save {margin-bottom: 4rem;}
</style>

<h5>Cadastro de Contrato</h5>
<hr>


<section class="panel panel-dados-cliente">
    <header class="panel-heading">
        <h2 class="panel-title">Dados do cliente</h2>
    </header>
    <div class="panel-body">
        <div class="row">
            <div class="col-md-4">
                <div class="form-group">
                    <label class="col-sm-4 control-label">CPF: </label>
                    <div class="col-sm-8">
                        <input type="text" name="cpf" id="cpf"  class="form-control" value="<?php if (isset($cliente['dados']['cpf'])) echo $cliente['dados']['cpf']; ?>" >
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <button class="btn btn-grey" onclick="buscarCliente()">&nbsp;Carregar</button>
            </div>
        </div>
        
        <div class="row">
            <div class="col-md-8">
                <div class="form-group">
                    <label class="col-md-2 control-label">Nome: </label>
                    <div class="col-sm-8">
                        <input type="text" name="nomecliente" id="nomecliente"  class="form-control" disabled value="<?php if (isset($cliente['dados']['cpf'])) echo $cliente['dados']['cpf']; ?>" >
                    </div>
                </div>
            </div>
        </div>
        
        <div class="row">
            <div class="col-md-4">
                <div class="form-group">
                    <label class="col-sm-4 control-label">CEP: </label>
                    <div class="col-sm-8">
                        <input type="text" name="cep" id="cep"  class="form-control" disabled value="<?php if (isset($cliente['dados']['cep'])) echo $cliente['dados']['cep']; ?>" >
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-sm-4 control-label">Complemento: </label>
                    <div class="col-sm-8">
                        <input type="text" name="complemento" id="complemento"  class="form-control maiusculo" disabled value="<?php if (isset($cliente['dados']['complemento'])) echo $cliente['dados']['complemento']; ?>">
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-sm-4 control-label">Cidade: </label>
                    <div class="col-sm-8">
                        <input type="text" name="cidade" id="cidade"  class="form-control maiusculo" disabled value="<?php if (isset($cliente['dados']['cidade'])) echo $cliente['dados']['cidade']; ?>">
                    </div>
                </div>
            </div>
            
            <div class="col-md-4">
                <div class="form-group">
                    <label class="col-sm-4 control-label">Rua: </label>
                    <div class="col-sm-8">
                        <input type="text" name="rua" id="rua" class="form-control maiusculo" disabled value="<?php if (isset($cliente['dados']['rua'])) echo $cliente['dados']['rua']; ?>">
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-sm-4 control-label">Bairro: </label>
                    <div class="col-sm-8">
                        <input type="text" name="bairro" id="bairro"  class="form-control maiusculo" disabled value="<?php if (isset($cliente['dados']['bairro'])) echo $cliente['dados']['bairro']; ?>">
                    </div>
                </div>
                <div class="form-group">
                    <div class="col-sm-8">
                        <a href="" target="_blank" onclick="verCadastroCliente(this)" >ver cadastro completo do cliente</a>
                    </div>
                </div>
            </div>
            
             <div class="col-md-4">
                 <div class="form-group">
                    <label class="col-sm-4 control-label">N&uacute;mero: </label>
                    <div class="col-sm-8">
                        <input type="text" name="numerorua" id="numerorua"  class="form-control maiusculo" disabled value="<?php if (isset($cliente['dados']['numeroRua'])) echo $cliente['dados']['numeroRua']; ?>">
                    </div>
                </div>
                 <div class="form-group">
                    <label class="col-sm-4 control-label">UF: </label>
                    <div class="col-sm-8">
                        <input type="text" name="uf" id="uf"  class="form-control maiusculo" disabled value="<?php if (isset($cliente['dados']['numeroRua'])) echo $cliente['dados']['numeroRua']; ?>">
                    </div>
                </div>
            </div>
            
        </div>
        
        <hr />
        
        <br />
        <p><i>Contratos já feitos pelo cliente</i></p>
        
        <div class="row">
            <div class="col-md-12">
                <table id="datatable" class="display" cellspacing="0" width="100%">
                    <thead>
                        <tr>
                            <th>Contrato</th>
                            <th>Banco</th>
                            <th>Operação</th>
                            <th>N&ordm; de vezes</th>
                            <th>Parcela</th>
                            <th>Valor Todal</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                    </tbody>
                </table>
            </div>
        </div>
        
        <hr />
        
        <div class="row">
            <div class="col-md-2">
                <div class="form-group">
                    <div class="row">
                        <div class="col-sm-12">
                            <label>Conta Banc&aacute;ria:</label>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-12">
                            <select id="contabancaria" class="form-control" >
                                <option></option>
                            </select>
                        </div>
                    </div>
                </div>
            </div>
            
            
            <div class="col-md-2">
                <div class="form-group">
                    <div class="row">
                        <div class="col-sm-12">
                            <label>Banco:</label>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-12">
                            <input type="text" class="form-control" disabled id="dadosBanco" />
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="col-md-2">
                <div class="form-group">
                    <div class="row">
                        <div class="col-sm-12">
                            <label>Conta:</label>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-12">
                            <input type="text" class="form-control" disabled id="dadosConta"  />
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="col-md-2">
                <div class="form-group">
                    <div class="row">
                        <div class="col-sm-12">
                            <label>Agencia:</label>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-12">
                            <input type="text" class="form-control" disabled id="dadosAgencia" />
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="col-md-2">
                <div class="form-group">
                    <div class="row">
                        <div class="col-sm-12">
                            <label>Tipo de Conta:</label>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-12">
                            <input type="text" class="form-control" disabled id="dadosTipoConta" />
                        </div>
                    </div>
                </div>
            </div>
            
        </div>
            
    </div>
</section>


<section class="panel panel-dados-contrato">
    <header class="panel-heading">
        <h2 class="panel-title">Dados do contrato</h2>
    </header>
    <div class="panel-body">
        <div class="row">
            <div class="col-md-2">
                <div class="form-group">
                    <div class="row">
                        <div class="col-sm-12">
                            <label>N&uacute;mero do contrato:</label>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-12">
                            <input type="text" class="form-control" disabled />
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="col-md-2">
                <div class="form-group">
                    <div class="row">
                        <div class="col-sm-12">
                            <label>Data:</label>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-12">
                            <input type="text" class="form-control" disabled />
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="col-md-2">
                <div class="form-group">
                    <div class="row">
                        <div class="col-sm-12">
                            <label>Status:</label>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-12">
                            <select class="form-control" disabled>
                                <option></option>
                            </select>
                        </div>
                    </div>
                </div>
            </div>
            
        </div>
        
        <div class="row">
            <div class="col-md-6">
                <div class="form-group">
                    <div class="row">
                        <div class="col-sm-12">
                            <label>Usu&aacute;rio:</label>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-12">
                            <input type="text" class="form-control" disabled />
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="row">
            <div class="col-md-2">
                <div class="form-group">
                    <div class="row">
                        <div class="col-sm-12">
                            <label>Banco:</label>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-12">
                            <select class="form-control" id="contratoBanco" >
                                <option></option>
                            </select>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-2">
                <div class="form-group">
                    <div class="row">
                        <div class="col-sm-12">
                            <label>Conv&ecirc;nio:</label>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-12">
                            <select class="form-control"  id="contratoConvenio">
                                <option></option>
                            </select>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="col-md-2">
                <div class="form-group">
                    <div class="row">
                        <div class="col-sm-12">
                            <label>Opera&ccedil;&atilde;o:</label>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-12">
                            <select class="form-control" id="contratoOperacao" >
                                <option></option>
                            </select>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="col-md-2">
                <div class="form-group">
                    <div class="row">
                        <div class="col-sm-12">
                            <label>Tabela:</label>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-12">
                            <select class="form-control" id="contratoTabela" >
                                <option></option>
                            </select>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="col-md-2">
                <div class="form-group">
                    <div class="row">
                        <div class="col-sm-12">
                            <label>Seguro:</label>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-12">
                            <select class="form-control" id="contratoSeguro" >
                                <option></option>
                            </select>
                        </div>
                    </div>
                </div>
            </div>
        
        </div>
        
        <div class="row">
            <div class="col-md-2">
                <div class="form-group">
                    <div class="row">
                        <div class="col-sm-12">
                            <label>Quantidade parcelas:</label>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-12">
                            <select class="form-control" >
                                <option></option>
                            </select>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="col-md-2">
                <div class="form-group">
                    <div class="row">
                        <div class="col-sm-12">
                            <label>Valor parcela:</label>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-12">
                            <input type="text" class="form-control" />
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="col-md-2">
                <div class="form-group">
                    <div class="row">
                        <div class="col-sm-12">
                            <label>Valor total:</label>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-12">
                            <input type="text" class="form-control" disabled />
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="col-md-2">
                <div class="form-group">
                    <div class="row">
                        <div class="col-sm-12">
                            <label>Valor l&iacute;quido:</label>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-12">
                            <input type="text" class="form-control" disabled />
                        </div>
                    </div>
                </div>
            </div>
        
        </div>
        
        
        
    </div>
</section>

<div class="row box-button-save">
    <div class="col-md-4 box-button-save">
         <button type="button" class="mb-xs mt-xs mr-xs btn btn-danger" onclick="salvar()"><i class="material-icons material-align-icons-button">&#xE877;</i>&nbsp;Salvar</button>
    </div>
</div>


<script>
    

function verCadastroCliente(a)
{
    a.href = '/cliente/cadastrar/'+ $('#cpf').val().replace(/[\.-]/gi,'');
}
    
    
    $.getCSS('/library/jsvendor/datatables/css/jquery.dataTables.min.css');
$.getScript( "/library/jsvendor/datatables/js/jquery.dataTables.min.js", function(  ) {
     /**
     * Inicia DataTable... CALLBACK executado após baixar dataTables.min.js
     * 
     **/
   table =  $('#datatable').DataTable({
           "bStateSave": false,
            "BLengthChange" : false,
             "iDisplayLength": 50,
             "bInfo": false,
             "bSort": false,  
             "bLengthChange": false,
             "paging": false,
             "searching": false,
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
              } 
        } );
}); // fim $.GetScript
    
$.getScript('/library/javascript/contratos/cadastrar.js')
    
    
$.getScript('/library/jsvendor/jquery-masked-input/jquery.maskedinput.min.js', function(){
    $('#cpf').mask('999.999.999-99',{placeholder:"___.___.___-__", autoclear: true});
 //   $('#cep').mask('99999-999',{placeholder:"_____-__", autoclear: true});
 //   $('#box-telefones div.panel-body input.fone').mask('(99)9999-99999?',{placeholder:"(__)____-_____", autoclear: false});
  //  $('.nb').mask('9999999999');
});
    
    
</script>