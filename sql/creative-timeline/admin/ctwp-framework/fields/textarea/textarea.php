<?php
if (!defined('ABSPATH')) { // Cannot access directly.
  die;
}
/**
 *
 * Field: Textarea
 *
 * @since 1.0.0
 * @version 1.0.0
 *
 */
if (!class_exists('CTWP_Field_textarea')) {
  class CTWP_Field_textarea extends CTWP_Fields {
    public function __construct($field, $value = '', $unique = '', $where = '', $parent = '') {
      parent::__construct($field, $value, $unique, $where, $parent);
    }
    public function render() {
      echo $this->field_before();
      echo $this->shortcoder();
      echo '<textarea name="'. esc_attr($this->field_name()) .'"'. $this->field_attributes() .'>'. $this->value .'</textarea>';
      echo $this->field_after();
    }
    public function shortcoder() {
      if (!empty($this->field['shortcoder'])) {
        $instances = (is_array($this->field['shortcoder'])) ? $this->field['shortcoder'] : array_filter((array) $this->field['shortcoder']);
        foreach ($instances as $instance_key) {
          if (isset(CTWP::$shortcode_instances[$instance_key])) {
            $button_title = CTWP::$shortcode_instances[$instance_key]['button_title'];
            echo '<a href="#" class="button button-primary ctwp-shortcode-button" data-modal-id="'. esc_attr($instance_key) .'">'. $button_title .'</a>';
          }
        }
      }
    }
  }
}
