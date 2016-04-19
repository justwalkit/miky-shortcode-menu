<?php
/*
Plugin Name: Shortcode Menu
Description: Add a dropdown of your available shortcodes to your post or page editor
Plugin URI: http://coveloping.com
Author: Coveloping
Author URI: http://coveloping.com
Version: 1.2.1
License: GPL2
*/

$button_shortcodes = array();

// Update script
require_once plugin_dir_path( __FILE__ ) . 'update/plugin-update-checker.php';
$pluginChecker = new PluginUpdateChecker( 'http://coveloping.com/product-updates/coveloping-shortcode-menu.json', __FILE__, 'coveloping-shortcode-menu');

if(is_admin())
{
    //require_once plugin_dir_path( __FILE__ ) . 'includes/coveloping-shortcode-admin-default-shortcodes.php';
    //new Coveloping_Shortcode_Admin_Default_Shortcodes();

    // Admin shortcode button tinymce
    require_once plugin_dir_path(__FILE__) . 'admin/coveloping-shortcode-admin-shortcode-tinymce.php';
    new Coveloping_Shortcode_Admin_Shortcode_Tinymce();

    // Admin shortcode form
    require_once plugin_dir_path(__FILE__) . 'admin/coveloping-shortcode-admin-shortcode-form.php';
    new Coveloping_Shortcode_Admin_Shortcode_Form();

    // Admin shortcode settings page
    //require_once plugin_dir_path( __FILE__ ) . 'admin/coveloping-shortcode-admin-shortcode-settings.php';
    //new Coveloping_Shortcode_Admin_Shortcode_Settings();

    // Admin shortcode ajax
    require_once plugin_dir_path( __FILE__ ) . 'admin/coveloping-shortcode-admin-shortcode-ajax.php';
    new Coveloping_Shortcode_Admin_Shortcode_Ajax();

    // Add Test shortcode
    //require_once plugin_dir_path(__FILE__) . 'tests/coveloping-shortcode-test-shortcode-form.php';
    //new Coveloping_Shortcode_Test_Shortcode_Form();
}