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

		$wtitle = $options['wtitle']; $autoplay = $options['autoplay']; $width = $options['width']; $height = $options['height'];

		echo $before_widget;
		if($title != '') echo $before_title . $title . $after_title;

		$vids = $wpdb->get_results("SELECT * FROM `".$wpdb->prefix."randomyoutube`");
		if($wpdb->num_rows == 0){echo "<div align='center'>no video</div>";}
		else{
		$video = $wpdb->get_results("SELECT * FROM `".$wpdb->prefix."randomyoutube` ORDER BY RAND() LIMIT 1");
		$url=$video[0]->url; $vtitle=$video[0]->vtitle;
		?>
		<div align="left">
		<h2><?php echo $wtitle; ?></h2>
			<font style="font-size:10px;"><?php echo $vtitle; ?><br /></font>
			<iframe width="<?php echo $width; ?>" height="<?php echo $height; ?>" src="http://www.youtube.com/embed/<?php echo $url; ?>?<?php if($options['autoplay']==1){?>rel=0&autoplay=1<?php } ?>" frameborder="0" allowfullscreen="allowfullscreen"></iframe>
			<?php if($options['link']==1){?><br /><a href="http://wordpress.org/plugins/random-youtube-video/" target="_blank">RYV plugin by Shobba</a><?php } ?>
		</div>

<?php	
	}
		echo $after_widget;
	}
	 function form( $instance ){
		$options = $newoptions = get_option('ryv_mywidget');
		if ( $_POST['ryv_submit'] ) {
			$newoptions['wtitle'] = strip_tags(stripslashes($_POST['ryv_wtitle']));
			$newoptions['autoplay'] = strip_tags(stripslashes($_POST['ryv_autoplay']));
			$newoptions['width'] = strip_tags(stripslashes($_POST['ryv_width']));
			$newoptions['height'] = strip_tags(stripslashes($_POST['ryv_height']));
			$newoptions['link'] = strip_tags(stripslashes($_POST['ryv_link']));
			$newoptions['url'] = strip_tags(stripslashes($_POST['ryv_url']));
		}
		if ( $options != $newoptions ) {
			$options = $newoptions;
			update_option('ryv_mywidget', $options);
		}
		$wtitle = htmlspecialchars($options['wtitle'], ENT_QUOTES);
		$width = htmlspecialchars($options['width'], ENT_QUOTES);
		$height = htmlspecialchars($options['height'], ENT_QUOTES);
		
		?>

		<p>
			<label for="ryv_wtitle"><strong><?php _e('Title:'); ?></strong>
				<input style="width: 200px;" id="ryv_wtitle" name="ryv_wtitle" type="text" value="<?php echo $options['wtitle']; ?>" />
			</label>
		</p>		
		<p>
			<label for="ryv_width"><strong><?php _e('Width of video:'); ?></strong>
				<input style="width: 50px;" id="ryv_width" name="ryv_width" type="text" value="<?php echo $options['width']; ?>" /> <em>( px )</em>
			</label>
			<label for="ryv_height"><strong><?php _e('Height of video:'); ?></strong>
				<input style="width: 50px;" id="ryv_height" name="ryv_height" type="text" value="<?php echo $options['height']; ?>" /> <em>( px )</em>
			</label>
		</p>		
		<p>
		<strong>Other Options</strong>
		<br />
			<label for="ryv_autoplay">
				<input type="Checkbox" name="ryv_autoplay" id="ryv_autoplay" value="1" <?php if($options['autoplay']==1){echo "checked";}?>> AutoPlay?
			</label>			
			<label for="ryv_link">
				<input type="Checkbox" name="ryv_link" id="ryv_link" value="1" <?php if($options['link']==1){echo "checked";}?>> Show link to plugin?
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