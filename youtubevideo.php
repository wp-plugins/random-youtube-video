<?php
/*
Plugin Name: Random YouTube Video
Plugin URI: http://wordpress.org/plugins/random-youtube-video/
Description: This widget shows a random youtube video from your video list in your wordpress sidebar
Download URL: http://www.soslidesigns.com/files/random-youtube-videos.zip
Author: Shobba, roycegracie, zigvt85
Author Notes: Community patch by the authors above fixed from using object to iframe and menu glitch where
the menu was going under the video if close enough.
Author URI: http://wordpress.org/plugins/random-youtube-video/
Version: 1.9
License: GPL compatible
*/

/*  Copyright 2008  Marcel Eichhorn

    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation; either version 2 of the License, or
    (at your option) any later version.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    For a copy of the GNU General Public License see <http://www.gnu.org/licenses/>.
*/

require_once("widget.php");

function addrowfunc()
{
    $url = get_bloginfo('wpurl');
    echo "\n\t<!-- Added By Random YouTube Video -->\n\t";
    ?>
	<script language="JavaScript">
		function newrow(last, obj){
			var latestid = last;
			tb = document.getElementById("asd");
			var inn2 = '<div style="width:100%"><strong>Title:</strong> <input type="Text" name="titel['+latestid+']" value="" size="35"><strong>Code:</strong> <input type="Text" name="url['+latestid+']" value="" size="35"></div>';
			tb.innerHTML += inn2;
			/*
			tb = document.getElementById("greattable");
			tr = document.createElement("tr");
			th1 = document.createElement("th");
			th2 = document.createElement("th");

			tb.appendChild(tr);
			tr.appendChild(th1);
			tr.appendChild(th2);

			th1.innerHTML = '<input type="Text" name="titel['+latestid+']" value="" size="50">';
			th2.innerHTML = '<input type="Text" name="url['+latestid+']" value="" size="50">';*/
			latestid += 1;
			obj.onclick = function(){newrow(latestid, obj);};

			return false;
		}
	</script>
    <?
//    echo "\n\t<script language='text/javascript' src='".$url."/wp-content/plugins/randomyoutubevideo/addrow.js'></script>";
}
function ryv_adminpage(){
	global $wpdb;
	if($_POST['ryv_submit']){
	$leeren = $wpdb->query("TRUNCATE TABLE `".$wpdb->prefix."randomyoutube`");
		if($_POST['url']){
			foreach($_POST['url'] as $key => $con){
				if($con){
					$ins = $wpdb->query("INSERT INTO `".$wpdb->prefix."randomyoutube` (`id`,`url`,`titel`) VALUES (".$key.",'".$con."','".$_POST['titel'][$key]."')");
				}
			}
			$message = '<div class="updated"><p><strong>Saved.</strong></p></div>';
		}
	}?>
	<div style="width:1700px;margin-left:auto;margin-right:auto;">
		<?=$message?>
		<h2>Random YouTube Video Management</h2>
		<form name="ryv" method="post" action="<?php echo $_SERVER['REQUEST_URI']; ?>">
			<fieldset class="options">
				<legend>YouTube Video List</legend>
				<div style="text-align:right;">
					To delete one entry just delete its url and click "Save"
				</div>
				<div class="editform" id="greattable">
				<div style="width:100%;background-color:#464646;color:#FFF;">
					<div style="text-align:center;">Your YouTube video code (looks like: <strong>j9c5N2HzaHY</strong>)</div>
				</div>
					<?
					$vids = $wpdb->get_results("SELECT * FROM `".$wpdb->prefix."randomyoutube` ORDER BY id");
					foreach ($vids as $vid){
						$letzte_id = $vid->id;
						?>
					<div style="text-align:right;">
					<div style="margin-right:645px;">
						<iframe width="300" height="200" src="//www.youtube.com/embed/<?=$vid->url?>" frameborder="0" allowfullscreen></iframe>
					</div>
					<br />
					<strong>Title:</strong><input type="Text" name="titel[<?=$vid->id?>]" value="<?=$vid->titel?>" size="50">
					<strong>Code:</strong><input type="Text" name="url[<?=$vid->id?>]" value="<?=$vid->url?>" size="50">
					</div>
					<br />
					<?
					}
					?>
				</div>
				<div class="editform" id="asd" style="width:80%;float:left;position:relative;z-index:9990;margin-top:-535px;"></div>
				<div class="editform" style="width:135px;float:right;"><input class="button" type="button" name="addrow_button" value="+ add row" onClick="return newrow(<?=$letzte_id+1?>, this);"><input class="button" type="Submit" name="ryv_submit" value="Save"></div>
				
			</fieldset>
		</form>
		<div style="text-align:center;">
			<strong>Maintained and Coded By:</strong> <a href="http://www.soslidesigns.com" target="_blank">So Sli Designs</a>
		</div>
	</div>
	<?
}

function ryv_adminmenu() {
    add_submenu_page('options-general.php', 'Random YouTube Video &raquo; Manage Videos', 'Random Youtube Videos', 10, __FILE__, 'ryv_adminpage');
}
add_action('admin_menu', 'ryv_adminmenu');

function ryv_install(){
	global $wpdb;

	$table_name = $wpdb->prefix . "randomyoutube";
	if($wpdb->get_var("SHOW TABLES LIKE '$table_name'") != $table_name){
	$sql = "CREATE TABLE " . $table_name . " (
	  id INT UNSIGNED NOT NULL AUTO_INCREMENT,
	  url TEXT NOT NULL,
	  titel TEXT NOT NULL,
	  autoplay TEXT NOT NULL,
	  width TEXT NOT NULL,
	  height TEXT NOT NULL,
	  UNIQUE KEY id (id)
	);";

	require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
	dbDelta($sql);
	}
}

register_activation_hook(__FILE__,'ryv_install');
//if ($_REQUEST['page'] == "youtubevideo.php")
    add_action('admin_head', 'addrowfunc');
?>