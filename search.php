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

		</main><!-- #main -->
	</section><!-- #primary -->

<?php get_sidebar(); ?>
<?php get_footer(); ?>
