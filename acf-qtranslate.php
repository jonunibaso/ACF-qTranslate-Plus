<?php
/*
Plugin Name: Advanced Custom Fields: ppqtranslate
Plugin URI: http://github.com/funkjedi/acf-ppqtranslate
Description: Provides multilingual versions of the text, text area, and wysiwyg fields.
Version: 1.4
Author: funkjedi
Author URI: http://funkjedi.com
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html
*/

// http://support.advancedcustomfields.com/discussion/1181/prease-check-wp3-3-ppqtranslate-advance-custom-field/p1

require_once dirname(__FILE__) . '/v4/init.php';
require_once dirname(__FILE__) . '/v5/init.php';


function acf_ppqtranslate_enabled() {
	return function_exists('ppqtrans_useCurrentLanguageIfNotFoundUseDefaultLanguage');
}

function acf_ppqtranslate_acf_major_version() {
	if (function_exists('acf')) {
		$acf = acf();
		return (int) $acf->settings['version'][0];
	}
	return '';
}



// ppqtranslate Monkey Patches

add_action('plugins_loaded', 'acf_ppqtranslate_monkey_patch', 3);
function acf_ppqtranslate_monkey_patch() {
	global $q_config;

	// http://www.qianqin.de/ppqtranslate/forum/viewtopic.php?f=3&t=3497
	if (strpos($q_config['js']['ppqtrans_switch'], 'originalSwitchEditors') === false) {
		$q_config['js']['ppqtrans_switch'] = "originalSwitchEditors = jQuery.extend(true, {}, switchEditors);\n" . $q_config['js']['ppqtrans_switch'];
		$q_config['js']['ppqtrans_switch'] = preg_replace("/(var vta = document\.getElementById\('ppqtrans_textarea_' \+ id\);)/", "\$1\nif(!vta)return originalSwitchEditors.go(id, lang);", $q_config['js']['ppqtrans_switch']);
	}

	// https://github.com/funkjedi/acf-ppqtranslate/issues/2#issuecomment-37612918
	if (strpos($q_config['js']['ppqtrans_hook_on_tinyMCE'], 'ed.editorId.match(/^ppqtrans_/)') === false) {
		$q_config['js']['ppqtrans_hook_on_tinyMCE'] = preg_replace("/(ppqtrans_save\(switchEditors\.pre_wpautop\(o\.content\)\);)/", "if (ed.editorId.match(/^ppqtrans_/)) \$1", $q_config['js']['ppqtrans_hook_on_tinyMCE']);
	}
}
