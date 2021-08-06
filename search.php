<?php
/**
 * The template for displaying search results pages.
 *
 * @package Zeit Online Blogs Twentyeighteen
 */

get_header(); ?>

	<section id="primary" class="content-area">
		<main id="main" class="site-main" role="main">

		<?php if ( have_posts() ) : ?>

			<header class="page-header">
				<h1 class="page-title"><?php printf( esc_html__( 'Search Results for: %s', 'zb' ), '<span>' . get_search_query() . '</span>' ); ?></h1>
			</header><!-- .page-header -->

			<?php /* Start the Loop */
			$counter = 1;
			?>
			<?php while ( have_posts() ) : the_post(); ?>
				<?php
				/**
				 * Run the loop for the search to output the results.
				 * If you want to overload this in a child theme then include a file
				 * called content-search.php and that will be used instead.
				 */
				get_template_part( 'template-parts/content', get_post_format() );
				if ( $counter == 1) {
					zb_render_ad( 'mobile', '1', 'ad-wrapper ad-wrapper-fullwidth', 'blog', '', 'ad-medium-rectangle' );
				}
				if ( $counter == 2) {
					zb_render_ad( 'mobile', '3', 'ad-wrapper ad-wrapper-fullwidth', 'blog', '', 'ad-medium-rectangle' );
					zb_render_ad( 'desktop', '8', 'ad-wrapper ad-wrapper-fullwidth', 'blog', 'Anzeige', 'ad-medium-rectangle' );
				}
				if ( $counter == 5) {
					zb_render_ad( 'desktop', '4', 'ad-wrapper ad-wrapper-fullwidth', 'blog', 'Anzeige' );
				}
				if ( $counter == 8) {
					zb_render_ad( 'mobile', '4', 'ad-wrapper ad-wrapper-fullwidth',  'blog', '', 'ad-medium-rectangle' );
				}
				$counter++;
				?>

			<?php endwhile; ?>

			<?php zb_paging_nav(); ?>

		<?php else : ?>

			<?php get_template_part( 'template-parts/content', 'none' ); ?>

		<?php endif; ?>

		</main><!-- #main -->
	</section><!-- #primary -->

<?php get_sidebar(); ?>
<?php get_footer(); ?>
