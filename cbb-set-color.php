<?php

/**
 * @package Colorize Mobile Browser Address bar
 */

    require_once './admin.php';

    if ( !current_user_can('edit_posts') )
        wp_die(__('Cheatin&#8217; uh?'));
?>
    <div class="wrap">
         <?php echo "<h1>".__('Colorize Browser Bar')."</h1>";?>
        <div>
            <div>
               <h2>Select & Apply Browser bar color</h2>
                <p class="about-description">Use color picker to select or paste the color that will be applied as background color on Mobile devices Browser top bar. After you save changes, open your website on any mobile device and check it out.</p>
               <br/>
               
            </div>
    </div>

    <form method="post" action="options.php">      
        <?php settings_fields( 'cbb_plugin_options' ); ?>
        <?php $options = get_option( 'cbb_options' );?>

        <ul>
            <li><label for="cbb_set_color"><?php echo __('Change Color'); ?>: </label>
                <input name="cbb_options[cbb_set_color]" id="cbb-set-color" type="text" value="<?php if ( isset( $options['cbb_set_color'] ) ) echo $options['cbb_set_color']; ?>" />
               </li>
             </ul>
            <?php submit_button();?>          
         </form>
    </div>