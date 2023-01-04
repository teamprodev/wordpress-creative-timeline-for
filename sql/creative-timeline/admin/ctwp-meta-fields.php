<?php 
/**
 * Creating Meta boxes for Timeline Section
*/

if(class_exists('CTWP')) {    
    $prefix = 'ctwp_post_meta';
    
    CTWP::createMetabox($prefix, array(
        'title'     =>  esc_html__('Timeline Settings', CTWP_DOMAIN),
        'post_type' => 'creative_timeline',
        'data_type' => 'unserialize',
        'context'   => 'normal',
    ));

    /**
     * Create a section
     */
    CTWP::createSection($prefix, array(
        'data_type' => 'unserialize',
        'fields'    => array(
            // Timeline Date
            array(
                'id'     => 'story_type',
                'type'   => 'fieldset',
                'title'  =>  esc_html__('Timeline Type', CTWP_DOMAIN),
                'fields' => array(
                    array(
                        'id'      => 'ctwp_story_date',
                        'type'    => 'datetime',
                        'title'   => esc_html__('Timeline Date / Year', CTWP_DOMAIN) . '<span class="ctwp_required">*</span>',
                        'desc'    => '<p class="ctwp_required">' . esc_html__('Please select Timeline Date / Year / Time using datepicker only.', CTWP_DOMAIN) . '<strong>' . esc_html__('Date Format(mm/dd/yy hh:mm)', CTWP_DOMAIN) . '</strong></p>',
                        'default' => date('m/d/Y h:m a'),                
                    ),    
                ),
            ),
            // Timeline Icon
            array(
                'id'     => 'story_icon',
                'type'   => 'fieldset',
                'title'  =>  esc_html__('Timeline Icon', CTWP_DOMAIN),
                'fields' => array(                   
                    array(
                        'id'    => 'fa_field_icon',
                        'type'  => 'icon',
                        'title' => esc_html__('Font Awesome Icon', CTWP_DOMAIN),
                    ),
                ),
            ),
            array(
                'id'     => 'ctwp_custom_color',
                'type'   => 'fieldset',
                'title'  =>  esc_html__('Timeline Custom Color', CTWP_DOMAIN),
                'fields' => array(                      
                    array(
                        'id'         => 'ctwp_post_custom_color',
                        'type'       => 'switcher',
                        'title'      => esc_html__('Use Custom Color Option', CTWP_DOMAIN),
                        'text_on'    => esc_html__('Yes', CTWP_DOMAIN),
                        'text_off'   => esc_html__('No', CTWP_DOMAIN),
                        'text_width' => 70,
                        'default'    => false,
                    ), 
                    array(
                        'id'         => 'ctwp_title_hover_color',
                        'type'       => 'color',
                        'title'      => esc_html__('Title Hover Color', CTWP_DOMAIN),
                        'default'    => '#000',
                        'dependency' => array('ctwp_post_custom_color', '==', 'true'), 
                    ), 
                    array(
                        'id'         => 'ctwp_title_color',
                        'type'       => 'color',
                        'title'      => esc_html__('Title Color', CTWP_DOMAIN),
                        'default'    => '#1f98d7',
                        'dependency' => array('ctwp_post_custom_color', '==', 'true'), 
                    ),  
                    array(
                        'id'         => 'ctwp_content_box_color',
                        'type'       => 'color',
                        'title'      => esc_html__('Content Box color', CTWP_DOMAIN),
                        'default'    => '#fff',
                        'dependency' => array('ctwp_post_custom_color', '==', 'true'), 
                    ),
                    array(
                        'id'         => 'ctwp_content_color',
                        'type'       => 'color',
                        'title'      => esc_html__('Content Color', CTWP_DOMAIN),
                        'default'    => '#000',
                        'dependency' => array('ctwp_post_custom_color', '==', 'true'), 
                    ), 
                    array(
                        'id'         => 'ctwp_cat_hover_color',
                        'type'       => 'color',
                        'title'      => esc_html__('Category Hover Color', CTWP_DOMAIN),
                        'default'    => '#000',
                        'dependency' => array('ctwp_post_custom_color', '==', 'true'), 
                    ),
                    array(
                        'id'         => 'ctwp_cat_color',
                        'type'       => 'color',
                        'title'      => esc_html__('Category Color', CTWP_DOMAIN),
                        'default'    => '#1f98d7',
                        'dependency' => array('ctwp_post_custom_color', '==', 'true'), 
                    ),
                    array(
                        'id'         => 'ctwp_date_color',
                        'type'       => 'color',
                        'title'      => esc_html__('Date Color', CTWP_DOMAIN),
                        'default'    => '#1f98d7',
                        'dependency' => array('ctwp_post_custom_color', '==', 'true'), 
                    ), 
                    array(
                        'id'         => 'ctwp_cat_bg_color',
                        'type'       => 'color',
                        'title'      => esc_html__('Category Background Color', CTWP_DOMAIN),
                        'default'    => '#1f98d7',
                        'dependency' => array('ctwp_post_custom_color', '==', 'true'), 
                    ),
                    array(
                        'id'         => 'ctwp_meta_color',
                        'type'       => 'color',
                        'title'      => esc_html__('Meta Color', CTWP_DOMAIN),
                        'default'    => '#1f98d7',
                        'dependency' => array('ctwp_post_custom_color', '==', 'true'), 
                    ),  
                    array(
                        'id'         => 'ctwp_date_hover_color',
                        'type'       => 'color',
                        'title'      => esc_html__('Date Hover Color', CTWP_DOMAIN),
                        'default'    => '#000',
                        'dependency' => array('ctwp_post_custom_color', '==', 'true'), 
                    ),
                    array(
                        'id'         => 'ctwp_social_color',
                        'type'       => 'color',
                        'title'      => esc_html__('Social Share Color', CTWP_DOMAIN),
                        'default'    => '#1f98d7',
                        'dependency' => array('ctwp_post_custom_color', '==', 'true'), 
                    ),
                    array(
                        'id'         => 'ctwp_meta_hover_color',
                        'type'       => 'color',
                        'title'      => esc_html__('Meta Hover Color', CTWP_DOMAIN),
                        'default'    => '#000',
                        'dependency' => array('ctwp_post_custom_color', '==', 'true'), 
                    ),
                    array(
                        'id'         => 'ctwp_readmore_color',
                        'type'       => 'color',
                        'title'      => esc_html__('Read More Button Color', CTWP_DOMAIN),
                        'default'    => '#1f98d7',
                        'dependency' => array('ctwp_post_custom_color', '==', 'true'), 
                    ),
                    array(
                        'id'         => 'ctwp_social_hover_color',
                        'type'       => 'color',
                        'title'      => esc_html__('Social Share Hover Color', CTWP_DOMAIN),
                        'default'    => '#000',
                        'dependency' => array('ctwp_post_custom_color', '==', 'true'), 
                    ),
                    array(
                        'id'         => 'ctwp_readmore_bg_color',
                        'type'       => 'color',
                        'default'    => '#1f98d7',
                        'dependency' => array('ctwp_post_custom_color', '==', 'true'), 
                    ), 
                    array(
                        'id'         => 'ctwp_readmore_hover_color',
                        'type'       => 'color',
                        'title'      => esc_html__('Read More Button Hover Color', CTWP_DOMAIN),
                        'default'    => '#000',
                        'dependency' => array('ctwp_post_custom_color', '==', 'true'), 
                    ),
                    array(
                        'id'         => 'ctwp_icon_color',
                        'type'       => 'color',
                        'title'      => esc_html__('Icon Color', CTWP_DOMAIN),
                        'default'    => '#1f98d7',
                        'dependency' => array('ctwp_post_custom_color', '==', 'true'), 
                    ),
                    array(
                        'id'         => 'ctwp_readmore_bg_hover_color',
                        'type'       => 'color',
                        'title'      => esc_html__('Read More Button Background Hover Color', CTWP_DOMAIN),
                        'default'    => '#000',
                        'dependency' => array('ctwp_post_custom_color', '==', 'true'), 
                    ),
                    array(
                        'id'         => 'ctwp_icon_bg_color',
                        'type'       => 'color',
                        'title'      => esc_html__('Icon Background Color', CTWP_DOMAIN),
                        'default'    => '#1f98d7',
                        'dependency' => array('ctwp_post_custom_color', '==', 'true'), 
                    ),
                    array(
                        'id'         => 'ctwp_icon_hover_color',
                        'type'       => 'color',
                        'title'      => esc_html__('Icon Hover Color', CTWP_DOMAIN),
                        'default'    => '#000',
                        'dependency' => array('ctwp_post_custom_color', '==', 'true'), 
                    ), 
                    array(
                        'id'         => 'ctwp_icon_border_color',
                        'type'       => 'color',
                        'title'      => esc_html__('Icon Border Color', CTWP_DOMAIN),
                        'default'    => '#000',
                        'dependency' => array('ctwp_post_custom_color', '==', 'true'), 
                    ),
                    array(
                        'id'         => 'ctwp_icon_bg_hover_color',
                        'type'       => 'color',
                        'title'      => esc_html__('Icon Background Hover Color', CTWP_DOMAIN),
                        'default'    => '#000',
                        'dependency' => array('ctwp_post_custom_color', '==', 'true'), 
                    ),
                ),
            ),        
        )
    )); 
    $pro_list = 'feature_list';
}