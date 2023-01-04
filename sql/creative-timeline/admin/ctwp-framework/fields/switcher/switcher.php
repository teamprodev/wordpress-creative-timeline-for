<?php
if (!defined('ABSPATH')) { // Cannot access directly.
  die;
}
/**
 *
 * Field: Switcher
 *
 * @since 1.0.0
 * @version 1.0.0
 *
 */
if (!class_exists('CTWP_Field_switcher')) {
  class CTWP_Field_switcher extends CTWP_Fields {
    public function __construct($field, $value = '', $unique = '', $where = '', $parent = '') {
      parent::__construct($field, $value, $unique, $where, $parent);
    }
    public function render() {
      $active     = (!empty($this->value)) ? ' ctwp--active' : '';
      $text_on    = (!empty($this->field['text_on'])) ? $this->field['text_on'] : esc_html__('On', CTWP_DOMAIN);
      $text_off   = (!empty($this->field['text_off'])) ? $this->field['text_off'] : esc_html__('Off', CTWP_DOMAIN);
      $text_width = (!empty($this->field['text_width'])) ? ' style="width: '. esc_attr($this->field['text_width']) .'px;"': '';
      echo $this->field_before();
      echo '<div class="ctwp--switcher'. esc_attr($active) .'"'. $text_width .'>';
      echo '<span class="ctwp--on">'. esc_attr($text_on) .'</span>';
      echo '<span class="ctwp--off">'. esc_attr($text_off) .'</span>';
      echo '<span class="ctwp--ball"></span>';
      echo '<input type="hidden" name="'. esc_attr($this->field_name()) .'" value="'. esc_attr($this->value) .'"'. $this->field_attributes() .' />';
      echo '</div>';
      echo (!empty($this->field['label'])) ? '<span class="ctwp--label">'. esc_attr($this->field['label']) . '</span>' : '';
      echo $this->field_after();
    }
  }
}
