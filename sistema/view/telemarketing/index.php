<?php
$ler = \Application::isAuthorized(ucfirst('clientes') , 'telemarketing', 'ler');
$escrever = \Application::isAuthorized(ucfirst('clientes') , 'telemarketing', 'escrever');
$remover = \Application::isAuthorized(ucfirst('clientes') , 'telemarketing', 'remover');
$telemarketing = $this->getParams('telemarketing');


?>
		
						
<div class="form-group">
    <a href="/cliente/telemarketing-importar-clientes" button="button" id="addToTable" class="btn btn-danger">Importar Clientes <em class="fa fa-plus"></em></a>
</div>


<section class="panel">
    <section class="panel">
            <header class="panel-heading">
            <div class="panel-actions"></div>
            <h2 class="panel-title">Foco Listas</h2>
            </header>
            <div class="panel-body">
            <div class="row">
                <div class="col-sm-6">
                    <div class="mb-md">

                    </div>
                </div>
            </div>
            <table class="table table-bordered table-striped mb-none" id="datatable-editable">
                <thead>
                    <tr>
                        <th>Convênio </th>
                        <th>Data da importação</th>
                        <th>Numero de clientes</th>
                        <th>Clientes trabalhados</th>
                        <th>Nome da Importação</th>
                        <th>Editar</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                        if (is_array($telemarketing))   
                        {
                            foreach($telemarketing as $telem)
                            {
                                ?>
                                <tr class="gradeX">
                                    <td><?php echo $telem['nomeConvenio']; ?></td>
                                    <td><?php echo $telem['created']; ?></td>
                                    <td><?php echo $telem['totalClientesImportados']; ?></td>
                                    <td><?php echo $telem['totalClientesTrabalhados']; ?></td>
                                    <td><?php echo $telem['nomeImportacao']; ?></td>
                                    <td class="actions">
                                        <a href="/cliente/telemarketing-cliente-em-andamento/<?php echo $telem['id']; ?>" class="on-default edit-row"><i class="fa  fa-arrow-right"></i></a>
                                    <?php if ($escrever){ ?> 
                                        <a href="/cliente/telemarketing-atribuir-usuarios/<?php echo $telem['id']; ?>" class="on-default edit-row"><i class="fa  fa-group"></i></a>
                                        <a href="#" onclick="reprocessar('<?php echo $telem['id']; ?>')" class="on-default edit-row"><i class="fa  fa-rotate-right"></i></a>
                                        <a href="/cliente/telemarketing-download/<?php echo $telem['id']; ?>" class="on-default edit-row" target="_blank"><i class="fa  fa-download"></i></a>
                                        <a href="/cliente/telemarketing-relatorio/<?php echo $telem['id']; ?>" class="on-default edit-row" target="_blank"><i class="fa  fa-database"></i></a>
                                    <?php } if ($remover){ ?> 
                                        <a href="#" onclick="excluir('<?php echo $telem['id']; ?>')" class="on-default remove-row"><i class="fa fa-trash-o"></i></a>
                                     <?php    } ?>
                                    </td>
                                </tr>
                  <?php     } 
                        } ?>
                   
                </tbody>
            </table>
            </div>
        </section>
    <!-- end: page -->
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

<script src="/library/jsvendor/magnific-popup/magnific-popup.js"></script>

<script>
$.getCSS('/library/jsvendor/magnific-popup/magnific-popup.css');
    
function reprocessar(id)
{
	$.magnificPopup.open({ 
        items: { src: '#modalSuccess'},
        type: 'inline',
		preloader: false,
		modal: true
                         
       });

    $.ajax({
          type: "POST",
          url:  "/cliente/telemarketing-reprocessar/",
          data: '&id='+id,
          dataType: 'json',
        success: function(json){
            $.magnificPopup.close();
            
             if (json.success == true)
            {
                alert('Importação reprocessada com sucesso');
            }else
            {
                alert('A importação não pode ser reprocessada. '+ json.message);
            }
        },
        error: function(e){
            $.magnificPopup.close();
            alert('A importação não pode ser reprocessada. '+ e.responseText);
        }
    });
}
    
    
function excluir(id)
{
	$.magnificPopup.open({ 
        items: { src: '#modalSuccess'},
        type: 'inline',
		preloader: false,
		modal: true
                         
       });

    $.ajax({
          type: "POST",
          url:  "/cliente/telemarketing-excluir/",
          data: '&id='+id,
          dataType: 'json',
        success: function(json){
            $.magnificPopup.close();
            
             if (json.success == true)
            {
                alert('Importação excluida com sucesso');
                document.location.reload();
            }else
            {
                alert('A importação não pode ser excluida. '+ json.message);
            }
        },
        error: function(e){
            $.magnificPopup.close();
            alert('A importação não pode ser excluida. '+ e.responseText);
        }
    });
}
    
</script>