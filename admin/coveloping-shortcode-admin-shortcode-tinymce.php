<?php

class Coveloping_Shortcode_Admin_Shortcode_Tinymce
{
    /**
     * @var string
     */
    private $optionName = 'coveloping-shortcode-fields';

    /**
     * Create javascript to use to add the shortcode button
     * Override the developer shortcodes with ones setup on the settings page
     */
    public function __construct()
    {
        add_action('admin_init', array($this, 'shortcode_button'));

        add_action('admin_footer', array($this, 'get_shortcodes'));
    }

    /**
     * Create a shortcode button for tinymce
     *
     * @return void
     */
    public function shortcode_button()
    {
        if( current_user_can('edit_posts') &&  current_user_can('edit_pages') )
        {
            add_filter( 'mce_external_plugins', array($this, 'add_buttons' ));
            add_filter( 'mce_buttons', array($this, 'register_buttons' ));
        }
    }

    /**
     * Add new Javascript to the plugin script array
     *
     * @param  Array $plugin_array - Array of scripts
     *
     * @return Array
     */
    public function add_buttons( $plugin_array )
    {
        $plugin_array['coveshortcodes'] = plugin_dir_url( dirname(__FILE__) ) . 'js/shortcode-tinymce-button.js';

        return $plugin_array;
    }

    /**
     * Add new button to TinyMce
     *
     * @param  Array $buttons - Array of buttons
     *
     * @return Array
     */
    public function register_buttons( $buttons )
    {
        array_push( $buttons, 'separator', 'coveshortcodes' );
        return $buttons;
    }

    /**
     * Add shortcode JS to the page
     *
     * @return string
     */
    public function get_shortcodes()
    {
        echo '<script type="text/javascript">var shortcodes_button = new Object();';

        $options = $this->get_shortcode_options();

        ksort($options, SORT_STRING);

        if(!empty($options))
        {
            foreach($options as $tag => $title)
            {
                echo "shortcodes_button['{$tag}'] = '{$title}';";
            }
        }

        echo '</script>';
    }

    /**
     * Get the shortcode tag options
     *
     * @return array
     */
    private function get_shortcode_options()
    {
        $finalShortcodeTags = array();

        $shortcode_button_tags = array();

        $shortcode_button_tags = apply_filters('coveloping_shortcode_button', $shortcode_button_tags);

        if(!empty($shortcode_button_tags))
        {
            foreach($shortcode_button_tags as $tag => $title)
            {
                $finalShortcodeTags[$tag] = $title;
            }
        }
        
        $options = get_option($this->optionName);

        if(!empty($options))
        {
            foreach($options as $tag => $option)
            {
                if(!empty($option['included-menu']) && $option['included-menu'] == 1)
                {
                    $title = $option['menu-title'];
                    $finalShortcodeTags[$tag] = $title;
                }
                else if(isset($finalShortcodeTags[$tag]) && $option['included-menu'] == 0)
                {
                    unset($finalShortcodeTags[$tag]);
                }
            }
        }

        return $finalShortcodeTags;
    }
}