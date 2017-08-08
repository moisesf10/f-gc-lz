<?php

$despesa = $this->getParams('despesa');

if (\Application::getUrlParams(0) != null && $despesa == null)
    \Application::print404();


?>
<form id="form1">
    <section class="panel">
            <header class="panel-heading">
                <h2 class="panel-title">Cadastro de Despesas a Pagar</h2>
            </header>
            <div class="panel-body">


                <div class="panel-body">
                    <div class="form-group">
                        <label class="col-md-3 control-label">Descrição</label>
                        <div class="col-md-4">
                            <input type="text" class="form-control" id="descricao" name="descricao" value="<?php if(! empty($despesa['descricao'])) echo $despesa['descricao']; ?>">
                        </div>
                    </div>
                </div>

                <div class="panel-body">
                    <div class="form-group">
                        <label class="col-md-3 control-label">Vence em</label>
                        <div class="col-md-4">
                            <div class="input-daterange input-group" data-plugin-datepicker>
                            <input type="text" class="form-control" id="datavencimento" name="datavencimento" value="<?php if(! empty($despesa['vencimento'])) echo $despesa['vencimento']; ?>">
                            </div>
                        </div>
                    </div>
                </div>

                <div class="panel-body">
                    <div class="form-group">
                        <label class="col-md-3 control-label">Valor Devido</label>
                        <div class="col-md-4">
                            <input type="text" class="form-control money" id="valordevido" name="valordevido" value="<?php if(! empty($despesa['valorDevido'])) echo $despesa['valorDevido']; ?>">
                        </div>
                    </div>
                </div>

                <div class="panel-body">
                    <div class="form-group">
                        <label class="col-md-3 control-label">Valor Pago</label>
                        <div class="col-md-4">
                            <input type="text" class="form-control money" id="valorpago" name="valorpago" value="<?php if(! empty($despesa['valorPago'])) echo $despesa['valorPago']; ?>">
                        </div>
                    </div>
                </div>

                <div class="panel-body">
                    <div class="form-group">
                        <label class="col-md-3 control-label">Pago em</label>
                        <div class="col-md-4" >
                            <div class="input-daterange input-group" data-plugin-datepicker>
                            <input type="text" class="form-control" id="datapagamento" name="datapagamento" value="<?php if(! empty($despesa['pagamento'])) echo $despesa['pagamento']; ?>">
                            </div>
                        </div>
                    </div>
                </div>



                <div class="panel-body">
                  <button type="button" class="mb-xs mt-xs mr-xs btn btn-grey" onclick="document.location='/administracao/cadastrar-despesas-pagar'">Nova Despesa</button>
                    <?php if (\Application::isAuthorized('Administracao' , 'despesas_pagar', 'escrever')  ) { ?>
                        <button type="button" class="mb-xs mt-xs mr-xs btn btn-danger" onclick="salvar()">Salvar</button>
                    <?php } ?>

                    <?php if (\Application::isAuthorized('Administracao' , 'despesas_pagar', 'remover')  && isset($despesa['id'])  ) { ?>
                      <button type="button" class="mb-xs mt-xs mr-xs btn btn-grey" onclick="excluir()">Excluir</button>
                    <?php } ?>

                </div>

        </div>

    </section>
</form>


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


<script src="/library/javascript/despesas/cadastrar.js?=_<?php echo uniqid(); ?>"></script>
<script src="/library/jsvendor/select2/select2.min.js"></script>
<script src="/library/jsvendor/bootstrap-datepicker/js/bootstrap-datepicker.js"></script>
<script src="/library/jsvendor/magnific-popup/magnific-popup.js"></script>
<script src="/library/jsvendor/jquery-maskmoney/dist/jquery.maskMoney.min.js"></script>

<script>
$.getCSS('/library/jsvendor/select2/select2.css');
$.getCSS('/library/jsvendor/select2/select2.custom.css');
$.getCSS('/library/jsvendor/magnific-popup/magnific-popup.css');

$('.money').maskMoney({prefix:'R$ ', allowNegative: false, thousands:'.', decimal:',', affixesStay: true});

function updateMask()
{
    if ($('#tipopagamento').val() == '%')
        $('#parcela').maskMoney({prefix:'', allowNegative: false, thousands:'', decimal:',', affixesStay: true});
    else
        $('#parcela').maskMoney({prefix:'R$ ', allowNegative: false, thousands:'.', decimal:',', affixesStay: true});

}

<?php
if (! empty($despesa['id']))
echo 'key = '. $despesa['id'] . ';';
?>

</script>
