<?php
$produtos = $this->getParams('produtos');

?>
<!DOCTYPE html>
<html>
	<head>

		<!-- Basic -->
		<meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">

		<title>Gaúcha Cred | Troca de pontos</title>

		<meta name="keywords" content="HTML5 Template" />
		<meta name="description" content="Porto - Responsive HTML5 Template">
		<meta name="author" content="okler.net">

		<!-- Favicon -->
		<link rel="shortcut icon" href="img/favicon.ico" type="image/x-icon" />
		<link rel="apple-touch-icon" href="img/apple-touch-icon.png">

		<!-- Mobile Metas -->
		<meta name="viewport" content="width=device-width, minimum-scale=1.0, maximum-scale=1.0, user-scalable=no">

		<!-- Web Fonts  -->
		<link href="http://fonts.googleapis.com/css?family=Open+Sans:300,400,600,700,800%7CShadows+Into+Light" rel="stylesheet" type="text/css">

		<!-- Vendor CSS -->
		<link rel="stylesheet" type="text/css" href="/library/jsvendor/bootstrap/css/bootstrap.min.css">
		<link rel="stylesheet" href="/library/jsvendor/font-awesome/css/font-awesome.min.css" />
		<link rel="stylesheet" href="/library/jsvendor/simple-line-icons/css/simple-line-icons.css">
		<link rel="stylesheet" href="/library/jsvendor/owl.carousel/assets/owl.carousel.min.css">
		<link rel="stylesheet" href="/library/jsvendor/owl.carousel/assets/owl.theme.default.min.css">
		<link rel="stylesheet" href="/library/jsvendor/magnific-popup/magnific-popup.css">

		<!-- Theme CSS -->
		<link rel="stylesheet" href="/library/css/theme-loja/theme.css">
		<link rel="stylesheet" href="/library/css/theme-loja/theme-elements.css">
		<link rel="stylesheet" href="/library/css/theme-loja/theme-blog.css">
		<link rel="stylesheet" href="/library/css/theme-loja/theme-shop.css">
		<link rel="stylesheet" href="/library/css/theme-loja/theme-animate.css">

		<!-- Skin CSS -->
		<link rel="stylesheet" href="/library/css/theme-loja/skins/default.css">

		<!-- Theme Custom CSS -->
		<link rel="stylesheet" href="/library/css/theme-loja/custom.css">

		<!-- Head Libs -->
		<script src="/library/jsvendor/modernizr/modernizr.js"></script>

	</head>
	<body>
    <a href="/">Voltar para o sistema</a>
		<center><img  src="/images/logo609x336.png" alt="" width="234" height="129" />

			<div role="main" class="main shop">

				<div class="container">

					<div class="row">
						<div class="col-md-12">
							<hr class="tall">
						</div>
					</div>

					<div class="row">
						<div class="col-md-12">
							<center><h1 class="mb-none"><strong>Troque seus pontos</strong></h1>
							<p>Por produtos </p></center>
						</div>
					</div>

					<div class="row">

						<ul class="products product-thumb-info-list" data-plugin-masonry>
              <?php
                if (is_array($produtos))
                  foreach ($produtos as $produto) { ?>

      							<li class="col-md-3 col-sm-6 col-xs-12 product">
      								<a href="/lojapontos/visualizar-produto/<?php echo $produto['id']; ?>">
      									<span class="onsale">Novo!</span>
      								</a>
      								<span class="product-thumb-info">
      									<a href="/lojapontos/visualizar-produto/<?php echo $produto['id']; ?>" class="add-to-cart-product">
      										<span><i class="fa fa-shopping-cart"></i>Trocar os pontos</span>
      									</a>
      									<a href="/lojapontos/visualizar-produto/<?php echo $produto['id']; ?>">
      										<span class="product-thumb-info-image">
      											<span class="product-thumb-info-act">
      												<span class="product-thumb-info-act-left"><em>Olhar</em></span>
      												<span class="product-thumb-info-act-right"><em><i class="fa fa-plus"></i> Detalhes</em></span>
      											</span>
      											<img alt="" class="img-responsive" src="<?php echo $produto['link']; ?>">
      										</span>
      									</a>
      									<span class="product-thumb-info-content">
      										<a href="/lojapontos/visualizar-produto/<?php echo $produto['id']; ?>">
      											<h4><?php echo ucwords(strtolower($produto['nome'])) . ' - ' . $produto['pontos']; ?> pontos</h4>
      											<span class="price">
      												<ins><span class="amount">Requer <?php echo $produto['pontos']; ?> pontos</span></ins>
      											</span>
      										</a>
      									</span>
      								</span>
      							</li>
              <?php } ?>
						</ul>

					</div>


				</div>

			</div>

			<footer id="footer">
				<div class="container">
					<div class="row">
						<div class="footer-ribbon">
							<span>Regulamento</span>
						</div>
						<div class="col-md-12">
							<div class="newsletter">
								<h4>
								Regras da Campanha:</h4>

Cada 1000 reais em vendas vale 1 ponto.

O participante só poder trocar pontos após R$50.000,00 em vendas SABEMI.

É permitido apenas  a troca de 3 produtos por mês.

Os pontos tem período de vigência de 12 meses, após  isso os pontos expiram.

O prazo de entrega dos produtos é de 30 dias após o pedido no site, a entrega vai ser diretamente na loja da Gaúcha Cred.

O

							</div>
						</div>


		<!-- Vendor -->
		<script src="/library/javascript/jquery.1.11.2.min.js"></script>
		<script src="/library/jsvendor/jquery.appear/jquery.appear.js"></script>
		<script src="/library/jsvendor/jquery.easing/jquery.easing.js"></script>
    <!--
		<script src="/library/jsvendor/jquery-cookie/jquery-cookie.js"></script>
		<script src="/library/jsvendor/bootstrap/js/bootstrap.js"></script>
		<script src="/library/jsvendor/common/common.js"></script>
		<script src="/library/jsvendor/jquery.validation/jquery.validation.js"></script>
		<script src="/library/jsvendor/jquery.stellar/jquery.stellar.js"></script>
		<script src="/library/jsvendor/jquery.easy-pie-chart/jquery.easy-pie-chart.js"></script>
		<script src="/library/jsvendor/jquery.gmap/jquery.gmap.js"></script>
		<script src="/library/jsvendor/jquery.lazyload/jquery.lazyload.js"></script>
		<script src="/library/jsvendor/isotope/jquery.isotope.js"></script>
		<script src="/library/jsvendor/owl.carousel/owl.carousel.js"></script>
		<script src="/library/jsvendor/magnific-popup/jquery.magnific-popup.js"></script>
		<script src="/library/jsvendor/vide/vide.js"></script>
  -->
		<!-- Theme Base, Components and Settings -->
		<script src="/library/javascript/theme-loja/theme.js"></script>

		<!-- Theme Custom -->
		<script src="/library/javascript/theme-loja/custom.js"></script>

		<!-- Theme Initialization Files -->
		<script src="/library/javascript/theme-loja/theme.init.js"></script>

		<!-- Google Analytics: Change UA-XXXXX-X to be your site's ID. Go to http://www.google.com/analytics/ for more information.
		<script>
			(function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
			(i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
			m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
			})(window,document,'script','//www.google-analytics.com/analytics.js','ga');

			ga('create', 'UA-12345678-1', 'auto');
			ga('send', 'pageview');
		</script>
		 -->

	</body>
</html>
