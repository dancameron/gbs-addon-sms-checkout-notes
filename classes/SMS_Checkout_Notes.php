<?php

class SMS_Checkout_Notes extends Group_Buying_Controller {

	const NOTIFICATION_SENT_META_KEY = 'checkout_notes_notification';

	public static function init() {
		// Actions to send alerts
		add_action('completing_checkout', array(get_class(), 'maybe_send_alerts'), 10, 1);
	}

	public function maybe_send_alerts( Group_Buying_Checkouts $checkout ) {
		$purchase = Group_Buying_Purchase::get_instance( $checkout->cache['purchase_id'] );
		$products = $purchase ? $purchase->get_products() : array();
		$checkout_note = $checkout->cache['gb_notes'];

		// Loop through all deals
		foreach ( $products as $product => $item ) {
			$deal = Group_Buying_Deal::get_instance( $item['deal_id'] );
			$numbers = SMS_MetaBox::get_deals_numbers_array( $item['deal_id'] );
			// $merchant = $deal->get_merchant();
			$merchant_id = $deal->get_merchant_id();
			$merchant_name = get_the_title( $merchant_id );
			$item_names = Group_Buying_Attributes::get_attribute_name_by_purchase( $item['deal_id'], $purchase->get_id() );
			if ( $numbers ) {
				foreach ( $numbers as $number ) {
					// Run the data through notifications to get the message.
					$data = array(
						'user_id' => get_current_user_id(),
						'purchase' => $purchase,
						'deal' => $deal,
						'checkout_note' => $checkout_note,
						'merchant_name' => $merchant_name,
						'item_name' => $item_names['title']
					);
					$message = SMS_Notifications::get_message( $data );

					// And send
					$sms = GB_Twilio::send_sms( $number, $message );

					// Log that the message was sent
					add_post_meta( $account_id, self::NOTIFICATION_SENT_META_KEY, $voucher_id );
				}
			}
		}
	}
}