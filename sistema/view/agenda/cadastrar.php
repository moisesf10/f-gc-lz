<?php
$agenda = (isset($this->getParams('agenda')[0])) ? $this->getParams('agenda')[0] : null;
$convenio = $this->getParams('convenios');


if (\Application::getUrlParams(0) !== null && $agenda === null)
    \Application::print404();


?>
<style>
section.panel div.panel-body div.row {padding-bottom: 1rem;}
    .form-group-endereco {padding-left: 1.5rem}
    div.botoes {margin-left: 0.2rem;}
    div.botoes div {padding-right: 1rem; }
    .row-datatable {margin-top: 4rem;}
</style>

<section class="panel">
    <header class="panel-heading">
        <h2 class="panel-title">Cadastro de Agenda</h2>
    </header>
    <div class="panel-body">
            <div class="row">
                <div class="col-md-12">
                            <div class="form-group">
                                <label class="col-sm-1 control-label">Usuário: </label>
                                <div class="col-sm-9">
                                    <input type="text"  class="form-control" disabled value="<?php  if (is_array($agenda) && count($agenda) > 0) echo $agenda['nomeUsuario']; ?>" >
                                </div>
                            </div>
                    </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                            <div class="form-group">
                                <label class="col-sm-1 control-label">Criado em: </label>
                                <div class="col-sm-3">
                                    <input type="text"  class="form-control" disabled value="<?php  if (is_array($agenda) && count($agenda) > 0) echo $agenda['created']; ?>" >
                                </div>
                                
                                <label class="col-sm-1 control-label">Alterado em: </label>
                                <div class="col-sm-2">
                                    <input type="text"  class="form-control" disabled value="<?php  if (is_array($agenda) && count($agenda) > 0) echo $agenda['modified']; ?>" >
                                </div>
                                
                                <label class="col-sm-1 control-label"><b>Data Ligação:</b> </label>
                                <div class="col-sm-2">
                                    <input type="text" id="dataligacao"  class="form-control" data-plugin-datepicker data-input-mask="99/99/9999" placeholder="__/__/____" value="<?php  if (is_array($agenda) && count($agenda) > 0 && isset(explode(' ', $agenda['dataLigacao'])[0])) echo explode(' ', $agenda['dataLigacao'])[0]; ?>" >
                                </div>
                                
                            </div>
                    </div>
            </div>
        
            <div class="row">
                <div class="col-md-12">
                            <div class="form-group">
                                <label class="col-sm-1 control-label">CPF: </label>
                                <div class="col-sm-2">
                                    <input type="text" id="cpf"  class="form-control" value="<?php  if (is_array($agenda) && count($agenda) > 0) echo $agenda['cpfCliente']; ?>" >
                                </div>
                                <div class="col-sm-1">
                                    <button class="btn btn-grey" onclick="buscarCliente()">&nbsp;Carregar</button>
                                </div>
                                <label class="col-sm-1 control-label">Nascimento: </label>
                                <div class="col-sm-2">
                                    <input type="text" id="nascimento"  class="form-control" data-plugin-datepicker data-input-mask="99/99/9999" placeholder="__/__/____" value="<?php  if (is_array($agenda) && count($agenda) > 0) echo $agenda['nascimentoCliente']; ?>" >
                                </div>
                                <label class="col-sm-1 control-label"><b>Hora Ligação:</b> </label>
                                <div class="col-sm-2">
                                    <input type="text" id="horaligacao"   class="form-control"  value="<?php  if (is_array($agenda) && count($agenda) > 0 && isset(explode(' ', $agenda['dataLigacao'])[1])) echo explode(' ', $agenda['dataLigacao'])[1]; ?>" >
                                </div>
                              
                            </div>
                    </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                            <div class="form-group">
                                <label class="col-sm-1 control-label">Nome Cliente: </label>
                                <div class="col-sm-9">
                                    <input type="text" id="nome"  class="form-control" value="<?php  if (is_array($agenda) && count($agenda) > 0) echo $agenda['nomeCliente']; ?>" >
                                </div>
                            </div>
                    </div>
            </div>
        
            <div class="row">
                <div class="form-group-endereco">
                    <div class="col-md-3">
                        <div class="row"><label class="col-sm-12 control-label">CEP</label></div>
                        <div class="row">
                            <div class="col-sm-9">
                                <input type="text" id="cep"   class="form-control" value="<?php  if (is_array($agenda) && count($agenda) > 0) echo $agenda['cep']; ?>" >
                            </div>
                             <div class="col-sm-3">
                                <button class="btn btn-grey" onclick="buscarCep()">&nbsp;Pesquisar</button>
                            </div>
                        </div> 
                    </div>
                    <div class="col-md-4">
                        <div class="row"><label class="col-sm-12 control-label">Rua</label></div>
                        <div class="row">
                            <div class="col-sm-12">
                                <input type="text" id="rua"  class="form-control" value="<?php  if (is_array($agenda) && count($agenda) > 0) echo $agenda['rua']; ?>" >
                            </div>
                             
                        </div> 
                    </div>
                    <div class="col-md-3">
                        <div class="row"><label class="col-sm-12 control-label">Número</label></div>
                        <div class="row">
                            <div class="col-sm-12">
                                <input type="text" id="numero"  class="form-control" value="<?php  if (is_array($agenda) && count($agenda) > 0) echo $agenda['numero']; ?>" >
                            </div>
                             
                        </div> 
                    </div>
                </div>
            </div>
        
            <div class="row">
                    <div class="form-group-endereco">
                        <div class="col-md-3">
                            <div class="row"><label class="col-sm-12 control-label">Complemento</label></div>
                            <div class="row">
                                <div class="col-sm-12">
                                    <input type="text" id="complemento"   class="form-control" value="<?php  if (is_array($agenda) && count($agenda) > 0) echo $agenda['complemento']; ?>" >
                                </div>
                                 
                            </div> 
                        </div>
                        <div class="col-md-3">
                            <div class="row"><label class="col-sm-12 control-label">Bairro</label></div>
                            <div class="row">
                                <div class="col-sm-12">
                                    <input type="text" id="bairro"  class="form-control" value="<?php  if (is_array($agenda) && count($agenda) > 0) echo $agenda['bairro']; ?>" >
                                </div>

                            </div> 
                        </div>
                        <div class="col-md-4">
                            <div class="row"><label class="col-sm-12 control-label">Cidade</label></div>
                            <div class="row">
                                <div class="col-sm-12">
                                    <input type="text" id="cidade"  class="form-control" value="<?php  if (is_array($agenda) && count($agenda) > 0) echo $agenda['cidade']; ?>" >
                                </div>

                            </div> 
                        </div>
                    </div>
                </div>
        
            <div class="row">
                    <div class="form-group-endereco">
                        <div class="col-md-2">
                            <div class="row"><label class="col-sm-12 control-label">UF</label></div>
                            <div class="row">
                                <div class="col-sm-12">
                                    <select class="form-control mb-md" name="uf" id="uf">
                                        <?php
                                            $uf = (isset($agenda['uf'])) ? $agenda['uf'] : null;
                                        ?>
                                        <option></option>
                                        <option value="AC" <?php  if (strtoupper($uf) == 'MG')  echo 'selected="selected"';  ?>>AC</option>
                                        <option value="AL" <?php  if (strtoupper($uf) == 'AL')  echo 'selected="selected"';  ?>>AL</option>
                                        <option value="AM" <?php  if (strtoupper($uf) == 'AM')  echo 'selected="selected"';  ?>>AM</option>
                                        <option value="AP" <?php  if (strtoupper($uf) == 'AP')  echo 'selected="selected"';  ?>>AP</option>
                                        <option value="BA" <?php  if (strtoupper($uf) == 'BA')  echo 'selected="selected"';  ?>>BA</option>
                                        <option value="CE" <?php  if (strtoupper($uf) == 'CE')  echo 'selected="selected"';  ?>>CE</option>
                                        <option value="DF" <?php  if (strtoupper($uf) == 'DF')  echo 'selected="selected"';  ?>>DF</option>
                                        <option value="ES" <?php  if (strtoupper($uf) == 'ES')  echo 'selected="selected"';  ?>>ES</option>
                                        <option value="GO" <?php  if (strtoupper($uf) == 'GO')  echo 'selected="selected"';  ?>>GO</option>
                                        <option value="MA" <?php  if (strtoupper($uf) == 'MA')  echo 'selected="selected"';  ?>>MA</option>
                                        <option value="MG" <?php  if (strtoupper($uf) == 'MG')  echo 'selected="selected"';  ?>>MG</option>
                                        <option value="MS" <?php  if (strtoupper($uf) == 'MS')  echo 'selected="selected"';  ?>>MS</option>
                                        <option value="MT" <?php  if (strtoupper($uf) == 'MT')  echo 'selected="selected"';  ?>>MT</option>
                                        <option value="PA" <?php  if (strtoupper($uf) == 'PA')  echo 'selected="selected"';  ?>>PA</option>
                                        <option value="PB" <?php  if (strtoupper($uf) == 'PB')  echo 'selected="selected"';  ?>>PB</option>
                                        <option value="PE" <?php  if (strtoupper($uf) == 'PE')  echo 'selected="selected"';  ?>>PE</option>
                                        <option value="PI" <?php  if (strtoupper($uf) == 'PI')  echo 'selected="selected"';  ?>>PI</option>
                                        <option value="PR" <?php  if (strtoupper($uf) == 'PR')  echo 'selected="selected"';  ?>>PR</option>
                                        <option value="RJ" <?php  if (strtoupper($uf) == 'RJ')  echo 'selected="selected"';  ?>>RJ</option>
                                        <option value="RN" <?php  if (strtoupper($uf) == 'RN')  echo 'selected="selected"';  ?>>RN</option>
                                        <option value="RS" <?php  if (strtoupper($uf) == 'RS')  echo 'selected="selected"';  ?>>RS</option>
                                        <option value="RO" <?php  if (strtoupper($uf) == 'RO')  echo 'selected="selected"';  ?>>RO</option>
                                        <option value="RR" <?php  if (strtoupper($uf) == 'RR')  echo 'selected="selected"';  ?>>RR</option>
                                        <option value="SC" <?php  if (strtoupper($uf) == 'SC')  echo 'selected="selected"';  ?>>SC</option>
                                        <option value="SE" <?php  if (strtoupper($uf) == 'SE')  echo 'selected="selected"';  ?>>SE</option>
                                        <option value="SP" <?php  if (strtoupper($uf) == 'SP')  echo 'selected="selected"';  ?>>SP</option>
                                        <option value="TO" <?php  if (strtoupper($uf) == 'TO')  echo 'selected="selected"';  ?>>TO</option>
                                    </select>
                                    
                                </div>
                                 
                            </div> 
                        </div>
                        <div class="col-md-2">
                            <div class="row"><label class="col-sm-12 control-label">Tipo Cliente</label></div>
                            <div class="row">
                                <div class="col-sm-12">
                                    <select class="form-control" id="tipocliente">
                                        <option></option>
                                        <option value="Cliente Interno" <?php if (isset($agenda['tipoCliente']) && $agenda['tipoCliente'] == 'Cliente Interno') echo 'selected="selected"'; ?>>Cliente Interno</option>
                                        <option value="Cliente Externo" <?php if (isset($agenda['tipoCliente']) && $agenda['tipoCliente'] == 'Cliente Externo') echo 'selected="selected"'; ?>>Cliente Externo</option>
                                    </select>
                                </div>

                            </div> 
                        </div>
                        <div class="col-md-3">
                            <div class="row"><label class="col-sm-12 control-label">Convênio</label></div>
                            <div class="row">
                                <div class="col-sm-12">
                                    <select class="form-control" id="convenio">
                                        <option></option>
                                        <?php
                                            if (is_array($convenio))
                                                foreach($convenio as $i => $value)
                                                {
                                                    $selected = ($agenda['idConvenio'] == $value['id']) ? 'selected="selected"' : '';
                                                    echo '<option '. $selected . 'value="' . $value['id'] . '">'. $value['nome']. '</option>';
                                                }
                                        ?>
                                    </select>
                                </div>

                            </div> 
                        </div>
                        <div class="col-md-3">
                            <div class="row"><label class="col-sm-12 control-label">Status Ligação</label></div>
                            <div class="row">
                                <div class="col-sm-12">
                                    <select class="form-control" id="status">
                                        <option></option>
                                        <option value="Pendente" <?php if (isset($agenda['status']) && $agenda['status'] == 'Pendente') echo 'selected="selected"'; ?>>Pendente</option>
                                        <option value="Efetuada" <?php if (isset($agenda['status']) && $agenda['status'] == 'Efetuada') echo 'selected="selected"'; ?>>Efetuada</option>
                                    </select>
                                </div>

                            </div> 
                        </div>
                    </div>
                </div>
        
            <div class="row">
                    <div class="form-group-endereco">
                        <div class="col-md-12">
                            <div class="row"><label class="col-sm-12 control-label">E-mail</label></div>
                            <div class="row">
                                <div class="col-sm-12">
                                    <input type="text" id="email"   class="form-control" value="<?php  if (is_array($agenda) && count($agenda) > 0) echo $agenda['emailCliente']; ?>" >
                                </div>
                                 
                            </div> 
                        </div>
                </div>
            </div>
        
             <div class="row">
                <div class="form-group-endereco">
                    <div class="col-md-12">
                        <div class="row"><label class="col-sm-12 control-label">Observações</label></div>
                        <div class="row">
                            <div class="col-sm-12">
                                <textarea class="form-control" id="observacoes" maxlength="4999" rows="5" ><?php if (isset($agenda['observacoes'])) echo $agenda['observacoes']; ?></textarea>
                            </div>

                        </div> 
                    </div>
                </div>
            </div>
        
            <div class="row">
                <div class="row">
                    <div class="col-md-12 text-right">
                        <button class="mb-xs mt-xs mr-xs btn btn-success" onclick="adicionarTelefone()">+ Adicionar Telefone</button> 
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                            <table id="datatable" class="display" cellspacing="0" width="100%">
                                <thead>
                                    <tr>
                                        <th>Telefone</th>
                                        <th>Referência</th>
                                        <th>Excluir</th>
                                    </tr>
                                </thead>
                                <tbody>
                                   <?php
                                    if (is_array($agenda['telefones']))
                                        foreach($agenda['telefones'] as $i => $value)
                                        {
                                            ?>
                                            <tr>
                                                <td><input type="text" class="form-control" value="<?php echo $value['numero']; ?>" /> </td>
                                                <td><input type="text" class="form-control" value="<?php echo $value['referencia']; ?>" /> </td>
                                                <td><button type="button"  class="mb-xs mt-xs mr-xs btn btn-warning" onclick="table.row( $(this).parents('tr')).remove().draw()">Remover</button></td>
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
        if (\Application::isAuthorized(ucfirst(strtolower('clientes')) , 'agenda', 'escrever') )
        {?>
        <button type="button" class="mb-xs mt-xs mr-xs btn btn-danger" onclick="salvar()"><i class="material-icons material-align-icons-button">&#xE877;</i>&nbsp;Salvar</button>
        <?php } ?>
        
        <?php
        if (\Application::getUrlParams(0) !== null &&  \Application::isAuthorized(ucfirst(strtolower('clientes')) , 'agenda', 'remover') )
        {?>
            <button type="button" class="mb-xs mt-xs mr-xs btn btn-grey" onclick="remover()"><i class="material-icons material-align-icons-button"></i>&nbsp;Remover</button>
        <?php } ?>
        
      
        <button type="button" class="mb-xs mt-xs mr-xs btn btn-grey" onclick="document.location='/<?php echo strtolower(\Application::getNameController()); ?>/cadastrar-agenda/'"><i class="material-icons material-align-icons-button">&#xE145;</i>&nbsp;Novo</button>
        
    
    </div>
</section>


<script src="/library/jsvendor/bootstrap-datepicker/js/bootstrap-datepicker.js" ></script>

<script>
$.getScript('/library/javascript/agenda/cadastrar.js');
    
     
    
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
                 "sZeroRecords": "Nenhum telefone cadastrado. Clique no botão acima &quot;Adicionar Telefone&quot; para cadastrar" ,
                 "sInfo": "Mostrando página _PAGE_ de _PAGES_",
                 "sInfoFiltered": "(Filtrado _MAX_ do total)"
              } 
        } );
}); // fim $.GetScript    
    
    
$.getScript('/library/jsvendor/jquery-masked-input/jquery.maskedinput.min.js', function(){
    $('#cpf').mask('999.999.999-99',{placeholder:"___.___.___-__", autoclear: false});
    $('#cep').mask('99999-999',{placeholder:"_____-__", autoclear: true});
    $('#horaligacao').mask('99?:99',{placeholder:"_____-__", autoclear: true});
    $('#datatable tr td:first-child input').mask('(99)9999-99999?',{placeholder:"(__)____-_____", autoclear: false});
 //   $('#box-telefones div.panel-body input.fone').mask('(99)9999-99999?',{placeholder:"(__)____-_____", autoclear: false});
  //  $('.nb').mask('9999999999');
});
    
function adicionarTelefone()
{
    var dataUuid = 'telefone-'+ new Date().getTime();
    table.row.add( [
            '<input type="text" class="form-control" data-Uuid="'+ dataUuid  +'" />',
            '<input type="text" class="form-control" />',
            '<button type="button" class="mb-xs mt-xs mr-xs btn btn-warning" onclick="table.row( $(this).parents(\'tr\') ).remove().draw()">Remover</button>'
            
        ] ).draw( false );
     $('#datatable [data-uuid="'+  dataUuid +'"]').mask('(99)9999-99999?',{placeholder:"(__)____-_____", autoclear: false});
}

<?php
    if ($agenda !== null)
        echo 'key=' . $agenda['id']. ';';
?>

</script>