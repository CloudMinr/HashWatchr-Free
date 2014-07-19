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
    if (!isset($action)){
	    $action = 'view';
	  }
	  switch ($action){
	    default:
		    global $wpdb;
		    $sql = "SELECT COUNT(*) FROM ".$wpdb->prefix."cloudminr_pools WHERE locked='0' AND user_id='".$current_user->ID."'";
		    $html .= '<div class="row">'.PHP_EOL;
	        $html .= '<div class="col-sm-2"><h3><strong>Name</strong></h3></div>'.PHP_EOL;
		      $html .= '<div class="col-sm-1"><h3><strong>ID</strong></h3></div>'.PHP_EOL;
				  $html .= '<div class="col-sm-4"><h3><strong>URL</strong></h3></div>'.PHP_EOL;
				  if ($the_plugin_header == 'internal'){
				    $html .= '<div class="col-sm-4"><h3><strong>Key</strong></h3></div>'.PHP_EOL;
					  $html .= '<div class="col-sm-1"><h3><strong>Action</strong></h3></div>'.PHP_EOL;
				  } else {
				    $html .= '<div class="col-sm-1 col-sm-offset-4"><h3><strong>Action</strong></h3></div>'.PHP_EOL;
				  }
	      $html .= '</div>'.PHP_EOL;
			  $html .= '<div style="margin: 0 0 5px 0; border-top: 1px solid #C8C8C8;"></div>';
			  global $wpdb;
			  $count = $wpdb->get_var($sql);
			  if ($count >= 1){
			    $sql = "SELECT * FROM ".$wpdb->prefix."cloudminr_pools WHERE locked='0' AND user_id='".$current_user->ID."' ORDER BY pool_name ASC";
				  $pools = $wpdb->get_results($sql);
				  foreach ($pools as $pool){
				    $html .= '<div class="row">'.PHP_EOL;
	            $html .= '<div class="col-sm-2"><h3><strong>'.PHP_EOL;
						    if ($pool->pool_name == 'not_set'){
							    $html .= '<h4>'.$pool->id.'</h4>'.PHP_EOL;
							  } else {
							    $html .= '<h4>'.$pool->pool_name.'</h4>'.PHP_EOL;
							  }
						  $html .= '</div>'.PHP_EOL;
		          $html .= '<div class="col-sm-1"><h4>'.$pool->pool_api_id.'</h4></div>'.PHP_EOL;
				      $html .= '<div class="col-sm-4"><h4>'.$pool->pool_api_url.'</h4></div>'.PHP_EOL;
						  if ($the_plugin_header == 'internal'){
				        $html .= '<div class="col-sm-4"><h4>'.$pool->pool_api_key.'</h4></div>'.PHP_EOL;
						  }
						  if ($the_plugin_header == 'internal'){
						    $html .= '<div class="col-sm-1"><h4><a href="./?section=edit-pool&pool='.$pool->id.'">Edit</a></h4></div>'.PHP_EOL;
						  } else {
						    $html .= '<div class="col-sm-1 col-sm-offset-4"><h5><a href="./?section=edit-pool&pool='.$pool->id.'"><i class="icon-2x icon-edit"></i></a></h5></div>'.PHP_EOL;
						  }
	          $html .= '</div>'.PHP_EOL;
					  $html .= '<div style="margin: 0 0 5px 0; border-top: 1px solid #C8C8C8;"></div>';
				  }
			  } else {
			    $html .= '<div class="row"><div class="col-sm-8"><h4>No Pools to display</h4></div></div>'.PHP_EOL;
				  $html .= '<div style="margin: 0 0 5px 0; border-top: 1px solid #C8C8C8;"></div>';
			  }
		  break;
	  }
	  if ($count <= 5){
	    $html .= '<p style="line-height: 6em;">&nbsp;</p>'.PHP_EOL;
	  }
	} else {
	  print redirect_to('./?section=logout');
	}
?>
