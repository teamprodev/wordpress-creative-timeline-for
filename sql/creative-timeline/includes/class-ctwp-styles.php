<?php
/**
 * 
 * Creative Timeline Dynamic CSS settings
 * 
 * @since 1.0.0
 * @version 1.0.0
 * 
 */
class CTWP_styles{
    
    /**
     * Register plugin with WordPress
     */
    public static function register() {
        $thisCls = new self();
        add_action('wp_enqueue_scripts', array($thisCls, 'ctwp_load_scripts_styles'));
		add_action('wp_enqueue_scripts',array($thisCls,'ctwp_custom_style'));
    }

    /** 
     * Enqueue Styles
     */
	public static function ctwp_load_assets($layout) {
        wp_enqueue_style('ctwp-layouts-style');
        wp_enqueue_style('ctwp-default-fonts');
        wp_enqueue_style("ctwp-gfonts");
        wp_enqueue_style('ctwp-fontawesome-5-v4');
        wp_enqueue_script('ctwp-all-script');
        wp_enqueue_script('ctwp-custom-js');
	}	 

    /**
	 * Registered All assets 
	 */
    public static function ctwp_load_scripts_styles() {
        $ctwp_options_arr = get_option('creative_timeline_settings');	
        $selected_fonts = array();

        if(isset($ctwp_options_arr['ctwp_post_content_typo']['font-family'])) {
            $ctwp_post_content_typo=$ctwp_options_arr['ctwp_post_content_typo'];
            if(isset($ctwp_post_content_typo['type']) && $ctwp_post_content_typo['type']=='google') {
                $selected_fonts[]=$ctwp_post_content_typo['font-family'];   
            }
        }
        if(isset($ctwp_options_arr['ctwp_post_title_typo']['font-family'])) {
            $ctwp_post_title_typo=$ctwp_options_arr['ctwp_post_title_typo'];
            if(isset($ctwp_post_title_typo['type']) && $ctwp_post_title_typo['type']=='google') {
                $selected_fonts[]=$ctwp_post_title_typo['font-family'];   
            }
        }
        if(isset($ctwp_options_arr['ctwp_main_title_typo']['font-family'])) {
            $ctwp_main_title_typo=$ctwp_options_arr['ctwp_main_title_typo'];
            if(isset($ctwp_main_title_typo['type']) && $ctwp_main_title_typo['type']=='google') {
                $selected_fonts[]=$ctwp_main_title_typo['font-family'];   
            }
        }
        if(isset($ctwp_options_arr['ctwp_date_typo']['font-family'])) {
            $ctwp_date_typo=$ctwp_options_arr['ctwp_date_typo'];
            if(isset($ctwp_date_typo['type']) && $ctwp_date_typo['type']=='google') {
                $selected_fonts[]=$ctwp_date_typo['font-family'];   
            }
        }
        
        /**
         * Integrate Google fonts
         */
        $selected_fonts = array_unique($selected_fonts);
        $gfont_arr = array();

        if(is_array($selected_fonts)) {
            foreach ($selected_fonts as $font) {
                if($font && $font != 'inherit') {
                    if ($font == 'Raleway') {
                        $font = 'Raleway:100';
                    }
                    $font = str_replace(" ", "+", $font);
                    $gfont_arr[]=$font;
                }
            }
            if(is_array($gfont_arr)) {
                $allfonts=implode("|",$gfont_arr);
                if($allfonts) {
                    wp_register_style("ctwp-gfonts", "//fonts.googleapis.com/css?family=$allfonts", false, CREATIVE_TIMELINE_CURRENT_VERSION, 'all');
                }
            }	       
        }
        wp_register_style("ctwp-default-fonts",  CTWP_URL . 'assets/fontawesome/ctwp-default-fonts.css', false, CREATIVE_TIMELINE_CURRENT_VERSION, 'all');
        wp_register_style('ctwp-layouts-style', CTWP_URL . 'assets/css/ctwp-layouts-style.css', null, CREATIVE_TIMELINE_CURRENT_VERSION,'all');	
        wp_register_style('ctwp-styles', CTWP_URL . 'assets/css/ctwp_styles.min.css',null, CREATIVE_TIMELINE_CURRENT_VERSION,'all');
        wp_register_style('ctwp-fontawesome-5-v4',  CTWP_URL . 'assets/fontawesome/ctwp-fontawesome.css', false, CREATIVE_TIMELINE_CURRENT_VERSION, 'all');
        wp_register_script('ctwp-all-script',CTWP_URL.'/assets/js/fontawesome.js', array('jquery'), CREATIVE_TIMELINE_CURRENT_VERSION, true);
        wp_register_script('ctwp-custom-js',CTWP_URL.'/assets/js/ctwp-custom.js', array('jquery'), CREATIVE_TIMELINE_CURRENT_VERSION, true);
    }

    /**
     * Generate dynamic CSS style
     */
    public static function ctwp_custom_style() {                  
        $ctwp_options_arr = get_option('creative_timeline_settings');
        $custom_styles = '';
        $custom_styles = isset($ctwp_options_arr['ctwp_custom_styles'])?$ctwp_options_arr['ctwp_custom_styles']:"";          
        $styles = '';
        $custom_css = preg_replace('/\\\\/', '', $custom_styles);
        $final_css = CTWP_styles::clt_minify_css($styles);
        wp_add_inline_style('ctwp-layouts-style',$final_css.' '.$custom_css);
    }

    /**
     * Compress CSS
     */
    public static function clt_minify_css($css) {
        $buffer = $css;
        $buffer = preg_replace('!/\*[^*]*\*+([^/][^*]*\*+)*/!', '', $buffer);
        $buffer = str_replace(': ', ':', $buffer);
        $buffer = str_replace(array("\r\n", "\r", "\n", "\t"), '', $buffer);
        $buffer = preg_replace(" {2,}", ' ',$buffer);
        return $buffer;
    }

    public static function ctwp_get_typeo_output($settings) {
        $output    = '';
        $important ='';
        $font_family   = (!empty($settings['font-family'])) ? $settings['font-family'] : '';
        $backup_family = (!empty($settings['backup-font-family'])) ? ', '. $settings['backup-font-family'] : '';
        if ($font_family) {
            $output .= 'font-family:'. $font_family .''. $backup_family . $important .';';
        }

        /**
         * Typography Properties
         */
        $properties = array(
            'color',
            'font-weight',
            'font-style',
            'font-variant',
            'text-align',
            'text-transform',
            'text-decoration',
        );
        foreach ($properties as $property) {
            if (isset($settings[$property]) && $settings[$property] !== '') {
                $output .= $property .':'.$settings[$property] . $important .';';
            }
        }
        $properties = array(
            'font-size',
            'line-height',
            'letter-spacing',
            'word-spacing',
        );
        $unit = (!empty($settings['unit'])) ? $settings['unit'] : 'px';

        $line_height_unit = (!empty($settings['line_height_unit'])) ? $settings['line_height_unit'] : 'px';
        foreach ($properties as $property) {
            if (isset($settings[$property]) && $settings[$property] !== '') {
            $unit = ($property === 'line-height') ? $line_height_unit : $unit;
            $output .= $property .':'. $settings[$property] . $unit . $important .';';
            }
        }
        return $output;
    }
}
