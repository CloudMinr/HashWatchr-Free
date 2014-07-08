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
  if (is_numeric($current_user->ID)){{
	  if (isset($_GET['pool']) && is_numeric($_GET['pool'])){
	    $sql = "SELECT COUNT(id) FROM ".$wpdb->prefix."cloudminr_pools WHERE active='1' AND id='".$_GET['pool']."' AND user_id='".$current_user->ID."'";
		}
		$count = $wpdb->get_var($sql);
		if ($count >= 1){
		  $sql = "SELECT id FROM ".$wpdb->prefix."cloudminr_pools WHERE active='1' AND id='".$_GET['pool']."' AND user_id='".$current_user->ID."'";
			$pool_account_id = $wpdb->get_var($sql);
			$sql = "SELECT COUNT(id) FROM ".$wpdb->prefix."cloudminr_workers WHERE active='1' AND pool_account_id='".$pool_account_id."' AND user_id='".$current_user->ID."'";
			$count = $wpdb->get_var($sql);
		  if ($count >= 1){
		    $sql = "SELECT id, worker_id FROM ".$wpdb->prefix."cloudminr_workers WHERE active='1' AND pool_account_id='".$pool_account_id."' AND user_id='".$current_user->ID."'";
			  $results = $wpdb->get_results($sql);
			  foreach ($results as $result){
			    $update_sql = "UPDATE ".$wpdb->prefix."cloudminr_workers SET active='0', locked='1' WHERE active='1' AND id='".$result->id."' AND user_id='".$current_user->ID."'";
					$update = $wpdb->query($update_sql);
			    if ($update){
			      $error = 0;
			    } else {
			      $error = 1;
			    }
					$sql = "SELECT COUNT(id) FROM ".$wpdb->prefix."cloudminr_stats WHERE active='1' AND locked='0' AND pool_account_id='".$pool_account_id."' AND worker_id='".$result->worker_id."' AND user_id='".$current_user->ID."'";
				  $count = $wpdb->get_var($sql);
				  if ($count >= 1){
				    $update_sql = "UPDATE ".$wpdb->prefix."cloudminr_stats SET active='0', locked='1' WHERE active='1' AND pool_account_id='".$pool_account_id."' AND worker_id='".$result->worker_id."' AND user_id='".$current_user->ID."'";
				    $update = $wpdb->query($update_sql);
			      if ($update){
			        $error = 0;
			      } else {
			        $error = 1;
			      }
			    }
			    $sql = "SELECT COUNT(id) FROM ".$wpdb->prefix."cloudminr_stats WHERE active='0', locked='1' WHERE active='1' AND pool_account_id='".$pool_account_id."' AND worker_id='".$result->worker_id."' AND user_id='".$current_user->ID."'";
			    $count = $wpdb->get_var($sql);
			    if ($count >= 1){
			      $update_sql = "UPDATE ".$wpdb->prefix."cloudminr_stats_hourly SET active='0', locked='1' WHERE active='1' AND pool_account_id='".$pool_account_id."' AND worker_id='".$result->worker_id."' AND user_id='".$current_user->ID."'";
			      $update = $wpdb->query($update_sql);
			      if ($update){
			        $error = 0;
			      } else {
			        $error = 1;
			      }
			    }
			  }
			}
		  $update_sql = "UPDATE ".$wpdb->prefix."cloudminr_pools SET active='0', locked='1' WHERE active='1' AND id='".$pool_account_id."' AND user_id='".$current_user->ID."'";
			$update = $wpdb->query($update_sql);
			if ($update){
			  $error = 0;
			} else {
			  $error = 1;
			}
		} else {
		  $error = 1;
		}
	} else {
	  $error = 1;
	}
	if ($error == 0){
	  print redirect_to('./?section=pools&&deleted=y');
	} else {
		print redirect_to('./?section=pools&&deleted=n');
	}
?>
