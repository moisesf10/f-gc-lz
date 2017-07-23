<?php
$produto = $this->getParams('produto');
$pontos = $this->getParams('pontos');
$avaliacoes = $this->getParams('avaliacoes');
?>
<!DOCTYPE html>
<html lang="pt-BR" class="no-js no-svg">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">

<script>(function(html){html.className = html.className.replace(/\bno-js\b/,'js')})(document.documentElement);</script>
<title><?php if (isset($produto['nome'])) echo $produto['nome'] . ' &#8211; ' . $produto['pontos'] . ' pontos &#8211; '; ?>Gaúcha Cred | Loja de Pontos</title>
<link rel='dns-prefetch' href='//fonts.googleapis.com' />
<link rel='dns-prefetch' href='//s.w.org' />
<link href='https://fonts.gstatic.com' crossorigin rel='preconnect' />


		<style type="text/css">
img.wp-smiley,
img.emoji {
	display: inline !important;
	border: none !important;
	box-shadow: none !important;
	height: 1em !important;
	width: 1em !important;
	margin: 0 .07em !important;
	vertical-align: -0.1em !important;
	background: none !important;
	padding: 0 !important;
}
.pontos-disponiveis{color: #FF0000;}
.comentarios div.row {padding-bottom: 1rem;}
.comentarios span {font-size: 0.8rem;}
.comentarios-usuario {font-weight: bold; font-size: 0.8rem;}
</style>

<link rel='stylesheet' id='woocommerce-layout-css'  href='/library/jsvendor/woocomerce/woocommerce-layout.css?ver=3.0.7' type='text/css' media='all' />
<link rel='stylesheet' id='woocommerce-smallscreen-css'  href='/library/jsvendor/woocomerce/woocommerce-smallscreen.css?ver=3.0.7' type='text/css' media='only screen and (max-width: 768px)' />
<link rel='stylesheet' id='woocommerce-twenty-seventeen-css'  href='/library/jsvendor/woocomerce/twenty-seventeen.css?ver=3.0.7' type='text/css' media='all' />
<link rel='stylesheet' id='twentyseventeen-fonts-css'  href='https://fonts.googleapis.com/css?family=Libre+Franklin%3A300%2C300i%2C400%2C400i%2C600%2C600i%2C800%2C800i&#038;subset=latin%2Clatin-ext' type='text/css' media='all' />
<link rel='stylesheet' id='twentyseventeen-style-css'  href='/library/jsvendor/woocomerce//twentyseventeen-style.css?ver=4.7.5' type='text/css' media='all' />
<link rel='stylesheet' href="/library/jsvendor/magnific-popup/magnific-popup.css" type="text/css" />
	<script src="/library/javascript/jquery.1.11.2.min.js"></script>




	<noscript><style>.woocommerce-product-gallery{ opacity: 1 !important; }</style></noscript>
	            <style type="text/css">

            </style>

                    <style type="text/css">
                .variableshopmessage {
                    display:none;
                }
            </style>


                <script type="text/javascript">
            jQuery(document).ready(function () {

							jQuery('#buttonTrocar').click(function(){
									var quantidade = $('#quantity').val();
									if (gPontosDisponiveis < (gPontosNecessarios * quantidade) )
									{
										alert('Você não tem pontos suficientes')
									}else
									 	if (quantidade > 0){
												// POPUP
												if (! confirm('Confirma a troca dos pontos?'))
													return false;
												$.magnificPopup.open({
											        items: { src: '#modalSuccess'},
											        type: 'inline',
													preloader: false,
													modal: true
											       });

											$.ajax({
									        type: "POST",
									        url: '/lojapontos/efetuar-troca',
									        cache: false,
									        dataType: "json",
									        data: 'id='+gId + '&quantidade='+ quantidade,
									        success: function(json){
														$.magnificPopup.close();
															if (json.success)
									             {
									                 alert('Troca efetuada com sucesso\nUma notificação chegará no seu e-mail');
									                 document.location.reload();

									             }
									            else
									            {
									                alert('Não foi possível realizar a alteração\nMotivo: ' + json.message);
									                //document.location.reload();
									            }
									        },
													error: function(r){
														$.magnificPopup.close();
														alert(r.responseText);
													}

									    });
									}else {
										alert('Defina uma quantidade');
									}
							});



                jQuery('#submit').click(function () {

                    var comentario = $('#comment').val();
										if ($.trim(comentario) == '')
											return false;

										if (! confirm('Deseja gravar a avaliação?'))
											return false;

										$.magnificPopup.open({
													items: { src: '#modalSuccess'},
													type: 'inline',
														preloader: false,
														modal: true
										});

										$.ajax({
												type: "POST",
												url: '/lojapontos/gravar-avaliacao',
												cache: false,
												dataType: "json",
												data: 'id='+gId + '&comentario='+ encodeURIComponent(comentario),
												success: function(json){
													$.magnificPopup.close();
														if (json.success)
														 {
																 alert('Avaliação registrada com sucesso');
																 document.location.reload();

														 }
														else
														{
																alert('Não foi possível realizar a alteração\nMotivo: ' + json.message);
																//document.location.reload();
														}
												},
												error: function(r){
													$.magnificPopup.close();
													alert(r.responseText);
												}

										});
                });
            });
        </script>

        </head>

<body class="product-template-default single single-product postid-38 woocommerce woocommerce-page has-header-image colors-light">
<div id="page" class="site">
	<a class="skip-link screen-reader-text" href="#content">Pular para o conteúdo</a>

	<header id="masthead" class="site-header" role="banner">

		<div class="custom-header">

	<div class="custom-header-media">
		<div id="wp-custom-header" class="wp-custom-header"><img src="/images/loja/header.jpg" width="2000" height="1200" alt="Gaúcha Cred | Loja de Pontos" /></div>	</div>

	<div class="site-branding">
	<div class="wrap">


		<div class="site-branding-text">
							<p class="site-title"><a href="/lojapontos/" rel="home">Gaúcha Cred | Loja de Pontos</a></p>

								<p class="site-description">Troque seus pontos por produtos</p>
						</div><!-- .site-branding-text -->


	</div><!-- .wrap -->
</div><!-- .site-branding -->

</div><!-- .custom-header -->


	</header><!-- #masthead -->

	<div class="single-featured-image-header"><img width="1500" height="1125" src="<?php if (is_array($produto)) echo $produto['link']; ?>" class="attachment-twentyseventeen-featured-image size-twentyseventeen-featured-image wp-post-image" alt="" sizes="100vw" /></div><!-- .single-featured-image-header -->
	<div class="site-content-contain">
		<div id="content" class="site-content">

			<div class="wrap">
			<div id="primary" class="content-area twentyseventeen">
				<main id="main" class="site-main" role="main">
          Bem Vindo(a) <?php echo $_SESSION['nome']; ?><br /><br />
		<nav class="woocommerce-breadcrumb"><a href="/lojapontos">Início</a>&nbsp;&#47;&nbsp;<?php  if(isset($produto['nome'])) echo $produto['nome'] . ' &#8211; '. $produto['pontos'] . ' pontos'; ?></nav>



<div id="product-38" class="post-38 product type-product status-publish has-post-thumbnail first instock shipping-taxable purchasable product-type-simple">

	<div class="woocommerce-product-gallery woocommerce-product-gallery--with-images woocommerce-product-gallery--columns-4 images" data-columns="4" style="opacity: 0; transition: opacity .25s ease-in-out;">
	<figure class="woocommerce-product-gallery__wrapper">
		<div data-thumb="<?php if (is_array($produto)) echo $produto['link']; ?>" class="woocommerce-product-gallery__image"><a href="<?php if (is_array($produto)) echo $produto['link']; ?>"><img width="600" height="450" src="<?php if (is_array($produto)) echo $produto['link']; ?>" class="attachment-shop_single size-shop_single wp-post-image" alt="" title="" data-src="<?php if (is_array($produto)) echo $produto['link']; ?>" data-large_image="<?php if (is_array($produto)) echo $produto['link']; ?>" data-large_image_width="1500" data-large_image_height="1125" sizes="100vw" /></a></div>	</figure>
</div>

	<div class="summary entry-summary">

		<h1 class="product_title entry-title"><?php if (isset($produto['nome'])) echo $produto['nome'] . '&#8211; '. $produto['pontos'] . ' pontos'; ?></h1><p class="price"><h6 class="pontos-disponiveis">Você possui <?php echo ($pontos != null) ? $pontos : 0; ?> pontos</h6><span class="woocommerce-Price-amount amount"><span class="woocommerce-Price-currencySymbol">&#8369;</span><?php if (isset($produto['pontos'])) echo $produto['pontos']; ?></span></p>


	<form class="cart" method="post" enctype='multipart/form-data'>
			<div class="quantity">
		<input type="number" class="input-text qty text" step="1" min="1" max="" id="quantity" name="quantity" value="1" title="Qtd" size="4" pattern="[0-9]*" inputmode="numeric" />
	</div>

		<button type="button"  id="buttonTrocar" onclick="return void(0)"  class="single_add_to_cart_button button alt">Trocar Pontos</button>

			</form>


<div class="product_meta">






</div>


	</div><!-- .summary -->


	<div class="woocommerce-tabs wc-tabs-wrapper">
		<ul class="tabs wc-tabs" role="tablist">
							<li class="description_tab" id="tab-title-description" role="tab" aria-controls="tab-description">
					<a href="#tab-description">Descrição</a>
				</li>
							<li class="reviews_tab" id="tab-title-reviews" role="tab" aria-controls="tab-reviews">
					<a href="#tab-reviews">Avaliações (<?php echo count($avaliacoes); ?>)</a>
				</li>
					</ul>
				<div class="woocommerce-Tabs-panel woocommerce-Tabs-panel--description panel entry-content wc-tab" id="tab-description" role="tabpanel" aria-labelledby="tab-title-description">
					  <h2>Descrição</h2>

					<p><?php if (isset($produto['nome'])) echo $produto['nome'] . ' &#8211; '. $produto['pontos'] . ' pontos'; ?></p>
					<div><?php if (isset($produto['descricao'])) echo nl2br( $produto['descricao']); ?></div>
			</div>
			<div class="woocommerce-Tabs-panel woocommerce-Tabs-panel--reviews panel entry-content wc-tab" id="tab-reviews" role="tabpanel" aria-labelledby="tab-title-reviews"><div id="reviews" class="woocommerce-Reviews">
						<div id="comments">
							<h2 class="woocommerce-Reviews-title">Avaliações</h2>

							<?php if (count($avaliacoes) < 1) { ?>
								<p class="woocommerce-noreviews">Não há avaliações ainda.</p>
							<?php } ?>
						</div>

						<div id="review_form_wrapper">
							<div id="review_form">
									<div id="respond" class="comment-respond">
										<?php if (count($avaliacoes) < 1) { ?>
										<span id="reply-title" class="comment-reply-title">
											Seja o primeiro a avaliar &ldquo;<?php echo $produto['nome'] . '&#8211;' . $produto['pontos']; ?> pontos&rdquo;
										</span>
									<?php } ?>
											 <form id="commentform" class="comment-form" novalidate>
													<p class="comment-notes"><span id="email-notes">O seu endereço de e-mail não será publicado.</span> Campos obrigatórios são marcados com <span class="required">*</span></p>
													<p class="comment-form-comment">
														<label for="comment">Sua avaliação sobre o produto <span class="required">*</span></label>
														<textarea id="comment" name="comment" cols="45" rows="8" aria-required="true" required></textarea>
													</p>
													<p class="form-submit">
														<input name="submit" type="button" id="submit" class="submit" value="Enviar" />
													</p>
											</form>
										</div><!-- #respond -->
								</div>
						</div>
					<div class="clear"></div>

					<div class="comentarios">
						<?php
							if (is_array($avaliacoes))
								foreach ($avaliacoes as $avaliacao) { ?>
									<div class="row">
											<div class="comentarios-usuario"><?php echo $avaliacao['nomeUsuario'] . ' - '. $avaliacao['created']; ?></div>
											<span><?php echo $avaliacao['comentario']; ?></span>
									</div>
					<?php		} ?>



					</div>


				</div>
			</div>
			</div>


							</div><!-- #product-38 -->
					</main>
			</div>
					</div>



		</div><!-- #content -->

		<footer id="colophon" class="site-footer" role="contentinfo">
			<div class="wrap">

<div class="site-info">
	<a href="/lojapontos">Loja da Gauchacred®</a>
</div><!-- .site-info -->
			</div><!-- .wrap -->
		</footer><!-- #colophon -->
	</div><!-- .site-content-contain -->
</div><!-- #page -->
<script type="application/ld+json">{"@graph":[{"@context":"https:\/\/schema.org\/","@type":"BreadcrumbList","itemListElement":[{"@type":"ListItem","position":"1","item":{"name":"In\u00edcio","@id":"http:\/\/gauchacred.com\/loja"}},{"@type":"ListItem","position":"2","item":{"name":"TV 32 &#8211; 300 pontos"}}]},{"@context":"https:\/\/schema.org\/","@type":"Product","@id":"http:\/\/gauchacred.com\/loja\/produto\/tv-32-300-pontos\/","url":"http:\/\/gauchacred.com\/loja\/produto\/tv-32-300-pontos\/","name":"TV 32 - 300 pontos","offers":[{"@type":"Offer","priceCurrency":"PHP","availability":"https:\/\/schema.org\/InStock","sku":"","image":"http:\/\/gauchacred.com\/loja\/wp-content\/uploads\/2017\/05\/tv-32.jpg","description":"TV 32\u00a0- 300\u00a0pontos Regras da Campanha: Cada 1000 reais em vendas vale 1 ponto. O\u00a0participante s\u00f3 poder trocar pontos ap\u00f3s R$50.000,00 em vendas SABEMI. \u00c9 permitido apenas \u00a0a troca de 3 produtos por m\u00eas. Os pontos tem per\u00edodo de vig\u00eancia de 12 meses, ap\u00f3s \u00a0isso os pontos expiram. O prazo de entrega dos produtos \u00e9 de 30 dias ap\u00f3s o pedido no site, a entrega vai ser diretamente na loja da Ga\u00facha Cred.","seller":{"@type":"Organization","name":"Ga\u00facha Cred | Loja de Pontos","url":"http:\/\/gauchacred.com\/loja"},"price":"300.00"}]}]}</script>
<!-- Root element of PhotoSwipe. Must have class pswp. -->
<div class="pswp" tabindex="-1" role="dialog" aria-hidden="true">

	<!-- Background of PhotoSwipe. It's a separate element as animating opacity is faster than rgba(). -->
	<div class="pswp__bg"></div>

	<!-- Slides wrapper with overflow:hidden. -->
	<div class="pswp__scroll-wrap">

		<!-- Container that holds slides.
		PhotoSwipe keeps only 3 of them in the DOM to save memory.
		Don't modify these 3 pswp__item elements, data is added later on. -->
		<div class="pswp__container">
			<div class="pswp__item"></div>
			<div class="pswp__item"></div>
			<div class="pswp__item"></div>
		</div>

		<!-- Default (PhotoSwipeUI_Default) interface on top of sliding area. Can be changed. -->
		<div class="pswp__ui pswp__ui--hidden">

			<div class="pswp__top-bar">

				<!--  Controls are self-explanatory. Order can be changed. -->

				<div class="pswp__counter"></div>

				<button class="pswp__button pswp__button--close" aria-label="Fechar (Esc)"></button>

				<button class="pswp__button pswp__button--share" aria-label="Compartilhar"></button>

				<button class="pswp__button pswp__button--fs" aria-label="Expandir tela"></button>

				<button class="pswp__button pswp__button--zoom" aria-label="Ampliar/reduzir (zoom)"></button>

				<!-- Preloader demo http://codepen.io/dimsemenov/pen/yyBWoR -->
				<!-- element will get class pswp__preloader--active when preloader is running -->
				<div class="pswp__preloader">
					<div class="pswp__preloader__icn">
						<div class="pswp__preloader__cut">
							<div class="pswp__preloader__donut"></div>
						</div>
					</div>
				</div>
			</div>

			<div class="pswp__share-modal pswp__share-modal--hidden pswp__single-tap">
				<div class="pswp__share-tooltip"></div>
			</div>

			<button class="pswp__button pswp__button--arrow--left" aria-label="Anterior (seta da esquerda)"></button>

			<button class="pswp__button pswp__button--arrow--right" aria-label="Próximo (seta da direita)"></button>

			<div class="pswp__caption">
				<div class="pswp__caption__center"></div>
			</div>

		</div>

	</div>

</div>


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
<script type='text/javascript' src='/library/jsvendor/jquery-zoom/jquery.zoom.min.js?ver=1.7.15'></script>

<script type='text/javascript'>
/* <![CDATA[ */
var wc_single_product_params = {"i18n_required_rating_text":"Por favor, selecione uma classifica\u00e7\u00e3o","review_rating_required":"yes","flexslider":{"rtl":false,"animation":"slide","smoothHeight":false,"directionNav":false,"controlNav":"thumbnails","slideshow":false,"animationSpeed":500,"animationLoop":false},"zoom_enabled":"1","photoswipe_enabled":"1","flexslider_enabled":"1"};
/* ]]> */
</script>
<script type='text/javascript' src='/library/jsvendor/single-product/js/single-product.min.js?ver=3.0.7'></script>




<script type='text/javascript' src='/library/jsvendor/select2/select2.min.js'></script>


</body>
</html>
<script>
gPontosDisponiveis = <?php echo ($pontos != null) ? $pontos : 0; ?>;
gPontosNecessarios = <?php echo $produto['pontos']; ?>;
gId = <?php echo $produto['id']; ?>;
</script>
