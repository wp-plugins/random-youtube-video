<?php

class ryv_widget extends WP_Widget{

function ryv_widget()
    { 

        $this->WP_Widget( false, "Random YT Video", array( 'description' => 'Displays a random YouTube video on your sidebar!' ) );

    }
	 function widget( $args ){
		global $wpdb;

		extract($args);
		$options = get_option('ryv_mywidget');

		$title = $options['title']; $autoplay = $options['autoplay']; $width = $options['width']; $height = $options['height'];

		echo $before_widget;
		if($title != '') echo $before_title . $title . $after_title;

		$vids = $wpdb->get_results("SELECT * FROM `".$wpdb->prefix."randomyoutube`");
		if($wpdb->num_rows == 0){echo "<div align='center'>no video</div>";}
		else{
		$video = $wpdb->get_results("SELECT * FROM `".$wpdb->prefix."randomyoutube` ORDER BY RAND() LIMIT 1");
		$url=$video[0]->url; $name=$video[0]->titel;
		?>
		<div align="left">
			<font style="font-size:10px;"><? echo $name; ?><br /></font>
			<iframe width="<?=$width?>" height="<?=$height?>" src="//www.youtube.com/embed/<? echo $url; ?>?<?=$autoplay?>" frameborder="0" allowfullscreen></iframe>
			<? if($options['link']==1){?><br /><a href="http://wordpress.org/plugins/random-youtube-video/" target="_blank">RYV plugin by Shobba</a><?}?>
		</div>

<?		}
		echo $after_widget;
	}
	 function form( $instance ){
		$options = $newoptions = get_option('ryv_mywidget');
		if ( $_POST['ryv_submit'] ) {
			$newoptions['title'] = strip_tags(stripslashes($_POST['ryv_title']));
			$newoptions['autoplay'] = strip_tags(stripslashes($_POST['ryv_autoplay']));
			$newoptions['width'] = strip_tags(stripslashes($_POST['ryv_width']));
			$newoptions['height'] = strip_tags(stripslashes($_POST['ryv_height']));
			$newoptions['link'] = strip_tags(stripslashes($_POST['ryv_link']));
		}
		if ( $options != $newoptions ) {
			$options = $newoptions;
			update_option('ryv_mywidget', $options);
		}
		$title = htmlspecialchars($options['title'], ENT_QUOTES);
		$autoplay = htmlspecialchars($options['autoplay'], ENT_QUOTES);
		$width = htmlspecialchars($options['width'], ENT_QUOTES);
		$height = htmlspecialchars($options['height'], ENT_QUOTES);
		?>
		<p>
			<label for="ryv_title"><?php _e('Title:'); ?>
				<input style="width: 200px;" id="ryv_title" name="ryv_title" type="text" value="<?=$options['title']; ?>" />
			</label>
		</p>
		<p>
			<label for="ryv_autoplay"><?php _e('AutoPlay:'); ?>
				<input style="width: 200px;" id="ryv_autoplay" name="ryv_autoplay" type="text" value="<?=$options['autoplay']; ?>" />
				<br />
				Add the following to enable autoplay <strong> rel=0&autoplay=1 </strong>
				<br />
				To disable it just remove it and save and repeat! 			
			</label>
		</p>
		<p>
			<label for="ryv_width"><?php _e('Width of video:'); ?>
				<input style="width: 50px;" id="ryv_width" name="ryv_width" type="text" value="<?=$options['width']; ?>" /> ( px )
			</label>
		</p>		
		<p>
			<label for="ryv_height"><?php _e('Height of video:'); ?>
				<input style="width: 50px;" id="ryv_height" name="ryv_height" type="text" value="<?=$options['height']; ?>" /> ( px )
			</label>
		</p>	
		<p>
			<label for="ryv_link">
				<input type="Checkbox" name="ryv_link" id="ryv_link" value="1" <? if($options['link']==1){echo "checked";}?>> Show link to plugin?
			</label>
		</p>
		<p style="text-align:right;margin-right:40px;"><?php echo $error; ?></p>
		<input type="hidden" id="ryv_submit" name="ryv_submit" value="1" />
        <?php

    }

}

//Register the widget
add_action( 'widgets_init', 'register_ryv_widget' );

function register_ryv_widget() { register_widget( 'ryv_widget' ); }

?>