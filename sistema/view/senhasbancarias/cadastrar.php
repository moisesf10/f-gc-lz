<?php
$senhaBanco = (isset($this->getParams('senhabanco')[0])) ? $this->getParams('senhabanco')[0] : null;
$bancos = $this->getParams('bancos');
//$promotoras = $this->getParams('promotoras');
//echo '<pre>'; var_dump($senhaBanco); exit;
if (\Application::getUrlParams(0) !== null && $senhaBanco === null)
    \Application::print404();

$lerMaster = \Application::isAuthorized('Cadastros Basicos' , 'Senhas_bancos_master', 'ler');
$escreverMaster = \Application::isAuthorized('Cadastros Basicos' , 'Senhas_bancos_master', 'escrever');
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
        <h2 class="panel-title">Cadastro de Senhas Bancárias</h2>
    </header>
    <div class="panel-body">
        
        <div class="row">
            <div class="col-md-4">
                <div class="row">
                    <div class="col-md-12">
                        <label>Banco</label>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <select id="banco" class="form-control">
                            <option></option>
                            <?php 
                                if (is_array($bancos))
                                    foreach($bancos as $i => $value)
                                    {
                                        $selected =  (isset($senhaBanco['idBanco']) && $senhaBanco['idBanco'] == $value['id']) ? 'selected="selected"' : '';
                                        echo '<option '. $selected . ' value="'. $value['id'] . '">'. $value['nome'] . '</option>';
                                    }
                            ?>
                        </select>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="row">
                    <div class="col-md-12">
                        <label>Promotora</label>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <input type="text" class="form-control" id="promotora" value="<?php if (isset($senhaBanco['nomePromotora'])) echo $senhaBanco['nomePromotora']; ?>" >
                        
                        
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="row">
                    <div class="col-md-12">
                        <label>Link do Portal</label>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <input type="text" id="link" class="form-control" value="<?php if(isset($senhaBanco['link'])) echo $senhaBanco['link']; ?>">
                    </div>
                </div>
            </div>
        </div>
        
        
        <div class="row <?php if($lerMaster == false && $escreverMaster == false) echo 'hidden'; ?> ">
            <div class="col-md-4">
                <div class="row">
                    <div class="col-md-12">
                        <label>Login Master</label>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <input type="text" id="loginmaster" class="form-control" value="<?php if(isset($senhaBanco['loginMaster'])) echo $senhaBanco['loginMaster']; ?>" <?php if($escreverMaster == false) echo 'disabled="disabled"'; ?>>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="row">
                    <div class="col-md-12">
                        <label>Senha Master</label>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <input type="text" id="senhamaster" class="form-control" value="<?php if(isset($senhaBanco['senhaMaster'])) echo $senhaBanco['senhaMaster']; ?>" <?php if($escreverMaster == false) echo 'disabled="disabled"'; ?>>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="row">
                    <div class="col-md-12">
                        <label>email</label>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <input type="text" id="emailmaster" class="form-control" value="<?php if(isset($senhaBanco['emailMaster'])) echo $senhaBanco['emailMaster']; ?>" <?php if($escreverMaster == false) echo 'disabled="disabled"'; ?>>
                    </div>
                </div>
            </div>
        </div>
        
        
        <div class="row">
            <div class="col-md-12">
                <div class="row">
                    <div class="col-md-12">
                        <label>Observações</label>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <textarea id="observacao" class="form-control" rows="5"><?php if(isset($senhaBanco['observacao'])) echo $senhaBanco['observacao']; ?></textarea>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="row">
                <div class="row">
                    <div class="col-md-12 text-right">
                        <button class="mb-xs mt-xs mr-xs btn btn-success" onclick="adicionarSenha()">+ Adicionar Senha</button> 
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                            <table id="datatable" class="display" cellspacing="0" width="100%">
                                <thead>
                                    <tr>
                                        <th>Usuário Padrão</th>
                                        <th>Senha Padrão</th>
                                        <th>Nome Usuário</th>
                                        <th>Excluir</th>
                                    </tr>
                                </thead>
                                <tbody>
                                   <?php
                                    if (isset($senhaBanco['senhas']) && is_array($senhaBanco['senhas']))
                                        foreach($senhaBanco['senhas'] as $i => $value)
                                        {
                                            if ($value['senha'] != '')
                                            {
                                            ?>
                                            <tr>
                                                <td><input type="text" class="form-control" value="<?php echo $value['login']; ?>" /> </td>
                                                <td><input type="text" class="form-control" value="<?php echo $value['senha']; ?>" /> </td>
                                                <td><input type="text" class="form-control" value="<?php echo $value['nome']; ?>" /> </td>
                                                <td><button type="button"  class="mb-xs mt-xs mr-xs btn btn-warning" onclick="table.row( $(this).parents('tr')).remove().draw()">Remover</button></td>
                                            </tr>
                                    <?php
                                            }
                                        }


                                    ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
            </div>
        
        
        <div class="row botoes">
        <?php
        if (\Application::isAuthorized('Cadastros Basicos' , 'senhas_bancos', 'escrever') )
        {?>
        <button type="button" class="mb-xs mt-xs mr-xs btn btn-danger" onclick="salvar()"><i class="material-icons material-align-icons-button">&#xE877;</i>&nbsp;Salvar</button>
        <?php } ?>
        
        <?php
        if (\Application::getUrlParams(0) !== null &&  \Application::isAuthorized('Cadastros Basicos' , 'senhas_bancos', 'remover') )
        {?>
            <button type="button" class="mb-xs mt-xs mr-xs btn btn-grey" onclick="remover()"><i class="material-icons material-align-icons-button"></i>&nbsp;Remover</button>
        <?php } ?>
        
      
        <button type="button" class="mb-xs mt-xs mr-xs btn btn-grey" onclick="document.location='/<?php echo strtolower(\Application::getNameController()); ?>/cadastrar-senha-bancaria/'"><i class="material-icons material-align-icons-button">&#xE145;</i>&nbsp;Novo</button>
        
    
    </div>
        
        
    </div>
</section>



<script src="/library/jsvendor/bootstrap-datepicker/js/bootstrap-datepicker.js" ></script>

<script>
$.getScript('/library/javascript/senhasbancarias/cadastrar.js');
    
     
    
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
                 "sZeroRecords": "Nenhum senha cadastrada. Clique no botão acima &quot;Adicionar Senha&quot; para cadastrar" ,
                 "sInfo": "Mostrando página _PAGE_ de _PAGES_",
                 "sInfoFiltered": "(Filtrado _MAX_ do total)"
              } 
        } );
}); // fim $.GetScript    
    
  
function adicionarSenha()
{
    var dataUuid = 'telefone-'+ new Date().getTime();
    table.row.add( [
            '<input type="text" class="form-control" data-Uuid="'+ dataUuid  +'" />',
            '<input type="text" class="form-control" />',
        '<input type="text" class="form-control" />',
            '<button type="button" class="mb-xs mt-xs mr-xs btn btn-warning" onclick="table.row( $(this).parents(\'tr\') ).remove().draw()">Remover</button>'
            
        ] ).draw( false );
     
}

<?php
    if ($senhaBanco !== null)
        echo 'key=' . $senhaBanco['id']. ';';
?>

</script>