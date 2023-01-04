<?php
if (!defined('ABSPATH')) { // Cannot access directly.
  die;
}
/**
 *
 * Replace letter a to letter b
 *
 * @since 1.0.0
 * @version 1.0.0
 *
 */
if (!function_exists('ctwp_sanitize_replace_a_to_b')) {
  function ctwp_sanitize_replace_a_to_b($value) {
    return str_replace('a', 'b', $value);
  }
}

/**
 *
 * Sanitize title
 *
 * @since 1.0.0
 * @version 1.0.0
 *
 */
if (!function_exists('ctwp_sanitize_title')) {
  function ctwp_sanitize_title($value) {
    return sanitize_title($value);
  }
}
