# WP Admin Notices - TTWP
An easy and convenient way to create WordPress admin notices that can be closed persistently. Besides that it has some cool features:
* Make your notice keep hidden only for the user who closed it.
* Display your notice on specific situations, like screen ids, requests, even if some plugin gets updated or activated
* Create your own special cases where/when your notice should be displayed

## Simple Usage

```php
add_action( 'admin_notices', function () {
	$notices_manager = \ThanksToWP\WPAN\get_notices_manager();
	$notices_manager->create_notice( array(
		'id'         => 'my-notice',
		'content'    => '<p>My Notice</p>',		
	) );
} );
```

**Note:** By default, this library will make notices persist, meaning they will not be displayed again after user close them, unless they expire

## create_notice() parameters

* **id** (String) - Identifies the notice.
* **type** (String) - The type of notice, used on notice class in order to setup its color. Possible values are `'notice-info'`, `'notice-warning'`, `'notice-success'`, `'notice-error'`, `'notice-info'`. Default value is `'notice-info'`.
* **content** (String) - Notice content.
* **dismissible** (Boolean) - If notice should be dismissible. In other words, if you can close it. Default is `true`.
* **dismissal_expiration** (int) - How long in seconds the notice will keep closed. Values bigger than zero will make the notice persist after closed. If you set 0, it will no longer persist. Default is `MONTH_IN_SECONDS`.
* **display_on** (array) - Situations where/when the notice should be displayed. Possible values below:
  - **screen_id** (array). Displays the notice on specific admin screen ids. Example: `'screen_id' => array( 'plugins' )`.
  - **activated_plugin** (array). Displays if some plugin gets activated. Example: `'activated_plugin' => array('akismet/akismet.php')`.
  - **updated_plugin** (array). Displays if some plugin gets updated. Example: `'updated_plugin' => array('akismet/akismet.php')`.
  - **request** (array) - Displays on $_GET or $_POST values. Note: It takes an array of arrays with `'key'` and `'value'` parameters. Example:
`'request' => array( array( 'key' => 'show_notice', 'value' => '1' ), ) `.
* **keep_active_on** (array) - Keeps the notice always opened once the display_on requirements are met, until the user close the notice. Default value is `array( 'activated_plugin', 'updated_plugin' )`,

## Examples

### Create a notice that will be closed for 1 week
```php
add_action( 'admin_notices', function () {
	$notices_manager = \ThanksToWP\WPAN\get_notices_manager();
	$notices_manager->create_notice( array(
		'id'         => '1-week-notice',
		'content'    => '<p>1 week notice</p>',	
		'dismissal_expiration' => WEEK_IN_SECONDS,
	) );
} );
```

### Create a notice that will be displayed on plugins page only
```php
add_action( 'admin_notices', function () {
	$notices_manager = \ThanksToWP\WPAN\get_notices_manager();
	$notices_manager->create_notice( array(
		'id'         => '1-week-notice',
		'content'    => '<p>1 week notice</p>',	
		'display_on' => array(
			'screen_id' => array( 'plugins' ),			
		)
	) );
} );
```
