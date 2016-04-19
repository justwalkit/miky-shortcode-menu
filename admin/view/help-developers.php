<?php
/**
 * Help content for developers
 */
?>
<h1>Developer Filters Available</h1>

<h2>Add Text To Menu</h2>
<pre><code>
// if the shortcode tinymce plugin filters exist
include_once( ABSPATH . 'wp-admin/includes/plugin.php' );
if(is_plugin_active('coveloping-shortcode-menu/coveloping-shortcode-menu.php'))
{
    add_filter('coveloping_shortcode_button', 'add_facebook_shortcode', 10, 1);
}

function add_facebook_shortcode( $shortcode_tags )
{
    $shortcode_tags['facebook_like_box'] = 'Facebook Like Box';

    return $shortcode_tags;
}
</code></pre>

<h2>Add Fields To Form</h2>
<pre><code>
// if the shortcode tinymce plugin filters exist
include_once( ABSPATH . 'wp-admin/includes/plugin.php' );
if(is_plugin_active('coveloping-shortcode-menu/coveloping-shortcode-menu.php'))
{
    add_filter('coveloping_shortcode_form', 'add_facebook_form', 10, 1);
}

function add_facebook_form( $shortcode_form )
{
    $shortcode_form['facebook_like_box'][] = apply_filters('coveloping_shortcode_form_add_text', null, 'page_name', 'Page Name');
    $shortcode_form['facebook_like_box'][] = apply_filters('coveloping_shortcode_form_add_text', null, 'width', 'Width', 300);
    $shortcode_form['facebook_like_box'][] = apply_filters('coveloping_shortcode_form_add_checkbox', null, 'show_faces', 'Show Faces');
    $shortcode_form['facebook_like_box'][] = apply_filters('coveloping_shortcode_form_add_checkbox', null, 'show_stream', 'Show Stream');
    $shortcode_form['facebook_like_box'][] = apply_filters('coveloping_shortcode_form_add_checkbox', null, 'show_header', 'Show Header', true);
    $shortcode_form['facebook_like_box'][] = apply_filters('coveloping_shortcode_form_add_checkbox', null, 'show_border', 'Show Border');
    $shortcode_form['facebook_like_box'][] = apply_filters('coveloping_shortcode_form_add_select', null, 'color_scheme', 'Color Scheme', array('light' => 'Light', 'dark' => 'Dark'));

    return $shortcode_form;
}
</code></pre>

<h2>Add Checkbox To Form</h2>
<pre><code>
apply_filters('coveloping_shortcode_form_add_checkbox', $id, $title, $default_checked);
</code></pre>

<h2>Add Colour Picker To Form</h2>
<pre><code>
apply_filters('coveloping_shortcode_form_add_color', $id, $title, $value);
</code></pre>

<h2>Add Date Picker To Form</h2>
<pre><code>
apply_filters('coveloping_shortcode_form_add_date', $id, $title, $value);
</code></pre>

<h2>Add Divide To Form</h2>
<pre><code>
apply_filters('coveloping_shortcode_form_add_divide');
</code></pre>

<h2>Add Info To Form</h2>
<pre><code>
apply_filters('coveloping_shortcode_form_add_info', $content);
</code></pre>

<h2>Add Radio To Form</h2>
<pre><code>
apply_filters('coveloping_shortcode_form_add_radio', $id, $title, $options);
</code></pre>

<h2>Add Select Dropdown To Form</h2>
<pre><code>
apply_filters('coveloping_shortcode_form_add_select', $id, $title, $options);
</code></pre>

<h2>Add Textbox To Form</h2>
<pre><code>
apply_filters('coveloping_shortcode_form_add_text', $id, $title, $value);
</code></pre>

<h2>Add Textarea To Form</h2>
<pre><code>
apply_filters('coveloping_shortcode_form_add_textarea', $id, $title, $value);
</code></pre>