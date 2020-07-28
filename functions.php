<?php

// remove meta tag <meta name="generator" content="WordPress 4.x">
remove_action('wp_head', 'wp_generator');

/**
 * Zeit Online Blogs Twentyfifteen functions and definitions
 *
 * @package Zeit Online Blogs Twentyeighteen
 */

function zb_set_cache_headers( $headers ) {
	return array_merge($headers, wp_get_nocache_headers());
}
add_filter( 'wp_headers', 'zb_set_cache_headers' );

if ( ! function_exists( 'zb_setup' ) ) :
/**
 * Sets up theme defaults and registers support for various WordPress features.
 *
 * Note that this function is hooked into the after_setup_theme hook, which
 * runs before the init hook. The init hook is too late for some features, such
 * as indicating support for post thumbnails.
 */
function zb_setup() {
	/*
	 * Make theme available for translation.
	 * Translations can be filed in the /languages/ directory.
	 * If you're building a theme based on Zeit Online Blogs Twentyfifteen, use a find and replace
	 * to change 'zb' to the name of your theme in all the template files
	 */
	load_theme_textdomain( 'zb', get_template_directory() . '/languages' );

	// Add default posts and comments RSS feed links to head.
	add_theme_support( 'automatic-feed-links' );

	/*
	 * Let WordPress manage the document title.
	 * By adding theme support, we declare that this theme does not use a
	 * hard-coded <title> tag in the document head, and expect WordPress to
	 * provide it for us.
	 */
	add_theme_support( 'title-tag' );

	/*
	 * Enable support for Post Thumbnails on posts and pages.
	 *
	 * @link http://codex.wordpress.org/Function_Reference/add_theme_support#Post_Thumbnails
	 */
	add_theme_support( 'post-thumbnails' );

	// This theme uses wp_nav_menu() in one location.
	register_nav_menus( array(
		'primary' => esc_html__( 'Primary Menu', 'zb' ),
	) );

	/*
	 * Switch default core markup for search form, comment form, and comments
	 * to output valid HTML5.
	 */
	add_theme_support( 'html5', array(
		'search-form',
		'comment-form',
		'comment-list',
		'gallery',
		'caption',
	) );

	// Set up the WordPress core custom background feature.
	add_theme_support( 'custom-background', apply_filters( 'zb_custom_background_args', array(
		'default-color' => 'ffffff',
		'default-image' => '',
	) ) );

}
endif; // zb_setup
add_action( 'after_setup_theme', 'zb_setup' );

function zb_imagelink_setup() {
	$image_set = get_option( 'image_default_link_type' );
	if ( $image_set !== 'none' ) {
		update_option( 'image_default_link_type', 'none' );
	}
}
add_action('admin_init', 'zb_imagelink_setup', 10);

/**
 * Set the content width in pixels, based on the theme's design and stylesheet.
 *
 * Priority 0 to make it available to lower priority callbacks.
 *
 * @global int $content_width
 */
function zb_content_width() {
	$GLOBALS['content_width'] = apply_filters( 'zb_content_width', 640 );
}
add_action( 'after_setup_theme', 'zb_content_width', 0 );

/**
 * Register widget area.
 *
 * @link http://codex.wordpress.org/Function_Reference/register_sidebar
 */
function zb_widgets_init() {
	register_sidebar( array(
		'name'          => esc_html__( 'Sidebar', 'zb' ),
		'id'            => 'sidebar-1',
		'description'   => '',
		'before_widget' => '<aside id="%1$s" class="widget %2$s">',
		'after_widget'  => '</aside>',
		'before_title'  => '<h2 class="widget-title">',
		'after_title'   => '</h2>',
	) );
}
add_action( 'widgets_init', 'zb_widgets_init' );

/**
 * Enqueue scripts and styles.
 */
function zb_scripts() {
	wp_enqueue_style(
		'zon-blogs-style',
		get_template_directory_uri() . '/style.css',
		array(),
		@filemtime( get_template_directory() . '/style.css' ),
		'screen'
	);

	wp_enqueue_style(
		'zon-blogs-style-print',
		get_template_directory_uri() . '/print.css',
		array(),
		@filemtime( get_template_directory() . '/print.css' ),
		'print'
	);

	wp_enqueue_script( 'zon-blogs-fitvids', get_template_directory_uri() . '/js/jquery.fitvids.js', array(), '20130101', true );

	wp_enqueue_script( 'zon-blogs-general-last', get_template_directory_uri() . '/js/general-last.js', array('jquery'), '20150813', true );

	if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
		wp_enqueue_script( 'comment-reply' );
	}
}
add_action( 'wp_enqueue_scripts', 'zb_scripts' );

/**
 * Implement the Custom Header feature.
 */
require get_template_directory() . '/inc/custom-header.php';

/**
 * Custom template tags for this theme.
 */
require get_template_directory() . '/inc/template-tags.php';

/**
 * Custom functions that act independently of the theme templates.
 */
require get_template_directory() . '/inc/extras.php';

/**
 * Customizer additions.
 */
require get_template_directory() . '/inc/customizer.php';

/**
 * Load Jetpack compatibility file.
 */
require get_template_directory() . '/inc/jetpack.php';

// force inline images not to link to attachment page
update_option('image_default_link_type','none');


/**
 * Custom template tags for this theme.
 *
 * Eventually, some of the functionality here could be replaced by core features.
 *
 * @package zb
 */

if ( ! function_exists( 'zb_paging_nav' ) ) :
	/**
	 * Display navigation to next/previous set of posts when applicable.
	 */
	function zb_paging_nav() {

		// Don't print empty markup if there's only one page.
		if ( $GLOBALS['wp_query']->max_num_pages < 2 ) {
			return;
		}



		$pages = '';
		$range = 1;

		$showitems = ($range * 2)+1;

		global $paged;
		if(empty($paged)) $paged = 1;

		if($pages == '') {
			global $wp_query;
			$pages = $wp_query->max_num_pages;

			if(!$pages)
			{
				$pages = 1;
			}
		}

		if ( 1 != $pages ) {

			$output = "";

			$output .= '<nav class="zb-pager">';


			$output .= '<p>';

			if ( $paged > 1 && $showitems < $pages) {
			 	$output .= '<a href="' . get_pagenum_link( $paged - 1 ) . '" class="zb-pager__btn zb-pager__btn-rel zb-pager__btn-rel-prev" title="zurück"><span class="zb-pager__btn-rel-prev-text" rel="prev">Vorherige Seite</span></a>';
			}


			$thepages = array();

			if($paged > 2 && $paged > $range+1 && $showitems < $pages) {
				$thepages[] = ' <a href="' . get_pagenum_link(1) . '" class="zb-pager__btn zb-pager__btn-abs zb-pager__btn-abs-first" title="1">1</a> ';

			}


			if($paged > 2 && $paged > $range+2 && $showitems < $pages) {
				$thepages[]  = ' <span class="zb-pager__placeholder">…</span> ';
			}



			for ($i=1; $i < $pages; $i++) {

				if (1 != $pages &&( !($i >= $paged+$range+1 || $i <= $paged-$range-1) || $pages <= $showitems )) {

					if ( $paged == $i ) {
						$thepages[] = ' <span class="zb-pager__btn zb-pager__btn-abs zb-pager__btn-is-active is-active">' . $i .'</span> ';
					} else {
						$diff = $i - $paged;
						$abs_class = '';
						if ( $diff > 0 ) {
							$abs_class = ' zb-pager__btn-abs-next-' . abs($diff);
						}
						else {
							$abs_class = ' zb-pager__btn-abs-prev-' . abs($diff);
						}
						$thepages[] = 	' <a href="' . get_pagenum_link( $i ) . '" class="zb-pager__btn zb-pager__btn-abs' . $abs_class . '" title="' . $i .'">' . $i .'</a>';
					}
				}
			}




			if ($paged < $pages-3 &&  $paged+$range-3 < $pages && $showitems < $pages) {
				$thepages[] = ' <span class="zb-pager__placeholder">…</span> ';
			}


			if ( $paged == $pages ) {
			    $thepages[] = '  <span class="zb-pager__btn zb-pager__btn-abs zb-pager__btn-is-active is-active">' . $i .'</span>';
			} else {
				$thepages[] = '  <a href="' . get_pagenum_link($pages) . '" class="zb-pager-btn zb-pager__btn-abs zb-pager__btn-abs-last" title="' . $pages . '">' . $pages . '</a> ';

			}

			$output .= implode(' <span class="zb-pager__devider">/</span> ', $thepages);

			if ($paged < $pages && $showitems < $pages) {
				$output .= ' <a href="' . get_pagenum_link($paged + 1) . '" class="zb-pager__btn zb-pager__btn-rel zb-pager__btn-rel-next" title="weiter"><span class="zb-pager__btn-rel-next-text" rel="next">Nächste Seite</span></a>';
			}

			$output .= '</p>';

			$output .= '</nav>';

			echo $output;
		}
	}
endif;




if ( ! function_exists( 'zb_cats_nav' ) ) :
	function zb_cats_nav() {
		$catselector = wp_dropdown_categories(array(
			'show_option_all' => __( 'Alle Kategorien', 'zb' ),
			'echo' => false
		));

		return $catselector . '<span class="cat-icon-helper">&nbsp;</span><span id="cat-width-helper"></span>';
	}

endif;

/**
 * Custom template tags for this theme.
 *
 * Eventually, some of the functionality here could be replaced by core features.
 *
 * @package zb
 */

if ( ! function_exists( 'zb_comments_nav' ) ) :
	/**
	 * Display navigation to next/previous set of posts when applicable.
	 */
	function zb_comments_nav($pages = 1, $paged = 1, $link_base = '' ) {

		// Don't print empty markup if there's only one page.
		if ( $pages < 2 ) {
			return;
		}

		$range = 1;

		$showitems = ($range)+1;


		if ( 1 != $pages ) {

			$output = "";

			$output .= '<nav class="zb-pager zb-comments-pager">';


			$output .= '<p>';

			if ( $paged > 1 && $showitems < $pages) {
			 	//$output .= '<a href="' . $link_base.( $paged - 1 ) . '" class="zb-pager__btn zb-pager__btn-rel zb-pager__btn-rel-prev" title="zurück"><span class="zb-pager__btn-rel-prev-text" rel="prev">'.__('Previous comments', 'zb').'</span></a>';
			}


			$thepages = array();

			if($paged > 2 && $paged > $range+1 && $showitems < $pages) {
				$thepages[] = ' <a href="' . $link_base.(1) . '#comments" class="zb-pager__btn zb-pager__btn-abs zb-pager__btn-abs-first" title="1">1</a> ';

			}


			if($paged > 2 && $paged > $range+2 && $showitems < $pages) {
				$thepages[]  = ' <span class="zb-pager__placeholder">…</span> ';
			}



			for ($i=1; $i < $pages; $i++) {

				if (1 != $pages &&( !($i >= $paged+$range+1 || $i <= $paged-$range-1) || $pages <= $showitems )) {

					if ( $paged == $i ) {
						$thepages[] = ' <span class="zb-pager__btn zb-pager__btn-abs zb-pager__btn-is-active is-active">' . $i .'</span> ';
					} else {
						$diff = $i - $paged;
						$abs_class = '';
						if ( $diff > 0 ) {
							$abs_class = ' zb-pager__btn-abs-next-' . abs($diff);
						}
						else {
							$abs_class = ' zb-pager__btn-abs-prev-' . abs($diff);
						}
						$thepages[] = 	' <a href="' . $link_base. ( $i ) . '#comments" class="zb-pager__btn zb-pager__btn-abs' . $abs_class . '" title="' . $i .'">' . $i .'</a>';
					}
				}
			}




			if ($paged < $pages-3 &&  $paged+$range-3 < $pages && $showitems < $pages) {
				$thepages[] = ' <span class="zb-pager__placeholder">…</span> ';
			}


			if ( $paged == $pages ) {
			    $thepages[] = '  <span class="zb-pager__btn zb-pager__btn-abs zb-pager__btn-is-active is-active">' . $i .'</span>';
			} else {
				$thepages[] = '  <a href="' . $link_base. ($pages) . '#comments" class="zb-pager-btn zb-pager__btn-abs zb-pager__btn-abs-last" title="' . $pages . '">' . $pages . '</a> ';

			}

			$output .= implode(' <span class="zb-pager__devider">/</span> ', $thepages);

			if ($paged < $pages && $showitems < $pages) {
				$output .= ' <a href="' . $link_base. ($paged + 1) . '#comments" class="zb-pager__btn zb-pager__btn-rel zb-pager__btn-rel-next" title="weiter"><span class="zb-pager__btn-rel-next-text" rel="next">'.__('Next comments', 'zb').'</span></a>';
			}

			$output .= '</p>';

			$output .= '</nav>';

			echo $output;
		}
	}
endif;

/*
 * Make a separate feed available
 * for ZON-Article XML under the URL
 * http://blog.zeit.de/[pathtoarticle]?feed=articlexml
 * The corresponding template can be found in the theme directory
 * in the file articlexml.php
 */
if ( ! function_exists( 'create_my_articlexml' ) ) :
	function create_my_articlexml() {
		load_template( TEMPLATEPATH . '/articlexml.php');
	}
	add_action('do_feed_articlexml', 'create_my_articlexml', 10, 1);
endif;


// repair defected article feed
function custom_feed_rewrite($wp_rewrite) {
	$feed_rules = array(
		"(.*)_([0-9]+)/feed/(feed|rdf|rss|rss2|atom|articlexml)/?$" => 'index.php?p='.$wp_rewrite->preg_index(2).'&feed='. $wp_rewrite->preg_index(3),
	);
	$wp_rewrite->rules = $feed_rules + $wp_rewrite->rules;
}
add_filter('generate_rewrite_rules', 'custom_feed_rewrite');


/**
 * Meta Box for custom fields
 */

function zb_get_meta( $value, $standard=false ) {
	global $post;

	$field = get_post_meta( $post->ID, $value, true );
	if ( ! empty( $field ) ) {
		return is_array( $field ) ? stripslashes_deep( $field ) : stripslashes( wp_kses_decode_entities( $field ) );
	} else {
		return $standard;
	}
}

if ( ! function_exists( 'zb_metabox_html' ) ) :
  function zb_add_meta_box() {
  	add_meta_box(
  		'zb-admanagement',
  		__( 'Ad Platzierung anpassen', 'zb' ),
  		'zb_metabox_html',
  		'post',
  		'side',
  		'default'
  	);
  }
  add_action( 'add_meta_boxes', 'zb_add_meta_box' );
endif;

if ( ! function_exists( 'zb_metabox_html' ) ) :
  function zb_metabox_html( $post) {
  	wp_nonce_field( '_zb_medium_rectangle_nonce', 'zb_medium_rectangle_nonce' );
  	?>
  	<p>
    	<input type="radio" name="zb_medium_rectangle" id="zb_paragraph_medium_rectangle" value="paragraph-medium-rectangle" <?php echo ( (zb_get_meta( 'zb_medium_rectangle' ) === 'paragraph-medium-rectangle') OR !zb_get_meta( 'zb_medium_rectangle' ) ) ? 'checked' : ''; ?>>
  		<label for="zb_paragraph_medium_rectangle">1. Ad nach dem <input type="number" min="1" name="zb_medium_rectangle_paragraph" id="zb_medium_rectangle_paragraph" value="<?php echo ( !zb_get_meta( 'zb_medium_rectangle_paragraph' ) ? '1' : zb_get_meta( 'zb_medium_rectangle_paragraph' ) ); ?>" style="width:40px;">. Absatz anzeigen.</label>
	</p>
	<p style="color:#777;">Standardmäßig wird ein Ad <strong>nach dem 1. Absatz</strong> angezeigt. Ein Absatz muss dabei mindestens <strong><?php echo get_option('zon_ads_paragraph_length', 200); ?></strong> Zeichen lang sein, kürzere werden zusammenaddiert. Ist der Artikel länger als fünf Absätze, wird ein weiteres Ad nach dem 4. Absatz angezeigt. Ein verschieben des Ads setzt die Minimalabsatzlänge außer Kraft und platziert das Ad nach dem auswähltem Absatz. Das zweite Ad verschiebt sich entsprechend.
	</p>
	<p>
  		<input type="radio" name="zb_medium_rectangle" id="zb_hide_medium_rectangle" value="hide-medium-rectangle" <?php echo ( (zb_get_meta( 'zb_medium_rectangle' ) === 'hide-medium-rectangle') OR (zb_get_meta( 'zb_hide_medium_rectangle' ) === 'hide-medium-rectangle') ) ? 'checked' : ''; ?>>
  		<label for="zb_hide_medium_rectangle"> Ads im Artikelbody ausblenden (nur nach Absprache).</label>
    </p>
  		<?php
  }
endif;

function zb_save( $post_id ) {
	if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) return;
	if ( ! isset( $_POST['zb_medium_rectangle_nonce'] ) || ! wp_verify_nonce( $_POST['zb_medium_rectangle_nonce'], '_zb_medium_rectangle_nonce' ) ) return;
	if ( ! current_user_can( 'edit_post', $post_id ) ) return;

	if ( isset( $_POST['zb_medium_rectangle'] ) )
		update_post_meta( $post_id, 'zb_medium_rectangle', esc_attr( $_POST['zb_medium_rectangle'] ) );
	else
		update_post_meta( $post_id, 'zb_medium_rectangle', null );

  if ( isset( $_POST['zb_medium_rectangle_paragraph'] ) )
		update_post_meta( $post_id, 'zb_medium_rectangle_paragraph', esc_attr( $_POST['zb_medium_rectangle_paragraph'] ) );
}
add_action( 'save_post', 'zb_save' );

if ( ! function_exists( 'zb_is_wrapped' ) ) {
	function zb_is_wrapped() {
		return strpos( $_SERVER['HTTP_USER_AGENT'], 'ZONApp' ) !== false;
	}
}

/*
 * Fügt im RSS-Feed als erstes <category-Tag den Blogtitel ein
 * Wird zur für die Darstellung der Blogs im RSS-Widget auf den
 * Politik CPs benutzt
 */
if ( ! function_exists( 'add_blogtitle_to_category' ) ) {
	function add_blogtitle_to_category( $content ) {
		$blog_title = '<category>'.get_bloginfo('name').'</category>'."\n";
		return $blog_title.$content;
	}
	add_filter( 'the_category_rss', 'add_blogtitle_to_category' );
}

/*
 * Add iframe to tiny mce (who ever uses this???)
 */
add_filter( 'tiny_mce_before_init',
	create_function( '$a',
	'$a["extended_valid_elements"] = "iframe[id|class|title|style|align|frameborder|height|longdesc|marginheight|marginwidth|name|scrolling|src|width]"; return $a;'
	)
);

/*
 * Add iframe and script to the list of allowed html
 * replace old kses, which also added object, param and embed
 * and a bunch of html5 tags, that we're eventually added by
 * wordpress itself
 */
if ( ! function_exists( 'zon_add_tags_to_kses' ) ) {
	function zon_add_tags_to_kses( $tags ) {
		$tags['iframe'] = array(
			'src' => array (),
			'width' => array (),
			'height' => array (),
			'scrolling' => array (),
			'marginheight' => array (),
			'marginwidth' => array (),
			'frameborder' => array ()
		);
		$tags['script'] = array(
			'src' => array (),
			'type' => array (),
			'version' => array (),
			'defer' => array (),
			'language' => array ()
		);
		return $tags;
	}
	add_filter( 'wp_kses_allowed_html', 'zon_add_tags_to_kses' );
}

// <!-- noformat on --> and <!-- noformat off --> functions
function newautop( $text )
{
    $newtext = "";
    $pos = 0;

    $tags = array( '<!-- noformat on -->', '<!-- noformat off -->' );
    $status = 0;

    while ( ! ( ( $newpos = strpos( $text, $tags[ $status ], $pos ) ) === FALSE ) )
    {
        $sub = substr( $text, $pos, $newpos-$pos );

        if ( $status ) {
            $newtext .= $sub;
		} else {
            $newtext .= convert_chars(wptexturize(wpautop($sub)));      //Apply both functions (faster)
		}

        $pos = $newpos + strlen( $tags[ $status ] );

        $status = $status ? 0 : 1;
    }

    $sub = substr( $text, $pos, strlen( $text ) - $pos );

    if ( $status ) {
		$newtext .= $sub;
	} else {
		$newtext .= convert_chars(wptexturize(wpautop($sub)));      //Apply both functions (faster)
	}

    //To remove the tags
    $newtext = str_replace( $tags[0], "", $newtext );
    $newtext = str_replace( $tags[1], "", $newtext );

    return $newtext;
}

function newtexturize( $text ) {
    return $text;
}

function new_convert_chars( $text ) {
    return $text;
}

remove_filter( 'the_content', 'wpautop' );
add_filter( 'the_content', 'newautop' );

remove_filter( 'the_content', 'wptexturize' );
add_filter( 'the_content', 'newtexturize' );

remove_filter( 'the_content', 'convert_chars' );
add_filter( 'the_content', 'new_convert_chars' );
