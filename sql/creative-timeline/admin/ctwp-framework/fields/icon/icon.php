<?php
if (!defined('ABSPATH')) { // Cannot access directly.
  die;
}
/**
 *
 * Field: Icon
 *
 * @since 1.0.0
 * @version 1.0.0
 *
 */
if (!class_exists('CTWP_Field_icon')) {
  class CTWP_Field_icon extends CTWP_Fields {
    public function __construct($field, $value = '', $unique = '', $where = '', $parent = '') {
      parent::__construct($field, $value, $unique, $where, $parent);
    }
    public function render() {
      $args = wp_parse_args($this->field, array(
        'button_title' => esc_html__('Add Icon', CTWP_DOMAIN),
        'remove_title' => esc_html__('Remove Icon', CTWP_DOMAIN),
    ));
      echo $this->field_before();
      $nonce  = wp_create_nonce('ctwp_icon_nonce');
      $hidden = (empty($this->value)) ? ' hidden' : '';
      echo '<div class="ctwp-icon-select">';
      echo '<span class="ctwp-icon-preview'. esc_attr($hidden) .'"><i class="'. esc_attr($this->value) .'"></i></span>';
      echo '<a href="#" class="button button-primary ctwp-icon-add" data-nonce="'. esc_attr($nonce) .'">'. $args['button_title'] .'</a>';
      echo '<a href="#" class="button ctwp-warning-primary ctwp-icon-remove'. esc_attr($hidden) .'">'. $args['remove_title'] .'</a>';
      echo '<input type="hidden" name="'. esc_attr($this->field_name()) .'" value="'. esc_attr($this->value) .'" class="ctwp-icon-value"'. $this->field_attributes() .' />';
      echo '</div>';
      echo $this->field_after();
    }
    public function enqueue() {
      add_action('admin_footer', array('CTWP_Field_icon', 'add_footer_modal_icon'));
      add_action('customize_controls_print_footer_scripts', array('CTWP_Field_icon', 'add_footer_modal_icon'));
    }

    public static function add_footer_modal_icon() { ?>
      <div id="ctwp-modal-icon" class="ctwp-modal ctwp-modal-icon hidden">
        <div class="ctwp-modal-table">
          <div class="ctwp-modal-table-cell">
            <div class="ctwp-modal-overlay"></div>
            <div class="ctwp-modal-inner">
              <div class="ctwp-modal-title">
                <?php esc_html_e('Add Icon', CTWP_DOMAIN); ?>
                <div class="ctwp-modal-close ctwp-icon-close"></div>
              </div>
              <div class="ctwp-modal-header">
                <input type="text" placeholder="<?php esc_html_e('Search...', CTWP_DOMAIN); ?>" class="ctwp-icon-search" />
              </div>
              <div class="ctwp-modal-content">
                <div class="ctwp-modal-loading"><div class="ctwp-loading"></div></div>
                <div class="ctwp-modal-load"></div>
              </div>
            </div>
          </div>
        </div>
      </div>
    <?php }
  }
}
