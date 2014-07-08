<?php
/*
  Version: 0.1
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

  @package cloudminr
  @since cloudminr 0.1
*/
  // BEGIN CHECK FOR "My Minrs"
	$args = array(
	  'name' => 'minrs',
		'post_title' => 'My Minrs',
		'post_type' => 'nav_menu_item',
		'post_status' => 'publish',
		'posts_per_page' => 1
	);
	$post = get_posts($args);
	if ($post){
	  if ( (!isset($debugMode)) || ( (isset($debugMode)) && ( ($debugMode >= 2) || ($debugMode == 1) ) ) ){
		  print 'Found My Minrs<br/>'.PHP_EOL;
		}
		foreach ($post as $this_post){
		  $minrs_page_id = $this_post->ID;
			$sql = "SELECT COUNT(object_id) FROM ".$wpdb->prefix."term_relationships WHERE object_id='".$minrs_page_id."' AND term_taxonomy_id='".$term_taxonomy_id."' AND term_order='0'";
			$count = $wpdb->get_var($sql);
			if ($count <= 0){
			  $insert_sql = "INSERT INTO ".$wpdb->prefix."term_relationships SET object_id='".$minrs_page_id."', term_taxonomy_id='".$term_taxonomy_id."', term_order='0'";
				if ($wpdb->query($insert_sql)){
				  if ( (!isset($debugMode)) || ( (isset($debugMode)) && ( ($debugMode >= 2) || ($debugMode == 1) ) ) ){
			      print 'Yay!'.PHP_EOL;
			    }
				} else {
				  if ( (!isset($debugMode)) || ( (isset($debugMode)) && ( ($debugMode >= 2) || ($debugMode == 1) ) ) ){
			      print 'Boo!'.PHP_EOL;
			    }
				}
			}
		}
	} else {
	  if ( (!isset($debugMode)) || ( (isset($debugMode)) && ( ($debugMode >= 2) || ($debugMode == 1) ) ) ){
		  print 'Adding My Minrs<br/>'.PHP_EOL;
		}
		$args = array(
		    'post_author' => $current_user->ID,
			  'post_content' => '',
	      'post_name' => 'minrs',
		    'post_status' => 'publish',
			  'post_title' => 'My Minrs',
		    'post_type' => 'nav_menu_item',
				'post_parent' => '0',
		    'comment_status' => 'closed',
			  'ping_status' => 'closed',
				'menu_order' => '1'
	  );
		if ( (!isset($debugMode)) || ( (isset($debugMode)) && ( ($debugMode >= 2) || ($debugMode == 0) ) ) ){
 	    $nav_menu_item_id = wp_insert_post($args);
		  if ($nav_menu_item_id){
		    if ( (!isset($debugMode)) || ( (isset($debugMode)) && ( ($debugMode >= 2) || ($debugMode == 1) ) ) ){
			    print 'Yay!'.PHP_EOL;
			  }
				$minrs_page_id = $nav_menu_item_id;
				update_post_meta($nav_menu_item_id, '_menu_item_type', 'custom');
				update_post_meta($nav_menu_item_id, '_menu_item_object', 'custom');
				update_post_meta($nav_menu_item_id, '_menu_item_url', get_bloginfo('url').'/?section=minrs');
				update_post_meta($nav_menu_item_id, '_menu_item_object_id', $nav_menu_item_id);
				$args = array(
				  'ID' => $nav_menu_item_id,
					'guid' => get_bloginfo('url').'/?p='.$nav_menu_item_id
				);
				if (wp_update_post($args)){
				  if ( (!isset($debugMode)) || ( (isset($debugMode)) && ( ($debugMode >= 2) || ($debugMode == 1) ) ) ){
			      print 'Yay!'.PHP_EOL;
			    }
				} else {
				  if ( (!isset($debugMode)) || ( (isset($debugMode)) && ( ($debugMode >= 2) || ($debugMode == 1) ) ) ){
			      print 'Boo!'.PHP_EOL;
			    }
				}
				$insert_sql = "INSERT INTO ".$wpdb->prefix."term_relationships SET object_id='".$nav_menu_item_id."', term_taxonomy_id='".$term_taxonomy_id."', term_order='0'";
				if ($wpdb->query($insert_sql)){
				  if ( (!isset($debugMode)) || ( (isset($debugMode)) && ( ($debugMode >= 2) || ($debugMode == 1) ) ) ){
			      print 'Yay!'.PHP_EOL;
			    }
				} else {
				  if ( (!isset($debugMode)) || ( (isset($debugMode)) && ( ($debugMode >= 2) || ($debugMode == 1) ) ) ){
			      print 'Boo!'.PHP_EOL;
			    }
				}
			}
		}
	}
	if ( (!isset($debugMode)) || ( (isset($debugMode)) && ( ($debugMode >= 2) || ($debugMode == 1) ) ) ){
	  print $minrs_page_id.'<br />'.PHP_EOL;
	}
	// END CHECK FOR "My Minrs"
	
	// BEGIN CHECK FOR "Charts"
	$args = array(
	  'name' => 'charts',
		'post_title' => 'Chrts',
		'post_type' => 'nav_menu_item',
		'post_status' => 'publish',
		'posts_per_page' => 1
	);
	$post = get_posts($args);
	if ($post){
	  if ( (!isset($debugMode)) || ( (isset($debugMode)) && ( ($debugMode >= 2) || ($debugMode == 1) ) ) ){
		  print 'Found Charts<br/>'.PHP_EOL;
		}
		foreach ($post as $this_post){
		  $charts_page_id = $this_post->ID;
			$sql = "SELECT COUNT(object_id) FROM ".$wpdb->prefix."term_relationships WHERE object_id='".$charts_page_id."' AND term_taxonomy_id='".$term_taxonomy_id."' AND term_order='0'";
			$count = $wpdb->get_var($sql);
			if ($count <= 0){
			  $insert_sql = "INSERT INTO ".$wpdb->prefix."term_relationships SET object_id='".$charts_page_id."', term_taxonomy_id='".$term_taxonomy_id."', term_order='0'";
				if ($wpdb->query($insert_sql)){
				  if ( (!isset($debugMode)) || ( (isset($debugMode)) && ( ($debugMode >= 2) || ($debugMode == 1) ) ) ){
			      print 'Yay!'.PHP_EOL;
			    }
				} else {
				  if ( (!isset($debugMode)) || ( (isset($debugMode)) && ( ($debugMode >= 2) || ($debugMode == 1) ) ) ){
			      print 'Boo!'.PHP_EOL;
			    }
				}
			}
		}
	} else {
	  if ( (!isset($debugMode)) || ( (isset($debugMode)) && ( ($debugMode >= 2) || ($debugMode == 1) ) ) ){
		  print 'Adding Charts<br/>'.PHP_EOL;
		}
		$args = array(
		    'post_author' => $current_user->ID,
			  'post_content' => '',
	      'post_name' => 'charts',
		    'post_status' => 'publish',
			  'post_title' => 'Chrts',
		    'post_type' => 'nav_menu_item',
				'post_parent' => '0',
		    'comment_status' => 'closed',
			  'ping_status' => 'closed',
				'menu_order' => '2'
	  );
		if ( (!isset($debugMode)) || ( (isset($debugMode)) && ( ($debugMode >= 2) || ($debugMode == 0) ) ) ){
 	    $nav_menu_item_id = wp_insert_post($args);
		  if ($nav_menu_item_id){
		    if ( (!isset($debugMode)) || ( (isset($debugMode)) && ( ($debugMode >= 2) || ($debugMode == 1) ) ) ){
			    print 'Yay!'.PHP_EOL;
			  }
				$charts_page_id = $nav_menu_item_id;
				update_post_meta($nav_menu_item_id, '_menu_item_type', 'custom');
				update_post_meta($nav_menu_item_id, '_menu_item_object', 'custom');
				update_post_meta($nav_menu_item_id, '_menu_item_url', get_bloginfo('url').'/?section=charts');
				update_post_meta($nav_menu_item_id, '_menu_item_object_id', $nav_menu_item_id);
				$args = array(
				  'ID' => $nav_menu_item_id,
					'guid' => get_bloginfo('url').'/?p='.$nav_menu_item_id
				);
				if (wp_update_post($args)){
				  if ( (!isset($debugMode)) || ( (isset($debugMode)) && ( ($debugMode >= 2) || ($debugMode == 1) ) ) ){
			      print 'Yay!'.PHP_EOL;
			    }
				} else {
				  if ( (!isset($debugMode)) || ( (isset($debugMode)) && ( ($debugMode >= 2) || ($debugMode == 1) ) ) ){
			      print 'Boo!'.PHP_EOL;
			    }
				}
				$insert_sql = "INSERT INTO ".$wpdb->prefix."term_relationships SET object_id='".$nav_menu_item_id."', term_taxonomy_id='".$term_taxonomy_id."', term_order='0'";
				if ($wpdb->query($insert_sql)){
				  if ( (!isset($debugMode)) || ( (isset($debugMode)) && ( ($debugMode >= 2) || ($debugMode == 1) ) ) ){
			      print 'Yay!'.PHP_EOL;
			    }
				} else {
				  if ( (!isset($debugMode)) || ( (isset($debugMode)) && ( ($debugMode >= 2) || ($debugMode == 1) ) ) ){
			      print 'Boo!'.PHP_EOL;
			    }
				}
			}
		}
	}
	if ( (!isset($debugMode)) || ( (isset($debugMode)) && ( ($debugMode >= 2) || ($debugMode == 1) ) ) ){
	  print $charts_page_id.'<br />'.PHP_EOL;
	}
	// END CHECK FOR "Charts"
	
	// BEGIN CHECK FOR "Admin"
	$args = array(
	  'name' => 'admin',
		'post_title' => 'Admin',
		'post_type' => 'nav_menu_item',
		'post_status' => 'publish',
		'posts_per_page' => 1
	);
	$post = get_posts($args);
	if ($post){
	  if ( (!isset($debugMode)) || ( (isset($debugMode)) && ( ($debugMode >= 2) || ($debugMode == 1) ) ) ){
		  print 'Found Admin<br/>'.PHP_EOL;
		}
		foreach ($post as $this_post){
		  $admin_page_id = $this_post->ID;
			$sql = "SELECT COUNT(object_id) FROM ".$wpdb->prefix."term_relationships WHERE object_id='".$admin_page_id."' AND term_taxonomy_id='".$term_taxonomy_id."' AND term_order='0'";
			$count = $wpdb->get_var($sql);
			if ($count <= 0){
			  $insert_sql = "INSERT INTO ".$wpdb->prefix."term_relationships SET object_id='".$admin_page_id."', term_taxonomy_id='".$term_taxonomy_id."', term_order='0'";
				if ($wpdb->query($insert_sql)){
				  if ( (!isset($debugMode)) || ( (isset($debugMode)) && ( ($debugMode >= 2) || ($debugMode == 1) ) ) ){
			      print 'Yay!'.PHP_EOL;
			    }
				} else {
				  if ( (!isset($debugMode)) || ( (isset($debugMode)) && ( ($debugMode >= 2) || ($debugMode == 1) ) ) ){
			      print 'Boo!'.PHP_EOL;
			    }
				}
			}
		}
	} else {
	  if ( (!isset($debugMode)) || ( (isset($debugMode)) && ( ($debugMode >= 2) || ($debugMode == 1) ) ) ){
		  print 'Adding Admin<br/>'.PHP_EOL;
		}
		$args = array(
		    'post_author' => $current_user->ID,
			  'post_content' => '',
	      'post_name' => 'admin',
		    'post_status' => 'publish',
			  'post_title' => 'Admin',
		    'post_type' => 'nav_menu_item',
				'post_parent' => '0',
		    'comment_status' => 'closed',
			  'ping_status' => 'closed',
				'menu_order' => '4'
	  );
		if ( (!isset($debugMode)) || ( (isset($debugMode)) && ( ($debugMode >= 2) || ($debugMode == 0) ) ) ){
 	    $nav_menu_item_id = wp_insert_post($args);
		  if ($nav_menu_item_id){
		    if ( (!isset($debugMode)) || ( (isset($debugMode)) && ( ($debugMode >= 2) || ($debugMode == 1) ) ) ){
			    print 'Yay!'.PHP_EOL;
			  }
				$admin_page_id = $nav_menu_item_id;
				update_post_meta($nav_menu_item_id, '_menu_item_type', 'custom');
				update_post_meta($nav_menu_item_id, '_menu_item_object', 'custom');
				update_post_meta($nav_menu_item_id, '_menu_item_url', get_bloginfo('url').'/?section=admin');
				update_post_meta($nav_menu_item_id, '_menu_item_object_id', $nav_menu_item_id);
				$args = array(
				  'ID' => $nav_menu_item_id,
					'guid' => get_bloginfo('url').'/?p='.$nav_menu_item_id
				);
				if (wp_update_post($args)){
				  if ( (!isset($debugMode)) || ( (isset($debugMode)) && ( ($debugMode >= 2) || ($debugMode == 1) ) ) ){
			      print 'Yay!'.PHP_EOL;
			    }
				} else {
				  if ( (!isset($debugMode)) || ( (isset($debugMode)) && ( ($debugMode >= 2) || ($debugMode == 1) ) ) ){
			      print 'Boo!'.PHP_EOL;
			    }
				}
				$insert_sql = "INSERT INTO ".$wpdb->prefix."term_relationships SET object_id='".$nav_menu_item_id."', term_taxonomy_id='".$term_taxonomy_id."', term_order='0'";
				if ($wpdb->query($insert_sql)){
				  if ( (!isset($debugMode)) || ( (isset($debugMode)) && ( ($debugMode >= 2) || ($debugMode == 1) ) ) ){
			      print 'Yay!'.PHP_EOL;
			    }
				} else {
				  if ( (!isset($debugMode)) || ( (isset($debugMode)) && ( ($debugMode >= 2) || ($debugMode == 1) ) ) ){
			      print 'Boo!'.PHP_EOL;
			    }
				}
			}
		}
	}
	if ( (!isset($debugMode)) || ( (isset($debugMode)) && ( ($debugMode >= 2) || ($debugMode == 1) ) ) ){
	  print $admin_page_id.'<br />'.PHP_EOL;
	}
	// END CHECK FOR "Admin"
	
  // BEGIN CHECK FOR "Logout"
	$args = array(
	  'name' => 'logout',
		'post_title' => 'Logout',
		'post_type' => 'nav_menu_item',
		'post_status' => 'publish',
		'posts_per_page' => 1
	);
	$post = get_posts($args);
	if ($post){
	  if ( (!isset($debugMode)) || ( (isset($debugMode)) && ( ($debugMode >= 2) || ($debugMode == 1) ) ) ){
		  print 'Found Logout<br/>'.PHP_EOL;
		}
		foreach ($post as $this_post){
		  $logout_page_id = $this_post->ID;
			$sql = "SELECT COUNT(object_id) FROM ".$wpdb->prefix."term_relationships WHERE object_id='".$logout_page_id."' AND term_taxonomy_id='".$term_taxonomy_id."' AND term_order='0'";
			$count = $wpdb->get_var($sql);
			if ($count <= 0){
			  $insert_sql = "INSERT INTO ".$wpdb->prefix."term_relationships SET object_id='".$logout_page_id."', term_taxonomy_id='".$term_taxonomy_id."', term_order='0'";
				if ($wpdb->query($insert_sql)){
				  if ( (!isset($debugMode)) || ( (isset($debugMode)) && ( ($debugMode >= 2) || ($debugMode == 1) ) ) ){
			      print 'Yay!'.PHP_EOL;
			    }
				} else {
				  if ( (!isset($debugMode)) || ( (isset($debugMode)) && ( ($debugMode >= 2) || ($debugMode == 1) ) ) ){
			      print 'Boo!'.PHP_EOL;
			    }
				}
			}
		}
	} else {
	  if ( (!isset($debugMode)) || ( (isset($debugMode)) && ( ($debugMode >= 2) || ($debugMode == 1) ) ) ){
		  print 'Adding Logout<br/>'.PHP_EOL;
		}
		$args = array(
		    'post_author' => $current_user->ID,
			  'post_content' => '',
	      'post_name' => 'logout',
		    'post_status' => 'publish',
			  'post_title' => 'Logout',
		    'post_type' => 'nav_menu_item',
				'post_parent' => '0',
		    'comment_status' => 'closed',
			  'ping_status' => 'closed',
				'menu_order' => '5'
	  );
		if ( (!isset($debugMode)) || ( (isset($debugMode)) && ( ($debugMode >= 2) || ($debugMode == 0) ) ) ){
 	    $nav_menu_item_id = wp_insert_post($args);
		  if ($nav_menu_item_id){
		    if ( (!isset($debugMode)) || ( (isset($debugMode)) && ( ($debugMode >= 2) || ($debugMode == 1) ) ) ){
			    print 'Yay!'.PHP_EOL;
			  }
				$logout_page_id = $nav_menu_item_id;
				update_post_meta($nav_menu_item_id, '_menu_item_type', 'custom');
				update_post_meta($nav_menu_item_id, '_menu_item_object', 'custom');
				update_post_meta($nav_menu_item_id, '_menu_item_url', get_bloginfo('url').'/?section=logout');
				update_post_meta($nav_menu_item_id, '_menu_item_object_id', $nav_menu_item_id);
				$args = array(
				  'ID' => $nav_menu_item_id,
					'guid' => get_bloginfo('url').'/?p='.$nav_menu_item_id
				);
				if (wp_update_post($args)){
				  if ( (!isset($debugMode)) || ( (isset($debugMode)) && ( ($debugMode >= 2) || ($debugMode == 1) ) ) ){
			      print 'Yay!'.PHP_EOL;
			    }
				} else {
				  if ( (!isset($debugMode)) || ( (isset($debugMode)) && ( ($debugMode >= 2) || ($debugMode == 1) ) ) ){
			      print 'Boo!'.PHP_EOL;
			    }
				}
				$insert_sql = "INSERT INTO ".$wpdb->prefix."term_relationships SET object_id='".$nav_menu_item_id."', term_taxonomy_id='".$term_taxonomy_id."', term_order='0'";
				if ($wpdb->query($insert_sql)){
				  if ( (!isset($debugMode)) || ( (isset($debugMode)) && ( ($debugMode >= 2) || ($debugMode == 1) ) ) ){
			      print 'Yay!'.PHP_EOL;
			    }
				} else {
				  if ( (!isset($debugMode)) || ( (isset($debugMode)) && ( ($debugMode >= 2) || ($debugMode == 1) ) ) ){
			      print 'Boo!'.PHP_EOL;
			    }
				}
			}
		}
	}
	if ( (!isset($debugMode)) || ( (isset($debugMode)) && ( ($debugMode >= 2) || ($debugMode == 1) ) ) ){
	  print $admin_page_id.'<br />'.PHP_EOL;
	}
	// END CHECK FOR "Logout"
	
	// BEGIN CHECK FOR PAGE FOR SHORTCODE [cloudminr]
	$args = array(
	  'name' => 'CloudMinr',
		'post_title' => 'CloudMinr',
		'post_type' => 'page',
		'post_status' => 'publish',
		'posts_per_page' => 1
	);
	$post = get_posts($args);
	if ($post){
	  if ( (!isset($debugMode)) || ( (isset($debugMode)) && ( ($debugMode >= 2) || ($debugMode == 1) ) ) ){
		  print 'Found CloudMinr Page<br/>'.PHP_EOL;
		}
		foreach ($post as $this_post){
		  update_option( 'page_on_front', $this_post->ID );
			update_option( 'show_on_front', 'page' );
			set_theme_mod('zee_copyright_text', "&copy; 2014 CloudMinr's HashWatchr by Chris MacKay. All rights reserved." );
			update_post_meta($this_post->ID, '_wp_page_template', 'page-hashwatchr.php' );
		}
	} else {
	  if ( (!isset($debugMode)) || ( (isset($debugMode)) && ( ($debugMode >= 2) || ($debugMode == 1) ) ) ){
	    print 'Did not find CloudMinr Page<br />'.PHP_EOL;
		}
	  $args = array(
		    'post_author' => $current_user->ID,
				'post_content' => '[cloudminr]',
		    'post_name' => 'CloudMinr',
			  'post_status' => 'publish',
				'post_title' => 'CloudMinr',
			  'post_type' => 'page',
			  'comment_status' => 'closed',
				'ping_status' => 'closed'
		);
		if ( (!isset($debugMode)) || ( (isset($debugMode)) && ( ($debugMode >= 2) || ($debugMode == 0) ) ) ){
		  $post_id = wp_insert_post($args);
			if ( (isset($debugMode)) && ($debugMode >= 1) ){
			  $html .= '<p>id: '.$post_id.'</p>';
			}
			if ($post_id){
			  update_option( 'page_on_front', $post_id );
			  update_option( 'show_on_front', 'page' );
				set_theme_mod('zee_copyright_text', "&copy; 2014 CloudMinr's HashWatchr by Chris MacKay. All rights reserved." );
				update_post_meta( $post_id, '_wp_page_template', 'page-hashwatchr.php' );
			}
		}
	}
	
?>