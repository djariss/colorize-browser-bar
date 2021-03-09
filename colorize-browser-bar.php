<?php
/*
Plugin Name: Colorize Mobile Browser Bar
Plugin URI:  https://wordpress.org/plugins/colorize-browser-bar/
Description: Quickly set the Browser bar color on mobile devices to match your header color or any other color of your choice. It works on all major mobile browsers and supports Android, iOS and Windows devices.
Version:     1.0.2
Author:      Worda Themes
Author URI:  https://wordathemes.com/
License:     GPLv3
License URI: https://www.gnu.org/licenses/gpl-3.0.html
Text Domain: cbb_
*/

defined( 'ABSPATH' ) or die( 'No script kiddies please!' );

// Initiate the admin menu, options page and enquque the custom color picker setup

	add_action('admin_init', 'cbb_init' ); 
	add_action('admin_menu', 'cbb_add_options_page');
	add_action( 'admin_enqueue_scripts', 'cbb_admin_enqueue_scripts' );

    
    // Uses the Settings_API to register the plugin options.
    
    function cbb_init(){
        register_setting( 'cbb_plugin_options', 'cbb_options', 'cbb_validate_options' );
    }

    
    // Defines plugin options page parameters.
    
    function cbb_add_options_page() {
        add_options_page('Colorize Browser Bar', 'Colorize Browser Bar', 'manage_options', __FILE__, 'cbb_display_options');
    }

   
    // Displays Initialized
    
    function cbb_display_options() {
        include "cbb-set-color.php";
    }

    
    // Uses sanitize_text_field to strip html from input text.

    function cbb_validate_options($input) {
        // strip html from textboxes
        sanitize_text_field($input);
        return $input;
    }

    
    // Load default 'wp-color-picker' and append with the custom script

    function cbb_admin_enqueue_scripts($hook) {
      
        wp_enqueue_script( 'wp-color-picker' );
        // load the minified version of custom script
        wp_enqueue_script( 'cbb-picker-settings', plugins_url( 'assets/js/cbb.js', __FILE__ ), array( 'jquery', 'wp-color-picker' ), '1.1', true );
        wp_enqueue_style( 'wp-color-picker' );
    }


    // Insert the colors via meta data inside the pages head tag

    function cbb_add_meta_tags() {

        $cbb_color = get_option('cbb_options'); 

        // Chrome, Firefox OS, Opera and Vivaldi
        echo '<meta name="theme-color" content="' .  $cbb_color['cbb_set_color'] . '" />' . "\n";
        // Windows Phone **
        echo '<meta name="msapplication-navbutton-color" content="' .  $cbb_color['cbb_set_color']  . '" />' . "\n";
        // iOS Safari
        echo '<meta name="apple-mobile-web-app-capable" content="yes">' . "\n";
        echo '<meta name="apple-mobile-web-app-status-bar-style" content="' .  $cbb_color['cbb_set_color']  . '" />' . "\n";             
        }

    add_action( 'wp_head', 'cbb_add_meta_tags' , 2 );

?>