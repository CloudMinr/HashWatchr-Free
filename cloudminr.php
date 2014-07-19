<?php
/*
  Plugin Name: CloudMinr's HashWatchr
  Plugin URI: http://www.github.com/CloudMinr/HashWatchr
  Description: A customizable dashboard that uses the MPOS API to provide statistics
  Version: 1.0.0
  Author: Chris MacKay
  Author URI: http://cloudminr.com/chris-mackay
  License: FreeBSD

  Copyright (c) 2013-2014, Chris MacKay (email: chris@cloudminr.com)
  All rights reserved.

  Redistribution and use in source and binary forms, with or without
  modification, are permitted provided that the following conditions are met:

  1. Redistributions of source code must retain the above copyright notice, this
     list of conditions and the following disclaimer. 
  2. Redistributions in binary form must reproduce the above copyright notice,
     this list of conditions and the following disclaimer in the documentation
     and/or other materials provided with the distribution.

  THIS SOFTWARE IS PROVIDED BY THE COPYRIGHT HOLDERS AND CONTRIBUTORS "AS IS" AND
  ANY EXPRESS OR IMPLIED WARRANTIES, INCLUDING, BUT NOT LIMITED TO, THE IMPLIED
  WARRANTIES OF MERCHANTABILITY AND FITNESS FOR A PARTICULAR PURPOSE ARE
  DISCLAIMED. IN NO EVENT SHALL THE COPYRIGHT OWNER OR CONTRIBUTORS BE LIABLE FOR
  ANY DIRECT, INDIRECT, INCIDENTAL, SPECIAL, EXEMPLARY, OR CONSEQUENTIAL DAMAGES
  (INCLUDING, BUT NOT LIMITED TO, PROCUREMENT OF SUBSTITUTE GOODS OR SERVICES;
  LOSS OF USE, DATA, OR PROFITS; OR BUSINESS INTERRUPTION) HOWEVER CAUSED AND
  ON ANY THEORY OF LIABILITY, WHETHER IN CONTRACT, STRICT LIABILITY, OR TORT
  (INCLUDING NEGLIGENCE OR OTHERWISE) ARISING IN ANY WAY OUT OF THE USE OF THIS
  SOFTWARE, EVEN IF ADVISED OF THE POSSIBILITY OF SUCH DAMAGE.
*/


global $cloudminr_version;
$cloudminr_version = "0.1";

function cloudminr_create_custom_roles(){
  $caps = array(
		    'read'         => true,  // True allows this capability
		    'edit_posts'   => false,
		    'delete_posts' => false, // Use false to explicitly deny
	);
	
  add_role('cloudminr_client', 'CloudMinr Client', $caps);
	
	$admin = get_role('administrator');
  $caps = $admin->capabilities;
  unset($caps['activate_plugins']);
  unset($caps['edit_plugins']);
  unset($caps['update_plugins']);
  unset($caps['delete_plugins']);
  unset($caps['install_plugins']);
  unset($caps['import']);
  add_role('cloudminr_admin', 'CloudMinr Admin', $caps);
}
add_action('init', 'cloudminr_create_custom_roles');

/* Begin redirect_to Function */
function redirect_to($url){
    $string = '<script type="text/javascript">';
      $string .= 'window.location = "'.$url.'"';
    $string .= '</script>';
    return $string;
}
/* End redirect_to Function */

//Begin Hide Admin Bar
add_filter('show_admin_bar', '__return_false');
//End Hide Admin Bar

function cloudminr_install() {
  global $wpdb;
  global $cloudminr_version;
	add_option("cloudminr_version", $cloudminr_version);
	add_option("cloudminr_base_country", "Canada");
	require_once(ABSPATH.'wp-admin/includes/upgrade.php');
	
	/* Batches Table */
  $table_name = $wpdb->prefix."cloudminr_batches";
  $sql = "CREATE TABLE ".$table_name." (
    id bigint(20) NOT NULL AUTO_INCREMENT,
		active mediumint(2) DEFAULT '0' NOT NULL,
		locked mediumint(2) DEFAULT '0' NOT NULL,
    created_date date NOT NULL,
		created_time time  NOT NULL,
		updated timestamp DEFAULT CURRENT_TIMESTAMP NOT NULL,
		created_by_id bigint(20) NOT NULL,
		batch_id bigint(20) NOT NULL,
		user_id bigint(20) NOT NULL,
		pool_account_id bigint(2) NOT NULL,
		worker_id bigint(20) NOT NULL,
		minute int(1) DEFAULT '0' NOT NULL,
		hour int(1) DEFAULT '0' NOT NULL,
		worker_count bigint(4) DEFAULT '0',
    UNIQUE KEY id (id)
  );";
  dbDelta($sql);
	
	/* Batches Hourly Table */
  $table_name = $wpdb->prefix."cloudminr_batches_hourly";
  $sql = "CREATE TABLE ".$table_name." (
    id bigint(20) NOT NULL AUTO_INCREMENT,
		active mediumint(2) DEFAULT '0' NOT NULL,
		locked mediumint(2) DEFAULT '0' NOT NULL,
    created_date date NOT NULL,
		created_time time  NOT NULL,
		updated timestamp DEFAULT CURRENT_TIMESTAMP NOT NULL,
		created_by_id bigint(20) NOT NULL,
		batch_id bigint(20) NOT NULL,
		user_id bigint(20) NOT NULL,
		pool_account_id bigint(2) NOT NULL,
		worker_id bigint(20) NOT NULL,
		hour int(1) DEFAULT '0' NOT NULL,
		week int(1) DEFAULT '0' NOT NULL,
		worker_count bigint(4) DEFAULT '0',
    UNIQUE KEY id (id)
  );";
  dbDelta($sql);
	
	/* Main Workers Table */
  $table_name = $wpdb->prefix."cloudminr_workers";
  $sql = "CREATE TABLE ".$table_name." (
    id bigint(20) NOT NULL AUTO_INCREMENT,
		active mediumint(2) DEFAULT '0' NOT NULL,
		locked mediumint(2) DEFAULT '0' NOT NULL,
    created_date date NOT NULL,
		created_time time  NOT NULL,
		updated timestamp DEFAULT CURRENT_TIMESTAMP NOT NULL,
		created_by_id bigint(20) NOT NULL,
		pool_account_id bigint(20) DEFAULT '0' NOT NULL,
		worker_id bigint(20) NOT NULL,
		name varchar(64) NULL,
    user_id bigint(20) NOT NULL,
    description text NULL,
    UNIQUE KEY id (id)
  );";
  dbDelta($sql);
	
	/* Main Pools Table */
  $table_name = $wpdb->prefix."cloudminr_pools";
  $sql = "CREATE TABLE ".$table_name." (
    id bigint(20) NOT NULL AUTO_INCREMENT,
		active mediumint(2) DEFAULT '0' NOT NULL,
		locked mediumint(2) DEFAULT '0' NOT NULL,
    created_date date NOT NULL,
		created_time time  NOT NULL,
		updated timestamp DEFAULT CURRENT_TIMESTAMP NOT NULL,
		created_by_id bigint(20) NOT NULL,
		user_id bigint(20) NOT NULL,
		pool_name varchar(255) NOT NULL,
		pool_api_id bigint(255) NOT NULL,
		pool_api_url varchar(255) NOT NULL,
		pool_api_key varchar(255) NOT NULL,
		pool_currency varchar(3) NULL,
    UNIQUE KEY id (id)
  );";
  dbDelta($sql);
	
}
register_activation_hook( __FILE__, 'cloudminr_install' );

/* Begin Check Of and Populating CloudMinr Pages */
function cloudminr_init_pages(){
  $debugMode = 0;
	global $current_user, $wpdb;
	get_currentuserinfo();
	$active_theme = wp_get_theme();
	$active_theme_name = $active_theme->get('Name');
	if ($active_theme_name == 'Flat Theme'){
	  $the_plugin_header = 'external';
		$sql = "SELECT COUNT(term_id) FROM ".$wpdb->prefix."terms WHERE name LIKE '%HashWatchr%Main%' AND slug LIKE '%hashwatchr-main%' AND term_group='0'";
	  $count = $wpdb->get_var($sql);
	  if ($count <= 0){
	    $insert_sql = "INSERT INTO ".$wpdb->prefix."terms SET name='HashWatchr Main', slug='hashwatchr-main', term_group='0'";
		  if ($wpdb->query($insert_sql)){
		    $term_id = $wpdb->insert_id;
		  } else {
		    if ( (!isset($debugMode)) || ( (isset($debugMode)) && ($debugMode >= 2) ) ){
		      print 'Boo!'.PHP_EOL;
			  }
	    }
	  } else {
	    $sql = "SELECT term_id FROM ".$wpdb->prefix."terms WHERE name LIKE '%HashWatchr%Main%' AND slug LIKE '%hashwatchr-main%' AND term_group='0'";
	    $term_id = $wpdb->get_var($sql);
	  }
	  $sql = "SELECT COUNT(term_taxonomy_id) FROM ".$wpdb->prefix."term_taxonomy WHERE term_id='".$term_id."' AND taxonomy='nav_menu' AND parent='0'";
	  $count = $wpdb->get_var($sql);
	  if ($count <= 0){
	    $insert_sql = "INSERT INTO ".$wpdb->prefix."term_taxonomy SET term_id='".$term_id."', taxonomy='nav_menu', parent='0', count='1'";
		  if ($wpdb->query($insert_sql)){
		    $term_taxonomy_id = $wpdb->insert_id;
		  } else {
		    if ( (!isset($debugMode)) || ( (isset($debugMode)) && ($debugMode >= 2) ) ){
		      print 'Boo!'.PHP_EOL;
		    }
	    }
	  } else {
	    $sql = "SELECT term_taxonomy_id FROM ".$wpdb->prefix."term_taxonomy WHERE term_id='".$term_id."' AND taxonomy='nav_menu' AND parent='0'";
	    $term_taxonomy_id = $wpdb->get_var($sql);
	  }
    $nav_locations = get_theme_mod('nav_menu_locations');
    $nav_locations['primary'] = $term_id;
		$nav_locations['footer'] = $term_id;		
    set_theme_mod('nav_menu_locations', $nav_locations);
	  include_once('cloudminr_init_pages.php');
	} else {
	  $the_plugin_header = 'internal';
	}
}
add_action( 'init', 'cloudminr_init_pages' );
/* End Check Of and Populating CloudMinr Pages */

/* Begin the_slug Function */
function get_the_slug($echo=false){
  $slug = basename(get_permalink());
  do_action('before_slug', $slug);
  $slug = apply_filters('slug_filter', $slug);
  if( $echo ) echo $slug;
    do_action('after_slug', $slug);
  return $slug;
}
/* End the_slug Function */

/* Begin Redirect After Login */
function cloudminr_login_redirect( $redirect_to, $request, $user ) {
	//is there a user to check?
	global $user;
	if ( isset( $user->roles ) && is_array( $user->roles ) ) {
		if ( in_array( 'cloudminr_admin', $user->roles ) ) {
			$redirect_to = get_bloginfo('url');
			return $redirect_to;
		} else {
			return home_url();
		}
	} else {
		return $redirect_to;
	}
}
add_filter( 'login_redirect', 'cloudminr_login_redirect', 10, 3 );
/* End Redirect After Login */

/* Begin Removal of non-essentials from Pages */
function cloudminr_custom_css(){
	function cloudminr_custom_css_header(){
	  
	  ?>
		<style type="text/css">
		  .nav > li > a:hover,
      .nav > li > a:focus {
			  text-decoration: none;
				background-color: #036417;
      }
			.current-menu-item { background-color: #036417; }
		</style>
		<?php
		$active_theme = wp_get_theme();
		if ($active_theme == 'Flat Theme'){
		  ?>
			<style type="text/css">
			  #bottom {display: none;}
				#donate {padding: 50px 0;}
			</style>
			<?php
		}
	}
	add_action('wp_head','cloudminr_custom_css_header');
}
add_action('init', 'cloudminr_custom_css');
/* End Removal of non-essentials from Pages */

/* Begin CloudMinr Charts */
function enqueue_cloudminr_charts_scripts(){
  global $wp_scripts, $current_user, $wpdb;
	get_currentuserinfo();
  wp_register_script('google-jsapi', 'https://www.google.com/jsapi', array( 'jquery' ), '0.0.0', false);
	wp_enqueue_script('google-jsapi');
}
add_action('wp_enqueue_scripts', 'enqueue_cloudminr_charts_scripts');
/* End CloudMinr Charts */

/* Begin [cloudminr] shortcode */
function cloudminr ( $atts ) {
  $html = '';
	$debugMode = 0;
	if ( ! is_user_logged_in() && $pagenow != 'wp-login.php' ){
		print redirect_to(get_bloginfo('url').'/wp-login.php');
	}
	global $current_user;
	get_currentuserinfo();
	
	$user_meta = get_user_meta($current_user->ID);
	if (isset($_GET['section'])){
	  $section = $_GET['section'];
	} else {
	  if (isset($_POST['section'])){
	    $section = $_POST['section'];
	  } else {
	    $section = 'dashboard';
	  }
	}	
	if (isset($_GET['view'])){
	  $view = $_GET['view'];
	} else {
	  if (isset($_POST['view'])){
	    $view = $_POST['view'];
	  } else {
	    $view = 'view';
	  }
	}
	if ($section == 'logout'){
		$nonce = wp_create_nonce( 'log-out' );
		print redirect_to(get_bloginfo('url').'/wp-login.php?action=logout&redirect_to='.get_bloginfo('url').'&_wpnonce='.$nonce);
	}
	$the_userdata = get_userdata($current_user->ID);
	$loop_count = 0;
	if (!empty($the_userdata->roles)){
	  foreach ($the_userdata->roles as $role){
		  if ($role == 'cloudminr_admin'){
			  $loop_count++;
			}
		}
	}
	if ( ((is_numeric($current_user->user_level)) && ($current_user->user_level >= 6)) || ($loop_count >= 1) ){
	  if (isset($user_meta['cloudminr_configured'][0])){
		  $configured = $user_meta['cloudminr_configured'][0];
			if ( (empty($configured)) || ($configured == 0)){
			  $do_first_run = 1;
			}
		} else {
		  $do_first_run = 1;
		}
	}
	if ($do_first_run == 1){
		  include_once('views/first_run.php');
	} else {
	  $active_theme = wp_get_theme();
	  $active_theme_name = $active_theme->get('Name');
	  if ($active_theme_name == 'Flat Theme'){
	    $the_plugin_header = 'external';
	  } else {
	    $the_plugin_header = 'internal';
	  }
	  include_once('views/header.php');
	  switch ($section){
	    case 'add-pool':
		    include_once('views/add_pool.php');
		  break;
	    case 'add-worker':
		    include_once('views/add_worker.php');
		  break;
	    case 'admin':
		    include_once('views/admin.php');
		  break;
		  case 'charts':
		    include_once('views/charts.php');
		  break;
		  case 'delete-pool':
		    include_once('views/delete_pool.php');
		  break;
		  case 'delete-worker':
		    include_once('views/delete_worker.php');
	  	break;
	  	case 'disable-worker':
		    include_once('views/disable_worker.php');
		  break;
		  case 'edit-pool':
		    include_once('views/edit_pool.php');
		  break;
		  case 'edit-worker':
		    include_once('views/edit_worker.php');
		  break;
		  case 'enable-worker':
		    include_once('views/enable_worker.php');
		  break;
		  case 'import-worker':
		    include_once('views/import_worker.php');
		  break;
		  case 'pools':
		    include_once('views/pools.php');
		  break;
	    default:
		    include_once('views/dashboard.php');
		  break;
	  }
	  $html .= '<h1 style="line-height: 3em;">&nbsp;</h1>'.PHP_EOL;
	}
	$html .= '<section id="donate" class="wet-asphalt">'.PHP_EOL;
    $html .= '<div class="container">'.PHP_EOL;
      $html .= '<div class="row" style="text-align: center;">'.PHP_EOL;
			  $html .= "<h5><a href='http://www.cloudminr.com' target='_cloudminr'>CloudMinr's</a> <a href='http://www.hashwatchr.com' target='_hashwatchr'>HashWatchr</a> by <a href='http://www.cloudminr.com/chris-mackay' target='_chris-mackay'>Chris MacKay</a>, available on <a href='http://www.github.com/CloudMinr/HashWatchr' target='_github'>GitHub</a></h5>".PHP_EOL;
        $html .= '<h5>Please donate to <a href="http://www.cloudminr.com" target="_cloudminr">CloudMinr</a> LTC: LcBaqteAhRVXqpqcVfQrKKDT1SXrBH2P6F</h5>'.PHP_EOL;
				$html .= '<h5>Theme by <a href="http://www.shapebootstrap.net" target="_shapebootstrap">ShapeBootstrap.net</a></h5>'.PHP_EOL;
      $html .= '</div>'.PHP_EOL;
    $html .= '</div>'.PHP_EOL;
  $html .= '</section>'.PHP_EOL;
	return $html;
}
add_shortcode('cloudminr', 'cloudminr');