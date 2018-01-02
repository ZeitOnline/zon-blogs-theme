<?php
/**
 * The template for displaying all single posts.
 *
 * @package Zeit Online Blogs Twentyeighteen
 */

get_header(); ?>

	<div id="primary" class="content-area">
		<div class="site-main">

		<?php while ( have_posts() ) : the_post(); ?>

			<?php get_template_part( 'template-parts/content', 'single' ); ?>


			<?php
				// If comments are open or we have at least one comment, load up the comment template.
				if ( comments_open() || get_comments_number() ) :
					comments_template();
				endif;
			?>

		<?php endwhile; // End of the loop. ?>

		</div><!-- -site-main -->
	</div><!-- #primary -->

<?php get_sidebar(); ?>
<?php get_footer(); ?>
