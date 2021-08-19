<?php
/**
 * The main template file.
 *
 * This is the most generic template file in a WordPress theme
 * and one of the two required files for a theme (the other being style.css).
 * It is used to display a page when nothing more specific matches a query.
 * E.g., it puts together the home page when no home.php file exists.
 * Learn more: http://codex.wordpress.org/Template_Hierarchy
 *
 * @package Zeit Online Blogs Twentyeighteen
 */

get_header(); ?>

	<?php if( is_front_page() ){ ?>
    <div class="site-content__navigation__category">
	    <?php
	    print zb_cats_nav();
	    ?>
	  </div>
	<?php }; ?>

	<div id="primary" class="content-area">
		<div class="site-main">

		<?php if ( have_posts() ) : ?>

			<?php if ( is_home() && ! is_front_page() ) : ?>
				<header>
					<h1 class="page-title screen-reader-text"><?php single_post_title(); ?></h1>
				</header>
			<?php endif; ?>

			<?php /* Start the Loop */
			$counter = 1;
			?>
			<?php while ( have_posts() ) : the_post(); ?>

				<?php

					/*
					 * Include the Post-Format-specific template for the content.
					 * If you want to override this in a child theme, then include a file
					 * called content-___.php (where ___ is the Post Format name) and that will be used instead.
					 */
					get_template_part( 'template-parts/content', get_post_format() );
					if ( $counter == 1 ) {
						zb_place_ad( 1, 'mobile' );
					}
					if ( $counter == 2 ) {
						zb_place_ad( 3, 'mobile' );
						zb_place_ad( 8, 'desktop', ['ad-centered'] );
					}
					if ( $counter == 5 ) {
						zb_place_ad( 4 );
						zb_place_ad( 4, 'mobile' );
					}
					if ( $counter == 8 ) {
						zb_place_ad( 41 );
						zb_place_ad( 41, 'mobile' );
					}
					if ( $counter == 12 ) {
						zb_place_ad( 42);
						zb_place_ad( 42, 'mobile' );
					}
					$counter++;
				?>

			<?php endwhile; ?>

			<?php zb_paging_nav(); ?>

		<?php else : ?>

			<?php get_template_part( 'template-parts/content', 'none' ); ?>

		<?php endif; ?>

		</div><!-- .site-main -->
	</div><!-- #primary -->

<?php get_sidebar(); ?>

<?php get_footer(); ?>
