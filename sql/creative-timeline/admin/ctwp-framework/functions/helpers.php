<?php
if (!defined('ABSPATH')) { // Cannot access directly.
  die;
}
/**
 *
 * Array Search Key & Value
 *
 * @since 1.0.0
 * @version 1.0.0
 *
 */
if (!function_exists('ctwp_array_search')) {
  function ctwp_array_search($array, $key, $value) {
    $results = array();
    if (is_array($array)) {
      if (isset($array[$key]) && $array[$key] == $value) {
        $results[] = $array;
      }
      foreach ($array as $sub_array) {
        $results = array_merge($results, ctwp_array_search($sub_array, $key, $value));
      }
    }
    return $results;
  }
}

/**
 * Between Microtime
 */
if (!function_exists('ctwp_timeout')) {
  function ctwp_timeout($timenow, $starttime, $timeout = 30) {
    return (($timenow - $starttime) < $timeout) ? true : false;
  }
}

/**
 * Check for WP Editor API
 */
if (!function_exists('ctwp_wp_editor_api')) {
  function ctwp_wp_editor_api() {
    global $wp_version;
    return version_compare($wp_version, '4.8', '>=');
  }
}
