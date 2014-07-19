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
  global $wpdb, $current_user;
	get_currentuserinfo();
	if (is_numeric($current_user->ID)) {
    if ( ('POST' == $_SERVER['REQUEST_METHOD']) && (isset($_POST['action'])) && ($_POST['action'] == 'add-worker') ){
	    $html .= '<h2>Going for processing...</h2>'.PHP_EOL;
		  if (isset($_POST['user_id'])){
		    $user_id = $_POST['user_id'];
		  } else {
		    if (is_numeric($current_user->ID)){
			    $user_id = $current_user->ID;
			  } else {
		      $user_id = '0';
			  }
		  }
	  	if (isset($_POST['pool_account_id'])){
		    $pool_account_id = $_POST['pool_account_id'];
		  } else {
		    $error = 1;
		  }
		  if (isset($_POST['worker_id'])){
		    $worker_id = $_POST['worker_id'];
		  } else {
			  $error = 1;
		  }
		  if (isset($_POST['worker_name'])){
		    $worker_name = $_POST['worker_name'];
		  } else {
		    $worker_name = 'not_set';
		  }
		  if (isset($_POST['description'])){
		    $sql .= ", description='".$_POST['description']."'";
		  }
		  if ($error <= 0){
		    $insert = $wpdb->insert( $wpdb->prefix."cloudminr_workers",
				      array(
					      'active' => '1',
						    'locked' => '0',
						    'created_date' => $created_date,
						    'created_time' => $created_time,
						    'created_by_id' => $current_user->ID,
							  'user_id' => $user_id,
							  'pool_account_id' => $pool_account_id,
							  'worker_id' => $worker_id,
							  'name' => $worker_name,
						    'description' => $_POST['description']
					    )
				    );
		    if ($insert){
				  print redirect_to('./?section=admin&added=y');
		    } else {
		      $error = '<h1 style="color: red;">Danger! NO INSERT!</h1>'.PHP_EOL;
		    }
		  } else {
		    $error = '<h1 style="color: red;">Danger!</h1>'.PHP_EOL;
		  }
	  }
	  if ($the_plugin_header != 'internal'){
	    $html .= '<section class="emerald" style="padding: 10px; cursor: pointer;" onClick="location.href=';
	    $html .= "'./?section=admin'";
	    $html .= '">';
        $html .= '<div class="container">';
          $html .= '<div class="row-fluid">';
            $html .= '<div class="col-xs-12"><h2>Back</h2></div>';
          $html .= '</div>';
        $html .= '</div>';
      $html .= '</section>';
    }
	  if (isset($error)){
	    $html .= $error;
  	}
	  $html .= '<br />'.PHP_EOL;
	  $sql = "SELECT COUNT(*) FROM ".$wpdb->prefix."cloudminr_pools WHERE active='1' AND user_id='".$current_user->ID."'";
		$count = $wpdb->get_var($sql);
		if ($count >= 1){
		  $html .= '<form name="cloudminr_add_worker" method="post" action=".">'.PHP_EOL;
        $html .= '<div class="row">'.PHP_EOL;  
	        $html .= '<div class="col-sm-2">Pool</div>'.PHP_EOL;
		      $html .= '<div class="col-sm-4">'.PHP_EOL;
						$html .= '<select required="required" class="form-control" style="width: 100%;" name="pool_account_id">'.PHP_EOL;
			        $html .= '<option value="">Select</option>'.PHP_EOL;
						  $sql = "SELECT * FROM ".$wpdb->prefix."cloudminr_pools WHERE active='1' AND user_id='".$current_user->ID."'";
							$results = $wpdb->get_results($sql);
							foreach ($results as $pools){
							  $html .= '<option value="'.$pools->id.'">'.$pools->pool_name.'</option>'.PHP_EOL;
							}
			      $html .= '</select>'.PHP_EOL;
		      $html .= '</div>'.PHP_EOL;
	      $html .= '</div>'.PHP_EOL;
	      $html .= '<br />'.PHP_EOL;
	      $html .= '<div class="row">'.PHP_EOL;
	        $html .= '<div class="col-sm-2">ID*</div>'.PHP_EOL;
		      $html .= '<div class="col-sm-4">';
			      if (isset($_POST['worker_id'])){
				      $html .= '<input class="form-control" required="required" type="text" name="worker_id" id="worker_id" value="'.$_POST['worker_id'].'">';
				    } else {
			        $html .= '<input class="form-control" required="required" type="text" name="worker_id" id="worker_id">';
				    }
			    $html .= '</div>'.PHP_EOL;
		    $html .= '</div>'.PHP_EOL;
	      $html .= '<br />'.PHP_EOL;
	      $html .= '<div class="row">'.PHP_EOL;
	        $html .= '<div class="col-sm-2">Name</div>'.PHP_EOL;
			    $html .= '<div class="col-sm-4">';
			      if (isset($_POST['worker_name'])){
				      $html .= '<input class="form-control" type="text" name="worker_name" id="worker_name" value="'.$_POST['worker_name'].'">';
				    } else {
			        $html .= '<input class="form-control" type="text" name="worker_name" id="worker_name">';
				    }
			    $html .= '</div>'.PHP_EOL;
	      $html .= '</div>'.PHP_EOL;
	      $html .= '<br />'.PHP_EOL;
	      $html .= '<div class="row">'.PHP_EOL;
	        $html .= '<div class="col-sm-2">Notes</div>'.PHP_EOL;
			    $html .= '<div class="col-sm-4">';
			      if (isset($_POST['description'])){
				      $html .= '<textarea class="form-control" name="description" id="description">'.stripslashes_deep($_POST['description']).'</textarea>';
				    } else {
			        $html .= '<textarea class="form-control" name="description" id="description"></textarea>';
			      }
			    $html .= '</div>'.PHP_EOL;
	      $html .= '</div>'.PHP_EOL;
	      $html .= '<br />'.PHP_EOL;
	      $html .= '<div class="row">'.PHP_EOL;
	        $html .= '<div class="col-sm-6">'.PHP_EOL;
		        $html .= '<input type="hidden" name="section" value="add-worker">'.PHP_EOL;
			      $html .= '<input type="hidden" name="action" value="add-worker">'.PHP_EOL;
				    $html .= '<input type="hidden" name="user_id" value="'.$current_user->ID.'">'.PHP_EOL;
		        $html .= '<button class="btn btn-lg btn-primary btn-block" type="submit">Save</button'.PHP_EOL;
		      $html .= '</div>'.PHP_EOL;
	      $html .= '</div>'.PHP_EOL;
			$html .= '</form>'.PHP_EOL;
		} else {
		  $html .= '<div class="row">'.PHP_EOL;
			  $html .= '<div class="col-sm-2">Pools</div>'.PHP_EOL;
				$html .= '<div class="col-sm-4">'.PHP_EOL;
				  $html .= '<select disabled="disabled" required="required" class="form-control" style="width: 100%;" name="pool_account_id">'.PHP_EOL;
					  $html .= '<option selected="selected" value="">No Pools to display</option>'.PHP_EOL;
					$html .= '</select>'.PHP_EOL;
				$html .= '</div>'.PHP_EOL;
			$html .= '</div>'.PHP_EOL;
			$html .= '<br />'.PHP_EOL;
		  $html .= '<div class="row">'.PHP_EOL;
	      $html .= '<div class="col-sm-6">'.PHP_EOL;
				  $html .= '<input type="hidden" name="section" value="add-pool">'.PHP_EOL;
				  $html .= '<button class="btn btn-lg btn-primary btn-block" onClick="location.href=';
	        $html .= "'./?section=add-pool'";
	        $html .= '">Add Pool</button>';
				$html .= '</div>'.PHP_EOL;
			$html .= '</div>'.PHP_EOL;
		}
	} else {
	  print redirect_to('./?section=logout');
	}
?>
