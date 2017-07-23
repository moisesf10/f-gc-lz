<?php
use Gauchacred\library\php\Utils as Utils;
$tabelas = $this->getParams('tabelas');
$gruposDoUsuario = $this->getParams('gruposdousuario');
//echo '<pre>'; print_r($gruposDoUsuario); exit;


// verifica em quais tabelas o grupo do usuário está incluso


$usuarioPertence = array();
if (is_array($tabelas) && is_array($gruposDoUsuario))
    foreach($tabelas as $i => $value)
    {
        foreach($gruposDoUsuario as $g => $grupo)
        {
            $key = array_search($grupo['id'], array_column($value['comissoes'], 'idGrupo'));
            if ($key !== false)
                array_push($usuarioPertence, array(
                    'idSubtabela' => $value['id'], 
                    'idGrupo' => $value['comissoes'][$key]['idGrupo'], 
                    'percentual' => $value['comissoes'][$key]['comissao']
                ));
            
        }
        
    }
//echo '<pre>'; print_r($usuarioPertence); exit;

$tree = array();

// Prepara os bancos como key do array
if (is_array($tabelas))
{
        // define nome banco
                foreach($tabelas as $i => $value)
                {
                    $inicioVigencia = Utils::formatStringDate($value['inicioVigencia'], 'd/m/Y', 'Y-m-d');
                    $fimVigencia = Utils::formatStringDate($value['fimVigencia'], 'd/m/Y', 'Y-m-d');
                    $hoje = date('Y-m-d');
                    $valido = true;
                    if (strtotime($hoje) < strtotime($inicioVigencia)   || strtotime($hoje) > strtotime($fimVigencia)    )
                        $valido = false;
                    if (! array_key_exists($value['nomeBanco'], $tree)   && $valido )
                        $tree[$value['nomeBanco']] = array();
                }

           // define nome Convenio

                foreach($tree as $i => $banco)
                    foreach($tabelas as $a => $value)
                    {
                        $inicioVigencia = Utils::formatStringDate($value['inicioVigencia'], 'd/m/Y', 'Y-m-d');
                        $fimVigencia = Utils::formatStringDate($value['fimVigencia'], 'd/m/Y', 'Y-m-d');
                        $hoje = date('Y-m-d');
                        $valido = true;
                        
                        if ($value['nomeBanco'] == $i )
                            if (! array_key_exists($value['nomeConvenio'], $tree[$i]) && $valido )
                                $tree[$i][$value['nomeConvenio']] = array();
                    }
    
        ;
    
            // define a Operação
            
               foreach($tree as $i => $banco)
                    foreach($banco as $a => $convenio)
                        foreach($tabelas as $t => $tab)
                        {
                            $inicioVigencia = Utils::formatStringDate($tab['inicioVigencia'], 'd/m/Y', 'Y-m-d');
                            $fimVigencia = Utils::formatStringDate($tab['fimVigencia'], 'd/m/Y', 'Y-m-d');
                            $hoje = date('Y-m-d');
                            $valido = true;
                             if (   $tab['nomeBanco'] == $i && $tab['nomeConvenio'] == $a &&  ! array_key_exists($tab['nomeOperacao'], $tree[$i][$a] )  && $valido   )
                                 $tree[$i][$a][$tab['nomeOperacao']] = array();
                        }

          
    
                // Define nome Tabela

                foreach($tree as $i => $banco)
                    foreach($banco as $a => $convenio)
                        foreach($convenio as $o => $operacao)
                            foreach($tabelas as $t => $tab)
                            {
                                    $inicioVigencia = Utils::formatStringDate($tab['inicioVigencia'], 'd/m/Y', 'Y-m-d');
                                    $fimVigencia = Utils::formatStringDate($tab['fimVigencia'], 'd/m/Y', 'Y-m-d');
                                    $hoje = date('Y-m-d');
                                    $valido = true;
                                     if (   $tab['nomeBanco'] == $i && $tab['nomeConvenio'] == $a  && $tab['nomeOperacao'] == $o &&  ! array_key_exists($tab['nomeOperacao'], $tree[$i][$a][$o] )  && $valido   )
                                         $tree[$i][$a][$o][$tab['nomeTabela']] = array();
                            }

      //echo '<pre>';print_r($tree); exit;
                foreach($tabelas as $t => $value)
                {

                    if (is_array($value['prazos']) )
                    {
                        foreach($value['prazos'] as $p => $prazo)
                        {
                            $key =  array_search($value['id'], array_column($usuarioPertence, 'idSubtabela'));
                            if ($key !== false)
                            {
                                $p = null;
                                $p = $prazo;
                                $p['comissaoTotal'] = $usuarioPertence[$key]['percentual'];
                                if (isset($tree[$value['nomeBanco']][$value['nomeConvenio']][$value['nomeOperacao']][$value['nomeTabela']]) )
                                    array_push( $tree[$value['nomeBanco']][$value['nomeConvenio']][$value['nomeOperacao']][$value['nomeTabela']] , $p);
                            }else
                            {
                                $p = null;
                                $p = $prazo;
                                $p['comissaoTotal'] = '0.00';
                                if (isset($tree[$value['nomeBanco']][$value['nomeConvenio']][$value['nomeOperacao']][$value['nomeTabela']]) )
                                    array_push( $tree[$value['nomeBanco']][$value['nomeConvenio']][$value['nomeOperacao']][$value['nomeTabela']] , $p);
                            }
                            
                        }
                    }
                    //$tree[$value['nomeBanco']][$value['nomeConvenio']][$value['nomeTabela']]  = $value['prazos'];
                }
    
}

  // echo '<pre>'; print_r($tree); echo '</pre>';

?>

<style>
    .tree-tittle {display: inline-block; padding: 5px;  margin-left: 5px; min-width: 8rem; background-color: #d1d1d1;}
    .tree-content {display: inline-block; padding: 5px; margin-left: 5px; min-width: 8rem;text-align: center;}
</style>



<div class="row">
    <div class="col-md-12">
        <div id="tree"></div>
    </div>
</div>


<script>


    $.getScript('/library/jsvendor/bootstrap-treeview/dist/bootstrap-treeview.min.js', function(){
            $('#tree').treeview({
                data: getTree(),
                showBorder: false,
                state: {
                        expanded: false,
            
                      },
            });   
    });
 
    
    
function getTree()
{
    <?php
        if (! is_array($tree) || count($tree) == 0)
            echo 'var tree = []';
        else
        {
            $aux = 'var tree = [';
            
            foreach($tree as $a => $banco)
            {
                $aux .= '{text: "'. $a . '", nodes: [';
                if (is_array($banco))
                    foreach($banco as $b => $operacao )
                    {
                        $aux .= '{text: "'. $b .'", nodes: [';
                        if (is_array($operacao))
                            foreach($operacao as $c => $convenio)
                            {
                                $aux .= '{text: "'. $c .'", nodes: [';
                                        if (is_array($convenio))
                                                foreach($convenio as $c => $tab)
                                                {
                                                    $aux .= '{text: "'. $c . '", nodes: [{text: "<label class=\"tree-tittle\">Prazo</label><label class=\"tree-tittle\">Coeficiente</label><label class=\"tree-tittle\">Comissao</label>"},';
                                                    if (is_array($tab) && count($tab) > 0)
                                                        foreach($tab as $d => $value)
                                                            $aux .= '{text: " <label class=\"tree-content\"> '. $value['prazo']   .'x</label><label class=\"tree-content\">'. $value['coeficiente']. '</label><label class=\"tree-content\">'. $value['comissaoTotal']. '</label>"},';
                                                    $aux .= ']},';
                                                }
                                $aux .= ']},';
                            }
                        $aux .= ']},';
                    }
                $aux .= ']},';
            }
            $aux .= ']';
            
            echo $aux;
        }
    ?>
    
    /*var tree = [
      {
        text: "Parent 1",
        nodes: [
                  {
                        text: "Child 1",
                        nodes: [
                          {
                            text: "Grandchild 1"
                          },
                          {
                            text: "Grandchild 2"
                          }
                        ]
                  },
                  {
                    text: "Child 2"
                  }
        ]
      },
      {
        text: "Parent 2"
      },
      {
        text: "Parent 3"
      },
      {
        text: "Parent 4"
      },
      {
        text: "Parent 5"
      }
    ];*/
    
    return tree;
}
    
</script>