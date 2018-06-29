# WP Admin Notices - TTWP

```php
add_action( 'admin_notices', function () {
	$notices_manager = \ThanksToWP\WPAN\get_notices_manager();
	$notices_manager->create_notice( array(
		'id'         => 'my-notice',
		'content'    => '<p>My Notice</p>',
		'display_on' => array(
			'screen_id' => array( 'plugins' ),
			'request'   => array(
				array( 'key' => 'show_notice', 'value' => '1' ),
				array( 'key' => 'show_notice', 'value' => 'true' )
			),
		)
	) );
} );
```
