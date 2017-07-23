<?php
$convenios = $this->getParams('convenios');
$usuarios = $this->getParams('usuarios');
?>

<form name="form1" id="form1">
<!-- start: page -->
            <section class="panel">
                    <header class="panel-heading">
                        <div class="panel-actions">
                            <a href="#" class="fa fa-caret-down"></a>
                            <a href="#" class="fa fa-times"></a>
                        </div>
                    </header>
                <div class="panel-body">
                            <div class="row">
                    <h4>Importar base de clientes ao sistema </h4>
                      </div>

                        <div class="row">
                                    <div class="col-sm-7">
                                        <div class="mb-md">
                                        <label class="control-label">Selecione o Arquivo a ser Enviado</label>
                                </div>
                            </div>
                         </div>
                          
                          <div class="row">
                               <div class="controls col-md-2">
                                    <input type="file" id="file" name="file" class="hidden" />
                                        <input type="text" value="" id="filename" class="form-control" readonly="readonly">
                                </div>
                              <div class="col-md-3">
                                    <button type="button" class="btn btn-info mr-xs mb-sm" onclick="carregarArquivo()">Carregar arquivo</button>
                              </div>
                            </div>
                            
                            <br />
                            <div class="row">
                                <h2 class="panel-title">Resultado da importação </h2>
                            </div>
                            
                            
                            <div class="row">
                                <div class="row">
                                    <label class="col-md-2 control-label">Nome da Importação</label>
                                    <label class="col-md-2 control-label">Convenio</label>
                                    <label class="col-md-3 control-label">Selecione os usuarios</label>
                                </div>
                                <div class="row">
                                    <div class="col-md-2">
                                        <input type="text" id="nomeimportacao" name="nomeimportacao" class="form-control">
                                    </div>
                                    
                                    <div class="col-md-2">
                                        <select class="form-control mb-md" id="convenio" name="convenio">
                                            <option></option>
                                            <?php
                                            if (is_array($convenios))
                                                foreach($convenios as $convenio) 
                                                { ?>
                                                    <option value="<?php echo $convenio['id']; ?>"><?php echo $convenio['nome']; ?></option>
                                            
                                          <?php } ?>
                                        
                                        </select>
                                    </div>
 
                                    <div class="col-md-3">
                                        <select multiple data-plugin-selectTwo class="form-control populate" id="usuarios">
                                            <optgroup label="Selecione o usuario">
                                                  <option></option>
                                            <?php
                                            if (is_array($usuarios))
                                                foreach($usuarios as $usuario) 
                                                { ?>
                                                    <option value="<?php echo $usuario['id']; ?>"><?php echo $usuario['nome']; ?></option>
                                            
                                          <?php } ?>
                                            </optgroup>
                                        </select>
                                    </div>
                                    
                                    
                                </div>
                            </div>
        
                </div>
        <!-- end: page -->
        </section>
</form>
<div class="row">
    <div class="col-md-12">
        <button type="button" id="buttonimportar" class="mb-xs mt-xs mr-xs btn btn-danger" onclick="importar()">Importar</button>
    </div>
</div>

<script src="/library/jsvendor/select2/select2.min.js"></script>
<script src="/library/javascript/telemarketing/importar_clientes.js"></script>
<script>
$.getCSS('/library/jsvendor/select2/select2.css');
$.getCSS('/library/jsvendor/select2/select2.custom.css');
</script>