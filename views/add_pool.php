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
  if (is_numeric($current_user->ID)){
	  if ( ('POST' == $_SERVER['REQUEST_METHOD']) && (isset($_POST['action'])) && ($_POST['action'] == 'add-pool') ){
	    $debug_output = '';
	    if ( (isset($debugMode)) && ($debugMode >= 1) ){
	      $debug_output .= '<h2>Going for processing...</h2>'.PHP_EOL;
		  }
		  global $wpdb, $current_user;
		  get_currentuserinfo();
		  if (isset($_POST['user_id'])){
		    $user_id = $_POST['user_id'];
		  } else {
		    if (is_numeric($current_user->ID)){
			    $user_id = $current_user->ID;
			  } else {
		      $user_id = '0';
			  }
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
		  if (isset($_POST['pool_name'])){
		    $pool_name = $_POST['pool_name'];
		  } else {
		    $pool_name = 'not_set';
		  }
		  if (isset($_POST['pool_currency'])){
		    $pool_currency = $_POST['pool_currency'];
	  	} else {
		    $pool_currency = 'not_set';
		  }
		  if ($err <= 0){
		    $insert = $wpdb->insert( $wpdb->prefix."cloudminr_pools",
				      array(
					      'active' => '1',
						    'locked' => '0',
						    'created_date' => date('Y-m-d'),
						    'created_time' => date('H:i:s'),
						    'created_by_id' => $current_user->ID,
							  'user_id' => $user_id,
							  'pool_name' => $pool_name,
							  'pool_api_id' => $pool_api_id,
							  'pool_api_url' => $pool_api_url,
							  'pool_api_key' => $pool_api_key,
							  'pool_currency' => $pool_currency
					    )
				    );
		    if ($insert){
				  print redirect_to('./?section=pools&added=y');
		    } else {
		      print '<h1 style="color: red;">Danger! NO INSERT!</h1>'.PHP_EOL;
		    }
		  } else {
		    print '<h1 style="color: red;">Danger!</h1>'.PHP_EOL;
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
    $html .= '<form name="cloudminr_add_pool" method="post" action=".">'.PHP_EOL;
	    $html .= '<div class="row">'.PHP_EOL;
	      $html .= '<div class="col-md-2">Name</div>'.PHP_EOL;
		    $html .= '<div class="col-md-4"><input class="form-control" type="text" name="pool_name" id="pool_name"></div>'.PHP_EOL;
		    $html .= '</div>'.PHP_EOL;
	    $html .= '</div>'.PHP_EOL;
	    $html .= '<br />'.PHP_EOL;
	    $html .= '<div class="row">'.PHP_EOL;
	      $html .= '<div class="col-md-2">ID*</div>'.PHP_EOL;
		    $html .= '<div class="col-md-4"><input class="form-control" required="required" type="text" name="pool_api_id" id="pool_api_id"></div>'.PHP_EOL;
	    $html .= '</div>'.PHP_EOL;
	    $html .= '<br />'.PHP_EOL;
	    $html .= '<div class="row">'.PHP_EOL;
	      $html .= '<div class="col-md-2">URL*</div>'.PHP_EOL;
		    $html .= '<div class="col-md-4"><input class="form-control" required="required" type="text" name="pool_api_url" id="pool_api_url"></div>'.PHP_EOL;
	    $html .= '</div>'.PHP_EOL;
	    $html .= '<br />'.PHP_EOL;
	    $html .= '<div class="row">'.PHP_EOL;
	      $html .= '<div class="col-md-2">Key*</div>'.PHP_EOL;
		    $html .= '<div class="col-md-4"><input class="form-control" required="required" type="text" name="pool_api_key" id="pool_api_key"></div>'.PHP_EOL;
	    $html .= '</div>'.PHP_EOL;
	  	$html .= '<br />'.PHP_EOL;
	    $html .= '<div class="row">'.PHP_EOL;
	      $html .= '<div class="col-md-2">Currency</div>'.PHP_EOL;
		    $html .= '<div class="col-md-4"><input class="form-control" type="text" name="pool_currency" id="pool_currency"></div>'.PHP_EOL;
	    $html .= '</div>'.PHP_EOL;
	    $html .= '<br />'.PHP_EOL;
	    $html .= '<div class="row">'.PHP_EOL;
	      $html .= '<div class="col-md-6">'.PHP_EOL;
		      $html .= '<input type="hidden" name="section" value="add-pool">'.PHP_EOL;
			    $html .= '<input type="hidden" name="action" value="add-pool">'.PHP_EOL;
				  $html .= '<input type="hidden" name="user_id" value="'.$current_user->ID.'">'.PHP_EOL;
		      $html .= '<button class="btn btn-lg btn-primary btn-block" type="submit">Save</button'.PHP_EOL;
		    $html .= '</div>'.PHP_EOL;
	    $html .= '</div>'.PHP_EOL;
	  $html .= '</form>'.PHP_EOL;
	  if ( (isset($debugMode)) && ($debugMode >= 1) ){
	    $html .= $debug_output;
	  }
	} else {
	  print redirect_to('./?section=logout');
	}
?>
