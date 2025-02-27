<?php
if (!defined('ABSPATH')) { // Cannot access directly.
  die;
}
/**
 *
 * Validate Email Address
 *
 * @since 1.0.0
 * @version 1.0.0
 *
 */
if (!function_exists('ctwp_validate_email')) {
  function ctwp_validate_email($value) {
    if (!filter_var($value, FILTER_VALIDATE_EMAIL)) {
      return esc_html__('Please enter a valid email address.', CTWP_DOMAIN);
    }
  }
}

/**
 *
 * Validate Numeric Value
 *
 * @since 1.0.0
 * @version 1.0.0
 *
 */
if (!function_exists('ctwp_validate_numeric')) {
  function ctwp_validate_numeric($value) {
    if (!is_numeric($value)) {
      return esc_html__('Please enter a valid number.', CTWP_DOMAIN);
    }
  }
}

/**
 *
 * Required validate
 *
 * @since 1.0.0
 * @version 1.0.0
 *
 */
if (!function_exists('ctwp_validate_required')) {
  function ctwp_validate_required($value) {
    if (empty($value)) {
      return esc_html__('This field is required.', CTWP_DOMAIN);
    }
  }
}

/**
 *
 * URL Validate
 *
 * @since 1.0.0
 * @version 1.0.0
 *
 */
if (!function_exists('ctwp_validate_url')) {
  function ctwp_validate_url($value) {
    if (!filter_var($value, FILTER_VALIDATE_URL)) {
      return esc_html__('Please enter a valid URL.', CTWP_DOMAIN);
    }
  }
}

/**
 *
 * Email validate for Customizer
 *
 * @since 1.0.0
 * @version 1.0.0
 *
 */
if (!function_exists('ctwp_customize_validate_email')) {
  function ctwp_customize_validate_email($validity, $value, $wp_customize) {
    if (!sanitize_email($value)) {
      $validity->add('required', esc_html__('Please enter a valid email address.', CTWP_DOMAIN));
    }
    return $validity;
  }
}

/**
 *
 * Numeric validate for Customizer
 *
 * @since 1.0.0
 * @version 1.0.0
 *
 */
if (!function_exists('ctwp_customize_validate_numeric')) {
  function ctwp_customize_validate_numeric($validity, $value, $wp_customize) {
    if (!is_numeric($value)) {
      $validity->add('required', esc_html__('Please enter a valid number.', CTWP_DOMAIN));
    }
    return $validity;
  }
}

/**
 *
 * Required validate for Customizer
 *
 * @since 1.0.0
 * @version 1.0.0
 *
 */
if (!function_exists('ctwp_customize_validate_required')) {
  function ctwp_customize_validate_required($validity, $value, $wp_customize) {
    if (empty($value)) {
      $validity->add('required', esc_html__('This field is required.', CTWP_DOMAIN));
    }
    return $validity;
  }
}

/**
 *
 * URL validate for Customizer
 *
 * @since 1.0.0
 * @version 1.0.0
 *
 */
if (!function_exists('ctwp_customize_validate_url')) {
  function ctwp_customize_validate_url($validity, $value, $wp_customize) {
    if (!filter_var($value, FILTER_VALIDATE_URL)) {
      $validity->add('required', esc_html__('Please enter a valid URL.', CTWP_DOMAIN));
    }
    return $validity;
  }
}
