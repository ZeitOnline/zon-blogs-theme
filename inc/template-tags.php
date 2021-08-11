<?php
/**
 * Custom template tags for this theme.
 *
 * Eventually, some of the functionality here could be replaced by core features.
 *
 * @package Zeit Online Blogs Twentyeighteen
 */

if ( ! function_exists( 'the_posts_navigation' ) ) :
/**
 * Display navigation to next/previous set of posts when applicable.
 *
 * @todo Remove this function when WordPress 4.3 is released.
 */
function the_posts_navigation() {
	// Don't print empty markup if there's only one page.
	if ( $GLOBALS['wp_query']->max_num_pages < 2 ) {
		return;
	}
	?>
	<nav class="navigation posts-navigation" role="navigation">
		<h2 class="screen-reader-text"><?php esc_html_e( 'Posts navigation', 'zb' ); ?></h2>
		<div class="nav-links">

			<?php if ( get_next_posts_link() ) : ?>
			<div class="nav-previous"><?php next_posts_link( esc_html__( 'Older posts', 'zb' ) ); ?></div>
			<?php endif; ?>

			<?php if ( get_previous_posts_link() ) : ?>
			<div class="nav-next"><?php previous_posts_link( esc_html__( 'Newer posts', 'zb' ) ); ?></div>
			<?php endif; ?>

		</div><!-- .nav-links -->
	</nav><!-- .navigation -->
	<?php
}
endif;

if ( ! function_exists( 'the_post_navigation' ) ) :
/**
 * Display navigation to next/previous post when applicable.
 *
 * @todo Remove this function when WordPress 4.3 is released.
 */
function the_post_navigation() {
	// Don't print empty markup if there's nowhere to navigate.
	$previous = ( is_attachment() ) ? get_post( get_post()->post_parent ) : get_adjacent_post( false, '', true );
	$next     = get_adjacent_post( false, '', false );

	if ( ! $next && ! $previous ) {
		return;
	}
	?>
	<nav class="navigation post-navigation" role="navigation">
		<h2 class="screen-reader-text"><?php esc_html_e( 'Post navigation', 'zb' ); ?></h2>
		<div class="nav-links">
			<?php
				previous_post_link( '<div class="nav-previous">%link</div>', '%title' );
				next_post_link( '<div class="nav-next">%link</div>', '%title' );
			?>
		</div><!-- .nav-links -->
	</nav><!-- .navigation -->
	<?php
}
endif;

if ( ! function_exists( 'zb_posted_on' ) ) :
/**
 * Prints HTML with meta information for the current post-date/time.
 */
function zb_posted_on( $version = 'short' ) {
	$time_string = '<time class="entry-date published updated" datetime="%1$s">%2$s</time>';

  if( $version == 'long' ) {
	$time_string = sprintf( $time_string,
		  esc_attr( get_the_date( 'c' ) ),
	  esc_html( get_the_date( 'j. F Y') ). " um " .get_the_date('G:i'),
	  esc_attr( get_the_modified_date( 'c' ) ),
	  esc_html( get_the_modified_date() )
	  );
	$posted_on = sprintf(
		  esc_html_x( '%s', 'post date', 'zb' ),
	  '<span class="posted-on__anchor">' . $time_string . ' Uhr</span>'
	);
  } else {
	$time_string = sprintf( $time_string,
		esc_attr( get_the_date( 'c' ) ),
		esc_html( get_the_date( 'j. F Y') ),
		esc_attr( get_the_modified_date( 'c' ) ),
		esc_html( get_the_modified_date() )
	);
	$posted_on = sprintf(
		  esc_html_x( '%s', 'post date', 'zb' ),
	  '<span class="posted-on__anchor">' . $time_string . '</span>'
	);
  }

	echo '<span class="zb-posted-on">' . $posted_on . '</span>'; // WPCS: XSS OK.
}

endif;

if ( ! function_exists( 'zb_posted_by' ) ) :
/**
 * Prints HTML with meta information for the current byline.
 */
function zb_posted_by() {
	$byline = sprintf(
		esc_html_x( 'by %s', 'post author', 'zb' ),
		'<span class="author vcard"><a class="url fn n" href="' . esc_url( get_author_posts_url( get_the_author_meta( 'ID' ) ) ) . '">' . esc_html( get_the_author() ) . '</a></span>'
	);

	echo '<span class="zb-byline"> ' . $byline . '</span>'; // WPCS: XSS OK.

}
endif;

if ( ! function_exists( 'zb_entry_footer' ) ) :
/**
 * Prints HTML with meta information for the categories, tags and comments.
 */
function zb_entry_footer($separator = "") {
	// Hide category and tag text for pages.
	if ( 'post' == get_post_type() ) {
		if ( ! is_single() && ! post_password_required() && ( comments_open() || get_comments_number() ) ) {
		  echo $separator;
		  echo '<span class="comments-link">';
		  comments_popup_link( esc_html__( '0 Comments', 'zb' ), esc_html__( '1 Comment', 'zb' ), esc_html__( '% Comments', 'zb' ) );
		  echo '</span>';
	  }

	//edit_post_link( esc_html__( 'Edit', 'zb' ), '<span class="edit-link">', '</span>' );
  }
}
endif;


/**
 * renders the single comment on comments list
 */
if( ! function_exists('zb_comment') ) {
	function zb_comment( $comment, $counter, $sort, $comment_count=0, $offset=0 ) {
		$GLOBALS['comment'] = $comment; ?>
<li <?php comment_class(); ?> id="li-comment-<?php comment_ID() ?>">
	<div id="comment-<?php comment_ID(); ?>" class="comment-inner">
		<div class="zb-comment-author vcard">
			<?php
			//$avatar = get_avatar( $comment, $size='50', "404", false );
			$avatar = get_avatar( $comment, $size='50' );
			?>
			<span class="zb-comment-avatar-wrapper"><?php echo $avatar; ?>&nbsp;</span>
			<?php printf(__('<span class="zb-comment-author-name"><span class="zb-icon-author"></span> %s</span>'), get_comment_author_link()) ?>
		</div>
		<div class="zb-comment-meta commentmetadata">
			<a href="#comment-<?php comment_ID(); ?>">#<?php echo $counter; ?>&nbsp;&nbsp;&mdash;&nbsp;&nbsp;vor <?php echo human_time_diff( strtotime("{$comment->comment_date_gmt} GMT"), time() ); ?></a>
		</div>
		<br />
		<div class="zb-comment-text"><?php comment_text() ?></div>
	</div>
	<?php
		if ( $sort == 'asc' ) {
			if ( $comment_count > 3 && $comment_count - $offset > 3 && $counter == $offset + 2 ) {
				zb_render_ad( 'desktop', '9', 'ad-wrapper ad-medium-rectangle ad-container', 'article', 'Anzeige' );
				zb_render_ad( 'mobile', '8', 'ad-wrapper ad-medium-rectangle ad-container' );
			}
		} else {
			if ( $comment_count > 3 && $comment_count - $offset > 3 && ($counter == $comment_count - $offset - 1) ) {
				zb_render_ad( 'desktop', '9', 'ad-wrapper ad-medium-rectangle ad-container', 'article', 'Anzeige' );
				zb_render_ad( 'mobile', '8', 'ad-wrapper ad-medium-rectangle ad-container' );
			}
		}
	// closing </li> generated by wordpress
	}
}

if( ! function_exists( 'zb_sharing_menu' )):
function zb_sharing_menu() { ?>
	<span class="zb-sharing-menu zb-sharing-menu--active js-zb-sharing-menu" data-ct-area="articlebottom">
		<span class="zb-sharing-menu__title"><?php esc_html_e( 'Share', 'zb' ); ?></span>
		<br />
		<span class="zb-sharing-menu__items" data-ct-row="social">
			<span class="zb-sharing-menu__item">
				<a class="zb-sharing-menu__link zb-sharing-menu__link--facebook" href="http://www.facebook.com/sharer/sharer.php?u=<?php echo urlencode( get_the_permalink() ); ?>" target="_blank" data-ct-label="facebook">
					Facebook
				</a>
			</span>
			<span class="zb-sharing-menu__item">
				<a class="zb-sharing-menu__link zb-sharing-menu__link--twitter" href="http://twitter.com/intent/tweet?<?php

					echo http_build_query( array(
						'text' => get_the_title(),
						'url' => get_the_permalink(),
						'via' => 'zeitonline'
					));

					?>" target="_blank" data-ct-label="twitter">
					Twitter
				</a>
			</span>
			<span class="zb-sharing-menu__item zb-sharing-menu__item--whatsapp">
				<a class="zb-sharing-menu__link zb-sharing-menu__link--whatsapp" href="whatsapp://send?text=<?php

					echo urlencode( get_the_title() . ' - Artikel auf ZEIT ONLINE: ' . get_the_permalink() );

					?>" data-ct-label="whatsapp">
					WhatsApp
				</a>
			</span>
			<span class="zb-sharing-menu__item">
				<a class="zb-sharing-menu__link zb-sharing-menu__link--mail" href="mailto:?subject=<?php echo rawurlencode( get_the_title() . ' - Artikel auf ZEIT ONLINE' ); ?>&amp;body=<?php echo rawurlencode( 'Artikel auf ZEIT ONLINE lesen: ' . get_the_permalink() ); ?>" data-ct-label="mail">
					Mail
				</a>
			</span>
		</span>
	</span>
<?php
}

endif;


if ( ! function_exists( 'the_archive_title' ) ) :
/**
 * Shim for `the_archive_title()`.
 *
 * Display the archive title based on the queried object.
 *
 * @todo Remove this function when WordPress 4.3 is released.
 *
 * @param string $before Optional. Content to prepend to the title. Default empty.
 * @param string $after  Optional. Content to append to the title. Default empty.
 */
function the_archive_title( $before = '', $after = '' ) {
	if ( is_category() ) {
		$title = sprintf( esc_html__( 'Category: %s', 'zb' ), single_cat_title( '', false ) );
	} elseif ( is_tag() ) {
		$title = sprintf( esc_html__( 'Tag: %s', 'zb' ), single_tag_title( '', false ) );
	} elseif ( is_author() ) {
		$title = sprintf( esc_html__( 'Author: %s', 'zb' ), '<span class="vcard">' . get_the_author() . '</span>' );
	} elseif ( is_year() ) {
		$title = sprintf( esc_html__( 'Year: %s', 'zb' ), get_the_date( esc_html_x( 'Y', 'yearly archives date format', 'zb' ) ) );
	} elseif ( is_month() ) {
		$title = sprintf( esc_html__( 'Month: %s', 'zb' ), get_the_date( esc_html_x( 'F Y', 'monthly archives date format', 'zb' ) ) );
	} elseif ( is_day() ) {
		$title = sprintf( esc_html__( 'Day: %s', 'zb' ), get_the_date( esc_html_x( 'F j, Y', 'daily archives date format', 'zb' ) ) );
	} elseif ( is_tax( 'post_format' ) ) {
		if ( is_tax( 'post_format', 'post-format-aside' ) ) {
			$title = esc_html_x( 'Asides', 'post format archive title', 'zb' );
		} elseif ( is_tax( 'post_format', 'post-format-gallery' ) ) {
			$title = esc_html_x( 'Galleries', 'post format archive title', 'zb' );
		} elseif ( is_tax( 'post_format', 'post-format-image' ) ) {
			$title = esc_html_x( 'Images', 'post format archive title', 'zb' );
		} elseif ( is_tax( 'post_format', 'post-format-video' ) ) {
			$title = esc_html_x( 'Videos', 'post format archive title', 'zb' );
		} elseif ( is_tax( 'post_format', 'post-format-quote' ) ) {
			$title = esc_html_x( 'Quotes', 'post format archive title', 'zb' );
		} elseif ( is_tax( 'post_format', 'post-format-link' ) ) {
			$title = esc_html_x( 'Links', 'post format archive title', 'zb' );
		} elseif ( is_tax( 'post_format', 'post-format-status' ) ) {
			$title = esc_html_x( 'Statuses', 'post format archive title', 'zb' );
		} elseif ( is_tax( 'post_format', 'post-format-audio' ) ) {
			$title = esc_html_x( 'Audio', 'post format archive title', 'zb' );
		} elseif ( is_tax( 'post_format', 'post-format-chat' ) ) {
			$title = esc_html_x( 'Chats', 'post format archive title', 'zb' );
		}
	} elseif ( is_post_type_archive() ) {
		$title = sprintf( esc_html__( 'Archives: %s', 'zb' ), post_type_archive_title( '', false ) );
	} elseif ( is_tax() ) {
		$tax = get_taxonomy( get_queried_object()->taxonomy );
		/* translators: 1: Taxonomy singular name, 2: Current taxonomy term */
		$title = sprintf( esc_html__( '%1$s: %2$s', 'zb' ), $tax->labels->singular_name, single_term_title( '', false ) );
	} else {
		$title = esc_html__( 'Archives', 'zb' );
	}

	/**
	 * Filter the archive title.
	 *
	 * @param string $title Archive title to be displayed.
	 */
	$title = apply_filters( 'get_the_archive_title', $title );

	if ( ! empty( $title ) ) {
		echo $before . $title . $after;  // WPCS: XSS OK.
	}
}
endif;

if ( ! function_exists( 'the_archive_description' ) ) :
/**
 * Shim for `the_archive_description()`.
 *
 * Display category, tag, or term description.
 *
 * @todo Remove this function when WordPress 4.3 is released.
 *
 * @param string $before Optional. Content to prepend to the description. Default empty.
 * @param string $after  Optional. Content to append to the description. Default empty.
 */
function the_archive_description( $before = '', $after = '' ) {
	$description = apply_filters( 'get_the_archive_description', term_description() );

	if ( ! empty( $description ) ) {
		/**
		 * Filter the archive description.
		 *
		 * @see term_description()
		 *
		 * @param string $description Archive description to be displayed.
		 */
		echo $before . $description . $after;  // WPCS: XSS OK.
	}
}
endif;

/**
 * Returns true if a blog has more than 1 category.
 *
 * @return bool
 */
function zb_categorized_blog() {
	if ( false === ( $all_the_cool_cats = get_transient( 'zb_categories' ) ) ) {
		// Create an array of all the categories that are attached to posts.
		$all_the_cool_cats = get_categories( array(
			'fields'     => 'ids',
			'hide_empty' => 1,

			// We only need to know if there is more than one category.
			'number'     => 2,
		) );

		// Count the number of categories that are attached to the posts.
		$all_the_cool_cats = count( $all_the_cool_cats );

		set_transient( 'zb_categories', $all_the_cool_cats );
	}

	if ( $all_the_cool_cats > 1 ) {
		// This blog has more than 1 category so zb_categorized_blog should return true.
		return true;
	} else {
		// This blog has only 1 category so zb_categorized_blog should return false.
		return false;
	}
}

/**
 * Flush out the transients used in zb_categorized_blog.
 */
function zb_category_transient_flusher() {
	if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
		return;
	}
	// Like, beat it. Dig?
	delete_transient( 'zb_categories' );
}
add_action( 'edit_category', 'zb_category_transient_flusher' );
add_action( 'save_post',     'zb_category_transient_flusher' );


function determine_paragraph_by_length( $paras, $min ) {
	$length = 0;
	$index = -1;
	while ( $length <= $min ) {
		$index++;
		$length += strlen(strip_tags($paras[$index]));
	};
	return $index;
}

function var_template_part($slug, $name) {
	ob_start();
	get_template_part( $slug, $name );
	return ob_get_clean();
}

/**
* Renders article content with inlined ads and autorbox
*
* @param $content 	string 	the text content of an article
* @param $ad_paragraph 		mixed 			int or string w/o number where the ad should be placed
* @param $box_paragraph 	mixed 			int or string w/o number where the authorbox should be placed
* @param $box 				string|bool 	type of box or false
* @param $box_full			string|bool		fullwidth box as a special type for backwards compatibility
*/
if ( ! function_exists('zb_render_content_with_ads') ) {
	function zb_render_content_with_ads( $content, $ad_paragraph=1, $box_paragraph=2, $box=false, $box_full=false ) {
		$output = "";
		$ad_paragraph = intval( $ad_paragraph ) - 1;
		$box_paragraph = intval( $box_paragraph ) - 1;
		$second_ad_paragraph = false;
		$prefix = '<br style="clear:both;" />';
		$prefix .= '<div class="widget-author-box widget-author-articlebox widget-author-articlebox-fullwidth">';
		// seperate content into paragraphs
		// only catch paragrqphs w/o attributes
		preg_match_all( '/<p>(.*)<\/p>/', $content, $matches );
		$items = count( $matches[ 0 ] );
		if ( $items > 0 ) {
			// if first para selected use min char length
			if ( $ad_paragraph == 0  ) {
				$ad_paragraph = determine_paragraph_by_length( $matches[ 0 ], get_option( 'zon_ads_paragraph_length', 200 ) );
			}
			// enough space for 2nd ad?
			if ( $ad_paragraph + 4 <= $items  ) {
				$second_ad_paragraph = $ad_paragraph + 3;
			}
			$output = var_template_part( 'template-parts/ads', 'article-content-top' );
			$ad_content = $output . $matches[ 0 ][ $ad_paragraph ];
			$content = str_replace( $matches[ 0 ][ $ad_paragraph ], $ad_content, $content );
			if ( $second_ad_paragraph ) {
				$output = var_template_part( 'template-parts/ads', 'article-content-center' );
				$ad_content = $output . $matches[ 0 ][ $second_ad_paragraph ];
				$content = str_replace( $matches[ 0 ][ $second_ad_paragraph ], $ad_content, $content );
			}
			// add authorbox
			if ( $box != 'hide-author-box') {
				if ( $box_paragraph == $ad_paragraph || $box_paragraph == $second_ad_paragraph ) {
					$box_paragraph++;
				}
				if (
					! $box_full &&
					( $box != 'bottom-author-box' ) &&
					( $box != 'hide-author-box' ) &&
					$box_paragraph < $items
				) {
					$prefix = '<div class="widget-author-box widget-author-articlebox">';
				} else {
					$box_paragraph = $items - 1;
				}
				ob_start();
				print $prefix;
				dynamic_sidebar( 'article-author' );
				print '</div>';
				$output = ob_get_clean();
				$authorbox = $output . $matches[ 0 ][ $box_paragraph ];
				$content = str_replace( $matches[ 0 ][ $box_paragraph ], $authorbox, $content );
			}

		} else {
			// if there IS nothing to replace at least add the author to the bottom
			if ( $box != 'hide-author-box' ) {
				ob_start();
				print $prefix;
				dynamic_sidebar( 'article-author' );
				print '</div>';
				$output = ob_get_clean();
				$content .= $output;
			}
		}
		// fire!
		print $content;
	}
}

if( ! function_exists( 'zb_render_ad' ) ) {
	/**
	 * Render function to display ad code in blog pages
	 *
	 * @param  string $type The type of the ad delivered, i.e. mobile or desktop
	 * @param  string $tilenumber The number of the iqadtile, iqadtile16 for instance
	 * @param  string $wrapperclass CSS-Classnames for the wrapping container
	 * @param  string $pagetype Tpe of the blogpage, which is blog for indexpages and article for articlepages
	 * @param  string $adlabel For desktop ads the adlabel is printed as :before content, normally "Anzeige"
	 * @param  string $containerclasses CSS-Classnames for an conditional inner container, i.e. "ad-medium-rectangle"
	 * @param  mixed $comment String with html comment preceding the adplace, false for standard comment text
	 * @return void
	 */
	function zb_render_ad( $type, $tilenumber, $wrapperclasses, $pagetype='blog', $adlabel='', $containerclasses='', $comment=false ) {
		if ( ! get_option( 'zon_ads_deactivated' ) ) {
			$template = <<< EOT
<!-- {{ comment }} -->
<div class="{{ wrapperclasses }}" data-type="{{ type }}" data-tile="{{ tilenumber }}">
	{{ container_start }}
		<script id="ad-{{ type }}-{{ tilenumber }}">
			if ( typeof AdController !== 'undefined' && {{ modificator }}window.Zeit.isMobileView() ) {
				if( ! document.getElementById( "iqadtile{{ tilenumber }}" ) ) {
					var elem = document.createElement('div');
					elem.id = "iqadtile{{ tilenumber }}";
					elem.className = "ad ad-{{ type }} ad-{{ type }}--{{ tilenumber }} ad-{{ type }}--{{ tilenumber }}-on-{{ pagetype }}";
					elem.setAttribute('data-banner-type', '{{ type }}');
					elem.setAttribute('data-banner-label', '{{ adlabel }}');
					document.getElementById('ad-{{ type }}-{{ tilenumber }}').parentNode.appendChild(elem);
					AdController.render('iqadtile{{ tilenumber }}');
					if (window.console && typeof window.console.info === 'function') {
						window.console.info('AdController ' + AdController.VERSION + ' tile {{ tilenumber }} {{ type }}')
					}
				}
			}
		</script>
	{{ container_end }}
</div>
EOT;
			$modificator = $type == 'desktop' ? '!' : '';
			$comment = $comment ? $comment : 'ad-' . $type . '-' . $tilenumber;
			$container_start = $containerclasses ? '<div class="' . $containerclasses . '">' : '';
			$container_end = $container_start ? '</div>' : '';
			$search = array(
				'{{ type }}',
				'{{ tilenumber }}',
				'{{ wrapperclasses }}',
				'{{ pagetype }}',
				'{{ adlabel }}',
				'{{ container_start }}',
				'{{ container_end }}',
				'{{ modificator }}',
				'{{ comment }}'
			);
			$replace = array(
				$type,
				$tilenumber,
				$wrapperclasses,
				$pagetype,
				$adlabel,
				$container_start,
				$container_end,
				$modificator,
				$comment
			);
			$output = str_replace($search, $replace, $template);
			print $output;
		}
	}
}
