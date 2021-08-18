<?php
/**
 * The template for displaying archive pages.
 *
 * Learn more: http://codex.wordpress.org/Template_Hierarchy
 *
 * @package Zeit Online Blogs Twentyeighteen
 */

get_header(); ?>
  <div class="site-content__navigation__category">
    <?php
    print zb_cats_nav();
    ?>
  </div>
	<div id="primary" class="content-area">
		<div class="site-main<?php if( '1' == $GLOBALS['wp_query']->found_posts ) echo ' one-post-only'; ?>">

		<?php if ( have_posts() ) : ?>

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

		</div><!-- .div-main -->
	</div><!-- #primary -->

<?php get_sidebar(); ?>
<?php get_footer(); ?>
