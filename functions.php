<?php

// metaboxes
include(STYLESHEETPATH . '/inc/metaboxes/metaboxes.php');

include(STYLESHEETPATH . '/inc/category-feeds-widget.php');

include(STYLESHEETPATH . '/inc/advanced-navigation.php');

include(STYLESHEETPATH . '/inc/jeo-post-zoom.php');

// infocongo setup

// register taxonomies
include(STYLESHEETPATH . '/inc/taxonomies.php');
// taxonomy meta
include(STYLESHEETPATH . '/inc/taxonomies-meta.php');

/*
 * Advanced Custom Fields
 */

if(!class_exists('Acf')) {

	function infocongo_acf_dir() {
		return get_stylesheet_directory_uri() . '/inc/acf/';
	}
	add_filter('acf/helpers/get_dir', 'infocongo_acf_dir');

}

function infocongo_acf_date_time_picker_dir() {
	return infocongo_acf_dir() . '/add-ons/acf-field-date-time-picker/';
}
add_filter('acf/add-ons/date-time-picker/get_dir', 'infocongo_acf_date_time_picker_dir');

function infocongo_acf_repeater_dir() {
	return infocongo_acf_dir() . '/add-ons/acf-repeater/';
}
add_filter('acf/add-ons/repeater/get_dir', 'infocongo_acf_repeater_dir');

if(!class_exists('Acf')) {
	define('ACF_LITE', true);
	require_once(STYLESHEETPATH . '/inc/acf/acf.php');
}
include_once(STYLESHEETPATH . '/inc/acf/add-ons/acf-qtranslate/acf-qtranslate.php');

/*
 * Slider
 */
include(STYLESHEETPATH . '/inc/slider.php');

/*
 * Datasets
 */
include(STYLESHEETPATH . '/inc/datasets.php');

/*
 * Projects
 */
include(STYLESHEETPATH . '/inc/projects.php');

/*
 * Blog
 */
include(STYLESHEETPATH . '/inc/blog.php');

function infocongo_setup() {

	add_theme_support('post-thumbnails');
	add_image_size('post-thumb', 360, 121, true);
	add_image_size('project-thumb', 560, 206, true);
	add_image_size('map-thumb', 200, 200, true);

	// text domain
	load_child_theme_textdomain('infocongo', get_stylesheet_directory() . '/languages');

	//sidebars
	register_sidebar(array(
		'name' => __('Main widgets', 'infocongo'),
		'id' => 'main-sidebar',
		'description' => __('Widgets used on front and inside pages.', 'infocongo'),
		'before_widget' => '<div class="four columns row">',
		'after_widget' => '</div>',
		'before_title' => '<h3>',
		'after_title' => '</h3>'
	));

}
add_action('after_setup_theme', 'infocongo_setup');

// set OSM geocode
function infocongo_geocode_service() {
	return 'osm';
}
add_filter('jeo_geocode_service', 'infocongo_geocode_service');

function infocongo_scripts() {
	/*
	 * Register scripts & styles
	 */

	// deregister jeo styles
	wp_deregister_style('jeo-main');

	/* Shadowbox */
	wp_register_script('shadowbox', get_stylesheet_directory_uri() . '/lib/shadowbox/shadowbox.js', array('jquery'), '3.0.3');
	wp_register_style('shadowbox', get_stylesheet_directory_uri() . '/lib/shadowbox/shadowbox.css', array(), '3.0.3');

	/* Chosen */
	wp_register_script('chosen', get_stylesheet_directory_uri() . '/lib/chosen.jquery.min.js', array('jquery'), '1.0.0');

	// Momentjs
	wp_register_script('moment-js', get_stylesheet_directory_uri() . '/lib/moment.min.js');

	// scripts
	wp_register_script('html5', get_stylesheet_directory_uri() . '/js/html5shiv.js', array(), '3.6.2');
	wp_register_script('submit-story', get_stylesheet_directory_uri() . '/js/submit-story.js', array('jquery'), '0.1.1');

	wp_register_script('twttr', 'http://platform.twitter.com/widgets.js');

	$lang = '';
	if(function_exists('qtrans_getLanguage')) {
		$lang = qtrans_getLanguage();
	}

	// custom marker system
	global $jeo_markers;
	wp_deregister_script('jeo.markers');
	wp_register_script('jeo.markers', get_stylesheet_directory_uri() . '/js/infocongo.markers.js', array('jeo', 'underscore', 'shadowbox', 'twttr'), '0.3.17', true);
	wp_localize_script('jeo.markers', 'infocongo_markers', array(
		'ajaxurl' => admin_url('admin-ajax.php'),
		'query' => $jeo_markers->query(),
		'stories_label' => __('stories', 'infocongo'),
		'home' => (is_home() && !is_paged() && !$_REQUEST['infocongo_filter_']),
		'copy_embed_label' => __('Copy the embed code', 'infocongo'),
		'share_label' => __('Share', 'infocongo'),
		'print_label' => __('Print', 'infocongo'),
		'embed_base_url' => home_url('/' . $lang . '/embed/'),
		'share_base_url' => home_url('/' . $lang . '/share/'),
		'marker_active' => array(
			'iconUrl' => get_stylesheet_directory_uri() . '/img/marker_active.png',
			'iconSize' => array(26, 30),
			'iconAnchor' => array(13, 30),
			'popupAnchor' => array(0, -40),
			'markerId' => 'none'
		),
		'language' => $lang,
		'site_url' => home_url('/'),
		'read_more_label' => __('Read more', 'infocongo'),
		'lightbox_label' => array(
			'slideshow' => __('Open slideshow', 'infocongo'),
			'videos' => __('Watch video gallery', 'infocongo'),
			'video' => __('Watch video', 'infocongo'),
			'images' => __('View image gallery', 'infocongo'),
			'image' => __('View fullscreen image', 'infocongo'),
			'infographic' => __('View infographic', 'infocongo'),
			'infographics' => __('View infographics', 'infocongo')
		),
		'enable_clustering' => jeo_use_clustering() ? true : false,
		'default_icon' => jeo_formatted_default_marker()
	));

	if(is_home())
		wp_enqueue_script('infocongo-sticky', get_stylesheet_directory_uri() . '/js/sticky-posts.js', array('jeo.markers', 'jquery'), '0.1.2');

	// styles
	wp_register_style('site', get_stylesheet_directory_uri() . '/css/site.css', array(), '1.1'); // old styles
	wp_register_style('reset', get_stylesheet_directory_uri() . '/css/reset.css', array(), '2.0');
	wp_register_style('main', get_stylesheet_directory_uri() . '/css/main.css', array('jeo-skeleton', 'jeo-base', 'jeo-lsf'), '3.0');

	/*
	 * Enqueue scripts & styles
	 */
	// scripts
	wp_enqueue_script('html5');
	wp_enqueue_script('submit-story');
	// styles
	wp_enqueue_style('site');
	//wp_enqueue_style('reset');
	wp_enqueue_style('webfont-lato', 'http://fonts.googleapis.com/css?family=Lato:900');
	wp_enqueue_style('main');
	wp_enqueue_style('shadowbox');

	wp_localize_script('submit-story', 'infocongo_submit', array(
		'ajaxurl' => admin_url('admin-ajax.php'),
		'success_label' => __('Success! Thank you, your story will be reviewed by one of our editors and soon will be online.', 'infocongo'),
		'redirect_label' => __('You\'re being redirect to the home page in 4 seconds.', 'infocongo'),
		'home' => home_url('/'),
		'error_label' => __('Oops, please try again in a few minutes.', 'infocongo')
	));

	wp_enqueue_script('infocongo-print', get_stylesheet_directory_uri() . '/js/infocongo.print.js', array('jquery', 'imagesloaded'));


	wp_register_script('sly', get_stylesheet_directory_uri() . '/lib/sly.min.js', array('jquery'));
	wp_enqueue_script('infocongo-site', get_stylesheet_directory_uri() . '/js/site.js', array('jquery','sly'));

	if(is_page_template('page-institutional.php')) {
		wp_register_script('snapsvg', get_stylesheet_directory_uri() . '/js/snap.svg-min.js');
		wp_enqueue_script('svg-animations', get_stylesheet_directory_uri() . '/js/svganimations.js', array('jquery', 'snapsvg'));
	}


}
add_action('wp_enqueue_scripts', 'infocongo_scripts', 100);

function infocongo_enqueue_marker_script() {
	wp_enqueue_script('infocongo.markers');
}
add_action('wp_footer', 'infocongo_enqueue_marker_script');

function infocongo_map_data($data, $map) {
	$map_data = get_post_meta($map->ID, 'map_data', true);
	$layers = $map_data['layers'];
	foreach($layers as &$layer) {
		$layer['title'] = __($layer['title']);
	}
	$data['layers'] = $layers;
	return $data;
}
add_filter('jeo_map_data', 'infocongo_map_data', 10, 2);

// slideshow
include(STYLESHEETPATH . '/inc/slideshow.php');

// ajax calendar
include(STYLESHEETPATH . '/inc/ajax-calendar.php');

// story fragment title
add_filter('wp_title', 'infocongo_story_fragment_title', 10, 2);
function infocongo_story_fragment_title($title, $sep) {
	if(isset($_GET['_escaped_fragment_'])) {
		$args = substr($_GET['_escaped_fragment_'], 1);
		parse_str($args, $query);
		if(isset($query['story'])) {
			$title = get_the_title(substr($query['story'], 9));
			return $title . ' ' . $sep . ' ';
		}
	}
	return $title;
}

// add qtrans filter to get_permalink
if(function_exists('qtrans_convertURL'))
	add_filter('post_type_link', 'qtrans_convertURL');

function infocongo_gfw_markers_data($data, $query) {
	if(isset($_GET['gfw'])) {

		$gfw_features = array();

		foreach($data['features'] as $feature) {

			$gfw_feature = $feature;
			$properties = $feature['properties'];

			$date = explode('/', $properties['date']);
			$date = $date[2] . '-' . $date[0] . '-' . $date[1];

			$gfw_feature['properties'] = array(
				'title' => $properties['title'],
				'description' => $properties['content'],
				'loc' => $properties['url'],
				'published' => $date,
				'thumbnail' => $properties['thumbnail'],
				'author' => $properties['source']
			);

			$gfw_features[] = $gfw_feature;

		}

		$data['features'] = $gfw_features;

	}
	return $data;
}
add_filter('jeo_markers_data', 'infocongo_gfw_markers_data', 100, 2);

function infocongo_gfw_cache_key($cache_key) {
	if(isset($_GET['gfw'])) {
		$cache_key .= '_gfw';
	}
	return $cache_key;
}
add_filter('jeo_markers_cache_key', 'infocongo_gfw_cache_key', 100);


// custom marker data
function infocongo_marker_data($data, $post) {
	global $post;

	$permalink = $data['url'];

	if(function_exists('qtrans_getLanguage'))
		$permalink = add_query_arg(array('lang' => qtrans_getLanguage()), $permalink);

	$data['permalink'] = $permalink;
	$data['url'] = $permalink;
	$data['content'] = get_the_excerpt();
	$data['slideshow'] = infocongo_get_content_media();
	if(get_post_meta($post->ID, 'geocode_zoom', true))
		$data['zoom'] = get_post_meta($post->ID, 'geocode_zoom', true);

	// source
	$publishers = get_the_terms($post->ID, 'publisher');
	if($publishers) {
		$publisher = array_shift($publishers);
		$data['source'] = apply_filters('single_cat_title', $publisher->name);
	}
	// thumbnail
	$data['thumbnail'] = infocongo_get_thumbnail();

	return $data;
}
add_filter('jeo_marker_data', 'infocongo_marker_data', 10, 2);

function infocongo_get_thumbnail($post_id = false) {
	global $post;
	$post_id = $post_id ? $post_id : $post->ID;
	$thumb_src = wp_get_attachment_image_src(get_post_thumbnail_id(), 'post-thumb');
	if($thumb_src)
		return $thumb_src[0];
	else
		return get_post_meta($post->ID, 'picture', true);
}

// geocode box
include(STYLESHEETPATH . '/inc/geocode-box.php');

// submit story
include(STYLESHEETPATH . '/inc/submit-story.php');

// import geojson
//include(STYLESHEETPATH . '/inc/import-geojson.php');

// remove page from search result

function infocongo_remove_page_from_search($query) {
	if($query->is_search) {
		$query->set('post_type', 'post');
	}
	return $query;
}
add_filter('pre_get_posts', 'infocongo_remove_page_from_search');

function infocongo_all_markers_if_none($posts, $query) {
	if(empty($posts))
		$posts = get_posts(array('post_type' => 'post', 'posts_per_page' => 100));
	return $posts;
}
//add_filter('jeo_the_markers', 'infocongo_all_markers_if_none', 10, 2);

// multilanguage publishers
add_action('publisher_add_form', 'qtrans_modifyTermFormFor');
add_action('publisher_edit_form', 'qtrans_modifyTermFormFor');

// limit markers per page
function infocongo_markers_limit() {
	return 100;
}
add_filter('jeo_markers_limit', 'infocongo_markers_limit');

// flush w3tc on save_post
function infocongo_flush_w3tc() {
	if(function_exists('flush_pgcache')) {
		flush_pgcache();
	}
}
add_action('save_post', 'infocongo_flush_w3tc');

// disable sidebar on single map
function infocongo_story_sidebar($conf) {
	if(is_singular('post')) {
		$conf['disableSidebar'] = true;
	}
	return $conf;
}
add_filter('jeo_map_conf', 'infocongo_story_sidebar');
add_filter('jeo_mapgroup_conf', 'infocongo_story_sidebar');

// search placeholder
function infocongo_search_placeholder() {
	global $wp_the_query;
	$placeholder = __('Search for stories', 'infocongo');
	if($wp_the_query->is_singular(array('map', 'map-group')))
		$placeholder = __('Search for stories on this map', 'infocongo');
	elseif($wp_the_query->is_tax('publisher'))
		$placeholder = __('Search for stories on this publisher', 'infocongo');

	return $placeholder;
}

// embed custom stuff

function infocongo_before_embed() {
	remove_action('wp_footer', 'infocongo_submit');
	remove_action('wp_footer', 'infocongo_geocode_box');
}
add_action('jeo_before_embed', 'infocongo_before_embed');

function infocongo_embed_type($post_types) {
	if(get_query_var('embed')) {
		$post_types = 'map';
	}
	return $post_types;
}
add_filter('jeo_featured_map_type', 'infocongo_embed_type');



// twitter card

function infocongo_share_meta() {

	if(is_singular('post')) {
		$image = jeo_get_mapbox_image(false, 435, 375, jeo_get_marker_latitude(), jeo_get_marker_longitude(), 7);
	} elseif(is_singular('map')) {
		$image = jeo_get_mapbox_image(false, 435, 375);
	} elseif(isset($_GET['_escaped_fragment_'])) {

		$fragment = $_GET['_escaped_fragment_'];

		$vars = str_replace('/', '', $fragment);
		$vars = explode('%26', $vars);

		$query = array();
		foreach($vars as $var) {
			$keyval = explode('=', $var);
			if($keyval[0] == 'story') {
				$post_id = explode('post-', $keyval[1]);
				$query[$keyval[0]] = $post_id[1];
				continue;
			}
			if($keyval[0] == 'loc') {
				$loc = explode(',', $keyval[1]);
				$query['lat'] = $loc[0];
				$query['lng'] = $loc[1];
				$query['zoom'] = $loc[2];
				continue;
			}
			$query[$keyval[0]] = $keyval[1];
		}

		if($query['story']) {
			global $post;
			setup_postdata(get_post($query['story']));
		}

		if(isset($query['map'])) {
			$map_id = $query['map'];
		}

		if($query['lat'] && $query['lng'] && $query['zoom']) {
			$lat = $query['lat'];
			$lng = $query['lng'];
			$zoom = $query['zoom'];
		}

		$image = jeo_get_mapbox_image($map_id, 435, 375, $lat, $lng, $zoom);

	}

	?>
	<meta name="twitter:card" content="summary_large_image" />
	<meta name='twitter:site' content="@infocongo" />
	<meta name="twitter:url" content="<?php the_permalink(); ?>" />
	<meta name="twitter:title" content="<?php the_title(); ?>" />
	<meta name="twitter:description" content="<?php the_excerpt(); ?>" />

	<meta property="og:title" content="<?php the_title(); ?>" />
	<meta property="og:description" content="<?php the_excerpt(); ?>" />
	<meta property="og:image" content="<?php echo $image; ?>" />

	<?php

	if($query['story'])
		wp_reset_postdata();

}
add_action('wp_head', 'infocongo_share_meta');

/*
 * Geojson keys according to language (qTranslate fix)
 */

function infocongo_geojson_key($key) {
	if(function_exists('qtrans_getLanguage'))
		$key = '_ia_geojson_' . qtrans_getLanguage();

	return $key;
}
add_filter('jeo_markers_geojson_key', 'infocongo_geojson_key');

function infocongo_geojson_keys($keys) {
	if(function_exists('qtrans_getLanguage')) {
		global $q_config;
		$keys = array();
		foreach($q_config['enabled_languages'] as $lang) {
			$keys[] = '_ia_geojson_' . $lang;
		}
	}
	return $keys;
}
add_filter('jeo_markers_geojson_keys', 'infocongo_geojson_keys');

function infocongo_flush_rewrite() {
	global $pagenow;
	if(is_admin() && $_REQUEST['activated'] && $pagenow == 'themes.php') {
		global $wp_rewrite;
		$wp_rewrite->init();
		$wp_rewrite->flush_rules();
	}
}
add_action('jeo_init', 'infocongo_flush_rewrite');

function infocongo_convert_url($url) {
	if(function_exists('qtrans_convertURL'))
		$url = qtrans_convertURL($url);

	$pos = strpos($url, '?');
	if($pos === false)
		$url .= '?';

	return $url;
}
add_filter('jeo_embed_url', 'infocongo_convert_url');
add_filter('jeo_share_url', 'infocongo_convert_url');

function infocongo_embed_query($query) {
	if(get_query_var('jeo_map_embed')) {
		if($query->get('p') || $query->get('tax_query')) {
			error_log($query->get('p'));
			$query->set('without_map_query', 1);
		}
	}
}
add_action('pre_get_posts', 'infocongo_embed_query');

function infocongo_ignore_sticky($query) {
	if($query->is_main_query()) {
		$query->set('ignore_sticky_posts', true);
	}
}
add_action('pre_get_posts', 'infocongo_ignore_sticky');

/*
 * CUSTOM IMPLEMENTATION OF WP_DATE_QUERY
 */

if(!class_exists('WP_Date_Query')) {

	require(STYLESHEETPATH . '/inc/date.php');
	add_filter('query_vars', 'infocongo_date_query_var');
	add_filter('posts_clauses', 'infocongo_date_query_clauses', 10, 2);

}

function infocongo_date_query_var($vars) {
	$vars[] = 'date_query';
	return $vars;
}

function infocongo_date_query_clauses($clauses, $query) {

	if($query->get('date_query')) {
		$date_query = new WP_Date_Query($query->get('date_query'));
		$clauses['where'] .= $date_query->get_sql();
	}
	return $clauses;
}

function infocongo_home_url($path = '') {
	if(function_exists('qtrans_convertURL'))
		return qtrans_convertURL(home_url($path));
	else
		return home_url($path);
}

// convert local URLs in custom menu items
function qtrans_menuitem( $menu_item ) {
	if ($menu_item->type == 'custom' && stripos($menu_item->url, get_site_url()) !== false){
		$menu_item->url = qtrans_convertURL($menu_item->url);
	}
	return $menu_item;
}

add_filter('wp_setup_nav_menu_item', 'qtrans_menuitem', 0);

// do not use map query on front page

function infocongo_home_query($query) {
	if($query->is_main_query() && $query->is_home) {
		$query->set('without_map_query', 1);
	}
}
add_action('pre_get_posts', 'infocongo_home_query');

function infocongo_disable_share_a_map_nav() {
	return true;
}
//add_filter('jeo_disable_share_map_menu', 'infocongo_disable_share_a_map_nav');