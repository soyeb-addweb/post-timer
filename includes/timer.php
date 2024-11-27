<?php

/**
 * Timer Popup Class
 * @package   Post Timer
 * @author    AddWeb Solution
 * @license   GPL-2.0+
 * @link      http://www.addwebsolution.com
 * @copyright 2016 AddwebSolution Pvt. Ltd.
 **/

/**
 * Restricting user to access this file directly (Security Purpose).
 **/
if (!defined('ABSPATH')) {
  die("You Don't Have Permission To Access This Page");
  exit;
}

class ADDWEBPT_TIMER
{

  public $options;

  public $post_related_data;

  public function __construct()
  {
    $this->options = get_option(ADDWEBPT_TEXT_DOMAIN);
    $this->post_related_data = $this->addweb_pt_get_post_page_related_data();
    if ($this->addweb_pt_enable_popup()) {
      add_action('admin_head', array($this, 'addweb_pt_timer_clock'));
      add_action('admin_head', array($this, 'addweb_pt_head_styles'));
      add_filter('admin_footer', array($this, 'addweb_pt_get_timer_popup'));
      add_action('admin_footer', array($this, 'addweb_pt_footer_scripts'));
    }
  }

  public function addweb_pt_enable_popup()
  {
    $show_timer_popup = false;
    // add_action( 'admin_enqueue_scripts', array( $this, 'addweb_pt_enqueue_styles' ) );
    if (isset($this->options['addweb_pt_popup_active']) && isset($this->options['addweb_pt_popup_posts'])) {

      //Show popup when create new custom post.
      if ($this->post_related_data['addweb_pt_url_page'] == 'post-new.php' && in_array($this->post_related_data['addweb_pt_query_string'], $this->options['addweb_pt_popup_posts'])) {
        $show_timer_popup = true;
      }

      //Create new user 
      if (($this->post_related_data['addweb_pt_url_page'] == 'user-new.php' || ($this->post_related_data['addweb_pt_url_page'] == 'profile.php' || (isset($this->post_related_data['addweb_pt_action_query']) && $this->post_related_data['addweb_pt_action_query'] == 'edit'))) && in_array('user_request', $this->options['addweb_pt_popup_posts'])) {
        $show_timer_popup = true;
      }

      //navigation menu
      if ($this->post_related_data['addweb_pt_url_page'] == 'nav-menus.php' && in_array('wp_navigation', $this->options['addweb_pt_popup_posts'])) {
        $show_timer_popup = true;
      }

      //Show popup when edit a custom post.
      if ($this->post_related_data['addweb_pt_url_page'] == 'post.php' && $this->post_related_data['addweb_pt_action_query'] == 'edit' && in_array($this->post_related_data['addweb_post_type'], $this->options['addweb_pt_popup_posts'])) {
        $show_timer_popup = true;
      }

      //Show popup when create a new or edit a simple post.
      if (empty($this->post_related_data['addweb_pt_query_string']) && $this->post_related_data['addweb_pt_url_page'] == 'post-new.php' && in_array('post', $this->options['addweb_pt_popup_posts']) || $this->post_related_data['addweb_pt_url_page'] == 'post.php' && $this->post_related_data['addweb_pt_action_query'] == 'edit' && in_array('post', $this->options['addweb_pt_popup_posts']) && empty($this->post_related_data['addweb_post_type'])) {
        $show_timer_popup = true;
      }
      return $show_timer_popup;
    }
  }

  public function addweb_pt_get_timer_popup()
  {
    // $addweb_pt_popup_html  = '<div class="addweb-pt-timer-popup" onclick="start();">';
    // $addweb_pt_popup_html .= '<div class="popup-wrap">';
    // if ($this->options['addweb_pt_popup_place'] != 'top-left' && $this->options['addweb_pt_popup_place'] != 'top-right') {
    //   $addweb_pt_popup_html .= '<div class="popup-header">';
    //   $addweb_pt_popup_html .= '<span class="popup-title" id="timer-clock">';

    //   $addweb_pt_popup_html .= '</span>';
    //   $addweb_pt_popup_html .= '</div>';
    // }

    // if ($this->options['addweb_pt_popup_place'] == 'top-left' || $this->options['addweb_pt_popup_place'] == 'top-right') {
    //   $addweb_pt_popup_html .= '<div class="popup-header">';
    //   $addweb_pt_popup_html .= '<span class="popup-title" id="timer-clock">';

    //   $addweb_pt_popup_html .= '</span>';
    //   $addweb_pt_popup_html .= '</div>';
    // }
    // $addweb_pt_popup_html .= '</div>';
    // $addweb_pt_popup_html .= '</div>';

    // echo $addweb_pt_popup_html;

    // Ensure options are set and valid.
    if (empty($this->options) || !isset($this->options['addweb_pt_popup_place'])) {
      return; // Prevent execution if options are missing.
    }

    // Initialize the popup HTML.
    $addweb_pt_popup_html  = '<div class="addweb-pt-timer-popup" onclick="start();">';
    $addweb_pt_popup_html .= '<div class="popup-wrap">';

    // Determine the placement of the popup header.
    if ($this->options['addweb_pt_popup_place'] == 'top-left' || $this->options['addweb_pt_popup_place'] == 'top-right') {
      $addweb_pt_popup_html .= '<div class="popup-header">';
      $addweb_pt_popup_html .= '<span class="popup-title" id="timer-clock"></span>';
      $addweb_pt_popup_html .= '</div>';
    } else {
      $addweb_pt_popup_html .= '<div class="popup-header">';
      $addweb_pt_popup_html .= '<span class="popup-title" id="timer-clock"></span>';
      $addweb_pt_popup_html .= '</div>';
    }

    // Close the wrap and main div.
    $addweb_pt_popup_html .= '</div>';
    $addweb_pt_popup_html .= '</div>';

    $allowed_html = array(
      'div' => array(
        'class' => array(),
        'onclick' => array(),
      ),
      'span' => array(
        'class' => array(),
        'id' => array(),
      ),
    );

    // Safely output the HTML.
    echo wp_kses($addweb_pt_popup_html, $allowed_html);



    if (empty($this->post_related_data['addweb_pt_query_string']) || $this->post_related_data['addweb_pt_url_page'] == 'post-new.php' || ($this->post_related_data['addweb_pt_url_page'] == 'post.php' && $this->post_related_data['addweb_pt_action_query'] == 'edit')) {
?><script>
        jQuery(document).ready(function() {
          jQuery('.addweb-pt-timer-popup').click();
        });
      </script><?php
              }
            }

            /**
             * Add Javascript for popup place
             */
            public function addweb_pt_footer_scripts()
            {
              if ($this->options['addweb_pt_popup_place'] == 'right-bottom') {
                ?><script type="text/javascript">
        jQuery(document).ready(function() {
          jQuery(".addweb-pt-timer-popup").addClass('right-bottom');
          var contheight = jQuery(".popup-content").outerHeight() + 2;
          jQuery(".addweb-pt-timer-popup").css("bottom", "-" + contheight + "px");

          jQuery(".addweb-pt-timer-popup").css("visibility", "visible");

          jQuery('.addweb-pt-timer-popup').addClass("open_timer_popup");
          jQuery('.addweb-pt-timer-popup').addClass("popup-content-bounce-in-up");
        });
      </script><?php
              } elseif ($this->options['addweb_pt_popup_place'] == 'left-bottom') {
                ?><script type="text/javascript">
        jQuery(document).ready(function() {
          jQuery(".addweb-pt-timer-popup").addClass('left-bottom');
          var contheight = jQuery(".popup-content").outerHeight() + 2;
          jQuery(".addweb-pt-timer-popup").css("bottom", "-" + contheight + "px");

          jQuery(".addweb-pt-timer-popup").css("visibility", "visible");

          jQuery('.addweb-pt-timer-popup').addClass("open_timer_popup");
          jQuery('.addweb-pt-timer-popup').addClass("popup-content-bounce-in-up");
        });
      </script><?php
              } elseif ($this->options['addweb_pt_popup_place'] == 'left') {
                ?><script type="text/javascript">
        jQuery(document).ready(function() {
          if ( /*@cc_on!@*/ true) {
            var ieclass = 'ie' + document.documentMode;
            jQuery(".popup-wrap").addClass(ieclass);
          }
          jQuery(".addweb-pt-timer-popup").addClass('addweb-pt-timer-popup-left');
          var contwidth = jQuery(".popup-content").outerWidth() + 2;
          jQuery(".addweb-pt-timer-popup").css("left", "-" + contwidth + "px");

          jQuery(".addweb-pt-timer-popup").css("visibility", "visible");

          jQuery('.addweb-pt-timer-popup').addClass("open_timer_popup_left");
          jQuery('.addweb-pt-timer-popup').addClass("popup-content-bounce-in-left");
        });
      </script><?php
              } elseif ($this->options['addweb_pt_popup_place'] == 'right') {
                ?><script type="text/javascript">
        jQuery(document).ready(function() {
          if ( /*@cc_on!@*/ true) {
            var ieclass = 'ie' + document.documentMode;
            jQuery(".popup-wrap").addClass(ieclass);
          }
          jQuery(".addweb-pt-timer-popup").addClass('addweb-pt-timer-popup-right');
          var contwidth = jQuery(".popup-content").outerWidth() + 2;
          jQuery(".addweb-pt-timer-popup").css("right", "-" + contwidth + "px");
          jQuery(".addweb-pt-timer-popup").css("visibility", "visible");
          jQuery('.addweb-pt-timer-popup').addClass("open_timer_popup_right");
          jQuery('.addweb-pt-timer-popup').addClass("popup-content-bounce-in-right");
        });
      </script><?php
              } elseif ($this->options['addweb_pt_popup_place'] == 'top-left') {
                ?><script type="text/javascript">
        jQuery(document).ready(function() {
          jQuery(".addweb-pt-timer-popup").addClass('top-left');
          var contheight = jQuery(".popup-content").outerHeight() + 2;
          jQuery(".addweb-pt-timer-popup").css("top", "-" + contheight + "px");
          jQuery(".addweb-pt-timer-popup").css("visibility", "visible");
          jQuery('.addweb-pt-timer-popup').addClass("open_timer_popup_top");
          jQuery('.addweb-pt-timer-popup').addClass("popup-content-bounce-in-down");
        });
      </script><?php
              } elseif ($this->options['addweb_pt_popup_place'] == 'top-right') {
                ?><script type="text/javascript">
        jQuery(document).ready(function() {
          jQuery(".addweb-pt-timer-popup").addClass('top-right');
          var contheight = jQuery(".popup-content").outerHeight() + 2;
          jQuery(".addweb-pt-timer-popup").css("top", "-" + contheight + "px");
          jQuery(".addweb-pt-timer-popup").css("visibility", "visible");
          jQuery('.addweb-pt-timer-popup').addClass("open_timer_popup_top");
          jQuery('.addweb-pt-timer-popup').addClass("popup-content-bounce-in-down");
        });
      </script><?php
              } else {
                ?><script type="text/javascript">
        jQuery(document).ready(function() {
          jQuery(".addweb-pt-timer-popup").addClass('right-bottom');
          var contheight = jQuery(".popup-content").outerHeight() + 2;
          jQuery(".addweb-pt-timer-popup").css("bottom", "-" + contheight + "px");
          jQuery(".addweb-pt-timer-popup").css("visibility", "visible");
          jQuery('.addweb-pt-timer-popup').addClass("open_timer_popup");
          jQuery('.addweb-pt-timer-popup').addClass("popup-content-bounce-in-up");
        });
      </script><?php
              }
            }

            //Timer Script
            public function addweb_pt_timer_clock()
            {
                ?><script>
      var clsStopwatch = function() {
        // Private vars
        var startAt = 0; // Time of last start / resume. (0 if not running)
        var lapTime = 0; // Time on the clock when last stopped in milliseconds

        var now = function() {
          return (new Date()).getTime();
        };

        // Public methods
        // Start or resume
        this.start = function() {
          startAt = startAt ? startAt : now();
        };

        // Stop or pause
        this.stop = function() {
          // If running, update elapsed time otherwise keep it
          lapTime = startAt ? lapTime + now() - startAt : lapTime;
          startAt = 0; // Paused
        };

        // Reset
        this.reset = function() {
          lapTime = startAt = 0;
        };

        // Duration
        this.time = function() {
          return lapTime + (startAt ? now() - startAt : 0);
        };
      };

      var x = new clsStopwatch();
      var $time;
      var clocktimer;

      function pad(num, size) {
        var s = "0000" + num;
        return s.substr(s.length - size);
      }

      function formatTime(time) {
        var h = m = s = ms = 0;
        var newTime = '';

        h = Math.floor(time / (60 * 60 * 1000));
        time = time % (60 * 60 * 1000);
        m = Math.floor(time / (60 * 1000));
        time = time % (60 * 1000);
        s = Math.floor(time / 1000);
        ms = time % 1000;

        newTime = pad(h, 2) + ':' + pad(m, 2) + ':' + pad(s, 2);
        return newTime;
      }

      jQuery(document).ready(function show() {
        $time = document.getElementById('timer-clock');
        update();
      });


      function update() {
        $time.innerHTML = formatTime(x.time());
      }

      function start() {
        clocktimer = setInterval("update()", 1);
        x.start();
      }

      function stop() {
        x.stop();
        clearInterval(clocktimer);
      }

      function reset() {
        stop();
        x.reset();
        update();
      }
    </script><?php
            }

            /**
             * Add styles for popup header color
             */
            public function addweb_pt_head_styles()
            {
              ?><style type="text/css">
      .addweb-pt-timer-popup .popup-header {
        <?php
              if ($this->options['addweb_pt_popup_color'] != '') {
        ?>background-color: <?php echo esc_attr($this->options['addweb_pt_popup_color']); ?>;
        <?php
              } else {
        ?>background-color: #2C5A85;
        <?php
              }
        ?>
      }

      <?php
              if ($this->options['addweb_pt_popup_place'] == 'left' || $this->options['addweb_pt_popup_place'] == 'right') {
      ?>.addweb-pt-timer-popup-right,
      .addweb-pt-timer-popup-left {
        <?php
                if ($this->options['addweb_pt_popup_top_margin'] != '') {
        ?>top: <?php echo esc_attr($this->options['addweb_pt_popup_top_margin']); ?>%;
        <?php
                } else {
        ?>top: 25%;
        <?php
                }
        ?>
      }

      <?php } ?>
    </style><?php
            }

            /**
             * Register and enqueues public-facing JavaScript files.
             */
            public function addweb_pt_enqueue_scripts()
            {
              wp_enqueue_script(ADDWEBPT_TEXT_DOMAIN . '-modernizr-script', ADDWEBPT_PLUGIN_URL . '/assets/js/modernizr.custom.js', array(), ADDWEBPT_PLUGIN_VERSION, 'false');
            }
            /*
  public function addweb_pt_get_post_page_related_data() {
    $addweb_pt_url = $_SERVER['REQUEST_URI'];
    // $addweb_pt_url2 = substr($addweb_pt_url, strrpos($addweb_pt_url, '/') + 1);
    // $dat3 = list($data['addweb_pt_url_page'] , $addweb_pt_param) = explode("?", $addweb_pt_url);
    // print_r($dat3);exit();
    $gets = parse_url($addweb_pt_url);
    
    if(isset($_GET['post_type'])){
      $data['addweb_pt_query_string'] = $_GET['post_type'];
    }
    if(isset($_GET['action'])){
      $data['addweb_pt_action_query'] = $_GET['action'];
    }
    if(isset($_GET['post'])){
      $data['addweb_post_type'] = get_post_type($_GET['post']);
    }
    
    if(isset($gets['query']))
    {
      $addweb_pt_url = substr($addweb_pt_url, strrpos($addweb_pt_url, '/') + 1);
      list($data['addweb_pt_url_page'] , $addweb_pt_param) = explode("?", $addweb_pt_url);
      return $data;
    }else{
      $addweb_pt_url = substr($addweb_pt_url, strrpos($addweb_pt_url, '/') + 1);
      list($data['addweb_pt_url_page'] , $addweb_pt_param) = explode("?", $addweb_pt_url);
      return $data;
    }
    
  }
  */
            // modified and optimize the aboce function
            public function addweb_pt_get_post_page_related_data()
            {
              if (isset($_SERVER['REQUEST_URI'])) {
                $addweb_pt_url = sanitize_text_field(wp_unslash($_SERVER['REQUEST_URI']));
              } else {
                $addweb_pt_url = '';
              }


              $data = []; // Initialize $data to avoid potential undefined index notices.

              // Parse URL components.
              $gets = wp_parse_url($addweb_pt_url);

              // Handle query string parameters.
              if (isset($_GET['post_type'])) {
                $data['addweb_pt_query_string'] = sanitize_text_field(wp_unslash($_GET['post_type']));
              }
              if (isset($_GET['action'])) {
                $data['addweb_pt_action_query'] = sanitize_text_field(wp_unslash($_GET['action']));
              }
              if (isset($_GET['post'])) {
                $data['addweb_post_type'] = get_post_type(sanitize_text_field(wp_unslash($_GET['post'])));
              }

              // Handle the path and query.
              $addweb_pt_url = substr($addweb_pt_url, strrpos($addweb_pt_url, '/') + 1);

              if (strpos($addweb_pt_url, "?") !== false) {
                list($data['addweb_pt_url_page'], $addweb_pt_param) = explode("?", $addweb_pt_url, 2);
              } else {
                $data['addweb_pt_url_page'] = $addweb_pt_url; // No query string found.
                $addweb_pt_param = ''; // Optional: Define $addweb_pt_param as an empty string if needed later.
              }

              return $data;
            }
          }

          if (class_exists('ADDWEBPT_TIMER')) {
            new ADDWEBPT_TIMER();
          }
