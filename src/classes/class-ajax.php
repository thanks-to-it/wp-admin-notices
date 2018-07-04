<?php
/**
 * WP Admin Notices - Ajax
 *
 * @version 1.0.0
 * @since   1.0.0
 * @author  Pablo S G Pacheco
 */

namespace ThanksToWP\WPAN;

if ( ! class_exists( 'ThanksToWP\WPAN\Ajax' ) ) {

	class Ajax {
		public $action_dismiss_persistently = 'wpanttwp_dismiss_persist';
		public $action_dismiss_persistently_security = 'wpanttwpd_dismiss_sec';

		public function dismiss() {
			if ( ! check_ajax_referer( $this->action_dismiss_persistently_security, 'security' ) ) {
				wp_die();
			}

			$notice_id                  = $_REQUEST['id'];
			$notice                     = new Notice( $notice_id );
			$notice->dismiss_expiration = $_REQUEST['expiration'];
			$notice->dismiss();
			die();
		}
	}
}