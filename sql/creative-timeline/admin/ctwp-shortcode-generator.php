<?php
/**
 * This file is responsible for creating all admin settings in Timeline Builder (Custom Post)
 */
if (!defined("ABSPATH")) { // Cannot access directly.
    exit('Can not load script outside of WordPress Environment!');
}

if (!class_exists('CTWP_shortcode_generator')) {
    class CTWP_shortcode_generator {
        /**
         * The unique instance of the plugin.
         */
        private static $instance;

        /**
         * Gets an instance of our plugin.
         */
        public static function get_instance()
        {
            if (null === self::$instance) {
                self::$instance = new self();
            }
            return self::$instance;
        }
        
        /**
         * The Constructor
         */
        public function __construct()
        {
            $this->CTWP_shortcode_generator();
        }
        public function CTWP_shortcode_generator() {
            $id = isset($GLOBALS['_GET']['post'])?$GLOBALS['_GET']['post']:'';
            $post_type = isset($GLOBALS['_GET']['post_type'])?$GLOBALS['_GET']['post_type']:get_post_type($id);
            if($post_type !== 'page' && $post_type !== 'post' && $post_type!='') { 
                return;
            }
            if (class_exists('CTWP')) {
                $prefix = 'creative_timeline_shortcode'; 
                CTWP::createShortcoder($prefix, array(
                    'button_title' => esc_html__('Add Creative Timeline Shortcode', CTWP_DOMAIN),
                    'insert_title' => esc_html__('Insert shortcode', CTWP_DOMAIN),
                ));

                CTWP::createSection($prefix, array(
                    'title'     => esc_html__('Creative Timeline Shortcode', CTWP_DOMAIN),
                    'view'      => 'normal',
                    'shortcode' => 'creative-timeline',
                    'fields'    => array(
                        array(
                            'id'         => 'timeline-title',
                            'type'       => 'text',
                            'title'      => esc_html__('Timeline Title', CTWP_DOMAIN),                           
                            'default'    => esc_html__('Timeline Story ', CTWP_DOMAIN),                          
                            'attributes' => array(
                                'style'  => 'width: 50%;',
                            ),
                        ),
                        array(
                            'id'          => 'category',
                            'type'        => 'select',
                            'title'       => esc_html__('Category', CTWP_DOMAIN),
                            'placeholder' => esc_html__('Select a Category', CTWP_DOMAIN),                                                                                        
                            'settings'    => array(
                                'width'   => '50%'
                            ),
                            'options'    => 'ctwp_select_category',
                            'attributes' => array(
                                'style'  => 'width: 50%;',
                            ),
                        ),
                        array(
                            'id'      => 'layout',
                            'type'    => 'select',
                            'title'   => esc_html__('Select Layout', CTWP_DOMAIN),                                                                                
                            'default' => 'default',
                            'desc'    => esc_html__('Select your timeline Layout', CTWP_DOMAIN),                            
                            'options' => array(
                                'classic'  => esc_html__('Classic', CTWP_DOMAIN),
                                'artistic' => esc_html__('Artistic', CTWP_DOMAIN),
                            ),
                            'attributes' => array(
                                'style'  => 'width: 50%;',
                            ),
                        ),
                        array(
                            'id'         => 'animation',
                            'type'       => 'select',
                            'title'      => 'Animation',
                            'default'    =>'none',
                            'dependency' => array( 'layout', '!=', 'artistic' ),
                            'options'    => array(
                                'none'     =>'none',
                                'tilt'     => esc_html__('Tilt', CTWP_DOMAIN),
                                'inandout' => esc_html__('In And Out', CTWP_DOMAIN)
                            ),
                            'attributes' => array(
                                'style'  => 'width: 50%;',
                            ),
                        ),
                        array(
                            'id'         => 'animation',
                            'type'       => 'select',
                            'title'      => 'Animation',
                            'default'    =>'none',
                            'dependency' => array( 'layout', '!=', 'classic' ),
                            'options'    => array(
                                'none'          =>'none',
                                'fadeup'        => 'Fade UP',
                                'fadeleftright' => 'Fade Left Right'
                                
                            ),
                            'attributes' => array(
                                'style'  => 'width: 50%;',
                            ),
                        ),
                        array(
                            'id'      => 'date-format',
                            'type'    => 'select',
                            'title'   => esc_html__('Select Date Formats', CTWP_DOMAIN), 
                            'default' => 'F j',                           
                            'options' => array(
                                'F j'         => __('F j', CTWP_DOMAIN),
                                'F j Y'       => __('F j Y', CTWP_DOMAIN),
                                'Y-m-d'       => __('Y-m-d', CTWP_DOMAIN),
                                'm/d/Y'       => __('m/d/Y', CTWP_DOMAIN),  
                                'd/m/Y'       => __('d/m/Y', CTWP_DOMAIN), 
                                'F j Y g:i A' => __('F j Y g:i A', CTWP_DOMAIN),  
                                'Y'           => __('Y', CTWP_DOMAIN),
                            ),
                            'desc'       => esc_html__('Timeline dates formats', CTWP_DOMAIN), 
                            'attributes' => array(
                                'style'  => 'width: 50%;',
                            ),
                        ),
                        array(
                            'id'      => 'show-posts',
                            'type'    => 'text',
                            'title'   => esc_html__('Display Number of Posts', CTWP_DOMAIN),                           
                            'default' => '10',              
                            'desc'    => esc_html__('You Can Show Pagination After These Posts In Vertical Timeline.', CTWP_DOMAIN),
                            'attributes' => array(
                                'style'  => 'width: 50%;',
                            ),
                        ),
                        array(
                            'id'      => 'icons',
                            'type'    => 'select',
                            'title'   => esc_html__('Icons', CTWP_DOMAIN),
                            'default' =>'NO',                            
                            'options' => array(
                                'NO'  => esc_html__('NO', CTWP_DOMAIN),
                                'YES' => esc_html__('YES', CTWP_DOMAIN),                                
                            ),
                            'desc'       => esc_html__('Display Icons In Timeline.', CTWP_DOMAIN),
                            'attributes' => array(
                                'style'  => 'width: 50%;',
                            ),
                        ),
                        array(
                            'id'       => 'order',
                            'type'     => 'select',
                            'title'    => esc_html__('Order', CTWP_DOMAIN),
                            'default'  =>'DESC',                            
                            'options'  => array(
                                'DESC' => esc_html__('DESC', CTWP_DOMAIN),
                                'ASC'  => esc_html__('ASC', CTWP_DOMAIN),
                            ),
                            'attributes' => array(
                                'style'  => 'width: 50%;',
                            ),
                            'desc' => '<span>' . esc_html__('Timeline order like:- DESC(2017-1900) , ASC(1900-2017)', CTWP_DOMAIN) . '</span>',
                        ),
                        array(
                            'id'       => 'orderby',
                            'type'     => 'select',
                            'title'    => esc_html__('Order By', CTWP_DOMAIN),
                            'default'  => '',                            
                            'options'  => array(
                                'date'           => esc_html__('Publish Date', CTWP_DOMAIN),
                                'ID'             => esc_html__('ID', CTWP_DOMAIN),
                                'author'         => esc_html__('Author', CTWP_DOMAIN),
                                'title'          => esc_html__('Title', CTWP_DOMAIN),
                                'name'           => esc_html__('Name', CTWP_DOMAIN),
                                'modified'       => esc_html__('Modified', CTWP_DOMAIN), 
                                'parent'         => esc_html__('Parent', CTWP_DOMAIN), 
                                'rand'           => esc_html__('Random', CTWP_DOMAIN),
                                'comment_count'  => esc_html__('Comment count', CTWP_DOMAIN), 
                                'menu_order'     => esc_html__('Menu order', CTWP_DOMAIN), 
                                'meta_value'     => esc_html__('Meta value', CTWP_DOMAIN), 
                                'meta_value_num' => esc_html__('Meta value num', CTWP_DOMAIN), 
                                'post__in'       => esc_html__('Post__in', CTWP_DOMAIN), 
                                'none'           => esc_html__('None', CTWP_DOMAIN),
                            ),
                            'attributes' => array(
                                'style'  => 'width: 50%;',
                            ),
                        ),
                    )
                ));
            }

            /**
             * Fetch all timeline items for shortcode builder options
             */    
            function ctwp_select_category() {
                $terms = get_terms(array(
                'taxonomy'   => 'ctwp_category',
                'hide_empty' => false,
                ));
                $ctwp_categories = array();
                $ctwp_categories[''] = esc_html__('All Categories', CTWP_DOMAIN);
                if (!empty($terms) || !is_wp_error($terms)) {
                    foreach ($terms as $term) {
                        $ctwp_categories[$term->slug] = $term->name ;
                    }
                }
                return $ctwp_categories;
            }
        }
    }
}
new CTWP_shortcode_generator();
