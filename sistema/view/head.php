<!DOCTYPE html>
<html class="header-dark">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Ga&uacute;cha Cred - Promotora</title>
 
<link rel="stylesheet" type="text/css" href="/library/jsvendor/bootstrap/css/bootstrap.min.css">
<link rel="stylesheet" type="text/css" href="/library/jsvendor/nanoscroller/nanoscroller.css">
<link href="https://fonts.googleapis.com/icon?family=Material+Icons"
      rel="stylesheet">
<link rel="stylesheet" href="/library/jsvendor/font-awesome/css/font-awesome.min.css" />
<link rel="shortcut icon" href="/images/favicon.ico" /> 
    
<link rel="stylesheet" type="text/css" href="/library/css/invoice-print.css">
<link rel="stylesheet" type="text/css" href="/library/css/theme.css">
<link rel="stylesheet" type="text/css" href="/library/css/theme-custom.css">

<link href="/library/jsvendor/bootsnav/css/bootsnav.css" rel="stylesheet">
    <link href="/library/jsvendor/bootsnav/css/overwrite.css" rel="stylesheet">
    
    <script src="/library/javascript/jquery.1.11.2.min.js"></script>
    <script src="/library/javascript/theme.custom.js"></script>

<?php 
        if (  isset($this) &&     count($this->getCss()) > 0)
            foreach($this->getCss() as $i => $value)
            {
                echo '<link rel="stylesheet" href="'.  $value . '"/>';
            }
    
        // ADICIONA JS
    
        if (  isset($this) &&     count($this->getJs()) > 0)
            foreach($this->getJs() as $i => $value)
            {
                echo '<script src="'.  $value .'"/>';
            }
?>    
<style>
    #userbox {margin-top: 2rem;}
    .logo {width: 10%; margin-top: 1rem; margin-left: 1rem; }
    .header-right{margin-top: 1rem;}
    .attr-nav {margin-right: 1rem;}
    .page-header {margin: 0;}
    section#pagina {margin: 3rem 1rem 0 1rem;}
    div.widget li{padding-bottom: 0.4rem;}
    div.widget li:last-child{padding-bottom: 1.2rem;}
    .row {overflow-x: hidden;}
</style>
</head>
<body>
    
   <nav class="navbar navbar-inverse bootsnav  ">
    <div class="">  
        <!-- Start Atribute Navigation -->
                    <div class="attr-nav navbar-left">
                        <ul>
                            <li class="side-menu"><a href="#"><i class="fa fa-bars">&nbsp;MENU</i></a></li>
                        </ul>
                    </div>        
                    <!-- End Atribute Navigation -->
       
        <a class="" href="<?php echo \Application::getUrlSite(); ?>"><img src="/images/logo2-609x336.png" class="logo" alt=""></a>
        
    
        <div class="header-right">
					<div id="userbox" class="userbox">
						<a href="#" data-toggle="dropdown" aria-expanded="false">
							<figure class="profile-picture">
								<img src="/images/!logged-user.jpg" alt="Joseph Doe" class="img-circle" data-lock-picture="/images/!logged-user.jpg">
							</figure>
							<div class="profile-info" data-lock-name="<?php echo $_SESSION['nome']; ?>" data-lock-email="<?php echo $_SESSION['email']; ?>">
								<span class="name"><?php echo $_SESSION['nome']; ?></span>
								<span class="role">seja bem vindo(a)</span>
							</div>
			
							<i class="fa custom-caret"></i>
						</a>
			
						<div class="dropdown-menu">
							<ul class="list-unstyled">
								<li class="divider"></li>
								<li>
									<a role="menuitem" tabindex="-1" href="/usuarios/alterar-senha"><i class="fa fa-user"></i> Alterar Senha</a>
								</li>
								<li>
									<a role="menuitem" tabindex="-1" href="/controle-acesso/logout"><i class="fa fa-power-off"></i> Sair do Sistema</a>
								</li>
							</ul>
						</div>
					</div>
        </div>
        
    </div>   
       
       
    
    
    <!-- Start Side Menu -->
    <div class="side">
        <a href="#" class="close-side"><i class="fa fa-times"></i></a>
        <div class="widget link">
            <a href="<?php echo \Application::getUrlSite(); ?>"><i class="fa fa-home" aria-hidden="true"></i>&nbsp;Home</a>
        </div>
        <div class="widget">
            <?php
                $permissions = \Application::getPermissions();
               
                if (is_array($permissions))
                    foreach($permissions as $i => $value)
                    {
                        $pos = 1;
                        $closeUl = false;
                        if (is_array($value) && count($value) > 0)
                        {
                           
                            foreach($value as $d => $permission)
                            {
                               //echo '<pre>'; var_dump($permission); echo '</pre>';
                                if ($pos == 1 && ($permission['ler'] || $permission['escrever']  ) && $permission['indicaMenu'] == true  )
                                {
                                     echo '<h6 class="title">'. htmlentities( ucfirst(strtolower($permission['nomeGrupoRecurso'])) ) .'</h6>' . PHP_EOL;
                                        echo '<ul class="link">' . PHP_EOL;
                                    $closeUl = true;
                                    $pos++;
                                }
                                if (($permission['ler'] || $permission['escrever']) && $permission['indicaMenu'] === true )
                                    echo '<li><a href="'. $permission['pagina'] .'">'. $permission['tagIcon'] . '&nbsp;'. htmlentities($permission['nomeMenu'])  .'</a></li>' . PHP_EOL;
                                
                            }
                            if ($closeUl)
                                echo '</ul>';
                        }
                    }
            ?>
        </div>

    </div>
    <!-- End Side Menu -->

</nav>
    
<div class="clearfix"></div>
    
    <header class="page-header">
  
        <div class="right-wrapper pull-left">
            <ol class="breadcrumbs">
                <li>
                    <a href="<?php echo \Application::getUrlSite(); ?>">
                        <i class="fa fa-home"></i>
                    </a>
                </li>
                <?php
                    $controller = \Application::getNameController();
                    echo "<li><span>{$controller}</span></li>";
                   $action = ucfirst(\Application::getNameAction());
                
                    if (strtolower($action) != 'actiondefault')
                        echo "<li><span>{$action}</span></li>";
                  
                
                ?>
                
               
            </ol>

            
        </div>
    </header>
    
     
        <section id="pagina">
            