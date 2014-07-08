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
  global $wpdb, $current_user;
	get_currentuserinfo();
	if (is_numeric($current_user->ID)) {
    if ( ('POST' == $_SERVER['REQUEST_METHOD']) && (isset($_POST['action'])) && ($_POST['action'] == 'update-pool') ){
	    $html .= '<h2>Going for processing...</h2>'.PHP_EOL;
		  if (isset($_POST['db_pool_id'])){
		    $db_pool_id = $_POST['db_pool_id'];
		  } else {
		    $err = 1;
		  }
		  if (isset($_POST['user_id'])){
		    $user_id = $_POST['user_id'];
		  } else {
		    $user_id = '0';
		  }
		  if (isset($_POST['pool_name'])){
		    $pool_name = $_POST['pool_name'];
		  } else {
		    $pool_name = 'not_set';
		  }
		  if (isset($_POST['pool_api_id'])){
		    $pool_api_id = $_POST['pool_api_id'];
		  } else {
		    $err = 1;
		  }
		  if (isset($_POST['pool_api_url'])){
		    $pool_api_url = $_POST['pool_api_url'];
		  } else {
			  $err = 1;
		  }
		  if (isset($_POST['pool_api_key'])){
		    $pool_api_key = $_POST['pool_api_key'];
		  } else {
			  $err = 1;
		  }
		  if (isset($_POST['pool_currency'])){
		    $pool_currency = $_POST['pool_currency'];
		  } else {
		    $pool_currency = '';
		  }		
		  if ($err <= 0){
		    $update = $wpdb->update( $wpdb->prefix."cloudminr_pools",
			      array(
					    'user_id' => $user_id,
						  'pool_name' => $pool_name,
						  'pool_api_id' => $pool_api_id,
						  'pool_api_url' => $pool_api_url,
						  'pool_api_key' => $pool_api_key,
						  'pool_currency' => $pool_currency
					  ),
					  array(
					    'id' => $db_pool_id
					  )
			  );
			  if ($update){
				  print redirect_to('./?section=pools&updated=y');
			  } else {
		      print '<h1 style="color: red;">Danger! NO UPDATE!</h1>'.PHP_EOL;
		    }
		  } else {
		    print '<h1 style="color: red;">Danger!</h1>'.PHP_EOL;
		  }
	  }
	  if (isset($_GET['pool'])){
	    $sql = "SELECT * FROM ".$wpdb->prefix."cloudminr_pools WHERE id='".$_GET['pool']."' AND user_id='".$current_user->ID."'";
		  $cloudminr_pool = $wpdb->get_row($sql, ARRAY_A);
		  if ( (isset($debugMode)) && ($debugMode >= 1) ){
		    print_r($cloudminr_pool);
		  }
	  }
	  if ($the_plugin_header != 'internal'){
	    $html .= '<section class="emerald" style="padding: 10px; cursor: pointer;" onClick="location.href=';
	    $html .= "'./?section=pools'";
	    $html .= '">';
        $html .= '<div class="container">';
          $html .= '<div class="row-fluid">';
            $html .= '<div class="col-xs-12"><h2>Back</h2></div>';
          $html .= '</div>';
        $html .= '</div>';
      $html .= '</section>';
    }
	  $html .= '<br />'.PHP_EOL;
    $html .= '<form name="cloudminr_edit_pool" method="post" action=".">'.PHP_EOL;
	    $html .= '<div class="row">'.PHP_EOL;
	      $html .= '<div class="col-xs-2">Name</div>'.PHP_EOL;
		    $html .= '<div class="col-xs-4"><input class="form-control" type="text" name="pool_name" id="pool_name" value="'.$cloudminr_pool['pool_name'].'"></div>'.PHP_EOL;
		  $html .= '</div>'.PHP_EOL;
	    $html .= '<br />'.PHP_EOL;
		  $html .= '<div class="row">'.PHP_EOL;
	      $html .= '<div class="col-xs-2">API ID*</div>'.PHP_EOL;
		    $html .= '<div class="col-xs-4"><input class="form-control" required="required" type="text" name="pool_api_id" id="pool_api_id" value="'.$cloudminr_pool['pool_api_id'].'"></div>'.PHP_EOL;
	    $html .= '</div>'.PHP_EOL;
	    $html .= '<br />'.PHP_EOL;
		  $html .= '<div class="row">'.PHP_EOL;
	      $html .= '<div class="col-xs-2">API URL*</div>'.PHP_EOL;
		    $html .= '<div class="col-xs-8"><input class="form-control" required="required" type="text" name="pool_api_url" id="pool_api_url" value="'.$cloudminr_pool['pool_api_url'].'"></div>'.PHP_EOL;
	    $html .= '</div>'.PHP_EOL;
	    $html .= '<br />'.PHP_EOL;
		  $html .= '<div class="row">'.PHP_EOL;
	      $html .= '<div class="col-xs-2">API Key*</div>'.PHP_EOL;
		    $html .= '<div class="col-xs-8"><input class="form-control" required="required" type="text" name="pool_api_key" id="pool_api_key" value="'.$cloudminr_pool['pool_api_key'].'"></div>'.PHP_EOL;
	    $html .= '</div>'.PHP_EOL;
	    $html .= '<br />'.PHP_EOL;
		  $html .= '<div class="row">'.PHP_EOL;
	      $html .= '<div class="col-xs-2">Currency</div>'.PHP_EOL;
		    $html .= '<div class="col-xs-4"><input class="form-control" type="text" name="pool_currency" id="pool_currency" maxlength="4" value="'.$cloudminr_pool['pool_currency'].'"></div>'.PHP_EOL;
	    $html .= '</div>'.PHP_EOL;
	    $html .= '<br />'.PHP_EOL;
	    $html .= '<div class="row">'.PHP_EOL;
	      $html .= '<div class="col-xs-10">'.PHP_EOL;
			    $html .= '<input type="hidden" name="db_pool_id" value="'.$_GET['pool'].'">'.PHP_EOL;
		      $html .= '<input type="hidden" name="section" value="edit-pool">'.PHP_EOL;
			    $html .= '<input type="hidden" name="action" value="update-pool">'.PHP_EOL;
				  $html .= '<input type="hidden" name="user_id" value="'.$current_user->ID.'">'.PHP_EOL;
		      $html .= '<button class="btn btn-lg btn-primary btn-block" type="submit">Save</button>'.PHP_EOL;
				  $html .= '<div class="btn btn-lg btn-primary btn-block" style="background-color: #A00000;" ><a style="color: #fff; text-decoration: none;" href="./?section=delete-pool&pool='.$_GET['pool'].'" onclick="';
				  $html .= "return confirm('Are you sure you wish to delete the Pool ".$cloudminr_pool['pool_name']."?')";
				  $html .= '">Delete '.$cloudminr_pool['pool_name'].'</a></div>'.PHP_EOL;
		    $html .= '</div>'.PHP_EOL;
	    $html .= '</div>'.PHP_EOL;
	  $html .= '</form>'.PHP_EOL;
	} else {
	  print redirect_to('./?section=logout');
	}	
?>
