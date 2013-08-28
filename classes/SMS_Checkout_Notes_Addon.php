<?php

/**
 * Load via GBS Add-On API
 */
class SMS_Checkout_Notes_Addon extends Group_Buying_Controller {
	const META_KEY = '_gbs_sms_checkout_notes';
	public static function init() {

		require_once('GB_Twilio.php');

		require_once('SMS_Notifications.php');
		SMS_Notifications::init();

		require_once('SMS_Checkout_Notes.php');
		SMS_Checkout_Notes::init();

		require_once('SMS_Options.php');
		require_once('SMS_MetaBox.php');

		if ( is_admin() ) {
			SMS_MetaBox::init();
			SMS_Options::init();
		}


	}

	public static function gb_addon( $addons ) {
		$addons['sms_notes_notifier'] = array(
			'label' => self::__( 'SMS Notes Notifier' ),
			'description' => self::__( 'Send a notification to a spcified group about all purchases.' ),
			'files' => array(),
			'callbacks' => array(
				array( __CLASS__, 'init' ),
			),
			'active' => TRUE,
		);
		return $addons;
	}

}