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

# create_notice() parameters

|Arg                |Description                    |type    |Default          |              
|----------------|-------------------------------|--------|---------------------------|
|**id**| Notice ID            |`string`              |`''`
|**type**| Notice type          |`string`              |`'notice-info'`
|**content**|Notice content   |`string`              |`''` 
|**display_on**|Situations where/when the notice should be displayed| `array` | `array()` 
|**dismissible**|If notice can be persistently closed   |`boolean`              |`true`|
|**dismissal_expiration**|Time in seconds the notice will be persistently hidden after users close it| `int` | `1 * MONTH_IN_SECONDS`
|**keep_active_on**|Keep notice active after display_on triggers until users close it| `array` | `array( 'activated_plugin', 'updated_plugin' )`

# display_on parameters
Situations where/when the notice should be displayed

|Arg                |Description                    |type    |Example |               
|----------------|-------------------------------|--------|---------------------------|
|**request**| Displays on $_GET or $_POST values            |`array`              |`'request' => array( array( 'key' => 'show_notice', 'value' => '1'), array( 'key' => 'show_notice', 'value' => 'true') )`|
|**screen_id**| Displays on Admin Screen Ids            |`array`              |`array( 'plugins' )`|
|**activated_plugin**| Displays if some plugin gets activated            |`array`              |`array('akismet/akismet.php')`|
|**updated_plugin**| Displays if some plugin gets updated|`array`              |`array('akismet/akismet.php')`|
