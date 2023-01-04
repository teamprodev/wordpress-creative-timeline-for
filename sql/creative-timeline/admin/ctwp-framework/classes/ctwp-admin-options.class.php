<?php
if (!defined('ABSPATH')) { // Cannot access directly.
    die;
}
/**
 *
 * Options Class
 *
 * @since 1.0.0
 * @version 1.0.0
 *
 */
if (!class_exists('CTWP_Options')) {
    class CTWP_Options extends CTWP_Abstract {
        public $unique       = '';
        public $notice       = '';
        public $abstract     = 'options';
        public $sections     = array();
        public $options      = array();
        public $errors       = array();
        public $pre_tabs     = array();
        public $pre_fields   = array();
        public $pre_sections = array();
        public $args         = array(
            // Framework Title
            'framework_title' => 'CTWP Framework <small>by CTWP</small>',
            'framework_class' => '',

            // Menu Settings
            'menu_title'      => '',
            'menu_slug'       => '',
            'menu_type'       => 'menu',
            'menu_capability' => 'manage_options',
            'menu_icon'       => null,
            'menu_position'   => null,
            'menu_hidden'     => false,
            'menu_parent'     => '',
            'sub_menu_title'  => '',

            // Menu Extras
            'show_bar_menu'      => true,
            'show_sub_menu'      => true,
            'show_in_network'    => true,
            'show_in_customizer' => false,

            'show_search'        => true,
            'show_reset_all'     => true,
            'show_reset_section' => true,
            'show_footer'        => true,
            'show_all_options'   => true,
            'show_form_warning'  => true,
            'sticky_header'      => true,
            'save_defaults'      => true,
            'ajax_save'          => true,
            'form_action'        => '',

            // Admin bar menu settings
            'admin_bar_menu_icon'     => '',
            'admin_bar_menu_priority' => 50,

            // Footer
            'footer_text'   => '',
            'footer_after'  => '',
            'footer_credit' => '',

            // Database model
            'database'       => '',
            'transient_time' => 0,

            // Contextual help
            'contextual_help'         => array() ,
            'contextual_help_sidebar' => '',

            // Typography options
            'enqueue_webfont' => true,
            'async_webfont'   => false,

            // Others
            'output_css' => true,

            // Theme
            'nav'   => 'normal',
            'theme' => 'dark',
            'class' => '',

            // External default values
            'defaults' => array() ,
        );

        /**
         * Run framework construct
         */
        public function __construct($key, $params = array()) {
            $this->unique       = $key;
            $this->args         = apply_filters("ctwp_{$this->unique}_args", wp_parse_args($params['args'], $this->args) , $this);
            $this->sections     = apply_filters("ctwp_{$this->unique}_sections", $params['sections'], $this);
            $this->pre_tabs     = $this->pre_tabs($this->sections);
            $this->pre_fields   = $this->pre_fields($this->sections);
            $this->pre_sections = $this->pre_sections($this->sections);
            $this->get_options();
            $this->set_options();
            $this->save_defaults();
            add_action('admin_menu', array(
                $this,
                'add_admin_menu'
            ));
            add_action('admin_bar_menu', array(
                $this,
                'add_admin_bar_menu'
            ) , $this->args['admin_bar_menu_priority']);
            add_action('wp_ajax_ctwp_' . $this->unique . '_ajax_save', array(
                $this,
                'ajax_save'
            ));
            if ($this->args['database'] === 'network' && !empty($this->args['show_in_network'])) {
                add_action('network_admin_menu', array(
                    $this,
                    'add_admin_menu'
                ));
            }
            parent::__construct();
        }

        /**
         * Instance
         */
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
                    $section['subs'] = wp_list_sort($parents[$section['id']], array(
                        'priority' => 'ASC'
                    ) , 'ASC', true);
                }
                $result[] = $section;
                $count++;
            }
            return wp_list_sort($result, array(
                'priority' => 'ASC'
            ) , 'ASC', true);
        }

        public function pre_fields($sections) {
            $result = array();
            foreach ($sections as $key => $section) {
                if (!empty($section['fields'])) {
                    foreach ($section['fields'] as $field) {
                        $result[] = $field;
                    }
                }
            }
            return $result;
        }

        public function pre_sections($sections) {
            $result = array();
            foreach ($this->pre_tabs as $tab) {
                if (!empty($tab['subs'])) {
                    foreach ($tab['subs'] as $sub) {
                        $sub['ptitle'] = $tab['title'];
                        $result[] = $sub;
                    }
                }
                if (empty($tab['subs'])) {
                    $result[] = $tab;
                }
            }
            return $result;
        }

        /**
         * Add admin bar menu
         */
        public function add_admin_bar_menu($wp_admin_bar) {
            if (is_network_admin() && ($this->args['database'] !== 'network' || $this->args['show_in_network'] !== true)) {
                return;
            }
            if (!empty($this->args['show_bar_menu']) && empty($this->args['menu_hidden'])) {
                global $submenu;
                $menu_slug = $this->args['menu_slug'];
                $menu_icon = (!empty($this->args['admin_bar_menu_icon'])) ? '<span class="ctwp-ab-icon ab-icon ' . esc_attr($this->args['admin_bar_menu_icon']) . '"></span>' : '';
                $wp_admin_bar->add_node(array(
                    'id'    => $menu_slug,
                    'title' => $menu_icon . esc_attr($this->args['menu_title']) ,
                    'href'  => esc_url((is_network_admin()) ? network_admin_url('admin.php?page=' . $menu_slug) : admin_url('admin.php?page=' . $menu_slug)) ,
                ));
                if (!empty($submenu[$menu_slug])) {
                    foreach ($submenu[$menu_slug] as $menu_key => $menu_value) {
                        $wp_admin_bar->add_node(array(
                            'parent' => $menu_slug,
                            'id'     => $menu_slug . '-' . $menu_key,
                            'title'  => $menu_value[0],
                            'href'   => esc_url((is_network_admin()) ? network_admin_url('admin.php?page=' . $menu_value[2]) : admin_url('admin.php?page=' . $menu_value[2])) ,
                        ));
                    }
                }
            }
        }

        public function ajax_save() {
            $result = $this->set_options(true);
            if (!$result) {
                wp_send_json_error(array(
                    'error' => esc_html__('Error while saving the changes.', CTWP_DOMAIN)
                ));
            }
            else {
                wp_send_json_success(array(
                    'notice' => $this->notice,
                    'errors' => $this->errors
                ));
            }
        }
        public function get_default($field) {
            $default = (isset($field['default'])) ? $field['default'] : '';
            $default = (isset($this->args['defaults'][$field['id']])) ? $this->args['defaults'][$field['id']] : $default;
            return $default;
        }
        public function save_defaults() {
            $tmp_options = $this->options;
            foreach ($this->pre_fields as $field) {
                if (!empty($field['id'])) {
                    $this->options[$field['id']] = (isset($this->options[$field['id']])) ? $this->options[$field['id']] : $this->get_default($field);
                }
            }
            if ($this->args['save_defaults'] && empty($tmp_options)) {
                $this->save_options($this->options);
            }
        }

        public function set_options($ajax = false) {
            $response = ($ajax && !empty($_POST['data'])) ? json_decode(wp_unslash(trim($_POST['data'])) , true) : $_POST;
            $data = array();
            $noncekey = 'ctwp_options_nonce' . $this->unique;
            $nonce = (!empty($response[$noncekey])) ? $response[$noncekey] : '';
            $options = (!empty($response[$this->unique])) ? $response[$this->unique] : array();
            $transient = (!empty($response['ctwp_transient'])) ? $response['ctwp_transient'] : array();
            if (wp_verify_nonce($nonce, 'ctwp_options_nonce')) {
                $importing = false;
                $section_id = (!empty($transient['section'])) ? $transient['section'] : '';
                if (!$ajax && !empty($response['ctwp_import_data'])) {
                    $import_data = json_decode(wp_unslash(trim($response['ctwp_import_data'])) , true);
                    $options = (is_array($import_data) && !empty($import_data)) ? $import_data : array();
                    $importing = true;
                    $this->notice = esc_html__('Settings successfully imported.', CTWP_DOMAIN);
                }
                if (!empty($transient['reset'])) {
                    foreach ($this->pre_fields as $field) {
                        if (!empty($field['id'])) {
                            $data[$field['id']] = $this->get_default($field);
                        }
                    }
                    $this->notice = esc_html__('Default settings restored.', CTWP_DOMAIN);
                }
                else if (!empty($transient['reset_section']) && !empty($section_id)) {
                    if (!empty($this->pre_sections[$section_id - 1]['fields'])) {
                        foreach ($this->pre_sections[$section_id - 1]['fields'] as $field) {
                            if (!empty($field['id'])) {
                                $data[$field['id']] = $this->get_default($field);
                            }
                        }
                    }
                    $data = wp_parse_args($data, $this->options);
                    $this->notice = esc_html__('Default settings restored.', CTWP_DOMAIN);
                }
                else {
                    
                    /**
                     * Sanitize and validate
                     */
                    foreach ($this->pre_fields as $field) {
                        if (!empty($field['id'])) {
                            $field_id = $field['id'];
                            $field_value = isset($options[$field_id]) ? $options[$field_id] : '';
                            if (!$ajax && !$importing) {
                                $field_value = wp_unslash($field_value);
                            }
                            if (!isset($field['sanitize'])) {
                                if (is_array($field_value)) {
                                    $data[$field_id] = wp_kses_post_deep($field_value);
                                }
                                else {
                                    $data[$field_id] = wp_kses_post($field_value);
                                }
                            }
                            else if (isset($field['sanitize']) && is_callable($field['sanitize'])) {
                                $data[$field_id] = call_user_func($field['sanitize'], $field_value);
                            }
                            else {
                                $data[$field_id] = $field_value;
                            }
                            if (isset($field['validate']) && is_callable($field['validate'])) {
                                $has_validated = call_user_func($field['validate'], $field_value);
                                if (!empty($has_validated)) {
                                    $data[$field_id] = (isset($this->options[$field_id])) ? $this->options[$field_id] : '';
                                    $this->errors[$field_id] = $has_validated;
                                }
                            }
                        }
                    }
                }
                $data = apply_filters("ctwp_{$this->unique}_save", $data, $this);
                do_action("ctwp_{$this->unique}_save_before", $data, $this);
                $this->options = $data;
                $this->save_options($data);
                do_action("ctwp_{$this->unique}_save_after", $data, $this);
                if (empty($this->notice))
                {
                    $this->notice = esc_html__('Settings saved.', CTWP_DOMAIN);
                }
                return true;
            }
            return false;
        }

        /**
         * Save options database
         */
        public function save_options($data) {
            if ($this->args['database'] === 'transient') {
                set_transient($this->unique, $data, $this->args['transient_time']);
            }
            else if ($this->args['database'] === 'theme_mod') {
                set_theme_mod($this->unique, $data);
            }
            else if ($this->args['database'] === 'network') {
                update_site_option($this->unique, $data);
            }
            else {
                update_option($this->unique, $data);
            }
            do_action("ctwp_{$this->unique}_saved", $data, $this);
        }

        /**
         * Get options from database
         */
        public function get_options() {
            if ($this->args['database'] === 'transient') {
                $this->options = get_transient($this->unique);
            }
            else if ($this->args['database'] === 'theme_mod') {
                $this->options = get_theme_mod($this->unique);
            }
            else if ($this->args['database'] === 'network') {
                $this->options = get_site_option($this->unique);
            }
            else {
                $this->options = get_option($this->unique);
            }
            if (empty($this->options)) {
                $this->options = array();
            }
            return $this->options;
        }

        /**
         * Admin menu
         */
        public function add_admin_menu() {
            extract($this->args);
            if ($menu_type === 'submenu') {
                $menu_page = call_user_func('add_submenu_page', $menu_parent, esc_attr($menu_title) , esc_attr($menu_title) , $menu_capability, $menu_slug, array(
                    $this,
                    'add_options_html'
                ));
            }
            else {
                $menu_page = call_user_func('add_menu_page', esc_attr($menu_title) , esc_attr($menu_title) , $menu_capability, $menu_slug, array(
                    $this,
                    'add_options_html'
                ) , $menu_icon, $menu_position);
                if (!empty($sub_menu_title)) {
                    call_user_func('add_submenu_page', $menu_slug, esc_attr($sub_menu_title) , esc_attr($sub_menu_title) , $menu_capability, $menu_slug, array(
                        $this,
                        'add_options_html'
                    ));
                }
                if (!empty($this->args['show_sub_menu']) && count($this->pre_tabs) > 1) {
                    foreach ($this->pre_tabs as $section) {
                        call_user_func('add_submenu_page', $menu_slug, esc_attr($section['title']) , esc_attr($section['title']) , $menu_capability, $menu_slug . '#tab=' . sanitize_title($section['title']) , '__return_null');
                    }
                    remove_submenu_page($menu_slug, $menu_slug);
                }
                if (!empty($menu_hidden)) {
                    remove_menu_page($menu_slug);
                }
            }
            add_action('load-' . $menu_page, array(
                $this,
                'add_page_on_load'
            ));
        }

        public function add_page_on_load() {
            if (!empty($this->args['contextual_help'])) {
                $screen = get_current_screen();
                foreach ($this->args['contextual_help'] as $tab) {
                    $screen->add_help_tab($tab);
                }
                if (!empty($this->args['contextual_help_sidebar'])) {
                    $screen->set_help_sidebar($this->args['contextual_help_sidebar']);
                }
            }
        }

        public function error_check($sections, $err = '') {
            if (!$this->args['ajax_save']) {
                if (!empty($sections['fields'])) {
                    foreach ($sections['fields'] as $field) {
                        if (!empty($field['id'])) {
                            if (array_key_exists($field['id'], $this->errors)) {
                                $err = '<span class="ctwp-label-error">!</span>';
                            }
                        }
                    }
                }
                if (!empty($sections['subs'])) {
                    foreach ($sections['subs'] as $sub) {
                        $err = $this->error_check($sub, $err);
                    }
                }
                if (!empty($sections['id']) && array_key_exists($sections['id'], $this->errors)) {
                    $err = $this->errors[$sections['id']];
                }
            }
            return $err;
        }

        /**
         * Option page html output
         */
        public function add_options_html() {
            $has_nav = (count($this->pre_tabs) > 1) ? true : false;
            $show_all = (!$has_nav) ? ' ctwp-show-all' : '';
            $ajax_class = ($this->args['ajax_save']) ? ' ctwp-save-ajax' : '';
            $sticky_class = ($this->args['sticky_header']) ? ' ctwp-sticky-header' : '';
            $wrapper_class = ($this->args['framework_class']) ? ' ' . $this->args['framework_class'] : '';
            $theme = ($this->args['theme']) ? ' ctwp-theme-' . $this->args['theme'] : '';
            $class = ($this->args['class']) ? ' ' . $this->args['class'] : '';
            $nav_type = ($this->args['nav'] === 'inline') ? 'inline' : 'normal';
            $form_action = ($this->args['form_action']) ? $this->args['form_action'] : '';
            do_action('ctwp_options_before');
            echo '<div class="ctwp ctwp-options' . esc_attr($theme . $class . $wrapper_class) . '" data-slug="' . esc_attr($this->args['menu_slug']) . '" data-unique="' . esc_attr($this->unique) . '">';
            echo '<div class="ctwp-container">';
            echo '<form method="post" action="' . esc_attr($form_action) . '" enctype="multipart/form-data" id="ctwp-form" autocomplete="off" novalidate="novalidate">';
            echo '<input type="hidden" class="ctwp-section-id" name="ctwp_transient[section]" value="1">';
            wp_nonce_field('ctwp_options_nonce', 'ctwp_options_nonce' . $this->unique);
            echo '<div class="ctwp-header' . esc_attr($sticky_class) . '">';
            echo '<div class="ctwp-header-inner">';
            echo '<div class="csf-header-left">';
            echo '<h1><img style="width:40px;height:40px;vertical-align:middle" src="' . $this->args['menu_icon'] . '" alt="' . esc_html__($this->args['framework_title'], CTWP_DOMAIN) . '">  <span class="settings_title">' . $this->args['framework_title'] . '</span></h1>';
            echo '</div>';
            echo '<div class="ctwp-header-right">';
            $notice_class = (!empty($this->notice)) ? 'ctwp-form-show' : '';
            $notice_text = (!empty($this->notice)) ? $this->notice : '';
            echo '<div class="ctwp-form-result ctwp-form-success ' . esc_attr($notice_class) . '">' . $notice_text . '</div>';
            echo ($this->args['show_form_warning']) ? '<div class="ctwp-form-result ctwp-form-warning">' . esc_html__('You have unsaved changes, save your changes!', CTWP_DOMAIN) . '</div>' : '';
            echo ($has_nav && $this->args['show_all_options']) ? '<div class="ctwp-expand-all" title="' . esc_html__('show all settings', CTWP_DOMAIN) . '"><i class="fas fa-outdent"></i></div>' : '';
            echo ($this->args['show_search']) ? '<div class="ctwp-search"><input type="text" name="ctwp-search" placeholder="' . esc_html__('Search...', CTWP_DOMAIN) . '" autocomplete="off" /></div>' : '';
            echo '<div class="ctwp-buttons">';
            echo '<input type="submit" name="' . esc_attr($this->unique) . '[_nonce][save]" class="button button-primary ctwp-top-save ctwp-save' . esc_attr($ajax_class) . '" value="' . esc_html__('Save', CTWP_DOMAIN) . '" data-save="' . esc_html__('Saving...', CTWP_DOMAIN) . '">';
            echo ($this->args['show_reset_section']) ? '<input type="submit" name="ctwp_transient[reset_section]" class="button button-secondary ctwp-reset-section ctwp-confirm" value="' . esc_html__('Reset Section', CTWP_DOMAIN) . '" data-confirm="' . esc_html__('Are you sure to reset this section options?', CTWP_DOMAIN) . '">' : '';
            echo ($this->args['show_reset_all']) ? '<input type="submit" name="ctwp_transient[reset]" class="button ctwp-warning-primary ctwp-reset-all ctwp-confirm" value="' . (($this->args['show_reset_section']) ? esc_html__('Reset All', CTWP_DOMAIN) : esc_html__('Reset', CTWP_DOMAIN)) . '" data-confirm="' . esc_html__('Are you sure you want to reset all settings to default values?', CTWP_DOMAIN) . '">' : '';
            echo '</div>';
            echo '</div>';
            echo '<div class="clear"></div>';
            echo '</div>';
            echo '</div>';
            echo '<div class="ctwp-wrapper' . esc_attr($show_all) . '">';

            if ($has_nav) {
                echo '<div class="ctwp-nav ctwp-nav-' . esc_attr($nav_type) . ' ctwp-nav-options">';
                echo '<ul>';
                foreach ($this->pre_tabs as $tab) {
                    $tab_id = sanitize_title($tab['title']);
                    $tab_error = $this->error_check($tab);
                    $tab_icon = (!empty($tab['icon'])) ? '<i class="ctwp-tab-icon ' . esc_attr($tab['icon']) . '"></i>' : '';
                    if (!empty($tab['subs'])) {
                        echo '<li class="ctwp-tab-item">';
                        echo '<a href="#tab=' . esc_attr($tab_id) . '" data-tab-id="' . esc_attr($tab_id) . '" class="ctwp-arrow">' . $tab_icon . $tab['title'] . $tab_error . '</a>';
                        echo '<ul>';
                        foreach ($tab['subs'] as $sub) {
                            $sub_id = $tab_id . '/' . sanitize_title($sub['title']);
                            $sub_error = $this->error_check($sub);
                            $sub_icon = (!empty($sub['icon'])) ? '<i class="ctwp-tab-icon ' . esc_attr($sub['icon']) . '"></i>' : '';
                            echo '<li><a href="#tab=' . esc_attr($sub_id) . '" data-tab-id="' . esc_attr($sub_id) . '">' . $sub_icon . $sub['title'] . $sub_error . '</a></li>';
                        }
                        echo '</ul>';
                        echo '</li>';
                    }
                    else {
                        echo '<li class="ctwp-tab-item"><a href="#tab=' . esc_attr($tab_id) . '" data-tab-id="' . esc_attr($tab_id) . '">' . $tab_icon . $tab['title'] . $tab_error . '</a></li>';
                    }
                }
                echo '</ul>';
                echo '</div>';
            }
            echo '<div class="ctwp-content">';
            echo '<div class="ctwp-sections">';
            foreach ($this->pre_sections as $section) {
                $section_onload = (!$has_nav) ? ' ctwp-onload' : '';
                $section_class = (!empty($section['class'])) ? ' ' . $section['class'] : '';
                $section_icon = (!empty($section['icon'])) ? '<i class="ctwp-section-icon ' . esc_attr($section['icon']) . '"></i>' : '';
                $section_title = (!empty($section['title'])) ? $section['title'] : '';
                $section_parent = (!empty($section['ptitle'])) ? sanitize_title($section['ptitle']) . '/' : '';
                $section_slug = (!empty($section['title'])) ? sanitize_title($section_title) : '';
                echo '<div class="ctwp-section hidden' . esc_attr($section_onload . $section_class) . '" data-section-id="' . esc_attr($section_parent . $section_slug) . '">';
                echo ($has_nav) ? '<div class="ctwp-section-title"><h3>' . $section_icon . $section_title . '</h3></div>' : '';
                echo (!empty($section['description'])) ? '<div class="ctwp-field ctwp-section-description">' . $section['description'] . '</div>' : '';
                if (!empty($section['fields'])) {
                    foreach ($section['fields'] as $field) {
                        $is_field_error = $this->error_check($field);
                        if (!empty($is_field_error)) {
                            $field['_error'] = $is_field_error;
                        }
                        if (!empty($field['id'])) {
                            $field['default'] = $this->get_default($field);
                        }
                        $value = (!empty($field['id']) && isset($this->options[$field['id']])) ? $this->options[$field['id']] : '';
                        CTWP::field($field, $value, $this->unique, 'options');
                    }
                }
                else {
                    echo '<div class="ctwp-no-option">' . esc_html__('No data available.', CTWP_DOMAIN) . '</div>';
                }
                echo '</div>';
            }
            echo '</div>';
            echo '<div class="clear"></div>';
            echo '</div>';
            echo ($has_nav && $nav_type === 'normal') ? '<div class="ctwp-nav-background"></div>' : '';
            echo '</div>';
            if (!empty($this->args['show_footer'])) {
                echo '<div class="ctwp-footer">';
                echo '<div class="ctwp-buttons">';
                echo '<input type="submit" name="ctwp_transient[save]" class="button button-primary ctwp-save' . esc_attr($ajax_class) . '" value="' . esc_html__('Save', CTWP_DOMAIN) . '" data-save="' . esc_html__('Saving...', CTWP_DOMAIN) . '">';
                echo ($this->args['show_reset_section']) ? '<input type="submit" name="ctwp_transient[reset_section]" class="button button-secondary ctwp-reset-section ctwp-confirm" value="' . esc_html__('Reset Section', CTWP_DOMAIN) . '" data-confirm="' . esc_html__('Are you sure to reset this section options?', CTWP_DOMAIN) . '">' : '';
                echo ($this->args['show_reset_all']) ? '<input type="submit" name="ctwp_transient[reset]" class="button ctwp-warning-primary ctwp-reset-all ctwp-confirm" value="' . (($this->args['show_reset_section']) ? esc_html__('Reset All', CTWP_DOMAIN) : esc_html__('Reset', CTWP_DOMAIN)) . '" data-confirm="' . esc_html__('Are you sure you want to reset all settings to default values?', CTWP_DOMAIN) . '">' : '';
                echo '</div>';
                echo (!empty($this->args['footer_text'])) ? '<div class="ctwp-copyright">' . $this->args['footer_text'] . '</div>' : '';
                echo '<div class="clear"></div>';
                echo '</div>';
            }
            echo '</form>';
            echo '</div>';
            echo '<div class="clear"></div>';
            echo (!empty($this->args['footer_after'])) ? $this->args['footer_after'] : '';
            echo '</div>';
            do_action('ctwp_options_after');
        }
    }
}