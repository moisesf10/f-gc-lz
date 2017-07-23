<style>
    .grupo-telefone {margin-top: 2rem;}
</style>

<?php
$processadorFoco = $this->getParams('processadorfoco');
$listaFoco = $this->getParams('listafoco');
$listaStatus = $this->getParams('listastatus');

?>

<?php if ($processadorFoco !== null) { ?>
<div class="alert alert-danger alert-dismissible" role="alert">
        <strong><i class="fa fa-warning"></i>Problemas ao processar foco:</strong> <?php echo $processadorFoco; ?>
</div>

<?php } ?>

<?php
if ($listaFoco == null)
{
    echo '<div class="alert alert-danger alert-dismissible" role="alert"> <strong><i class="fa fa-warning"></i>Você não tem clientes para focar</strong></div>';
}
else
{ ?>
<!-- start: page -->
<form id="form1">
    <section class="panel">
        <header class="panel-heading">
            <div class="panel-actions">
                <a href="#" class="fa fa-caret-down"></a>
                <a href="#" class="fa fa-times"></a>
            </div>
            <h2 class="panel-title">Dados do cliente</h2>
        </header>
        <div class="panel-body">
                <div class="form-group">
                    <label class="col-sm-2 control-label">CPF: </label>
                    <div class="col-sm-3">
                        <input type="text" name="cpf" readonly="readonly" id="cpf" class="form-control" value="<?php echo $listaFoco['cpf']; ?>">
                    </div>

                    <div class="form-group">
                        <label class="col-sm-2 control-label">Nome: </label>
                        <div class="col-sm-3">
                            <input type="text" name="nome"  readonly="readonly" id="nome" class="form-control maiusculo" value="<?php echo $listaFoco['nomeCliente']; ?>">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-2 control-label">Data de Nascimento /idade </label>
                        <div class="col-sm-3">
                            <input type="text" name="idade" readonly="readonly" id="idade" class="form-control" value="<?php echo $listaFoco['nascimento'] . '   (' . $listaFoco['idade'] . ' anos)'; ?>">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-2 control-label">Convenio </label>
                        <div class="col-sm-3">
                            <input type="text" name="convenio" id="convenio"   readonly="readonly" class="form-control maiusculo" value="<?php echo $listaFoco['nomeConvenioImportacao']; ?>">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-2 control-label">Dados Extras</label>
                        <div class="col-sm-8">
                            <input type="text" name="dadosextras"  id="dadosextras" readonly="readonly" class="form-control maiusculo" value="<?php echo $listaFoco['dadosExtras']; ?>">
                        </div>
                    </div>
                </div>
        </div>
</section>

                
<section class="panel">
    <header class="panel-heading">
    <div class="text-right box-button-add-telefone">
        <button class="mb-xs mt-xs mr-xs btn btn-success" onclick="adicionarTelefones()">+ Adicionar Telefone</button>
    </div>
        <h2 class="panel-title">Telefones</h2>
    </header>
    <div class="panel-body">
        <div class="form-group box-telefone">
        <?php
            $contador = 0;
            if (is_array($listaFoco['telefones']))
                foreach($listaFoco['telefones'] as $telefone)
                { 
                    $checked = $telefone['certo'];
                ?>
                    <div class="col-md-6 col-sm-6 grupo-telefone" >
                        <label class="col-sm-2 control-label">Telefone</label>
                        <div class="col-sm-4">
                            <input type="text" name="telefone<?php echo $contador; ?>" id="telefone<?php echo $contador; ?>"  readonly="readonly" class="form-control" value="<?php echo $telefone['numero']; ?>" data-number="<?php echo $contador; ?>">
                        </div>
                        <div class="col-md-6">
                            <label class="checkbox-inline">
                                <input type="radio" id="radiotelefone<?php echo $contador; ?>" name="radiotelefone<?php echo $contador; ?>" value="1" <?php if ($telefone['certo'] == 1) echo 'CHECKED="checked"'; ?> > Certo
                            </label>
                            <label class="checkbox-inline">
                                <input type="radio" id="radiotelefone<?php echo $contador; ?>" name="radiotelefone<?php echo $contador++; ?>" value="0" <?php if ($telefone['certo'] == 0) echo 'CHECKED="checked"'; ?> > Errado
                            </label>
                        </div>
                    </div>
          <?php } ?>
            <div class="col-md-6 col-sm-6 grupo-telefone">
                <div class="form-group">
                    <label class="col-sm-2 control-label">Telefone</label>
                    <div class="col-sm-4">
                        <input type="text" name="telefone<?php echo $contador; ?>" id="telefone<?php echo $contador; ?>"  class="form-control" value="" data-number="<?php echo $contador; ?>" onblur="adicionarTelefones()">
                    </div>
                    <div class="col-md-6">
                        <label class="checkbox-inline">
                            <input type="radio" id="radiotelefone<?php echo $contador; ?>"  name="radiotelefone<?php echo $contador; ?>" value="1"  checked> Certo
                        </label>
                        <label class="checkbox-inline">
                            <input type="radio" id="radiotelefone<?php echo $contador; ?>"  name="radiotelefone<?php echo $contador++; ?>" value="0">  Errado
                        </label>
                    </div>	
                </div>
            </div>
        </div>
    </div>
</section>
             

<section class="panel">
    <header class="panel-heading">
        <h2 class="panel-title">Agenda</h2>
    </header>
    <div class="panel-body">	
        <div class="form-group">
            <label class="col-md-2 control-label" for="inputSuccess">Tipo de cliente</label>
            <div class="col-md-3">
                <select class="form-control mb-md" name="tipocliente">
                    <option></option>
                    <option value="Cliente Interno" <?php if ($listaFoco['tipoCliente'] == 'Cliente Interno') echo 'SELECTED="selected"'; ?> >Cliente Interno</option>
                    <option value="Cliente Externo" <?php if ($listaFoco['tipoCliente'] == 'Cliente Externo') echo 'SELECTED="selected"'; ?> >Cliente Externo</option>

                </select>
            </div>
            <label class="col-md-2 control-label" for="inputSuccess">Status</label>
            <div class="col-md-3">
                <select class="form-control mb-md" name="status" id="status">
                    <option></option>
                    <?php
                        if (is_array($listaStatus))
                            foreach($listaStatus as $status)
                            { 
                                $selected = ($status['id'] == $listaFoco['status']) ? 'SELECTED="selected"' : '';
                            ?>
                    <option <?php echo $selected; ?> value="<?php echo $status['id']; ?>"><?php echo $status['descricao']; ?></option>
                  <?php } ?>
                </select>
            </div>
        </div>
        <div class="form-group">
            <label class="col-md-2 control-label">Data da ligação</label>
            <div class="col-md-2">
                <div class="input-group">
                    <span class="input-group-addon">
                        <i class="fa fa-calendar"></i>
                    </span>
                    <?php
                        $aux = explode(' ', $listaFoco['dataLigacao']);
                        $dataLigacao = (isset($aux[0])) ? $aux[0] : '';
                        $horaLigacao = (isset($aux[1])) ? $aux[1] : '';
                    ?>
                    <input type="text" data-plugin-datepicker class="form-control" id="dataligacao" name="dataligacao" value="<?php echo $dataLigacao; ?>" >
                </div>
            </div>


            <label class="col-md-3	 control-label" for="inputDefault">Hora da ligação</label>
            <div class="col-md-2">
                <input type="text" class="form-control" id="horaligacao" name="horaligacao" value="<?php echo $horaLigacao; ?>" >
            </div>
        </div>

        <div class="form-group">
            <label class="col-md-3 control-label" for="textareaDefault">Observações</label>
            <div class="col-md-6">
                <textarea class="form-control" rows="3" id="observacoes" name="observacoes"><?php echo $listaFoco['observacoes']; ?></textarea>
            </div>
        </div>
        <button type="button" id="buttonSalvar" class="mb-xs mt-xs mr-xs btn btn-danger" onclick="salvar()"><i class="material-icons material-align-icons-button"></i>&nbsp;Salvar</button>
    </div>
</section>
</form>
<script src="/library/jsvendor/bootstrap-datepicker/js/bootstrap-datepicker.js"></script>

<script>
    $.getScript('/library/jsvendor/jquery-masked-input/jquery.maskedinput.min.js', function(){
    $('#horaligacao').mask('99:99:99',{placeholder:"HH:mm:ss", autoclear: true});
    });
    <?php echo 'UID = ' . $listaFoco['idCliente']; ?>
</script>


<?php } ?>

<script src="/library/javascript/telemarketing/clientes_em_andamento.js?<?php echo uniqid(); ?>"></script>