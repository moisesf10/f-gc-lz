<!DOCTYPE html>
<html class="header-dark">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Ga&uacute;cha Cred - Promotora</title>
    
<link rel="stylesheet" type="text/css" href="/library/jsvendor/bootstrap/css/bootstrap.min.css">
<link rel="stylesheet" type="text/css" href="/library/jsvendor/nanoscroller/nanoscroller.css">
    
<link rel="stylesheet" type="text/css" href="/library/css/invoice-print.css">
<link rel="stylesheet" type="text/css" href="/library/css/theme.css">
<link rel="stylesheet" type="text/css" href="/library/css/theme-custom.css">


    
<?php 
        if (  isset($this) &&     count($this->getCss()) > 0)
            foreach($this->getCss() as $i => $value)
            {
                echo '<link rel="stylesheet" href="'.  \Application::getUrlSite(). '/library/css/' . $value . '.css' .'"/>';
            }
    
        // ADICIONA JS
    
        if (  isset($this) &&     count($this->getJs()) > 0)
            foreach($this->getJs() as $i => $value)
            {
                echo '<script src="'.  \Application::getUrlSite(). '/library/javascript/' . $value . '.js' .'"/>';
            }
?>    
    

<style>
    html, body{background-color: #FFF;}   
    .panel-body {border: 1px solid #c8cbca; background-color: #fdfdfd !important;}
    
    .alert-danger {
        margin-bottom: 1rem;
        <?php
            $displayError = (! isset($_SESSION['failedautenticate']) || $_SESSION['failedautenticate'] == false ) ? 'none' : 'block';
        ?>
        display: <?php echo $displayError; ?>;
    }
    
    .form-control:focus{border-color: #e03a3a;  box-shadow: none; -webkit-box-shadow: none;} 
    .has-error .form-control:focus{box-shadow: none; -webkit-box-shadow: none;}
</style>

</head>
<body>

<!-- start: page -->
		<section class="body-sign">
			<div class="center-sign">
                <div class="text-center alert alert-danger" role="alert">Usuário ou senha inválido</div>
				<a href="/" class="logo pull-left">
					<img src="/images/logo609x336.png" height="54" alt="Ga&uacute;cha Cred" />
				</a>

				<div class="panel panel-sign">
					<div class="panel-title-sign mt-xl text-right">
						<h2 class="title text-uppercase text-bold m-none"><i class="fa fa-user mr-xs"></i> Acessar Sistema</h2>
					</div>
					<div class="panel-body">
						<form action="<?php echo \Application::getUrlSite(). '/controle-acesso/autenticar'; ?>" method="post">
							<div class="form-group mb-lg">
								<label>Usu&aacute;rio</label>
								<div class="input-group input-group-icon">
									<input name="login" type="text" class="form-control input-lg" placeholder="CPF ou E-mail" />
									<span class="input-group-addon">
										<span class="icon icon-lg">
											<i class="fa fa-user"></i>
										</span>
									</span>
								</div>
							</div>

							<div class="form-group mb-lg">
								<div class="clearfix">
									<label class="pull-left">Senha</label>
									<a href="pages-recover-password.html" class="pull-right">Esqueci minha senha</a>
								</div>
								<div class="input-group input-group-icon">
									<input name="password" type="password" class="form-control input-lg" />
									<span class="input-group-addon">
										<span class="icon icon-lg">
											<i class="fa fa-lock"></i>
										</span>
									</span>
								</div>
							</div>

							<div class="row">
								<div class="col-sm-8">
									<div class="checkbox-custom checkbox-default">
										<input id="RememberMe" name="rememberme" type="checkbox"/>
										<label for="RememberMe">Remember Me</label>
									</div>
								</div>
								<div class="col-sm-4 text-right">
									<button type="submit" class="btn btn-primary hidden-xs">Entrar</button>
									<button type="submit" class="btn btn-send btn-block btn-lg visible-xs mt-lg">Entrar</button>
								</div>
							</div>

													

						</form>
					</div>
				</div>

				<p class="text-center text-muted mt-md mb-md">&copy; Copyright <?php echo date('Y'); ?>. Propriedade da Ga&uacute;cha Cred.</p>
			</div>
		</section>
		<!-- end: page -->

    
    
    
<script src="/library/javascript/jquery.1.11.2.min.js"></script>
<script src="/library/jsvendor/bootstrap/js/bootstrap.min.js"></script>
<script src="/library/jsvendor/modernizr/modernizr.js"></script>
<script src="/library/jsvendor/nanoscroller/nanoscroller.js"></script>
    
<script src="/library/javascript/theme.js"></script>
<script src="/library/javascript/theme.init.js"></script>
<script src="/library/javascript/theme.custom.js"></script>
<script src="/library/javascript/index/index.js"></script>
    
</body>
</html>
