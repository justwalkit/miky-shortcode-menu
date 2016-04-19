<?php
/**
 * Class is used to process the ajax functionality of the reorder of the shortcode fields
 */
class Coveloping_Shortcode_Admin_Shortcode_Ajax
{
    /**
     * Set the action of the ajax method
     */
    public function __construct()
    {
        add_action( 'wp_ajax_coveloping_shortcode_menu_ajax_action', array($this, 'reorder_shortcode_fields') );
    }

    /**
     * Reorder and update the fields option
     *
     * @return bool
     */
    public function reorder_shortcode_fields()
    {
        if(isset($_POST['fieldid']) && $_POST['shortcode'])
        {
            $shortcode = $_POST['shortcode'];

            $options = get_option('coveloping-shortcode-fields');
            
            if(isset($options[$shortcode]['fields']))
            {
                $order = 1;
                foreach($_POST['fieldid'] as $index => $fieldIds)
                {
                    $options[$shortcode]['fields'][$fieldIds]['order'] = $order;

                    $order++;
                }

                $options = $this->reorder_fields($shortcode, $options);

                return update_option('coveloping-shortcode-fields', $options);
            }
        }

        return false;
    }

    /**
     * @param $shortcode
     * @param $options
     * @return mixed
     */
    private function reorder_fields($shortcode, $options)
    {
        uasort($options[$shortcode]['fields'], array($this, 'sort_fields_function'));

        return $options;
    }

    /**
     * @param $a
     * @param $b
     *
     * @return array
     */
    private function sort_fields_function ($a, $b)
    {
        return $a['order'] - $b['order'];
    }
}