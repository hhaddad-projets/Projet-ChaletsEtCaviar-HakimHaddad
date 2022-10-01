<?php
/**
 * The template to displaying all chalets
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/#single-post
 *
 * @package wordpress
 * @subpackage bstone
 * @since Bstone 1.0
 */

$bstone_page_layout = bstone_page_layout();

get_header(); ?>

<div class="st-container st-flex content-top st-<?php echo esc_attr( $bstone_page_layout ); ?>" >
	<div id="primary" <?php bstone_primary_class('st-flex-grow-1'); ?>>
	
		<?php bstone_primary_content_top(); ?>
		<head>
			<link href="//netdna.bootstrapcdn.com/bootstrap/3.1.0/css/bootstrap.min.css" rel="stylesheet" id="bootstrap-css">
		</head>
		<main id="main" class="site-main bst-post-single">

		<?php
		while ( have_posts() ) : the_post();

			if ( !function_exists('get_field') ) return; /*Function from the Advanced Custom Fields, it returns the value of a specific field (doc : https://www.advancedcustomfields.com/resources/get_field/ )*/
			
			/*---------------------Getting the values of the caracteristics by the custom fields----------------------------*/
			$name = get_field('nom_du_chalet');
			$reference = get_field('reference_du_chalet');
			$description = get_field('description_du_chalet');
			$surface = get_field('surface_du_chalet');
			$chambers = get_field('nombre_de_chambres');
			$equipements = get_field('equipements'); // the equipements are saved in an array 
			$category = get_field('categorie_de_chalet');
			$address = get_field('adresse');
			/*-------------------------------------------------------------------------------------------------------------------------*/

			/*---------------------Determination of the right price according to the chalet category--------------------------*/
			if ($category == "À vendre") {
				$prix = get_field('prix_de_vente');
				$signe = '€';
			} elseif ($category == "À louer") {
				$prix = get_field('prix_de_location');
				$signe = '€/semaine';
			}
			/*------------------------------------------------------------------------------------------------------------------------*/

			/*---------------------Creation of an array to collect all the URL of the pictures used to show the chalet----------------*/
			$photo = get_field('photo_du_chalet');
			$i=1;
			$tableau = array(
				1 => get_field('photo_du_chalet'),
				2 => get_field('photo_du_chalet_2'),
				3 => get_field('photo_du_chalet_3'),
				4 => get_field('photo_du_chalet_4'),
				5 => get_field('photo_du_chalet_5'),
				6 => get_field('photo_du_chalet_6'),
				7 => get_field('photo_du_chalet_7'),
				8 => get_field('photo_du_chalet_8')
			);
			/*------------------------------------------------------------------------------------------------------------------------*/

			$tableau = array_filter($tableau); 
			$premiere_cle = key($tableau);
		?>

			<div class="l-container-chalets">

				<h1 class="l-container-chalets__title"><?php echo $name; ?></h1>

				<!-----------------------------------------------Carousel------------------------------------------------------------->
				<div class="section-carousel">
		  			<div class="container-carousel">

					    <div id="carousel-example-generic" class="carousel slide" data-ride="carousel">

					      <!-- Indicators -->
					    	<ol class="carousel-indicators">
						      <li data-target="#carousel-example-generic" data-slide-to="0" class="active"></li>
								<?php
									/*---------------------------Displaying one indicator per picture--------------------------------*/
									for($i = ($premiere_cle + 1); $i <= 8; $i++){
										if (isset($tableau[$i])) {
											$j = $i-1; ?>
											<li data-target="#carousel-example-generic" data-slide-to="<?php echo $j; ?>" ></li><?php
										}
									}
								?>
					    	</ol>

		      				<!-- Wrapper for slides -->
					     	<div class="carousel-inner">
						        <div class="item active">
		      						<img src="<?php echo $tableau[1]; ?>" alt="Photo du <?php echo $name; ?>"class="item__img--active"/>
		      					</div>
		      					<?php
		      						/*--------------Displaying the pictures by their URL saved in the array $tableau-----------------*/
		      						unset($tableau[1]);
									foreach ($tableau as $value) {?>
										<div class="item">
											<img src="<?php echo $value; ?>" alt="Photo du <?php echo $name; ?>" class="item__img"/>
										</div><?php
									}
								?>
		      				</div>

		      				<!-- Controls -->
		      				<a class="left carousel-control" href="#carousel-example-generic" data-slide="prev">
		        				<span class="glyphicon glyphicon-chevron-left"></span>
		      				</a>
		      				<a class="right carousel-control" href="#carousel-example-generic" data-slide="next">
		        				<span class="glyphicon glyphicon-chevron-right"></span>
		      				</a>

		    			</div> <!-- #carousel-example-generic -->
					</div> <!-- .container-carousel -->
				</div> <!-- .section-carousel -->
				
				<span class="l-container-chalets__reference"><?php echo $reference ; ?></span>

				
				<div class="separator"></div> 


				<div class="l-container-chalets__address">
					<p><?php echo $address; ?></p>
				</div>

				<!------------------------------------------Specifics to each chalet------------------------------------------------------------->
				<div class="chalets-specifications">
					<div class="chalets-specifications__description">
						<p class="chalets-specifications__description__header">Description de ce bien </p>
						<p><?php echo $description; ?></p>
					</div>

					<div class="chalets-specifications__container">
						<div class="chalets-specifications__container__informations">
							<p class="chalets-specifications__container__informations__header">L'essentiel</p>
							<p><?php echo $surface; ?> m<sup>2</sup></p>
							<p><?php echo $chambers; ?> chambres</p>

							<?php
								/*---------------------------Displaying the specifics equipements in a list--------------------------------*/
								if ($equipements){
									?><ul class="chalets-specifications__container__informations__equipements-list"><?php
									foreach ($equipements as $equipement) {?>
										<li class="chalets-specifications__container__informations__equipements-list__item"><?php echo $equipement; ?></li><?php
									} ?>
									</ul> <?php
								}
							?>

							<h3 class ="chalets-specifications__container__informations__endtitle">Ce bien de prestige est certifié Chalets & Caviar !</h3>
						</div>
					</div>
				</div>

				<!------------------------------------------Common to all chalets------------------------------------------------------------->
				<div class="chalets-commodities"> 
					<h2 class="chalets-commodities__title">Les indispensables <strong class="chalets-commodities__title__mark">Chalets & Caviar</strong></h2>

					<div class ="chalets-commodities__list">
						<figure class="chalets-commodities__list__item">
							<img src="http://hakimhaddad-projets.fr/chalets-et-caviar/wp-content/uploads/2018/07/wifi-1.png" alt="icone wifi" />
							<figcaption>Wifi gratuit</figcaption>
						</figure>
						<figure class="chalets-commodities__list__item">
							<img src="http://hakimhaddad-projets.fr/chalets-et-caviar/wp-content/uploads/2018/07/bar-1.png" alt="icone verre de vin" />
							<figcaption>Accès VIP au bar de la station</figcaption>
						</figure>
						<figure class="chalets-commodities__list__item">
							<img src="http://hakimhaddad-projets.fr/chalets-et-caviar/wp-content/uploads/2018/07/tv-1.png" alt="icone télévision" />
							<figcaption>TV 4K 150cm</figcaption>
						</figure>
						<figure class="chalets-commodities__list__item">
							<img src="http://hakimhaddad-projets.fr/chalets-et-caviar/wp-content/uploads/2018/07/food-tray.png" alt="icone cuisine" />
							<figcaption>Cuisinier étoilé</figcaption>
						</figure>
					</div>
				</div>


				<div class="separator"></div>


				<!------------------------------------------Price and call-to-action------------------------------------------------------------->
				<div class="chalets-reserve_buy">
					<h2 class="chalets-reserve_buy__title">Chalet <?php echo $category; ?></h2>
					<div class="chalets-reserve_buy__container">
						<div class="chalets-reserve_buy__container__price"><p><?php echo $prix . $signe; ?></p></div>
						<?php 
							/*-------------------------------Displaying a call-to-action button according to the category of the chalet (for rent or for sale)-----------------------------------*/
							if ($category == "À vendre") {
								?><div class="chalets-reserve_buy__container__action"><p><a href="http://hakimhaddad-projets.fr/chalets-et-caviar/contact/" class="chalets-reserve_buy__container__action--link">nous contacter</a></p></div><?php
							} elseif ($category == "À louer") {
								?><div class="chalets-reserve_buy__container__action"><p><a href="http://hakimhaddad-projets.fr/chalets-et-caviar/reservation/" class="chalets-reserve_buy__container__action--link">réserver</a></p></div><?php
							}
						?>					
					</div>
				</div>
			</div> <!-- .l-container-chalets -->


		<?php
		endwhile; // End of the loop.
		?>
			<script src="//netdna.bootstrapcdn.com/bootstrap/3.1.0/js/bootstrap.min.js"></script>
			<script src="//code.jquery.com/jquery-1.11.1.min.js"></script>
		</main><!-- #main -->
		
		<?php bstone_primary_content_bottom(); ?>
		
	</div><!-- #primary -->

<?php
if( $bstone_page_layout == 'right-sidebar' || $bstone_page_layout == 'left-sidebar' || $bstone_page_layout == 'both-sidebars' ) :
	  get_sidebar();
endif;
?>
</div><!-- .st-container -->
  
<?php get_footer();
?>
