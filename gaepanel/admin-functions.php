<?php

/*------------------------------------------------------------------

TABLE OF CONTENTS

1 - Get Options Settings
	1.1 - Get Theme Settings
	1.2 - Get Mode Settings
	1.3 - Get SEO Settings
2 - Gae Notice Message
	2.1 - Message Theme Settings
	2.2 - Message Site Mode
	2.3 - Message Theme Data
	2.4 - Message Theme Path
	2.5 - Message Theme Edited
3 - Gae Notice Front End
	3.1 - Not Active Mode
	3.2 - If Using IE6 as Browser
4 - Load Template Mode
5 - Action Mode Options

------------------------------------------------------------------*/

/*------------------------------------------------------------------
	1 - Get Options Settings
------------------------------------------------------------------*/

/*	1.1 - Get Theme Settings
------------------------------------------------------------------*/

function get_theme_option($option='', $option_child='') {
	global $shortname;
	$option = stripslashes($option);
	$option_child = isset($option_child) ? stripslashes($option_child) : '';
	if ( empty($option) ) return;
	$theme_options = get_option( $shortname.'_options_theme' );
	$result_options = isset($theme_options[$option]) ? $theme_options[$option] : '';
	if ( $option_child ) {
		if ( $result_options && in_array($option_child, $result_options) )
			return true;
		else
			return false;
	} else {
		return $result_options;
	}
}


/*	1.2 - Get Mode Settings
------------------------------------------------------------------*/

function get_mode_option($option='') {
	global $shortname;
	$mode_options = get_option( $shortname.'_options_mode' );
	$option = stripslashes($option);
	return $mode_options[$option];
}



/*	1.3 Get SEO Settings
------------------------------------------------------------------*/

function get_seo_option($option='', $option_child='') {
	global $shortname;
	$option = stripslashes($option);
	$option_child = isset($option_child) ? stripslashes($option_child) : '';
	if ( empty($option) ) return;
	$seo_options = get_option( $shortname.'_options_seo' );
	$result_options = isset($seo_options[$option]) ? $seo_options[$option] : '';
	if ( $option_child ) {
		if ( $result_options && in_array($option_child, $result_options) )
			return true;
		else
			return false;
	} else {
		return $result_options;
	}
}



/*------------------------------------------------------------------
	2 - Gae Notice Message
------------------------------------------------------------------*/

/*	2.1 - Message Theme Settings
------------------------------------------------------------------*/

function gae_message_setting($message='', $class='updated') {
	if ( empty($message) ) return exit;
	return '<div class="'. $class .' below-h2"><p><strong>'. $message .'</strong></p></div>';
}


/*	2.2 - Message Site Mode
------------------------------------------------------------------*/

function gae_message_mode() {
	return sprintf( __('Your site on <strong>%s mode</strong>. Only the <strong>administrator</strong> can access your site.','gaetheme'), get_mode_option('status') );
}


/*	2.3 - Message Theme Data
------------------------------------------------------------------*/

function gae_message_data() {
	if ( is_multisite() && !is_super_admin() ) {
		$display = __("The stylesheet attributes has removed or replaced. The stylesheet attributes is used as a function of theme settings.<br/>Please contact your super admin to restore the stylesheet attributes.", "gaetheme");
	} elseif  ( ( is_multisite() && is_super_admin() ) || !is_multisite() ) {
		$display = __("Don't remove or replace the stylesheet attributes. The stylesheet attributes is used as a function of theme settings.<br/>Please restore the stylesheet attributes now!", "gaetheme");
	}
	return $display;
}


/*	2.4 - Message Theme Path
------------------------------------------------------------------*/

function gae_message_path() {
	global $user_ID;
	$user_info = get_userdata($user_ID);
	$cache_dir = get_theme_dir('includes').'/cache';
	if ( is_writable($cache_dir) ) return;
	if ( is_multisite() && !is_super_admin() ) {
		$display = sprintf( __("The path %s is not writable, please contact your super admin to make it writable (chmod 777).", "gaetheme"), '<code>'. $cache_dir .'</code>' );
	} elseif  ( ( is_multisite() && is_super_admin() ) || ( !is_multisite() && $user_info->user_level == 10 ) ) {
		$display = sprintf( __("The path %s is not writable, let's make it writable (chmod 777) please.", "gaetheme"), '<code>'. $cache_dir .'</code>' );
	} else {
		$display = sprintf( __("The path %s is not writable, please contact your admin to make it writable (chmod 777).", "gaetheme"), '<code>'. $cache_dir .'</code>' );
	}
	return $display;
}


/*	2.5 - Message Theme Edited
------------------------------------------------------------------*/

function gae_message_lock() {
	return __('Sorry, your site is <strong>LOCKED</strong>. Please restore the theme script from last editing.','gaetheme');
}





/*------------------------------------------------------------------
	3 - Gae Notice Front End
------------------------------------------------------------------*/

/*	3.1 - Not Active Mode
------------------------------------------------------------------*/

function gae_mode_warning() {
	global $user_ID;
	$user_info = get_userdata($user_ID);
	if ( $user_ID && $user_info->user_level == 10 ) {
		echo '<div class="mode-warning">'. gae_message_mode() .'</div>';
?>
<style type="text/css">
<!--
div.mode-warning { position: fixed; z-index: 99999; top: 0; width: 100%; height: 15px; padding: 5px 0; font: bold 12px Arial, sans-serif; color: #ccc; background: #333; text-align: center; border-bottom: 1px solid #222; }
div.mode-warning strong { color: #fff; }
body.warning-bar { margin-top: 25px; }
body.admin-bar div.mode-warning { top: 28px; }
-->
</style>
<?php
	}
}

function gae_lock_warning() {
?>
<div class="content">
	<a href="http://www.gaetheme.com/"><img src="<?php echo get_theme_uri('functions, gaepanel, images'); ?>/gaetheme.png" alt="GaeTheme.com" /></a>
	<h2><?php _e('Sorry, Your Site is LOCKED', 'gaetheme'); ?></h2>	
	<p><strong><?php _e('Please restore the theme script from last editing.', 'gaetheme'); ?></strong></p>
	<p><?php _e('Get the theme without the footer links, including the other features with the purchase this theme.', 'gaetheme'); ?></p>
</div>
<?php
}


/*	3.2 - If Using IE6 as Browser
------------------------------------------------------------------*/

function gae_ie6_warning() {
	echo '<div class="ie6-warning">'. get_mode_option('ie6_warning') .'</div>';
?>
<style type="text/css">
<!--
div.ie6-warning { position: absolute; z-index: 99999; top: 0; width: 100%; height: 15px; padding: 5px 0; font: bold 12px Arial, sans-serif; color: #fff; background: #b00; text-align: center; border-bottom: 1px solid #000; }
div.ie6-warning a, div.ie6-warning strong { color: #ff0; text-decoration: none; }
body.warning-bar { margin-top: 25px; }
body.admin-bar div.ie6-warning { top: 28px; }
-->
</style>
<?php
}




/*------------------------------------------------------------------
	4 - Load Template Mode
------------------------------------------------------------------*/

function load_mode_template() {
	global $user_ID, $pagenow;
	$user_info = get_userdata($user_ID);
	if ( $pagenow == 'wp-login.php' || is_admin() ) return;
	if ( !$user_ID || ( $user_ID && $user_info->user_level != 10 ) ) {
		include( get_theme_dir('includes') . '/mode.php' );
		exit;
	}
}

function load_lock_template() {
	global $pagenow;
	if ( $pagenow == 'wp-login.php' || is_admin() ) return;
	include( get_theme_dir('includes') . '/mode.php' );
	exit;
}



/*------------------------------------------------------------------
	5 - Action Mode Options
------------------------------------------------------------------*/

$browser = $_SERVER[ 'HTTP_USER_AGENT' ];
if ( get_mode_option('status') == 'maintenance' || get_mode_option('status') == 'construction' ) {
	add_action( 'init', 'load_mode_template' );
	add_action( 'wp_footer', 'gae_mode_warning' );
}
if ( get_mode_option('status') == 'active' && preg_match( "/MSIE 6.0/", $browser ) ) {
	add_action( 'wp_footer', 'gae_ie6_warning' );
}
if ( is_lock_mode() ) {
	add_action( 'init', 'load_lock_template' );
}



/*------------------------------------------------------------------
	(c) GAETHEME.COM 2012
------------------------------------------------------------------*/

?>