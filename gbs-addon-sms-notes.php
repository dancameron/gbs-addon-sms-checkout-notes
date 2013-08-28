<?php
/*
Plugin Name: Group Buying Addon - SMS Notes Notifier
Version: 1.2
Plugin URI: http://groupbuyingsite.com/marketplace
Description: Send a notification to a spcified group about all purchases.
Author: Sprout Venture
Author URI: http://sproutventure.com/wordpress
Plugin Author: Dan Cameron
Text Domain: group-buying
*/


define( 'GB_SMS_NOTES_PATH', WP_PLUGIN_DIR . '/' . basename( dirname( __FILE__ ) ) . '/' );


// Load after all other plugins since we need to be compatible with groupbuyingsite
add_action( 'plugins_loaded', 'gb_sms_notes_notifier' );
function gb_sms_notes_notifier() {
	$gbs_min_version = '4.4';
	if ( class_exists( 'Group_Buying_Controller' ) && version_compare( Group_Buying::GB_VERSION, $gbs_min_version, '>=' ) ) {
		require_once 'classes/SMS_Checkout_Notes_Addon.php';

		// Hook this plugin into the GBS add-ons controller
		add_filter( 'gb_addons', array( 'SMS_Checkout_Notes_Addon', 'gb_addon' ), 10, 1 );
	}
}