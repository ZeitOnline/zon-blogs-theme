<?php
/**
 * The template for displaying comments.
 *
 * The area of the page that contains both current comments
 * and the comment form.
 *
 * @package Zeit Online Blogs Twentyeighteen
 */

/*
 * If the current post is protected by a password and
 * the visitor has not yet entered the password we will
 * return early without loading the comments.
 */
if ( post_password_required() ) {
	return;
}

if ( have_comments() && (! zb_get_meta( 'zb_hide_medium_rectangle' ) && ( zb_get_meta( 'zb_medium_rectangle' ) != 'hide-medium-rectangle' ) ) ) {
		zb_render_ad( 'desktop', '5', 'ad-wrapper ad-container', 'article', 'Anzeige' );
}
?>
<div class="comments-wrapper">

	<header class="comments-header">
		<?php if ( have_comments() ) : ?>
			<h2 class="comments-title"><?php
				printf( // WPCS: XSS OK.
					esc_html( _nx( '1 Comment', '%1$s Comments', get_comments_number(), 'comments title', 'zb' ) ),
					number_format_i18n( get_comments_number() )
				);
			?></h2>

			<?php
				$comments_page = 1;
				if(   isset($_GET["comments_page"])
					&&  !empty($_GET["comments_page"])
					&&  is_numeric($_GET["comments_page"]))
				{
					$comments_page = intval($_GET["comments_page"]);
				}
				$comments_per_page = 10;
				$offset = $comments_per_page*($comments_page-1);
			?>

		<?php endif; // Check for have_comments(). ?>

		<div class="comments-button-wrapper"><a class="zb-button comments-button__show-form" href="#comment-form"><?php print esc_html__( 'Leave a comment', 'zb' ) ?> ▸</a></div>
	</header>

	<?php if ( have_comments() ) : ?>
		<div class="comments-optionbar">
			<?php if( isset($_GET["sort"]) && $_GET["sort"] == "desc" ): ?>
				<a href="?sort=asc#comments" class="comments-sort-oldest"><span class="zb-icon-sort-oldest"></span><?php esc_html_e( 'Oldest first', 'zb' ); ?></a>
				<?php $sort = 'desc'; ?>
			<?php else: ?>
				<a href="?sort=desc#comments" class="comments-sort-newest"><span class="zb-icon-sort-newest"></span><?php esc_html_e( 'Newest first', 'zb' ); ?></a>
				<?php $sort = 'asc'; ?>
			<?php endif; // isset sort ?>
		</div> <!-- .comments-optionbar -->

		<div id="comments" class="comments-area">
			<ol class="comment-list">
			<?php
				$comments = get_comments( array(
					'post_id' => $post->ID,
					'order' => $sort,
					'status' => 'approve',
					'number'=> $comments_per_page,
					"offset" => $offset,

					) );

				if( $sort != "desc" ):
					$i = 1;
					$counter_offset = $offset;
				else:
					$i = get_comments_number();
					$counter_offset = $offset*-1;
				endif;
				foreach( $comments as $comment ) {
					zb_comment( $comment, ($i+$counter_offset), $sort, get_comments_number(), $offset );
					if( $sort != "desc" ) {
						$i++;
					} else {
						$i--;
					}
				}
			?>
			</ol><!-- .comment-list -->

			<!--  Comments pager -->
			<?php
			$pages = ceil(get_comments_number()/$comments_per_page);
			global $wp;
			$link_base = explode("?", add_query_arg( $wp->query_string, '', home_url( $wp->request ) ) );
			$link_base = $link_base[0]."?sort=".$sort."&comments_page=";
			print zb_comments_nav($pages, $comments_page, $link_base);
			?>
			<!-- Comments pager END -->
		</div><!-- #comments -->

	<?php endif; // Check for have_comments(). ?>

	<a name="comment-form">&nbsp;</a>
	<div class="comments-form-wrapper"><?php
		 // If comments are closed and there are comments, let's leave a little note, shall we?
		if ( ! comments_open() && '0' != get_comments_number() && post_type_supports( get_post_type(), 'comments' ) ) : ?>
			<p class="no-comments"><?php esc_html_e( 'Comments are closed.', 'zb' ); ?></p>
		<?php endif;

	$commenter = wp_get_current_commenter();
	$req = get_option( 'require_name_email' );
	$aria_req = ( $req ? " aria-required='true'" : '' );

	$fields =  array(
		'author' =>
			'<p class="comment-form-author"><label for="author">' . __( 'Name', 'domainreference' ) . '</label> ' .
			( $req ? '<span class="required">*</span>' : '' ) .
			'<input id="author" name="author" type="text" value="' . esc_attr( $commenter['comment_author'] ) .
			'" size="30"' . $aria_req . ' /></p>',
		'email' =>
			'<p class="comment-form-email"><label for="email">' . __( 'Email', 'domainreference' ) . '</label> ' .
			( $req ? '<span class="required">*</span>' : '' ) .
			'<input id="email" name="email" type="text" value="' . esc_attr(  $commenter['comment_author_email'] ) .
			'" size="30"' . $aria_req . ' /></p>',
	);

	$sso_user_data = NULL;
	if ( function_exists( ' z_auth_decode_master_cookie' ) ) {
		$sso_user_data = z_auth_decode_master_cookie();
	}

	if ($user_ID) {
		comment_form( array('comment_notes_after' => '', 'label_submit' => 'Absenden' ) );
	} else if ( $sso_user_data ) {
		if ( !empty($sso_user_data->name) ) {
			$fields['author'] = '<input name="author" type="hidden" value="' . esc_attr($sso_user_data->name) . '" />';
		}
		if ( !empty($sso_user_data->email) ) {
			$fields['email'] = '<input name="email" type="hidden" value="' . esc_attr($sso_user_data->email) . '" />';
		}
		comment_form( array('fields'=>$fields, 'comment_notes_after' => '', 'label_submit' => 'Absenden' ) );
	}
	else//not logged in
	{
		$permlink = urlencode( get_permalink() );
		$html = <<<EOT
		<p>Bitte melden Sie sich an, um zu kommentieren.</p>
		<a class="button" href="https://meine.zeit.de/anmelden?$permlink&entry_service=blog_kommentare">Anmelden</a>
		<a class="button" href="https://meine.zeit.de/registrieren?url=$permlink&entry_service=blog_kommentare">Registrieren</a>
EOT;
		$fields = array(
			'author' => '',
			'email' => '',
			'url' => ''
		);
		$args = array(
			'action' => '',
			'id_form' => 'comment-login',
			'class_form' => 'comment-form',
			'logged_in_as' => '',
			'submit_button' => '',
			'title_reply' => '',
			'title_reply_to' => '',
			'comment_notes_before' => '',
			'must_log_in' => '',
			'comment_field' => $html,
			'fields' => $fields
		);
		comment_form( $args );
	}
?></div>
</div><!-- .comments-wrapper -->
