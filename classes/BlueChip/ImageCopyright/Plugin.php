<?php
/**
 * @package BC_Image_Copyright
 */

namespace BlueChip\ImageCopyright;

class Plugin
{
    /**
     * Load the plugin by hooking into WordPress actions and filters.
     *
     * @internal Method should be invoked immediately on plugin load.
     */
    public function load()
    {
        // Register initialization method.
        add_action('init', [$this, 'init'], 10, 0);
    }


    /**
     * Perform initialization tasks.
     *
     * @internal Method should be invoked in `init` hook.
     */
    public function init()
    {
        (new Admin())->init();
    }


    /**
     * Perform uninstallation tasks.
     *
     * @internal Method should be run on plugin uninstall.
     * @link https://developer.wordpress.org/plugins/the-basics/uninstall-methods/
     */
    public function uninstall()
    {
        // Delete all persistent data set by this plugin.
        Attachment::purgeCopyrightData();
    }
}
