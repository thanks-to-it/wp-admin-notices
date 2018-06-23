<?php
/**
 * WP Admin Notices TTWP - Notices Maanger
 *
 * @version 1.1.0
 * @since   1.0.0
 * @author  Algoritmika Ltd.
 */

namespace ThanksToWP\WPAN;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
} // Exit if accessed directly

if ( ! class_exists( 'ThanksToWP\WPAN\NoticesManager' ) ) {

	class NoticesManager {

		//protected static $instance = null;
		public $activated_plugins = array();
		public $upgrader_process_object;
		public $upgrader_process_options;
		public $file_path;


		/*public function create_notice( $args ) {
			if ( ! is_admin() ) {
				return;
			}

			if ( empty( $args['id'] ) ) {
				return new \WP_Error( 'empty_id', __( 'Missing "id" parameter ', 'wp-admin-notices-ttwp' ) );
			} else {
				$id = $args['id'];

				// Store notice configuration
				update_option( "ttwpwpan_notice_{$id}", $args, false );

				// Store notice id
				$prev_ids   = get_option( "ttwpwpan_ids", array() );
				$count      = count( $prev_ids );
				$prev_ids[] = $id;
				$prev_ids   = array_unique( $prev_ids );
				if ( count( $prev_ids ) != $count ) {
					update_option( "ttwpwpan_ids", $prev_ids, false );
				}
			}
		}*/

		public function handle_ajax_dismiss() {
			$ajax = new Ajax();
			$ajax->dismiss();
		}

		public function create_notice( $args ) {
			$args       = wp_parse_args( $args, array(
				'id'                   => '',
				'type'                 => 'notice-info', // | 'notice-warning' | 'notice-success' | 'notice-error' | 'notice-info',
				'dismissible'          => true,
				'dismissal_expiration' => 1 * MONTH_IN_SECONDS,
				'content'              => '',
				'enabled'              => true,
				'keep_active_on'       => array( 'activated_plugin', 'updated_plugin' ),
				'display_on'           => array(
					'screen_id'        => array(),  // array( 'plugins' ),
					'activated_plugin' => array(), // array( 'plugin-a' ),
					'updated_plugin'   => array(), //array( 'plugin-b' ),
				)
			) );
			$expiration = $args['dismissal_expiration'];

			$notice = new Notice( $args['id'] );
			$notice->set_manager( $this );
			$notice->set_content( $args['content'] );
			$notice->display_on( $args['display_on'] );
			if ( empty( $expiration ) ) {
				$notice->dismissible_persistent = false;
			}
			$notice->keep_active_on = $args['keep_active_on'];
			$notice->enable();
		}

		/*public function get_useful_data_from_wp() {
			if ( current_filter() != 'activated_plugin' ) {
				add_action( 'activated_plugin', array( $this, 'get_activated_plugin' ) );
			} else {
				$this->get_activated_plugin();
			}
		}*/

		public function discard_data() {
			delete_option( 'ttwpwpan_activated_plugins' );
		}

		public function set_activated_plugin( $plugin ) {
			$activated_plugins       = $this->activated_plugins;
			$activated_plugins[]     = $plugin;
			$this->activated_plugins = $activated_plugins;
			update_option( 'ttwpwpan_activated_plugins', $this->activated_plugins );
		}

		public function set_upgrader_process( $upgrader_object, $options ) {
			$this->upgrader_process_object  = $upgrader_object;
			$this->upgrader_process_options = $options;
			update_option( 'ttwpwpan_upgrader_options', $options );
		}

		public function set_plugin_filepath( $path ) {
			$this->file_path = $path;
		}

		/**
		 * Constructor.
		 *
		 * @version 1.0.0
		 * @since   1.0.0
		 */
		/*protected function __construct() {
			add_action( 'admin_notices', array( $this, 'save_current_screen' ) );
			add_action( 'activated_plugin', array( $this, 'save_activated_plugin' ) );
		}

		public function save_activated_plugin( $plugin ) {
			//error_log(print_r($plugin,true));
		}

		public function save_current_screen() {
			$current_screen = get_current_screen();
			$screen_id      = $current_screen->id;
			update_option( "ttwpwpan_screen", $screen_id, false );
		}*/

		/**
		 * Call this method to get singleton
		 */
		public static function instance() {
			static $instance = false;
			if ( $instance === false ) {
				// Late static binding (PHP 5.3+)
				$instance = new static();
			}

			return $instance;
		}

	}
}