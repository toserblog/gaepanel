<?php

/*------------------------------------------------------------------

TABLE OF CONTENTS

1 - Theme Active Action
2 - Theme Install Action
3 - Theme Uninstall Action
4 - Add WP Dashboard
5 - Add WP Admin styles
6 - Make Admin Notice
7 - Gae Lock Functions
8 - LOAD OTHER ADMIN FILES
9 - Create Panel Options
10 - LOAD TEMPLATE FILES

------------------------------------------------------------------*/





/*------------------------------------------------------------------
	1 - Theme Active Inits
------------------------------------------------------------------*/

global $pagenow;
if ( $pagenow == 'themes.php' && isset($_GET['activated'] ) )
	wp_redirect( admin_url('admin.php?page=gae_options') );
function theme_active_init_admin(){
	add_action( 'admin_init', 'theme_install_init' );
	add_action( 'switch_theme', 'theme_uninstall_init' );
	//add_action( 'wp_dashboard_setup', 'add_admin_dashboard' );
	add_action( 'admin_head', 'add_admin_style' );
	add_action( 'admin_notices','add_admin_notice' );
}
add_action( 'after_setup_theme', 'theme_active_init_admin' );






/*------------------------------------------------------------------
	2 - Theme Install Action
------------------------------------------------------------------*/

function theme_install_init(){
	global $sidebars_widgets;
	wp_set_sidebars_widgets($sidebars_widgets);
	if ( !get_option('theme_notice') )
		update_option( 'theme_notice', theme_initialize_init() );
}





/*------------------------------------------------------------------
	3 - Theme Uninstall Action
------------------------------------------------------------------*/

function theme_uninstall_init(){
    global $shortname;
	delete_option( 'theme_notice' );
}





/*------------------------------------------------------------------
	4 - Gae Add WP Dashboard
------------------------------------------------------------------*/

function gae_blog_feed_dashboard() {
?>
	<div class="rss-widget">
	<a href="http://www.gaetheme.com/"><img src="<?php echo get_theme_uri('functions, gaepanel, images'); ?>/gaetheme.png"  class="alignright" alt="gaetheme.com"/></a>
		<?php wp_widget_rss_output( 'http://www.gaetheme.com/blog/feed/', array( 'items' => 3, 'show_summary' => 1, 'show_date' => 1 ) ); ?>
	</div>
	<br class="clear">
	<div class="connects">
		<a href="http://www.toserblog.com/blog/feed/"><img src="<?php echo get_bloginfo('wpurl') .'/'. WPINC; ?>/images/rss.png" alt=""/> <?php _e('Subscribe with RSS', 'gaetheme'); ?></a>
		<a href="http://feedburner.google.com/fb/a/mailverify?uri=toserblogcom"><img src="<?php echo get_theme_uri('functions, gaepanel, images'); ?>/email.png" alt=""/> <?php _e('Subscribe by Email', 'gaetheme'); ?></a>
		<a href="http://twitter.com/toserblog"><img src="<?php echo get_theme_uri('functions, gaepanel, images'); ?>/twitter.png" alt=""/> <?php _e('Follow on Twitter', 'gaetheme'); ?></a>
		<a href="http://twitter.com/toserblog"><img src="<?php echo get_theme_uri('functions, gaepanel, images'); ?>/facebook.png" alt=""/> <?php _e('Join on Facebook', 'gaetheme'); ?></a>
	</div>
<?php
} 

function add_admin_dashboard() {
	wp_add_dashboard_widget('gae_dashboard_widget', 'GaeTheme.com Blog', 'gae_blog_feed_dashboard');
} 





/*------------------------------------------------------------------
	5 - Add WP Admin styles
------------------------------------------------------------------*/

function add_admin_style() {
	global $pagenow;
	if ( $pagenow != 'index.php' && $pagenow != 'admin.php' && $pagenow != 'widgets.php') return;
?>
<style type="text/css">
<!--
#gae_dashboard_widget .connects { border-top: 1px solid #ececec; padding-top: 1em; text-align: center; }
#gae_dashboard_widget .connects a { display: inline-block; font-size: 12px; line-height: 18px; margin: 0 3px; }
#gae_dashboard_widget .connects img { display: inline-block; margin: -2px 0; }
.red-button { color: #a55; background: #ecc url(<?php echo get_theme_uri('functions, gaepanel, images'); ?>/button-red.png) repeat-x; border-color: #eaa; }
.red-button:hover { color: #900; border-color: #c55; }
.gae-widget-ads { position: relative; }
.gae-widget-ads .ad-preview { position: absolute; top: 0; left: 100%; margin: 0.5em 0; }
.gae-widget-ads .banner-preview { background: #fff; overflow: hidden; }
.no-js .gae-widget-ads { width: 100% !important; }
.js .widget-ads-125 .gae-widget-ads { border-top: 1px solid #ddd; padding-top: 0.5em; padding-right: 135px; min-height: 130px; }
-->
</style>
<?php
} 





/*------------------------------------------------------------------
	6 - Make Admin Notice
------------------------------------------------------------------*/

function add_admin_notice() {
	global $user_ID, $theme_data, $themename, $shortname, $pagenow;
	$user_info = get_userdata($user_ID);
	$theme_data_check = theme_data_init();
	$initial = get_option('theme_notice');
	$gaepage = explode('_', isset($_GET['page']) ? $_GET['page'] : '');
	if ( ( get_mode_option('status') == 'maintenance' || get_mode_option('status') == 'construction' ) ) {
		$site_mode = get_mode_option('status');
		$set_title = get_mode_option($site_mode.'_title');
		$message = !empty($set_title) ? $set_title : sprintf( __('This Site Under %s', 'gaetheme'), $mode );
		if( $user_info->user_level == 10 ) echo '<div class="update-nag">'. gae_message_mode() .'</div>';
		else echo '<div class="update-nag">'. $message .'</div>';
	}
	if ( $user_info->user_level == 10 && is_lock_mode() ) {
		echo '<div class="update-nag">'. gae_message_lock() .'</div>';
	}
	if ( gae_message_path() ) {
		echo '<div class="update-nag">'. gae_message_path() .'</div>';
	}
	if ( $theme_data_check['Name'] != $themename || $theme_data_check['Name'] != $theme_data['Name'] || $shortname != str_replace(' ', '_', strtolower($theme_data_check['Name'])) || $theme_data_check['URI'] != $theme_data['URI'] || $theme_data_check['AuthorURI'] != $theme_data['AuthorURI'] || $theme_data_check['AuthorName'] != $theme_data['AuthorName'] ) {
		if ( $initial['type'] == '' || $initial['credits'] == 'GaeTheme.com' ) return;
		if ( $pagenow == 'themes.php' || $pagenow == 'widgets.php' || $pagenow == 'nav-menus.php' || $pagenow == 'theme-editor.php' || $gaepage[0] == 'gae' ) {
			echo '<div class="update-nag">'. gae_message_data() .'</div>';
		}
	}
}





/*------------------------------------------------------------------
	7 - Gae Lock Functions
------------------------------------------------------------------*/

function is_lock_opt() {
	$initial = get_option('theme_notice');
	if ( $initial['type'] != 'free' && $initial['credits'] == 'GaeTheme.com' ) {  return false; } else { return true; }
}

function is_lock_mode() {
	$c1 = get_option('theme_notice'); $c2 = theme_initialize_init(); if ( $c1['credits'] == 'GaeTheme.com' && $c1['type'] != 'free' ) return false; $l1 = $c2['credits']; $l2 = $c1['credits']; $rs = get_theme_dir() .'/footer.php';  $fi = fopen($rs, "r"); $rf = fread($fi, filesize($rs)); $p1 = preg_quote($l1, "/"); $p2 = preg_quote($l2, "/"); fclose($fi);  if ( $c2 != $c1 || $l2 != $l1 || strpos($rf, $l1) == 0 || strpos($rf, $l2) == 0 || preg_match("/<\!--(.*" . $p1 . ".*)-->/si", $rf) || preg_match("/<\!--(.*" . $p2 . ".*)-->/si", $rf) ) { return true; } else { return false; }
}





/*------------------------------------------------------------------
	8 - LOAD OTHER ADMIN FILES
------------------------------------------------------------------*/

require_once( get_theme_dir('functions, gaepanel') . '/admin-functions.php' );		// Load Admin Functions
require_once( get_theme_dir('functions, gaepanel') . '/admin-panel.php' );			// Load Admin Panel Class
require_once( get_theme_dir('functions, template') . '/theme-options.php' );		// Load Admin Panel Theme
if( file_exists(get_theme_dir('functions, asset') . '/admin-functions-add.php') )
	require_once( get_theme_dir('functions, asset') . '/admin-functions-add.php' );	// Load Admin Panel - Premium





/*------------------------------------------------------------------
	9 - Create Panel Options
------------------------------------------------------------------*/

new Gae_Option_Panel(
	array (
		'id' => 'gae_options',
		'key' => 'theme',
		'parent' => array('position' => 62, 'name' => $themename, 'icon' => get_theme_uri('functions, gaepanel, images') .'/gae-icon.png'),
		'page' => __('Theme Options', 'gaetheme'),
		'title' => sprintf( __('%s Theme Options', 'gaetheme'), $themename ),
		'icon' => 'options-general',
		'capability' => 'edit_theme_options',
		'options' => $theme_options
	)
);

if (function_exists('gae_mode_options')) {
	new Gae_Option_Panel(
		array( 
			'id' => 'gae_mode',
			'key' => 'mode',
			'parent' => 'gae_options',
			'page' => __('Mode Options', 'gaetheme'),
			'title' => sprintf( __('%s Mode Options', 'gaetheme'), $themename ),
			'icon' => 'plugins',
			'capability' => 'edit_theme_options',
			'options' => gae_mode_options()
		)
	);
}


if (function_exists('gae_seo_options')) {
	new Gae_Option_Panel(
		array( 
			'id' => 'gae_seo',
			'key' => 'seo',
			'parent' => 'gae_options',
			'page' => __('SEO Options', 'gaetheme'),
			'title' => sprintf( __('%s SEO Options', 'gaetheme'), $themename ),
			'icon' => 'ms-admin',
			'capability' => 'edit_theme_options',
			'options' => gae_seo_options()
		)
	);
}

if (function_exists('gae_sc_generator')) {
	new Gae_Shortcode_Gen(
		array( 
			'id' => 'gae_shortcode',
			'key' => 'shortcode',
			'parent' => 'gae_options',
			'page' => __('Shortcode Generator', 'gaetheme'),
			'title' => __('Shortcode Generator', 'gaetheme'),
			'icon' => 'tools',
			'capability' => 'edit_posts',
			'options' => gae_sc_generator()
		)
	);
}


new Gae_Themes_Show(
	array( 
		'id' => 'gae_themes',
		'key' => 'gaethemes',
		'parent' => 'gae_options',
		'page' => __('Theme Collection', 'gaetheme'),
		'title' => sprintf( __('Theme Collection from %s', 'gaetheme'), 'GaeTheme.com' ),
		'icon' => 'themes',
		'capability' => 'read',
		'options' => ''
	)
);





/*------------------------------------------------------------------
	10 - LOAD TEMPLATE FILES
------------------------------------------------------------------*/

require_once( get_theme_dir('functions, gaepanel') . '/template-functions.php' );		// Load template functions
if( file_exists(get_theme_dir('functions, asset' . '/template-functions-add.php')) )
	require_once( get_theme_dir('functions, asset') . '/template-functions-add.php' );	// Load template functions - Premium





/*------------------------------------------------------------------
	(c) GAETHEME.COM 2012
------------------------------------------------------------------*/

?>