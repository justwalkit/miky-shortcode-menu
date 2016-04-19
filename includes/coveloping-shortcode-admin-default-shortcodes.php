<?php
/**
 * Activator script for the shortcode menu plugin
 */
class Coveloping_Shortcode_Admin_Default_Shortcodes
{
    public function __construct()
    {
        add_filter('coveloping_shortcode_button', array($this, 'add_shortcodes'), 10, 1);
        add_filter('coveloping_shortcode_form', array($this, 'add_shortcodes_form_fields'), 10, 1);
    }

    public function add_shortcodes( $shortcode_tags )
    {
        // Default WordPress Shortcode
        $shortcode_tags['audio'] = 'Audio';
        $shortcode_tags['caption'] = 'Caption';
        $shortcode_tags['gallery'] = 'Gallery';
        $shortcode_tags['video'] = 'Video';
        $shortcode_tags['embed'] = 'Embed';
        $shortcode_tags['wp_caption'] = 'wp_caption';
        $shortcode_tags['playlist'] = 'Playlist';

        // Add Advanced Custom Fields Shortcode
        if(is_plugin_active('advanced-custom-fields/acf.php'))
        {
            $shortcode_tags['acf'] = 'Advanced Custom Fields';
        }

        // Easy Digital Downloads
        if(is_plugin_active('easy-digital-downloads/easy-digital-downloads.php'))
        {
            $shortcode_tags['purchase_link'] = 'EDD Purchase Link';
            $shortcode_tags['download_history'] = 'EDD Download History';
            $shortcode_tags['purchase_history'] = 'EDD Purchase History';
            $shortcode_tags['download_checkout'] = 'EDD Download Checkout';
            $shortcode_tags['download_cart'] = 'EDD Download Cart';
            $shortcode_tags['edd_login'] = 'EDD Login';
            $shortcode_tags['edd_register'] = 'EDD Register';
            $shortcode_tags['download_discounts'] = 'EDD Download Discounts';
            $shortcode_tags['purchase_collection'] = 'EDD Purchase Collection';
            $shortcode_tags['downloads'] = 'EDD Downloads';
            $shortcode_tags['edd_price'] = 'EDD Price';
            $shortcode_tags['edd_receipt'] = 'EDD Receipt';
            $shortcode_tags['edd_profile_editor'] = 'EDD Profile Editor';
        }

        if(is_plugin_active('wordpress-seo/wp-seo.php'))
        {
            $shortcode_tags['wpseo_sitemap'] = 'WPSEO Sitemap';
            $shortcode_tags['wpseo_breadcrumb'] = 'WPSEO Breadcrumb';
        }

        return $shortcode_tags;
    }

    /**
     * Add default shortcode fields
     *
     * @param $shortcode_form
     *
     * @return mixed
     */
    public function add_shortcodes_form_fields( $shortcode_form )
    {
        $shortcode_form = $this->get_default_wordpress_shortcodes( $shortcode_form );

        if(is_plugin_active('advanced-custom-fields/acf.php')) {
            $shortcode_form = $this->get_acf_shortcodes($shortcode_form);
        }

        if(is_plugin_active('easy-digital-downloads/easy-digital-downloads.php')) {
            $shortcode_form = $this->get_edd_shortcodes($shortcode_form);
        }

        return $shortcode_form;
    }

    /**
     * Get the default WordPress shortcodes
     *
     * @param $shortcode_form
     *
     * @return mixed
     */
    private function get_default_wordpress_shortcodes( $shortcode_form )
    {
        $shortcode_form['audio'][] = apply_filters('coveloping_shortcode_form_add_text', 'src', 'Audio URL');
        $shortcode_form['audio'][] = apply_filters('coveloping_shortcode_form_add_select', 'loop', 'Loop', array('off' => 'Off', 'on' => 'On'));
        $shortcode_form['audio'][] = apply_filters('coveloping_shortcode_form_add_select', 'autoplay', 'Autoplay', array('off' => 'Off', 'on' => 'On'));
        $shortcode_form['audio'][] = apply_filters('coveloping_shortcode_form_add_select', 'preload', 'Preload', array('none' => 'None', 'auto' => 'Auto', 'metadata' => 'Metadata'));

        $shortcode_form['caption'][] = apply_filters('coveloping_shortcode_form_add_text', 'id', 'HTML ID');
        $shortcode_form['caption'][] = apply_filters('coveloping_shortcode_form_add_text', 'class', 'CSS Class');
        $shortcode_form['caption'][] = apply_filters('coveloping_shortcode_form_add_select', 'align', 'Align', array('alignnone' => 'None', 'aligncenter' => 'Center', 'alignright' => 'Right', 'alignleft' => 'Left'));
        $shortcode_form['caption'][] = apply_filters('coveloping_shortcode_form_add_text', 'width', 'Width');

        $shortcode_form['gallery'][] = apply_filters('coveloping_shortcode_form_add_select', 'orderby', 'Order By', array(
            'menu_order' => 'Menu Order',
            'title' => 'Image Title',
            'post_date' => 'Date',
            'rand' => 'Random',
            'ID' => 'ID',
        ));
        $shortcode_form['gallery'][] = apply_filters('coveloping_shortcode_form_add_select', 'order', 'Order', array(
            'DESC' => 'DESC',
            'ASC' => 'ASC'
        ));
        $shortcode_form['gallery'][] = apply_filters('coveloping_shortcode_form_add_text', 'columns', 'Columns');
        $shortcode_form['gallery'][] = apply_filters('coveloping_shortcode_form_add_text', 'id', 'Id');
        $shortcode_form['gallery'][] = apply_filters('coveloping_shortcode_form_add_text', 'size', 'Size');
        $shortcode_form['gallery'][] = apply_filters('coveloping_shortcode_form_add_text', 'itemtag', 'HTML Tag', 'dl');
        $shortcode_form['gallery'][] = apply_filters('coveloping_shortcode_form_add_text', 'icontag', 'HTML Tag For Thumbnail', 'dt');
        $shortcode_form['gallery'][] = apply_filters('coveloping_shortcode_form_add_text', 'captiontag', 'HTML Tag For Caption', 'dd');
        $shortcode_form['gallery'][] = apply_filters('coveloping_shortcode_form_add_select', 'link', 'Link', array('file' => 'Link To File', 'none' => 'none'));
        $shortcode_form['gallery'][] = apply_filters('coveloping_shortcode_form_add_text', 'include', 'Include Images');
        $shortcode_form['gallery'][] = apply_filters('coveloping_shortcode_form_add_text', 'exclude', 'Exclude Images');

        $shortcode_form['video'][] = apply_filters('coveloping_shortcode_form_add_text', 'src', 'Video URL');
        $shortcode_form['video'][] = apply_filters('coveloping_shortcode_form_add_text', 'poster', 'Thumbnail Image');
        $shortcode_form['video'][] = apply_filters('coveloping_shortcode_form_add_select', 'loop', 'Loop', array('off' => 'Off', 'on' => 'On'));
        $shortcode_form['video'][] = apply_filters('coveloping_shortcode_form_add_select', 'autoplay', 'Autoplay', array('off' => 'Off', 'on' => 'On'));
        $shortcode_form['video'][] = apply_filters('coveloping_shortcode_form_add_select', 'preload', 'Preload', array('none' => 'None', 'auto' => 'Auto', 'metadata' => 'Metadata'));
        $shortcode_form['video'][] = apply_filters('coveloping_shortcode_form_add_text', 'height', 'Height');
        $shortcode_form['video'][] = apply_filters('coveloping_shortcode_form_add_text', 'width', 'Width');

        return $shortcode_form;
    }

    /**
     * Get ACF shortcodes
     */
    private function get_acf_shortcodes( $shortcode_form )
    {
        $shortcode_form['acf'][] = apply_filters('coveloping_shortcode_form_add_text', 'field', 'Field');

        return $shortcode_form;
    }

    private function get_edd_shortcodes( $shortcode_form )
    {
        $shortcode_form['purchase_link'][] = apply_filters('coveloping_shortcode_form_add_text', 'id', 'ID');
        $shortcode_form['purchase_link'][] = apply_filters('coveloping_shortcode_form_add_checkbox', 'price', 'Show Price');
        $shortcode_form['purchase_link'][] = apply_filters('coveloping_shortcode_form_add_text', 'text', 'Text');
        $shortcode_form['purchase_link'][] = apply_filters('coveloping_shortcode_form_add_select', 'style', 'Style', array('button' => 'Button', 'text' => 'Text'));
        $shortcode_form['purchase_link'][] = apply_filters('coveloping_shortcode_form_add_select', 'color', 'Color', array('gray' => 'Gray', 'blue' => 'Blue', 'green' => 'Green', 'dark gray' => 'Dark Gray', 'yellow' => 'Yellow'));
        $shortcode_form['purchase_link'][] = apply_filters('coveloping_shortcode_form_add_text', 'class', 'Class');
        $shortcode_form['purchase_link'][] = apply_filters('coveloping_shortcode_form_add_checkbox', 'direct', 'Direct');

        $shortcode_form['edd_login'][] = apply_filters('coveloping_shortcode_form_add_text', 'redirect', 'Redirect URL');

        $shortcode_form['downloads'][] = apply_filters('coveloping_shortcode_form_add_text', 'category', 'Category');
        $shortcode_form['downloads'][] = apply_filters('coveloping_shortcode_form_add_text', 'exclude_category', 'Exclude Category');
        $shortcode_form['downloads'][] = apply_filters('coveloping_shortcode_form_add_text', 'tags', 'Tags');
        $shortcode_form['downloads'][] = apply_filters('coveloping_shortcode_form_add_text', 'exclude_tags', 'Exclude Tags');
        $shortcode_form['downloads'][] = apply_filters('coveloping_shortcode_form_add_text', 'relation', 'Relation');
        $shortcode_form['downloads'][] = apply_filters('coveloping_shortcode_form_add_text', 'number', 'Number');
        $shortcode_form['downloads'][] = apply_filters('coveloping_shortcode_form_add_text', 'price', 'Price');
        $shortcode_form['downloads'][] = apply_filters('coveloping_shortcode_form_add_text', 'excerpt', 'Excerpt');
        $shortcode_form['downloads'][] = apply_filters('coveloping_shortcode_form_add_text', 'full_content', 'Full Content');
        $shortcode_form['downloads'][] = apply_filters('coveloping_shortcode_form_add_text', 'buy_button', 'Buy Button');
        $shortcode_form['downloads'][] = apply_filters('coveloping_shortcode_form_add_text', 'columns', 'Columns');
        $shortcode_form['downloads'][] = apply_filters('coveloping_shortcode_form_add_text', 'thumbnails', 'Thumbnails');
        $shortcode_form['downloads'][] = apply_filters('coveloping_shortcode_form_add_select', 'orderby', 'Order By', array('price' => 'Price', 'id' => 'ID', 'random' => 'Random', 'post_date' => 'Post Date', 'title' => 'Title'));
        $shortcode_form['downloads'][] = apply_filters('coveloping_shortcode_form_add_select', 'order', 'Order', array('DESC' => 'DESC', 'ASC' => 'ASC'));
        $shortcode_form['downloads'][] = apply_filters('coveloping_shortcode_form_add_text', 'ids', 'IDs');

        return $shortcode_form;
    }
}