<?php
if (!defined('ABSPATH')) { // Cannot access directly.
  die;
}
/**
 *
 * Field: Submessage
 *
 * @since 1.0.0
 * @version 1.0.0
 *
 */
if (!class_exists('CTWP_Field_submessage')) {
  class CTWP_Field_submessage extends CTWP_Fields {
    public function __construct($field, $value = '', $unique = '', $where = '', $parent = '') {
      parent::__construct($field, $value, $unique, $where, $parent);
    }
    public function render() {
      $style = (!empty($this->field['style'])) ? $this->field['style'] : 'normal';
      echo '<div class="ctwp-submessage ctwp-submessage-'. esc_attr($style) .'">'. $this->field['content'] .'</div>';
    }
  }
}
