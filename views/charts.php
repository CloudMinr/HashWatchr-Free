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
  global $wpdb;
  $debugMode = 0;
	if (is_numeric($current_user->ID)) {
	  if (isset($_GET['action'])){
	    $action = $_GET['action'];
	  } else {
	    if (isset($_POST['action'])){
		    $action = $_POST['action'];
		  } else {
			  $action = 'view';
		  }
	  }
	  if (is_numeric($current_user->ID)) {
	    if (isset($_GET['worker'])){
		    $sql = "SELECT pool_account_id FROM ".$wpdb->prefix."cloudminr_workers WHERE active='1' AND locked='0' AND id='".$_GET['worker']."' AND user_id='".$current_user->ID."'";
			  $worker_id_sql = "SELECT worker_id FROM ".$wpdb->prefix."cloudminr_workers WHERE active='1' AND locked='0' AND id='".$_GET['worker']."' AND user_id='".$current_user->ID."'";
			  $worker_id = $wpdb->get_var($worker_id_sql);
		  } elseif (isset($_GET['pool'])){
		    $sql = "SELECT id FROM ".$wpdb->prefix."cloudminr_pools WHERE active='1' AND locked='0' AND id='".$_GET['pool']."' AND user_id='".$current_user->ID."'";
		  } else {
		    $sql = "SELECT id FROM ".$wpdb->prefix."cloudminr_pools WHERE active='1' AND locked='0' AND user_id='".$current_user->ID."' ORDER BY updated DESC LIMIT 1";
		  }
	  }
	  $pool_account_id = $wpdb->get_var($sql);
	  $sql = "SELECT COUNT(id) FROM ".$wpdb->prefix."cloudminr_pools WHERE active='1' AND locked='0' AND user_id='".$current_user->ID."'";
	  $count_id = $wpdb->get_var($sql);
	  if ($count_id >= 2){
	    $html .= '<section id="title" class="emerald" style="padding: 10px;">'.PHP_EOL;
			  $html .= '<div class="container">'.PHP_EOL;
			    $html .= '<div class="row">'.PHP_EOL;
				    $html .= '<div class="col-sm-12">'.PHP_EOL;
					    $html .= '<ul class="nav navbar-nav navbar-main" style="text-align: center;">'.PHP_EOL;
                $sql = "SELECT id, pool_name FROM ".$wpdb->prefix."cloudminr_pools WHERE active='1' AND locked='0' AND user_id='".$current_user->ID."'";
			          $cloudminr_pools = $wpdb->get_results($sql);
			          foreach ($cloudminr_pools as $cloudminr_pool){
							    if ($cloudminr_pool->id == $pool_account_id){
								    $html .= '<li class="menu-item menu-item-type-custom menu-item-object-custom current-menu-item">'.PHP_EOL;
								  } else {
								    $html .= '<li class="menu-item menu-item-type-custom menu-item-object-custom">'.PHP_EOL;
								  }
								    $html .= '<a href="./?section=charts&pool='.$cloudminr_pool->id.'"><h2>'.$cloudminr_pool->pool_name.'</h2></a>'.PHP_EOL;
								  $html .= '</li>'.PHP_EOL;
			          }
						  $html .= '</ul>'.PHP_EOL;
					  $html .= '</div>'.PHP_EOL;
				  $html .= '</div>'.PHP_EOL;
			  $html .= '</div>'.PHP_EOL;
	    $html .= '</section>'.PHP_EOL;
	  }
	  $dropdown_html = '<div class="row">'.PHP_EOL;
	    $dropdown_html .= '<div class="col-md-12" style="text-align: center;">'.PHP_EOL;
		    $dropdown_html .= '<p>&nbsp;</p>'.PHP_EOL;
	      $dropdown_html .= '<form action="../">';
          $dropdown_html .= '<select class="form-control" style="width: 100%;" onchange="window.open(this.options[this.selectedIndex].value,';
				  $dropdown_html .= "'_top')";
				  $dropdown_html .= '">';
            $dropdown_html .= '<option value="">Choose a Chrt</option>';
					  $sql = "SELECT COUNT(id) FROM ".$wpdb->prefix."cloudminr_workers WHERE active='1' AND user_id='".$current_user->ID."'";
						$count = $wpdb->get_var($sql);
						if ($count >= 1){
						  $sql = "SELECT * FROM ".$wpdb->prefix."cloudminr_workers WHERE active='1' AND user_id='".$current_user->ID."' ORDER BY name ASC";
						  $workers = $wpdb->get_results($sql);
						  foreach ($workers as $worker){
						    unset($pool_name);
						    $sql = "SELECT pool_name FROM ".$wpdb->prefix."cloudminr_pools WHERE id='".$worker->pool_account_id."' AND active='1'";
							  $pool_name = $wpdb->get_var($sql);
						    $dropdown_html .= '<option value="./?section=charts&worker='.$worker->id.'">'.$worker->name.' ('.$pool_name.')</option>';
						  }
						}
          $dropdown_html .= '</select>';
        $dropdown_html .= '</form>';
		  $dropdown_html .= '</div>'.PHP_EOL;
	  $dropdown_html .= '</div>'.PHP_EOL;
	  $dropdown_html .= '<div class="row">'.PHP_EOL;
	    $dropdown_html .= '<div class="col-md-12" style="text-align: center;">'.PHP_EOL;
		    $dropdown_html .= '<p>&nbsp;</p>'.PHP_EOL;
	      $dropdown_html .= '<form action="../">';
          $dropdown_html .= '<select class="form-control" style="width: 100%;" onchange="window.open(this.options[this.selectedIndex].value,';
				  $dropdown_html .= "'_top')";
				  $dropdown_html .= '">';
            $dropdown_html .= '<option value="">Choose a View</option>';
				  	if ( (isset($_GET['time'])) && ($_GET['time'] == 'day') ){
					    if (isset($_GET['worker'])){
						    $dropdown_html .= '<option selected="selected" value="./?section=charts&pool='.$pool_account_id.'&worker='.$_GET['worker'].'">The Last Hour</option>'.PHP_EOL;
						  } else {
					      $dropdown_html .= '<option selected="selected" value="./?section=charts&pool='.$pool_account_id.'">The Last Hour</option>'.PHP_EOL;
						  }
					  } else {
					    if (isset($_GET['worker'])){
						    $dropdown_html .= '<option value="./?section=charts&pool='.$pool_account_id.'&worker='.$_GET['worker'].'">The Last Hour</option>'.PHP_EOL;
						  } else {
					      $dropdown_html .= '<option value="./?section=charts&pool='.$pool_account_id.'">The Last Hour</option>'.PHP_EOL;
						  }
					  }
				  	if ( (isset($_GET['time'])) && ($_GET['time'] == 'day') ){
					    if (isset($_GET['worker'])){
						    $dropdown_html .= '<option selected="selected" value="./?section=charts&pool='.$pool_account_id.'&worker='.$_GET['worker'].'&time=day">The Last 24 Hours</option>'.PHP_EOL;
						  } else {
					      $dropdown_html .= '<option selected="selected" value="./?section=charts&pool='.$pool_account_id.'&time=day">The Last 24 Hours</option>'.PHP_EOL;
						  }
					  } else {
					    if (isset($_GET['worker'])){
						    $dropdown_html .= '<option value="./?section=charts&pool='.$pool_account_id.'&worker='.$_GET['worker'].'&time=day">The Last 24 Hours</option>'.PHP_EOL;
					  	} else {
					      $dropdown_html .= '<option value="./?section=charts&pool='.$pool_account_id.'&time=day">The Last 24 Hours</option>'.PHP_EOL;
					  	}
				  	}
				  	if ( (isset($_GET['time'])) && ($_GET['time'] == 'day-to-minute') ){
					    if (isset($_GET['worker'])){
						    $dropdown_html .= '<option selected="selected" value="./?section=charts&pool='.$pool_account_id.'&worker='.$_GET['worker'].'&time=day-to-minute">The Last 24 Hours To-The-Minute (SLOW)</option>'.PHP_EOL;
						  } else {
					      $dropdown_html .= '<option selected="selected" value="./?section=charts&pool='.$pool_account_id.'&time=day-to-minute">The Last 24 Hours To-The-Minute (SLOW)</option>'.PHP_EOL;
						  }
					  } else {
					    if (isset($_GET['worker'])){
						    $dropdown_html .= '<option value="./?section=charts&pool='.$pool_account_id.'&worker='.$_GET['worker'].'&time=day-to-minute">The Last 24 Hours To-The-Minute (SLOW)</option>'.PHP_EOL;
						  } else {
					      $dropdown_html .= '<option value="./?section=charts&pool='.$pool_account_id.'&time=day-to-minute">The Last 24 Hours To-The-Minute (SLOW)</option>'.PHP_EOL;
						  }
					  }
						if ( (isset($_GET['time'])) && ($_GET['time'] == 'week') ){
					    if (isset($_GET['worker'])){
						    $dropdown_html .= '<option selected="selected" value="./?section=charts&pool='.$pool_account_id.'&worker='.$_GET['worker'].'&time=week">The Last Week)</option>'.PHP_EOL;
						  } else {
					      $dropdown_html .= '<option selected="selected" value="./?section=charts&pool='.$pool_account_id.'&time=week">The Last Week</option>'.PHP_EOL;
						  }
					  } else {
					    if (isset($_GET['worker'])){
						    $dropdown_html .= '<option value="./?section=charts&pool='.$pool_account_id.'&worker='.$_GET['worker'].'&time=week">The Last Week</option>'.PHP_EOL;
						  } else {
					      $dropdown_html .= '<option value="./?section=charts&pool='.$pool_account_id.'&time=week">The Last Week</option>'.PHP_EOL;
						  }
					  }
				  $dropdown_html .= '</select>'.PHP_EOL;
			  $dropdown_html .= '</form>'.PHP_EOL;
		  $dropdown_html .= '</div>'.PHP_EOL;
	  $dropdown_html .= '</div>'.PHP_EOL;
	  $html .= '<div class="row">'.PHP_EOL;
	    $html .= '<div class="col-md-12">'.PHP_EOL;
	      $html .= '<div id="cloudminr_stats_the_last_24hrs" style="width: 100%; height: 500px;"></div>'.PHP_EOL;
		  $html .= '</div>'.PHP_EOL;
	  $html .= '</div>'.PHP_EOL;
    if (isset($_GET['time'])){
	    $time = $_GET['time'];
	  } else {
	    $time = "hour";
	  }
    if (is_numeric($current_user->ID)) {
	    if (isset($_GET['worker'])){
		    $sql = "SELECT COUNT(pool_account_id) FROM ".$wpdb->prefix."cloudminr_workers WHERE active='1' AND locked='0' AND id='".$_GET['worker']."' AND user_id='".$current_user->ID."'";
		  } elseif (isset($_GET['pool'])) {
		    $sql = "SELECT COUNT(id) FROM ".$wpdb->prefix."cloudminr_pools WHERE active='1' AND locked='0' AND id='".$_GET['pool']."' AND user_id='".$current_user->ID."' ORDER BY updated DESC LIMIT 1";
		  } else {
		    $sql = "SELECT COUNT(id) FROM ".$wpdb->prefix."cloudminr_pools WHERE active='1' AND locked='0' AND user_id='".$current_user->ID."' ORDER BY updated DESC LIMIT 1";
		  }
	  } else {
	    $sql = 0;
	  }
	  if (!is_numeric($sql)){
	    $count = $wpdb->get_var($sql);
		  if ($count >= 1){
		    if (is_numeric($current_user->ID)) {
	        if (isset($_GET['worker'])){
		        $sql = "SELECT pool_account_id FROM ".$wpdb->prefix."cloudminr_workers WHERE active='1' AND locked='0' AND id='".$_GET['worker']."' AND user_id='".$current_user->ID."'";
					  $worker_id_sql = "SELECT worker_id FROM ".$wpdb->prefix."cloudminr_workers WHERE active='1' AND locked='0' AND id='".$_GET['worker']."' AND user_id='".$current_user->ID."'";
					  $worker_id = $wpdb->get_var($worker_id_sql);
		      } elseif (isset($_GET['pool'])){
		        $sql = "SELECT id FROM ".$wpdb->prefix."cloudminr_pools WHERE active='1' AND locked='0' AND id='".$_GET['pool']."' AND user_id='".$current_user->ID."'";
		      } else {
		        $sql = "SELECT id FROM ".$wpdb->prefix."cloudminr_pools WHERE active='1' AND locked='0' AND user_id='".$current_user->ID."' ORDER BY updated DESC LIMIT 1";
		      }
	      }
			  $pool_account_id = $wpdb->get_var($sql);
			  if (is_numeric($current_user->ID)) {
			    if (isset($_GET['worker'])){
				    $sql = "SELECT COUNT(id) FROM ".$wpdb->prefix."cloudminr_stats_".$current_user->ID."_".$pool_account_id."_".$worker_id." WHERE active='1' AND locked='0' AND pool_account_id='".$pool_account_id."' AND user_id='".$current_user->ID."' AND worker_id='".$worker_id."' ORDER BY updated DESC";
				  } else {
			      $sql = "SELECT COUNT(DISTINCT(batch_id)) FROM ".$wpdb->prefix."cloudminr_batches WHERE active='1' AND locked='0' AND pool_account_id='".$pool_account_id."' AND user_id='".$current_user->ID."' ORDER BY updated DESC";
				  }
			  }
				
			  $count = $wpdb->get_var($sql);
			  if ($count >= 1){
			    // BEGIN BUILD CHRTS
				  $file_content = '<script type="text/javascript">';
				  $file_content .= 'google.load("visualization", "1", {packages:["corechart"]});';
          $file_content .= 'google.setOnLoadCallback(drawChart);';
          $file_content .= 'function drawChart() {';
				  $file_content .= 'var data = google.visualization.arrayToDataTable([';
				  if (is_numeric($current_user->ID)) {
					  if (isset($_GET['worker'])){
						  //BEGIN WORKER CHARTS
					    switch ($time){
						    case 'day':
						      $sql = "SELECT DISTINCT batch_id FROM ".$wpdb->prefix."cloudminr_stats_hourly_".$current_user->ID."_".$pool_account_id."_".$worker_id." WHERE active='1' AND locked='0' AND pool_account_id='".$pool_account_id."'AND user_id='".$current_user->ID."' AND worker_id='".$worker_id."' ORDER BY updated DESC LIMIT 29";
						 	  break;
						 	  case 'day-to-minute':
						      $sql = "SELECT DISTINCT batch_id FROM ".$wpdb->prefix."cloudminr_stats_".$current_user->ID."_".$pool_account_id."_".$worker_id." WHERE active='1' AND locked='0' AND pool_account_id='".$pool_account_id."' AND user_id='".$current_user->ID."' AND worker_id='".$worker_id."' ORDER BY updated DESC LIMIT 1728";
						    break;
							  case 'week':
						      $sql = "SELECT DISTINCT batch_id FROM ".$wpdb->prefix."cloudminr_stats_hourly_".$current_user->ID."_".$pool_account_id."_".$worker_id." WHERE active='1' AND locked='0' AND pool_account_id='".$pool_account_id."'AND user_id='".$current_user->ID."' AND worker_id='".$worker_id."' ORDER BY updated DESC LIMIT 173";
						   	break;
						    default:
						      $sql = "SELECT DISTINCT batch_id FROM ".$wpdb->prefix."cloudminr_stats_".$current_user->ID."_".$pool_account_id."_".$worker_id." WHERE active='1' AND locked='0' AND pool_account_id='".$pool_account_id."'AND user_id='".$current_user->ID."' AND worker_id='".$worker_id."' ORDER BY updated DESC LIMIT 65";
						    break;
						  }
				      $batches = $wpdb->get_results($sql);					
				      $batch_count = count($batches);
				      $error = 0;
				      switch ($time){
				        case 'day':
					        if ($batch_count == 0){
						        $error = 1;
						      }
					      break;
				        case 'day-to-minute':
					        if ($batch_count == 0){
						        $error = 1;
						      }
					      break;
						    case 'week':
					        if ($batch_count == 0){
						        $error = 1;
						      }
					      break;
					      default:
					        if ($batch_count == 0){
						        $error = 1;
						      }
					      break;
				      }
				      if ($error == 0){
				        $file_data = "['Time', 'Hashrate', 'Average'],";
					      $this_batch_count = 0;
					      $batches = array_reverse($batches);
					      $hashrate_master_total = 0;
						    $loop_count = 0;
				        foreach ($batches as $batch){
					        $this_batch_count++;
						      if (is_numeric($current_user->ID)) {
							      switch ($time){
								      case 'day':
									    case 'week':
								        $sql = "SELECT * FROM ".$wpdb->prefix."cloudminr_stats_hourly_".$current_user->ID."_".$pool_account_id."_".$worker_id." WHERE active='1' AND locked='0' AND pool_account_id='".$pool_account_id."' AND user_id='".$current_user->ID."' AND batch_id='".$batch->batch_id."' AND worker_id='".$worker_id."' ORDER BY updated DESC";
								      break;
								      default:
								        $sql = "SELECT * FROM ".$wpdb->prefix."cloudminr_stats_".$current_user->ID."_".$pool_account_id."_".$worker_id." WHERE active='1' AND locked='0' AND pool_account_id='".$pool_account_id."' AND user_id='".$current_user->ID."' AND batch_id='".$batch->batch_id."' AND worker_id='".$worker_id."' ORDER BY updated DESC";
								      break;
							      }
				          }
						      $batch_data = $wpdb->get_results($sql);
						      $hashrate_total = 0;
							    $loop_count++;
						      foreach ($batch_data as $batch_miner){
						        $batch_created_time = $batch_miner->created_date.' '.$batch_miner->created_time;
						        $hashrate_total = $hashrate_total + $batch_miner->hashrate;
						      }
						      $hashrate_master_total = $hashrate_master_total + $hashrate_total;
						      $hashrate_average = $hashrate_master_total / $this_batch_count;
							    switch ($time){
							      case 'day-to-minute':
										  if ($batch_count >= 289){
								        if ($loop_count >= 289){
						              $file_data .= "['".$batch_created_time."', ".$hashrate_total.", ".$hashrate_average."],";
							          }
											} else {
											  $file_data .= "['".$batch_created_time."', ".$hashrate_total.", ".$hashrate_average."],";
											}
							  	  break;
								    default:
										  if ($batch_count >= 6){
								        if ($loop_count >= 6){
						              $file_data .= "['".$batch_created_time."', ".$hashrate_total.", ".$hashrate_average."],";
							          }
											} else {
											  $file_data .= "['".$batch_created_time."', ".$hashrate_total.", ".$hashrate_average."],";
											}
								    break;
							    }							
				        }
				  	    $file_content .= $file_data;
					      $file_content .= "]);";
					      $file_content .= 'var options = {';
					      switch ($time){
						      case 'day':
						        $file_content .= "title: 'The Last 24 Hours - Total Hashrate for ".$batch_miner->worker_name."'";
						     	break;
						      case 'day-to-minute':
						        $file_content .= "title: 'The Last 24 Hours - Total Hashrate for ".$batch_miner->worker_name."'";
						      break;
							    case 'week':
						        $file_content .= "title: 'The Last Week - Total Hashrate for ".$batch_miner->worker_name."'";
						     	break;
						       default:
						        $file_content .= "title: 'The Last Hour - Total Hashrate for ".$batch_miner->worker_name."'";
						      break;
						    }
                $file_content .= '};';
					      $file_content .= "var chart = new google.visualization.LineChart(document.getElementById('cloudminr_stats_the_last_24hrs'));";
                $file_content .= 'chart.draw(data, options);';
                $file_content .= '}';
					      $file_content .= '</script>';
					    }
				    } else {
						  //BEGIN POOL CHARTS
				      $sql = "SELECT COUNT(DISTINCT(worker_id)) FROM ".$wpdb->prefix."cloudminr_batches WHERE active='1' AND locked='0' AND user_id='".$current_user->ID."' AND pool_account_id='".$pool_account_id."'";
							$count = $wpdb->get_var($sql);
							if ($count >= 1){
							  $sql = "SELECT DISTINCT(worker_id) FROM ".$wpdb->prefix."cloudminr_batches WHERE active='1' AND locked='0' AND user_id='".$current_user->ID."' AND pool_account_id='".$pool_account_id."'";
								$results = $wpdb->get_results($sql);
								$worker_ids = array();
								$batch_ids = array();
								foreach ($results as $result){
								  $worker_id = $result->worker_id;
									if (!in_array($worker_id, $worker_ids)){
									  array_push($worker_ids, $worker_id);
									}
									switch ($time){
									  case 'day':
										  $sql = "SELECT COUNT(DISTINCT(batch_id)) FROM ".$wpdb->prefix."cloudminr_stats_hourly_".$current_user->ID."_".$pool_account_id."_".$result->worker_id." WHERE active='1' AND locked='0' AND user_id='".$current_user->ID."' AND pool_account_id='".$pool_account_id."' ORDER BY updated DESC LIMIT 29";
										break;
									  case 'day-to-minute':
										  $sql = "SELECT COUNT(DISTINCT(batch_id)) FROM ".$wpdb->prefix."cloudminr_stats_".$current_user->ID."_".$pool_account_id."_".$result->worker_id." WHERE active='1' AND locked='0' AND user_id='".$current_user->ID."' AND pool_account_id='".$pool_account_id."' ORDER BY updated DESC LIMIT 1728";
										break;
									  default:
									    $sql = "SELECT COUNT(DISTINCT(batch_id)) FROM ".$wpdb->prefix."cloudminr_stats_".$current_user->ID."_".$pool_account_id."_".$result->worker_id." WHERE active='1' AND locked='0' AND user_id='".$current_user->ID."' AND pool_account_id='".$pool_account_id."' ORDER BY updated DESC LIMIT 65";
									  break;
									}
									$batch_count = $wpdb->get_var($sql);
									$error = 0;
									switch ($time){
									  case 'day':
										  if ($batch_count >= 1){
											  $sql = "SELECT DISTINCT(batch_id) FROM ".$wpdb->prefix."cloudminr_stats_hourly_".$current_user->ID."_".$pool_account_id."_".$result->worker_id." WHERE active='1' AND locked='0' AND user_id='".$current_user->ID."' AND pool_account_id='".$pool_account_id."' ORDER BY updated DESC LIMIT 29";
												$results = $wpdb->get_results($sql);
												foreach ($results as $result){
												  if (!in_array($result->batch_id, $batch_ids)){
													  array_push($batch_ids, $result->batch_id);
													}
												}
											} else {
											  $error = 1;
											}
										break;
									  case 'day-to-minute':
										  if ($batch_count >= 1){
											  $sql = "SELECT DISTINCT(batch_id) FROM ".$wpdb->prefix."cloudminr_stats_".$current_user->ID."_".$pool_account_id."_".$result->worker_id." WHERE active='1' AND locked='0' AND user_id='".$current_user->ID."' AND pool_account_id='".$pool_account_id."' ORDER BY updated DESC LIMIT 1728";
												$results = $wpdb->get_results($sql);
												foreach ($results as $result){
												  if (!in_array($result->batch_id, $batch_ids)){
													  array_push($batch_ids, $result->batch_id);
													}
												}
											} else {
											  $error = 1;
											}
										break;
										case 'week':
										  if ($batch_count >= 1){
											  $sql = "SELECT DISTINCT(batch_id) FROM ".$wpdb->prefix."cloudminr_stats_hourly_".$current_user->ID."_".$pool_account_id."_".$result->worker_id." WHERE active='1' AND locked='0' AND user_id='".$current_user->ID."' AND pool_account_id='".$pool_account_id."' ORDER BY updated DESC LIMIT 203";
												$results = $wpdb->get_results($sql);
												foreach ($results as $result){
												  if (!in_array($result->batch_id, $batch_ids)){
													  array_push($batch_ids, $result->batch_id);
													}
												}
											} else {
											  $error = 1;
											}
										break;
									  default:
										  if ($batch_count >= 1){
											  $sql = "SELECT DISTINCT(batch_id) FROM ".$wpdb->prefix."cloudminr_stats_".$current_user->ID."_".$pool_account_id."_".$result->worker_id." WHERE active='1' AND locked='0' AND user_id='".$current_user->ID."' AND pool_account_id='".$pool_account_id."' ORDER BY updated DESC LIMIT 65";
												$results = $wpdb->get_results($sql);
												foreach ($results as $result){
												  if (!in_array($result->batch_id, $batch_ids)){
													  array_push($batch_ids, $result->batch_id);
													}
												}
											} else {
											  $error = 1;
											}
										break;
									}	
								}
								if ($error == 0){
								  $file_data = "['Time', 'Hashrate', 'Average'],";
					        $this_batch_count = 0;
									switch ($time){
									  case 'day-to-minute':
										case 'hour':
										  $batches = array_reverse($batch_ids);
										break;
										default:
										  $batches = $batch_ids;
											asort($batches);
										break;
									}
					        $hashrate_master_total = 0;
						      $loop_count = 0;
				          foreach ($batches as $batch){
					          $this_batch_count++;
										$hashrate_total = 0;
										$loop_count++;
										foreach ($worker_ids as $worker_id){
										  switch ($time){
								        case 'day':
									      case 'week':
								          $sql = "SELECT * FROM ".$wpdb->prefix."cloudminr_stats_hourly_".$current_user->ID."_".$pool_account_id."_".$worker_id." WHERE active='1' AND locked='0' AND pool_account_id='".$pool_account_id."' AND user_id='".$current_user->ID."' AND batch_id='".$batch."' AND worker_id='".$worker_id."' ORDER BY updated DESC";
								        break;
								        default:
								          $sql = "SELECT * FROM ".$wpdb->prefix."cloudminr_stats_".$current_user->ID."_".$pool_account_id."_".$worker_id." WHERE active='1' AND locked='0' AND pool_account_id='".$pool_account_id."' AND user_id='".$current_user->ID."' AND batch_id='".$batch."' AND worker_id='".$worker_id."' ORDER BY updated DESC";
								        break;
							        }
											$batch_data = $wpdb->get_results($sql);
											foreach ($batch_data as $batch_miner){
						            $batch_created_time = $batch_miner->created_date.' '.$batch_miner->created_time;
						            $hashrate_total = $hashrate_total + $batch_miner->hashrate;
						          }
										}
										$hashrate_master_total = $hashrate_master_total + $hashrate_total;
						        $hashrate_average = $hashrate_master_total / $this_batch_count;
										switch ($time){
										  case 'day':
										    if ($batch_count >= 29){
								          if ($loop_count >= 29){
						                $file_data .= "['".$batch_created_time."', ".$hashrate_total.", ".$hashrate_average."],";
							            }
										   	} else {
											    $file_data .= "['".$batch_created_time."', ".$hashrate_total.", ".$hashrate_average."],";
											  }
							  	    break;
							        case 'day-to-minute':
										    if ($batch_count >= 289){
								          if ($loop_count >= 289){
						                $file_data .= "['".$batch_created_time."', ".$hashrate_total.", ".$hashrate_average."],";
							            }
										   	} else {
											    $file_data .= "['".$batch_created_time."', ".$hashrate_total.", ".$hashrate_average."],";
											  }
							  	    break;
											case 'week':
										    if ($batch_count >= 203){
								          if ($loop_count >= 203){
						                $file_data .= "['".$batch_created_time."', ".$hashrate_total.", ".$hashrate_average."],";
							            }
										   	} else {
											    $file_data .= "['".$batch_created_time."', ".$hashrate_total.", ".$hashrate_average."],";
											  }
							  	    break;
								      default:
										    if ($batch_count >= 6){
								          if ($loop_count >= 6){
						                $file_data .= "['".$batch_created_time."', ".$hashrate_total.", ".$hashrate_average."],";
							            }
											  } else {
										      $file_data .= "['".$batch_created_time."', ".$hashrate_total.", ".$hashrate_average."],";
											  }
								      break;
							      }
									}
									$file_content .= $file_data;
					        $file_content .= "]);";
					        $file_content .= 'var options = {';
					        switch ($time){
						        case 'day':
						          $file_content .= "title: 'The Last 24 Hours - Total Hashrate'";
						       	break;
						        case 'day-to-minute':
						          $file_content .= "title: 'The Last 24 Hours - Total Hashrate'";
						        break;
							      case 'week':
						          $file_content .= "title: 'The Last Week - Total Hashrate'";
						     	  break;
						        default:
						          $file_content .= "title: 'The Last Hour - Total Hashrate'";
						        break;
						      }
                  $file_content .= '};';
					        $file_content .= "var chart = new google.visualization.LineChart(document.getElementById('cloudminr_stats_the_last_24hrs'));";
                  $file_content .= 'chart.draw(data, options);';
                  $file_content .= '}';
					        $file_content .= '</script>';
							  } else {
								  $file_content = '<script type="text/javascript">';
		              $file_content .= 'google.load("visualization", "1", {packages:["corechart"]});';
                  $file_content .= 'google.setOnLoadCallback(drawChart);';
                  $file_content .= 'function drawChart() {';
		              $file_content .= 'var data = google.visualization.arrayToDataTable([';
	                $file_data = "['Time', 'Hashrate', 'Average'],";
		              $file_data .= "['', 0, 0],"; 
		              $file_content .= $file_data;
		              $file_content .= "]);";
		              $file_content .= 'var options = {';
		              if (isset($_GET['worker'])){
		                switch ($time){
			                case 'day':
				              case 'day-to-minute':
				                $file_content .= "title: 'The Last 24 Hours - No Data for ".$batch_miner->worker_name."',";
				              break;
							        case 'week':
				                $file_content .= "title: 'The Last Week - No Data for ".$batch_miner->worker_name."',";
				              break;
			                default:
			                  $file_content .= "title: 'The Last Hour - No Data for ".$batch_miner->worker_name."',";
				              break;
			              }
		              } else {
		                switch ($time){
			                case 'day':
				              case 'day-to-minute':
				                $file_content .= "title: 'The Last 24 Hours - No Data',";
				              break;
							        case 'week':
				                $file_content .= "title: 'The Last Week - No Data',";
				              break;
			                default:
				                $file_content .= "title: 'The Last Hour - No Data',";
				              break;
			              }
		              }
		              $file_content .= "legend: {position: 'none'}";
                  $file_content .= '};';
		              $file_content .= "var chart = new google.visualization.LineChart(document.getElementById('cloudminr_stats_the_last_24hrs'));";
                  $file_content .= 'chart.draw(data, options);';
                  $file_content .= '}';
			  	        $file_content .= '</script>';
								}
							} else {
							  $file_content = '<script type="text/javascript">';
		            $file_content .= 'google.load("visualization", "1", {packages:["corechart"]});';
                $file_content .= 'google.setOnLoadCallback(drawChart);';
                $file_content .= 'function drawChart() {';
		            $file_content .= 'var data = google.visualization.arrayToDataTable([';
	              $file_data = "['Time', 'Hashrate', 'Average'],";
		            $file_data .= "['', 0, 0],"; 
		            $file_content .= $file_data;
		            $file_content .= "]);";
		            $file_content .= 'var options = {';
		            if (isset($_GET['worker'])){
		              switch ($time){
			              case 'day':
				            case 'day-to-minute':
				              $file_content .= "title: 'The Last 24 Hours - No Data for ".$batch_miner->worker_name."',";
				            break;
							      case 'week':
				              $file_content .= "title: 'The Last Week - No Data for ".$batch_miner->worker_name."',";
				            break;
			              default:
			                $file_content .= "title: 'The Last Hour - No Data for ".$batch_miner->worker_name."',";
				            break;
			            }
		            } else {
		              switch ($time){
			              case 'day':
				            case 'day-to-minute':
				              $file_content .= "title: 'The Last 24 Hours - No Data',";
				            break;
							      case 'week':
				              $file_content .= "title: 'The Last Week - No Data',";
				            break;
			              default:
				              $file_content .= "title: 'The Last Hour - No Data',";
				            break;
			            }
		            }
		            $file_content .= "legend: {position: 'none'}";
                $file_content .= '};';
		            $file_content .= "var chart = new google.visualization.LineChart(document.getElementById('cloudminr_stats_the_last_24hrs'));";
                $file_content .= 'chart.draw(data, options);';
                $file_content .= '}';
			  	      $file_content .= '</script>';
							}
				      $batches = $wpdb->get_results($sql);					
				      $batch_count = count($batches);
				      $error = 0;
						  //END POOL CHARTS
				    }
					} 
					//END BUILD CHARTS
			  } else {
			    $file_content = '<script type="text/javascript">';
		      $file_content .= 'google.load("visualization", "1", {packages:["corechart"]});';
          $file_content .= 'google.setOnLoadCallback(drawChart);';
          $file_content .= 'function drawChart() {';
		      $file_content .= 'var data = google.visualization.arrayToDataTable([';
	        $file_data = "['Time', 'Hashrate', 'Average'],";
		      $file_data .= "['', 0, 0],"; 
		      $file_content .= $file_data;
		      $file_content .= "]);";
		      $file_content .= 'var options = {';
		      if (isset($_GET['worker'])){
		        switch ($time){
			        case 'day':
				      case 'day-to-minute':
				        $file_content .= "title: 'The Last 24 Hours - No Data for ".$batch_miner->worker_name."',";
				      break;
							case 'week':
				        $file_content .= "title: 'The Last Week - No Data for ".$batch_miner->worker_name."',";
				      break;
			        default:
			          $file_content .= "title: 'The Last Hour - No Data for ".$batch_miner->worker_name."',";
				      break;
			      }
		      } else {
		        switch ($time){
			        case 'day':
				      case 'day-to-minute':
				        $file_content .= "title: 'The Last 24 Hours - No Data',";
				      break;
							case 'week':
				        $file_content .= "title: 'The Last Week - No Data',";
				      break;
			        default:
				        $file_content .= "title: 'The Last Hour - No Data',";
				      break;
			      }
		      }
		      $file_content .= "legend: {position: 'none'}";
          $file_content .= '};';
		      $file_content .= "var chart = new google.visualization.LineChart(document.getElementById('cloudminr_stats_the_last_24hrs'));";
          $file_content .= 'chart.draw(data, options);';
          $file_content .= '}';
			  	$file_content .= '</script>';
			  }
		  } else {
		    $file_content = '<script type="text/javascript">';
		    $file_content .= 'google.load("visualization", "1", {packages:["corechart"]});';
        $file_content .= 'google.setOnLoadCallback(drawChart);';
        $file_content .= 'function drawChart() {';
		    $file_content .= 'var data = google.visualization.arrayToDataTable([';
	      $file_data = "['Time', 'Hashrate', 'Average'],";
		    $file_data .= "['', 0, 0],"; 
		    $file_content .= $file_data;
		    $file_content .= "]);";
		    $file_content .= 'var options = {';
		    if (isset($_GET['worker'])){
		      switch ($time){
			      case 'day':
				    case 'day-to-minute':
				      $file_content .= "title: 'The Last 24 Hours - No Data for ".$batch_miner->worker_name."',";
				    break;
						case 'week':
				      $file_content .= "title: 'The Last Week - No Data for ".$batch_miner->worker_name."',";
				    break;
			      default:
			        $file_content .= "title: 'The Last Hour - No Data for ".$batch_miner->worker_name."',";
				    break;
			    }
		    } else {
		      switch ($time){
			      case 'day':
				    case 'day-to-minute':
				      $file_content .= "title: 'The Last 24 Hours - No Data',";
				    break;
						case 'week':
				      $file_content .= "title: 'The Last Week - No Data',";
				    break;
			      default:
				      $file_content .= "title: 'The Last Hour - No Data',";
				    break;
			    }
		    }
		    $file_content .= "legend: {position: 'none'}";
        $file_content .= '};';
		    $file_content .= "var chart = new google.visualization.LineChart(document.getElementById('cloudminr_stats_the_last_24hrs'));";
        $file_content .= 'chart.draw(data, options);';
        $file_content .= '}';
			  $file_content .= '</script>';
		  }
  	} else {
	    $file_content = '<script type="text/javascript">';
		  $file_content .= 'google.load("visualization", "1", {packages:["corechart"]});';
      $file_content .= 'google.setOnLoadCallback(drawChart);';
      $file_content .= 'function drawChart() {';
		  $file_content .= 'var data = google.visualization.arrayToDataTable([';
	    $file_data = "['Time', 'Hashrate', 'Average'],";
		  $file_data .= "['', 0, 0],"; 
		  $file_content .= $file_data;
		  $file_content .= "]);";
		  $file_content .= 'var options = {';
		  if (isset($_GET['worker'])){
		    switch ($time){
			    case 'day':
				  case 'day-to-minute':
				    $file_content .= "title: 'The Last 24 Hours - No Data for ".$batch_miner->worker_name."',";
				  break;
					case 'week':
				    $file_content .= "title: 'The Last Week - No Data for ".$batch_miner->worker_name."',";
				  break;
			    default:
			      $file_content .= "title: 'The Last Hour - No Data for ".$batch_miner->worker_name."',";
				  break;
			  }
		  } else {
		    switch ($time){
			    case 'day':
				  case 'day-to-minute':
				    $file_content .= "title: 'The Last 24 Hours - No Data',";
				  break;
					case 'week':
				    $file_content .= "title: 'The Last Week - No Data',";
				  break;
			    default:
				    $file_content .= "title: 'The Last Hour - No Data',";
				  break;
			  }
		  }
		  $file_content .= "legend: {position: 'none'}";
      $file_content .= '};';
		  $file_content .= "var chart = new google.visualization.LineChart(document.getElementById('cloudminr_stats_the_last_24hrs'));";
      $file_content .= 'chart.draw(data, options);';
      $file_content .= '}';
	  	$file_content .= '</script>';
	  }	
	  $html .= $file_content;
	  $html .= $dropdown_html;
	} else {
	  print redirect_to('./?section=logout');
	}
?>
