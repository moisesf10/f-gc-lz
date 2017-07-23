<?php
$usuario = (isset($this->getParams('usuario')[0])) ? $this->getParams('usuario')[0] : null;
$tiposDeContas = $this->getParams('tiposdecontas');
$bancos = $this->getParams('bancos');

if (\Application::getUrlParams(0) !== null && $usuario === null)
    \Application::print404();
?>
<style>
    section.panel div.panel-body div.row {padding-bottom: 1rem;}
    div.botoes {margin-left: 0.2rem;}
    div.botoes div {padding-right: 1rem; }
  
</style>

<section class="panel">
        <header class="panel-heading">
            <h2 class="panel-title">Cadastro de Usuários</h2>
        </header>
        <div class="panel-body">
            <div class="row">
                <div class="col-md-9">
                            <div class="form-group">
                                <label class="col-sm-2 control-label">ID: </label>
                                <div class="col-sm-2">
                                    <input type="text" id="id" name="id"  class="form-control" disabled value="<?php  if (is_array($usuario) && count($usuario) > 0) echo $usuario['id']; ?>" >
                                </div>
                            </div>
                    </div>
            </div>
          
            <div class="row">
                <div class="col-md-9">
                            <div class="form-group">
                                <label class="col-sm-2 control-label">CPF: </label>
                                <div class="col-sm-4">
                                    <input type="text" id="cpf" name="cpf" class="form-control" value="<?php  if (is_array($usuario) && count($usuario) > 0) echo $usuario['cpf']; ?>" >
                                </div>
                            </div>
                    </div>
            </div>
            
            <div class="row">
                <div class="col-md-9">
                            <div class="form-group">
                                <label class="col-sm-2 control-label">Nome: </label>
                                <div class="col-sm-6">
                                    <input type="text" id="nome" name="nome" class="form-control" value="<?php  if (is_array($usuario) && count($usuario) > 0) echo $usuario['nome']; ?>" >
                                </div>
                            </div>
                    </div>
            </div>
            
            <div class="row">
                <div class="col-md-9">
                            <div class="form-group">
                                <label class="col-sm-2 control-label">E-mail: </label>
                                <div class="col-sm-6">
                                    <input type="text" id="email" name="email" class="form-control" value="<?php  if (is_array($usuario) && count($usuario) > 0) echo $usuario['email']; ?>" >
                                </div>
                            </div>
                    </div>
            </div>
            
            <div class="row">
                <div class="col-md-9">
                            <div class="form-group">
                                <label class="col-sm-2 control-label">Senha: </label>
                                <div class="col-sm-6">
                                    <input type="password" id="senha" name="senha" class="form-control" placeholder="<?php if(\Application::getUrlParams(0) !== null && $usuario !== null) echo 'Preencha somente se deseja alterar a senha'?>"  >
                                </div>
                            </div>
                    </div>
            </div>
            
             <div class="row">
                <div class="col-md-9">
                            <div class="form-group">
                                <label class="col-sm-2 control-label">Nascimento: </label>
                                <div class="col-sm-6">
                                    <input type="text" id="nascimento" name="senha" data-plugin-datepicker data-input-mask="99/99/9999" placeholder="__/__/____" class="form-control" value="<?php  if (is_array($usuario) && count($usuario) > 0) echo $usuario['dataNascimento']; ?>" >
                                </div>
                            </div>
                    </div>
            </div>
            
            <div class="row">
                <div class="col-md-9">
                            <div class="form-group">
                                <label class="col-sm-2 control-label">Telefone: </label>
                                <div class="col-sm-6">
                                    <input type="text" id="telefone"  class="form-control" value="<?php  if (isset($usuario['telefone'])) echo $usuario['telefone']; ?>" >
                                </div>
                            </div>
                    </div>
            </div>
            
            <div class="row">
                <div class="col-md-9">
                            <div class="form-group">
                                <label class="col-sm-2 control-label">Celular: </label>
                                <div class="col-sm-6">
                                    <input type="text" id="celular"  class="form-control"  value="<?php  if (isset($usuario['celular'])) echo $usuario['celular']; ?>">
                                </div>
                            </div>
                    </div>
            </div>
            
            <div class="row">
                <div class="col-md-9">
                            <div class="form-group">
                                <label class="col-sm-2 control-label">Status: </label>
                                <div class="col-sm-4">
                                    <select class="form-control" id="status">
                                        <option <?php echo ($usuario != null && $usuario['status'] == 1) ? 'selected="selected"' : ''; ?> value="1">Ativo</option>
                                        <option <?php echo ($usuario != null &&  $usuario['status'] == 0) ? 'selected="selected"' : ''; ?> value="0">Desabilitado</option>
                                    </select>
                                </div>
                            </div>
                    </div>
            </div>
            
            <div class="row">
                <label class="col-md-9"><i>Informações sobre o endereço residencial</i></label>
            </div>
            
            
            <div class="row">
                <div class="form-group-endereco">
                    <div class="col-md-2">
                        <div class="row"><label class="col-sm-12 control-label">CEP</label></div>
                        <div class="row">
                            <div class="col-sm-6">
                                <input type="text" id="cep"   class="form-control" value="<?php  if (isset($usuario['cep']) ) echo $usuario['cep']; ?>" >
                            </div>
                             <div class="col-sm-6">
                                <button class="btn btn-grey" onclick="buscarCep()">&nbsp;Pesquisar</button>
                            </div>
                        </div> 
                    </div>
                    <div class="col-md-3">
                        <div class="row"><label class="col-sm-12 control-label">Rua</label></div>
                        <div class="row">
                            <div class="col-sm-12">
                                <input type="text" id="rua"  class="form-control" value="<?php  if (isset($usuario['rua'])) echo $usuario['rua']; ?>" >
                            </div>
                             
                        </div> 
                    </div>
                    <div class="col-md-1">
                        <div class="row"><label class="col-sm-12 control-label">Número</label></div>
                        <div class="row">
                            <div class="col-sm-12">
                                <input type="text" id="numeroresidencia"  class="form-control" value="<?php  if (isset($usuario['numeroResidencia'])) echo $usuario['numeroResidencia']; ?>" >
                            </div>
                             
                        </div> 
                    </div>
                    <div class="col-md-1">
                        <div class="row"><label class="col-sm-12 control-label">UF</label></div>
                        <div class="row">
                            <div class="col-sm-12">
                                <select class="form-control mb-md" name="uf" id="uf">
                                        <?php
                                            $uf = (isset($usuario['uf'])) ? $usuario['uf'] : null;
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
                </div>
            </div>
            
            
        
            <div class="row">
                    <div class="form-group-endereco">
                        <div class="col-md-2">
                            <div class="row"><label class="col-sm-12 control-label">Complemento</label></div>
                            <div class="row">
                                <div class="col-sm-12">
                                    <input type="text" id="complemento"   class="form-control" value="<?php  if (isset($usuario['complemento'])) echo $usuario['complemento']; ?>" >
                                </div>
                                 
                            </div> 
                        </div>
                        <div class="col-md-2">
                            <div class="row"><label class="col-sm-12 control-label">Bairro</label></div>
                            <div class="row">
                                <div class="col-sm-12">
                                    <input type="text" id="bairro"  class="form-control" value="<?php  if (isset($usuario['bairro'])) echo $usuario['bairro']; ?>" >
                                </div>

                            </div> 
                        </div>
                        <div class="col-md-3">
                            <div class="row"><label class="col-sm-12 control-label">Cidade</label></div>
                            <div class="row">
                                <div class="col-sm-12">
                                    <input type="text" id="cidade"  class="form-control" value="<?php  if (isset($usuario['cidade'])) echo $usuario['cidade']; ?>" >
                                </div>

                            </div> 
                        </div>
                    </div>
                </div>
            
            <div class="row">
                <label class="col-md-9"><i>Informações sobre a conta bancária</i></label>
            </div>
            
            
            <div class="row">
                    <div class="form-group-endereco">
                        <div class="col-md-2">
                            <div class="row"><label class="col-sm-12 control-label">Tipo de Conta</label></div>
                            <div class="row">
                                <div class="col-sm-12">
                                    <select class="form-control maiusculo" id="tipoconta">
                                    <option></option>
                                    <?php 
                                        if (is_array($tiposDeContas))
                                            foreach($tiposDeContas as $i => $value)
                                            {
                                                 $selected =  (isset($usuario['idTipoContaBancaria'])  && $usuario['idTipoContaBancaria'] == $value['id']  ) ? 'selected="selected"' : '';
                                                echo '<option '. $selected .' value="'. $value['id'] .'">'. $value['descricao'] .'</option>';
                                            }
                                    ?>
                                </select>
                                </div>
                                 
                            </div> 
                        </div>
                        <div class="col-md-2">
                            <div class="row"><label class="col-sm-12 control-label">Banco</label></div>
                            <div class="row">
                                <div class="col-sm-12">
                                    <select class="form-control maiusculo" id="banco">
                                    <option></option>
                                    <?php 
                                        if (is_array($bancos))
                                            foreach($bancos as $i => $value)
                                            {
                                                  $selected =  (isset($usuario['idBanco'])  && $usuario['idBanco'] == $value['id']  ) ? 'selected="selected"' : '';
                                                echo '<option '. $selected .' value="'. $value['id'] .'">'. $value['codigo']. ' - '. $value['nome'] .'</option>';
                                            }
                                    ?>
                                </select>
                                </div>

                            </div> 
                        </div>
                        <div class="col-md-1">
                            <div class="row"><label class="col-sm-12 control-label">Agencia</label></div>
                            <div class="row">
                                <div class="col-sm-12">
                                    <input type="text" id="agencia"  class="form-control" value="<?php  if (isset($usuario['agencia']) ) echo $usuario['agencia']; ?>" >
                                </div>

                            </div> 
                        </div>
                        <div class="col-md-2">
                            <div class="row"><label class="col-sm-12 control-label">Conta</label></div>
                            <div class="row">
                                <div class="col-sm-12">
                                    <input type="text" id="numeroconta"  class="form-control" value="<?php  if (isset($usuario['numeroConta'])) echo $usuario['numeroConta']; ?>" >
                                </div>

                            </div> 
                        </div>
                    </div>
                </div>
            
            
     
    </div>
    
    
    <div class="row botoes">
        <?php
        if (\Application::isAuthorized(ucfirst(strtolower(\Application::getNameController())) , 'usuarios', 'escrever') )
        {?>
        <button type="button" class="mb-xs mt-xs mr-xs btn btn-danger" onclick="salvar()"><i class="material-icons material-align-icons-button">&#xE877;</i>&nbsp;Salvar</button>
        <?php } ?>
        
        <?php
        if (\Application::getUrlParams(0) !== null &&  \Application::isAuthorized(ucfirst(strtolower(\Application::getNameController())) , 'usuarios', 'remover') )
        {?>
            <button type="button" class="mb-xs mt-xs mr-xs btn btn-grey" onclick="remover()"><i class="material-icons material-align-icons-button">&#xE92B;</i>&nbsp;Remover</button>
        <?php } ?>
        
         <button type="button" class="mb-xs mt-xs mr-xs btn btn-grey" onclick="document.location = '/sistema/cadastrar-usuario'"><i class="material-icons material-align-icons-button">&#xE145;</i>&nbsp;Novo</button>
        
        
        <?php
        if (\Application::isAuthorized(ucfirst(strtolower(\Application::getNameController())) , 'usuarios', 'escrever') && $usuario !== null )
        {?>
        <button type="button" class="mb-xs mt-xs mr-xs btn btn-grey" onclick="desativar()"><i class="material-icons material-align-icons-button">&#xE14B;</i>&nbsp;Desativar</button>
        <?php } ?>
      
    
    </div>
    
</section>

<script src="/library/javascript/usuarios/cadastro_usuarios.js"></script>
<script src="/library/jsvendor/bootstrap-datepicker/js/bootstrap-datepicker.js" ></script>

<script>

$.getScript('/library/jsvendor/jquery-masked-input/jquery.maskedinput.min.js', function(){
    $('#cpf').mask('999.999.999-99',{placeholder:"___.___.___-__", autoclear: false});
    $('#cep').mask('99999-999',{placeholder:"_____-___", autoclear: true});
    $('#telefone, #celular').mask('(99)9999-99999?',{placeholder:"(__)____-_____", autoclear: false});
    $('#agencia, #numeroconta').mask('99999999999',{placeholder:"", autoclear: false});
});


</script>