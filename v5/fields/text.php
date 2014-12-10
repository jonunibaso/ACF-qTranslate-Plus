<?php

class acf_field_ppqtranslate_text extends acf_field_text
{

	function __construct()
	{
		$this->name = 'ppqtranslate_text';
		$this->label = __("Text",'acf');
		$this->category = __("ppqtranslate",'acf');
		$this->defaults = array(
			'default_value'	=> '',
			'maxlength'		=> '',
			'placeholder'	=> '',
			'prepend'		=> '',
			'append'		=> '',
			'readonly'		=> 0,
			'disabled'		=> 0,
		);

		acf_field::__construct();
	}


	function render_field($field)
	{
		if (!acf_ppqtranslate_enabled()) {
			acf_field_text::render_field($field);
			return;
		}

		global $q_config;
		$languages = ppqtrans_getSortedLanguages(true);
		$values = ppqtrans_split($field['value'], $quicktags = true);


		// vars
		$o = array( 'type', 'id', 'class', 'name', 'value', 'placeholder' );
		$s = array( 'readonly', 'disabled' );
		$e = '';


		// maxlength
		if( $field['maxlength'] !== "" ) {

			$o[] = 'maxlength';

		}


		// populate atts
		$atts = array();
		foreach( $o as $k ) {

			$atts[ $k ] = $field[ $k ];

		}


		// special atts
		foreach( $s as $k ) {

			if( $field[ $k ] ) {

				$atts[ $k ] = $k;

			}

		}


		// render
		$e .= '<div class="acf-input-wrap multi-language-field">';

		foreach ($languages as $language) {

		}

		foreach ($languages as $language) {
			if ($language === end($languages)) {
				$atts['class'] .= ' current-language';
			}
			$class = ($language === end($languages)) ? 'wp-switch-editor current-language' : 'wp-switch-editor';
			$e .=  $language ;
			$atts['type'] = 'text';
			$atts['name'] = $field['name'] . "[$language]";
			$atts['value'] = $values[$language];
			$atts['data-language'] = $language;
			$e .= '<input ' . acf_esc_attr( $atts ) . ' />';
		}

		$e .= '</div>';


		// return
		echo $e;
	}


	function render_field_settings( $field ) {

		// default_value
		acf_render_field_setting( $field, array(
			'label'			=> __('Default Value','acf'),
			'instructions'	=> __('Appears when creating a new post','acf'),
			'type'			=> 'text',
			'name'			=> 'default_value',
		));

		// placeholder
		acf_render_field_setting( $field, array(
			'label'			=> __('Placeholder Text','acf'),
			'instructions'	=> __('Appears within the input','acf'),
			'type'			=> 'text',
			'name'			=> 'placeholder',
		));

		// maxlength
		acf_render_field_setting( $field, array(
			'label'			=> __('Character Limit','acf'),
			'instructions'	=> __('Leave blank for no limit','acf'),
			'type'			=> 'number',
			'name'			=> 'maxlength',
		));

	}


	function update_value($value, $post_id, $field)
	{
		if (acf_ppqtranslate_enabled()) {
			$value = ppqtrans_join($value);
		}

		return $value;
	}

}


new acf_field_ppqtranslate_text;
