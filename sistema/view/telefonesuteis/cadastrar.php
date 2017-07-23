<?php
$telefone = (isset($this->getParams('telefone')[0])) ? $this->getParams('telefone')[0] : null;
//$convenio = $this->getParams('convenios');


if (\Application::getUrlParams(0) !== null && $telefone === null)
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
        <h2 class="panel-title">Cadastro de Telefones Úteis</h2>
    </header>
    <div class="panel-body">
            <div class="row">
                <div class="col-md-12">
                            <div class="form-group">
                                <label class="col-sm-1 control-label">Nome: </label>
                                <div class="col-sm-11">
                                    <input type="text" id="nome"  class="form-control"  value="<?php if (isset($telefone['nome'])) echo $telefone['nome']; ?>" >
                                </div>
                            </div>
                    </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                            <div class="form-group">
                                <label class="col-sm-1 control-label">Contato: </label>
                                <div class="col-sm-3">
                                    <input type="text" id="contato"  class="form-control"  value="<?php  if (isset($telefone['contato'])) echo $telefone['contato']; ?>" >
                                </div>
                                
                                <label class="col-sm-1 control-label">Telefone 1: </label>
                                <div class="col-sm-3">
                                    <input type="text" id="telefone1"  class="form-control"  value="<?php  if (isset($telefone['telefone1'])) echo $telefone['telefone1']; ?>" >
                                </div>
                                
                                <label class="col-sm-1 control-label">Telefone 2: </label>
                                <div class="col-sm-3">
                                    <input type="text" id="telefone2"  class="form-control" value="<?php  if (isset($telefone['telefone2'])) echo $telefone['telefone2']; ?>" >
                                </div>
                                
                            </div>
                    </div>
            </div>
        
          
            <div class="row">
                <div class="col-md-12">
                            <div class="form-group">
                                <label class="col-sm-1 control-label">E-mail: </label>
                                <div class="col-sm-11">
                                    <input type="text" id="email"  class="form-control" value="<?php  if (isset($telefone['email'])) echo $telefone['email']; ?>" >
                                </div>
                            </div>
                    </div>
            </div>
        
            <div class="row">
                <div class="form-group-endereco">
                    <div class="col-md-4">
                        <div class="row"><label class="col-sm-12 control-label">CEP</label></div>
                        <div class="row">
                            <div class="col-sm-9">
                                <input type="text" id="cep"   class="form-control" value="<?php  if (isset($telefone['cep'])) echo $telefone['cep']; ?>" >
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
                                <input type="text" id="rua"  class="form-control" value="<?php  if (isset($telefone['rua'])) echo $telefone['rua']; ?>" >
                            </div>
                             
                        </div> 
                    </div>
                    <div class="col-md-4">
                        <div class="row"><label class="col-sm-12 control-label">Número</label></div>
                        <div class="row">
                            <div class="col-sm-12">
                                <input type="text" id="numero"  class="form-control" value="<?php  if (isset($telefone['numero'])) echo $telefone['numero']; ?>" >
                            </div>
                             
                        </div> 
                    </div>
                </div>
            </div>
        
            <div class="row">
                    <div class="form-group-endereco">
                        <div class="col-md-4">
                            <div class="row"><label class="col-sm-12 control-label">Complemento</label></div>
                            <div class="row">
                                <div class="col-sm-12">
                                    <input type="text" id="complemento"   class="form-control" value="<?php  if (isset($telefone['complemento'])) echo $telefone['complemento']; ?>" >
                                </div>
                                 
                            </div> 
                        </div>
                        <div class="col-md-4">
                            <div class="row"><label class="col-sm-12 control-label">Bairro</label></div>
                            <div class="row">
                                <div class="col-sm-12">
                                    <input type="text" id="bairro"  class="form-control" value="<?php  if (isset($telefone['bairro'])) echo $telefone['bairro']; ?>" >
                                </div>

                            </div> 
                        </div>
                        <div class="col-md-3">
                            <div class="row"><label class="col-sm-12 control-label">Cidade</label></div>
                            <div class="row">
                                <div class="col-sm-12">
                                    <input type="text" id="cidade"  class="form-control" value="<?php  if (isset($telefone['cidade'])) echo $telefone['cidade']; ?>" >
                                </div>

                            </div> 
                        </div>
                        
                        <div class="col-md-1">
                            <div class="row"><label class="col-sm-12 control-label">UF</label></div>
                            <div class="row">
                                <div class="col-sm-12">
                                    <select class="form-control mb-md" name="uf" id="uf">
                                        <?php
                                            $uf = (isset($telefone['uf'])) ? $telefone['uf'] : null;
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
                        <div class="col-md-12">
                            <div class="row"><label class="col-sm-12 control-label">Site</label></div>
                            <div class="row">
                                <div class="col-sm-12">
                                    <input type="text" id="site"   class="form-control" value="<?php  if (isset($telefone['site'])) echo $telefone['site']; ?>" >
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
                                <textarea class="form-control" id="observacao" maxlength="4999" rows="5" ><?php  if (isset($telefone['observacao'])) echo $telefone['observacao']; ?></textarea>
                            </div>

                        </div> 
                    </div>
                </div>
            </div>
        
            
        
           
        
            
    </div>
    
    <div class="row botoes">
        <?php
        if (\Application::isAuthorized('Cadastros Basicos' , 'telefones_uteis', 'escrever') )
        {?>
        <button type="button" class="mb-xs mt-xs mr-xs btn btn-danger" onclick="salvar()"><i class="material-icons material-align-icons-button">&#xE877;</i>&nbsp;Salvar</button>
        <?php } ?>
        
        <?php
        if (\Application::getUrlParams(0) !== null &&  \Application::isAuthorized('Cadastros Basicos' , 'telefones_uteis', 'remover') )
        {?>
            <button type="button" class="mb-xs mt-xs mr-xs btn btn-grey" onclick="remover()"><i class="material-icons material-align-icons-button"></i>&nbsp;Remover</button>
        <?php } ?>
        
      
        <button type="button" class="mb-xs mt-xs mr-xs btn btn-grey" onclick="document.location='/<?php echo strtolower(\Application::getNameController()); ?>/cadastrar-telefone-util/'"><i class="material-icons material-align-icons-button">&#xE145;</i>&nbsp;Novo</button>
        
    
    </div>
</section>




<script>
$.getScript('/library/javascript/telefonesuteis/cadastrar.js');
    
    
    
$.getScript('/library/jsvendor/jquery-masked-input/jquery.maskedinput.min.js', function(){
    //$('#cpf').mask('999.999.999-99',{placeholder:"___.___.___-__", autoclear: false});
    $('#cep').mask('99999-999',{placeholder:"_____-__", autoclear: true});
    $('#telefone1, #telefone2').mask('(99)9999-99999?',{placeholder:"(__)____-_____", autoclear: false});
 //   $('#box-telefones div.panel-body input.fone').mask('(99)9999-99999?',{placeholder:"(__)____-_____", autoclear: false});
  //  $('.nb').mask('9999999999');
});
    

<?php
    if (isset($telefone['id']) && $telefone !== null)
        echo 'key=' . $telefone['id']. ';';
?>

</script>