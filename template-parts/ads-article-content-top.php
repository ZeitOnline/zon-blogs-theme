<?php
/**
 * Template part for displaying ad on article.
 *
 * @package Zeit Online Blogs Twentyeighteen
 */

if (! zb_get_meta( 'zb_hide_medium_rectangle' ) && ( zb_get_meta( 'zb_medium_rectangle' ) != 'hide-medium-rectangle' ) ) {
	zb_render_ad( 'desktop', '8', 'ad-wrapper ad-wrapper--article ad-container', 'article', 'Anzeige' );
	zb_render_ad( 'mobile', '3', 'ad-wrapper ad-wrapper--article ad-container' );
}
?>
