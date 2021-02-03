<?php
/**
 * Plugin Name:       GrossNet
 * Plugin URI:        https://codedaokysu.com
 * Description:       Công cụ tính lương Gross sang Net / Net sang Gross chuẩn 2021
 * Version:           1.0.0
 * Author:            Quang Hoang
 * Author URI:        https://codedaokysu.com
 * Text Domain:       gross-net
 */
require_once __DIR__ . '/includes/grossnet-functions.php';
require_once __DIR__ . '/includes/grossnet-ajax.php';

if ( !function_exists( 'grossnet_add_plugin_page_settings_link' ) ) {
	add_filter('plugin_action_links_'.plugin_basename(__FILE__), 'grossnet_add_plugin_page_settings_link');
	function grossnet_add_plugin_page_settings_link( $links ) {
		$links[] = '<a href="' .
			admin_url( 'options-general.php?page=grossnet-settings' ) .
			'">' . __('Settings') . '</a>';
		return $links;
	}
}