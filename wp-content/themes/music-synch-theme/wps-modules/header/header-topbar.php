
<?php
/**
 * Header with Top-Bar
 */
?>

	<div id="header-wrapper">
		<div id="top-bar-wrapper">
			<div class="container">
				<div class="row top-bar-desktop">
					<?php dynamic_sidebar('top-bar') ?>
				</div>
				<div class="row top-bar-mobile">
					<?php dynamic_sidebar('top-bar-mobile') ?>
				</div>
			</div>
		</div>

		<!-- ******************* The Navbar Area ******************* -->
		<div id="wrapper-navbar" itemscope itemtype="http://schema.org/WebSite">

			<a class="skip-link sr-only sr-only-focusable" href="#content"><?php esc_html_e( 'Skip to content', 'understrap' ); ?></a>

			<nav class="navbar navbar-expand-xl navbar-dark bg-primary">

			<?php if ( 'container' == $container ) : ?>
				<div class="container">
			<?php endif; ?>

						
            <!-- Your site title as branding in the menu -->
            <?php if ( ! has_custom_logo() ) { ?>

                <?php if ( is_front_page() && is_home() ) : ?>

                    <h1 class="navbar-brand mb-0"><a rel="home" href="<?php echo esc_url( home_url( '/' ) ); ?>" title="<?php echo esc_attr( get_bloginfo( 'name', 'display' ) ); ?>" itemprop="url"><?php bloginfo( 'name' ); ?></a></h1>

				<?php else : ?>

                    <a class="navbar-brand" rel="home" href="<?php echo esc_url( home_url( '/' ) ); ?>" title="<?php echo esc_attr( get_bloginfo( 'name', 'display' ) ); ?>" itemprop="url"><?php bloginfo( 'name' ); ?></a></div>

                <?php endif; ?>


                <?php } else {
				?>
				<div id="navbar-brand-wrapper"><?php the_custom_logo();?></div>
				<?php
                } ?><!-- end custom logo -->


					<!-- The WordPress Menu goes here -->
					<?php wp_nav_menu(
						array(
							'theme_location'  => 'primary',
							'container_class' => 'collapse navbar-collapse',
							'container_id'    => 'navbarNavDropdown',
							'menu_class'      => 'navbar-nav ml-auto',
							'fallback_cb'     => '',
							'menu_id'         => 'main-menu',
							'depth'           => 3,
							'walker'          => new Understrap_WP_Bootstrap_Navwalker(),
						)
					); ?>

					<div id="nav-right-wrapper">
						<?php dynamic_sidebar('nav-right') ?>
					</div>


				<?php if ( 'container' == $container ) : ?>
				</div><!-- .container -->
				<?php endif; ?>

			</nav><!-- .site-navigation -->

		</div><!-- #wrapper-navbar end -->
	</div><!-- #header-wrapper end -->
