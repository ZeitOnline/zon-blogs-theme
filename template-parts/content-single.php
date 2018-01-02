<?php
/**
 * Template part for displaying single posts.
 *
 * @package Zeit Online Blogs Twentyeighteen
 */

?>
<!-- template-parts/content-single.php -->
<a class="entry-backlink desktop-only" href="<?php echo get_home_url(); ?>">‹ <?php esc_html_e( 'all entries', 'zb' ); ?></a>
<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
	<header class="entry-header">
		<?php the_title( '<h1 class="entry-title">', '</h1>' ); ?>		
		<div class="entry-meta">
			<?php zb_posted_on( 'long' ); ?>
		</div><!-- .entry-meta -->
		<div class="horizontal-line-long">&nbsp;</div>
	</header><!-- .entry-header -->
	<div class="entry-content">
		<?php
      zb_render_content_with_ads( apply_filters( 'the_content', get_the_content() ), zb_get_meta( 'zb_medium_rectangle_paragraph', 1 ) );
    ?>
	</div><!-- .entry-content -->
	
	<footer class="entry-footer">
  	<div class="entry-footer__options">
		  <?php zb_posted_by(); ?> <span class="entry-footer__option-seperator desktop-only">/</span>
      <a href="javascript:window.print()" class="print-link desktop-only"><?php esc_html_e( 'Print article', 'zb' ); ?></a> <span class="entry-footer__option-seperator desktop-only">/</span>
		  <?php zb_sharing_menu(); ?>
  	</div>
		<p class="entry-footer__categories-headline"><?php esc_html_e( 'Category', 'zb' ); ?></p>
    <span class="entry-footer__categories"><?php the_category( ', ' ); ?></span>
	</footer><!-- .entry-footer -->
</article><!-- #post-## -->

