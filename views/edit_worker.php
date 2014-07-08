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
  $debugMode = 0;
  global $wpdb, $current_user;
	get_currentuserinfo();
	if (is_numeric($current_user->ID)) {
    if ( ('POST' == $_SERVER['REQUEST_METHOD']) && (isset($_POST['action'])) && ($_POST['action'] == 'update-worker') ){
	    $html .= '<h2>Going for processing...</h2>'.PHP_EOL;
		  if (isset($_POST['db_worker_id'])){
		    $db_worker_id = $_POST['db_worker_id'];
		  } else {
		    $err = 1;
		  }
		  if (isset($_POST['user_id'])){
		    $user_id = $_POST['user_id'];
		  } else {
		    $user_id = '0';
		  }
		  if (isset($_POST['pool_account_id'])){
		    $pool_account_id = $_POST['pool_account_id'];
		  } else {
		    $pool_account_id = '0';
		  }
		  if (isset($_POST['worker_id'])){
		    $worker_id = $_POST['worker_id'];
		  } else {
			  $err = 1;
		  }
		  if (isset($_POST['worker_name'])){
		    $worker_name = $_POST['worker_name'];
		  } else {
		    $worker_name = 'not_set';
		  }
		  if (isset($_POST['description'])){
		    $description = $_POST['description'];
		  }
		  $sql = "UPDATE ".$wpdb->prefix."cloudminr_workers SET user_id='".$user_id."', pool_account_id='".$pool_account_id."', worker_id='".$worker_id."', name='".$worker_name."', description='".$description."' WHERE id='".$db_worker_id."'";
		  if ($error <= 0){
		    $update = $wpdb->query($sql);
			  if ($update){
				  print redirect_to('./?section=admin&pool='.$pool_account_id.'&updated=y');
			  } else {
		      print '<h1 style="color: red;">Danger! NO UPDATE!</h1>'.PHP_EOL;
		    }
		  } else {
		    print '<h1 style="color: red;">Danger!</h1>'.PHP_EOL;
		  }
	  }
	  if (isset($_GET['worker'])){
	    $sql = "SELECT * FROM ".$wpdb->prefix."cloudminr_workers WHERE id='".$_GET['worker']."'";
		  $cloudminr_worker = $wpdb->get_row($sql, ARRAY_A);
		  if ( (isset($debugMode)) && ($debugMode >= 1) ){
		    print_r($cloudminr_worker);
		  }
	  }
	  if ($the_plugin_header != 'internal'){
	    $html .= '<section class="emerald" style="padding: 10px; cursor: pointer;" onClick="location.href=';
	    $html .= "'./?section=admin&pool=".$cloudminr_worker['pool_account_id']."'";
	    $html .= '">';
        $html .= '<div class="container">';
          $html .= '<div class="row-fluid">';
            $html .= '<div class="col-xs-12"><h2>Back</h2></div>';
          $html .= '</div>';
        $html .= '</div>';
      $html .= '</section>';
    }
		$html .= '<br />'.PHP_EOL;
    $html .= '<form name="cloudminr_edit_worker" method="post" action=".">'.PHP_EOL;
		  $html .= '<div class="row">'.PHP_EOL;  
	      $html .= '<div class="col-sm-2">Pool</div>'.PHP_EOL;
		    $html .= '<div class="col-sm-4">'.PHP_EOL;
		      $html .= '<select class="form-control" name="pool_account_id">'.PHP_EOL;
			      $html .= '<option value="0">Select</option>'.PHP_EOL;
						$sql = "SELECT COUNT(*) FROM ".$wpdb->prefix."cloudminr_pools WHERE active='1' AND user_id='".$current_user->ID."'";
						$count = $wpdb->get_var($sql);
						if ($count >= 1){
						  $sql = "SELECT * FROM ".$wpdb->prefix."cloudminr_pools WHERE active='1' AND user_id='".$current_user->ID."'";
							$results = $wpdb->get_results($sql);
							foreach ($results as $pools){
							  if ($pools->id == $cloudminr_worker['pool_account_id']){
							    $html .= '<option selected="selected" value="'.$pools->id.'">'.$pools->pool_name.'</option>'.PHP_EOL;
								} else {
								  $html .= '<option value="'.$pools->id.'">'.$pools->pool_name.'</option>'.PHP_EOL;
								}
							}
						} else {
						  $html .= '<option selected="selected" value="0">Default</option>'.PHP_EOL;
						}
			    $html .= '</select>'.PHP_EOL;
		    $html .= '</div>'.PHP_EOL;
	    $html .= '</div>'.PHP_EOL;
	    $html .= '<br />'.PHP_EOL;
	    $html .= '<div class="row">'.PHP_EOL;
	      $html .= '<div class="col-sm-2">ID*</div>'.PHP_EOL;
		    $html .= '<div class="col-sm-4"><input class="form-control" required="required" type="text" name="worker_id" id="worker_id" value="'.$cloudminr_worker['worker_id'].'"></div>'.PHP_EOL;
		    $html .= '</div>'.PHP_EOL;
	    $html .= '</div>'.PHP_EOL;
	    $html .= '<br />'.PHP_EOL;
	    $html .= '<div class="row">'.PHP_EOL;
	      $html .= '<div class="col-sm-2">Name</div>'.PHP_EOL;
		    $html .= '<div class="col-sm-4"><input class="form-control" type="text" name="worker_name" id="worker_name" value="'.$cloudminr_worker['name'].'"></div>'.PHP_EOL;
	    $html .= '</div>'.PHP_EOL;
	    $html .= '<br />'.PHP_EOL;
	    $html .= '<div class="row">'.PHP_EOL;
	      $html .= '<div class="col-sm-2">Notes</div>'.PHP_EOL;
		    $html .= '<div class="col-sm-4"><textarea class="form-control" name="description" id="description">'.$cloudminr_worker['description'].'</textarea></div>'.PHP_EOL;
	    $html .= '</div>'.PHP_EOL;
	    $html .= '<br />'.PHP_EOL;
	    $html .= '<div class="row">'.PHP_EOL;
	      $html .= '<div class="col-sm-6">'.PHP_EOL;
			    $html .= '<input type="hidden" name="db_worker_id" value="'.$_GET['worker'].'">'.PHP_EOL;
		      $html .= '<input type="hidden" name="section" value="edit-worker">'.PHP_EOL;
			  	$html .= '<input type="hidden" name="user_id" value="'.$current_user->ID.'">'.PHP_EOL;
			    $html .= '<input type="hidden" name="action" value="update-worker">'.PHP_EOL;
		      $html .= '<button class="btn btn-lg btn-primary btn-block" type="submit">Save</button>'.PHP_EOL;
				  if ($cloudminr_worker['locked'] == 0){
				    $html .= '<div class="btn btn-lg btn-primary btn-block" style="background-color: #FF9900;" ><a style="color: #fff; text-decoration: none;" href="./?section=disable-worker&worker='.$_GET['worker'].'" onclick="';
				    $html .= "return confirm('Are you sure you wish to disable the Minr ".$cloudminr_worker['name']."?')";
				    $html .= '">Disable '.$cloudminr_worker['name'].'</a></div>'.PHP_EOL;
			  	} elseif ($cloudminr_worker['locked'] == 1){
				    $html .= '<div class="btn btn-lg btn-primary btn-block" style="background-color: #006633;" ><a style="color: #fff; text-decoration: none;" href="./?section=enable-worker&worker='.$_GET['worker'].'" onclick="';
				    $html .= "return confirm('Are you sure you wish to enable the Minr ".$cloudminr_worker['name']."?')";
				    $html .= '">Enable '.$cloudminr_worker['name'].'</a></div>'.PHP_EOL;
				  }
				  $html .= '<div class="btn btn-lg btn-primary btn-block" style="background-color: #A00000;" ><a style="color: #fff; text-decoration: none;" href="./?section=delete-worker&worker='.$_GET['worker'].'" onclick="';
			  	$html .= "return confirm('Are you sure you wish to delete the Minr ".$cloudminr_worker['name']."?')";
			    $html .= '">Delete '.$cloudminr_worker['name'].'</a></div>'.PHP_EOL;
		    $html .= '</div>'.PHP_EOL;
	    $html .= '</div>'.PHP_EOL;
	  $html .= '</form>'.PHP_EOL;
	} else {
	  print redirect_to('./?section=logout');
	}
?>
