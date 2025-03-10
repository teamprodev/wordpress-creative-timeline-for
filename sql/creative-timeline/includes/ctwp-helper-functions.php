<?php
/**
 * 
 * Get the timeline Meta fields and set
 * 
 * @since 1.0.0
 * @version 1.0.0
 * 
 */
class CTWP_functions {
    public static function get_fa($format = false, $post_id = null) {
        if (!$post_id) {
            global $post;
            if (!is_object($post)) {
                return;
            }
            $post_id = $post->ID;
        }
        $icon = '';
        $ctwp_story_icon = get_post_meta($post_id, 'story_icon', true);
        $icon = isset($ctwp_story_icon['fa_field_icon'])?$ctwp_story_icon['fa_field_icon']:'';

        if (!$icon) {
            return;
        }
        if ($format) {
            if(strpos($icon, '-o') !==false) {
                $icon="fa ".$icon;
            }else if(strpos($icon, 'fas')!==false || strpos($icon, 'fab') !==false) {
                $icon=$icon;
            }else{
                $icon="fa ".$icon;
            }
            $output = '<i class="' . $icon . '"></i>';
        } else {
            $output = $icon;
        }
        return $output;
    }

    /**
     * Get story date
     */
    public static function ctwp_get_story_date($post_id,$date_formats) {
        $ctwp_story_date ='';
        $posted_date = '';
        $ctwp_story_type = get_post_meta($post_id, 'story_type', true);
        $ctwp_story_date = isset($ctwp_story_type['ctwp_story_date'])?$ctwp_story_type['ctwp_story_date']:'';

        if ($ctwp_story_date) {
            if (strtotime($ctwp_story_date)!==false) {
                $posted_date = date_i18n(__($date_formats, CTWP_DOMAIN), strtotime("$ctwp_story_date"));
            }else {
                $ctwp_story_date = trim(str_ireplace(array('am','pm'),'',$ctwp_story_date));  
                $dateobj = DateTime::createFromFormat('m/d/Y H:i',$ctwp_story_date ,wp_timezone());
                if($dateobj) {
                    $posted_date = $dateobj->format(__($date_formats, CTWP_DOMAIN));
                }
            }
            return  $posted_date;
        }
    }

    /**
     * Create own custom timestamp for stories
     */
    public static function ctwp_generate_custom_timestamp($story_date) {
        if(!empty($story_date)) {            
            $ctwp_story_date=strtotime($story_date);      
            if($ctwp_story_date!==false) {
                $story_timestamp = date('YmdHi',$ctwp_story_date);
            }
            return $story_timestamp;  
        }
    }

    /**
     * Get post type from url
     */
    public static function ctwp_get_ctp() {
        global $post, $typenow, $current_screen;
        if ($post && $post->post_type)
            return $post->post_type;
        elseif($typenow)
            return $typenow;
        elseif($current_screen && $current_screen->post_type)
            return $current_screen->post_type;
        elseif(isset($_REQUEST['post_type']))
            return sanitize_key($_REQUEST['post_type']);
        return null;
    }

    /**
     * Timeline pagination handler
     */
    public static function ctwp_pagination($numpages = '', $pagerange = '', $paged='') {
        if (empty($pagerange)) {
            $pagerange = 2;
        }
        if (get_query_var('paged')) { 
            $paged = get_query_var('paged'); 
        } elseif (get_query_var('page')) { 
            $paged = get_query_var('page'); 
        } else { 
            $paged = 1; 
        }
        if ($numpages == '') {
            global $wp_query;
            $numpages = $wp_query->max_num_pages;  
            if(!$numpages) {
                $numpages = 1;
            }
        }
        $big = 999999999; 
        $of_lbl = esc_html__(' of ', CTWP_DOMAIN); 
        $page_lbl = esc_html__(' Page ', CTWP_DOMAIN); 
        $pagination_args = array(
            'base' => str_replace($big, '%#%', esc_url(get_pagenum_link($big))),
            'format' => '?paged=%#%',
            'total'           => $numpages,
            'current'         => $paged,
            'show_all'        => False,
            'end_size'        => 1,
            'mid_size'        => $pagerange,
            'prev_next'       => True,
            'prev_text'       => '&laquo;',
            'next_text'       => '&raquo;',
            'type'            => 'plain',
            'add_args'        => false,
            'add_fragment'    => '' 
        );

        $paginate_links = paginate_links($pagination_args);
        $ctwp_pagi='';
        if ($paginate_links) {
            $ctwp_pagi .= "<nav class='custom-pagination'>";
            $ctwp_pagi .= "<span class='page-numbers page-num'> ".$page_lbl . $paged . $of_lbl . $numpages . "</span> ";
            $ctwp_pagi .= $paginate_links;
            $ctwp_pagi .= "</nav>";
            return $ctwp_pagi;
        }
    }
}