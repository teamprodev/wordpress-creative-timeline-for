<?php
if (!defined('ABSPATH')) { // Cannot access directly.
  die;
}
/**
 *
 * Field: Gallery
 *
 * @since 1.0.0
 * @version 1.0.0
 *
 */
if (!class_exists('CTWP_Field_gallery')) {
  class CTWP_Field_gallery extends CTWP_Fields {
    public function __construct($field, $value = '', $unique = '', $where = '', $parent = '') {
      parent::__construct($field, $value, $unique, $where, $parent);
    }
    public function render() {
      $args = wp_parse_args($this->field, array(
        'add_title'   => esc_html__('Add Gallery', CTWP_DOMAIN),
        'edit_title'  => esc_html__('Edit Gallery', CTWP_DOMAIN),
        'clear_title' => esc_html__('Clear', CTWP_DOMAIN),
      ));
      $hidden = (empty($this->value)) ? ' hidden' : '';
      echo $this->field_before();
      echo '<ul>';
      if (!empty($this->value)) {
        $values = explode(',', $this->value);
        foreach ($values as $id) {
          $attachment = wp_get_attachment_image_src($id, 'thumbnail');
          echo '<li><img src="'. esc_url($attachment[0]) .'" /></li>';
        }
      }
      echo '</ul>';
      echo '<a href="#" class="button button-primary ctwp-button">'. $args['add_title'] .'</a>';
      echo '<a href="#" class="button ctwp-edit-gallery'. esc_attr($hidden) .'">'. $args['edit_title'] .'</a>';
      echo '<a href="#" class="button ctwp-warning-primary ctwp-clear-gallery'. esc_attr($hidden) .'">'. $args['clear_title'] .'</a>';
      echo '<input type="hidden" name="'. esc_attr($this->field_name()) .'" value="'. esc_attr($this->value) .'"'. $this->field_attributes() .'/>';
      echo $this->field_after();
    }
  }
}
