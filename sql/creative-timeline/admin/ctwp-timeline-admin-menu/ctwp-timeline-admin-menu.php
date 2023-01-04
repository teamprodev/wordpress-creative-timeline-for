<?php
/**
 *
 * Creative Timeline Admin Menu
 *
 * @since 1.0.0
 * @version 1.0.0
 *
 */
if (!class_exists('CTWP_timeline_admin_menu')) {
    class CTWP_timeline_admin_menu{
        private static $instance;
        private $main_menu_slug = null;
        private $plugin_tag = null;
        private $dashboar_page_heading ;
        private $menu_title = 'Dashboard';
        private $menu_icon = false;

        /**
         * Initialize the class and create dashboard page
         */
        public static function init() {
            if(empty(self::$instance)) {
                return self::$instance = new self;
            }
            return self::$instance;
        }
        /**
         * Initialize the dashboard with specific plugins as per plugin tag
         */
        public function show_plugins($plugin_tag , $menu_slug , $dashboard_heading,  $main_menu_title, $icon ) {
            if(!empty($plugin_tag) && !empty($menu_slug) && !empty($dashboard_heading)) {
                $this->plugin_tag = $plugin_tag;
                $this->main_menu_slug = $menu_slug;
                $this->dashboar_page_heading = $dashboard_heading;
                $this->menu_title = $main_menu_title;
                $this->menu_icon = $icon;
            }else {
                return false;
            }
            add_action('admin_menu', array($this, 'init_plugins_dashboard_page'), 1);
            add_action('wp_ajax_creative_plugins_install_'. $this->plugin_tag, array($this, 'creative_plugins_install'));
            add_action('wp_ajax_creative_plugins_activate_'. $this->plugin_tag, array($this, 'creative_plugins_activate'));
            add_action('admin_enqueue_scripts', array($this,'enqueue_required_scripts') );
        }

        /**
         * Handle ajax request for activating plugin from dashboard
         */
        function creative_plugins_activate() {
            if(isset($_POST['wp_nonce']) && isset($_POST['nonce_name']) && wp_verify_nonce($_POST['wp_nonce'] , $_POST['nonce_name'])) {
                $pluginBase = (isset($_POST['pluginbase']) && !empty($_POST['pluginbase']))? sanitize_text_field($_POST['pluginbase']) : null;
                if($pluginBase != null) {
                    activate_plugin($pluginBase);
                }
            }else{
                die(esc_html__('wp nonce verification failed!', CTWP_DOMAIN));
            }
        }

        /**
         * handle ajax for installing plugin from the dashboard.
         */
        function creative_plugins_install() {
            if(isset($_POST['wp_nonce']) && isset($_POST['nonce_name']) && wp_verify_nonce($_POST['wp_nonce'] , $_POST['nonce_name'])) {
                $downloader = new creative_plugins_downloader();                  
                return  $downloader->install(filter_var(sanitize_key($_REQUEST['url']), FILTER_SANITIZE_URL), 'install');
            }else{
                die(esc_html__('wp nonce verification failed!', CTWP_DOMAIN));
            }
            die();
        }

        /**
         * This function will initialize the main dashboard menu for all plugins
         */
        function init_plugins_dashboard_page() {
            add_menu_page(esc_html__($this->menu_title, CTWP_DOMAIN), esc_html__($this->menu_title, CTWP_DOMAIN), 'manage_options', $this->main_menu_slug, array($this, 'displayPluginAdminDashboard'), $this->menu_icon, 9);
            add_submenu_page(esc_html__($this->main_menu_slug, CTWP_DOMAIN), esc_html__('Dashboard', CTWP_DOMAIN), esc_html__('Dashboard', CTWP_DOMAIN), 'manage_options', $this->main_menu_slug ,  array($this, 'displayPluginAdminDashboard'),1);
        }

        /**
         * This function will render and create the HTML display of dashboard page.
         */
        function displayPluginAdminDashboard() {?>
            <div class="creative-plugin-dashboard">
                <div class="creative-header">
                    <div class="creative-logo">
                        <img src="<?php echo esc_url(CTWP_URL . 'admin/ctwp-timeline-admin-menu/assets/images/creative-timeline-logo.svg');?>">
                    </div>
                    <div class="creative-heading">
                        <span class="ctwp-title"><?php _e('Creative Timeline', CTWP_DOMAIN); ?></span>
                        <span class="ctwp-subtitle"><?php _e('for WordPress', CTWP_DOMAIN); ?></span>
                    </div>
                </div>
                <div class="ctwp-plugin-heading"> 
                    <span class="getting-started"><h1><?php _e('Getting Started', CTWP_DOMAIN); ?></h1></span>
                    <div class="getting-started-content">
                        <ul>
                            <li><?php _e('Creative Timeline is helpful in showcasing your complex contents in structural way.', CTWP_DOMAIN); ?></li>
                            <li><?php _e('You will find everything you need to get started here. You are now equipped with arguably the most powerful WordPress Shortcode Builder.', CTWP_DOMAIN); ?></li>
                            <li><?php _e('Once you will activate your plugin, you’ll be redirect to a Dashboard (Creative Timeline > Getting Started). Here, you can view the required and helpful steps to use plugin.', CTWP_DOMAIN); ?></li>
                        </ul>
                    </div>
                    <div class="important-things-content">
                        <div class="style-content">
                            <div class="important-things"><h1><?php _e('Installation Process', CTWP_DOMAIN);?></h1>
                                <p><?php _e('To use Creative Timeline, follow the below steps for initial setup - Correct the Reading Settings.', CTWP_DOMAIN);?></p>
                                <ul class="important-things-list">
                                    <li><?php _e('1. Upload the <b> &nbsp; creative-timeline.zip &nbsp; </b> file via WordPress Admin > Plugins > Add New', CTWP_DOMAIN);?></li>
                                    <li><?php _e('2. Alternately, upload <b> &nbsp; creative-timeline &nbsp; </b> folder to the /wp-content/plugins/ directory via FTP', CTWP_DOMAIN);?></li>
                                    <li><?php _e('3. Activate the <b> &nbsp; Creative Timeline &nbsp; </b> plugin from Admin > Plugins', CTWP_DOMAIN);?></li>
                                </ul>
                            </div>
                            <div class="how-to-use"><h1><?php _e('How to use Creative Timeline Shortcode?', CTWP_DOMAIN);?></h1>
                                <ul class="how-to-use-list">
                                    <li><?php _e('1. Use shortcode [creative-timeline layout="classic"] in any WordPress Post or Page.', CTWP_DOMAIN);?></li>
                                    <li><?php _e('2. Use<b> &lt;?php echo do_shortcode("[creative-timeline layout="classic"]"); &nbsp;?&gt; </b>into a template file within your theme files.', CTWP_DOMAIN);?></li>
                                </ul>
                            </div>                                    
                        </div> 
                        <div class="style-img">
                            <img src=<?php echo esc_url(CTWP_URL.'admin/ctwp-timeline-admin-menu/assets/images/plugin-style.png');?>>
                        </div>
                    </div>
                    <div class="support-sec">
                        <div class="support">
                            <h1><?php _e('Support', CTWP_DOMAIN);?></h1>
                            <ul class="support-list-head">
                                <li><?php _e('Documentation', CTWP_DOMAIN);?></li>
                            </ul>
                            <div class="doc-list">
                                <ul class="support-list">
                                        <li><?php _e('Read helpful resources regarding how to use The Creative Timeline Plugin more efficiently.', CTWP_DOMAIN);?></li>
                                </ul>
                                <div class="doc-button">
                                    <a class="button-doc" href="<?php echo esc_url('https://www.techeshta.com/docs/creative-timeline-for-wordpress/'); ?>" target="_blank"><?php _e('Read Documentation', CTWP_DOMAIN);?></a>
                                </div>
                            </div>
                            <ul class="support-list-head">
                                <li><?php _e('FAQ', CTWP_DOMAIN);?></li>
                            </ul>
                            <div class="faq-list">
                                <ul class="support-list">
                                        <li><?php _e('The most frequently asked questions are answered here.', CTWP_DOMAIN);?></li>
                                </ul>
                                <div class="faq-button">
                                    <a class="button-faq" href="<?php echo esc_url('https://www.techeshta.com/docs/creative-timeline-for-wordpress/useful-notes-and-credits/faq/'); ?>" target="_blank"><?php _e('Read FAQ', CTWP_DOMAIN);?></a>
                                </div>
                            </div>
                            <ul class="support-list-head">
                                <li><?php _e('Ask our experts', CTWP_DOMAIN);?></li>
                            </ul>
                            <div class="ticket-list">
                                <ul class="support-list">
                                        <li><?php _e('Any question that is not addressed on documentations? Ask it from Techeshta experts. Note that you need to share your codecanyon license key to be able to get premium support.', CTWP_DOMAIN);?></li>
                                </ul>
                                <div class="ticket-button">
                                    <a class="button-ticket" href="mailto:support@techeshta.com" target="_blank"><?php _e('Submit a Ticket', CTWP_DOMAIN);?></a>
                                </div> 
                            </div>
                        </div>
                    </div>
                </div>
            </div> 
        <?php } 

        /**
         * Enqueue CSS & JS
         */
        function enqueue_required_scripts() {
            wp_enqueue_style('ctwp-timeline-addon', plugin_dir_url(__FILE__) .'assets/css/styles.css', null, null, 'all');
        }
    }

    /**
     * 
     * Initialize the main dashboard class with all required parameters
     */
    function ctwp_timeline_settings_page($tag ,$settings_page_slug, $dashboard_heading, $main_menu_title, $icon) {
        $event_page = CTWP_timeline_admin_menu::init();
        $event_page->show_plugins($tag, $settings_page_slug, $dashboard_heading, $main_menu_title, $icon);
    }
}
