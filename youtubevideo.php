<?php
/**
 * Plugin Name: Random YouTube Video
 * Plugin URI: http://wordpress.org/plugins/random-youtube-video/
 * Description: This widget shows a random youtube video from your video list in your wordpress sidebar
 * Version: 2.3
 * Author: Shobba, roycegracie, zigvt85
 * Author URI: http://www.soslidesigns.com
 * License: GPL
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
			var inn2 = '<table width="100%"><tr><th><input type="Text" name="titel['+latestid+']" value="" size="50"></th><th><input type="Text" name="url['+latestid+']" value="" size="50"></th></tr></table>';
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
    <?php
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
	<div class="wrap">
		<?=$message?>
		<h2>Random YouTube Video Management</h2>
		<form name="ryv" method="post" action="<?php echo $_SERVER['REQUEST_URI']; ?>">
			<fieldset class="options">
				<legend>YouTube Video List</legend>
				<center>
				To delete one entry just delete its url and click "Save"
				<table width="80%" cellspacing="2" cellpadding="3" class="editform" id="greattable">
					<tr class="videowrap">
						<th style="text-align: center;">Video Title (Optional)</th>
						<th style="text-align: center;">Video ID (From Embed Code)<br>(looks like: <span class="videoid"><a href="https://www.youtube.com/watch?v=096t3RyU0jY" target="_blank">096t3RyU0jY</a></span>)</th>
					</tr>
					<?php
					$vids = $wpdb->get_results("SELECT * FROM `".$wpdb->prefix."randomyoutube` ORDER BY id");
					foreach ($vids as $vid){
						$letzte_id = $vid->id;
						?>
					<tr>
						<th style="text-align: center;"><input type="Text" name="titel[<?=$vid->id?>]" value="<?=$vid->titel?>" size="50"></th>
						<th style="text-align: center;"><input type="Text" name="url[<?=$vid->id?>]" value="<?=$vid->url?>" size="50"></th>
					</tr>
					<?php
					}
					?>
				</table><table width="80%" cellspacing="2" cellpadding="3" class="editform"><tr><td id="asd"></td></tr></table>
				<table width="80%" cellspacing="2" cellpadding="3" class="editform">
					<tr>
						<th colspan="2" style="text-align: left;"><input class="button" type="button" name="addrow_button" value="+ add row" onClick="return newrow(<?=$letzte_id+1?>, this);"></th>
					</tr>
				</table><input class="button" type="Submit" name="ryv_submit" value="Save"></center>
			</fieldset>
			<div style="text-align:center;">
				<strong>Designed and Coded By:</strong> <a href="http://www.soslidesigns.com" target="_blank">So Sli Designs</a> - <strong>Orginal Coder:</strong> <a href="https://profiles.wordpress.org/shobba/" target="_blank">Shobba</a>
				<br />
				This plugin is now maintained by the community!
			</div>
		</form>
	</div>
	<?php
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
	  ryv_name TEXT NOT NULL,	  
	  autoplay TEXT NOT NULL,
	  width TEXT NOT NULL,
	  height TEXT NOT NULL,
	  UNIQUE KEY id (id)
	);";

	require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
	dbDelta($sql);
	}
}
    add_action( 'admin_enqueue_scripts', 'safely_add_stylesheet_to_admin' );

    /*Add stylesheet to the page*/
    function safely_add_stylesheet_to_admin() {
        wp_enqueue_style( 'prefix-style', plugins_url('css/style.css', __FILE__) );
    }

register_activation_hook(__FILE__,'ryv_install');
//if ($_REQUEST['page'] == "youtubevideo.php")
    add_action('admin_head', 'addrowfunc');
	
?>