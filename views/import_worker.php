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
  global $wpdb, $current_user;
	get_currentuserinfo();
	$debugMode = 0;
	if (is_numeric($current_user->ID)){
    if ( ('POST' == $_SERVER['REQUEST_METHOD']) && (isset($_POST['action'])) && ($_POST['action'] == 'import-worker') ){
	    $error = 0;
	    $debug_output = '';
	    if ( (isset($debugMode)) && ($debugMode >= 1) ){
	      $debug_output .= '<h2>Going for processing...</h2>'.PHP_EOL;
		  }
		  if (isset($_POST['user_id'])){
		    $user_id = $_POST['user_id'];
		  } else {
		    if (is_numeric($current_user->ID)){
			    $user_id = $current_user->ID;
			  } else {
		      $user_id = '0';
			  }
		  }
		  if (!isset($_POST['pool_account_id'])){
		    $error = 1;
		  }
		  if ( (isset($_POST['import_ids'])) && (is_array($_POST['import_ids'])) && ($error == 0) ){
		    foreach ($_POST['import_ids'] as $worker){
			    $worker_parts = explode('.', $worker);
				  $worker_id = $worker_parts[0];
				  $worker_name = $worker_parts[1];
				  $sql = "SELECT user_id FROM ".$wpdb->prefix."cloudminr_pools WHERE id='".$_POST['pool_account_id']."' AND active='1' AND locked='0'";
				  $pool_user_id = $wpdb->get_var($sql);
				  if ($pool_user_id != $current_user->ID){
				    $user_id = 0;
				  } else {
				    $user_id = $pool_user_id;
				  }
			    $insert_sql = "INSERT INTO ".$wpdb->prefix."cloudminr_workers SET created_date='".date('Y-m-d')."', created_time='".date('H:i:s')."', created_by_id='".$current_user->ID."', active='1', locked='0', ";
				  $insert_sql .= "pool_account_id='".$_POST['pool_account_id']."', worker_id='".$worker_id."', user_id='".$user_id."', name='".$worker_name."'";
				  if ($wpdb->query($insert_sql)){
				    if ( (isset($debugMode)) && ($debugMode >= 1) ){
	            $debug_output .= '<h2>Yay added '.$worker_id.'</h2>'.PHP_EOL;
		        }
				  } else {
				    $error = 1;
					  if ( (isset($debugMode)) && ($debugMode >= 1) ){
	            $debug_output .= '<h2>Boo failed to add '.$worker_id.'</h2>'.PHP_EOL;
		        }
				  }
			  }
		  	if ( (!isset($error)) || ($error == 0) ){
			    if ( (!isset($debugMode)) || ($debugMode == 0) ){
				  	print redirect_to('./?section=admin&pool='.$_POST['pool_account_id'].'&added=y');
		      } else {
				    $debug_output .= '<h2>Would redirect, but in debugMode</h2>'.PHP_EOL;
			    }
			  } else {
			    if ( (!isset($debugMode)) || ($debugMode == 0) ){
				  	print redirect_to('./?section=import-worker&pool='.$_POST['pool_account_id']);
		      } else {
				    $debug_output .= '<h2>Would redirect, but in debugMode</h2>'.PHP_EOL;
			    }
			  }
		  } else {
		    $error = 1;
		  }
	  }
	  if (!isset($_GET['pool'])){
	    $html .= '<div class="row">'.PHP_EOL;
	      $html .= '<div class="col-md-12">'.PHP_EOL;
			    $html .= '<h2>Import Minrs</h2>'.PHP_EOL;
				  $html .= '<br />'.PHP_EOL;
	        $html .= '<form action="../">';
            $html .= '<select style="text-align: center;" class="form-control" onchange="window.open(this.options[this.selectedIndex].value,';
				    $html .= "'_top')";
				    $html .= '">';
              $html .= '<option value="">Choose a Pool</option>';
						  $pool_count = 0;
					    $sql = "SELECT COUNT(id) FROM ".$wpdb->prefix."cloudminr_pools WHERE active='1' AND user_id='".$current_user->ID."'";
						  $count = $wpdb->get_var($sql);
							$pool_count = $pool_count + $count;
						  if ($count >= 1){
						    $sql = "SELECT * FROM ".$wpdb->prefix."cloudminr_pools WHERE active='1' AND user_id='".$current_user->ID."' ORDER BY pool_name ASC";
						    $pools = $wpdb->get_results($sql);
						    foreach ($pools as $pool){
							    $html .= '<option value="./?section=import-worker&pool='.$pool->id.'">'.$pool->pool_name.'</option>';
							  }
						  }
						  if ($pool_count == 0){
						    $html .= '<option selected="selected" value="">No Pools to display</option>'.PHP_EOL;
						  }
            $html .= '</select>';
          $html .= '</form>';
		    $html .= '</div>'.PHP_EOL;
	    $html .= '</div>'.PHP_EOL;
		  $html .= '<p style="line-height: 6em;">&nbsp;</p>'.PHP_EOL;
	  } else {
	    $action = "getuserworkers";
	    $sql = "SELECT COUNT(pool_name) FROM ".$wpdb->prefix."cloudminr_pools WHERE id='".$_GET['pool']."' AND user_id='".$current_user->ID."' AND active='1' AND locked='0'";
		  $count = $wpdb->get_var($sql);
		  if ($count >= 1){
		    $sql = "SELECT pool_name FROM ".$wpdb->prefix."cloudminr_pools WHERE id='".$_GET['pool']."' AND user_id='".$current_user->ID."' AND active='1' AND locked='0'";
		    $pool_name = $wpdb->get_var($sql);
				$html .= '<div class="row">'.PHP_EOL;
				  $html .= '<div class="col-md-8">'.PHP_EOL;
		  	    $html .= '<h2>Import Minrs Into Pool "'.$pool_name.'"</h2>'.PHP_EOL;
				  $html .= '</div>'.PHP_EOL;
				$html .= '</div>'.PHP_EOL;
				$html .= '<div style="margin: 0 0 5px 0; border-top: 1px solid #C8C8C8;"></div>';
			  $html .= '<form name="cloudminr_add_pool" method="post" action=".">'.PHP_EOL;
			    $sql = "SELECT COUNT(id) FROM ".$wpdb->prefix."cloudminr_workers WHERE pool_account_id='".$_GET['pool']."' AND user_id='".$current_user->ID."' AND active='1' AND locked='0'";
			    $count = $wpdb->get_var($sql);
				  $db_workers = array();
				  if ($count >= 1){
				    $sql = "SELECT worker_id FROM ".$wpdb->prefix."cloudminr_workers WHERE pool_account_id='".$_GET['pool']."' AND user_id='".$current_user->ID."' AND active='1' AND locked='0'";		      
					  $results = $wpdb->get_results($sql);
					  foreach ($results as $result){
					    array_push($db_workers, $result->worker_id);
					  }
				  }
				  if ( (isset($debugMode)) && ($debugMode >= 1) ){
				    print_r($db_workers);
			  	}
		      $sql = "SELECT * FROM ".$wpdb->prefix."cloudminr_pools WHERE active='1' AND id='".$_GET['pool']."'";
				  if ( (isset($debugMode)) && ($debugMode >= 1) ){
			      print '<h2>'.$sql.'</h2>'.PHP_EOL;
			  	}
			    $this_pool = $wpdb->get_row($sql);
				  if ( (isset($debugMode)) && ($debugMode >= 1) ){
			      print_r($this_pool);
		        print '<h2>'.$_GET['pool'].'</h2>'.PHP_EOL;
          }
			    $data_in = json_decode(file_get_contents($this_pool->pool_api_url.'&action='.$action.'&api_key='.$this_pool->pool_api_key.'&id='.$this_pool->pool_api_id));
				  if ( (isset($debugMode)) && ($debugMode >= 1) ){
				    print_r($data_in);
				  }
			    $get_userworkers = $data_in->{$action};
			    $workers = $get_userworkers->{'data'};
				  foreach ($workers as $worker){
				    $html .= '<div class="row">'.PHP_EOL;
					    $html .= '<div class="col-md-1">'.PHP_EOL;
						    $html .= '<h3>'.$worker->{'id'}.'</h3>'.PHP_EOL;
						  $html .= '</div>'.PHP_EOL;
						  $html .= '<div class="col-md-2" style="text-align: center;">'.PHP_EOL;
						    $username_parts = explode('.', $worker->{'username'});
						    $html .= '<h3>'.$username_parts[1].'</h3>'.PHP_EOL;
						  $html .= '</div>'.PHP_EOL;
						  $html .= '<div class="col-md-2">'.PHP_EOL;
						    if (in_array($worker->{'id'}, $db_workers)){							  
							  	$sql = "SELECT name FROM ".$wpdb->prefix."cloudminr_workers WHERE worker_id='".$worker->{'id'}."' AND user_id='".$current_user->ID."' AND active='1' AND locked='0'";
								  $worker_name = $wpdb->get_var($sql);
								  if ($worker_name != $username_parts[1]){
								    $html .= '<h3><label for="import_'.$worker->{'id'}.'">Can be Updated</label></h3>'.PHP_EOL;
									  $html .= '</div><div class="col-md-1">'.PHP_EOL;
								    $html .= '<h3><input type="checkbox" name="import_ids[]" id="import_'.$worker->{'id'}.'" value="'.$worker->{'id'}.'.'.$worker_name.'" style="vertical-align: baseline;"></h3>'.PHP_EOL;
								  } else {
					          $html .= '<h3>Already Exists</h3>'.PHP_EOL;
								  }
					      } else {
					        $html .= '<h3><label for="import_'.$worker->{'id'}.'">Can be Imported</label></h3>'.PHP_EOL;
							  	$html .= '</div><div class="col-md-1">'.PHP_EOL;
							  	$html .= '<h3><input type="checkbox" name="import_ids[]" id="import_'.$worker->{'id'}.'" value="'.$worker->{'id'}.'.'.$username_parts[1].'" style="vertical-align: baseline;"></h3>'.PHP_EOL; 
					      }
						  $html .= '</div>'.PHP_EOL;
					  $html .= '</div>'.PHP_EOL;
						$html .= '<div style="margin: 0 0 5px 0; border-top: 1px solid #C8C8C8;"></div>';
			  	}
					$html .= '<br />'.PHP_EOL;
				  $html .= '<div class="row">'.PHP_EOL;
	          $html .= '<div class="col-md-6">'.PHP_EOL;
		          $html .= '<input type="hidden" name="section" value="import-worker">'.PHP_EOL;
			        $html .= '<input type="hidden" name="action" value="import-worker">'.PHP_EOL;
					  	$html .= '<input type="hidden" name="pool_account_id" value="'.$_GET['pool'].'">'.PHP_EOL;
					  	$html .= '<input type="hidden" name="user_id" value="'.$current_user->ID.'">'.PHP_EOL;
		          $html .= '<button class="btn btn-lg btn-primary btn-block" type="submit">Import</button'.PHP_EOL;
		        $html .= '</div>'.PHP_EOL;
	        $html .= '</div>'.PHP_EOL;
		    $html .= '</form>'.PHP_EOL;
			  $html .= '<p style="line-height: 5em;">&nbsp;</p>'.PHP_EOL;
		  }
	  }
	} else {
	  print redirect_to('./?section=logout');
	}
?>
