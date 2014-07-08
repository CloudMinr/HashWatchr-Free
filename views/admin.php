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
	$debugMode = 0;
	if (is_numeric($current_user->ID)) {
	  global $wpdb;
	  if (isset($_GET['pool'])){
	    $pool_account_id = $_GET['pool'];
	  } else {
	    $sql = "SELECT id FROM ".$wpdb->prefix."cloudminr_pools WHERE active='1' AND locked='0' AND user_id='".$current_user->ID."' ORDER BY created_date ASC LIMIT 1";
		  $pool_account_id = $wpdb->get_var($sql);
	  }
	  $sql = "SELECT COUNT(id) FROM ".$wpdb->prefix."cloudminr_pools WHERE active='1' AND locked='0' AND user_id='".$current_user->ID."'";
	  $count = $wpdb->get_var($sql);
	  if ($count >= 2){
	    $html .= '<div class="row">'.PHP_EOL;
		    $sql = "SELECT id, pool_name FROM ".$wpdb->prefix."cloudminr_pools WHERE active='1' AND locked='0' AND user_id='".$current_user->ID."'";
		  	$cloudminr_pools = $wpdb->get_results($sql);
		  	foreach ($cloudminr_pools as $cloudminr_pool){
			    $html .= '<div class="col-sm-2" style="text-align: center; border: solid 1px #c3c3c3;">'.PHP_EOL;
				    if ($pool_account_id == $cloudminr_pool->id){
					    $html .= '<a href="./?section=admin&pool='.$cloudminr_pool->id.'"><h2><u>'.$cloudminr_pool->pool_name.'</u></h2></a>'.PHP_EOL;
					  } else {
		          $html .= '<a href="./?section=admin&pool='.$cloudminr_pool->id.'"><h2>'.$cloudminr_pool->pool_name.'</h2></a>'.PHP_EOL;
					  }
		      $html .= '</div>'.PHP_EOL;
			  }
	    $html .= '</div>'.PHP_EOL;
	  }
	  $html .= '<div class="row">'.PHP_EOL;
	    $html .= '<div class="col-sm-2"><h3>Username</strong></h3></div>'.PHP_EOL;
	    $html .= '<div class="col-sm-2"><h3><strong>ID</strong></h3></div>'.PHP_EOL;
	    $html .= '<div class="col-sm-2"><h3><strong>Pool</strong></h3></div>'.PHP_EOL;
		  $html .= '<div class="col-sm-2 col-sm-offset-4" style="text-align: center;"><h3><strong>Actions</strong></h3></div>'.PHP_EOL;
	  $html .= '</div>'.PHP_EOL;
	  $html .= '<div style="margin: 0 0 5px 0; border-top: 1px solid #C8C8C8;"></div>';
	  $sql = "SELECT COUNT(id) FROM ".$wpdb->prefix."cloudminr_workers WHERE active='1' AND pool_account_id='".$pool_account_id."' ORDER BY name ASC";
	  $count = $wpdb->get_var($sql);
	  if ($count >= 1){
	    $sql = "SELECT * FROM ".$wpdb->prefix."cloudminr_workers WHERE active='1' AND pool_account_id='".$pool_account_id."' ORDER BY name ASC";
		  $cloudminr_workers = $wpdb->get_results($sql);
		  foreach ($cloudminr_workers as $cloudminr_worker){
		    $sql = "SELECT pool_name FROM ".$wpdb->prefix."cloudminr_pools WHERE active='1' AND locked='0' AND id='".$cloudminr_worker->pool_account_id."'";
			  $pool_name = $wpdb->get_var($sql);
		    $html .= '<div class="row">'.PHP_EOL;
	        $html .= '<div class="col-sm-2"><h4>'.$cloudminr_worker->name.'</h4></div>'.PHP_EOL;
	        $html .= '<div class="col-sm-2"><h4>'.$cloudminr_worker->worker_id.'</h4></div>'.PHP_EOL;
	        $html .= '<div class="col-sm-2"><h4>'.$pool_name.'</h4></div>'.PHP_EOL;
				  if ($the_plugin_header == 'internal'){
				    $html .= '<div class="col-sm-2 col-sm-offset-4" style="text-align: center;"><h4><a href="./?section=edit-worker&worker='.$cloudminr_worker->id.'">Edit</a></h4></div>'.PHP_EOL;
				  } else {
				    $html .= '<div class="col-sm-2 col-sm-offset-4" style="text-align: center;"><h5>'.PHP_EOL;
					    $html .= '<a href="./?section=edit-worker&worker='.$cloudminr_worker->id.'"><i class="icon-2x icon-edit"></i></a>'.PHP_EOL;
					    $html .= '<a href="./?section=charts-beta&pool='.$cloudminr_worker->pool_account_id.'&worker='.$cloudminr_worker->id.'"><i class="icon-2x icon-bar-chart"></i></a>'.PHP_EOL;
					  $html .= '</h5></div>'.PHP_EOL;
				  }
	      $html .= '</div>'.PHP_EOL;
			  $html .= '<div style="margin: 0 0 5px 0; border-top: 1px solid #C8C8C8;"></div>';
		  }
	  }	else {
		  $html .= '<div class="row">'.PHP_EOL;
		    $html .= '<div class="col-sm-8"><h4>No Minrs to display</h4></div>'.PHP_EOL;
		  $html .= '</div>'.PHP_EOL;
		  $html .= '<div style="margin: 0 0 5px 0; border-top: 1px solid #C8C8C8;"></div>';
	  }
  } else {
	  print redirect_to('./?section=logout');
	}	
?>
