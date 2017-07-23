<?php
$subtabela = (isset($this->getParams('subtabela')[0])) ? $this->getParams('subtabela') [0]:  null;

if ($subtabela === null)
    \Application::print404();

//var_dump($subtabela);


?>
<style>
     section.panel div.panel-body div.row {margin-top: 1rem;}
    .tabela {width: 100%; margin-left: 1.5rem; margin-top: 2rem; }
</style>

<section class="panel">
        <header class="panel-heading">
            <h2 class="panel-title">Visualização de Tabela</h2>
        </header>
        <div class="panel-body">
                <div class="row">
                    <div class="col-md-12">
                            <div class="col-md-2">
                                    <label>Nome do Banco</label>
                            </div>
                            <div class="col-md-8">
                                    <input type="text" class="form-control" disabled value="<?php if(isset($subtabela['nomeBanco'])) echo $subtabela['nomeBanco']; ?>" />
                            </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                            <div class="col-md-2">
                                    <label>Nome do Convênio</label>
                            </div>
                            <div class="col-md-3">
                                    <input type="text" class="form-control" disabled value="<?php if(isset($subtabela['nomeConvenio'])) echo $subtabela['nomeConvenio']; ?>" />
                            </div>
                            <div class="col-md-2">
                                    <label>Nome da Tabela</label>
                            </div>
                            <div class="col-md-3">
                                    <input type="text" class="form-control" disabled value="<?php if(isset($subtabela['nomeTabela'])) echo $subtabela['nomeTabela']; ?>" />
                            </div>
                    </div>
                </div>
            
                <div class="row">
                    <div class="col-md-12">
                        <table class="tabela">
                            <thead>
                                <tr>
                                    <th>Prazo</th>
                                    <th>Coeficiente</th>
                                    <th>Comissão</th>
                                    <th>Comissão a Receber</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                        if (is_array($subtabela['prazos']))
                                            foreach($subtabela['prazos'] as $i => $value)
                                            { ?>
                                                    <tr>
                                                        <td><?php echo str_pad($value['prazo'], 2, '0', STR_PAD_LEFT) . 'x'; ?></td>
                                                        <td><?php echo $value['coeficiente'] .'%'; ?></td>
                                                        <td><?php echo $subtabela['comissaoTotal'] . '%'; ?></td>
                                                        <td></td>
                                                    </tr>
                               
                                <?php } ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            
            
        </div>
</section>