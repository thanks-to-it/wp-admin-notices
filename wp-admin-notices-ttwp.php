<?php
/**
 * Plugin Name: WP Admin Notices TTWP - Tester
 * Description: Tests WP Admin Notices - TTWP
 * Version: 1.0.0
 * Author: Thanks to WP
 * Author URI: https://github.com/thankstowp
 * License: GNU General Public License v3.0
 * License URI: http://www.gnu.org/licenses/gpl-3.0.html
 * Text Domain: wp-admin-notices-ttwp
 * Domain Path: /languages
 */

if ( ! class_exists( 'WPANTTWP_Plugin' ) ) {

	/**
	 * Main WPANTTWP
	 *
	 * @class   WPANTTWP
	 * @version 1.0.0
	 * @since   1.0.0
	 */
	class WPANTTWP_Plugin {
		protected static $_instance = null;


		/**
		 * Main WPANTTWP_Plugin Instance
		 *
		 * Ensures only one instance of WPANTTWP_Plugin is loaded or can be loaded.
		 *
		 * @static
		 * @return WPANTTWP_Plugin - Main instance
		 */
		public static function instance() {
			if ( is_null( self::$_instance ) ) {
				self::$_instance = new self();
			}

			return self::$_instance;
		}

		/**
		 * Initializes the plugin
		 *
		 * @version 1.0.0
		 * @since   1.0.0
		 */
		public function init() {
			require_once "vendor/autoload.php";
			//$manager = new \ThanksToWP\WPAN\NoticesManager();

			add_action( 'wp_ajax_' . 'wpanttwp_dismiss_persist', array( 'Notices_Manager', 'ajax_dismiss' ) );
			add_action( 'activated_plugin', array( 'Notices_Manager', 'set_activated_plugin' ) );
			add_action( 'upgrader_process_complete', array( 'Notices_Manager', 'set_upgrader_process' ), 10, 2 );

			add_action( 'admin_notices', function () {
				$notices_manager = \ThanksToWP\WPAN\get_notices_manager();
				$notices_manager->create_notice( array(
					'id'         => 'my_notice3',
					'content'    => '<p>My Notice</p>',
					'display_on' => array(
						'screen_id' => array( 'plugins' ),
						/*'request'   => array(
							array( 'key' => 'show_notice', 'value' => '1' ),
							array( 'key' => 'show_notice', 'value' => 'true' )
						),*/
						//'activated_plugin' => array( 'alg-ajax-search/ajax-product-search-woocommerce.php' ),
						'updated_plugin' => array('akismet/akismet.php')
					)
				) );
			} );

			/*
			$manager->handle_notice( array(
				'id'                   => 'test',
				'type'                 => 'notice-error', // | 'notice-warning' | 'notice-success' | 'notice-info',
				'dismissible'          => true,
				'dismissal_expiration' => 1 * MONTH_IN_SECONDS,
				'content'              => '<p>content</p>',
			    'valid'                => true,
				'display_on'           => array(
					'screen_id'        => array( 'plugins' ),
					'activated_plugin' => array( 'plugin-a' ),
					'updated_plugin'   => array( 'plugin-b' ),
				)
			) );
			*/

		}

	}

}

if ( ! function_exists( 'WPANTTWP_plugin' ) ) {
	/**
	 * Returns the main instance of WPANTTWP_Plugin to prevent the need to use globals.
	 *
	 * @return  WPANTTWP_Plugin
	 * @version 1.0.0
	 * @since   1.0.0
	 */
	function WPANTTWP_plugin() {
		return WPANTTWP_Plugin::instance();
	}
}

$plugin = WPANTTWP_plugin();
$plugin->init();

