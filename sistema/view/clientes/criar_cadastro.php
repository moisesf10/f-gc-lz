<?php
$bancos = $this->getParams('bancos');
$convenio = $this->getParams('convenio');
$tiposDeContas = $this->getParams('tiposdecontas');
$cliente = (isset($this->getParams('cliente')[0])) ? $this->getParams('cliente')[0] : null;
//echo '<pre>';print_r($cliente); echo '</pre>';
if (\Application::getUrlParams(0)   !== null && ($cliente === null || count($cliente) == 0 )   )
    \Application::print404();

// isUpdate bloqueará quando tentado editar o cadastro que não seja logradouro
if (is_array($cliente) && count($cliente) > 0)
    $isUpdate = true;
else
    $isUpdate = false;

if ( \Application::isAuthorized(ucfirst('clientes') , 'admin_cadastro_clientes', 'ler') 
    ||  \Application::isAuthorized(ucfirst('clientes') , 'admin_cadastro_clientes', 'escrever') 
   )
    $isDisabled = '';
else
    $isDisabled = 'disabled="disabled"';

?>

<style>
    .panel {margin-bottom: 0px;}
    .box-button-add{margin-top: -2.5rem;}
    .box-button-save {margin-top: 2rem; padding-bottom: 3rem;}
    #textAreaObservacoes {height: 15rem;}
    .button-permitir-acesso {margin-top: 1rem;}
    .button-permitir-acesso  div.btn {margin-top: 0px;}
    /* .navbar-fixed-bottom{}*/
    .maiusculo {text-transform: uppercase;}
    .usuario {display: inline-block; font-style: italic; }
</style>

<h5>Cadastrar Cliente</h5>

<hr>



    <section class="panel">
        <header class="panel-heading">
            <h2 class="panel-title">Informa&ccedil;&otilde;es  Pessoais</h2>
        </header>
        <div class="panel-body">
            <div class="row">
                   <div class="col-md-4">
                            <div class="form-group">
                                <label class="col-sm-4 control-label">*CPF: </label>
                                <div class="col-sm-8">
                                    <input type="text" name="cpf" id="cpf" <?php if($isUpdate) echo $isDisabled; ?>  class="form-control" value="<?php if (isset($cliente['dados']['cpf'])) echo $cliente['dados']['cpf']; ?>" >
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-4 control-label">*CEP: </label>
                                <div class="col-sm-4">
                                    <input type="text" name="cep" id="cep"   class="form-control" value="<?php if (isset($cliente['dados']['cep'])) echo $cliente['dados']['cep']; ?>" >
                                </div>
                                <div class="col-sm-4"><button class="btn btn-grey" onclick="buscarCep()">&nbsp;Pesquisar</button></div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-4 control-label">Complemento: </label>
                                <div class="col-sm-8">
                                    <input type="text" name="complemento" id="complemento"  class="form-control maiusculo" value="<?php if (isset($cliente['dados']['complemento'])) echo $cliente['dados']['complemento']; ?>">
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-4 control-label">*UF: </label>
                                <div class="col-sm-8">
                                    <select class="form-control mb-md" name="uf" id="uf">
                                        <option></option>
                                        <option value="AC" <?php  if(strtoupper($cliente['dados']['uf'])== 'MG')  echo 'selected="selected"';  ?>>AC</option>
                                        <option value="AL" <?php  if(strtoupper($cliente['dados']['uf'])== 'AL')  echo 'selected="selected"';  ?>>AL</option>
                                        <option value="AM" <?php  if(strtoupper($cliente['dados']['uf'])== 'AM')  echo 'selected="selected"';  ?>>AM</option>
                                        <option value="AP" <?php  if(strtoupper($cliente['dados']['uf'])== 'AP')  echo 'selected="selected"';  ?>>AP</option>
                                        <option value="BA" <?php  if(strtoupper($cliente['dados']['uf'])== 'BA')  echo 'selected="selected"';  ?>>BA</option>
                                        <option value="CE" <?php  if(strtoupper($cliente['dados']['uf'])== 'CE')  echo 'selected="selected"';  ?>>CE</option>
                                        <option value="DF" <?php  if(strtoupper($cliente['dados']['uf'])== 'DF')  echo 'selected="selected"';  ?>>DF</option>
                                        <option value="ES" <?php  if(strtoupper($cliente['dados']['uf'])== 'ES')  echo 'selected="selected"';  ?>>ES</option>
                                        <option value="GO" <?php  if(strtoupper($cliente['dados']['uf'])== 'GO')  echo 'selected="selected"';  ?>>GO</option>
                                        <option value="MA" <?php  if(strtoupper($cliente['dados']['uf'])== 'MA')  echo 'selected="selected"';  ?>>MA</option>
                                        <option value="MG" <?php  if(strtoupper($cliente['dados']['uf'])== 'MG')  echo 'selected="selected"';  ?>>MG</option>
                                        <option value="MS" <?php  if(strtoupper($cliente['dados']['uf'])== 'MS')  echo 'selected="selected"';  ?>>MS</option>
                                        <option value="MT" <?php  if(strtoupper($cliente['dados']['uf'])== 'MT')  echo 'selected="selected"';  ?>>MT</option>
                                        <option value="PA" <?php  if(strtoupper($cliente['dados']['uf'])== 'PA')  echo 'selected="selected"';  ?>>PA</option>
                                        <option value="PB" <?php  if(strtoupper($cliente['dados']['uf'])== 'PB')  echo 'selected="selected"';  ?>>PB</option>
                                        <option value="PE" <?php  if(strtoupper($cliente['dados']['uf'])== 'PE')  echo 'selected="selected"';  ?>>PE</option>
                                        <option value="PI" <?php  if(strtoupper($cliente['dados']['uf'])== 'PI')  echo 'selected="selected"';  ?>>PI</option>
                                        <option value="PR" <?php  if(strtoupper($cliente['dados']['uf'])== 'PR')  echo 'selected="selected"';  ?>>PR</option>
                                        <option value="RJ" <?php  if(strtoupper($cliente['dados']['uf'])== 'RJ')  echo 'selected="selected"';  ?>>RJ</option>
                                        <option value="RN" <?php  if(strtoupper($cliente['dados']['uf'])== 'RN')  echo 'selected="selected"';  ?>>RN</option>
                                        <option value="RS" <?php  if(strtoupper($cliente['dados']['uf'])== 'RS')  echo 'selected="selected"';  ?>>RS</option>
                                        <option value="RO" <?php  if(strtoupper($cliente['dados']['uf'])== 'RO')  echo 'selected="selected"';  ?>>RO</option>
                                        <option value="RR" <?php  if(strtoupper($cliente['dados']['uf'])== 'RR')  echo 'selected="selected"';  ?>>RR</option>
                                        <option value="SC" <?php  if(strtoupper($cliente['dados']['uf'])== 'SC')  echo 'selected="selected"';  ?>>SC</option>
                                        <option value="SE" <?php  if(strtoupper($cliente['dados']['uf'])== 'SE')  echo 'selected="selected"';  ?>>SE</option>
                                        <option value="SP" <?php  if(strtoupper($cliente['dados']['uf'])== 'SP')  echo 'selected="selected"';  ?>>SP</option>
                                        <option value="TO" <?php  if(strtoupper($cliente['dados']['uf'])== 'TO')  echo 'selected="selected"';  ?>>TO</option>
                                    </select>
                                </div>
                            </div>
                            
                    </div>
                    <!-- fim coluna 1 -->
                    <div class="col-md-4">
                            <div class="form-group">
                                <label class="col-sm-4 control-label">*Nome: </label>
                                <div class="col-sm-8">
                                    <input type="text" name="nome" id="nome" <?php if($isUpdate) echo $isDisabled; ?>  class="form-control maiusculo" value="<?php if (isset($cliente['dados']['nomeCliente'])) echo $cliente['dados']['nomeCliente']; ?>">
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-4 control-label">*Rua: </label>
                                <div class="col-sm-8">
                                    <input type="text" name="rua" id="rua" class="form-control maiusculo" value="<?php if (isset($cliente['dados']['rua'])) echo $cliente['dados']['rua']; ?>">
                                </div>
                            </div>
                            
                            <div class="form-group">
                                <label class="col-sm-4 control-label">*Bairro: </label>
                                <div class="col-sm-8">
                                    <input type="text" name="bairro" id="bairro"  class="form-control maiusculo" value="<?php if (isset($cliente['dados']['bairro'])) echo $cliente['dados']['bairro']; ?>">
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="col-sm-12">
                                    <input type="text" disabled class="form-control" value="<?php if ($cliente != null) echo 'Criado em ' . $cliente['dados']['created'] . ' por ' . $cliente['nomeUsuario']; ?>">
                                </div>
                            </div>
                    </div>
                    <!-- fim coluna 2 -->
                    <div class="col-md-4">
                            
                            <div class="form-group">
                                <label class="col-sm-4 control-label">*Nascimento: </label>
                                <div class="col-sm-8">
                                    <input type="text" name="nascimento" id="nascimento" <?php if($isUpdate) echo $isDisabled; ?>   class="form-control" data-plugin-datepicker data-input-mask="99/99/9999" placeholder="__/__/____" value="<?php if (isset($cliente['dados']['nascimento'])) echo $cliente['dados']['nascimento']; ?>">
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-4 control-label">*N&uacute;mero: </label>
                                <div class="col-sm-8">
                                    <input type="text" name="numerorua" id="numerorua"  class="form-control maiusculo" value="<?php if (isset($cliente['dados']['numeroRua'])) echo $cliente['dados']['numeroRua']; ?>">
                                </div>
                            </div>
                                                  
                            <div class="form-group">
                                <label class="col-sm-4 control-label">*Cidade: </label>
                                <div class="col-sm-8">
                                    <input type="text" name="cidade" id="cidade"  class="form-control maiusculo" value="<?php if (isset($cliente['dados']['cidade'])) echo $cliente['dados']['cidade']; ?>">
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="col-sm-12">
                                    <input type="text" name="created" id="created"  class="form-control" disabled value="<?php if (isset($cliente['dados']['nomeUsuarioModificacao']) && ! empty($cliente['dados']['nomeUsuarioModificacao'])) echo 'Alterado em ' . $cliente['dados']['modified'] . ' por '. $cliente['dados']['nomeUsuarioModificacao']; ?>">
                                </div>
                            </div>
                    </div>
                    <!-- fim coluna 3 -->
            </div>
            
        </div>
    </section>
    <section class="panel" id="box-emails">
            <header class="panel-heading">
                <h2 class="panel-title text-left">E-mails</h2>
                <div class="text-right box-button-add">
                    <button class="mb-xs mt-xs mr-xs btn btn-success"  onclick="adicionarEmail()">+ Adicionar E-mail</button>
                </div>
            </header>
            <div class="panel-body">
                <?php
                if (isset($cliente['emails'])  && count($cliente['emails']) > 0   )
                {
                    foreach($cliente['emails'] as $i => $value)
                    {
                ?>
                        <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label class="col-sm-4 control-label">E-mail: </label>
                                <div class="col-sm-8">
                                    <input type="text" <?php if($isUpdate) echo $isDisabled; ?>  class="form-control" value="<?php echo $value['email']; ?>">
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label class="col-sm-4 control-label">Senha: </label>
                                <div class="col-sm-8">
                                    <input type="text" <?php if($isUpdate) echo $isDisabled; ?>  class="form-control" value="<?php echo $value['senha']  ?>">
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                                <button type="button" <?php if($isUpdate) echo $isDisabled; ?>  class="mb-xs mt-xs mr-xs btn btn-warning" onclick="$(this).parent().parent().remove()">Remover</button> 
                        </div>
                    </div>
    <?php   } 
                }    ?>


                <div class="row">
                    <div class="col-md-4">
                        <div class="form-group">
                            <label class="col-sm-4 control-label">E-mail: </label>
                            <div class="col-sm-8">
                                <input type="text" <?php if($isUpdate) echo $isDisabled; ?>  class="form-control">
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label class="col-sm-4 control-label">Senha: </label>
                            <div class="col-sm-8">
                                <input type="text" <?php if($isUpdate) echo $isDisabled; ?>   class="form-control">
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                            <button type="button" <?php if($isUpdate) echo $isDisabled; ?>  class="mb-xs mt-xs mr-xs btn btn-warning" onclick="$(this).parent().parent().remove()">Remover</button> 
                    </div>
                </div>
            </div>
        </section>
    <section class="panel" id="box-telefones">
        <header class="panel-heading">
            <h2 class="panel-title text-left">Telefones e Refer&ecirc;ncias</h2>
            <div class="text-right box-button-add">
                <button class="mb-xs mt-xs mr-xs btn btn-success"  onclick="adicionarTelefones()">+ Adicionar Telefone</button>
            </div>
        </header>
        <div class="panel-body">
            <?php
            if (isset($cliente['telefones'])  && count($cliente['telefones']) > 0   )
            {
                foreach($cliente['telefones'] as $i => $value)
                {
            ?>
                    <div class="row">
                    <div class="col-md-4">
                        <div class="form-group">
                            <label class="col-sm-4 control-label">Telefone: </label>
                            <div class="col-sm-8">
                                <input type="text" <?php if($isUpdate) echo $isDisabled; ?>   class="form-control fone" data-uuid="tel-<?php echo uniqid(); ?>" value="<?php echo $value['numero']  ?>">
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label class="col-sm-4 control-label">Refer&ecirc;ncia: </label>
                            <div class="col-sm-8">
                                <input type="text"  <?php if($isUpdate) echo $isDisabled; ?>  class="form-control maiusculo" value="<?php echo $value['referencia']  ?>">
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                            <button type="button" <?php if($isUpdate) echo $isDisabled; ?>  class="mb-xs mt-xs mr-xs btn btn-warning" onclick="$(this).parent().parent().remove()">Remover</button> 
                    </div>
                </div>
<?php   } 
            }    ?>
            
            
            <div class="row">
                <div class="col-md-4">
                    <div class="form-group">
                        <label class="col-sm-4 control-label">*Telefone: </label>
                        <div class="col-sm-8">
                            <input type="text" <?php if($isUpdate) echo $isDisabled; ?>  class="form-control fone" data-uuid="tel-1">
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label class="col-sm-4 control-label">Refer&ecirc;ncia: </label>
                        <div class="col-sm-8">
                            <input type="text" <?php if($isUpdate) echo $isDisabled; ?>  class="form-control maiusculo">
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                        <button type="button" <?php if($isUpdate) echo $isDisabled; ?>  class="mb-xs mt-xs mr-xs btn btn-warning" onclick="$(this).parent().parent().remove()">Remover</button> 
                </div>
            </div>
        </div>
    </section>
    
    <section class="panel " id="box-convenios">
        <header class="panel-heading">
            <h2 class="panel-title text-left">Conv&ecirc;nio</h2>
            <div class="text-right box-button-add">
                <button  class="mb-xs mt-xs mr-xs btn btn-success" onclick="adicionarConvenio()">+ Adicionar Convênio</button>
            </div>
        </header>
        <div class="panel-body">
            <?php
            if (is_array($cliente['convenios']))
                foreach($cliente['convenios'] as $a => $conv)
                { ?>
                    <div class="row">
                 <div class="col-md-3">
                        <div class="form-group">
                        <label class="col-sm-3 control-label">*Conv&ecirc;nio: </label>
                        <div class="col-sm-8">
                            <select <?php if($isUpdate) echo $isDisabled; ?>  class="form-control maiusculo">
                                <option></option>
                                <?php 
                                        if (is_array($convenio))
                                            foreach($convenio as $i => $value)
                                            {
                                                $selected = ($conv['idConvenio'] == $value['id']  ) ? 'selected="selected"' : '';
                                                echo '<option '. $selected .' value="'. $value['id'] .'">'. $value['nome'] .'</option>';
                                            }
                                    ?>
                            </select>
                        </div>
                        </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                            <label class="col-sm-3 control-label">*Matr&iacute;cula: </label>
                            <div class="col-sm-8">
                                <input type="text" <?php if($isUpdate) echo $isDisabled; ?>  class="form-control maiusculo" value="<?php echo $conv['matricula']; ?>">
                            </div>
                        </div>
                </div>
                <div class="col-md-3">
                        <div class="form-group">
                            <label class="col-sm-3 control-label">Senha: </label>
                            <div class="col-sm-8">
                                <input type="text" <?php if($isUpdate) echo $isDisabled; ?>   class="form-control" value="<?php echo $conv['senha']; ?>">
                            </div>
                        </div>
                </div> 
                <div class="col-md-3">
                        <button type="button" <?php if($isUpdate) echo $isDisabled; ?>  class="mb-xs mt-xs mr-xs btn btn-warning" onclick="$(this).parent().parent().remove()">Remover</button> 
                </div>
            </div>
            <?php } ?>
            <div class="row">
                 <div class="col-md-3">
                        <div class="form-group">
                        <label class="col-sm-3 control-label">*Conv&ecirc;nio: </label>
                        <div class="col-sm-8">
                            <select <?php if($isUpdate) echo $isDisabled; ?>  class="form-control maiusculo" name="convenio1" id="convenio1">
                                <option></option>
                                <?php 
                                        if (is_array($convenio))
                                            foreach($convenio as $i => $value)
                                                echo '<option value="'. $value['id'] .'">'. $value['nome'] .'</option>';
                                            
                                    ?>
                            </select>
                        </div>
                        </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                            <label class="col-sm-3 control-label">*Matr&iacute;cula: </label>
                            <div class="col-sm-8">
                                <input type="text"  <?php if($isUpdate) echo $isDisabled; ?>   class="form-control maiusculo">
                            </div>
                        </div>
                </div>
                <div class="col-md-3">
                        <div class="form-group">
                            <label class="col-sm-3 control-label">Senha: </label>
                            <div class="col-sm-8">
                                <input type="text"  <?php if($isUpdate) echo $isDisabled; ?>  class="form-control">
                            </div>
                        </div>
                </div> 
                <div class="col-md-3">
                        <button type="button" <?php if($isUpdate) echo $isDisabled; ?>  class="mb-xs mt-xs mr-xs btn btn-warning" onclick="$(this).parent().parent().remove()">Remover</button> 
                </div>
            </div>
        </div>
    </section>
    
     <section class="panel" id="box-dados-bancarios">
        <header class="panel-heading">
            <h2 class="panel-title text-left">Dados Banc&aacute;rios</h2>
            <div class="text-right box-button-add">
                <button  class="mb-xs mt-xs mr-xs btn btn-success" onclick="adicionarConta()">+ Adicionar Conta</button>
            </div>
        </header>
        <div class="panel-body">
            <?php
            if (is_array($cliente['contas']))
                foreach($cliente['contas'] as $a => $conta)
                { ?>
                    <div class="row">
                <div class="col-md-3">
                        <div class="form-group">
                            <label class="col-sm-3 control-label">*Banco: </label>
                            <div class="col-sm-8">
                                <select <?php if($isUpdate) echo $isDisabled; ?>  class="form-control maiusculo" id="banco1">
                                    <option></option>
                                    <?php 
                                        if (is_array($bancos))
                                            foreach($bancos as $i => $value)
                                            {
                                                  $selected =  ($conta['idBanco'] == $value['id']  ) ? 'selected="selected"' : '';
                                                echo '<option '. $selected .' value="'. $value['id'] .'">'. $value['codigo']. ' - '. $value['nome'] .'</option>';
                                            }
                                    ?>
                                </select>
                            </div>
                        </div>
                </div>
                <div class="col-md-2">
                        <div class="form-group">
                            <label class="col-sm-3 control-label">*Ag&ecirc;ncia: </label>
                            <div class="col-sm-8">
                                <input type="text" <?php if($isUpdate) echo $isDisabled; ?>   class="form-control maiusculo" value="<?php echo $conta['agencia'];  ?>"  >
                            </div>
                        </div>
                </div>
                <div class="col-md-2">
                        <div class="form-group">
                            <label class="col-sm-3 control-label">*Conta: </label>
                            <div class="col-sm-8">
                                <input type="text" <?php if($isUpdate) echo $isDisabled; ?>   class="form-control maiusculo" value="<?php echo $conta['conta'];  ?>">
                            </div>
                        </div>
                </div>
                <div class="col-md-3">
                        <div class="form-group">
                            <label class="col-sm-3 control-label">Tipo: </label>
                            <div class="col-sm-8">
                                <select <?php if($isUpdate) echo $isDisabled; ?>  class="form-control maiusculo" id="tipoconta1">
                                    <option></option>
                                    <?php 
                                        if (is_array($tiposDeContas))
                                            foreach($tiposDeContas as $i => $value)
                                            {
                                                 $selected =  ($conta['idTipoConta'] == $value['id']  ) ? 'selected="selected"' : '';
                                                echo '<option '. $selected .' value="'. $value['id'] .'">'. $value['descricao'] .'</option>';
                                            }
                                    ?>
                                </select>
                            </div>
                        </div>
                </div>
                <div class="col-md-2">
                        <button type="button" <?php if($isUpdate) echo $isDisabled; ?>  class="mb-xs mt-xs mr-xs btn btn-warning" onclick="$(this).parent().parent().remove()">Remover</button> 
                </div>
            </div>
            
            <?php } ?>
            
            <div class="row">
                <div class="col-md-3">
                        <div class="form-group">
                            <label class="col-sm-3 control-label">*Banco: </label>
                            <div class="col-sm-8">
                                <select <?php if($isUpdate) echo $isDisabled; ?>  class="form-control maiusculo" id="banco1">
                                    <option></option>
                                    <?php 
                                        if (is_array($bancos))
                                            foreach($bancos as $i => $value)
                                                echo '<option value="'. $value['id'] .'">'. $value['codigo']. ' - '. $value['nome'] .'</option>';
                                            
                                    ?>
                                </select>
                            </div>
                        </div>
                </div>
                <div class="col-md-2">
                        <div class="form-group">
                            <label class="col-sm-3 control-label">*Ag&ecirc;ncia: </label>
                            <div class="col-sm-8">
                                <input type="text" <?php if($isUpdate) echo $isDisabled; ?>  class="form-control maiusculo"  >
                            </div>
                        </div>
                </div>
                <div class="col-md-2">
                        <div class="form-group">
                            <label class="col-sm-3 control-label">*Conta: </label>
                            <div class="col-sm-8">
                                <input type="text" <?php if($isUpdate) echo $isDisabled; ?>   class="form-control maiusculo">
                            </div>
                        </div>
                </div>
                <div class="col-md-3">
                        <div class="form-group">
                            <label class="col-sm-3 control-label">Tipo: </label>
                            <div class="col-sm-8">
                                <select <?php if($isUpdate) echo $isDisabled; ?> class="form-control maiusculo" id="tipoconta1">
                                    <option></option>
                                    <?php 
                                        if (is_array($tiposDeContas))
                                            foreach($tiposDeContas as $i => $value)
                                                echo '<option '. $selected .' value="'. $value['id'] .'">'. $value['descricao'] .'</option>';
                                            
                                    ?>
                                </select>
                            </div>
                        </div>
                </div>
                <div class="col-md-2">
                        <button type="button" <?php if($isUpdate) echo $isDisabled; ?>  class="mb-xs mt-xs mr-xs btn btn-warning" onclick="$(this).parent().parent().remove()">Remover</button> 
                </div>
            </div>
         </div>
    </section>
    
    <!-- OBSERVAÇÃO -->
    <section class="panel box-observacoes">
        <header class="panel-heading">
            <h2 class="panel-title text-left">Observa&ccedil;&otilde;es</h2>
        </header>
        <div class="panel-body">
            <div class="row">
                <div class="col-md-12">
                    <textarea id="textAreaObservacoes" <?php if($isUpdate) echo $isDisabled; ?>  class="form-control maiusculo"  data-plugin-maxlength="" maxlength="300"><?php if (isset($cliente['dados']['observacoes'])) echo $cliente['dados']['observacoes']; ?></textarea>
                    <p>
                        <code>Restantes</code><label>300.</label>
                    </p>
                </div>
            </div>
        </div>
    </section>


<section class="panel box-arquivos">
    <header class="panel-heading">
        <h2 class="panel-title text-left">Enviar Arquivos</h2>
        <div class="text-right box-button-add">
                <button  class="mb-xs mt-xs mr-xs btn btn-success" onclick="adicionarArquivo()">+ Adicionar Arquivo</button>
            </div>
    </header>
    <div class="panel-body">
        <div class="row load-files">
            <div class="row">
                <label class="col-md-1">Selecione o Arquivo</label>
                <div class="col-md-2">
                    <input type="text" readonly class="form-control">
                </div>
                <label class="col-md-1">Nome do Arquivo</label>
                <div class="col-md-2">
                    <input type="text" class="form-control">
                </div>
                <div class="col-md-2">
                    <button type="button" class="btn btn-info mr-xs mb-sm" onclick="$(this).parent().parent().find('input').eq(2).click()">Carregar arquivo</button>
                    <button type="button" <?php if($isUpdate) echo $isDisabled; ?>  class="mb-sm  mr-xs btn btn-warning" onclick="descarregarArquivo($(this).parent().parent())">Remover</button> 
                </div>
                <div class="col-md-1">
                        <input type="file" class="hidden" data-id="" onchange="carregarArquivo(this.files, $(this).parent().parent())">
                </div>
                
            </div>
        </div>
        
        <div class="row">
            <h1 class="panel-title text-left">Arquivos armazenados</h1>
			<p><i><b>Obs.: Cada arquivo deve ter no máximo 1MB</i></b></p>
        </div>
        
        <div class="row view-files">
            <div class="col-md-12">
                <table class="table table-bordered table-striped mb-none" id="datatable-editable">
                    <thead>
                        <tr>
                            <th>Arquivo</th>
                            <th>Nome do Arquivo	</th>
                            <th>Data do envio</th>
                            <th>Editar</th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php
                        if (is_array($cliente['files']) && $cliente['files'][0]['id'] != 0 )    
                            foreach($cliente['files'] as $i => $file)
                            { 
                                $grade = ($i % 2 == 0) ? 'gradeX' : 'gradeC';
                            ?>
                                
                                <tr class="<?php echo $grade; ?>">
                                    <td><?php echo $file['name']; ?></td>
                                    <td><?php echo $file['descricao']; ?></td>
                                    <td><?php echo $file['created']; ?></td>
                                    <td class="actions">
                                            <a target="_blank" href="/cliente/baixar-arquivo-cliente/?filename=<?php echo urlencode($file['fileName']); ?>" class="on-default edit-row"><i class="fa fa-download"></i></a>
                                        <a href="javascript:void(0)" onclick="deletarArquivo(<?php echo $file['id']; ?>, '<?php echo $file['fileName']; ?>')" class="on-default remove-row"><i class="fa fa-trash-o"></i></a>
                                        <a target="_blank" href="/arquivos/clientes/<?php echo $file['fileName']; ?>" class="on-default remove-row"><i class="fa fa-eye"></i></a>
                                    </td>
                                </tr>
                        
                    <?php   }    ?>

                      </tbody>
                </table>

            </div>
        </div>
        
        
    </div>
</section>



<div class="row">
    <div class="col-md-12 box-button-save">
        <?php
        if (\Application::isAuthorized(ucfirst('clientes') , 'clientes', 'escrever') )
        {?>
         <button type="button" class="mb-xs mt-xs mr-xs btn btn-danger" onclick="salvar()"><i class="material-icons material-align-icons-button">&#xE877;</i>&nbsp;Salvar</button>
        
    <?php } ?>
    
        <?php
            if (isset($cliente['dados']['cpf']) && \Application::isAuthorized(ucfirst('clientes') , 'clientes', 'escrever')  )
            { ?>
                    <button type="button" class="mb-xs mt-xs mr-xs btn btn-grey" onclick="agendar()"><i class="material-icons material-align-icons-button">&#xE0DE;</i>&nbsp;Agendar</button>
        <?php } ?>
        
         <?php
        if (isset($cliente['dados']['cpf']) && \Application::isAuthorized(ucfirst('clientes') , 'clientes', 'remover')  )
        {?>
            <button type="button" class="mb-xs mt-xs mr-xs btn btn-grey" onclick="remover()"><i class="material-icons material-align-icons-button">&#xE92B;</i>&nbsp;Remover</button>
        <?php } ?>
        
      
        
        <button type="button" class="mb-xs mt-xs mr-xs btn btn-grey" onclick="document.location = '/cliente/cadastrar'"><i class="material-icons material-align-icons-button">&#xE145;</i>&nbsp;Novo</button>
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





<script>
$.ajaxSetup({
  cache: true
});  
</script>

<script src="/library/jsvendor/magnific-popup/magnific-popup.js"></script>
<script src="/library/jsvendor/bootstrap-datepicker/js/bootstrap-datepicker.js" ></script>

<script>
  $.getCSS('/library/jsvendor/magnific-popup/magnific-popup.css');

    
$(function(){
   
    $('#cpf').focusout(function(e){
         var str = $(this).val().replace('_','');
		/* if ( str.length == 14 && ! application.isValidCpf(str)  )
         {
             alert('CPF Inválido');
             $(this).focus();
         }else*/
             if (str != '' && str.length < 14)
                 $(this).val('');
             
	 });
    
    
    $('.box-observacoes div.panel-body textarea').keyup(function(){
       $('.box-observacoes div.panel-body label').html(300 - $(this).val().length); 
    });
    
});
    

<?php 
if(isset($cliente['dados']['cpf']) ){ ?>
function agendar()
{
    document.location =  '/cliente/cadastrar-agenda/?&cliente=' + update.replace(/[\.-]/gi, '') ;
   
}
<?php } ?>
    
    


$.getScript('/library/javascript/application.js');
$.getScript('/library/javascript/clientes/cadastrar.js?<?php echo uniqid(); ?>');
$.getScript('/library/jsvendor/jquery-masked-input/jquery.maskedinput.min.js', function(){
    $('#cpf').mask('999.999.999-99',{placeholder:"___.___.___-__", autoclear: false});
    $('#cep').mask('99999-999',{placeholder:"_____-__", autoclear: true});
    $('#box-telefones div.panel-body input.fone').mask('(99)9999-99999?',{placeholder:"(__)____-_____", autoclear: false});
    $('.nb').mask('9999999999');
});
    
    
update = '<?php if (isset($cliente['dados']['cpf'])) echo $cliente['dados']['cpf']; ?>';
    
function remover()
{
    if (! confirm('Deseja realmente excluir este cadastro? A informação será removida para sempre.'))
        return false;
    if (update == '')
        {
            alert('Este cadastro não está salvo, é necessário salvar antes de excluir');
            return false;
        }
     var cpf = update;
    $.ajax({
        type: "POST",
        url: '<?php echo '/cliente/apagar-cliente/'; ?>',
        cache: false,
        dataType: "json",
        data: 'cpf='+cpf,
        success: function(json){
            if (json.success)
             {
                 alert('Registro excluído com sucesso');
                 //$('#id').val(json.id);
                 document.location = '<?php echo '/cliente/pesquisar/'; ?>';
             }
            else
            {
                alert('Não foi possível realizar a alteração\nMotivo: ' + json.message);
                //document.location.reload();
            }
        }
    });
    
}
    
// Convenios do sistema
<?php 
    $arConvenios = array();
    if (is_array($convenio))
        foreach($convenio as $i => $value)
            array_push($arConvenios,  array('id' => $value['id'], 'nome' => $value['nome'])  );
echo 'convenios = ' . json_encode($arConvenios) . ';';     

    // BANCOS

$arBancos = array();
if (is_array($bancos))
    foreach($bancos as $i => $value)
        array_push($arBancos,  array('id' => $value['id'], 'nome' => $value['codigo']. ' - '. $value['nome'])  ); 
echo 'bancos = ' . json_encode($arBancos) . ';'; 
 
$arTipoContaBancos = array();
if (is_array($tiposDeContas))
    foreach($tiposDeContas as $i => $value)
        array_push($arTipoContaBancos,  array('id' => $value['id'], 'descricao' => $value['descricao'])  ); 
echo 'tipoContasBancos = ' . json_encode($arTipoContaBancos) . ';';

?>

</script>