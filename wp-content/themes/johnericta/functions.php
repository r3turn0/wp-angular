<?php 
// Start functions.php

add_action( 'after_setup_theme', 'johnericta_setup' );
function johnericta_setup() {
	load_theme_textdomain( 'johnericta', get_template_directory() . '/languages' );
	add_theme_support( 'title-tag' );
	//add_theme_support( 'automatic-feed-links' );
	add_theme_support( 'post-thumbnails' );
	set_post_thumbnail_size( 840, 0 );
	add_image_size( 'landscape', 560, 420, true );
	add_image_size( 'portrait', 480, 640, true );
	add_image_size( 'square', 480, 480, true );
	/*global $content_width;
	if ( ! isset( $content_width ) ) $content_width = 640;
		register_nav_menus(
		array( 'main_menu' => __( 'Main Menu', 'johnericta navigation' ) )
	);*/
}

/* -------------- QUEUE SCRIPTS -------------- */
//add_action( 'wp_enqueue_scripts', 'johnericta_load_scripts' );
//function johnericta_load_scripts()
//{
	/* ------------ CSS ------------ */
	//wp_enqueue_style( 'font', '//fonts.googleapis.com/css?family=Titillium Web:300,400|Fira Sans:400,600');
	//wp_enqueue_style( 'johnericta-css',  get_template_directory_uri() . '/css/johnericta.css');
	/* ------------ SCRIPTS -------------- */
	// Queue site script after loading array dependencies in Wordpress CORE. List of Built in pacakages can be found here:
	// https://developer.wordpress.org/reference/functions/wp_enqueue_script/
	//wp_enqueue_script('johnericta-js', get_template_directory_uri() .'/js/johnericta.js', array('jquery', 'jquery-ui-core'));
//}

/*add_action( 'comment_form_before', 'johnericta_enqueue_comment_reply_script' );
function johnericta_enqueue_comment_reply_script() {
	if ( get_option( 'thread_comments' ) ) { wp_enqueue_script( 'comment-reply' ); }
}*/

add_filter( 'the_title', 'johnericta_title' );
function johnericta_title( $title ) {
	if ( $title == '' ) {
		return '&rarr;';
	} else {
		return $title;
	}
}

add_filter( 'wp_title', 'johnericta_filter_wp_title' );
function johnericta_filter_wp_title( $title ) {
	return $title . esc_attr( get_bloginfo( 'name' ) );
}

// Add filter for title separator
function change_title_separator(){
	return ' | ';
}
add_filter('document_title_separator', 'change_title_separator');

/*add_action( 'widgets_init', 'johnericta_widgets_init' );
function johnericta_widgets_init() {
	register_sidebar( array (
		'name' => __( 'Sidebar Widget Area', 'johnericta' ),
		'id' => 'primary-widget-area',
		'before_widget' => '<li id="%1$s" class="widget-container %2$s">',
		'after_widget' => "</li>",
		'before_title' => '<h3 class="widget-title" style="display: none;">',
		'after_title' => '</h3>',
	) );
}*/

/*function johnericta_custom_pings( $comment ) {
	$GLOBALS['comment'] = $comment;
	?>
	<li <?php comment_class(); ?> id="li-comment-<?php comment_ID(); ?>"><?php echo comment_author_link(); ?></li>
	<?php 
}*/

/*add_filter( 'get_comments_number', 'johnericta_comments_number' );
function johnericta_comments_number( $count ) {
	if ( !is_admin() ) {
		global $id;
		$comments_by_type = &separate_comments( get_comments( 'status=approve&post_id=' . $id ) );
		return count( $comments_by_type['comment'] );
	} else {
		return $count;
	}
}*/

/* ------------- Show all categories ------------ */
add_filter( 'widget_categories_args', 'wpb_force_empty_cats' );
function wpb_force_empty_cats($cat_args) {
    $cat_args['hide_empty'] = 0;
    return $cat_args;
}

/* ------------ HEADER IMAGE ------------ 
$headerDef = array(
	'flex-width'    => true,
	'flex-height'   => true,
	'header-text'   => true,
	'default-image' => get_template_directory_uri() . '/images/header.jpg',
);
add_theme_support( 'custom-header', $headerDef );*/

// get images
function get_thumbnail_url($post){
    if(has_post_thumbnail($post['id'])){
        $imgArray = wp_get_attachment_image_src( get_post_thumbnail_id( $post['id'] ), 'full' ); // replace 'full' with 'thumbnail' to get a thumbnail
        $imgURL = $imgArray[0];
        return $imgURL;
    } else {
        return false;
    }
}

function add_site_settings() {
	$title = esc_attr( get_bloginfo( 'name' ) );
	$description = esc_attr( get_bloginfo( 'description' ) );
	$settings = array(
		'title' => $title,
		'description' => $description
	);
	return $settings;
}

function insert_post_thumbnail_url(){
	register_rest_field( 'post',
		'featured_image',  //key-name in json response
		array(
		'get_callback'    => 'get_thumbnail_url',
		'update_callback' => null,
		'schema'          => null,
		)
	);
}

function insert_page_thumbnail_url(){
	register_rest_field( 'page',
		'featured_image',  //key-name in json response
		array(
		'get_callback'    => 'get_thumbnail_url',
		'update_callback' => null,
		'schema'          => null,
		)
	);
}

// register actions for WP REST API
add_action( 'rest_api_init', 'insert_post_thumbnail_url' );
add_action( 'rest_api_init', 'insert_page_thumbnail_url' );
add_action( 'rest_api_init', function () {
	register_rest_route( 'wp/v2', '/site-settings', array(
		'methods' => 'GET',
		'callback' => 'add_site_settings',
	) );
} );

// End functions.php 
?>