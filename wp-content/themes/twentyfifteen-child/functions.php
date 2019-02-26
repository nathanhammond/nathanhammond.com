<?php
/**
 * Repair the styles for Crayon.
 */
function my_theme_enqueue_styles() {
	wp_enqueue_style( 'parent-style', get_template_directory_uri() . '/style.css' );
}
add_action( 'wp_enqueue_scripts', 'my_theme_enqueue_styles' );

/**
 * Add in the fonts for the site:
 * - .ultralightext
 * - .lightext
 */
function add_logo_fonts() {
	echo '<script type="text/javascript" src="https://fast.fonts.net/jsapi/f8cd41e5-4561-41b2-a213-3722142ffac8.js"></script>';
}
add_action( 'wp_head', 'add_logo_fonts' );

/**
 * 1. Copy the upstream function into the theme.
 * 2. Run that exact function as a filter with the required changes.
 * 3. Don't rerun the filter.
 */
function get_the_category_rss_extraction($the_list, $type) {
	if ( empty($type) )
		$type = get_default_feed();
	$tags = get_the_tags();
	$the_list = '';
	$cat_names = array();

	$filter = 'rss';
	if ( 'atom' == $type )
		$filter = 'raw';

	if ( !empty($tags) ) foreach ( (array) $tags as $tag ) {
		$cat_names[] = sanitize_term_field('name', $tag->name, $tag->term_id, 'post_tag', $filter);
	}

	$cat_names = array_unique($cat_names);

	foreach ( $cat_names as $cat_name ) {
		if ( 'rdf' == $type )
			$the_list .= "\t\t<dc:subject><![CDATA[$cat_name]]></dc:subject>\n";
		elseif ( 'atom' == $type )
			$the_list .= sprintf( '<category scheme="%1$s" term="%2$s" />', esc_attr( get_bloginfo_rss( 'url' ) ), esc_attr( $cat_name ) );
		else
			$the_list .= "\t\t<category><![CDATA[" . @html_entity_decode( $cat_name, ENT_COMPAT, get_option('blog_charset') ) . "]]></category>\n";
	}

	return $the_list;
}
add_filter('the_category_rss', 'get_the_category_rss_extraction', 10, 2);
?>