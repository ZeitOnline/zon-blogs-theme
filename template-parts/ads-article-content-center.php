<?php
/**
 * Template part for displaying ad on article.
 *
 * @package Zeit Online Blogs Twentyeighteen
 */

if (! zb_get_meta( 'zb_hide_medium_rectangle' ) && ( zb_get_meta( 'zb_medium_rectangle' ) != 'hide-medium-rectangle' ) ) {
	zb_place_ad( 4, 'mobile' );
	zb_place_ad( 4, 'desktop', ['ad-fullwidth'] );
}
?>
