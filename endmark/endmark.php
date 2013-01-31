<?php
/*
Plugin Name: Endmark
Plugin URI: http://workwebplay.com/
Description: Adds an end-of-article symbol to the end of posts and pages.
Version: 1.4
Author: Colin Temple
Author URI: http://colintemple.com/
*/
/**
 * Endmark
 * by Colin Temple <http://colintemple.com/>, 2008 - 2013
 * License: GPL 3.0 <http://www.gnu.org/licenses/gpl.html>
 * 
 *  This file is part of The Tableau Machine.
 *
 *  Endmark is free software: you can redistribute it and/or modify
 *  it under the terms of the GNU General Public License as published by
 *  the Free Software Foundation, either version 3 of the License, or
 *  (at your option) any later version.
 *
 *  Endmark is distributed in the hope that it will be useful,
 *  but WITHOUT ANY WARRANTY; without even the implied warranty of
 *  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *  GNU General Public License for more details.
 *
 *  You should have received a copy of the GNU General Public License
 *  along with Endmark.  If not, see <http://www.gnu.org/licenses/>.
 * 
 */

// Create the options to use in this plugin, if they don't exist already
$exists = get_option("endmark_type");
if( false === $exists) add_option("endmark_type","symbol");
$exists = get_option("endmark_symbol");
if( false === $exists) add_option("endmark_symbol","#");
$exists = get_option("endmark_image");
if( false === $exists) add_option("endmark_image");
$exists = get_option("endmark_where");
if( false === $exists) add_option("endmark_where");
		
// Hook for the administration page
add_action('admin_menu', 'endmark_conf');

if( ! function_exists( 'endmark_conf_page' ) ) {
	//Generate the administration page itself
	function endmark_conf_page() { 

	    if( 'Y' == $_POST['gonow']) {
	    
    		update_option( "endmark_type", $_POST["endmark_type"] );
    		update_option( "endmark_symbol", $_POST["endmark_symbol"] );
    		update_option( "endmark_image", $_POST["endmark_image"] );
    		update_option( "endmark_where", $_POST["endmark_where"] );
    
    		// Put an options updated message on the screen
    		?>
    		<div class="updated"><p><strong><?php _e('Options saved.', 'endmark_trans_domain' ); ?></strong></p></div>
    		<?php

	    } 

	    // Now display the options editing screen
	    echo '<div class="wrap">';

	    // header
	    echo "<h2>" . __( 'Endmark Plugin Options', 'endmark_trans_domain' ) . "</h2>";

	    // options form
	    // Read in existing option value from database
	    $endmark_type_val = get_option("endmark_type");
	    $endmark_where_val = get_option("endmark_where");
	    $endmark_symbol_val = get_option("endmark_symbol");
	    $endmark_image_val = get_option("endmark_image");

	    ?>
		    
		<form method="post">
		<input type="hidden" name="gonow" value="Y" />	

		<p><?php _e("Endmark to display:", 'endmark_trans_domain' ); ?> 
		<select name="endmark_type">
			<option value="symbol" <?php if( $endmark_type_val == "symbol" ) echo "selected=\"selected\""; ?>><?php _e("Symbol",'endmark_trans_domain'); ?></option>
			<option value="image" <?php if( $endmark_type_val == "image" ) echo "selected=\"selected\""; ?>><?php _e("Image",'endmark_trans_domain'); ?></option>
		</select>
		</p>

		<p><?php _e("Where to display Endmark:", 'endmark_trans_domain' ); ?> 
		<select name="endmark_where">
			<option value="both" <?php if( $endmark_where_val == "both" ) echo "selected=\"selected\""; ?>><?php _e("Posts and Pages",'endmark_trans_domain'); ?></option>
			<option value="post" <?php if( $endmark_where_val == "post" ) echo "selected=\"selected\""; ?>><?php _e("Posts Only",'endmark_trans_domain'); ?></option>
			<option value="page" <?php if( $endmark_where_val == "page" ) echo "selected=\"selected\""; ?>><?php _e("Pages Only",'endmark_trans_domain'); ?></option>
		</select>
		</p>

		<p><?php _e("Endmark Symbol:", 'endmark_trans_domain' ); ?> 
		<input type="text" name="endmark_symbol" value="<?php echo $endmark_symbol_val; ?>" size="5" maxlength="3" />
		</p>

		<p><?php _e("Endmark Image (URL or path):", 'endmark_trans_domain' ); ?> 
		<input type="text" name="endmark_image" value="<?php echo $endmark_image_val; ?>" />
		</p><hr />

		<p class="submit">
		<input type="submit" name="Submit" value="<?php _e('Save Endmark', 'endmark_trans_domain' ) ?>" />
		</p>

		</form>
		</div>

		<?php
	}
}

if( ! function_exists( 'endmark_conf' ) ) {
	//Add the administration page for this plugin
	function endmark_conf() {
		add_theme_page('Endmark Options', 'Endmark', 8, __FILE__, endmark_conf_page);
	}
}

//The strripos function does not exist in PHP4  so we'll have to make one if it does not exist.
if( ! function_exists( 'strripos' ) ){
    function strripos( $haystack, $needle, $offset=0 ) {
        if( $offset < 0 ){
            $temp_cut = strrev(  substr( $haystack, 0, abs($offset) )  );
        }
        else{
            $temp_cut = strrev(  substr( $haystack, $offset )  );
        }
        $pos = strlen($haystack) - (strpos($temp_cut, strrev($needle)) + $offset + strlen($needle));
        if ($pos == strlen($haystack)) { $pos = 0; }
       
        if( strpos( $temp_cut, strrev( $needle ) ) === false ){
             return false;
        }
        else return $pos;
    }
}

if( ! function_exists('add_endmark') ){
	function add_endmark( $content ) {

		$endmarkWhere = get_option("endmark_where");

		if(is_404() || is_attachment()) {
			$showendmark = 0;
		} elseif($endmarkWhere=="both") {	
			$showendmark = 1;
		} elseif($endmarkWhere=="page" && is_page()) {	
			$showendmark = 1;
		} elseif($endmarkWhere=="post" && (is_single() || is_home() || is_category() || is_tag() || is_archive() || is_search())) {	
			$showendmark = 1;
		} else {
			$showendmark = 0;
		}

		if( $showendmark ) {
			// Get the type of endmark we need and create it
			$endmarkType = get_option("endmark_type");

			if($endmarkType == "image") {
				$endmarkImage = get_option("endmark_image");
				$endmark = '<img src="' . $endmarkImage . '" class="endmark" alt="" />';	
			} else {
				$endmark = '&nbsp;' . htmlentities( get_option( 'endmark_symbol' ) );	
			}

			// Add end mark
			$pos = strripos( $content, '</p>' );
			$content = substr_replace($content, $endmark, $pos, 0);

		}

		return $content;
	}
}

add_filter('the_content', 'add_endmark', 50);

?>