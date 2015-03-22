<?php
/*
Plugin Name: Post Paging
Plugin URI: http://wp-learning.net
Description: Show next and previous post links at posts
Version: 1.0
Author: Tomek
Author URI: http://wp-learning-net
Text Domain: paging
Domain Path: /lang
*/

load_plugin_textdomain( 'paging', '', dirname( plugin_basename( __FILE__ ) ) . '/lang' );

function paging($content) { 
	if (get_option('post_paging_background_color_transparent','') == NULL) {
	    $bgcolor = get_option('post_paging_background_color','');
	} else {
		$bgcolor = "transparent";
	}
	if (get_option('post_paging_use_image','') == NULL) {
		if (get_option('post_paging_show_title','') == 0) {
			$title = "no";
		} elseif (get_option('post_paging_show_title','') == 1) {
			$title = "yes";
		}
		if (get_option('post_paging_show_title','') == 1 && get_option('post_paging_prev_post_text','') != NULL) {
			$prev = "<center>".get_option('post_paging_prev_post_text','')."</center>";
		} elseif (get_option('post_paging_prev_post_text','') != NULL) {
			$prev = get_option('post_paging_prev_post_text','');
		}
		if (get_option('post_paging_show_title','') == 1 && get_option('post_paging_next_post_text','') != NULL) {
			$next = "<center>".get_option('post_paging_next_post_text','')."</center>";
		} elseif (get_option('post_paging_next_post_text','') != NULL) {
			$next = get_option('post_paging_next_post_text','');
		}
	} else {
		if (get_option('post_paging_show_title','') == 1) {
			if (get_option('post_paging_prev_post_text','') != NULL || get_option('post_paging_next_post_text','') != NULL) {
				$prev_post = get_option('post_paging_prev_post_text','')." - ".get_adjacent_post(false, '', true)->post_title;
				$next_post = get_option('post_paging_next_post_text','')." - ".get_adjacent_post(false, '', false)->post_title;
			} else {
				$prev_post = get_adjacent_post(false, '', true)->post_title;
				$next_post = get_adjacent_post(false, '', false)->post_title;
			}
		}
		if (get_option('post_paging_show_title','') == NULL) {
			if (get_option('post_paging_prev_post_text','') != NULL || get_option('post_paging_next_post_text','') != NULL) {
				$prev_post = get_option('post_paging_prev_post_text','');
				$next_post = get_option('post_paging_next_post_text','');
			}
		}
	    $prev = "<img width='".get_option('post_paging_images_width','')."' title='".$prev_post."' src='".get_option('post_paging_prev_post_image','')."'>";
	    $next = "<img width='".get_option('post_paging_images_width','')."' title='".$next_post."' src='".get_option('post_paging_next_post_image','')."'>";
	}
	
	
    if (is_single()) {
    ?>
	<style>
		.arrowLeft a {
			position: fixed;
			z-index: 100;
			left: -5px;
			top: <?php echo get_option('post_paging_position','') ?>;
			padding: 15px 10px;
			-webkit-transition: .2s ease-in;
			-moz-transition: .2s ease-in;
			-o-transition: .2s ease-in;
			transition: .2s ease-in;
		}
 
		.arrowLeft a:hover {
			left: 0;
			-webkit-transition: .2s ease-in;
			-moz-transition: .2s ease-in;
			-o-transition: .2s ease-in;
			transition: .2s ease-in;
			color: <?php echo get_option('post_paging_hover_color','') ?>;
			text-decoration: <?php echo get_option('post_paging_text_decoration','') ?>;
		}
 
		.arrowRight a {
			position: fixed;
			z-index: 100;
			right: -5px;
			top: <?php echo get_option('post_paging_position','') ?>;
			padding: 15px 10px;
			-webkit-transition: .2s ease-in;
			-moz-transition: .2s ease-in;
			-o-transition: .2s ease-in;
			transition: .2s ease-in;
		}

		.arrowRight a:hover {
			right: 0;
			-webkit-transition: .2s ease-in;
			-moz-transition: .2s ease-in;
			-o-transition: .2s ease-in;
			transition: .2s ease-in;
			color: <?php echo get_option('post_paging_hover_color','') ?>;
			text-decoration: <?php echo get_option('post_paging_text_decoration','') ?>;
		}

		.navigation-bar a {
			background: <?php echo $bgcolor ?>;
			color: <?php echo get_option('post_paging_text_color','') ?>;
			text-decoration: <?php echo get_option('post_paging_text_decoration','') ?>;
			font-family: <?php echo get_option('post_paging_font_family','') ?>;
			font-size: <?php echo get_option('post_paging_font_size','') ?>px;
		}
	</style>

	<div class="navigation-bar">
 		<div class="arrowLeft"><?php previous_post('%',$prev,$title); ?></div>
 		<div class="arrowRight"><?php next_post('%',$next,$title); ?></div>		
	</div>
    <?php
   }
   return $content;
}

function paging_colorpicker(){
	wp_enqueue_style('wp-color-picker');
	wp_enqueue_script('wp-color-picker');
}

function paging_settings() {
 ?>
 <script>
   (function( $ ) {
	$(function() {
	$('.post_paging_hover_color').wpColorPicker();
	$('.post_paging_text_color').wpColorPicker();
	$('.post_paging_background_color').wpColorPicker();
	});
   })( jQuery );
 </script>
    <h2><?php _e('Post Paging settings','paging')?></h2>
	<form method="post" action="options.php">
    <?php settings_fields('paging_settings_page'); ?>
    <?php do_settings_sections('paging_settings_page'); ?>

				<table class="wp-list-table widefat fixed bookmarks" style="width: 100%; border-radius: 4px;">
					<tbody>
						<tr>
							<td>
									<div><label for="post_paging_position">
										<?php _e('Position:','paging') ?> <select style="width:100px"name="post_paging_position" id="post_paging_position">
										<option value="<?php echo get_option('post_paging_position','') ?>"><?php echo get_option('post_paging_position','') ?></option>
										<option value="10%"><?php _e('Top','paging'); ?></option>
										<option value="50%"><?php _e('Middle','paging'); ?></option>
										<option value="90%"><?php _e('Bottom','paging'); ?></option>
									</select></label></div>
									<div><label for="post_paging_background_color">
										<?php _e('Background color:','paging') ?> <input type="text" class="post_paging_background_color" name="post_paging_background_color" id="post_paging_background_color" value="<?php echo get_option('post_paging_background_color','') ?>">
									</label>
									<label for="post_paging_background_color_transparent">
									<input type="checkbox" name="post_paging_background_color_transparent" id="post_paging_background_color_transparent" value="1" <?php checked(get_option('post_paging_background_color_transparent')); ?>"><?php _e('Set as transparent','paging') ?>
									</div>
									<div><label for="post_paging_text_color">
										<?php _e('Text color:','paging') ?> <input type="text" class="post_paging_text_color" name="post_paging_text_color" id="post_paging_text_color" value="<?php echo get_option('post_paging_text_color','') ?>">
									</label></div>
									<div><label for="post_paging_hover_color">
										<?php _e('Hover color:','paging') ?> <input type="text" class="post_paging_hover_color" name="post_paging_hover_color" id="post_paging_text_color" value="<?php echo get_option('post_paging_hover_color','') ?>">
									</label></div>
									<div><label for="post_paging_font_family">
										<?php _e('Font family:','paging') ?> <input type="text" size="60" name="post_paging_font_family" id="post_paging_font_family" value="<?php echo get_option('post_paging_font_family','') ?>">
									</label></div>
									<div><label for="post_paging_font_size">
										<?php _e('Font size (numbers only):','paging') ?> <input type="text" size="5" name="post_paging_font_size" id="post_paging_font_size" value="<?php echo get_option('post_paging_font_size','') ?>">
									</label></div>
									<div><label for="post_paging_prev_post_text">
										<?php _e('Previous post text:','paging') ?> <input type="text" size="60" name="post_paging_prev_post_text" id="post_paging_prev_post_text" value="<?php echo get_option('post_paging_prev_post_text','') ?>">
									</label></div>
									<div><label for="post_paging_next_post_text">
										<?php _e('Next post text:','paging') ?> <input type="text" size="60" name="post_paging_next_post_text" id="post_paging_text_color" value="<?php echo get_option('post_paging_next_post_text','') ?>">
									</label></div>
									<div><label for="post_paging_text_decoration">
										<?php _e('Text decoration:','paging') ?> <select style="width:100px"name="post_paging_text_decoration" id="post_paging_text_decoration">
										<option value="<?php echo get_option('post_paging_text_decoration','') ?>"><?php echo get_option('post_paging_text_decoration','') ?></option>
										<option value="none"><?php _e('None','paging'); ?></option>
										<option value="underline"><?php _e('Underline','paging'); ?></option>
										<option value="overline"><?php _e('Overline','paging'); ?></option>
										<option value="line-through"><?php _e('Line-through','paging'); ?></option>
									</select></label></div>
									<div><label for="post_paging_show_title">
										<input type="checkbox" name="post_paging_show_title" id="post_paging_show_title" value="1" <?php checked(get_option('post_paging_show_title')); ?>"><?php _e('Show post title','paging') ?>
									</label></div>
									<div><label for="post_paging_use_image">
										<input type="checkbox" name="post_paging_use_image" id="post_paging_use_image" value="1" <?php checked(get_option('post_paging_use_image')); ?>"><?php _e('Use Image','paging') ?>
									</label></div>
									<div><label for="post_paging_prev_post_image">
										<?php _e('Previous post image (full URL):','paging') ?> <input type="text" size="60" name="post_paging_prev_post_image" id="post_paging_prev_post_image" value="<?php echo get_option('post_paging_prev_post_image','') ?>">
									</label></div>
									<div><label for="post_paging_next_post_image">
										<?php _e('Next post image (full URL):','paging') ?> <input type="text" size="60" name="post_paging_next_post_image" id="post_paging_next_post_image" value="<?php echo get_option('post_paging_next_post_image','') ?>">
									</label></div>
									<div><label for="post_paging_images_width">
										<?php _e('Images width (numbers only):','paging') ?> <input type="text" size="5" name="post_paging_images_width" id="post_paging_images_width" value="<?php echo get_option('post_paging_images_width','') ?>">
									</label></div>
								</td>
						</tr>
					</tbody>
				</table>
				<br>

	<p class="submit"><input type="submit" name="submit" class="button-primary" value="<?php _e('Save Changes','paging'); ?>"></p>
    </form>
	<i>
	<?php _e('A plugin by ','paging'); ?><a href="http://wp-learning.net"><em>Tomek</em></a> <a href="https://www.paypal.com/cgi-bin/webscr?cmd=_donations&business=tomek.maestro@gmail.com&charset=utf-8&&currency_code=EUR&amount=5.00&item_name=Segítsd fenntartani az oldalt!&cancel_return=http://wp-learing.net&return=http://wp-learing.net"><?php _e('Donation','paging'); ?></a>
	</i>
<?php
}

function paging_submenu() {
  add_submenu_page('options-general.php','Post Paging','Post Paging','administrator','paging','paging_settings');
}

function paging_init() {
    register_setting('paging_settings_page','post_paging_position');
    register_setting('paging_settings_page','post_paging_background_color');
    register_setting('paging_settings_page','post_paging_background_color_transparent');
    register_setting('paging_settings_page','post_paging_text_color');
    register_setting('paging_settings_page','post_paging_hover_color');
	register_setting('paging_settings_page','post_paging_font_family');
	register_setting('paging_settings_page','post_paging_font_size');
    register_setting('paging_settings_page','post_paging_prev_post_text');
    register_setting('paging_settings_page','post_paging_next_post_text');
    register_setting('paging_settings_page','post_paging_text_decoration');
    register_setting('paging_settings_page','post_paging_show_title');
    register_setting('paging_settings_page','post_paging_use_image');
    register_setting('paging_settings_page','post_paging_prev_post_image');
    register_setting('paging_settings_page','post_paging_next_post_image');
    register_setting('paging_settings_page','post_paging_images_width');
}

function uninstall_paging() {
  delete_option('post_paging_position');
  delete_option('post_paging_background_color');
  delete_option('post_paging_background_color_transparent');
  delete_option('post_paging_text_color');
  delete_option('post_paging_hover_color');
  delete_option('post_paging_font_family');
  delete_option('post_paging_font_size');
  delete_option('post_paging_prev_post_text');
  delete_option('post_paging_next_post_text');
  delete_option('post_paging_text_decoration');
  delete_option('post_paging_show_title');
  delete_option('post_paging_use_image');
  delete_option('post_paging_prev_post_image');
  delete_option('post_paging_next_post_image');
  delete_option('post_paging_images_width');
}

add_filter('the_content','paging'); 
add_action('admin_enqueue_scripts', 'paging_colorpicker');
add_action('admin_menu','paging_submenu');
add_action('admin_init','paging_init');
register_deactivation_hook( __FILE__, 'uninstall_paging' );
?>