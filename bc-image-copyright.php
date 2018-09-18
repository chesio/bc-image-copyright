<?php
/**
 * Plugin Name: BC Image Copyright
 * Plugin URI: https://github.com/chesio/bc-image-copyright
 * Description: Display and manage copyright information of your (image) image files.
 * Version: 1.0.0
 * Author: Česlav Przywara <ceslav@przywara.cz>
 * Author URI: https://www.chesio.com
 * Requires PHP: 7.0
 * Requires WP: 4.9
 * Tested up to: 4.9
 * Text Domain: bc-image-copyright
 * GitHub Plugin URI: https://github.com/chesio/bc-image-copyright
 */

if (version_compare(PHP_VERSION, '7.0', '<')) {
    // Warn user that his/her PHP version is too low for this plugin to function.
    add_action('admin_notices', function () {
        echo '<div class="error"><p>';
        echo esc_html(
            sprintf(
                __('BC Image Copyright plugin requires PHP 7.0 to function properly, but you have version %s installed. The plugin has been auto-deactivated.', 'bc-image-copyright'),
                PHP_VERSION
            )
        );
        echo '</p></div>';
        // https://make.wordpress.org/plugins/2015/06/05/policy-on-php-versions/
        if (isset($_GET['activate'])) {
            unset($_GET['activate']);
        }
    }, 10, 0);

    // Self deactivate.
    add_action('admin_init', function () {
        deactivate_plugins(plugin_basename(__FILE__));
    }, 10, 0);

    // Bail.
    return;
}


// Register autoloader for this plugin.
require_once __DIR__ . '/autoload.php';

// Include public API.
require_once __DIR__ . '/api.php';

// Construct plugin instance and load the plugin.
(new \BlueChip\ImageCopyright\Plugin())->load();
