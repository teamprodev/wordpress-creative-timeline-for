<?php
if (!defined('ABSPATH')) { // Cannot access directly.
  die;
}
/**
 * Get icons from admin ajax
 *
 * @since 1.0.0
 * @version 1.0.0
 *
 */
if (!function_exists('ctwp_get_icons')) {
  function ctwp_get_icons() {
    $nonce = (!empty($_POST[ 'nonce' ])) ? sanitize_text_field(wp_unslash($_POST[ 'nonce' ])) : '';
    if (!wp_verify_nonce($nonce, 'ctwp_icon_nonce')) {
      wp_send_json_error(array('error' => esc_html__('Error: Invalid nonce verification.', CTWP_DOMAIN)));
    }
    ob_start();
    $icon_library = (apply_filters('ctwp_fa4', false)) ? 'fa4' : 'fa5';
    CTWP::include_plugin_file('fields/icon/'. $icon_library .'-icons.php');
    $icon_lists = apply_filters('ctwp_field_icon_add_icons', ctwp_get_default_icons());
    if (!empty($icon_lists)) {
      foreach ($icon_lists as $list) {
        echo (count($icon_lists) >= 2) ? '<div class="ctwp-icon-title">'. esc_attr($list['title']) .'</div>' : '';
        foreach ($list['icons'] as $icon) {
          echo '<i title="'. esc_attr($icon) .'" class="'. esc_attr($icon) .'"></i>';
        }
      }
    } else {
      echo '<div class="ctwp-error-text">'. esc_html__('No data available.', CTWP_DOMAIN) .'</div>';
    }
    $content = ob_get_clean();
    wp_send_json_success(array('content' => $content));
  }
  add_action('wp_ajax_ctwp-get-icons', 'ctwp_get_icons');
}

/**
 * Export
 */
if (!function_exists('ctwp_export')) {
  function ctwp_export() {
    $nonce  = (!empty($_GET[ 'nonce' ])) ? sanitize_text_field(wp_unslash($_GET[ 'nonce' ])) : '';
    $unique = (!empty($_GET[ 'unique' ])) ? sanitize_text_field(wp_unslash($_GET[ 'unique' ])) : '';
    if (!wp_verify_nonce($nonce, 'ctwp_backup_nonce')) {
      die(esc_html__('Error: Invalid nonce verification.', CTWP_DOMAIN));
    }
    if (empty($unique)) {
      die(esc_html__('Error: Invalid key.', CTWP_DOMAIN));
    }
    header('Content-Type: application/json');
    header('Content-disposition: attachment; filename=backup-'. gmdate('d-m-Y') .'.json');
    header('Content-Transfer-Encoding: binary');
    header('Pragma: no-cache');
    header('Expires: 0');
    echo json_encode(get_option($unique));
    die();
  }
  add_action('wp_ajax_ctwp-export', 'ctwp_export');
}

/**
 * Import Ajax
 */
if (!function_exists('ctwp_import_ajax')) {
  function ctwp_import_ajax() {
    $nonce  = (!empty($_POST[ 'nonce' ])) ? sanitize_text_field(wp_unslash($_POST[ 'nonce' ])) : '';
    $unique = (!empty($_POST[ 'unique' ])) ? sanitize_text_field(wp_unslash($_POST[ 'unique' ])) : '';
    $data   = (!empty($_POST[ 'data' ])) ? wp_kses_post_deep(json_decode(wp_unslash(trim(sanitize_text_field($_POST[ 'data' ]))), true)) : array();
    if (!wp_verify_nonce($nonce, 'ctwp_backup_nonce')) {
      wp_send_json_error(array('error' => esc_html__('Error: Invalid nonce verification.', CTWP_DOMAIN)));
    }
    if (empty($unique)) {
      wp_send_json_error(array('error' => esc_html__('Error: Invalid key.', CTWP_DOMAIN)));
    }
    if (empty($data) || ! is_array($data)) {
      wp_send_json_error(array('error' => esc_html__('Error: The response is not a valid JSON response.', CTWP_DOMAIN)));
    }
    update_option($unique, $data);
    wp_send_json_success();
  }
  add_action('wp_ajax_ctwp-import', 'ctwp_import_ajax');
}

/**
 * Reset Ajax
 */
if (!function_exists('ctwp_reset_ajax')) {
  function ctwp_reset_ajax() {
    $nonce  = (!empty($_POST[ 'nonce' ])) ? sanitize_text_field(wp_unslash($_POST[ 'nonce' ])) : '';
    $unique = (!empty($_POST[ 'unique' ])) ? sanitize_text_field(wp_unslash($_POST[ 'unique' ])) : '';
    if (!wp_verify_nonce($nonce, 'ctwp_backup_nonce')) {
      wp_send_json_error(array('error' => esc_html__('Error: Invalid nonce verification.', CTWP_DOMAIN)));
    }
    delete_option($unique);
    wp_send_json_success();
  }
  add_action('wp_ajax_ctwp-reset', 'ctwp_reset_ajax');
}

/**
 * Chosen Ajax
 */
if (!function_exists('ctwp_chosen_ajax')) {
  function ctwp_chosen_ajax() {
    $nonce = (!empty($_POST[ 'nonce' ])) ? sanitize_text_field(wp_unslash($_POST[ 'nonce' ])) : '';
    $type  = (!empty($_POST[ 'type' ])) ? sanitize_text_field(wp_unslash($_POST[ 'type' ])) : '';
    $term  = (!empty($_POST[ 'term' ])) ? sanitize_text_field(wp_unslash($_POST[ 'term' ])) : '';
    $query = (!empty($_POST[ 'query_args' ])) ? wp_kses_post_deep(sanitize_text_field($_POST[ 'query_args' ])) : array();
    if (!wp_verify_nonce($nonce, 'ctwp_chosen_ajax_nonce')) {
      wp_send_json_error(array('error' => esc_html__('Error: Invalid nonce verification.', CTWP_DOMAIN)));
    }
    if (empty($type) || empty($term)) {
      wp_send_json_error(array('error' => esc_html__('Error: Invalid term ID.', CTWP_DOMAIN)));
    }
    $capability = apply_filters('ctwp_chosen_ajax_capability', 'manage_options');
    if (!current_user_can($capability)) {
      wp_send_json_error(array('error' => esc_html__('Error: You do not have permission to do that.', CTWP_DOMAIN)));
    }
    $options = CTWP_Fields::field_data($type, $term, $query);
    wp_send_json_success($options);
  }
  add_action('wp_ajax_ctwp-chosen', 'ctwp_chosen_ajax');
}
