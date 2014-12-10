<?php

class acf_field_ppqtranslate_textarea extends acf_field_textarea
{

	function __construct()
	{
		$this->name = 'ppqtranslate_textarea';
		$this->label = __("Text Area",'acf');
		$this->category = __("ppqtranslate",'acf');

		acf_field::__construct();
	}


	function create_field($field)
	{
		if (!acf_ppqtranslate_enabled()) {
			acf_field_textarea::create_field($field);
			return;
		}

		global $q_config;
		$languages = ppqtrans_getSortedLanguages(true);
		$values = ppqtrans_split($field['value'], $quicktags = true);

		echo '<div class="multi-language-field">';

		foreach ($languages as $language) {
			$class = ($language === end($languages)) ? 'wp-switch-editor current-language' : 'wp-switch-editor';
			echo '<a class="' . $class . '" data-language="' . $language . '">' . $q_config['language_name'][$language] . '</a>';
		}

		foreach ($languages as $language) {
			$class = ($language === end($languages)) ? $field['class'] . ' current-language' : $field['class'];
			echo '<textarea data-language="' . esc_attr($language) . '" id="' . esc_attr( $field['id'] ) . '" rows="4" class="' . esc_attr($class) . '" name="' . esc_attr($field['name'] . "[$language]") . '">' . esc_textarea($values[$language]) . '</textarea>';
		}

		echo '</div>';
	}


	function format_value($value, $post_id, $field)
	{
		return $value;
	}


	function format_value_for_api($value, $post_id, $field) {
		if (acf_ppqtranslate_enabled()) {
			$value = ppqtrans_useCurrentLanguageIfNotFoundUseDefaultLanguage($value);
		}

		return acf_field_textarea::format_value_for_api($value, $post_id, $field);
	}


	function update_value($value, $post_id, $field)
	{
		if (acf_ppqtranslate_enabled()) {
			$value = ppqtrans_join($value);
		}

		return $value;
	}

}


new acf_field_ppqtranslate_textarea;
