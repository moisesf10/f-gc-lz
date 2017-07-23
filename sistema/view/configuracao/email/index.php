<?php
$email =  $this->getParams('email');


if (\Application::getUrlParams(0) !== null && $produto === null)
    \Application::print404();
?>
<style>
    section.panel div.panel-body div.row {padding-bottom: 1rem;}
    div.botoes {margin-left: 0.2rem;}
    div.botoes div {padding-right: 1rem; }
    #descricao {height: 20rem;}
    .nota {font-size: 1.1rem; }
    #para {height: 15rem;}

</style>
<form id="form1">
<section class="panel">
        <header class="panel-heading">
            <h2 class="panel-title">Configuração de E-mail</h2>
        </header>
        <div class="panel-body">
            <div class="row">
                <div class="col-md-5">
                      <div class="form-group">
                          <div class="row">
                            <label class="col-sm-12 control-label">Servidor SMTP: </label>
                          </div>
                          <div class="row">
                            <div class="col-sm-12">
                                <input type="text" id="smtpserver" name="smtpserver"  class="form-control"  value="<?php  if (isset($email['smtpServer'])) echo $email['smtpServer']; ?>" >
                            </div>
                          </div>
                      </div>
                  </div>
                  <div class="col-md-3">
                        <div class="form-group">
                            <div class="row">
                              <label class="col-sm-12 control-label">Porta: </label>
                            </div>
                            <div class="row">
                              <div class="col-sm-12">
                                  <input type="text" id="smtpport" name="smtpport"  class="form-control"  value="<?php  if (isset($email['smtpPort'])) echo $email['smtpPort']; ?>" >
                              </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                          <div class="form-group">
                              <div class="row">
                                <label class="col-sm-12 control-label">Segurança: </label>
                              </div>
                              <div class="row">
                                <div class="col-sm-6">
                                    <select id="smtpsecurity" name="smtpsecurity"  class="form-control">
                                      <option value="">Nenhum</option>
                                      <option value="ssl" <?php if(isset($email['smtpSecurity']) && $email['smtpSecurity'] == 'ssl') echo 'selected="selected"';  ?>>SSL</option>
                                      <option value="tls" <?php if(isset($email['smtpSecurity']) && $email['smtpSecurity'] == 'tls') echo 'selected="selected"';  ?>>tls</option>
                                    </select>
                                </div>
                              </div>
                          </div>
                      </div>
            </div>

            <div class="row">
                <div class="col-md-5">
                      <div class="form-group">
                          <div class="row">
                            <label class="col-sm-12 control-label">Login: </label>
                          </div>
                          <div class="row">
                            <div class="col-sm-12">
                                <input type="text" id="smtplogin" name="smtplogin"  class="form-control"  value="<?php  if (isset($email['smtpLogin'])) echo $email['smtpLogin']; ?>" >
                            </div>
                          </div>
                      </div>
                  </div>
                  <div class="col-md-3">
                        <div class="form-group">
                            <div class="row">
                              <label class="col-sm-12 control-label">Senha: </label>
                            </div>
                            <div class="row">
                              <div class="col-sm-12">
                                  <input type="password" id="smtppassword" name="smtppassword"  class="form-control"  value="<?php  if (isset($email['smtpPassword'])) echo $email['smtpPassword']; ?>" >
                              </div>
                            </div>
                        </div>
                    </div>
            </div>

            <div class="row">
              <div class="col-md-12">
                    <div class="form-group">
                        <div class="row">
                          <label class="col-sm-12 control-label">Enviar para: <label class="nota">separar e-mails com ponto e virgula (;)</label> </label>
                        </div>
                        <div class="row">
                          <div class="col-sm-12">
                              <textarea class="form-control" name="para" id="para"><?php if(isset($email['para'])) echo $email['para'];  ?></textarea>
                          </div>
                        </div>
                    </div>
                </div>
            </div>

    </div>


    <div class="row botoes">
        <?php
        if (\Application::isAuthorized('Troca de Pontos' , 'loja_pontos_cadastrar_produtos', 'escrever') )
        {?>
        <button type="button" class="mb-xs mt-xs mr-xs btn btn-danger" onclick="salvar()"><i class="material-icons material-align-icons-button">&#xE877;</i>&nbsp;Salvar</button>
        <?php } ?>

        <?php
        if (\Application::getUrlParams(0) !== null &&  \Application::isAuthorized('Troca de Pontos' , 'loja_pontos_cadastrar_produtos', 'remover') )
        {?>
            <button type="button" class="mb-xs mt-xs mr-xs btn btn-grey" onclick="remover()"><i class="material-icons material-align-icons-button">&#xE92B;</i>&nbsp;Remover</button>
        <?php } ?>

         <button type="button" class="mb-xs mt-xs mr-xs btn btn-grey" onclick="document.location = '/administracao/cadastrar-banco'"><i class="material-icons material-align-icons-button">&#xE145;</i>&nbsp;Novo</button>



    </div>

</section>

</form>



<script src="/library/jsvendor/bootstrap-datepicker/js/bootstrap-datepicker.js"></script>

<script>

$.getScript('/library/jsvendor/jquery-masked-input/jquery.maskedinput.min.js', function(){
    $('#pontos').mask('999999999',{placeholder:"", autoclear: false});
});

function salvar()
{

    $.ajax({
        type: "POST",
        url: '<?php echo '/'. strtolower(\Application::getNameController()) . '/salvar-configuracao-email'; ?>',
        cache: false,
        dataType: "json",
        data: $('#form1').serialize(),
        success: function(json){
            if (json.success)
             {
                 alert('Registro salvo com sucesso');
                 //$('#id').val(json.id);
                 document.location = '<?php echo '/'. strtolower(\Application::getNameController()) . '/configurar-email/'; ?>';
             }
            else
            {
                alert('Não foi possível realizar a alteração\nMotivo: ' + json.message);
                //document.location.reload();
            }
        }
    });

}


</script>
