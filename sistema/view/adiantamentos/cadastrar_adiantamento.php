<?php
$usuarios = $this->getParams('usuarios');
$adiantamento = $this->getParams('adiantamento');

if (\Application::getUrlParams(0) != null && $adiantamento == null)
    \Application::print404();


?>
<section class="panel">
        <header class="panel-heading">
            <h2 class="panel-title">Cadastro de Adiantamento</h2>
        </header>
        <div class="panel-body">

            <div class="panel-body">
                <div class="form-group">
                    <label class="col-md-3 control-label">Nome do Usuário</label>
                    <div class="col-md-4">
                        <select data-plugin-selectTwo class="form-control " id="usuario" name="usuario">
                            <optgroup label="Selecione o nome do usuário">
                                <option></option>
                                <?php
                                    if (is_array($usuarios))
                                        foreach($usuarios as $i => $value)
                                        {
                                            $selected = (! empty($adiantamento['idUsuario']) && $adiantamento['idUsuario'] == $value['id']  ) ? 'selected="selected"' : '';
                                            echo '<option '. $selected . ' value="'. $value['id'] . '">'. ucwords(strtolower($value['nome'])) . '</option>';
                                        }
                                ?>

                            </optgroup>

                        </select>
                    </div>
                </div>

            </div>

            <div class="panel-body">
                <div class="form-group">
                    <label class="col-md-3 control-label">Descrição do Adiantamento</label>
                    <div class="col-md-4">
                        <input type="text" class="form-control" id="descricao" name="descricao" value="<?php if(! empty($adiantamento['descricao'])) echo $adiantamento['descricao']; ?>">
                    </div>
                </div>
            </div>

            <div class="panel-body">
                <div class="form-group">
                    <label class="col-md-3 control-label">Pagar Como?</label>
                    <div class="col-md-4">
                        <select data-plugin-selectTwo class="form-control " id="tipopagamento" name="tipopagamento">
                            <optgroup label="Selecione o tipo de pagamento">
                                <option></option>
                                <option value="%" <?php if (isset($adiantamento['qtdParcelas']) && ($adiantamento['qtdParcelas'] == 0  || empty($adiantamento['qtdParcelas'])  )     ) echo 'selected="selected"'; ?>>%</option>
                                <option value="1" <?php if (isset($adiantamento['qtdParcelas']) && $adiantamento['qtdParcelas'] == 1) echo 'selected="selected"'; ?>>1x</option>
                                <option value="2" <?php if (isset($adiantamento['qtdParcelas']) && $adiantamento['qtdParcelas'] == 2) echo 'selected="selected"'; ?>>2x</option>
                                <option value="3" <?php if (isset($adiantamento['qtdParcelas']) && $adiantamento['qtdParcelas'] == 3) echo 'selected="selected"'; ?>>3x</option>
                                <option value="4" <?php if (isset($adiantamento['qtdParcelas']) && $adiantamento['qtdParcelas'] == 4) echo 'selected="selected"'; ?>>4x</option>
                                <option value="5" <?php if (isset($adiantamento['qtdParcelas']) && $adiantamento['qtdParcelas'] == 5) echo 'selected="selected"'; ?>>5x</option>
                                <option value="6" <?php if (isset($adiantamento['qtdParcelas']) && $adiantamento['qtdParcelas'] == 6) echo 'selected="selected"'; ?>>6x</option>
                                <option value="7" <?php if (isset($adiantamento['qtdParcelas']) && $adiantamento['qtdParcelas'] == 7) echo 'selected="selected"'; ?>>7x</option>
                                <option value="8" <?php if (isset($adiantamento['qtdParcelas']) && $adiantamento['qtdParcelas'] == 8) echo 'selected="selected"'; ?>>8x</option>
                                <option value="9" <?php if (isset($adiantamento['qtdParcelas']) && $adiantamento['qtdParcelas'] == 9) echo 'selected="selected"'; ?>>9x</option>
                                <option value="10" <?php if (isset($adiantamento['qtdParcelas']) && $adiantamento['qtdParcelas'] == 10) echo 'selected="selected"'; ?>>10x</option>
                                <option value="11" <?php if (isset($adiantamento['qtdParcelas']) && $adiantamento['qtdParcelas'] == 11) echo 'selected="selected"'; ?>>11x</option>
                                <option value="12" <?php if (isset($adiantamento['qtdParcelas']) && $adiantamento['qtdParcelas'] == 12) echo 'selected="selected"'; ?>>12x</option>
                            </optgroup>

                        </select>
                    </div>
                </div>
            </div>

            <div class="panel-body">
                <div class="form-group">
                    <label class="col-md-3 control-label">Parcela</label>
                    <div class="col-md-4">
                        <input type="text" class="form-control" id="parcela" name="parcela" onclick="updateMask()" value="<?php if(! empty($adiantamento['valorParcela'])) echo $adiantamento['valorParcela']; ?>">
                    </div>
                </div>
            </div>

            <div class="panel-body">
                <div class="form-group">
                    <label class="col-md-3 control-label">Valor Total a Pagar</label>
                    <div class="col-md-4">
                        <input type="text" class="form-control" id="totalpagar" name="totalpagar" value="<?php if(! empty($adiantamento['valorTotalPagar'])) echo $adiantamento['valorTotalPagar']; ?>">
                    </div>
                </div>
            </div>

            <div class="panel-body">
                <div class="form-group">
                    <label class="col-md-3 control-label">Valor Total Pago</label>
                    <div class="col-md-4">
                        <input type="text" class="form-control" id="totalpago" name="totalpago" disabled value="<?php if(! empty($adiantamento['valorTotalPago'])) echo 'R$ '. \Gauchacred\library\php\Utils::numberToMoney( $adiantamento['valorTotalPago']); ?>">
                    </div>
                </div>
            </div>


            <div class="panel-body">
              <button type="button" class="mb-xs mt-xs mr-xs btn btn-grey" onclick="document.location='/adiantamentos/cadastrar-adiantamento'">Novo Adiantamento</button>
                <?php if (\Application::isAuthorized('Adiantamentos' , 'adiantamentos_cadastro', 'escrever')  && ((isset($adiantamento['valorTotalPago'])  && $adiantamento['valorTotalPago'] == 0) || ! isset($adiantamento['valorTotalPago']))  ) { ?>
                    <button type="button" class="mb-xs mt-xs mr-xs btn btn-danger" onclick="salvar()">Salvar</button>
                <?php } ?>

                <?php if (\Application::isAuthorized('Adiantamentos' , 'adiantamentos_cadastro', 'remover')  && isset($adiantamento['valorTotalPago'])  && $adiantamento['valorTotalPago'] == 0  ) { ?>
                  <button type="button" class="mb-xs mt-xs mr-xs btn btn-grey" onclick="excluir()">Excluir</button>
                <?php } ?>

            </div>

    </div>

</section>


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


<script src="/library/javascript/adiantamentos/cadastrar_adiantamento.js?=_<?php echo uniqid(); ?>"></script>
<script src="/library/jsvendor/select2/select2.min.js"></script>
<script src="/library/jsvendor/bootstrap-datepicker/js/bootstrap-datepicker.js"></script>
<script src="/library/jsvendor/magnific-popup/magnific-popup.js"></script>
<script src="/library/jsvendor/jquery-maskmoney/dist/jquery.maskMoney.min.js"></script>

<script>
$.getCSS('/library/jsvendor/select2/select2.css');
$.getCSS('/library/jsvendor/select2/select2.custom.css');
$.getCSS('/library/jsvendor/magnific-popup/magnific-popup.css');

$('#totalpagar, #totalpago').maskMoney({prefix:'R$ ', allowNegative: false, thousands:'.', decimal:',', affixesStay: true});

function updateMask()
{
    if ($('#tipopagamento').val() == '%')
        $('#parcela').maskMoney({prefix:'', allowNegative: false, thousands:'', decimal:',', affixesStay: true});
    else
        $('#parcela').maskMoney({prefix:'R$ ', allowNegative: false, thousands:'.', decimal:',', affixesStay: true});

}

<?php
if (! empty($adiantamento['id']))
echo 'key = '. $adiantamento['id'] . ';';
?>

</script>
