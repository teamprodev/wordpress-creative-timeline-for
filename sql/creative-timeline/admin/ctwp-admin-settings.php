<?php
/**
 *
 * Create Admin Settings
 *
 * @since 1.0.0
 * @version 1.0.0
 *
 */

if(class_exists('CTWP')) {

    $prefix = 'creative_timeline_settings';

    CTWP::createOptions($prefix, array(   
      'framework_title'   => esc_html__('Timeline Settings', CTWP_DOMAIN),
      'menu_title'        => esc_html__('Timeline Settings', CTWP_DOMAIN),
      'menu_slug'         => 'creative_timeline_settings',
      'menu_type'         => 'submenu',
      'menu_parent'       => 'ctwp-timeline-admin-menu',
      'menu_capability'   => 'manage_options',
      'menu_icon'         => CTWP_URL.'assets/images/timeline-icon.svg',
      'menu_position'     => 6,
      'nav'               => 'inline',
      'show_reset_section'=> false,
      'show_reset_all'    => false,		
      'show_bar_menu'     => false
    ));
  
    /**
     * Create a section
     */
    CTWP::createSection($prefix, array(
      'title'  => esc_html__('General Settings', CTWP_DOMAIN),
      'fields' => array(
        array(
          'id'     => 'timeline_header',
          'type'   => 'fieldset',
          'title'  => esc_html__('Timeline Heading', CTWP_DOMAIN),
          'fields' => array(
            array(
              'id'         => 'ctwp_head_on_off',
              'type'       => 'switcher',
              'title'      => esc_html__('Hide Timeline Title', CTWP_DOMAIN),
              'text_on'    => esc_html__('Show', CTWP_DOMAIN),
              'text_off'   => esc_html__('Hide', CTWP_DOMAIN),
              'text_width' => 70,
              'default'    => true,
            ),           
            array(
              'id'      => 'user_avatar',          
              'title'   => esc_html__('Timeline Image', CTWP_DOMAIN), 
              'type'    => 'media',          
              'library' => 'image',
              'url'     => true,
              'preview' => true,
              'desc'    => esc_html__('Image above the Timeline', CTWP_DOMAIN),
            ),              
          ),
        ),
        array(
          'id'      => 'title_tag',
          'type'    => 'select',
          'title'   => esc_html__('Timeline Title Heading Tag', CTWP_DOMAIN),
          'options' => array(
            'h1' => esc_html__('H1', CTWP_DOMAIN),
            'h2' => esc_html__('H2', CTWP_DOMAIN),
            'h3' => esc_html__('H3', CTWP_DOMAIN),
            'h4' => esc_html__('H4', CTWP_DOMAIN),
            'h5' => esc_html__('H5', CTWP_DOMAIN),
            'h6' => esc_html__('H6', CTWP_DOMAIN)
          ),
          'default'    => 'h2',
          'dependency' => array('ctwp_head_on_off', '==', 'true'),   
        ),       
        array(
          'id'      => 'ctwp_main_title_typo',
          'type'    => 'typography',
          'title'   => esc_html__('Timeline Title Typography', CTWP_DOMAIN),
          'default' => array(
            'font-family' => 'Maven Pro',
            'font-size'   => '22',
            'line-height' => '',
            'unit'        => 'px',
            'type'        => 'google',
            'text-align'  => 'center',
            'font-weight' => '700',
          ),
          'color'      => false,
          'dependency' => array('ctwp_head_on_off', '==', 'true'),   
        ),
        array(
          'id'      => 'ctwp_timeline_title_color',
          'type'    => 'color',
          'title'   =>  esc_html__('Timeline Title Color', CTWP_DOMAIN),
          'default' => '#1f98d7',
        ),  
        array(
          'id'      => 'ctwp_timeline_color',
          'type'    => 'color',
          'title'   =>  esc_html__('Timeline Color', CTWP_DOMAIN),
          'default' => '#1f98d7',
        ),    
      )
    ));

    CTWP::createSection($prefix, array(
      'title'  =>  esc_html__('Content Settings', CTWP_DOMAIN),
      'fields' => array(
        array(
          'id'     => 'ctwp_timeline_post_title',
          'type'   => 'fieldset',
          'title'  => esc_html__('Timeline Post Title', CTWP_DOMAIN),
          'fields' => array(
            array(
              'id'         => 'ctwp_post_title_on_off',
              'type'       => 'switcher',
              'title'      => esc_html__('Hide Post Title', CTWP_DOMAIN),
              'text_on'    => esc_html__('Show', CTWP_DOMAIN),
              'text_off'   => esc_html__('Hide', CTWP_DOMAIN),
              'text_width' => 70,
              'default'    => true,
            ),  
            array(
              'id'      => 'ctwp_post_title_tag',
              'type'    => 'select',
              'title'   => esc_html__('Post Title Heading Tag', CTWP_DOMAIN),
              'options' => array(
                  'h1'  => esc_html__('H1', CTWP_DOMAIN),
                  'h2'  => esc_html__('H2', CTWP_DOMAIN),
                  'h3'  => esc_html__('H3', CTWP_DOMAIN),
                  'h4'  => esc_html__('H4', CTWP_DOMAIN),
                  'h5'  => esc_html__('H5', CTWP_DOMAIN),
                  'h6'  => esc_html__('H6', CTWP_DOMAIN)
              ),
              'default'    => 'h2',
              'dependency' => array('ctwp_post_title_on_off', '==', 'true'), 
            ),
            array(
              'id'      => 'ctwp_post_title_typo',
              'type'    => 'typography',
              'title'   => esc_html__('Post Title Typography ', CTWP_DOMAIN),
              'default' => array(
                'font-family' => 'Maven Pro',
                'font-size'   => '22',
                'line-height' => '',
                'unit'        => 'px',
                'type'        => 'google',
                'text-align'  => 'center',
                'font-weight' => '700',
              ),
              'color'      => false,
              'dependency' => array('ctwp_post_title_on_off', '==', 'true'),
            ),           
          ),
        ),
        array(
          'id'     => 'ctwp_timeline_post_content',
          'type'   => 'fieldset',
          'title'  => esc_html__('Timeline Post Content', CTWP_DOMAIN),
          'fields' => array(
            array(
              'id'         => 'ctwp_post_content_on_off',
              'type'       => 'switcher',
              'title'      => esc_html__('Hide Post Content', CTWP_DOMAIN),
              'text_on'    => esc_html__('Show', CTWP_DOMAIN),
              'text_off'   => esc_html__('Hide', CTWP_DOMAIN),
              'text_width' => 70,
              'default'    => true,
            ),
            array(
              'id'         => 'ctwp_post_content_length',
              'type'       => 'text',
              'title'      => esc_html__('Post Content Length', CTWP_DOMAIN),
              'default'    => '50',
              'desc'       => esc_html__('Please enter no of words', CTWP_DOMAIN),
              'dependency' => array('ctwp_post_content_on_off', '==', 'true'), 
            ),  
            array(
              'id'      => 'ctwp_post_content_typo',
              'type'    => 'typography',
              'title'   => esc_html__('Post Content Typography', CTWP_DOMAIN),
              'default' => array(
                'font-family' => 'Maven Pro',
                'font-size'   => '22',
                'line-height' => '',
                'unit'        => 'px',
                'type'        => 'google',
                'text-align'  => 'center',
                'font-weight' => '700',
              ),
              'color'      => false,
              'dependency' => array('ctwp_post_content_on_off', '==', 'true'), 
            ),           
          ),
        ),
        array(
          'id'     => 'ctwp_timeline_post_date',
          'type'   => 'fieldset',
          'title'  => esc_html__('Timeline Post Date', CTWP_DOMAIN),
          'fields' => array(
            array(
              'id'         => 'ctwp_post_date_on_off',
              'type'       => 'switcher',
              'title'      => esc_html__('Hide Post Date', CTWP_DOMAIN),
              'text_on'    => esc_html__('Show', CTWP_DOMAIN),
              'text_off'   => esc_html__('Hide', CTWP_DOMAIN),
              'text_width' => 70,
              'default'    => true,
            ),  
            array(
              'id'      => 'ctwp_post_date_typo',
              'type'    => 'typography',
              'title'   => esc_html__('Post Date Typography', CTWP_DOMAIN),
              'default' => array(
                'font-family' => 'Maven Pro',
                'font-size'   => '22',
                'line-height' => '',
                'unit'        => 'px',
                'type'        => 'google',
                'text-align'  => 'center',
                'font-weight' => '700',
              ),
              'color'      => false,
              'dependency' => array('ctwp_post_date_on_off', '==', 'true'), 
            ),           
          ),
        ),
        array(
          'id'     => 'ctwp_timeline_post_cat',
          'type'   => 'fieldset',
          'title'  => esc_html__('Timeline Post Category', CTWP_DOMAIN),
          'fields' => array(
            array(
              'id'         => 'ctwp_post_cat_on_off',
              'type'       => 'switcher',
              'title'      => esc_html__('Hide Post Category', CTWP_DOMAIN),
              'text_on'    => esc_html__('Show', CTWP_DOMAIN),
              'text_off'   => esc_html__('Hide', CTWP_DOMAIN),
              'text_width' => 70,
              'default'    => true,
            ),
            array(
              'id'      => 'ctwp_post_cat_typo',
              'type'    => 'typography',
              'title'   => esc_html__('Post Category Typography', CTWP_DOMAIN),
              'default' => array(
                'font-family' => 'Maven Pro',
                'font-size'   => '22',
                'line-height' => '',
                'unit'        => 'px',
                'type'        => 'google',
                'text-align'  => 'center',
                'font-weight' => '700',
              ),
              'color' => false,
            ),           
          ),
        ),
        array(
          'id'     => 'ctwp_timeline_post_meta',
          'type'   => 'fieldset',
          'title'  => esc_html__('Timeline Post Meta', CTWP_DOMAIN),
          'fields' => array(
            array(
              'id'         => 'ctwp_post_author_on_off',
              'type'       => 'switcher',
              'title'      => esc_html__('Hide Post Author', CTWP_DOMAIN),
              'text_on'    => esc_html__('Show', CTWP_DOMAIN),
              'text_off'   => esc_html__('Hide', CTWP_DOMAIN),
              'text_width' => 70,
              'default'    => true,
            ),
            array(
              'id'         => 'ctwp_post_comment_on_off',
              'type'       => 'switcher',
              'title'      => esc_html__('Hide Post Comment', CTWP_DOMAIN),
              'text_on'    => esc_html__('Show', CTWP_DOMAIN),
              'text_off'   => esc_html__('Hide', CTWP_DOMAIN),
              'text_width' => 70,
              'default'    => true,
            ),   
            array(
              'id'      => 'ctwp_post_meta_typo',
              'type'    => 'typography',
              'title'   => esc_html__('Post Meta Typography', CTWP_DOMAIN),
              'default' => array(
                'font-family' => 'Maven Pro',
                'font-size'   => '22',
                'line-height' => '',
                'unit'        => 'px',
                'type'        => 'google',
                'text-align'  => 'center',
                'font-weight' => '700',
              ),
              'color' => false,
            ),           
          ),
        )  ,
        array(
          'id'     => 'ctwp_timeline_post_image',
          'type'   => 'fieldset',
          'title'  => esc_html__('Timeline Post Featured Image', CTWP_DOMAIN),
          'fields' => array(
            array(
              'id'         => 'ctwp_post_image_on_off',
              'type'       => 'switcher',
              'title'      => esc_html__('Hide Post Featured Image', CTWP_DOMAIN),
              'text_on'    => esc_html__('Show', CTWP_DOMAIN),
              'text_off'   => esc_html__('Hide', CTWP_DOMAIN),
              'text_width' => 70,
              'default'    => true,
            ),           
          ),
        ),
        array(
          'id'     => 'ctwp_timeline_post_social_share',
          'type'   => 'fieldset',
          'title'  => esc_html__('Timeline Post Social Share', CTWP_DOMAIN),
          'fields' => array(
            array(
              'id'         => 'ctwp_post_social_on_off',
              'type'       => 'switcher',
              'title'      => esc_html__('Hide Post Social Share Icon', CTWP_DOMAIN),
              'text_on'    => esc_html__('Show', CTWP_DOMAIN),
              'text_off'   => esc_html__('Hide', CTWP_DOMAIN),
              'text_width' => 70,
              'default'    => true,
            ),           
          ),
        ),
        array(
          'id'     => 'ctwp_timeline_post_readmore_btn',
          'type'   => 'fieldset',
          'title'  => esc_html__('Timeline Post Read More Button', CTWP_DOMAIN),
          'fields' => array(
            array(
              'id'         => 'ctwp_timeline_post_readmore_btn_on_off',
              'type'       => 'switcher',
              'title'      => esc_html__('Hide Post Read More Button', CTWP_DOMAIN),
              'text_on'    => esc_html__('Show', CTWP_DOMAIN),
              'text_off'   => esc_html__('Hide', CTWP_DOMAIN),
              'text_width' => 70,
              'default'    => true,
            ), 
            array(
              'id'         => 'ctwp_timeline_post_readmore_btn_text',
              'type'       => 'text',
              'title'      => esc_html__('Read More Button Text', CTWP_DOMAIN),
              'default'    => esc_html__('Read More', CTWP_DOMAIN), 
              'dependency' => array('ctwp_timeline_post_readmore_btn_on_off', '==', 'true')              
            ), 
            array(
              'id'      => 'ctwp_post_readmore_btn_typo',
              'type'    => 'typography',
              'title'   => esc_html__('Read More Button Typography', CTWP_DOMAIN),
              'default' => array(
                'font-family' => 'Maven Pro',
                'font-size'   => '22',
                'line-height' => '',
                'unit'        => 'px',
                'type'        => 'google',
                'text-align'  => 'center',
                'font-weight' => '700',
              ),
              'dependency' => array('ctwp_timeline_post_readmore_btn_on_off', '==', 'true'), 
              'color'      => false,
            ), 
            array(
              'id'       => 'ctwp_post_readmore_btn_link',
              'type'     => 'radio',
              'title'    => esc_html__('Read More Button Open link', CTWP_DOMAIN),
              'options'  => array(
                '_self'  => esc_html__('Current Tab', CTWP_DOMAIN),
                '_blank' => esc_html__('New Tab', CTWP_DOMAIN),
              ),
              'inline'     => true,
              'default'    => '_self',
              'dependency' => array('ctwp_timeline_post_readmore_btn_on_off', '==', 'true') ,          
            ),             
          ),
        ),
      )
    ));
    CTWP::createSection($prefix, array(
      'title'  => esc_html__('Color Options', CTWP_DOMAIN),
      'fields' => array(
        array(
          'id'     => 'ctwp_timeline_post_title',
          'type'   => 'fieldset',
          'title'  => esc_html__('Timeline Post Title', CTWP_DOMAIN),
          'fields' => array(
            array(
              'id'      => 'ctwp_post_title_color',
              'type'    => 'color',
              'title'   => esc_html__('Post Title Color', CTWP_DOMAIN),
              'default' => '#000',
            ), 
            array(
              'id'      => 'ctwp_post_title_hover_color',
              'type'    => 'color',
              'title'   => esc_html__('Post Title Hover Color', CTWP_DOMAIN),
              'default' => '#1f98d7',
            ),    
          ),
        ),
        array(
          'id'     => 'ctwp_timeline_post_content',
          'type'   => 'fieldset',
          'title'  => esc_html__('Timeline Post Content', CTWP_DOMAIN),
          'fields' => array(
            array(
              'id'      => 'ctwp_post_content_box_color', 
              'type'    => 'color',
              'title'   => esc_html__('Post Content Box Color', CTWP_DOMAIN),
              'default' => '#fff',
            ), 
            array(
              'id'      => 'ctwp_post_content_color',
              'type'    => 'color',
              'title'   => esc_html__('Post Content Color', CTWP_DOMAIN),
              'default' => '#000',
            ),    
            array(
              'id'      => 'ctwp_post_ct_box_shadow_color',
              'type'    => 'color',
              'title'   => esc_html__('Post Content Box Shadow Color', CTWP_DOMAIN),
              'default' => '#fff',
            ),       
          ),
        ),
        array(
          'id'     => 'ctwp_timeline_post_date',
          'type'   => 'fieldset',
          'title'  => esc_html__('Timeline Post Date', CTWP_DOMAIN),
          'fields' => array(
            array(
              'id'      => 'ctwp_post_date_color',
              'type'    => 'color',
              'title'   => esc_html__('Post Date Color', CTWP_DOMAIN),
              'default' => '#1f98d7',
            ), 
            array(
              'id'      => 'ctwp_post_date_hover_color',
              'type'    => 'color',
              'title'   => esc_html__('Post Date Hover Color', CTWP_DOMAIN),
              'default' => '#000000',
            ),        
          ),
        ),
        array(
          'id'     => 'ctwp_timeline_post_cat',
          'type'   => 'fieldset',
          'title'  => esc_html__('Timeline Post Category', CTWP_DOMAIN),
          'fields' => array(
            array(
              'id'      => 'ctwp_post_cat_color',
              'type'    => 'color',
              'title'   => esc_html__('Post Category Color', CTWP_DOMAIN),
              'default' => '#000',
            ), 
            array(
              'id'      => 'ctwp_post_cat_hover_color',
              'type'    => 'color',
              'title'   => esc_html__('Post Category Hover Color', CTWP_DOMAIN),
              'default' => '#1f98d7',
            ), 
            array(
              'id'      => 'ctwp_post_cat_bg_color',
              'type'    => 'color',
              'title'   => esc_html__('Post Category Background Color', CTWP_DOMAIN),
              'default' => '#1f98d7',
            ),        
          ),
        ),
        array(
          'id'     => 'ctwp_timeline_post_meta',
          'type'   => 'fieldset',
          'title'  => esc_html__('Timeline Post Meta', CTWP_DOMAIN),
          'fields' => array(
            array(
              'id'      => 'ctwp_post_meta_color',
              'type'    => 'color',
              'title'   => esc_html__('Post Meta Color', CTWP_DOMAIN),
              'default' => '#000',
            ), 
            array(
              'id'      => 'ctwp_post_meta_hover_color',
              'type'    => 'color',
              'title'   => esc_html__('Post Meta Hover Color', CTWP_DOMAIN),
              'default' => '#1f98d7',
            ),         
          ),
        ),
        array(
          'id'     => 'ctwp_timeline_post_social_share',
          'type'   => 'fieldset',
          'title'  => esc_html__('Timeline Post Social Share', CTWP_DOMAIN),
          'fields' => array(
            array(
              'id'      => 'ctwp_post_social_color',
              'type'    => 'color',
              'title'   => esc_html__('Post Social Share Color', CTWP_DOMAIN),
              'default' => '#000',
            ), 
            array(
              'id'      => 'ctwp_post_social_hover_color',
              'type'    => 'color',
              'title'   => esc_html__('Post Social Share Hover Color', CTWP_DOMAIN),
              'default' => '#1f98d7',
            ),           
          ),
        ),
        array(
          'id'     => 'ctwp_timeline_post_readmore_btn',
          'type'   => 'fieldset',
          'title'  => esc_html__('Timeline Post Read More Button', CTWP_DOMAIN),
          'fields' => array(
            array(
              'id'      => 'ctwp_post_readmore_btn_color',
              'type'    => 'color',
              'title'   => esc_html__('Post Read More Button Color', CTWP_DOMAIN),
              'default' => '#fff',
            ), 
            array(
              'id'      => 'ctwp_post_readmore_btn_hover_color',
              'type'    => 'color',
              'title'   => esc_html__('Post Read More Button Hover Color', CTWP_DOMAIN),
              'default' => '#1f98d7',
            ), 
            array(
              'id'      => 'ctwp_post_readmore_btn_bg_color',
              'type'    => 'color',
              'title'   => esc_html__('Post Read More Button Background Color', CTWP_DOMAIN),
              'default' => '#1f98d7',
            ), 
            array(
              'id'      => 'ctwp_post_readmore_btn_bg_hover_color',
              'type'    => 'color',
              'title'   => esc_html__('Post Read More Button Background Hover Color', CTWP_DOMAIN),
              'default' => '#fff',
            ),                
          ),
        ),
        array(
          'id'     => 'timeline_post_icon',
          'type'   => 'fieldset',
          'title'  => esc_html__('Timeline Post Icon', CTWP_DOMAIN),
          'fields' => array(
            array(
              'id'      => 'ctwp_post_icon_color',
              'type'    => 'color',
              'title'   => esc_html__('Post Icon Color', CTWP_DOMAIN),
              'default' => '#000',
            ), 
            array(
              'id'      => 'ctwp_post_icon_hover_color',
              'type'    => 'color',
              'title'   => esc_html__('Post Icon Hover Color', CTWP_DOMAIN),
              'default' => '#1f98d7',
            ), 
            array(
              'id'      => 'ctwp_post_icon_bg_color',
              'type'    => 'color',
              'title'   => esc_html__('Post Icon Background Color', CTWP_DOMAIN),
              'default' => '#fff',
            ), 
            array(
              'id'      => 'ctwp_post_icon_bg_hover_color',
              'type'    => 'color',
              'title'   => esc_html__('Post Icon Background Hover Color', CTWP_DOMAIN),
              'default' => '#fff',
            ),
            array(
              'id'      => 'ctwp_post_icon_border_color',
              'type'    => 'color',
              'title'   => esc_html__('Post Icon Border Color', CTWP_DOMAIN),
              'default' => '#1f98d7',
            ),                
          ),
        ),
      )
    ));

    CTWP::createSection($prefix, array(
      'title'  => esc_html__('Advance Settings', CTWP_DOMAIN),
      'fields' => array(
        array(
          'id'       => 'ctwp_custom_styles',
          'type'     => 'code_editor',
          'title'    => esc_html__('Timeline Custom Styles', CTWP_DOMAIN),
          'settings' => array(
            'theme'  => 'mbo',
            'mode'   => 'css',
          ),
        'desc' => esc_html__("Enter your CSS code in the field above. Do not include any tags or HTML in the field. Custom CSS entered here will override the plugin CSS. In some cases, the !important tag may be needed. Don't URL encode image or svg paths. Contents of this field will be auto encoded.", CTWP_DOMAIN)
        ),
      )
    ));
  }