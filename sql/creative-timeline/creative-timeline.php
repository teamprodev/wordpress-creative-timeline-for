<?php
/*
 * Plugin Name: Creative Timeline
 * Description: Creative Timeline WordPress Plugin to showcase your timeline in the best way. It is an effective and user-friendly way to beautify your WordPress Custom posts on your website with timeline concept.
 * Plugin URI: https://www.techeshta.com/product/creative-timeline/
 * Author: Techeshta
 * Version: 1.0.1
 * Author URI: https://www.techeshta.com
 *
 * Text Domain: creative-timeline
 */
if (!defined('ABSPATH')) { // Cannot access directly.
    exit;
}

/*
 * Define Plugin URL and Directory Path
 */
define('CTWP_FILE', __FILE__); // Define Plugin FILE
define('CTWP_URL', plugins_url('/', __FILE__));  // Define Plugin URL
define('CTWP_PATH', plugin_dir_path(__FILE__));  // Define Plugin Directory Path
define('CTWP_DOMAIN', 'creative-timeline'); // Define Text Domain

/** 
 * Define Plugin Version
*/
if (!defined('CREATIVE_TIMELINE_CURRENT_VERSION')) {
	define('CREATIVE_TIMELINE_CURRENT_VERSION', '1.0.0');
}

if (!class_exists('CreativeTimeline')) {
	final class CreativeTimeline {
		/**
		 * The unique instance of the plugin.
		 */
		private static $instance;

		/**
		 * Gets an instance of our plugin.
		 */
		public static function get_instance() {
			if (null === self::$instance) {
				self::$instance = new self();
			}
			return self::$instance;
		}

		/**
		 * Register our plugin with WordPress
		 */
		public static function registers() {
			$thisIns = self::$instance;

			/**
			 * Including required files
			 */
			add_action('plugins_loaded', array($thisIns, 'ctwp_include_files'));
			add_action('init', array($thisIns, 'ctwp_flush_rules'));
			
			/**
			 * Loading plugin translation files
			 */
			add_action('plugins_loaded', array($thisIns, 'ctwp_load_plugin_textdomain'));
			add_action('plugins_loaded', array($thisIns, 'ctwp_classic_editor_load'));
			/**
			 * Creative Timeline all hooks integrations
			 */
			if(is_admin()) {
				$pluginpath = plugin_basename(__FILE__);
				/**
				 * plugin settings links hook
				 */
				add_filter("plugin_action_links_$pluginpath", array($thisIns, 'ctwp_settings_link'));
				
				/** 
				 * Action for Save Posts Meta
				 */
				add_action('save_post', array($thisIns,'ctwp_save_story_meta'), 10, 3);
				add_action('admin_menu', array($thisIns,'ctwp_add_new_item'));
			}

			/**
			 * Action Deregister javascript
			 */
			add_action('wp_print_scripts', array($thisIns,'ctwp_deregister_javascript'), 100);

			/**
			 *  Redirect after plugin activation
			 */
			function ctwp_activation_redirect($plugin) {
				
				if($plugin == plugin_basename(__FILE__)) {
					exit(wp_redirect(admin_url('admin.php?page=ctwp-timeline-admin-menu')));
				}
			}
			add_action('activated_plugin', 'ctwp_activation_redirect');
		}

		/**
		 * Create Admin Menu for Creative Timeline
		 */
		public function ctwp_add_new_item() {
			add_submenu_page('ctwp-timeline-admin-menu', esc_html__('Add New Timeline', CTWP_DOMAIN), esc_html__('Add New Timeline', CTWP_DOMAIN), 'manage_options', 'post-new.php?post_type=creative_timeline', false, 15);
			add_submenu_page('ctwp-timeline-admin-menu', esc_html__('Timeline Category', CTWP_DOMAIN), esc_html__('Timeline Category', CTWP_DOMAIN), 'manage_options', 'edit-tags.php?taxonomy=ctwp_category&post_type=creative_timeline', false, 15);
		}

		/*
		* Including required files
		*/
		public function ctwp_include_files() {			
			/**
			 * Register Creative Timeline Custom Post Type
			 */
			require CTWP_PATH . 'admin/ctwp-post-type.php';
			include_once CTWP_PATH . 'includes/ctwp-helper-functions.php';
			/** 
			 * Creative Timeline Main shortcode
			 */
			require CTWP_PATH . 'includes/shortcodes/ctwp-shortcode.php';
			new CTWPShortcode();
			if(is_admin()) {
				/**
				 * Include Files
				 */
				$PostType = CTWP_functions::ctwp_get_ctp();
				require_once CTWP_PATH . 'admin/ctwp-framework/ctwp-framework.php';
				require_once CTWP_PATH . 'admin/ctwp-admin-settings.php';
				require CTWP_PATH .'admin/ctwp-meta-fields.php';					
				require_once __DIR__ . "/admin/ctwp-timeline-admin-menu/ctwp-timeline-admin-menu.php";
				ctwp_timeline_settings_page('timeline', 'ctwp-timeline-admin-menu' , esc_html__('Creative Timeline', CTWP_DOMAIN), esc_html__('Creative Timeline', CTWP_DOMAIN), CTWP_URL . 'assets/images/timeline-icon.svg');
			}
			/** 
			 * Include Shortcode Generator file
			 */
			require_once CTWP_PATH . 'admin/ctwp-shortcode-generator.php';
		} 

		/** 
		 * Flush rewrite rules after activation
		 */
		public function ctwp_flush_rules() {
			if (get_option('ctwp_flush_rewrite_rules_flag')) {
				flush_rewrite_rules();
				delete_option('ctwp_flush_rewrite_rules_flag');
			}
		}

		/**
		 * Loading language files
		 */
		public function ctwp_load_plugin_textdomain() {
			load_plugin_textdomain(CTWP_DOMAIN, FALSE, basename(dirname(__FILE__)) . '/languages/');	
		}

		/**
		 *  Admin Settings page
		 */
		public function ctwp_settings_link($links) {
			$settings_link = '<a href="admin.php?page=creative_timeline_settings">'. esc_html__('Settings', CTWP_DOMAIN) .'</a>';
			array_unshift($links, $settings_link);
			return $links;
		}

		/**
		 * Creative Timeline Meta fields
		 */
		public function ctwp_save_story_meta($post_id, $post, $update) {		
			$post_type = get_post_type($post_id);		
			if ("creative_timeline" != $post_type) return;
			$story_date = sanitize_text_field($_POST['ctwp_post_meta']['story_type']['ctwp_story_date']);	
			if (isset($story_date)) {		
				$story_timestamp = CTWP_functions::ctwp_generate_custom_timestamp($story_date);
				update_post_meta($post_id,'ctwp_story_timestamp',$story_timestamp);
				update_post_meta($post_id,'ctwp_story_based_on','default');	
				update_post_meta($post_id,'ctwp_story_date',$story_date);		
			}
		}

		/**
		* Deregister js
		*/
		public function ctwp_deregister_javascript() {
			if(is_admin()) {
				global $post; 
				$screen = get_current_screen();
				if ($screen->base == "toplevel_page_creative_timeline_page") {
					wp_deregister_script('default');
				}
				if(isset($post) && isset($post->post_type) && $post->post_type =='creative_timeline') {
					wp_deregister_script('acf-timepicker');
					wp_deregister_script('acf-input');
					wp_deregister_script('acf');
					wp_deregister_script('jquery-ui-timepicker-js');
					wp_deregister_script('thrive-admin-datetime-picker');
					wp_deregister_script('et_bfb_admin_date_addon_js');
					wp_deregister_script('zeen-engine-admin-vendors-js');
				}
			}
		}

		/**
		 * Check for Classic Editor
		 */
		public function ctwp_classic_editor_load() {
            if (!class_exists('Classic_Editor')) {
                add_action('admin_notices', array($this, 'ctwp_classic_editor_fail_load'));
                return;
            }
        }

		/**
         * This notice will appear if Classic Editor is not installed or activated or both
         */
        public function ctwp_classic_editor_fail_load() {
            $screen = get_current_screen();
            if (isset($screen->parent_file) && 'plugins.php' === $screen->parent_file && 'update' === $screen->id) {
                return;
            }
            $plugin = 'classic-editor/classic-editor.php';

            if ($this->ctwp_classic_editor_installed()) {
                if (!current_user_can('activate_plugins')) {
                    return;
                }
                $activation_url = wp_nonce_url('plugins.php?action=activate&amp;plugin=' . $plugin . '&amp;plugin_status=all&amp;paged=1&amp;s', 'activate-plugin_' . $plugin);
                $message = '<p><strong>' . esc_html__('Creative Timeline is not working because you need to activate the Classic Editor plugin.', CTWP_DOMAIN).'</strong></p>';
                $message .= '<p>' . sprintf('<a href="%s" class="button-primary">%s</a>', $activation_url, esc_html__('Activate Classic Editor Now', CTWP_DOMAIN)) . '</p>';
            } else {
                if (!current_user_can('install_plugins')) {
                    return;
                }
                $install_url = wp_nonce_url(self_admin_url('update.php?action=install-plugin&plugin=classic-editor'), 'install-plugin_classic-editor');
                $message = '<p><strong>' . esc_html__('Creative Timeline is not working because you need to install the Classic Editor plugin', CTWP_DOMAIN) . '</strong></p>';
                $message .= '<p>' . sprintf('<a href="%s" class="button-primary">%s</a>', $install_url, esc_html__('Install Classic Editor Now', CTWP_DOMAIN)) . '</p>';
            }
            echo '<div class="error"><p>' . $message . '</p></div>';
        }

		/**
         * Action when plugin installed
        */
        public function ctwp_classic_editor_installed() {
            $file_path = 'classic-editor/classic-editor.php';
            $installed_plugins = get_plugins();
            return isset($installed_plugins[$file_path]);
        }
	}
}
$ctwp=CreativeTimeline::get_instance();
$ctwp->registers();
