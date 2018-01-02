<?php
/**
 * The sidebar containing the main widget area.
 *
 * @package Zeit Online Blogs Twentyeighteen
 */

if ( ! is_active_sidebar( 'sidebar-1' ) ) {
	return;
}
?>
<div class="sidebar-wrapper-fullwidth" id="colophon">
	<div id="secondary" class="widget-area" role="complementary">
		<?php dynamic_sidebar( 'sidebar-1' ); ?>
		<hr class="widget-area__clearfix"/>
	</div><!-- #secondary -->
</div>
