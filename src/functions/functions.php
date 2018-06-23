<?php

namespace ThanksToWP\WPAN;

/**
 * @return NoticesManager
 */
function get_notices_manager() {
	$notices_manager = NoticesManager::instance();

	return $notices_manager;
}
