<style>
.spoiler {
  background-color:darkslategray;
  color:white;
  padding:.15384615384615385em;
  margin:1em 0;
}

.spoiler-toggle,
.spoiler-toggle:focus,
.spoiler-toggle:hover,
.spoiler-toggle:active {
  display:block;
  margin:0;
  padding:0 1.2em 0 .6em;
  height:2em;
  font:normal normal 100%/2em Helmet,FreeSans,Sans-Serif;
  color:inherit;
  text-decoration:none;
  outline:none;
  position:relative;
  overflow:hidden;
  white-space:nowrap;
  text-overflow:ellipsis;
  cursor:pointer;
}

.spoiler-toggle:before {
  content:"";
  display:block;
  float:right;
  width:0;
  height:0;
  border-width:.3076923076923077em .3076923076923077em 0;
  border-style:solid;
  border-color:white transparent transparent;
  margin:.9230769230769231em -.6em 0 0;
}

.spoiler-content {
  padding:1em;
  background-color:white;
  color:#333;
}

.spoiler-state-expanded .spoiler-toggle {padding-bottom:inherit}

.spoiler-state-expanded .spoiler-toggle:before {
  border-width:0 .3076923076923077em .3076923076923077em;
  border-color:transparent transparent white;
  margin-top:.8461538461538461em;
}

.spoiler-state-expanded .spoiler-content + .spoiler-toggle {
  padding-top:inherit;
  padding-bottom:0;
}

.spoiler-state-disabled .spoiler-toggle {
  cursor:default;
  cursor:not-allowed;
}

.spoiler-state-disabled .spoiler-toggle:before,
.spoiler-js .spoiler-state-collapsed .spoiler-content {display:none}

.spoiler-primary {background-color:steelblue}
.spoiler-success {background-color:mediumseagreen}
.spoiler-info {background-color:skyblue}
.spoiler-warning {background-color:sandybrown}
.spoiler-danger {background-color:salmon}

/* ignore this! */
#my-custom-id .spoiler-toggle {
  font-weight:bold;
  font-style:italic;
}

</style>



<?php
use Gauchacred\library\php\Utils as Utils;

$agenda = $this->getParams('agenda');
$aniversariantes = $this->getParams('aniversariantes');
$metaMensal = (isset($this->getParams('metamensal')[0])) ? $this->getParams('metamensal')[0] : null;
$totalVendasDia = $this->getParams('totalvendasdia');
$totalVendasPagasDia = $this->getParams('totalvendaspagasdia');
$valorVendaSemana = ($this->getParams('valorvendasemana') !== null) ? $this->getParams('valorvendasemana') : 0;
$valorVendaMes = ($this->getParams('valorvendames') !== null) ? $this->getParams('valorvendames') : 0;
//$historicoVendaDiaria = $this->getParams('historicovendadiaria');
//$historicoVendaMensal = $this->getParams('historicovendamensal');
$melhoresVendedores = $this->getParams('melhoresvendedores');

$totalContratosPendentes = $this->getParams('totalcontratospendentesmes');
$noticias = $this->getParams('noticias');
$comissaoSemanalNovo = $this->getParams('comissaosemanal');
$comissaoSemanalTodos = $this->getParams('comissaosemanaltodos');
$metasNovo = $this->getParams('metasnovo');
$gruposDoUsuario = $this->getParams('gruposdousuario');



$metaMesUsuario = $this->getParams('metamesusuario');
$metaMesLoja = $this->getParams('metamesloja');
$valoresGerais = $this->getParams('valoresgerais');
$metaTodosGrupos = $this->getParams('metatodosgrupos');
$pontosTroca = $this->getParams('pontostroca');

$descontosDevidos = $this->getParams('descontosdevidos');
$comissaoLoja = $this->getParams('comissaoloja');
//echo '<pre>'; var_dump($contratosPagos); exit;


// ***********
// METAS

$mSemanal = 0;
$mMensal = 0;
$mDiaria = 0;

if (is_array($metasNovo))
    foreach($metasNovo as $i => $m)
    {
        if ($m['idUsuario'] == $_SESSION['userid'])
        {
            switch($m['tipoMeta'])
            {

                case 'Semanal': $mSemanal = $m['valor']; break;
                case 'Mensal': $mMensal = $m['valor']; break;
               // case 'Semanal': $mSemanal = $m['valor']; break;
            }
        }else
        {
            if (is_array($gruposDoUsuario))
                foreach($gruposDoUsuario as $b => $gr)
                    if ($m['idGrupo'] === $gr['id'])
                    {
                        $mGrupo = $m['valor'];
                        $mNomeGrupo = $gr['nome'];

                    }

        }
    }




?>


<?php
//**********
/** CALCULO DA NOVA META DIARIA
*/

    $vlrMetaMes = (! isset($metaMesUsuario['valor'])) ? 0 : $metaMesUsuario['valor'];
    $vlrMetaMesIncrementada = $vlrMetaMes;
    if ($valorVendaMes > $vlrMetaMesIncrementada && $vlrMetaMesIncrementada > 0)
        while($vlrMetaMesIncrementada  < $valorVendaMes )
        {
            $vlrMetaMesIncrementada += $metaMesUsuario['valorIncremento'];
            // evita loop infinito
            if ($metaMesUsuario['valorIncremento'] == 0)
                break;
        }

    // calcula a meta diaria
    if ($metaMesUsuario == null || $vlrMetaMesIncrementada == 0  )
        $vlrMetaDia = 0;
    else
    {
        if (($metaMesUsuario['totalDiasUteis'] - $metaMesUsuario['numDiaUtilDoMes']) != 0)
            $vlrMetaDia = ($vlrMetaMesIncrementada - $valorVendaMes) / ($metaMesUsuario['totalDiasUteis'] - $metaMesUsuario['numDiaUtilDoMes'])  ;
        else
            $vlrMetaDia = 0;

    }


//**********
/** CALCULO DA NOVA META SEMANAL
*/

    // calcula a meta semanal
    if ($metaMesUsuario == null || $vlrMetaMesIncrementada == 0  )
        $vlrMetaSemana = 0;
    else
    {
        if ($metaMesUsuario['numSemanaAtual'] < $metaMesUsuario['numSemanaInicial'] )
            $vlrMetaSemana = 0;
        else
        {
            //
            if ($metaMesUsuario['numSemanaFinal'] - $metaMesUsuario['numSemanaAtual'] != 0)
                $vlrMetaSemana = ($vlrMetaMesIncrementada - $valorVendaMes) / (($metaMesUsuario['numSemanaFinal'] - $metaMesUsuario['numSemanaAtual']) + 1) ;
            else
                if ($vlrMetaMesIncrementada >  $valorVendaMes )
                    $vlrMetaSemana = $vlrMetaMesIncrementada - $valorVendaMes;
                else
                    $vlrMetaSemana = 0;
        }

    }





//var_dump($metaMesLoja); exit;
//**********
/** META LOJA
*/
   $nomeGrupoMetaLoja = '';
    $vlrMetaLoja = 0;
   if (is_array($metaMesLoja))
       foreach($metaMesLoja as $i => $value)
       {
           $nomeGrupoMetaLoja .= ' ' . $value['nomeGrupo'];
           $vlrMetaLoja += $value['valor'];
       }
?>

<style>
    .box-info {margin-top: 3rem;}
    .dataTables_wrapper td {text-align: left;}
    .agenda-status-pendente{background-color: #d50000;  color: #FFF; font-weight: bold; padding: 2px 8px 2px 8px;}
    .agenda-status-efetuada{background-color: #388e3c;  color: #FFF; font-weight: bold; padding: 2px 8px 2px 8px; }
    html, body {background-color: #eeeeee;}
    section.panel div.panel-body div.row {margin-top: 2rem}
    .bg-primary {
        background: #0088cc;
    }

</style>


<?php
// Se houver contratos pendentes mostra faixa de DANGER com comissão bloqueada
if ($totalContratosPendentes > 0)
{ ?>

    <div class="alert alert-danger alert-dismissible" role="alert">
        <strong><i class="fa fa-warning"></i>		  Comissão bloqueada:</strong> Regularize as pendencias.
        <ul>

        </ul>
    </div>
<?php } ?>

<?php
    if ($vlrMetaMes <= $valorVendaMes  && $vlrMetaMes > 0 )
    {
?>
    <div class="alert alert-success">
        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
        <strong>Parabéns você atingiu sua meta </strong>  Sua nova meta é de <?php echo Utils::numberToMoney($vlrMetaMesIncrementada); ?>
    </div>
<?php } ?>





 <section class="panel">

    <div class="panel-body">
            <div class="table-responsive">
                <table class="table table-striped mb-none">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Vendedor em destaque</th>
                            <th>Vendas no Mês</th>
                            <th>Vendas na Semana</th>
                            <th>Vendas no Dia</th>
                            <th>Vendas Vinculadas</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $cont = 1;
                        if (is_array($melhoresVendedores))
                            foreach($melhoresVendedores as $i => $value)
                            {
                              ?>
                        <tr>
                            <td><?php echo $cont++; ?></td>
                            <td><?php echo $value['nomeUsuario']; ?></td>
                            <td>R$ <?php echo Utils::numberToMoney($value['valorMes']); ?></td>
                            <td>R$ <?php echo Utils::numberToMoney($value['valorSemana']); ?></td>
                            <td>R$ <?php echo Utils::numberToMoney($value['valorDia']); ?></td>
                            <td>R$ <?php echo Utils::numberToMoney($value['valorVinculado']); ?></td>
                        </tr>
                    <?php } ?>

                    </tbody>
                </table>
            </div>
    </div>
</section>




<?php
if (isset($metaMesUsuario['valor']) && $metaMesUsuario > 0   )
{ ?>
    <section class="panel">
        <div class="row">
                <div class="col-lg-8">
                    <div>
                        <h4 class="text-center">Olá <?php echo $_SESSION['nome']; ?> você tem <?php echo  ($metaMesUsuario['totalDiasUteis'] - $metaMesUsuario['numDiaUtilDoMes']) ; ?> dias para concluir sua meta e Falta R$ <?php echo (($mMensal - $valorVendaMes) <= 0) ? Utils::numberToMoney(0) : Utils::numberToMoney($mMensal - $valorVendaMes); ?> para bater sua meta!	</h4>


                    </div>
                </div>

            </div>
    </section>
<?php } ?>







<div class="row">
    <div class="col-md-3 col-lg-3 col-xl-3">
        <section class="panel panel-featured-left panel-featured-primary">
            <div class="panel-body">
                <div class="widget-summary">
                    <div class="widget-summary-col widget-summary-col-icon">
                        <div class="summary-icon bg-primary">
                            <i class="fa  fa-bank"></i>
                        </div>
                    </div>
                    <div class="widget-summary-col">
                        <div class="summary">
                            <h4 class="title">Meta da Loja</h4>
                            <div class="info">
                                <strong class="amount"><?php echo (isset($vlrMetaLoja)) ? Utils::numberToMoney($vlrMetaLoja) : Utils::numberToMoney(0) ; ?></strong>

                            </div>
                        </div>
                        <div class="summary-footer">
                            <a class="text-muted text-uppercase"><?php echo (isset($nomeGrupoMetaLoja)) ? $nomeGrupoMetaLoja : '' ; ?></a>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
    <div class="col-md-3 col-lg-3 col-xl-3">
        <section class="panel panel-featured-left panel-featured-primary">
            <div class="panel-body">
                <div class="widget-summary">
                    <div class="widget-summary-col widget-summary-col-icon">
                        <div class="summary-icon bg-primary">
                            <i class="fa fa-money"></i>
                        </div>
                    </div>
                    <div class="widget-summary-col">
                        <div class="summary">
                            <h4 class="title">Meta Diaria</h4>
                            <div class="info">
                                <strong class="amount"><?php echo (isset($vlrMetaDia)) ? Utils::numberToMoney($vlrMetaDia) : Utils::numberToMoney(0) ; ?></strong>

                            </div>
                        </div>
                        <div class="summary-footer">

                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
    <div class="col-md-3 col-lg-3 col-xl-3">
        <section class="panel panel-featured-left panel-featured-primary">
            <div class="panel-body">
                <div class="widget-summary">
                    <div class="widget-summary-col widget-summary-col-icon">
                        <div class="summary-icon bg-primary">
                            <i class="fa fa-money"></i>
                        </div>
                    </div>
                    <div class="widget-summary-col">
                        <div class="summary">
                            <h4 class="title">Meta Semanal</h4>
                            <div class="info">
                                <strong class="amount"><?php echo (isset($vlrMetaSemana)) ? Utils::numberToMoney($vlrMetaSemana) : Utils::numberToMoney(0) ; ?></strong>

                            </div>
                        </div>
                        <div class="summary-footer">

                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
    <div class="col-md-3 col-lg-3 col-xl-3">
        <section class="panel panel-featured-left panel-featured-primary">
            <div class="panel-body">
                <div class="widget-summary">
                    <div class="widget-summary-col widget-summary-col-icon">
                        <div class="summary-icon bg-primary">
                            <i class="fa  fa-flag"></i>
                        </div>
                    </div>
                    <div class="widget-summary-col">
                        <div class="summary">
                            <h4 class="title">Meta Mensal</h4>
                            <div class="info">
                                <strong class="amount"><?php echo (isset($mMensal)) ? Utils::numberToMoney($mMensal) : Utils::numberToMoney(0) ; ?></strong>

                            </div>
                        </div>
                        <div class="summary-footer">

                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>

</div>



<div class="row">

                <div class="col-md-3 col-lg-3 col-xl-3">
                    <section class="panel panel-featured-left panel-featured-primary">
                        <div class="panel-body">
                            <div class="widget-summary">
                                <div class="widget-summary-col widget-summary-col-icon">
                                    <div class="summary-icon bg-primary">
                                        <i class="fa fa-chevron-down"></i>
                                    </div>
                                </div>
                                <div class="widget-summary-col">
                                    <div class="summary">
                                        <h4 class="title"> Vendas pagas no dia</h4>
                                        <div class="info">
                                            <strong class="amount"><?php echo Utils::numberToMoney($totalVendasPagasDia); ?></strong>
                                            <span class="text-primary"></span>
                                        </div>
                                    </div>
                                    <div class="summary-footer">
                                        <a class="text-muted text-uppercase">(Veja todas)</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </section>
                </div>


        <div class="col-md-3 col-lg-3 col-xl-3">
            <section class="panel panel-featured-left panel-featured-primary">
                <div class="panel-body">
                    <div class="widget-summary">
                        <div class="widget-summary-col widget-summary-col-icon">
                            <div class="summary-icon bg-primary">
                                <i class="fa fa-dollar"></i>
                            </div>
                        </div>
                        <div class="widget-summary-col">
                            <div class="summary">
                                <h4 class="title">Total de Vendas</h4>
                                <div class="info">
                                    <strong class="amount"><?php if (isset($valorVendaMes)) echo Utils::numberToMoney($valorVendaMes);  ?></strong>
                                </div>
                            </div>
                            <div class="summary-footer">
                                <a class="text-muted text-uppercase"></a>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
        </div>
        <div class="col-md-3 col-lg-3 col-xl-3">
            <section class="panel panel-featured-left panel-featured-primary">
                <div class="panel-body">
                    <div class="widget-summary">
                        <div class="widget-summary-col widget-summary-col-icon">
                            <div class="summary-icon bg-primary">
                                <i class="fa  fa-forward"></i>
                            </div>
                        </div>
                        <div class="widget-summary-col">
                            <div class="summary">
                                <h4 class="title">Vendas do dia</h4>
                                <div class="info">
                                    <strong class="amount"><?php echo Utils::numberToMoney($totalVendasDia); ?></strong>
                                </div>
                            </div>
                            <div class="summary-footer"> &nbsp;
                               <a class="text-muted text-uppercase"></a>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
        </div>
        <div class="col-md-3 col-lg-3 col-xl-3">
            <section class="panel panel-featured-left panel-featured-secondary">
                <div class="panel-body">
                    <div class="widget-summary">
                        <div class="widget-summary-col widget-summary-col-icon">
                            <div class="summary-icon bg-secondary">
                                <i class="fa fa-usd"></i>
                            </div>
                        </div>
                        <div class="widget-summary-col">
                            <div class="summary">
                                <h4 class="title">Comissão Semanal</h4>
                                <div class="info">
                                    <strong class="amount"><?php echo Utils::numberToMoney($comissaoSemanalNovo); ?></strong>
                                </div>
                            </div>
                            <div class="summary-footer">
                                <a class="text-muted text-uppercase">COMISSÃO PODE SOFRER ALTERAÇÃO</a>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
        </div>

</div>




<div class="row">
   <?php
    if ( (\Application::isAuthorized(ucfirst(strtolower(\Application::getNameController())) , 'pagina_inicial_comissao_total', 'ler') ||
            \Application::isAuthorized(ucfirst(strtolower(\Application::getNameController())) , 'pagina_inicial_comissao_total', 'escrever') )
           )
        { ?>
    <div class="col-md-3 col-lg-3 col-xl-3">
        <section class="panel panel-featured-left panel-featured-secondary">
            <div class="panel-body">
                <div class="widget-summary">
                    <div class="widget-summary-col widget-summary-col-icon">
                        <div class="summary-icon bg-secondary">
                            <i class="fa fa-usd"></i>
                        </div>
                    </div>
                    <div class="widget-summary-col">
                        <div class="summary">
                            <h4 class="title">Comissão Semanal Total</h4>
                            <div class="info">
                                <strong class="amount"><?php echo Utils::numberToMoney($comissaoSemanalTodos); ?></strong>
                            </div>
                        </div>
                        <div class="summary-footer">
                            <a class="text-muted text-uppercase">COMISSÃO PODE SOFRER ALTERAÇÃO</a>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>

<?php } ?>

    <div class="col-md-3 col-lg-3 col-xl-3">
        <section class="panel panel-featured-left panel-featured-primary">
            <div class="panel-body">
                <div class="widget-summary">
                    <div class="widget-summary-col widget-summary-col-icon">
                        <div class="summary-icon bg-quartenary">
                            <i class="fa  fa-group"></i>
                        </div>
                    </div>
                    <div class="widget-summary-col">
                        <div class="summary">
                            <h4 class="title">Meta do Grupo</h4>
                            <div class="info">
                                <strong class="amount"><?php echo (isset($mGrupo)) ? Utils::numberToMoney($mGrupo) : Utils::numberToMoney(0) ; ?></strong>

                            </div>
                        </div>
                        <div class="summary-footer">
                            <a class="text-muted text-uppercase"><?php echo (isset($mNomeGrupo)) ? $mNomeGrupo : '' ; ?></a>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>


    <?php
        if (is_array($metaTodosGrupos)  &&
           (\Application::isAuthorized(ucfirst(strtolower(\Application::getNameController())) , 'pagina_inicial_meta_todos_grupos', 'ler') ||
            \Application::isAuthorized(ucfirst(strtolower(\Application::getNameController())) , 'pagina_inicial_meta_todos_grupos', 'escrever') )
           )
        {
            $qtdColumns = 2;
            foreach($metaTodosGrupos as $i => $value)
            {
                if ($qtdColumns == 0)
                    echo '<div class="row">';

            ?>

                    <div class="col-md-3 col-lg-3 col-xl-3">
                        <section class="panel panel-featured-left panel-featured-primary">
                            <div class="panel-body">
                                <div class="widget-summary">
                                    <div class="widget-summary-col widget-summary-col-icon">
                                        <div class="summary-icon bg-quartenary">
                                            <i class="fa  fa-group"></i>
                                        </div>
                                    </div>
                                    <div class="widget-summary-col">
                                        <div class="summary">
                                            <h4 class="title"><?php echo (isset($value['nomeGrupo'])) ? $value['nomeGrupo'] : '' ; ?></h4>
                                            <div class="info">
                                                <strong class="amount"><?php echo (isset($value['valor'])) ? Utils::numberToMoney($value['valor']) : Utils::numberToMoney(0) ; ?></strong>

                                            </div>
                                        </div>
                                        <div class="summary-footer">
                                            <a class="text-muted text-uppercase"><?php echo (isset($value['valor'])) ? 'META R$ ' . Utils::numberToMoney($value['meta']) : 'META R$ ' . Utils::numberToMoney(0) ; ?></a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </section>
                    </div>

        <?php
                if ($qtdColumns == 4)
                    $qtdColumns = 0;

            }

        }

    ?>


    <?php
    if ( \Application::isAuthorized('Troca de Pontos' , 'loja_pontos', 'ler')
            ||  \Application::isAuthorized('Troca de Pontos' , 'loja_pontos', 'escrever'))
    { ?>

      <div class="col-md-3 col-lg-3 col-xl-3">
          <section class="panel panel-featured-left panel-featured-primary">
              <div class="panel-body">
                  <div class="widget-summary">
                      <div class="widget-summary-col widget-summary-col-icon">
                          <div class="summary-icon bg-quartenary">
                              <i class="fa  fa-forward"></i>
                          </div>
                      </div>
                      <div class="widget-summary-col">
                          <div class="summary">
                              <h4 class="title">Pontos Acumulados</h4>
                              <div class="info">
                                  <strong class="amount"><?php echo (isset($pontosTroca)) ?$pontosTroca : 0 ; ?></strong>

                              </div>
                          </div>
                          <div class="summary-footer">
                              <a class="text-muted text-uppercase" href="/lojapontos">Acessar loja</a>
                          </div>
                      </div>
                  </div>
              </div>
          </section>
      </div>

<?php    }

    ?>
    
    
  
<?php 
    if (! isset($descontosDevidos[0]))
        $valorDescontoUsuario = 0;
    else
    {
        $key = array_search('usuario', array_column($descontosDevidos, 'tipo'));
        
        if ($key !== false)
            $valorDescontoUsuario = $descontosDevidos[$key]['valor'];
        else
           $valorDescontoUsuario = 0; 
    }
    
?>
    
  <div class="col-md-3 col-lg-3 col-xl-3">
      <section class="panel panel-featured-left panel-featured-primary">
          <div class="panel-body">
              <div class="widget-summary">
                  <div class="widget-summary-col widget-summary-col-icon">
                      <div class="summary-icon bg-secondary">
                          <i class="fa fa-usd"></i>
                      </div>
                  </div>
                  <div class="widget-summary-col">
                      <div class="summary">
                          <h4 class="title">Total de Descontos</h4>
                          <div class="info">
                              <strong class="amount"><?php echo Utils::numberToMoney( ($valorDescontoUsuario * -1) ); ?></strong>

                          </div>
                      </div>
                      <div class="summary-footer">
                          PODE SER DESCONTADO PARCIALMENTE
                      </div>
                  </div>
              </div>
          </div>
      </section>
  </div>



    <?php
    if ( \Application::isAuthorized('Home' , 'paginainicial_descontos_todos', 'ler')
            ||  \Application::isAuthorized('Home' , 'paginainicial_descontos_todos', 'escrever'))
    { 
    
        if (! isset($descontosDevidos[0]))
            $valorDescontoUsuario = 0;
        else
        {
            $key = array_search('geral', array_column($descontosDevidos, 'tipo'));

            if ($key !== false)
                $valorDescontoUsuario = $descontosDevidos[$key]['valor'];
            else
               $valorDescontoUsuario = 0; 
        }
    ?>

      <div class="col-md-3 col-lg-3 col-xl-3">
          <section class="panel panel-featured-left panel-featured-primary">
              <div class="panel-body">
                  <div class="widget-summary">
                      <div class="widget-summary-col widget-summary-col-icon">
                          <div class="summary-icon bg-secondary">
                              <i class="fa fa-usd"></i>
                          </div>
                      </div>
                      <div class="widget-summary-col">
                          <div class="summary">
                              <h4 class="title">Total de Descontos Todos</h4>
                              <div class="info">
                                  <strong class="amount"><?php echo Utils::numberToMoney(($valorDescontoUsuario * -1) ); ?></strong>

                              </div>
                          </div>
                          <div class="summary-footer">
                              PODE SER DESCONTADO PARCIALMENTE
                          </div>
                      </div>
                  </div>
              </div>
          </section>
      </div>

<?php    }   ?>
    
    
    
<?php
    if ( \Application::isAuthorized('Home' , 'paginainicial_comissao_loja', 'ler')
            ||  \Application::isAuthorized('Home' , 'paginainicial_comissao_loja', 'escrever'))
    {   ?>
    
    <div class="col-md-3 col-lg-3 col-xl-3">
          <section class="panel panel-featured-left panel-featured-primary">
              <div class="panel-body">
                  <div class="widget-summary">
                      <div class="widget-summary-col widget-summary-col-icon">
                          <div class="summary-icon bg-secondary">
                              <i class="fa fa-usd"></i>
                          </div>
                      </div>
                      <div class="widget-summary-col">
                          <div class="summary">
                              <h4 class="title">Comissão Loja + Descontos (Mês)</h4>
                              <div class="info">
                                  <strong class="amount"><?php echo Utils::numberToMoney(($valorDescontoUsuario + $comissaoLoja) ); ?></strong>

                              </div>
                          </div>
                          <div class="summary-footer">
                              PODE SER DESCONTADO PARCIALMENTE
                          </div>
                      </div>
                  </div>
              </div>
          </section>
      </div>

<?php    }   ?>

</div>


<?php
$vlrRecebidoComissaoBanco = 0;
$vlrEmAnalise = 0;
$vlrEmAndamento = 0;
$vlrPagoVendedor = 0;
$vlrReprovado = 0;
$vlrPendente = 0;
if (is_array($valoresGerais))
    foreach($valoresGerais as $i => $value)
    {
        if (substr($value['dataPagamentoBanco'], 3 ) == date('m/Y')  )
            $vlrRecebidoComissaoBanco += $value['valorTotal'];
        else
            if ( (empty($value['dataPagamentoBanco'])  && substr($value['dataPagamento'], 3 ) == date('m/Y')) ||
                                 ! empty($value['dataPagamento']) && substr($value['dataPagamentoBanco'], 3 ) != date('m/Y')  )
                $vlrPagoVendedor += $value['valorTotal'];
            else
            {
                switch( strtolower($value['status'])  )
                {
                        case 'em andamento': $vlrEmAndamento += $value['valorTotal']; break;
                        case 'reprovado': $vlrReprovado += $value['valorTotal']; break;
                        case 'pendente': $vlrPendente += $value['valorTotal']; break;
                        case 'em análise': $vlrEmAnalise += $value['valorTotal']; break;
                }

            }


    }

?>

<div class="spoiler spoiler-default spoiler-state-collapsed" data-toggle-text="Contratos Recebido comissão do Banco - <b>TOTAL:</b> <i>R$  <?php echo Utils::numberToMoney($vlrRecebidoComissaoBanco) ?>	</i>" data-toggle-placement="top">
    <div class="spoiler-content">
            <table class="table table-bordered table-striped mb-none" id="datatable-editable">
            <thead>
                <tr>
                    <th>Numero</th>
                    <th>CPF</th>
                    <th>Nome</th>
                    <th>Banco</th>
                    <th>Valor da Parcela</th>
                    <th>Valor Completo</th>
                    <th>Valor liberado</th>
                    <th>Usuario</th>
                    <th>Editar</th>

                </tr>
            </thead>
            <tbody>
                <?php
                    if (is_array($valoresGerais))
                        foreach($valoresGerais as $i => $value)
                        {
                            if (substr($value['dataPagamentoBanco'], 3 ) == date('m/Y') )
                            {
                            ?>
                                    <tr class="gradeX">
                                        <td><?php echo $value['id'] ;?></td>
                                        <td><?php echo $value['cpf'] ;?></td>
                                        <td><?php echo $value['nomeCliente'] ;?></td>
                                        <td><?php echo $value['nomeBanco'] ;?></td>
                                        <td><?php echo Utils::numberToMoney($value['valorParcela']) ;?></td>
                                        <td><?php echo Utils::numberToMoney($value['valorTotal']) ;?></td>
                                        <td><?php echo Utils::numberToMoney($value['valorLiquido']) ;?></td>
                                        <td><?php echo $value['nomeUsuario'] ;?></td>
                                        <td class="actions">

                                            <a href="/contratos/cadastrar/<?php echo $value['id']; ?>" class="on-default edit-row"><i class="fa fa-pencil"></i></a>

                                        </td>
                                    </tr>
                   <?php     }
                         }
                ?>


            </tbody>
        </table>

    </div>
</div>

<div class="spoiler spoiler-Primary spoiler-state-collapsed" data-toggle-text="Contratos Pago - <b>TOTAL:</b><i>R$ <?php echo Utils::numberToMoney($vlrPagoVendedor) ?>	</i>" data-toggle-placement="top">
    <div class="spoiler-content">
        <table class="table table-bordered table-striped mb-none" id="datatable-editable">
            <thead>
                <tr>
                    <th>Numero</th>
                    <th>CPF</th>
                    <th>Nome</th>
                    <th>Banco</th>
                    <th>Valor da Parcela</th>
                    <th>Valor Completo</th>
                    <th>Valor liberado</th>
                    <th>Usuario</th>
                    <th>Editar</th>

                </tr>
            </thead>
            <tbody>
                <?php
                    if (is_array($valoresGerais))
                        foreach($valoresGerais as $i => $value)
                        {
                            if ( (empty($value['dataPagamentoBanco'])  && substr($value['dataPagamento'], 3 ) == date('m/Y')) ||
                                 ! empty($value['dataPagamento']) && substr($value['dataPagamentoBanco'], 3 ) != date('m/Y')
                               )
                            {
                            ?>
                                    <tr class="gradeX">
                                        <td><?php echo $value['id'] ;?></td>
                                        <td><?php echo $value['cpf'] ;?></td>
                                        <td><?php echo $value['nomeCliente'] ;?></td>
                                        <td><?php echo $value['nomeBanco'] ;?></td>
                                        <td><?php echo Utils::numberToMoney($value['valorParcela']) ;?></td>
                                        <td><?php echo Utils::numberToMoney($value['valorTotal']) ;?></td>
                                        <td><?php echo Utils::numberToMoney($value['valorLiquido']) ;?></td>
                                        <td><?php echo $value['nomeUsuario'] ;?></td>
                                        <td class="actions">

                                            <a href="/contratos/cadastrar/<?php echo $value['id']; ?>" class="on-default edit-row"><i class="fa fa-pencil"></i></a>

                                        </td>
                                    </tr>
                   <?php     }
                         }
                ?>


            </tbody>
        </table>


    </div>
</div>

<div class="spoiler spoiler-info spoiler-state-collapsed" data-toggle-text="Contratos em andamento - <b>TOTAL:</b><i>R$  <?php echo Utils::numberToMoney($vlrEmAndamento) ?>	</i> " data-toggle-placement="top">
	<div class="spoiler-content">
            <table class="table table-bordered table-striped mb-none" id="datatable-editable">
            <thead>
                <tr>
                    <th>Numero</th>
                    <th>CPF</th>
                    <th>Nome</th>
                    <th>Banco</th>
                    <th>Valor da Parcela</th>
                    <th>Valor Completo</th>
                    <th>Valor liberado</th>
                    <th>Usuario</th>
                    <th>Editar</th>

                </tr>
            </thead>
            <tbody>
                <?php
                    if (is_array($valoresGerais))
                        foreach($valoresGerais as $i => $value)
                        {
                            if ( (substr($value['dataPagamentoBanco'], 3 ) != date('m/Y')  && substr($value['dataPagamento'], 3 ) != date('m/Y')) &&
                                 strtolower($value['status']) == 'em andamento'
                               )
                            {
                            ?>
                                    <tr class="gradeX">
                                        <td><?php echo $value['id'] ;?></td>
                                        <td><?php echo $value['cpf'] ;?></td>
                                        <td><?php echo $value['nomeCliente'] ;?></td>
                                        <td><?php echo $value['nomeBanco'] ;?></td>
                                        <td><?php echo Utils::numberToMoney($value['valorParcela']) ;?></td>
                                        <td><?php echo Utils::numberToMoney($value['valorTotal']) ;?></td>
                                        <td><?php echo Utils::numberToMoney($value['valorLiquido']) ;?></td>
                                        <td><?php echo $value['nomeUsuario'] ;?></td>
                                        <td class="actions">

                                            <a href="/contratos/cadastrar/<?php echo $value['id']; ?>" class="on-default edit-row"><i class="fa fa-pencil"></i></a>

                                        </td>
                                    </tr>
                   <?php     }
                         }
                ?>


            </tbody>
        </table>

    </div>
  </div>


<div class="spoiler spoiler-warning spoiler-state-collapsed" data-toggle-text="Contratos Pendentes - <b>TOTAL:</b><i>R$ <?php echo Utils::numberToMoney($vlrPendente) ?></i>" data-toggle-placement="top">
    <div class="spoiler-content">
            <table class="table table-bordered table-striped mb-none" id="datatable-editable">
            <thead>
                <tr>
                    <th>Numero</th>
                    <th>CPF</th>
                    <th>Nome</th>
                    <th>Banco</th>
                    <th>Valor da Parcela</th>
                    <th>Valor Completo</th>
                    <th>Valor liberado</th>
                    <th>Usuario</th>
                    <th>Editar</th>

                </tr>
            </thead>
            <tbody>
                <?php
                    if (is_array($valoresGerais))
                        foreach($valoresGerais as $i => $value)
                        {
                            if ( (substr($value['dataPagamentoBanco'], 3 ) != date('m/Y')  && substr($value['dataPagamento'], 3 ) != date('m/Y')) &&
                                 strtolower($value['status']) == 'pendente'
                               )
                            {
                            ?>
                                    <tr class="gradeX">
                                        <td><?php echo $value['id'] ;?></td>
                                        <td><?php echo $value['cpf'] ;?></td>
                                        <td><?php echo $value['nomeCliente'] ;?></td>
                                        <td><?php echo $value['nomeBanco'] ;?></td>
                                        <td><?php echo Utils::numberToMoney($value['valorParcela']) ;?></td>
                                        <td><?php echo Utils::numberToMoney($value['valorTotal']) ;?></td>
                                        <td><?php echo Utils::numberToMoney($value['valorLiquido']) ;?></td>
                                        <td><?php echo $value['nomeUsuario'] ;?></td>
                                        <td class="actions">

                                            <a href="/contratos/cadastrar/<?php echo $value['id']; ?>" class="on-default edit-row"><i class="fa fa-pencil"></i></a>

                                        </td>
                                    </tr>
                   <?php     }
                         }
                ?>


            </tbody>
        </table>

    </div>
</div>

<div class="spoiler spoiler-info spoiler-state-collapsed" data-toggle-text="Contratos Em analise - <b>TOTAL:</b><i>R$ <?php echo Utils::numberToMoney($vlrEmAnalise) ?></i> " data-toggle-placement="top">
    <div class="spoiler-content">
            <table class="table table-bordered table-striped mb-none" id="datatable-editable">
            <thead>
                <tr>
                    <th>Numero</th>
                    <th>CPF</th>
                    <th>Nome</th>
                    <th>Banco</th>
                    <th>Valor da Parcela</th>
                    <th>Valor Completo</th>
                    <th>Valor liberado</th>
                    <th>Usuario</th>
                    <th>Editar</th>

                </tr>
            </thead>
            <tbody>
                <?php
                    if (is_array($valoresGerais))
                        foreach($valoresGerais as $i => $value)
                        {
                            if ( (substr($value['dataPagamentoBanco'], 3 ) != date('m/Y')  && substr($value['dataPagamento'], 3 ) != date('m/Y')) &&
                                 strtolower($value['status']) == 'em análise'
                               )
                            {
                            ?>
                                    <tr class="gradeX">
                                        <td><?php echo $value['id'] ;?></td>
                                        <td><?php echo $value['cpf'] ;?></td>
                                        <td><?php echo $value['nomeCliente'] ;?></td>
                                        <td><?php echo $value['nomeBanco'] ;?></td>
                                        <td><?php echo Utils::numberToMoney($value['valorParcela']) ;?></td>
                                        <td><?php echo Utils::numberToMoney($value['valorTotal']) ;?></td>
                                        <td><?php echo Utils::numberToMoney($value['valorLiquido']) ;?></td>
                                        <td><?php echo $value['nomeUsuario'] ;?></td>
                                        <td class="actions">

                                            <a href="/contratos/cadastrar/<?php echo $value['id']; ?>" class="on-default edit-row"><i class="fa fa-pencil"></i></a>

                                        </td>
                                    </tr>
                   <?php     }
                         }
                ?>


            </tbody>
        </table>
    </div>
 </div>

<div class="spoiler spoiler-danger spoiler-state-collapsed" data-toggle-text="Contratos Reprovados - <b>TOTAL:</b> <i>R$ <?php echo Utils::numberToMoney($vlrReprovado) ?></i>" data-toggle-placement="top">
    <div class="spoiler-content">
            <table class="table table-bordered table-striped mb-none" id="datatable-editable">
            <thead>
                <tr>
                    <th>Numero</th>
                    <th>CPF</th>
                    <th>Nome</th>
                    <th>Banco</th>
                    <th>Valor da Parcela</th>
                    <th>Valor Completo</th>
                    <th>Valor liberado</th>
                    <th>Usuario</th>
                    <th>Editar</th>

                </tr>
            </thead>
            <tbody>
                <?php
                    if (is_array($valoresGerais))
                        foreach($valoresGerais as $i => $value)
                        {
                            if ( (substr($value['dataPagamentoBanco'], 3 ) != date('m/Y')  && substr($value['dataPagamento'], 3 ) != date('m/Y')) &&
                                 strtolower($value['status']) == 'reprovado'
                               )
                            {
                            ?>
                                    <tr class="gradeX">
                                        <td><?php echo $value['id'] ;?></td>
                                        <td><?php echo $value['cpf'] ;?></td>
                                        <td><?php echo $value['nomeCliente'] ;?></td>
                                        <td><?php echo $value['nomeBanco'] ;?></td>
                                        <td><?php echo Utils::numberToMoney($value['valorParcela']) ;?></td>
                                        <td><?php echo Utils::numberToMoney($value['valorTotal']) ;?></td>
                                        <td><?php echo Utils::numberToMoney($value['valorLiquido']) ;?></td>
                                        <td><?php echo $value['nomeUsuario'] ;?></td>
                                        <td class="actions">

                                            <a href="/contratos/cadastrar/<?php echo $value['id']; ?>" class="on-default edit-row"><i class="fa fa-pencil"></i></a>

                                        </td>
                                    </tr>
                   <?php     }
                         }
                ?>


            </tbody>
        </table>
    </div>
</div>





<div class="row">
    <div class="col-md-12">
            <div class="panel-body">
    <h4 class="mb-md">Ultimas notícias</h4>
            <?php
                    if (\Application::isAuthorized(ucfirst(strtolower(\Application::getNameController())) , 'pagina_inicial_publicar_noticias', 'escrever') )
                    { ?>
                        <section class="simple-compose-box mb-xlg">

                                    <form id="formnoticia" >
                                        <textarea name="noticia" data-plugin-textarea-autosize placeholder="Digite aqui" rows="1"></textarea>
                                    </form>

                            <div class="compose-box-footer">
                                <ul class="compose-toolbar">
                                    <li>
                                        <a href="#"><i class="fa fa-camera"></i></a>
                                    </li>

                                </ul>
                                <ul class="compose-btn">
                                    <li>
                                        <a class="btn bg-primary btn-xs" onclick="gravarNoticias()">Publicar</a>
                                    </li>
                                </ul>
                            </div>
                        </section>
            <?php  } ?>
               <!-- <h4 class="mb-xlg">Linha do Tempo</h4> -->

                <?php
                if (is_array($noticias) && isset($noticias[0]))
                {
                    $data = $noticias[0]['created'];
                    echo '
                        <div class="timeline timeline-simple mt-xlg mb-md">
                            <div class="tm-body">
                                <div class="tm-title">
                                    <h3 class="h5 text-uppercase">'. $data .'</h3>
                                </div>
                            <ol class="tm-items">
                    ';
                    //var_dump($noticias); exit;
                    $count = 0;
                     foreach($noticias as $i => $value)
                     {
                         if ($data != $value['created'] && $count == 0 )
                         {
                             echo '
                                <div class="timeline timeline-simple mt-xlg mb-md">
                                <div class="tm-body">
                                    <div class="tm-title">
                                        <h3 class="h5 text-uppercase">'. $data .'</h3>
                                    </div>
                                <ol class="tm-items">
                             ';
                         }else
                         {
                             echo '
                                <li>
                                    <div class="tm-box">
                                        <p class="text-muted mb-none">'. $data .'</p>
                                        <p>
                                            '. $value['noticia'] .'
                                        </p>
                                    </div>
                                </li>
                             ';
                         }
                         $count++;
                     }

                    echo '
                                </ol>
                            </div>
                        </div>
                    ';

                }

                ?>







        </div>
    </div>
</div>











<!-- Botão de ligar abre pagina do agendamento do cliente e o de reagendar agenda cliente para 5 dias no mesmo horario -->
<div class="row">
    <div class="col-md-12">
        <div class="panel-body">
            <h4>Ligações a fazer </h4>
            <div class="table-responsive">
                <table class="table table-striped mb-none">
                    <thead>
                        <tr>
                            <th>Hora</th>
                            <th>Nome</th>
                            <th>CPF</th>
                            <th>Ligar</th>
                            <th>Reagendar</th>
                        </tr>
                    </thead>
                    <tbody>
                <?php
                    if (is_array($agenda))
                        foreach($agenda as $i => $value)
                        { ?>
                        <tr>
                            <td><?php echo Utils::formatStringDate($value['dataLigacao'], 'd/m/Y H:i:s', 'H:i:s'); ?></td>
                            <td><?php echo $value['nomeCliente']; ?></td>
                            <td><?php echo $value['cpfCliente']; ?></td>
                            <td><button type="button" class="btn btn-success mr-xs mb-sm" onclick="document.location='/cliente/cadastrar-agenda/<?php echo $value['id']; ?>'">Ligar</button></td>
                            <td><button type="button" class="btn btn-info mr-xs mb-sm" onclick="reagendarLigacao('<?php echo $value['id']; ?>','<?php echo $value['nomeCliente']; ?>')">Reagendar</button></td>

                        </tr>
                  <?php } ?>



                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<section class="panel">
    <div class="panel-body">
        <div class="table-responsive">
            <table class="table table-striped mb-none">
                <thead>
                    <tr>
                    <h4>Aniversariantes do dia</h4>
                        <th>Nome</th>
                        <th>CPF</th>
                        <th>Usuario que cadastrou</th>
                        <th>Ligar</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                        if (is_array($aniversariantes))
                            foreach($aniversariantes as $i => $value)
                            { ?>
                                     <tr>
                                            <td><?php echo $value['nomeCliente']; ?></td>
                                            <td><?php echo $value['cpf']; ?></td>
                                            <td><?php echo $value['nomeUsuario']; ?></td>
                                            <td><button type="button" class="btn btn-success mr-xs mb-sm" onclick="document.location='/cliente/cadastrar/<?php echo preg_replace('/[\.-]/','', $value['cpf']); ?>'">Ligar</button></td>
                                     </tr>
                <?php }     ?>

                </tbody>
            </table>
        </div>
    </div>
</section>

<hr />




<script src="/library/jsvendor/bootstrap-multiselect/bootstrap-multiselect.js"></script>
<script src="/library/jsvendor/flot/jquery.flot.min.js"></script>
<script src="/library/jsvendor/flot-tooltip/jquery.flot.tooltip.js"></script>
<script src="/library/jsvendor/flot/jquery.flot.pie.min.js"></script>
<script src="/library/jsvendor/flot/jquery.flot.categories.min.js"></script>
<script src="/library/jsvendor/flot/jquery.flot.resize.min.js"></script>
<script src="/library/jsvendor/jquery-browser-mobile/jquery.browser.mobile.js"></script>
<script src="/library/jsvendor/liquid-meter/liquid.meter.js"></script>

<script src="/library/jsvendor/spoiler/jquery.spoiler.min.js"></script>

<script src="/library/jsvendor/raphael/raphael.js"></script>
<script src="/library/jsvendor/snap-svg/snap.svg.js"></script>

<script src="/library/javascript/home/index.js?<?php echo time(); ?>"></script>


<script>document.documentElement.className += ' spoiler-js';</script>


<script>



function gravarNoticias()
{
    if (! confirm('Deseja cadastrar a notícia?'))
        return false;


    $.ajax({
        type: "POST",
        url:  '/home/salvar-noticia/',
        cache: false,
        dataType: "json",
        data: $("#formnoticia").serialize() ,
        success: function(json){
            if (json.success)
             {
                 alert('Registro salvo com sucesso');
                 if (json.success == true)
                    document.location = '/home';
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
