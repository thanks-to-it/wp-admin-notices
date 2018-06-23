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

			/*$notices_manager = \ThanksToWP\WPAN\get_notices_manager();
			$notices_manager->handle_ajax();*/


			add_action( 'wpanttwp_dispose_event', function () {
				$notices_manager = \ThanksToWP\WPAN\get_notices_manager();
				$notices_manager->discard_data();
			} );

			add_action( "wp_ajax_" . "wpanttwp_dismiss_persist", function () {
				$notices_manager = \ThanksToWP\WPAN\get_notices_manager();
				$notices_manager->handle_ajax_dismiss();
			} );

			add_action( 'activated_plugin', function ( $plugin ) {
				$notices_manager = \ThanksToWP\WPAN\get_notices_manager();
				$notices_manager->set_activated_plugin( $plugin );
			} );

			add_action( 'upgrader_process_complete', function ( $upgrader_object, $options ) {
				$notices_manager = \ThanksToWP\WPAN\get_notices_manager();
				$notices_manager->set_upgrader_process( $upgrader_object, $options );
				//error_log('---%---');
				//error_log(print_r($options,true));

				/*$current_plugin_path_name = plugin_basename( __FILE__ );

				if ($options['action'] == 'update' && $options['type'] == 'plugin' ){
					foreach($options['plugins'] as $each_plugin){
						if ($each_plugin==$current_plugin_path_name){
							set_transient( 'frou_activated_or_updated', true, 30 );
						}
					}
				}*/
			}, 10, 2 );

			add_action( 'admin_notices', function () {
				$notices_manager = \ThanksToWP\WPAN\get_notices_manager();
				$notices_manager->set_plugin_filepath( __FILE__ );
			} );

			add_action( 'admin_notices', function () {
				$notices_manager = \ThanksToWP\WPAN\get_notices_manager();
				$notices_manager->create_notice( array(
					'id'         => 'test',
					'content'    => '<p>First Notice</p>',
					'display_on' => array(
						'screen_id'      => array( 'plugins' ),
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

