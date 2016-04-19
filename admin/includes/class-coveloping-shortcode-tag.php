<?php
/**
 * Coveloping Shortcode Tag
 *
 * To process the shortcode tag data
 */
class Coveloping_Shortcode_Tag
{
    private $optionName = false;

    public function __construct( $optionName )
    {
        $this->optionName = $optionName;
    }

    /**
     * Get the shortcode from the option
     *
     * @param $shortcode
     *
     * @return mixed
     */
    public function get_shortcode($shortcode)
    {
        $options = get_option($this->optionName);

        if(isset($options[$shortcode]))
        {
            return $options[$shortcode];
        }
    }

    /**
     * Update the shortcode menu with the title text
     *
     * @param $post
     * @return bool
     */
    public function update_shortcode_menu( $post )
    {
        $options = get_option($this->optionName);

        if(!empty($post['included-menu']))
        {
            $options[$post['shortcode']]['included-menu'] = 1;
        } else {
            $options[$post['shortcode']]['included-menu'] = 0;
        }

        if(!empty($post['menu-title']))
        {
            $options[$post['shortcode']]['menu-title'] = sanitize_text_field($post['menu-title']);
        }

        return update_option($this->optionName, $options);
    }

    /**
     * Delete the shortcode from the menu
     *
     * @param $post
     *
     * @return bool
     */
    public function delete_shortcode_menu( $post )
    {
        $options = get_option($this->optionName);

        if(!empty($post['shortcode']) && isset($options[$post['shortcode']]))
        {
            unset($options[$post['shortcode']]);
        }

        return update_option($this->optionName, $options);
    }

    /**
     * Update the shortcode settings from the developer settings
     *
     * @param $developerShortcodes
     */
    public function update_shortcode_settings_from_developer( $developerShortcodes )
    {
        if(!empty($developerShortcodes))
        {
            $options = get_option($this->optionName);

            foreach($developerShortcodes as $shortcode => $title)
            {
                if(!isset($options[$shortcode]))
                {
                    $options[$shortcode] = array(
                        'included-menu' => 1,
                        'menu-title' => $title
                    );
                }
            }

            update_option($this->optionName, $options);
        }
    }
}