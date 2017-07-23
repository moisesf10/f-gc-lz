<?php
$grupo = (isset($this->getParams('grupo')[0])) ? $this->getParams('grupo')[0] : null;
$atribuicoes = $this->getParams('atribuicoes');

//var_dump($atribuicoes);

/*
$supervisor = null;
if (is_array($atribuicoes) && count($atribuicoes) > 0)
    foreach($atribuicoes as $i => $value)
        if ($value['indicaSupervisor'] == true)
        {
            $supervisor['id'] = $value['idUsuario'];
            $supervisor['cpf'] = $value['cpf'];
            $supervisor['nome'] = $value['nomeUsuario'];
            $supervisor['email'] = $value['email'];
            $supervisor['dataNascimento'] = $value['dataNascimento'];
            $supervisor['status'] = $value['statusUsuario'];
            $supervisor['bonus'] = $value['comissaoSupervisor'];
            $supervisor['tipo'] = ($value['indicaSupervisor'])? 'supervisor' : 'usuario';
            
        }

*/

if (! is_array($grupo) || count($grupo) < 1 )
    \Application::print404();
?>

<style>
section.panel div.panel-body div.row {padding-bottom: 1rem;}
div.subtitulo{padding-top: 1rem;}
div.subtitulo p{font-style: italic;}
.box-supervisor, .box-atribuir-usuario{margin-top: 4rem; padding-top: 2rem; border-top:1px solid #e0e0e0;}
    #buttonAdicionarUsuario {margin-top: 0px !important;}   
    .box-comissao-supervisor {display: none;} 
    .autocomplete-suggestions { border: 1px solid #999; background: #FFF; overflow: auto; }
.autocomplete-suggestion { padding: 2px 5px; white-space: nowrap; overflow: hidden; }
.autocomplete-selected { background: #F0F0F0; }
.autocomplete-suggestions strong { font-weight: normal; color: #3399FF; }
.autocomplete-group { padding: 2px 5px; }
.autocomplete-group strong { display: block; border-bottom: 1px solid #000; }
</style>


<form>
<section class="panel">
        <header class="panel-heading">
            <h2 class="panel-title">Atribuição de Usuários para Grupos</h2>
        </header>
        <div class="panel-body">
            <div class="row">
                <div class="col-md-9">
                            <div class="form-group">
                                <label class="col-sm-2 control-label">ID: </label>
                                <div class="col-sm-4">
                                    <input type="text" id="id" name="id"  class="form-control" disabled value="<?php  if (is_array($grupo) && count($grupo) > 0) echo $grupo['id']; ?>" >
                                </div>
                            </div>
                    </div>
            </div>
            <div class="row">
                <div class="col-md-9">
                            <div class="form-group">
                                <label class="col-sm-2 control-label">Nome do Grupo: </label>
                                <div class="col-sm-4">
                                    <input type="text" id="nomeGrupo" name="nomeGrupo"  class="form-control" disabled value="<?php  if (is_array($grupo) && count($grupo) > 0) echo $grupo['nome']; ?>" >
                                </div>
                            </div>
                    </div>
            </div>
           
            <!--
            <div class="row box-supervisor">
                <div class="col-md-4">
                    <div class="row">
                        <div class="col-md-12">
                            <label>Defina o supervisor do grupo</label>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <input type="text" id="supervisor" name="supervisor" class="form-control" placeholder="Nome do Supervisor" value="<?php if ($supervisor != null) echo $supervisor['nome']; ?>" />
                        </div>
                    </div>
                </div>
                
                <div class="col-md-2">
                    <div class="row">
                        <div class="col-md-12">
                            <label>Comissão Supervisor (%)</label>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <input type="text" id="bonussupervisor" name="bonussupervisor" class="form-control" value="<?php if ($supervisor != null) echo $supervisor['bonus']; ?>" />
                        </div>
                    </div>
                </div>
                
                <div class="col-md-2">
                    <div class="row">
                        <div class="col-md-12">
                            <label>Comissão Vendedor (%)</label>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <input type="text" id="bonussupervisorvendedor" name="bonussupervisorvendedor" class="form-control" value="<?php if ($supervisor != null) echo $supervisor['comissaoAgente']; ?>" />
                        </div>
                    </div>
                </div>
            </div>
            -->
            
            <div class="row box-atribuir-usuario">
                <div class="col-md-12">
                    <div class="row">
                        <div class="col-md-12">
                            <p>Abaixo, pesquise um usuário que fará parte do grupo e clique no botão ADICIONAR para fazer a inclusão.</p>
                        </div>
                    </div>
                    <div class="row">
                            <div class="col-md-12">
                                    <div class="col-md-4">
                                        <div class="row">
                                                <div class="col-md-12">
                                                    <label>Vendedor</label>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-md-12">
                                                    <input type="text" id="usuario" name="usuario" class="form-control" placeholder="Nome do Usuário" />
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-2">
                                            <div class="row">
                                                    <div class="col-md-12">
                                                        <label>Tipo de Vendedor</label>
                                                    </div>
                                                </div>
                                            <div class="row">
                                                    <div class="col-md-12">
                                                            <select id="selectTipoAgente" class="form-control">
                                                                <option value="Vendedor">Vendedor</option>
                                                                <option value="Supervisor">Supervisor</option>
                                                            </select>
                                                </div>
                                            </div>
                                        </div>
                                <!--
                                        <div class="col-md-2">
                                                <div class="row">
                                                    <div class="col-md-12">
                                                        <label>Comissão Agente (%)</label>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-md-12">
                                                        <input type="text" id="comissaoagente"  class="form-control"  />
                                                    </div>
                                                </div>
                                            </div>
                                -->

                                        <div class="col-md-2 box-comissao-supervisor">
                                                <div class="row">
                                                    <div class="col-md-12">
                                                        <label>Comissão Supervisor (%)</label>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-md-12">
                                                        <input type="text" id="comissaosupervisor"class="form-control"  />
                                                    </div>
                                                </div>
                                            </div>


                                        <div class="col-md-2">
                                            <div class="row">
                                                    <div class="col-md-12">
                                                        <label>&nbsp;</label>
                                                    </div>
                                                </div>
                                            <div class="row">
                                                    <div class="col-md-12">
                                                            <button type="button" id="buttonAdicionarUsuario" class="mb-xs mt-xs mr-xs btn btn-grey" onclick="adicionarUsuario()"><i class="material-icons material-align-icons-button">&#xE145;</i>&nbsp;Adicionar</button>
                                                </div>
                                            </div>
                                        </div>
                            </div>
                    </div>
                </div>
            </div>
            
            
            <div class="row">
                 <table cellpadding="0" cellspacing="0" border="0" class="display" id="datatable">
                       <thead>
                            <tr>
                                <th>CPF</th>
                                <th>Nome do Vendedor</th>
                                <th>Tipo de Vendedor</th>
                                <th>Comissao Supervisor</th>
                                <th>Remover</th>
                            </tr>
                        </thead>
                     <tbody>
                        <?php 
                            if (is_array($atribuicoes))
                                 foreach($atribuicoes as $i => $value)
                                 {

                                    echo '<tr>';
                                            echo '<td>'. $value['cpf'] . '</td>';
                                            echo '<td>'. $value['nomeUsuario'] . '</td>';
                                            echo '<td>'. (($value['indicaSupervisor'] == true) ? 'Supervisor' : 'Vendedor') . '</td>';
                                            echo '<td>'. $value['comissaoSupervisor'] . '</td>';
                                            echo '<td><a class="botao-remover" onclick="removerUsuario($(this).parents(\'tr\') )"><i class="material-icons">&#xE92B;</i></a></td>';
                                     echo '</tr>';
                                 }
                         ?>
                     </tbody>
                </table>
            </div>
            
            <button type="button" class="mb-xs mt-xs mr-xs btn btn-danger" onclick="salvar()"><i class="material-icons material-align-icons-button">&#xE877;</i>&nbsp;Salvar</button>
        </div>
     
    </section>
</form>


<script>
    
    
$.getScript('/library/jsvendor/jquery-maskmoney/dist/jquery.maskMoney.min.js', function(){
     $('#comissaosupervisor, #comissaoagente').maskMoney({prefix:'', allowNegative: false, thousands:',', decimal:'.', affixesStay: true, allowZero: true});
   
});    
    
    
    
atribuicoes = [];
usuarioTemporario = null;
    
$(function(){
 
    
    // ---
    $('#selectTipoAgente').change(function(){
        if ($(this).val() == 'Vendedor')
        {
            $('#comissaosupervisor').val('0.00');
            $('.box-comissao-supervisor').hide();
        }else
            $('.box-comissao-supervisor').show();
    });
    // -----
    
    
});
    
    
function adicionarUsuario()
{
    
    if (usuarioTemporario != null  )
    {
       
        
        var tipoAgente = $('#selectTipoAgente').val();
       // var valorComissaoAgente = $('#comissaoagente').val();
        var valorComissaoSupervisor = ($('#selectTipoAgente').val() == 'Supervisor') ? $('#comissaosupervisor').val() : '0.00';
        
        var dataUuid = 'comissao-'+ new Date().getTime();
        t.row.add( [
            usuarioTemporario.cpf,
            usuarioTemporario.nome,
            tipoAgente,
            valorComissaoSupervisor,
           // '<input type="text" data-uuid="'+ dataUuid + '" class="form-control" />',
            '<a class="botao-remover" onclick="removerUsuario($(this).parents(\'tr\') )"><i class="material-icons">&#xE92B;</i></a>'
        ] ).draw( false );
        
        usuarioTemporario.bonus = valorComissaoSupervisor;
       
        usuarioTemporario.tipo = tipoAgente;
        
        $('[data-uuid="' + dataUuid  +'"]').maskMoney({prefix:'', allowNegative: false, thousands:',', decimal:'.', affixesStay: true, allowZero: true});
         atribuicoes.push(usuarioTemporario.toObject());
        usuarioTemporario = null;
        $('#usuario').val('');
        
    }else
        alert('O usuário fornecido é inválido. Favor, selecionar um usuário da lista');
    
}
    
function removerUsuario(dataTableRow)
{
    //console.log(dataTableRow);
    var cpf = dataTableRow.find('td').eq(0).text();
    t.row(dataTableRow).remove().draw();
    for(var i in atribuicoes)
            if (atribuicoes[i].cpf == cpf)
                atribuicoes.splice(i, 1);
}
    
    
function salvar()
{
 
    var grupo = $('#id').val();
  
    $.ajax({
        type: "POST",
        url: '<?php echo '/'. strtolower(\Application::getNameController()) . '/salvar-atribuicao-grupo/'; ?>',
        cache: false,
        dataType: "json",
        data: 'grupo='+grupo + '&atribuicoes='+JSON.stringify(atribuicoes),
        success: function(json){
            if (json.success)
                document.location.reload();
                //alert('suceeo');
            else
            {
                alert('Não foi possível realizar a alteração\nMotivo: ' + json.message);
              //  document.location.reload();
            }
        }
    });
    
}
    
    

$.getScript('/library/jsvendor/jquery-autocomplete/src/jquery.autocomplete.js', function(){
    
    
        $('#usuario').autocomplete({
            serviceUrl: "/<?php echo strtolower(\Application::getNameController()); ?>/buscar-usuario-grupo/",
            paramName: 'nome',
            dataType: 'json',
            minChars: 3,
            formatResult: function(suggestion, currentValue){
                // zera os identificadores para evitar que o usuário apague o nome antes de submeter
                supervisor = '';
                var html = '';
                if (suggestion.data.status == 1)
                    html = '<p><span>' +suggestion.value +'</span> - <label>'+ suggestion.data.cpf +'</label></p>';
                return html; 
           },
            onSelect: function (suggestion) {
                // verifica se o nome escolhido não é o de um supervisor
                var indicaAdicionar = true;
                for(var i in atribuicoes)
                     if ( atribuicoes[i].cpf == suggestion.data.cpf )
                     {
                        alert('"' + suggestion.data.nome + '" já faz parte deste grupo');
                        $(this).val('');
                        indicaAdicionar = false;
                        break;
                     }      
                        
                
                    usuarioTemporario = null;
                    if (indicaAdicionar)
                    {
                        usuarioTemporario = new Usuario();
                        usuarioTemporario.init();
                        usuarioTemporario.setId(suggestion.data.id);
                        usuarioTemporario.setCpf(suggestion.data.cpf);
                        usuarioTemporario.setNome(suggestion.data.nome);
                        
                        //usuarioTemporario.setComissao(suggestion.data.comissao);
                       // usuarioTemporario.setBonus(suggestion.data.)
                        
                        //atribuicoes.push(usuarioTemporario.toObject());
                    }
                
            }
            
        });
});
    


    
    
$.getCSS('/library/jsvendor/datatables/css/jquery.dataTables.min.css');
// Carregar JS de forma sincronizada. Desta forma primeiro carrega $.getScript e depois $.ajax como callback
$.getScript( "/library/jsvendor/datatables/js/jquery.dataTables.min.js", function(  ) {
      /**
     * Inicia DataTable... CALLBACK executado após baixar dataTables.min.js
     * 
     **/
   
     t = $('#datatable').DataTable({
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
			 "sZeroRecords": "Nenhum agente cadastrado" ,
             "sInfo": "Mostrando página _PAGE_ de _PAGES_",
             "sInfoFiltered": "(Filtrado _MAX_ do total)"
		  } 
    } );
    
}); // fim $.GetScript
    

var Usuario = (function(){
    "use strict";
    var id = null;
    var cpf = null;
    var nome = null;
    var email = null;
    var dataNascimento = null;
    var status = null;
    var bonus = null;
    var tipo = null;
 
    
    Usuario.prototype.init = function()
    {
         this.id = null;
        this.cpf = null;
        this.nome = null;
        this.email = null;
        this.dataNascimento = null;
        this.status = null;
        this.bonus = null;
        this.tipo = null;
     
    }
    
    Usuario.prototype.setId = function(pId)
    {
        this.id = pId;
    }
    
    Usuario.prototype.setCpf = function(pCpf)
    {
        this.cpf = pCpf;
    }
    
    Usuario.prototype.setNome = function(pNome)
    {
        this.nome = pNome;
    }
    
    Usuario.prototype.setEmail = function(pEmail)
    {
        this.email = pEmail;
    }
    
    Usuario.prototype.setDataNascimento = function(pDataNascimento)
    {
        this.dataNascimento = pDataNascimento;
    }
    
    Usuario.prototype.setStatus = function(pStatus)
    {
        this.status = pStatus;
    }
    
    Usuario.prototype.setBonus = function(pBonus)
    {
        this.bonus = pBonus;
    }
    
    
    Usuario.prototype.setTipo = function(pTipo)
    {
        this.tipo = pTipo;
    }
    
    Usuario.prototype.getId = function()
    {
        return this.id;
    }
    
    Usuario.prototype.getCpf = function()
    {
        return this.cpf;
    }
    
    Usuario.prototype.getNome = function()
    {
        return this.nome;
    }
    
    Usuario.prototype.toObject = function (){
        return ({ 
            "id": this.id,
            "cpf": this.cpf,
            "nome": this.nome,
            "email": this.email,
            "dataNascimento": this.dataNascimento,
            "status": this.status,
             "bonus": this.bonus,
            "tipo": this.tipo, 
            "comissao": this.comissao,
        });
    }
    
});
    
    
function loadPageAtribuicoes(uId, uCpf, uNome, uEmail, uBonus, uTipo)   
{
    u = new Usuario();
    u.init;
    u.setId(uId);
    u.setCpf(uCpf);
    u.setNome(uNome);
    u.setEmail(uEmail);
    u.setBonus(uBonus);
    uTipo = (uTipo == 1) ? 'supervisor' : 'vendedor';
    u.setTipo(uTipo);
   
    atribuicoes.push(u.toObject());
    //console.log(atribuicoes);
}
    
<?php
    if (is_array($atribuicoes))
         foreach($atribuicoes as $i => $value)
         {
          //   var_dump($value);
             $isSupervisor = ($value['indicaSupervisor'] == true) ? 1 : 0;
             echo "loadPageAtribuicoes({$value['idUsuario']}, '{$value['cpf']}', '{$value['nomeUsuario']}', '{$value['email']}', {$value['comissaoSupervisor']}, {$isSupervisor});";
         }
                                     
    ?>
    
    
    
    
</script>