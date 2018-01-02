<?php
/**
 * ZON Article XML Template
 * Version: 0.2
 * @package WordPress
 */
header('Content-Type: text/xml');

while( have_posts()) : the_post();

	global $blog_id;

	$w=new XMLWriter();
	$w->openMemory();
	$w->startDocument('1.0', 'UTF-8');

	$content_xml = apply_filters('the_content', get_the_content());
	$content_xml = str_replace("<br />", "", $content_xml);
	$content_xml = str_replace("<!-- google_ad_section_start -->\n", "", $content_xml);
	$content_xml = str_replace("<!-- google_ad_section_end -->\n", "", $content_xml);
	$content_xml = preg_replace('/<span id="more-\d+"><\/span>/', '', $content_xml, 1);
	$content_xml_decoded = html_entity_decode($content_xml, ENT_COMPAT, "UTF-8");


	$is_xml_wellformed = FALSE;

	if ( !isset($_GET['xml']) && $_GET['xml'] != 'debug') {
		libxml_use_internal_errors(true);
	}

	if (simplexml_load_string('<division type="page">'.$content_xml_decoded.'</division>')) {
		$w->writeComment("#Original content structure was used");
		$is_xml_wellformed = TRUE;
	} else {
		$w->writeComment("#Original content structure not well-formed, transformations were applied");
	}


	$w->startElement("article");
		$w->startElement("head");
			// $w->startElement("keywordset");
			// 	$w->writeElement("keyword", "ki-ki-content");
			// $w->endElement();// keywordset
			// el: references
			// el: image
			$w->startElement("attribute");
				$w->writeAttribute("ns", "http://namespaces.zeit.de/CMS/document");
				$w->writeAttribute("name", "author");
				$w->text(get_the_author());
			$w->endElement();// el: attribute

			// Ressorts
			$main_ressort = get_option('zon_ressort_main');
			$sub_ressort = get_option('zon_ressort_sub');

			$w->startElement("attribute");
				$w->writeAttribute("ns", "http://namespaces.zeit.de/CMS/document");
				$w->writeAttribute("name", "ressort");
				$w->text(ucfirst($main_ressort));
			$w->endElement();// el: attribute

			if ($sub_ressort) {
				$w->startElement("attribute");
					$w->writeAttribute("ns", "http://namespaces.zeit.de/CMS/document");
					$w->writeAttribute("name", "subressort");
					$w->text(ucfirst($sub_ressort));
				$w->endElement();// el: attribute
			}



			$w->startElement("attribute");
				$w->writeAttribute("ns", "http://namespaces.zeit.de/CMS/document");
				$w->writeAttribute("name", "date_first_released");
				$w->text( get_post_time('c', true) );
			$w->endElement();// el: attribute

			$w->startElement("attribute");
				$w->writeAttribute("ns", "http://namespaces.zeit.de/CMS/document");
				$w->writeAttribute("name", "date-last-modified");
				$w->text( get_post_modified_time('c', true) );
			$w->endElement();// el: attribute

			$w->startElement("attribute");
				$w->writeAttribute("ns", "http://namespaces.zeit.de/CMS/document");
				$w->writeAttribute("name", "last-semantic-change");
				$w->text( get_post_modified_time('c', true) );
			$w->endElement();// el: attribute



			$w->startElement("attribute");
				$w->writeAttribute("ns", "http://namespaces.zeit.de/CMS/meta");
				$w->writeAttribute("name", "type");
				$w->text("blogpost");
			$w->endElement();// el: attribute
			$w->startElement("attribute");
				$w->writeAttribute("ns", "http://namespaces.zeit.de/CMS/workflow");
				$w->writeAttribute("name", "product-id");
				$w->text("ZBL".$blog_id);
			$w->endElement();// el: attribute
			$w->startElement("attribute");
				$w->writeAttribute("ns", "http://namespaces.zeit.de/CMS/workflow");
				$w->writeAttribute("name", "product-name");
				$w->text("ZEIT ONLINE");
			$w->endElement();// el: attribute
			$w->startElement("attribute");
				$w->writeAttribute("ns", "http://namespaces.zeit.de/CMS/document");
				$w->writeAttribute("name", "uuid");
				$w->text($blog_id."-".get_the_ID());
			$w->endElement(); // "attribute"

		$w->endElement(); //head

		$w->startElement("body");
			$w->writeElement("supertitle", 'Blog: '.get_bloginfo('name'));
			$w->startElement("title");
				$w->writeRaw(get_the_title());
			$w->endElement();
			$w->writeElement("byline", "Von ".get_the_author());

			if ($is_xml_wellformed) {
				$w->writeRaw($content_xml_decoded);
			} else {
				$w->startElement("division");
					$w->writeAttribute("type", "page");
					$w->writeCData($content_xml);
				$w->endElement(); // "division"
			}
				// $content_xml = strip_tags($content_xml, '<p>');
				// if (!simplexml_load_string('<division type="page">'.$content_xml.'</division>')) {
				// 	$content_xml = strip_tags($content_xml);
				// }

		$w->endElement(); //"body"


	$w->endElement(); //article

	echo $w->outputMemory(true);

endwhile;

?>