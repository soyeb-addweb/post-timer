<?php

/**
 * Restricting user to access this file directly (Security Purpose).
 **/
if (! defined('ABSPATH')) {
  die("Sorry You Don't Have Permission To Access This Page");
  exit;
}

/********* Plugin Setting Template ********/

if (isset($_GET['settings-updated'])) { ?>
  <div class="updated settings-error notice is-dismissible" id="setting-error-settings_updated">
    <p><strong>Settings saved.</strong></p>
    <button class="notice-dismiss" type="button"><span class="screen-reader-text">Dismiss this notice.</span></button>
  </div><?php } ?>
<div class="pt-wrap">
  <div class="fa-plugin-setting">
    <ul>
      <li><a href="#pt-setting"><?php _e('Settings', 'post-timer'); ?></a></li>
      <li><a href="#pt-about">About Us</a></li>
    </ul>
    <div id="pt-setting">

      <div style="display:none;">
        <a href="https://www.wewp.io/" style="outline: hidden;" target="_blank"><img src="<?php echo ADDWEBPT_PLUGIN_URL . '/assets/images/wewp-logo.png'; ?>" alt="WeWp" width="100%"></a>
      </div>
      <h2><?php _e('Timer Popup Settings', 'post-timer'); ?></h2>
      <form method="post" action="options.php">
        <!-- form method="post" action="<?php //echo esc_url( admin_url( 'options-general.php?page='.$_GET['page'].'&header=true' ) ); 
                                        ?>" enctype="multipart/form-data"> --><?php
                                                                              settings_fields('post-timer');
                                                                              ?><div class="timer_popup_form">
          <table class="form-table" width="100%" borde>
            <tr>
              <th scope="row">Enable</th>
              <td>
                <input type="checkbox" name="<?php echo 'post-timer'; ?>[addweb_pt_popup_active]" <?php echo !empty($addweb_pt_option['addweb_pt_popup_active']) ? 'checked="checked"' : ''; ?> id="addweb_pt_popup_active" value="1">
              </td>
              <td rowspan="4" align="right" valign="top">
                <a href="http://www.wewp.io" style="outline: hidden;" target="_blank"><img src="<?php echo ADDWEBPT_PLUGIN_URL . '/assets/images/wewp-ad-plugin-400.png'; ?>" alt="WeWp" width="280px"></a>
              </td>
            </tr>
            <tr>
              <th scope="row"><label for="addweb_pt_popup_color"><?php _e('Popup Color', 'post-timer'); ?></label></th>
              <td><input type="text" name="<?php echo 'post-timer'; ?>[addweb_pt_popup_color]" id="popup_color" maxlength="255" size="25" value="<?php echo $addweb_pt_option['addweb_pt_popup_color']; ?>"></td>
            </tr>
            <tr>
              <th scope="row"><label for="addweb_pt_popup_place"><?php _e('Popup Place', 'post-timer'); ?></label></th>
              <td><select name="<?php echo ADDWEBPT_TEXT_DOMAIN; ?>[addweb_pt_popup_place]" id="addweb_pt_popup_place">
                  <?php foreach ($addweb_pt_get_popup_place as $key => $value): ?>
                    <option value="<?php esc_attr_e($key); ?>" <?php esc_attr_e($key == $addweb_pt_option['addweb_pt_popup_place'] ? ' selected="selected"' : ''); ?>><?php esc_attr_e($value); ?></option>
                  <?php endforeach; ?>
                </select></td>Popup Place
            </tr>
            <tr>
              <th scope="row"><label for="addweb_pt_popup_top_margin"><?php _e('Popup Top Margin', 'post-timer'); ?></label></th>
              <td>
                <input
                  type="number"
                  name="<?php echo ADDWEBPT_TEXT_DOMAIN; ?>[addweb_pt_popup_top_margin]"
                  id="addweb_pt_popup_top_margin"
                  maxlength="255"
                  size="25"
                  value="<?php echo isset($addweb_pt_option['addweb_pt_popup_top_margin']) ? esc_attr($addweb_pt_option['addweb_pt_popup_top_margin']) : ''; ?>">
                <span>%</span>
                <small><?php _e('Top margin is only included if popup place Left or Right is selected. Please enter numeric value.', 'post-timer'); ?></small>
              </td>
            </tr>

          </table>
          <table class="form-table" width="100%">
            <tr>
              <th scope="row"><?php _e('Choose Where To Show Popup'); ?></th><?php

                                                                              $i_row = 0;
                                                                              foreach (get_post_types(array(), 'objects') as $addweb_pt_post_type) {
                                                                                $addweb_pt_post_name = $addweb_pt_post_type->name;
                                                                                $addweb_pt_post_remove = array('attachment', 'revision', 'nav_menu_item', 'custom_css', 'customize_changeset', 'ml-slider', 'oembed_cache');
                                                                                if (in_array($addweb_pt_post_name, $addweb_pt_post_remove)) {
                                                                                  echo '';
                                                                                } else {

                                                                                  echo $i_row % 5 == 0 && $i_row != 0 ? '</tr><th scope="row"></th>' : '';
                                                                              ?>
                  <td><input type="checkbox"
                      name="post-timer[addweb_pt_popup_posts][]"
                      id="<?php echo esc_attr($addweb_pt_post_type->name); ?>"
                      value="<?php echo esc_attr($addweb_pt_post_type->name); ?>"
                      <?php
                                                                                  if (
                                                                                    !empty($addweb_pt_option['addweb_pt_popup_posts']) &&
                                                                                    is_array($addweb_pt_option['addweb_pt_popup_posts']) &&
                                                                                    in_array($addweb_pt_post_type->name, $addweb_pt_option['addweb_pt_popup_posts'])
                                                                                  ) {
                                                                                    echo 'checked="checked"';
                                                                                  }
                      ?> />
                    <label for="<?php echo esc_attr($addweb_pt_post_type->name); ?>"><strong><?php echo esc_html($addweb_pt_post_type->label); ?></strong></label>
                  </td>
              <?php
                                                                                  $i_row++;
                                                                                }
                                                                              }
              ?>
            </tr>
          </table>
          <p class="submit">
            <?php submit_button(); ?>
          </p>
        </div>
      </form>
    </div>
    <div id="pt-about">
      <?php
      $arrAddwebPlugins = array(
        'woo-cart-customizer' => 'Simple Customization of Add to Cart Button',
        'aws-cookies-popup' => 'AWS Cookies Popup',
        'addweb-google-popular-post' => 'Traffic Post Page Views',
        'post-timer' => 'Post Timer',
        'wc-past-orders' => 'Track Order History for WooCommerce',
        'widget-social-share' => 'WSS: Widget Social Share'

      ); ?>
      <div class="advertise">
        <h2><?php _e('Visit Our Other Plugins:', 'post-timer'); ?></h2>
        <div class="ad-content"><?php
                                foreach ($arrAddwebPlugins as $slug => $name) { ?>
            <div class="ad-detail">
              <div class="ad-inner">
                <a href="https://wordpress.org/plugins/<?php echo $slug; ?>" target="_blank"><img height="160" src="<?php echo ADDWEBPT_PLUGIN_URL . 'assets/images/' . $slug; ?>.svg"></a>
                <a href="https://wordpress.org/plugins/<?php echo $slug; ?>" class="ad-link" target="_blank"><b><?php echo $name; ?></b></a>
              </div>
            </div><?php
                                } ?>
        </div>
      </div>

      <div style="margin:5px 0;width:100%;text-align: center;">
        <a href="http://www.wewp.io" style="outline: hidden;" target="_blank"><img src="<?php echo ADDWEBPT_PLUGIN_URL . '/assets/images/wewp-logo.png'; ?>" alt="WeWp" height="150px" width="100%"></a>
      </div>
      <div style="margin:5px 0;width:100%;text-align: center;">
        <h3>Developed with <img decoding="async" src="<?php echo ADDWEBPT_PLUGIN_URL . '/assets/images/Heart-yellow.svg'; ?>" alt="AddwebSolution"> By <a href="http://www.addwebsolution.com" style="outline: hidden;" target="_blank">ADDWEB SOLUTION</a></h3>

      </div>
    </div>
  </div><?php
        $plugin_basename = plugin_basename(plugin_dir_path(__FILE__)); ?>
</div>