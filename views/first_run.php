<?php
/*
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

  @package cloudminr
  @since cloudminr 1.0.0
*/
  global $wpdb, $current_user;
	get_currentuserinfo();
	$debugMode = 0;
	if (isset($_GET['stage'])){
	  $stage = $_GET['stage'];
	} else {
	  if (isset($_POST['stage'])){
		  $stage = $_POST['stage'];
		} else {
	    $stage = 1;
		}
	}
	if ( ('POST' == $_SERVER['REQUEST_METHOD']) && (isset($_POST['action'])) ){
	  switch ($_POST['action']){
		  case 'add-admin':
			  if ( (isset($debugMode)) && ($debugMode >= 1) ){
          print '<pre>';
            print 'First Name: '.$_POST['cloudminr_admin_first_name'].'<br />';
            print 'Last Name: '.$_POST['cloudminr_admin_last_name'].'<br />';
            print 'Email: '.$_POST['cloudminr_admin_email'].'<br />';
          print '</pre>';
        }
				$user_id = username_exists($_POST['cloudminr_admin_email']);
        $random_password = wp_generate_password($length=12, $include_standard_special_chars=false);
        if (!$user_id && email_exists($_POST['cloudminr_admin_email']) == false){
          $user_id = wp_create_user($_POST['cloudminr_admin_email'], $_POST['cloudminr_admin_password'], $_POST['cloudminr_admin_email']);
          if ($user_id){
            if ( !empty($_POST['cloudminr_admin_first_name']) ){
              wp_update_user( array( 'ID' => $user_id, 'cloudminr_admin_first_name' => esc_attr($_POST['cloudminr_admin_first_name']) ) );
            }
            if ( !empty($_POST['cloudminr_admin_last_name']) ){
              wp_update_user( array( 'ID' => $user_id, 'cloudminr_admin_last_name' => esc_attr($_POST['cloudminr_admin_last_name']) ) );
            }
            if ( !empty($_POST['cloudminr_admin_first_name']) ){
              $display_name = $_POST['cloudminr_admin_first_name'];
            }
            if ( !empty($_POST['cloudminr_admin_last_name']) ){
              $display_name .= ' '.$_POST['cloudminr_admin_last_name'];
            }
            if ( !empty($display_name) ){
              wp_update_user( array( 'ID' => $user_id, 'display_name' => esc_attr($display_name) ) );
            }
            if ( !empty($display_name) ){
              wp_update_user( array( 'ID' => $user_id, 'nickname' => esc_attr($display_name) ) );
            }
            wp_update_user( array( 'ID' => $user_id, 'role' => 'cloudminr_admin' ) );
					  if ( !empty($_POST['phone']) ){
					    update_usermeta( $user_id, '_cloudminr_contact_phone', $_POST['phone'] );
					  }
					  if ( !empty($_POST['address']) ){
					    update_usermeta( $user_id, '_cloudminr_contact_address', $_POST['address'] );
					  }
					  if ( !empty($_POST['city']) ){
					    update_usermeta( $user_id, '_cloudminr_contact_city', $_POST['city'] );
					  }
					  if ( !empty($_POST['province']) ){
					    update_usermeta( $user_id, '_cloudminr_contact_province', $_POST['province'] );
					  }
					  if ( !empty($_POST['country']) ){
					    update_usermeta( $user_id, '_cloudminr_contact_country', $_POST['country'] );
					  }
					  if ( !empty($_POST['mailcode']) ){
					    update_usermeta( $user_id, '_cloudminr_contact_mailcode', $_POST['mailcode'] );
					  }
						$nonce = wp_create_nonce( 'log-out' );
		        print redirect_to(get_bloginfo('url').'/wp-login.php?action=logout&redirect_to='.get_bloginfo('url').'&_wpnonce='.$nonce);
          }
        } else {
				  $stage = 1;
				}
			break;
			case 'confirm-first-run':
			  $the_userdata = get_userdata($current_user->ID);
				$loop_count = 0;
				if (!empty($the_userdata->roles)){
		      foreach ($the_userdata->roles as $role){
			      if ($role == 'cloudminr_admin'){
						  $loop_count++;
						}
					}
				}
				if ($loop_count >= 1){
				  update_usermeta( $current_user->ID, 'cloudminr_configured', '1' );
					print redirect_to(get_bloginfo('url'));
				}
			break;
		}
	}
	switch ($stage){
	  case '2':
		  $html .= '<section id="title" class="green-sea" style="padding: 50px;">'.PHP_EOL;
        $html .= '<div class="container">'.PHP_EOL;
          $html .= '<div class="row-fluid">'.PHP_EOL;
            $html .= "<div class='col-xs-6'><h1>Installation</h1></div>".PHP_EOL;
          $html .= '</div>'.PHP_EOL;
        $html .= '</div>'.PHP_EOL;
      $html .= '</section>'.PHP_EOL;
		  $html .= '<div class="containter">'.PHP_EOL;
	      $html .= '<div class="row">'.PHP_EOL;
	        $html .= '<div class="col-md-12">'.PHP_EOL;
					  $the_userdata = get_userdata($current_user->ID);
						$loop_count = 0;
						if (!empty($the_userdata->roles)){
		          foreach ($the_userdata->roles as $role){
			          if ($role == 'cloudminr_admin'){
						      $loop_count++;
						    }
					    }
				    }
					  if ($loop_count >= 1){
					    $html .= '<h3>Brilliant!</h3>'.PHP_EOL;
							$html .= '<br />'.PHP_EOL;
							$html .= '<h4>Now that you have setup a CloudMinr Admin Account, we can continue with the installation.</h4>'.PHP_EOL;
							$html .= '<br />'.PHP_EOL;
							$html .= '<h4>Below, you will find two links - one to each file necessary to collect the data from the added MPOS Pools.</h4>'.PHP_EOL;
							$html .= '<br />'.PHP_EOL;
							$html .= '<h4>The files are "cloudminr_hashwatchr_stats_builder.php" and "cloudminr_hashwatchr_stats_builder_hourly.php".</h4>'.PHP_EOL;
							$html .= '<br />'.PHP_EOL;
							$html .= '<h4>For the best results, they both should be added as cronjobs that run every minute; as follows:</h4>'.PHP_EOL;
							$html .= '<pre>'.PHP_EOL;
							  $html .= '* * * * * /full/path/to/bin/php /full/path/to/stats_builder.php > /dev/null'.PHP_EOL;
                $html .= '* * * * * /full/path/to/bin/php /full/path/to/stats_builder_hourly.php > /dev/null'.PHP_EOL;
							$html .= '</pre>'.PHP_EOL;
							$html .= '<p><strong>NOTE</strong>: Despite its name, "cloudminr_hashwatchr_stats_builder_hourly.php" really should run every minute!</p>'.PHP_EOL;
							$html .= '<h4>To obtain the /full/path/to/bin/php, in an SSH terminal, issue the following:</h4> <pre>which php</pre>'.PHP_EOL;
							$db_settings = '// MySQL settings - You can get this info from your web host'.PHP_EOL;
	            $db_settings .= '// or from your wp-config.php file'.PHP_EOL;
              $db_settings .= '// The name of the database for WordPress'.PHP_EOL;
              $db_settings .= "define('DB_NAME', '".DB_NAME."');".PHP_EOL;
              $db_settings .= '// MySQL database username'.PHP_EOL;
              $db_settings .= "define('DB_USER', '".DB_USER."');".PHP_EOL;
              $db_settings .= '// MySQL database password'.PHP_EOL;
              $db_settings .= "define('DB_PASSWORD', '".DB_PASSWORD."');".PHP_EOL;
              $db_settings .= '// MySQL hostname'.PHP_EOL;
							if (DB_HOST == 'localhost'){
                $db_settings .= "define('DB_HOST', '".$_SERVER['SERVER_NAME']."');".PHP_EOL;
							} else {
							  $db_settings .= "define('DB_HOST', '".DB_HOST."');".PHP_EOL;
							}
	            $db_settings .= '// WordPress Database Table Prefix '.PHP_EOL;
	            $db_settings .= "define('DB_PREFIX', '".$wpdb->prefix."');".PHP_EOL;
              $db_settings .= ' // Make a MySQL Connection'.PHP_EOL;
							$license_file = plugin_dir_path( __FILE__ ).'../bin/license';
							$file_dir = plugin_dir_path( __FILE__ ).'../builders';
							$stats_builder_file = $file_dir.'/cloudminr_hashwatchr_stats_builder.txt';
							if (file_exists($stats_builder_file)){
							  unlink($stats_builder_file);
							}
							$stats_builder_main = plugin_dir_path( __FILE__ ).'../bin/stats_builder_v3_main';								
							// BUILD STATS_BUILDER.PHP
							$file_content = file_get_contents($license_file);
							$file_content .= $db_settings;
							$file_content .= file_get_contents($stats_builder_main);
							// WRITE STATS_BUILDER.PHP
							touch($stats_builder_file);
							$f = fopen($stats_builder_file, 'w');
		          $write = fwrite($f, $file_content);
	            fclose($f);
							// ATTACH STATS_BUILDER.PHP TO WORDPRESS DATABASE
							
							$stats_builder_hourly_file = $file_dir.'/cloudminr_hashwatchr_stats_builder_hourly.txt';
							if (file_exists($stats_builder_hourly_file)){
							  unlink($stats_builder_hourly_file);
							}
							$stats_builder_hourly_main = plugin_dir_path( __FILE__ ).'../bin/stats_builder_hourly_v2_main';
							// BUILD STATS_BUILDER_HOURLY.PHP								
							$file_content = file_get_contents($license_file);
							$file_content .= $db_settings;
							$file_content .= file_get_contents($stats_builder_hourly_main);
							touch($stats_builder_hourly_file);
							$f = fopen($stats_builder_hourly_file, 'w');
		          $write = fwrite($f, $file_content);
	            fclose($f);
							// ATTACH STATS_BUILDER_HOURLY.PHP TO WORDPRESS DATABASE
							
							$html .= '<br />'.PHP_EOL;
							$html .= '<h4>To download the files below, simply Right-Click on the link, and go to "Save link as" (on Chrome) or "Save target as" (on IE), and choose a desired location to Save them.</h4>'.PHP_EOL;
							$html .= '<h4>At this stage you will need to replace the "txt" extension on the files with "php", and upload them to the Linux or FreeBSD server on which you are intending to run them.</h4>'.PHP_EOL;
							$html .= '<br />'.PHP_EOL;
							$html .= '<h4>Make sure you have created the cronjobs, and you are ready to begin collecting data!</h4>'.PHP_EOL;
							$html .= '<br />'.PHP_EOL;
							$html .= '<h1 style="text-align: center;"><a href="'.plugin_dir_url( __FILE__ ).'../builders/cloudminr_hashwatchr_stats_builder.txt">cloudminr_hashwatchr_stats_builder.txt</a>&nbsp;&nbsp;<a href="'.plugin_dir_url( __FILE__ ).'../builders/cloudminr_hashwatchr_stats_builder_hourly.txt">cloudminr_hashwatchr_stats_builder_hourly.txt</a></h1>'.PHP_EOL;
							$html .= '<br />'.PHP_EOL;
							$html .= '<form name="cloudminr_confirm_first_run_form" method="post" action=".">'.PHP_EOL;
							  $html .= '<div class="row">'.PHP_EOL;
	                $html .= '<div class="col-md-12">'.PHP_EOL;
		                $html .= '<input type="hidden" name="section" value="first-run">'.PHP_EOL;
			              $html .= '<input type="hidden" name="stage" value="3">'.PHP_EOL;
								    $html .= '<input type="hidden" name="action" value="confirm-first-run">'.PHP_EOL;
		                $html .= '<button class="btn btn-lg btn-primary btn-block" type="submit">Yes, I have completed this!</button'.PHP_EOL;
		              $html .= '</div>'.PHP_EOL;
	              $html .= '</div>'.PHP_EOL;
							$html .= '</form>'.PHP_EOL;
						} else {
						  $html .= '<h4>You need to <a href="'.wp_logout_url( home_url() ).'">logout</a> as WordPress Admin and log back in as CloudMinr Admin to continue.</h4>'.PHP_EOL;
						}
					$html .= '</div>'.PHP_EOL;
				$html .= '</div>'.PHP_EOL;
			$html .= '</div>'.PHP_EOL;
		break;
	  default:
		  $loop_count = 0;
      foreach ( (array) get_users_of_blog() as $the_user ){
	      $the_userdata = get_userdata($the_user->ID);
		    if (!empty($the_userdata->roles)){
		      foreach ($the_userdata->roles as $role){
			      if ($role == 'cloudminr_admin'){
						  $loop_count++;
						}
					}
				}
			}
			if ($loop_count >= 1){
			  print redirect_to('./?stage=2');
			}
		  $html .= '<section id="title" class="green-sea" style="padding: 50px;">'.PHP_EOL;
        $html .= '<div class="container">'.PHP_EOL;
          $html .= '<div class="row-fluid">'.PHP_EOL;
            $html .= "<div class='col-xs-6'><h1>Welcome!</h1></div>".PHP_EOL;
          $html .= '</div>'.PHP_EOL;
        $html .= '</div>'.PHP_EOL;
      $html .= '</section>'.PHP_EOL;
	    $html .= '<div class="containter">'.PHP_EOL;
	      $html .= '<div class="row">'.PHP_EOL;
	        $html .= '<div class="col-md-12">'.PHP_EOL;
					  $html .= "<h3>Thank you for choosing Cloudminr's HashWatchr!</h3>".PHP_EOL;
		        $html .= '<h4>To continue, we must configure some items.</h4>'.PHP_EOL;
		        $html .= '<br />'.PHP_EOL;
		        $html .= "<h4>Let's start with creating a CloudMinr Admin Account.</h4>".PHP_EOL;
		        $html .= '<p>The CloudMinr Admin Account is a separate Account from your WordPress Admin, and is used only for managing HashWatchr and your Minrs.</p>'.PHP_EOL;
						$html .= '<p>The E-Mail address used for the CloudMinr Admin Account needs to be unique from the one used for the WordPress Admin.</p>'.PHP_EOL;
						$html .= '<p>Once the CloudMinr Admin Account is added, you will be Logged Out and prompted to Log In again, to continue.</p>'.PHP_EOL;
			    $html .= '</div>'.PHP_EOL;
		    $html .= '</div>'.PHP_EOL;
	      $html .= '<div class="row">'.PHP_EOL;
	        $html .= '<div class="col-md-12">'.PHP_EOL;
	          $html .= '<form action="." method="post">';
						  $html .= '<div class="row">'.PHP_EOL;
						    $html .= '<div class="col-md-4">'.PHP_EOL;
						      $html .= '<h3><input class="form-control" required="required" type="text" name="cloudminr_admin_first_name" id="cloudminr_admin_first_name" placeholder="First Name"></h3>'.PHP_EOL;
						    $html .= '</div>'.PHP_EOL;
						    $html .= '<div class="col-md-4">'.PHP_EOL;
						      $html .= '<h3><input class="form-control" required="required" type="text" name="cloudminr_admin_last_name" id="cloudminr_admin_last_name" placeholder="Last Name"></h3>'.PHP_EOL;
						    $html .= '</div>'.PHP_EOL;
					    $html .= '</div>'.PHP_EOL;
							$html .= '<br />'.PHP_EOL;
				      $html .= '<div class="row">'.PHP_EOL;
						    $html .= '<div class="col-md-8">'.PHP_EOL;
						      $html .= '<h3><input class="form-control" required="required" type="text" name="cloudminr_admin_email" id="cloudminr_admin_email" placeholder="E-Mail"></h3>'.PHP_EOL;
						    $html .= '</div>'.PHP_EOL;
					    $html .= '</div>'.PHP_EOL;
							$html .= '<br />'.PHP_EOL;
							$html .= '<div class="row">'.PHP_EOL;
						    $html .= '<div class="col-md-8">'.PHP_EOL;
						      $html .= '<h3><input class="form-control" required="required" type="password" name="cloudminr_admin_password" id="cloudminr_admin_password" placeholder="Password"></h3>'.PHP_EOL;
						    $html .= '</div>'.PHP_EOL;
					    $html .= '</div>'.PHP_EOL;
							$html .= '<br />'.PHP_EOL;
							$html .= '<div class="row">'.PHP_EOL;
	              $html .= '<div class="col-md-8">'.PHP_EOL;
		              $html .= '<input type="hidden" name="section" value="first-run">'.PHP_EOL;
			            $html .= '<input type="hidden" name="action" value="add-admin">'.PHP_EOL;
		              $html .= '<button class="btn btn-lg btn-primary btn-block" type="submit">Save</button'.PHP_EOL;
		            $html .= '</div>'.PHP_EOL;
	            $html .= '</div>'.PHP_EOL;
				    $html .= '</form>'.PHP_EOL;
			    $html .= '</div>'.PHP_EOL;
		    $html .= '</div>'.PHP_EOL;
	    $html .= '</div>'.PHP_EOL;
	  break;
	}
	$html .= '<p style="line-height: 8em;">&nbsp;</p>'.PHP_EOL;
?>
