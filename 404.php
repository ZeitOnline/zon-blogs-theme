<?php
/**
 * The template for displaying 404 pages (not found).
 *
 * @package Zeit Online Blogs Twentyeighteen
 */

get_header(); ?>

	<div id="primary" class="content-area">
		<main id="main" class="site-main" role="main">

			<section class="error-404 not-found">
				<header class="page-header">
					<h1 class="page-title">Dokument nicht gefunden</h1>
				</header><!-- .page-header -->

				<div class="page-content">
					<p>Die von Ihnen eingegebene Internetadresse verweist auf keinen aktuellen Inhalt von ZEIT ONLINE.</p>
					<p><strong>Was können Sie tun?</strong></p>
					<ul>
            <li>Ältere Artikel finden Sie nach Jahr und Ausgabe sortiert im <a href="http://www.zeit.de/archiv/" title="ZEIT Online Archiv">Archiv</a>.</li>
            <li>Haben Sie Anmerkungen oder Anregungen? Dann können Sie uns über <a href="http://www.zeit.de/hilfe/hilfe/" title="ZHilfe">Email oder Twitter kontaktieren</a>.</li>
            <li>Sie können zur <a href="<?php echo trailingslashit( get_home_url() ); ?>">Startseite</a> zurückkehren.</li>
            <li>Sie können die Suche verwenden:</li>
					</ul>

					<?php get_search_form(); ?>

				</div><!-- .page-content -->
			</section><!-- .error-404 -->

		</main><!-- #main -->
	</div><!-- #primary -->

<?php get_sidebar(); ?>

<?php get_footer(); ?>
