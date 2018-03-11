<?php
/** Adding TM Menu in admin panel. */
function my_plugin_menu() {	
	add_theme_page( __('Theme Settings','templatemela'), __('TM Theme Settings','templatemela'), 'manage_options', 'tm_theme_settings', 'templatemela_theme_settings_page' );		
	add_theme_page( __('Hook Manager','templatemela'), __('TM Hook Manager','templatemela'), 'manage_options', 'tm_hook_manage', 'templatemela_hook_manage_page');	
}
?>