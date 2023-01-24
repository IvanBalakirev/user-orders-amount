<?php defined( 'ABSPATH' ) OR die( 'This script cannot be accessed directly.' );

/**
 * Plugin Name: WooCommerce user's orders amount
 * Plugin URI:  https://github.com/IvanBalakirev
 * Author:      Ivan
 * Author URI:  https://github.com/IvanBalakirev
 * License:     GPL-2.0+
 * License URI: http://www.gnu.org/licenses/gpl-2.0.txt
 * Description: This plugin adds a [user-orders-amount] shortcode to show the amount of completed WooCommerce orders for current user
 * Version: 1.0.0
 */

if ( ! function_exists( 'user_orders_amount_shortcode' ) ) {
	/**
	 * Shortcode supports prefix attribute, might be used for currency
	 */
	add_shortcode( 'user-orders-amount', 'user_orders_amount_shortcode' );
	function user_orders_amount_shortcode( $args ) {
		// Do nothing in backend
		if ( is_admin() AND ! wp_doing_ajax() ) {
			return FALSE;
		}
		// Make sure WooCommerce is active and user logged in
		if ( ! is_user_logged_in() OR ! class_exists( 'WooCommerce' ) ) {
			return FALSE;
		}
		$order_args = array(
			'customer_id' => get_current_user_id(),
			'status' => array( 'completed' ),
			'limit' => -1,
		);
		$orders = wc_get_orders( $order_args );
		$amount = 0;
		foreach ( $orders as $order ) {
			$amount += $order->get_total();
		}

		if ( ! empty( $args['prefix'] ) ) {
			$amount = esc_attr( $args['prefix'] ) . $amount;
		}

		return $amount;
	}
}
