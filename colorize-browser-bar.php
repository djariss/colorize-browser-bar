<?php
/*
Plugin Name: Colorize Mobile Browser Bar
Plugin URI:  https://wordpress.org/plugins/colorize-browser-bar/
Description: Quickly set the Browser bar color on mobile devices to match your header color or any other color of your choice. It works on all major mobile browsers and supports Android, iOS and Windows devices.
Version:     1.2.0
Author:      Worda Themes
Author URI:  https://eletuts.com/
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

    // Defines Plugin Options Page 
    function cbb_add_options_page() {
        add_options_page('Colorize Browser Bar', 'Colorize Browser Bar', 'manage_options', __FILE__, 'cbb_display_options');
    }

    // Initiate Main Functionality
    function cbb_display_options() {
        include "cbb-set-color.php";
    }

    // Add Admin Widget
    function cbb_add_dashboard_widgets() {
    
    wp_add_dashboard_widget(
            'cbb_dashboard_widget',
            'Colorize Browser Bar',
            'cbb_dashboard_widget_function'
        );
}

    add_action( 'wp_dashboard_setup', 'cbb_add_dashboard_widgets' );

    function cbb_dashboard_widget_function() {

    $cbb_author_url = 'https://eletuts.com/';
    $cbb_banner_url = '//eletuts.com/static/logo-banner.png';
    $cbb_support_url = '//wordpress.org/support/plugin/colorize-mobile-browser-bar/';
    $cbb_review_url = '//wordpress.org/support/plugin/colorize-mobile-browser-bar/reviews/';
  
    echo "<a href=".$cbb_author_url." target='_blank'/><img style='max-width:100%; margin-top:5px;' src='".$cbb_banner_url."'></a>";
    echo "<p>Thank you for using our <strong>Colorize Browser Bar</strong> plugin.</br> If you need support, open a new thread <a href=".$cbb_support_url." target='_blank'/>right here</a>.</br> If you find this plugin useful, consider rating it with ★★★★★ <a href=".$cbb_review_url." target='_blank'/>here</a>. </p>";
    echo "<h3 style='padding-top:10px;'><strong>☆ LATEST FROM OUR BLOG ☆</strong></h3>";
   
    include_once(ABSPATH . WPINC . '/feed.php');

    $cbb_feed = array( 
                'https://eletuts.com/feed', 
                );
    
    // Loop Through Feeds
    foreach ( $cbb_feed as $feed) :
    
        // Get a SimplePie feed object
        $rss = fetch_feed( $feed );
        if (!is_wp_error( $rss ) ) : // Checks that the object is created correctly 
            // Define how many items to show
            $maxitems = $rss->get_item_quantity( 5 ); 
        
            // Build an array of all the items
            $rss_items = $rss->get_items( 0, $maxitems ); 
    
            // Get RSS title
            $rss_title = '<a href="'.$rss->get_permalink().'" target="_blank">'.strtoupper( $rss->get_title() ).'</a>'; 
        endif;
    
        // Display the container
        echo '<div class="rss-widget">';
        echo '<hr style="border: 0; background-color: #DFDFDF; height: 1px;">';
        
        // Starts items listing within <ul> tag
        echo '<ul>';
        
        // Check items
        if ( $maxitems == 0 ) {
            echo '<li>'.__( 'No item', 'cbb_').'.</li>';
        } else {
            // Loop through each feed item and display each item as a hyperlink.
            foreach ( $rss_items as $item ) :
                
                // Convert date and time to readable format
                $item_date = human_time_diff( $item->get_date('U'), current_time('timestamp')).' '.__( 'ago', 'cbb_' );
                
                // Start feed display
                echo '<li>';
                echo '<a href="'.esc_url( $item->get_permalink() ).'" title="'.$item_date.'">';
                // Get RSS item title
                echo esc_html( $item->get_title() );
                echo '</a>';
                // Get post date
                echo ' <span class="rss-date">'.$item_date.'</span><br />';
                // Get post content
                $content = $item->get_content();
                // Trim post content
                $content = wp_html_excerpt($content, 120) . ' [...]';
                // Display post content
                echo $content;
                echo '</li>';
            endforeach;
        }
        echo '</ul></div>';

    endforeach; 
}

    // Uses sanitize_text_field to strip HTML from input
    function cbb_validate_options($input) {

        sanitize_text_field($input);
        return $input;
    }

    // Load default 'wp-color-picker' and append with the custom script

    function cbb_admin_enqueue_scripts($hook) {
      
        wp_enqueue_script( 'wp-color-picker' );
        wp_enqueue_script( 'cbb-picker-settings', plugins_url( 'assets/js/cbb.js', __FILE__ ), array( 'jquery', 'wp-color-picker' ), '1.1', true );
        wp_enqueue_style( 'wp-color-picker' );
    }

    // Insert the colors via meta data inside the pages head tag
    function cbb_add_meta_tags() {

        $cbb_color = get_option('cbb_options'); 
        // Start meta tags
        echo '<!-- Colorize Browser Bar - set mobile browser bar color -->' . "\n";
        // Chrome, Firefox, Opera, Brave and Vivaldi
        echo '<meta name="theme-color" content="' .  $cbb_color['cbb_set_color'] . '" />' . "\n";
        // Windows Phone
        echo '<meta name="msapplication-navbutton-color" content="' .  $cbb_color['cbb_set_color']  . '" />' . "\n";
        // iOS Safari
        echo '<meta name="apple-mobile-web-app-capable" content="yes">' . "\n";
        echo '<meta name="apple-mobile-web-app-status-bar-style" content="' .  $cbb_color['cbb_set_color']  . '" />' . "\n";             
        }
    add_action( 'wp_head', 'cbb_add_meta_tags' , 2 );

?>