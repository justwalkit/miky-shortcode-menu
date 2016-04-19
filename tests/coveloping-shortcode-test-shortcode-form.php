<?php
/**
 *
 * @package default
 * @author
 **/
class Coveloping_Shortcode_Test_Shortcode_Form
{
	/**
	 * Display a grid
	 */
	public function __construct()
	{
		add_shortcode( 'coveloping_test_shortcode', array( $this, 'display_grid' ) );

		add_filter('coveloping_shortcode_button', array($this, 'add_shortcode'), 10, 1);
		add_filter('coveloping_shortcode_form', array($this, 'add_shortcode_form'), 10, 1);
	}

	/**
	 * Add the shortcode dropdown
	 *
	 * @param $shortcode_tags
	 *
	 * @return mixed
	 */
	public function add_shortcode( $shortcode_tags )
	{
		$shortcode_tags['coveloping_test_shortcode'] = 'Coveloping Test Shortcode';

		return $shortcode_tags;
	}

	/**
	 * Add the form elements for the shortcode
	 *
	 * @param $shortcode_form
	 *
	 * @internal param $shortcode_tags
	 *
	 * @return mixed
	 */
	public function add_shortcode_form($shortcode_form)
	{
		// checkbox
		$shortcode_form['coveloping_test_shortcode'][] = apply_filters('coveloping_shortcode_form_add_checkbox', 'checkbox', 'Checkbox unchecked');
		$shortcode_form['coveloping_test_shortcode'][] = apply_filters('coveloping_shortcode_form_add_checkbox', 'checkbox_checked', 'Checkbox checked', true);

		// colour picker
		$shortcode_form['coveloping_test_shortcode'][] = apply_filters('coveloping_shortcode_form_add_color', 'color', 'Colour');

		// date
		$shortcode_form['coveloping_test_shortcode'][] = apply_filters('coveloping_shortcode_form_add_date', 'date', 'Date');

		// divide
		$shortcode_form['coveloping_test_shortcode'][] = apply_filters('coveloping_shortcode_form_add_divide', 'divide', 'Divide');

		// info
		$shortcode_form['coveloping_test_shortcode'][] = apply_filters('coveloping_shortcode_form_add_info', 'Information');

		// radio
		$shortcode_form['coveloping_test_shortcode'][] = apply_filters('coveloping_shortcode_form_add_radio', 'radio', 'Radio', array('type' => 'TYPE', 'stuff' => 'Stuff'));

		// select
		$shortcode_form['coveloping_test_shortcode'][] = apply_filters('coveloping_shortcode_form_add_select', 'select', 'Select', array('type' => 'TYPE', 'stuff' => 'Stuff'));

		// text
		$shortcode_form['coveloping_test_shortcode'][] = apply_filters('coveloping_shortcode_form_add_text', 'text', 'Text');

		// textarea
		$shortcode_form['coveloping_test_shortcode'][] = apply_filters('coveloping_shortcode_form_add_textarea', 'textarea', 'Textarea');

		return $shortcode_form;
	}

	/**
	 *
	 * @param array $atts
	 * @param string $content
	 *
	 * @return string
	 */
	public function display_shortcode( $atts, $content )
	{

	}
}