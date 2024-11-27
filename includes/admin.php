<?php

/**
 * Timer Popup Class
 * @package   Post Timer
 * @author    AddWeb Solution
 * @license   GPL-2.0+
 * @link      http://www.addwebsolution.com
 * @copyright 2016 AddWeb Solution
 **/

/**
 * Restricting user to access this file directly (Security Purpose).
 **/
if (!defined('ABSPATH')) {
  die("You Don't Have Permission To Access This Page");
  exit;
}

class ADDWEBPT_POST_TIMER
{

  public function __construct()
  {
    add_action('admin_menu', array($this, 'addweb_pt_addmenu_page'));
    add_action('admin_init', array($this, 'addweb_pt_register_settings'));
    add_action('admin_enqueue_scripts', array($this, 'addweb_pt_admin_style_and_js'));
  }

  /**
   * Add Plugin Menu Page
   **/
  public function addweb_pt_addmenu_page()
  {
    add_menu_page(__('Post Timer', 'post-timer'), __('Post Timer', 'post-timer'), 'manage_options', 'post-timer', array($this, 'addweb_pt_plugin_setting_page'), '
dashicons-clock');
  }
  /**
   * Add menu template
   **/
  public function addweb_pt_plugin_setting_page()
  {
    $addweb_pt_option = get_option(ADDWEBPT_TEXT_DOMAIN);
    $addweb_pt_get_popup_place = array(
      'right-bottom' => 'Right Bottom',
      'left-bottom' => 'Left Bottom',
      'top-left' => 'Top Left',
      'top-right' => 'Top Right',
      'right' => 'Right',
      'left' => 'Left',
    );
    include ADDWEBPT_PLUGIN_DIR . 'includes/plugin-setting-page.php';
  }

  /**
   * Register settings for plugin
   **/
  public function addweb_pt_register_settings()
  {
    register_setting(ADDWEBPT_TEXT_DOMAIN, ADDWEBPT_TEXT_DOMAIN, array($this, 'addweb_pt_sanatize_setting'));
  }

  /**
   * Sanitizing the submitted text
   **/
  public function addweb_pt_sanatize_setting($settings)
  {
    return $settings;
  }

  /**
   * Adding Script and style file
   **/
  public function addweb_pt_admin_style_and_js()
  {
    wp_enqueue_style('wp-color-picker');
    wp_enqueue_style(ADDWEBPT_TEXT_DOMAIN . '-style', ADDWEBPT_PLUGIN_URL . '/assets/css/post-timer-popup.css', array(), ADDWEBPT_PLUGIN_VERSION);
    wp_enqueue_script(ADDWEBPT_TEXT_DOMAIN . '-admin-script', ADDWEBPT_PLUGIN_URL . '/assets/js/admin.js', array('jquery', 'wp-color-picker'), ADDWEBPT_PLUGIN_VERSION);
    wp_enqueue_media();
    wp_enqueue_script('jquery-ui-tabs');
    wp_enqueue_style(ADDWEBPT_TEXT_DOMAIN . 'jquery-ui-css', ADDWEBPT_PLUGIN_URL . '/assets/css/jquery-ui.min.css', array(), ADDWEBPT_PLUGIN_VERSION);
  }

  /**
   * Register Default Setting When Plugin Activate
   **/
  static function addweb_pt_setDefault_values()
  {
    $default_values = array(
      'addweb_pt_popup_active' => '1',
      'addweb_pt_popup_color' => '#0b73b0',
      'addweb_pt_popup_place' => 'top-right',
      'addweb_pt_popup_posts' => array('post', 'page')
    );
    update_option(ADDWEBPT_TEXT_DOMAIN, $default_values);
  }

  /**
   * Delete Default Value When Plugin Deactivate
   **/
  static function addweb_pt_deleteDefault_values()
  {
    delete_option(ADDWEBPT_TEXT_DOMAIN);
  }
}

if (class_exists('ADDWEBPT_POST_TIMER')) {
  new ADDWEBPT_POST_TIMER();
}
