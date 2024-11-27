<?php

/**
 * Plugin Name: Post Timer
 * Plugin URI: http://www.addwebsolution.com
 * Description: It displays countdown timer in popup when user add/edit posts, pages or custom post. Go to your Post Timer settings page, and change popup theme/position as per your requirement.
 * Version: 5.0
 * Author: AddWeb Solution
 * Author URI: http://www.addwebsolution.com
 * License: GPL-2.0+
 * License URI: http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain: post-timer
 **/

// If this file is called directly, abort.
if (! defined('ABSPATH')) {
	die;
}

define("ADDWEBPT_PLUGIN_VERSION", 4.0);
define("ADDWEBPT_PLUGIN_DIR", plugin_dir_path(__FILE__));
define("ADDWEBPT_PLUGIN_URL", plugins_url('/', __FILE__));
define("ADDWEBPT_TEXT_DOMAIN", "post-timer");

require_once ADDWEBPT_PLUGIN_DIR . '/includes/admin.php';
require_once ADDWEBPT_PLUGIN_DIR . '/includes/timer.php';


//Initialize Default values when plugin is activated
register_activation_hook(__FILE__, array('ADDWEBPT_POST_TIMER', 'addweb_pt_setDefault_values'));
register_deactivation_hook(__FILE__, array('ADDWEBPT_POST_TIMER', 'addweb_pt_deleteDefault_values'));
