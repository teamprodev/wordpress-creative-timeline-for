<?php
if (!defined('ABSPATH')) { // Cannot access directly.
  die;
} 
/**
 *
 * Shortcode Class
 *
 * @since 1.0.0
 * @version 1.0.0
 *
 */
if (!class_exists('CTWP_Shortcoder')) {
  class CTWP_Shortcoder extends CTWP_Abstract{
    public $unique       = '';
    public $abstract     = 'shortcoder';
    public $blocks       = array();
    public $sections     = array();
    public $pre_tabs     = array();
    public $pre_sections = array();
    public $args         = array(
      'button_title'     => 'Add Shortcode',
      'select_title'     => 'Select a shortcode',
      'insert_title'     => 'Insert Shortcode',
      'show_in_editor'   => true,
      'show_in_custom'   => false,
      'defaults'         => array(),
      'class'            => '',
    );

    public function __construct($key, $params = array()) {
      $this->unique       = $key;
      $this->args         = apply_filters("ctwp_{$this->unique}_args", wp_parse_args($params['args'], $this->args), $this);
      $this->sections     = apply_filters("ctwp_{$this->unique}_sections", $params['sections'], $this);
      $this->pre_tabs     = $this->pre_tabs($this->sections);
      $this->pre_sections = $this->pre_sections($this->sections);
      add_action('admin_footer', array($this, 'add_footer_modal_shortcode'));
      add_action('customize_controls_print_footer_scripts', array($this, 'add_footer_modal_shortcode'));
      add_action('wp_ajax_ctwp-get-shortcode-'. $this->unique, array($this, 'get_shortcode'));
      if (!empty($this->args['show_in_editor'])) {
        CTWP::$shortcode_instances[$this->unique] = wp_parse_args(array('hash' => md5($key), 'modal_id' => $this->unique), $this->args);
      }
    }

    public static function instance($key, $params = array()) {
      return new self($key, $params);
    }

    public function pre_tabs($sections) {
      $result  = array();
      $parents = array();
      $count   = 100;
      foreach ($sections as $key => $section) {
        if (!empty($section['parent'])) {
          $section['priority'] = (isset($section['priority'])) ? $section['priority'] : $count;
          $parents[$section['parent']][] = $section;
          unset($sections[$key]);
        }
        $count++;
      }
      foreach ($sections as $key => $section) {
        $section['priority'] = (isset($section['priority'])) ? $section['priority'] : $count;
        if (!empty($section['id']) && !empty($parents[$section['id']])) {
          $section['subs'] = wp_list_sort($parents[$section['id']], array('priority' => 'ASC'), 'ASC', true);
        }
        $result[] = $section;
        $count++;
      }
      return wp_list_sort($result, array('priority' => 'ASC'), 'ASC', true);
    }

    public function pre_sections($sections) {
      $result = array();
      foreach ($this->pre_tabs as $tab) {
        if (!empty($tab['subs'])) {
          foreach ($tab['subs'] as $sub) {
            $result[] = $sub;
          }
        }
        if (empty($tab['subs'])) {
          $result[] = $tab;
        }
      }
      return $result;
    }

    public function get_default($field) {
      $default = (isset($field['default'])) ? $field['default'] : '';
      $default = (isset($this->args['defaults'][$field['id']])) ? $this->args['defaults'][$field['id']] : $default;
      return $default;
    }
    public function add_footer_modal_shortcode() {
      if(!wp_script_is(CTWP_DOMAIN)) {
        return;
      }
      $class        = ($this->args['class']) ? ' '. esc_attr($this->args['class']) : '';
      $has_select   = (count($this->pre_tabs) > 1) ? true : false;
      $single_usage = (!$has_select) ? ' ctwp-shortcode-single' : '';
      $hide_header  = (!$has_select) ? ' hidden' : '';
    ?>
      <div id="ctwp-modal-<?php echo esc_attr($this->unique); ?>" class="wp-core-ui ctwp-modal ctwp-shortcode hidden<?php echo esc_attr($single_usage . $class); ?>" data-modal-id="<?php echo esc_attr($this->unique); ?>" data-nonce="<?php echo esc_attr(wp_create_nonce('ctwp_shortcode_nonce')); ?>">
        <div class="ctwp-modal-table">
          <div class="ctwp-modal-table-cell">
            <div class="ctwp-modal-overlay"></div>
            <div class="ctwp-modal-inner">
              <div class="ctwp-modal-title">
                <?php echo esc_attr($this->args['button_title'], CTWP_DOMAIN ); ?>
                <div class="ctwp-modal-close"></div>
              </div>
              <?php
                echo '<div class="ctwp-modal-header'. esc_attr($hide_header) .'">';
                echo '<select>';
                echo ($has_select) ? '<option value="">'. esc_attr($this->args['select_title'], CTWP_DOMAIN) .'</option>' : '';
                $tab_key = 1;
                foreach ($this->pre_tabs as $tab) {
                  if (!empty($tab['subs'])) {
                    echo '<optgroup label="'. esc_attr($tab['title']) .'">';
                    foreach ($tab['subs'] as $sub) {
                      $view      = (!empty($sub['view'])) ? ' data-view="'. esc_attr($sub['view']) .'"' : '';
                      $shortcode = (!empty($sub['shortcode'])) ? ' data-shortcode="'. esc_attr($sub['shortcode']) .'"' : '';
                      $group     = (!empty($sub['group_shortcode'])) ? ' data-group="'. esc_attr($sub['group_shortcode']) .'"' : '';
                      echo '<option value="'. esc_attr($tab_key) .'"'. $view . $shortcode . $group .'>'. esc_attr($sub['title']) .'</option>';
                      $tab_key++;
                    }
                    echo '</optgroup>' ;
                  } else {
                      $view      = (!empty($tab['view'])) ? ' data-view="'. esc_attr($tab['view']) .'"' : '';
                      $shortcode = (!empty($tab['shortcode'])) ? ' data-shortcode="'. esc_attr($tab['shortcode']) .'"' : '';
                      $group     = (!empty($tab['group_shortcode'])) ? ' data-group="'. esc_attr($tab['group_shortcode']) .'"' : '';
                      echo '<option value="'. esc_attr($tab_key) .'"'. $view . $shortcode . $group .'>'. esc_attr($tab['title']) .'</option>';
                    $tab_key++;
                  }
                }
                echo '</select>';
                echo '</div>';
              ?>
              <div class="ctwp-modal-content">
                <div class="ctwp-modal-loading"><div class="ctwp-loading"></div></div>
                <div class="ctwp-modal-load"></div>
              </div>
              <div class="ctwp-modal-insert-wrapper hidden"><a href="#" class="button button-primary ctwp-modal-insert"><?php echo esc_attr($this->args['insert_title'], CTWP_DOMAIN); ?></a></div>
            </div>
          </div>
        </div>
      </div>
    <?php
    }

    public function get_shortcode() {
      ob_start();
      $nonce         = (!empty($_POST[ 'nonce' ])) ? sanitize_text_field(wp_unslash($_POST[ 'nonce' ])) : '';
      $shortcode_key = (!empty($_POST[ 'shortcode_key' ])) ? sanitize_text_field(wp_unslash($_POST[ 'shortcode_key' ])) : '';
      if (!empty($shortcode_key) && wp_verify_nonce($nonce, 'ctwp_shortcode_nonce')) {
        $unallows  = array('group', 'repeater', 'sorter');
        $section   = $this->pre_sections[$shortcode_key-1];
        $shortcode = (!empty($section['shortcode'])) ? $section['shortcode'] : '';
        $view      = (!empty($section['view'])) ? $section['view'] : 'normal';
        if (!empty($section)) {
          if (!empty($section['fields']) && $view !== 'repeater') {
            echo '<div class="ctwp-fields">';
            echo (!empty($section['description'])) ? '<div class="ctwp-field ctwp-section-description">'. $section['description'] .'</div>' : '';
            foreach ($section['fields'] as $field) {
              if (in_array($field['type'], $unallows)) { $field['_notice'] = true; }
              $field['tag_prefix'] = (!empty($field['tag_prefix'])) ? $field['tag_prefix'] .'_' : '';
              $field_default = (isset($field['id'])) ? $this->get_default($field) : '';
              CTWP::field($field, $field_default, $shortcode, 'shortcode');
            }
            echo '</div>';
          }

          $repeatable_fields = ($view === 'repeater' && !empty($section['fields'])) ? $section['fields'] : array();
          $repeatable_fields = ($view === 'group' && !empty($section['group_fields'])) ? $section['group_fields'] : $repeatable_fields;
          if (!empty($repeatable_fields)) {
            $button_title    = (!empty($section['button_title'])) ? ' '. $section['button_title'] : esc_html__('Add New', CTWP_DOMAIN);
            $inner_shortcode = (!empty($section['group_shortcode'])) ? $section['group_shortcode'] : $shortcode;
            echo '<div class="ctwp--repeatable">';
              echo '<div class="ctwp--repeat-shortcode">';
                echo '<div class="ctwp-repeat-remove fas fa-times"></div>';
                echo '<div class="ctwp-fields">';
                foreach ($repeatable_fields as $field) {
                  if (in_array($field['type'], $unallows)) { $field['_notice'] = true; }
                  $field['tag_prefix'] = (!empty($field['tag_prefix'])) ? $field['tag_prefix'] .'_' : '';
                  $field_default = (isset($field['id'])) ? $this->get_default($field) : '';
                  CTWP::field($field, $field_default, $inner_shortcode.'[0]', 'shortcode');
                }
                echo '</div>';
              echo '</div>';
            echo '</div>';
            echo '<div class="ctwp--repeat-button-block"><a class="button ctwp--repeat-button" href="#"><i class="fas fa-plus-circle"></i> '. $button_title .'</a></div>';
          }
        }
      } else {
        echo '<div class="ctwp-field ctwp-error-text">'. esc_html__('Error: Invalid nonce verification.', CTWP_DOMAIN) .'</div>';
      }
      wp_send_json_success(array('content' => ob_get_clean()));
    }

    /**
     * Add shortcode button in WP Editor
     */
    public static function once_editor_setup() {
      if (ctwp_wp_editor_api()) {
        add_action('media_buttons', array('CTWP_Shortcoder', 'add_media_buttons'));
      }
    }

    /**
     * Add media buttons
     */
    public static function add_media_buttons($editor_id) {
      foreach (CTWP::$shortcode_instances as $value) {
        echo '<a href="#" class="button button-primary ctwp-shortcode-button" data-editor-id="'. esc_attr($editor_id) .'" data-modal-id="'. esc_attr($value['modal_id']) .'">'. $value['button_title'] .'</a>';
      }
    }
  }
}
