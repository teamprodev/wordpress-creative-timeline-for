<?php
/**
 * 
 * Creative Timeline Dynamic Shortcode
 * 
 * @since 1.0.0
 * @version 1.0.0
 * 
 */
class CTWPShortcode {

    /**
     * Register plugin with WordPress
     */
    public static function register() {
        $ctwp_shortcode = new self();
		add_shortcode('creative-timeline', array($ctwp_shortcode,'creativetimeline_view'));
		add_filter('excerpt_length', array($ctwp_shortcode, 'ctwp_excerpt_length'), 999);
		require CTWP_PATH . 'includes/class-ctwp-styles.php';
		$ctwp_styles= CTWP_styles::register();
    }

	/**
     * Shortcode callback
     */
	public function creativetimeline_view($atts, $content = null) {
		$attribute = shortcode_atts(array(
			'post_per_page' => 10,
			'layout'=>'',
			'category' => 0,
			'order'=>'',
			'orderby'=>'',
			'date-format'=>'',
			'icons'=>'',
			'show-posts'=>'',
			'timeline-title'=>'',
			'animation'=>'',
		), $atts);
		$ctwp_options_arr = get_option('creative_timeline_settings');
		$layout = $attribute['layout'];
		$timeline_title = $attribute['timeline-title'];

		/**
		 * Set stories animations
		 */
		if($attribute['animation']){
			if($attribute['animation']=='none'){
				$ctwp_animation='';
			}else{
				$ctwp_animation = $attribute['animation'];
			}
		}
		
		/**
		 * Add Shortcode on the page
		 */
		CTWP_styles::ctwp_load_assets($layout);			
		$ctwp_title_tag = isset($ctwp_options_arr['title_tag'])?$ctwp_options_arr['title_tag']:'H2';
		$ctwp_head_on_off = isset($ctwp_options_arr['timeline_header']['ctwp_head_on_off'])?$ctwp_options_arr['timeline_header']['ctwp_head_on_off']:'1';
		
		if(isset($ctwp_options_arr['timeline_header']['user_avatar']['id'])) {
			$user_avatar = wp_get_attachment_image_src($ctwp_options_arr['timeline_header']['user_avatar']['id'],'medium');
		}
		$ctwp_post_per_page = isset($ctwp_options_arr['post_per_page'])?$ctwp_options_arr['post_per_page']:10;
		$ctwp_no_posts = isset($ctwp_options_arr['no_posts'])?$ctwp_options_arr['no_posts']:"No timeline post found";
		$ctwp_content_length = isset($ctwp_options_arr['story_content_settings']['content_length'])?$ctwp_options_arr['story_content_settings']['content_length']:100;
		$title_alignment = isset($ctwp_options_arr['title_alignment'])?$ctwp_options_arr['title_alignment']:"center";
		$ctwp_posts_orders='';
		$story_desc_type='';
		$ctwp_posts_orderby='';
		
		if(isset($attribute['order']) && !empty($attribute['order'])) {
			$ctwp_posts_orders = $attribute['order'];
		}else{
			$ctwp_posts_orders = isset($ctwp_options_arr['posts_orders'])?$ctwp_options_arr['posts_orders']:"DESC";
		}
		if(isset($attribute['orderby']) && !empty($attribute['orderby'])) {
			$ctwp_posts_orders =$attribute['orderby'];
		}else{
			$ctwp_posts_orders = isset($ctwp_options_arr['posts_orderby'])?$ctwp_options_arr['posts_orderby']:"date";
		}

		/**
		 * Create Active class
		 */
		if($attribute['layout']) {
			$wrp_cls=$attribute['layout'];
		}else{
			$wrp_cls='default-layout';
		}
		if($attribute['icons']=="YES") {
			$clt_icons="icons_yes";
		}else{
			$clt_icons="icons_no";
		}  

		/**
		 * Date format for timeline
		 */
		$date_format=$this->ctwp_date_formats($attribute['date-format'],$ctwp_options_arr);     
	
		/**
		 * Timeline custom loop
		 */
		$args = array(
			'post_type' => 'creative_timeline', 
			'posts_per_page' => $ctwp_post_per_page,
			'post_status' => array('publish', 'future','scheduled'),
			'orderby' => $ctwp_posts_orderby,
			'order' =>$ctwp_posts_orders
		);
		
		$args['meta_query']= array(
			array(
				'key'=> 'ctwp_story_timestamp',
				'compare' => 'EXISTS',
				'type'    => 'NUMERIC'
			)
		);

		/** 
		 * Paged for pagination
		 */
		$args['paged']= (get_query_var('paged')) ? get_query_var('paged') : 1;
		$paged=$args['paged'];

		if ($attribute['show-posts']) {
			$args['posts_per_page'] = $attribute['show-posts'];
		}else {
			$args['posts_per_page'] = $ctwp_post_per_page;
		}
		if ($attribute['order']) {
			$args['order'] = $attribute['order'];
		}else {
			$args['order'] = $ctwp_posts_orders;
		}
		if ($attribute['orderby']) {
			$args['orderby'] = $attribute['orderby'];
			
		}else {
			$args['orderby'] = $ctwp_posts_orderby;
		}
		$category = $attribute['category'];
		if ($category) {
            if (strpos($category, ",") !== false) {
				$cat_arr= explode(",",$category);
				$selected_cats = array_map('trim',$cat_arr);
            } else {
				$selected_cats= $category;
            }
            if(is_numeric($selected_cats)) {
				$args['tax_query'] = array(
                    array(
                        'taxonomy' => 'ctwp_category',
                        'field' => 'term_id',
                        'terms' => $selected_cats,
					)
				);  
            }else{
				$args['tax_query'] = array(
					array(
						'taxonomy' => 'ctwp_category',
						'field' => 'slug',
						'terms' =>$selected_cats,
					)
				);
			}
		}

		/**
		 * Start Main query
		 */
		$ctwp_loop = new WP_Query(apply_filters('ctwp_stories_query',$args));
		$total_stories = $ctwp_loop->found_posts;
		$p_id = get_the_ID();
		$posted_date = CTWP_functions::ctwp_get_story_date($p_id,$date_format);
		if($story_desc_type=='full') {
			$post_content = apply_filters('the_content', get_the_content());
			$post_content = apply_filters('creative_timeline_story_content',$post_content);
		}else{
			$post_content = apply_filters('creative_timeline_story_content',get_the_excerpt());
		}
		$post_icon=CTWP_functions::get_fa(true);

		/**
		 * Include Timeline Layout files
		 */
		if($layout =="classic") {
			require CTWP_PATH . 'includes/ctwp-layouts/ctwp-classic.php';
		}elseif($layout =="artistic") {
			require CTWP_PATH . 'includes/ctwp-layouts/ctwp-artistic.php';
		}
		$p_cls[]=esc_attr($clt_icons);	
	}

	/**
	 * Timeline content length filter
	 */
	public function ctwp_excerpt_length($length) {
		global $post;
		$ctwp_options_arr = get_option('creative_timeline_settings');
		$ctwp_content_length = isset($ctwp_options_arr['story_content_settings']['content_length'])?$ctwp_options_arr['story_content_settings']['content_length']:100;
		if (isset($post->post_type) &&
			$post->post_type == 'creative_timeline' && !is_single()) {
			return $ctwp_content_length;
			}
		return $length;
	}
	
	/**
	 * Date format settings for Timeline
     */
	function ctwp_date_formats($date_format,$ctwp_options_arr) {		
		$date_formats='';
		if(!empty($date_format)) {
			
            if($date_format=="default") {
                $date_formats = isset($ctwp_options_arr['ctwp_date_formats']) ? $ctwp_options_arr['ctwp_date_formats'] : __('F j', CTWP_DOMAIN);
            }else{
                $df = $date_format;
                $date_formats = __("$df", CTWP_DOMAIN);     
            }  
		}else{
			$date_formats = __('F j', CTWP_DOMAIN);
		}
        return $date_formats;
	}
}
CTWPShortcode::register();
