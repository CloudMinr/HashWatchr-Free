<?php
/*
  Version: 1.5.0
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
	$debugMode = 0;
	global $wpdb;
	if (is_numeric($current_user->ID)) {
	  if (isset($_GET['pool'])){
	    $pool_account_id = $_GET['pool'];
	  } else {
	    $sql = "SELECT id FROM ".$wpdb->prefix."cloudminr_pools WHERE active='1' AND locked='0' AND user_id='".$current_user->ID."' ORDER BY created_date ASC LIMIT 1";
		  $pool_account_id = $wpdb->get_var($sql);
	  }
	  $sql = "SELECT COUNT(id) FROM ".$wpdb->prefix."cloudminr_pools WHERE active='1' AND locked='0' AND user_id='".$current_user->ID."'";
	  $count_id = $wpdb->get_var($sql);
	  if ($count_id >= 1){
		  $html .= '<section id="title" class="emerald" style="padding: 10px;">'.PHP_EOL;
			  $html .= '<div class="container">'.PHP_EOL;
				  $html .= '<div class="row">'.PHP_EOL;
					  $html .= '<div class="col-sm-12">'.PHP_EOL;
						  $html .= '<ul class="nav navbar-nav navbar-main" style="text-align: center;">'.PHP_EOL;
		            $sql = "SELECT * FROM ".$wpdb->prefix."cloudminr_pools WHERE active='1' AND locked='0' AND user_id='".$current_user->ID."'";
			          $cloudminr_pools = $wpdb->get_results($sql);
			          foreach ($cloudminr_pools as $cloudminr_pool){
				          $action = 'getuserbalance';
				          $json = @file_get_contents($cloudminr_pool->pool_api_url.'&action='.$action.'&api_key='.$cloudminr_pool->pool_api_key.'&id='.$cloudminr_pool->pool_api_id);
					        if ($json === FALSE){
					          if (isset($balances)){
					            unset($balances);
						        }
					        } else {
				            $data_in = json_decode($json);
				            if ( (isset($debugMode)) && ($debugMode >= 1) ){
				              print_r($data_in);
				            }
					          if ($data_in){
			                $getuserbalance = $data_in->{$action};
				              $balances = $getuserbalance->{'data'};
				              if ( (isset($debugMode)) && ($debugMode >= 1) ){
						            print_r($getuserbalance);
				                print_r($balances);
				              }
						        }
					        }
					        $action = 'getuserstatus';
				          $json = @file_get_contents($cloudminr_pool->pool_api_url.'&action='.$action.'&api_key='.$cloudminr_pool->pool_api_key.'&id='.$cloudminr_pool->pool_api_id);
					        if ($json === FALSE){
					          if (isset($shares)){
					            unset($shares);
						        }
					        } else {
				            $data_in = json_decode($json);
				            if ( (isset($debugMode)) && ($debugMode >= 1) ){
				              print_r($data_in);
				            }
					          if ($data_in){
			                $getuserstatus = $data_in->{$action};
				              $data_in = $getuserstatus->{'data'};
							        $shares = $data_in->{'shares'};
				              if ( (isset($debugMode)) && ($debugMode >= 1) ){
						            print_r($getuserstatus);
				                print_r($shares);
				              }
						        }
					        }
					        if ( (isset($pool_account_id)) && ($pool_account_id == $cloudminr_pool->id) ){
									  $html .= '<li class="menu-item menu-item-type-custom menu-item-object-custom current-menu-item">'.PHP_EOL;
									} else {
									  $html .= '<li class="menu-item menu-item-type-custom menu-item-object-custom">'.PHP_EOL;
									}
									  $html .= '<a href="./?section=minrs&pool='.$cloudminr_pool->id.'">';
						          $html .= '<h2 style="margin: 0;">'.$cloudminr_pool->pool_name.'</h2>';
							        if (isset($balances)){
							          $html .= '<h4 style="margin: 0; padding: 0;">'.$balances->confirmed.' '.$cloudminr_pool->pool_currency.'</h4>';
							        }
							        if (isset($shares)){
							          if ($shares->valid == 1){
								          $html .= '<h4 style="margin: 0; padding: 0;">'.$shares->valid.' Share</h4>';
								        } else {
								          $html .= '<h4 style="margin: 0; padding: 0;">'.$shares->valid.' Shares</h4>';
								        }
							        }
						        $html .= '</a>'.PHP_EOL;
									$html .= '</li>'.PHP_EOL;
								}
							$html .= '</ul>'.PHP_EOL;
		        $html .= '</div>'.PHP_EOL;
			    $html .= '</div>'.PHP_EOL;
	      $html .= '</div>'.PHP_EOL;
		  $html .= '</section>'.PHP_EOL;
		  $html .= '<div class="row">'.PHP_EOL;
		    $html .= '<div class="col-sm-2"><h3><strong>';
			    if (isset($_GET['order_by'])){
				    if (isset($_GET['dir'])){
					    if ($_GET['dir'] == 'ASC'){
						    $html .= '<a href="./?section=minrs&pool='.$pool_account_id.'&order_by=name&dir=DESC" style="color: #000;">Username</a>';
						  } else {
						    $html .= '<a href="./?section=minrs&pool='.$pool_account_id.'&order_by=name" style="color: #000;">Username</a>';
						  }
					  } else {
					    $html .= '<a href="./?section=minrs&pool='.$pool_account_id.'&order_by=name&dir=DESC" style="color: #000;">Username</a>';
					  }
				  } else {
			      $html .= '<a href="./?section=minrs&pool='.$pool_account_id.'&order_by=name" style="color: #000;">Username</a>';
				  }
			  $html .= '</strong></h3></div>'.PHP_EOL;
	      $html .= '<div class="col-sm-2"><h3><strong>';
			    if (isset($_GET['order_by'])){
				    if (isset($_GET['dir'])){
					    if ($_GET['dir'] == 'ASC'){
						    $html .= '<a href="./?section=minrs&pool='.$pool_account_id.'&order_by=worker_id&dir=DESC" style="color: #000;">ID</a>';
						  } else {
						    $html .= '<a href="./?section=minrs&pool='.$pool_account_id.'&order_by=worker_id" style="color: #000;">ID</a>';
						  }
					  } else {
					    $html .= '<a href="./?section=minrs&pool='.$pool_account_id.'&order_by=worker_id&dir=DESC" style="color: #000;">ID</a>';
					  }
				  } else {
			      $html .= '<a href="./?section=minrs&pool='.$pool_account_id.'&order_by=worker_id" style="color: #000;">ID</a>';
				  }
			  $html .= '</strong></h3></div>'.PHP_EOL;
	      $html .= '<div class="col-sm-2"><h3><strong>Pool</strong></h3></div>'.PHP_EOL;
	      $html .= '<div class="col-sm-2"><h3><strong>Hashrate</h3></strong></div>'.PHP_EOL;
			  $html .= '<div class="col-sm-2 col-sm-offset-2"><h3><strong>Actions</h3></strong></div>'.PHP_EOL;
	    $html .= '</div>'.PHP_EOL;
		  $html .= '<div style="margin: 0 0 5px 0; border-top: 1px solid #C0C0C0;"></div>';
		  $sql = "SELECT batch_id FROM ".$wpdb->prefix."cloudminr_batches WHERE active='1' AND locked='0' and pool_account_id='".$pool_account_id."' AND user_id='".$current_user->ID."' ORDER BY updated DESC LIMIT 1";
		  $batch_id = $wpdb->get_var($sql);
		  if ((empty($batch_id)) || ($batch_id < 1)){
		    $batch_id = 0;
		  }
			$sql = "SELECT COUNT(id) FROM ".$wpdb->prefix."cloudminr_workers WHERE pool_account_id='".$pool_account_id."' AND user_id='".$current_user->ID."' AND active='1' AND locked='0' ORDER BY name ASC";
		  $count = $wpdb->get_var($sql);
		  if ($count >= 1){
		    if (isset($_GET['order_by'])){
				  if (isset($_GET['dir'])){
				    $sql = "SELECT id, updated, pool_account_id, name, worker_id FROM ".$wpdb->prefix."cloudminr_workers WHERE pool_account_id='".$pool_account_id."' AND user_id='".$current_user->ID."' AND active='1' AND locked='0' ORDER BY ".$_GET['order_by']." ".$_GET['dir'];
				  } else {
				    $sql = "SELECT id, updated, pool_account_id, name, worker_id FROM ".$wpdb->prefix."cloudminr_workers WHERE pool_account_id='".$pool_account_id."' AND user_id='".$current_user->ID."' AND active='1' AND locked='0' ORDER BY ".$_GET['order_by']." ASC";
				  }
			  } else {
				  $sql = "SELECT id, updated, pool_account_id, name, worker_id FROM ".$wpdb->prefix."cloudminr_workers WHERE pool_account_id='".$pool_account_id."' AND user_id='".$current_user->ID."' AND active='1' AND locked='0' ORDER BY name ASC";				  
			  }
			  $results = $wpdb->get_results($sql);
			  $hashrate_total = 0;
			  foreach ($results as $result){
			    $html .= '<div class="row">'.PHP_EOL;
					  $sql = "SELECT pool_name FROM ".$wpdb->prefix."cloudminr_pools WHERE id='".$result->pool_account_id."' AND active='1' AND locked='0'";
					  $pool_name = $wpdb->get_var($sql);
					  $html .= '<div class="col-sm-2"><h3>'.$result->name.'</h3></div>'.PHP_EOL;
					  $html .= '<div class="col-sm-2"><h3>'.$result->worker_id.'</h3></div>'.PHP_EOL;
				    $html .= '<div class="col-sm-2"><h3>'.$pool_name.'</h3></div>'.PHP_EOL;
					  $sql = "SELECT hashrate FROM ".$wpdb->prefix."cloudminr_stats_".$current_user->ID."_".$result->pool_account_id."_".$result->worker_id." WHERE batch_id='".$batch_id."' AND pool_account_id='".$result->pool_account_id."' AND worker_id='".$result->worker_id."' AND user_id='".$current_user->ID."' AND active='1' AND locked='0' ORDER BY created_date DESC LIMIT 1";
					  $hashrate = $wpdb->get_var($sql);
					  if (!isset($hashrate)){
					    $hashrate_display = 'No Data';
					  } else {
					    $hashrate_display = $hashrate.' KH/s';
					  }
		        $html .= '<div class="col-sm-2"><h3>'.$hashrate_display.'</h3></div>'.PHP_EOL;
					  if ($active_theme_name == 'Flat Theme'){
					    $html .= '<div class="col-sm-2 col-sm-offset-2"><h4><a href="./?section=charts&pool='.$result->pool_account_id.'&worker='.$result->id.'"><i class="icon-2x icon-bar-chart"></i></h4></a></div>'.PHP_EOL;
					  } else {
					    $html .= '<div class="col-sm-2 col-sm-offset-2"><h3><a href="./?section=charts&pool='.$result->pool_account_id.'&worker='.$result->id.'">View Chart</h3></a></div>'.PHP_EOL;
					  }
		      $html .= '</div>'.PHP_EOL;
			    $html .= '<div style="margin: 0 0 5px 0; border-top: 1px solid #C8C8C8;"></div>';
				  $hashrate_total = $hashrate_total + $hashrate;
					$updated = $result->updated;
			  }
		  } else {
		    $html .= '<div class="row">'.PHP_EOL;
			    $html .= '<div class="col-sm-8"><h4>No Minrs to display</h4></div>'.PHP_EOL;
			  $html .= '</div>'.PHP_EOL;
			  $html .= '<div style="margin: 0 0 5px 0; border-top: 1px solid #C8C8C8;"></div>';
		  }
		  if (isset($hashrate_total)){
	      $html .= '<div class="row">'.PHP_EOL;
			    $html .= '<div class="col-sm-4">&nbsp;</div>'.PHP_EOL;
		        $html .= '<div class="col-sm-2">'.PHP_EOL;
		          $html .= '<h2 style="text-align: right;"><strong>Total</strong></h2>'.PHP_EOL;
		        $html .= '</div>'.PHP_EOL;
	          $html .= '<div class="col-sm-3">'.PHP_EOL;
			        $html .= '<h2 style="text-align: left;"><strong>'.$hashrate_total.' Kh/s</strong></h2>'.PHP_EOL;
		        $html .= '</div>'.PHP_EOL;
	        $html .= '</div>'.PHP_EOL;
	      $html .= '</div>'.PHP_EOL;
		  }
	  } else {
	    $html .= '<div class="row">'.PHP_EOL;
		    $html .= '<div class="col-sm-2"><h3><strong>Username</strong></h3></div>'.PHP_EOL;
	      $html .= '<div class="col-sm-2"><h3><strong>ID</strong></h3></div>'.PHP_EOL;
	      $html .= '<div class="col-sm-2"><h3><strong>Pool</strong></h3></div>'.PHP_EOL;
	      $html .= '<div class="col-sm-2"><h3><strong>Hashrate</h3></strong></div>'.PHP_EOL;
	    $html .= '</div>'.PHP_EOL;
		  $html .= '<div style="margin: 0 0 5px 0; border-top: 1px solid #C8C8C8;"></div>';
		  $html .= '<div class="row">'.PHP_EOL;
		    $html .= '<div class="col-sm-8"><h4>No Minrs in any <a href="'.get_bloginfo('url').'/?section=pools">Pools</a> to display</h4></div>'.PHP_EOL;
		  $html .= '</div>'.PHP_EOL;
		  $html .= '<div style="margin: 0 0 5px 0; border-top: 1px solid #C8C8C8;"></div>';
	  }
	  if ($count <= 1){
	    $html .= '<p style="line-height: 5em;">&nbsp;</p>'.PHP_EOL;
	  }
		$updated_time_parts = explode(' ', $updated);
		$updated_time = trim($updated_time_parts[1]);
		$updated_time = strtotime($updated_time);
		$now_time = strtotime(date('H:i:s'));
		$minute = strtotime('1:00');
		//$html .= '<h3>'.$updated_time.' - '.$now_time.'</h3>'.PHP_EOL;
		$difference_time = $now_time - $updated_time;
    //$html .= '<h2>'.$difference_time.' '.date('H:i:s', $difference_time).'</h2>'.PHP_EOL;
	  $html .= '<script language="JavaScript" type="text/javascript">'.PHP_EOL;
	    $html .= 'setTimeout(function(){'.PHP_EOL;
		      $html .= 'window.location.reload(1);'.PHP_EOL;
      $html .= '}, 45000);'.PHP_EOL;
    $html .= '</script>'.PHP_EOL;
	} else {
	  print redirect_to('./?section=logout');
	}
?>
