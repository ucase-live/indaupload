<?php
/**
 * Plugin Name:     Upload csv plugin
 * Plugin URI:      https://ucase.live
 * Plugin Prefix:   TD
 * Description:     Plugin for upload csv-files
 * Author:          Ucase
 * Author URI:      https://ucase.live
 * Text Domain:     indaupload
 * Domain Path:     /languages
 * Version:         0.1.0
 */

use Backyard\Application;

if ( file_exists( dirname( __FILE__ ) . '/vendor/autoload.php' ) ) {
require dirname( __FILE__ ) . '/vendor/autoload.php';
}

$app = Application::get();
$indauploadPlugin = $app->loadPlugin( __DIR__, __FILE__, 'config' );

$indauploadPlugin->onActivation(
    function() use ( $indauploadPlugin ) {
        // Do something on activation here like update_option()
    }
);

$indauploadPlugin->onDeactivation(
    function() use ( $indauploadPlugin ) {
        // Do something on deactivation here
    }
);

$indauploadPlugin->boot(
    function( $plugin ) {
        // Do something when the plugins_loaded hook is fired.
    }
);