<?php
$usuariosSorteio = $this->getParams('usuariossorteio');
$usuarios = $this->getParams('usuarios');

?>
<section class="panel">
        <header class="panel-heading">
            <h2>Atribuir Usuários</h2>
            <div class="panel-actions">
                <a href="#" class="fa fa-caret-down"></a>
                <a href="#" class="fa fa-times"></a>
            </div>
        </header>
         <div class="panel-body">
                <div class="row">
                    <div class="row">
                        <label class="col-md-12">Nome da Importação</label>
                    </div>
                    <div class="col-md-2">
                        <input type="text" class="form-control" disabled value="alguma coisa">
                    </div>
                </div>
             <br />
                <div class="row">
                    <h5 class="margin-bottom">Os seguintes usuarios tem acesso a essa lista</h5>
						<label class="col-md-2 control-label">Selecione os usuarios</label>
                                <div class="col-md-3">
                                    <select multiple data-plugin-selectTwo class="form-control populate" id="usuarios">
                                        <optgroup label="Selecione o usuario">
                                            <option></option>
                                            <?php
                                                if (is_array($usuarios))
                                                    foreach($usuarios as $usuario)
                                                    { 
                                                        $selected = (array_search($usuario['id'], array_column($usuariosSorteio, 'idUsuario')) === false ) ? '' : 'SELECTED="SELECTED"';
                                                    ?>
                                                        <option <?php echo $selected; ?> value="<?php echo $usuario['id']; ?>"><?php echo ucwords(strtolower($usuario['nome'])); ?></option>
                                            <?php   } ?>
                                                
                                            
                                        </optgroup>

                                    </select>
                                </div>
				</div>
                <div class="row">
					<button type="button" onclick="salvar()" class="mb-xs mt-xs mr-xs btn btn-danger">Salvar</button>
                </div>
             
    </div>
</section>


                                
                                
<script src="/library/jsvendor/select2/select2.min.js"></script>
<script src="/library/javascript/telemarketing/atribuir_usuarios.js"></script>
<script>
$.getCSS('/library/jsvendor/select2/select2.css');
$.getCSS('/library/jsvendor/select2/select2.custom.css');
    
<?php
echo 'UID = '. \Application::getUrlParams(0); 
?>
</script>

