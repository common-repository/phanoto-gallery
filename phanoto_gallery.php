<?php
/*
Plugin Name: Phanoto Gallery 
Plugin URI: http://www.phanoto.com/ws_gallery_generator.php
Description: Displays photos taken from sports fans at sporting events.
Version: 1.0
Author: The Ambitious, Inc.
Author URI: http://www.theambitious.com
License: GPL2
*/
?>
<?php
/*  Copyright 2010  The Ambitious, Inc. (email : support@phanoto.com)

    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License, version 2, as 
    published by the Free Software Foundation.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/
?>
<?php

register_activation_hook(__FILE__,'phanoto_gallery_install');
register_deactivation_hook(__FILE__,'phanoto_gallery_remove');

function phanoto_gallery_install(){
	add_option("category_id", 0);
	add_option( "heading", "Phanoto Gallery");
	add_option("photo_count",4);
}

function phanoto_gallery_remove(){
	delete_option("category_id");
	delete_option("heading");
	delete_option("photo_count");
}

if(is_admin()){
	add_action('admin_menu','phanoto_gallery_admin_menu');
	function phanoto_gallery_admin_menu(){
		add_options_page('setting','Phanoto Gallery','administrator','phanoto-gallery','phanoto_gallery_plugin_page');
	}
}


function phanoto_gallery_sidebar_install(){
	register_sidebar_widget(__('Phanoto Gallery'), 'widget_phanoto');
}
add_action('plugins_loaded','phanoto_gallery_sidebar_install');


function widget_phanoto(){
	echo '<h3 class="widget-title">' . get_option('heading') . '</h3>';
	if(get_option('category_id')!=0){
		$addon_url = '&category_id=' . get_option('category_id');
	}
	else {
		$addon_url = '';
	}
	if(get_option('photo_count') == 2){
		$addon_url .= '&limit=2&rows=1';
	}
	elseif(get_option('photo_count') == 6){
		$addon_url .= '&limit=6&rows=3';
	}
	elseif(get_option('photo_count') == 8){
		$addon_url .= '&limit=8&rows=4';
	}
	else {
		$addon_url .= '&limit=4&rows=2';
	}
	echo '<script type="text/javascript" src="http://code.jquery.com/jquery-1.4.3.min.js"></script>';
	echo '<script type="text/javascript" src="http://phanoto.com/web_services/photo_widget.js?filter=1' . $addon_url . '"></script>';
}

?>
<?php
function phanoto_gallery_plugin_page(){
?>
<h2>Phanoto Gallery Settings</h2>
<form method="POST" action="options.php">
<?php wp_nonce_field('update-options'); ?>
	<table>
		<tr>
			<td>
				Display heading
			</td>
			<td>
				<input type="text" name="heading" id="heading"  value="<?php echo get_option('heading');?>">
			</td>	
		</tr>
		<tr>
			<td>
				Display Photos from
			</td>
			<td>
				<select name="category_id">
					<option value="0" <?php if(get_option('category_id') == 0){ echo 'SELECTED';  } ?>>All Leagues</option>
					<option value="1" <?php if(get_option('category_id') == 1){ echo 'SELECTED';  } ?>>MLB</option>
					<option value="2" <?php if(get_option('category_id') == 2){ echo 'SELECTED';  } ?>>NBA</option>
					<option value="3" <?php if(get_option('category_id') == 3){ echo 'SELECTED';  } ?>>NFL</option>
					<option value="4" <?php if(get_option('category_id') == 4){ echo 'SELECTED';  } ?>>NHL</option>
					<option value="5" <?php if(get_option('category_id') == 5){ echo 'SELECTED';  } ?>>NCAA Football</option>
					<option value="6" <?php if(get_option('category_id') == 6){ echo 'SELECTED';  } ?>>NCAA Hockey</option>
				</select>
			</td>
		</tr>
		<tr>
			<td>
				Number of Photos
			</td>
			<td>
				<select name="photo_count">
					<option <?php if(get_option('photo_count') == 2){ echo 'SELECTED';  } ?>>2</option>
					<option <?php if(get_option('photo_count') == 4){ echo 'SELECTED';  } ?>>4</option>
					<option <?php if(get_option('photo_count') == 6){ echo 'SELECTED';  } ?>>6</option>
					<option <?php if(get_option('photo_count') == 8){ echo 'SELECTED';  } ?>>8</option>
				</select>
			</td>
		</tr>
		<tr>
			<td>
			</td>
			<td>
				<input type="hidden" name="action" value="update" />
				<input type="hidden" name="page_options" value="category_id,heading,photo_count" />
				<input type="submit" value="<?php _e('Save Changes') ?>" />
			</td>
		</tr>
	</table>
</form>
<?php
}
?>
