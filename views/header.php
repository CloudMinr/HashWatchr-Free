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

if ( (is_user_logged_in()) && (is_numeric($current_user->ID)) ){
  //BEGIN TITLE ROW
	if ($active_theme_name == 'Flat Theme'){
	  $html .= '<section id="title" class="green-sea" style="padding: 50px;">'.PHP_EOL;
		$html .= '<div class="container">'.PHP_EOL;
	}
	$html .= '<div class="row-fluid">'.PHP_EOL;
	  //BEGIN LEFT COLUMN
	  switch ($section){
	    case 'add-pool':
		    $html .= '<div class="col-xs-6"><h1>Add Pool</h1></div>'.PHP_EOL;
		  break;
		  case 'add-worker':
		    $html .= '<div class="col-xs-6"><h1>Add Minr</h1></div>'.PHP_EOL;
		  break;
		  case 'admin':
		    $html .= '<div class="col-xs-6"><h1>Admin</h1></div>'.PHP_EOL;
		  break;
		  case 'charts':
		    $html .= '<div class="col-xs-6"><h1>Chrts</h1></div>'.PHP_EOL;
		  break;
		  case 'edit-pool':
		    $html .= '<div class="col-xs-6"><h1>Edit Pool</h1></div>'.PHP_EOL;
		  break;
		  case 'edit-worker':
		    $html .= '<div class="col-xs-6"><h1>Edit Minr</h1></div>'.PHP_EOL;
		  break;
			case 'pools':
			  $html .= '<div class="col-xs-6"><h1>Pools</h1></div>'.PHP_EOL;
			break;
		  default:
		    $html .= "<div class='col-xs-6'><h1>My Minrs</h1></div>".PHP_EOL;
		  break;
	  }
	  //END LEFT COLUMN
	  //BEGIN RIGHT COLUMN
		if ($the_plugin_header == 'internal'){
	    switch ($section){
		    case 'add-pool':
				  $html .= '<div style="text-align: right;" class="col-xs-2 col-xs-offset-4"><a href="javascript:history.back();"><h2>Back</h2></a></div>'.PHP_EOL;
			  break;
		    case 'add-worker':
	        $html .= '<div style="text-align: right;" class="col-xs-2 col-xs-offset-4"><a href="javascript:history.back();"><h2>Back</h2></a></div>'.PHP_EOL;
			  break;  
		    case 'admin':
		      $html .= '<div style="text-align: right;" class="col-xs-2 col-xs-offset-4"><a href="./?section=minrs"><h2>My Minrs</h2></a></div>'.PHP_EOL;
			  break;
			  case 'dashboard':
		      $html .= '<div style="text-align: right;" class="col-xs-2 col-xs-offset-4"><a href="./?section=admin"><h2>Admin</h2></a></div>'.PHP_EOL;
			  break;
			  case 'edit-pool':
		      $html .= '<div style="text-align: right;" class="col-xs-2 col-xs-offset-4"><a href="javascript:history.back();"><h2>Back</h2></a></div>'.PHP_EOL;
			  break;
		    case 'edit-worker':
	        $html .= '<div style="text-align: right;" class="col-xs-2 col-xs-offset-4"><a href="javascript:history.back();"><h2>Back</h2></a></div>'.PHP_EOL;
			  break;
			  case 'pools':
		      $html .= '<div style="text-align: right;" class="col-xs-2 col-xs-offset-4"><a href="./?section=minrs"><h2>My Minrs</h2></a></div>'.PHP_EOL;
			  break;
			  default:
		      $html .= '<div style="text-align: right;" class="col-xs-2 col-xs-offset-4"><a href="./?section=admin"><h2>Admin</h2></a></div>'.PHP_EOL;
			  break;
		  }
		}
	  //END RIGHT COLUMN
	$html .= '</div>'.PHP_EOL;
	if ($active_theme_name == 'Flat Theme'){
	  $html .= '</div>'.PHP_EOL;
	  $html .= '</section>'.PHP_EOL;
	}
	//END TITLE ROW
	//BEGIN NAVIGATION ROW
	if ($the_plugin_header == 'internal'){
	  $html .= '<div class="row">'.PHP_EOL;
	    switch ($section){
			  case 'minrs':
			  case 'charts':
			  case 'dashboard':
				  $html .= '<div class="col-xs-2" style="text-align: center; border: solid 1px #c3c3c3; border-top-left-radius: 15px; border-top-right-radius: 15px;">'.PHP_EOL;
		        $html .= '<a href="./"><h2>My Minrs</h2></a>'.PHP_EOL;
		      $html .= '</div>'.PHP_EOL;
			    $html .= '<div class="col-xs-2" style="text-align: center; border: solid 1px #c3c3c3; border-top-left-radius: 15px; border-top-right-radius: 15px;">'.PHP_EOL;
		        $html .= '<a href="./?section=charts"><h2>Chrts</h2></a>'.PHP_EOL;
		      $html .= '</div>'.PHP_EOL;
		  	  $html .= '<div class="col-xs-2" style="text-align: center; border: solid 1px #c3c3c3; border-top-left-radius: 15px; border-top-right-radius: 15px;">'.PHP_EOL;
		        $html .= '<a href="./?section=charts-v2"><h2>Chrts BETA</h2></a>'.PHP_EOL;
		      $html .= '</div>'.PHP_EOL;
		      $html .= '<div class="col-xs-4" style="border-bottom: solid 1px #c3c3c3;">'.PHP_EOL;
		        $html .= '<h2>&nbsp;</h2>'.PHP_EOL;
		      $html .= '</div>'.PHP_EOL;
		      $html .= '<div class="col-xs-2" style="text-align: center; border: solid 1px #c3c3c3; border-top-left-radius: 15px; border-top-right-radius: 15px;">'.PHP_EOL;
		        $html .= '<a href="./?section=profile"><h2>Profile</h2></a>'.PHP_EOL;
				  $html .= '</div>'.PHP_EOL;
			  break;
		  }
	  $html .= '</div>'.PHP_EOL;
	}
	//END NAVIGATION ROW
	//BEGIN SUB-NAVIGATION ROW
	
	  switch ($section){
		  case 'admin':
		  case 'import-worker':
		  case 'pools':
			  if ($active_theme_name == 'Flat Theme'){
	        $html .= '<section id="title" class="emerald" style="padding: 10px;">'.PHP_EOL;
	        $html .= '<div class="container">'.PHP_EOL;
					$border = '0';
					$border_bottom = '0';
	      } else {
				  $border = 'solid 1px #c3c3c3; border-top-left-radius: 15px; border-top-right-radius: 15px';
					$border_bottom = 'solid 1px #c3c3c3';
				}
				
				$html .= '<div class="row">'.PHP_EOL;
				  $html .= '<div class="col-sm-6">'.PHP_EOL;
					  $html .= '<ul class="nav navbar-nav navbar-main">'.PHP_EOL;
						  if ($section == 'admin'){
					      $html .= '<li class="menu-item menu-item-type-custom menu-item-object-custom current-menu-item">'.PHP_EOL;
		              $html .= '<a href="./?section=admin"><h2 style="padding-bottom: 5px;">Minrs</h2></a>'.PHP_EOL;
					      $html .= '</li>'.PHP_EOL;
				      } else {
				        $html .= '<li class="menu-item menu-item-type-custom menu-item-object-custom">'.PHP_EOL;
				          $html .= '<a href="./?section=admin"><h2 style="padding-bottom: 5px;">Minrs</h2></a>'.PHP_EOL;
					      $html .= '</li>'.PHP_EOL;
				      }
				      if ($section == 'pools'){
					      $html .= '<li class="menu-item menu-item-type-custom menu-item-object-custom current-menu-item">'.PHP_EOL;
		              $html .= '<a href="./?section=pools"><h2 style="padding-bottom: 5px;">Pools</h2></a>'.PHP_EOL;
					      $html .= '</li>'.PHP_EOL;
				      } else {
					      $html .= '<li class="menu-item menu-item-type-custom menu-item-object-custom">'.PHP_EOL;
				          $html .= '<a href="./?section=pools"><h2 style="padding-bottom: 5px;">Pools</h2></a>'.PHP_EOL;
					      $html .= '</li>'.PHP_EOL;
				      }
						$html .= '</ul>'.PHP_EOL;
				  $html .= '</div>'.PHP_EOL;
					if ($current_user->user_level >= 6){
					  if ( ($section == 'admin') || ($section == 'import-worker') ){
						  $html .= '<div class="col-sm-2 col-sm-offset-4">'.PHP_EOL;
						} else {
						  $html .= '<div class="col-sm-1 col-sm-offset-5">'.PHP_EOL;
						}
					} else {
					  if ( ($section == 'admin') || ($section == 'import-worker') ){
						  $html .= '<div class="col-sm-2 col-sm-offset-4">'.PHP_EOL;
						} else {
						  $html .= '<div class="col-sm-1 col-sm-offset-5">'.PHP_EOL;
						}
					}
					  $html .= '<ul class="nav navbar-nav navbar-main">'.PHP_EOL;
						  if ($section == 'admin'){
							  $html .= '<li class="menu-item menu-item-type-custom menu-item-object-custom">'.PHP_EOL;
		              $html .= '<a href="./?section=import-worker"><h2 style="padding-bottom: 5px;">Import</h2></a>'.PHP_EOL;
					      $html .= '</li>'.PHP_EOL;
							} elseif ($section == 'import-worker'){
							  $html .= '<li class="menu-item menu-item-type-custom menu-item-object-custom current-menu-item">'.PHP_EOL;
		              $html .= '<a href="./?section=import-worker"><h2 style="padding-bottom: 5px;">Import</h2></a>'.PHP_EOL;
					      $html .= '</li>'.PHP_EOL;
							}
							$html .= '<li class="menu-item menu-item-type-custom menu-item-object-custom">'.PHP_EOL;
							  if ($section == 'pools'){
		    	        $html .= '<a href="./?section=add-pool"><h2 style="padding-bottom: 5px;">Add</h2></a>'.PHP_EOL;
		    	      } else {
		    	        $html .= '<a href="./?section=add-worker"><h2 style="padding-bottom: 5px;">Add</h2></a>'.PHP_EOL;
		    	      }
							$html .= '</li>'.PHP_EOL;
						$html .= '</ul>'.PHP_EOL;
					$html .= '</div>'.PHP_EOL;
			  if ($active_theme_name == 'Flat Theme'){
			    $html .= '</div>'.PHP_EOL;
	        $html .= '</section>'.PHP_EOL;
			  }
			  $html .= '</div>'.PHP_EOL;
		  break;
		}
	//END SUB-NAVIGATION ROW
} else {
  print redirect_to('./?section=logout');
}
