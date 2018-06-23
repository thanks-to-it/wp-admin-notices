<?php
/**
 * WP Admin Notices - Display_Rules
 *
 * @version 1.0.0
 * @since   1.0.0
 * @author  Pablo S G Pacheco
 */

namespace ThanksToWP\WPAN;

if ( ! class_exists( 'ThanksToWP\WPAN\Display_Rules' ) ) {

	class Display_Rules {
		/**
		 * @var Notice
		 */
		private $notice;

		/**
		 * @var NoticesManager
		 */
		private $manager;

		/**
		 * @return NoticesManager
		 */
		public function get_manager() {
			return $this->manager;
		}

		/**
		 * @return Notice
		 */
		public function get_notice() {
			return $this->notice;
		}

		/**
		 * @param Notice $notice
		 */
		public function set_notice( $notice ) {
			$this->notice = $notice;
		}

		/**
		 * @param NoticesManager $manager
		 */
		public function set_manager( $manager ) {
			$this->manager = $manager;
		}

		private $display_rules = array(
			'screen_id'        => array( 'plugins' ),
			'activated_plugin' => array(),
			'updated_plugin'   => array(),
		);

		public function __construct( $display_rules = array() ) {
			$this->display_rules = wp_parse_args( $display_rules, array(
				'screen_id'        => array(),
				'activated_plugin' => array(),
				'updated_plugin'   => array(),
			) );
		}

		public function rules_match() {
			$rules     = $this->display_rules;
			$all_match = true;

			foreach ( $rules as $key => $value ) {
				if ( ! empty( $value ) ) {
					if (
						$key == 'screen_id' ||
						$key == 'activated_plugin' ||
						$key == 'updated_plugin'
					) {
						add_filter( "ttwpwpan_rule_{$key}", array( $this, "rule_{$key}_match" ), 10, 2 );
					}
					$match = apply_filters( "ttwpwpan_rule_{$key}", true, $value );

					if ( ! $match ) {
						if ( in_array( $key, $this->notice->keep_active_on ) ) {
							$match = $this->notice->is_active();
						}
					}

					if ( ! $match ) {
						$all_match = false;
						break;
					} else {
						$this->notice->keep_active_if_necessary( $key );
						//$this->keep_active_if_necessary( $key );
					}
				}
			}

			return $all_match;
		}

		private function replace_self_by_plugin_basename( $plugins_array ) {
			$self_index = array_search( 'self', $plugins_array );
			if ( $self_index !== false ) {
				$plugins_array[ $self_index ] = plugin_basename( $this->manager->file_path );
			}

			return $plugins_array;
		}

		public function rule_updated_plugin_match( $match = true, $plugins ) {
			$options = get_option( 'ttwpwpan_upgrader_options', array() );
			$plugins = $this->replace_self_by_plugin_basename( $plugins );

			if ( empty( $options ) ) {
				return false;
			}

			if ( $options['action'] == 'update' && $options['type'] == 'plugin' ) {
				if ( count( $options['plugins'] ) > 0 ) {
					wp_clear_scheduled_hook( 'wpanttwp_dispose_event' );
					wp_schedule_single_event( time() + 1, 'wpanttwp_dispose_event' );
				}
				if ( count( array_intersect( $options['plugins'], $plugins ) ) > 0 ) {
					return true;
				} else {
					return false;
				}
			} else {
				return false;
			}
		}

		public function rule_activated_plugin_match( $match = true, $plugins ) {
			$activated_plugins = get_option( 'ttwpwpan_activated_plugins', array() );
			if ( count( $activated_plugins ) > 0 ) {
				wp_clear_scheduled_hook( 'wpanttwp_dispose_event' );
				wp_schedule_single_event( time() + 1, 'wpanttwp_dispose_event' );
			}
			$plugins = $this->replace_self_by_plugin_basename( $plugins );
			if ( count( array_intersect( $activated_plugins, $plugins ) ) > 0 ) {
				return true;
			} else {
				return false;
			}
		}

		public function rule_screen_id_match( $match = true, $screen ) {
			$current_screen = get_current_screen();
			if ( is_array( $screen ) ) {
				if ( array_search( $current_screen->id, $screen ) !== false ) {
					return true;
				} else {
					return false;
				}
			} else {
				if ( $current_screen->id == $screen ) {
					return true;
				} else {
					return false;
				}
			}
		}

	}


}