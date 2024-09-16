<?php
/**
 * Plugin Name: Single Session
 * Plugin URI: https://github.com/hasinhayder/single-session
 * Description: This plugin restricts users to have only one active session at a time. It ensures that if a user is already logged in from one device or browser, they will be automatically logged out from any other active sessions.
 * Author: Hasin Hayder
 * Author URI: https://github.com/hasinhayder
 * Version: 1.0
 * License: GPL-2.0
 * License URI: http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain: single-session
 * Domain Path: /languages
 */

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

class SingleSession {

    /**
     * Constructor to initialize the plugin
     */
    public function __construct() {
        // Hook into 'wp_login' action to manage session control on user login
        add_action('wp_login', [$this, 'force_single_session_on_login'], 10, 2);
    }

    /**
     * Force single session on user login by retaining only the latest session token
     *
     * @param string $user_login
     * @param WP_User $user
     */
    public function force_single_session_on_login($user_login, $user) {
        // Check if the user is logged in and valid
        if (is_a($user, 'WP_User')) {
            $user_id = $user->ID;
            // Retrieve the session tokens array from the user meta
            $sessions = get_user_meta($user_id, 'session_tokens', true);

            if ($sessions && is_array($sessions)) {
                // Keep only the last session (most recent login)
                $sessions = array_slice($sessions, -1);
                // Update the user meta with the new session array
                update_user_meta($user_id, 'session_tokens', $sessions);
            }
        }
    }
}

// Initialize the plugin
new SingleSession();
