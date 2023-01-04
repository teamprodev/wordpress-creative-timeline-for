<?php
class CTWPPosttype {
    /**
     * Registers our plugin with WordPress.
     */
	
    public static function register() {
        $postTypeCls = new self();
        add_action('init', array($postTypeCls,'creative_timeline_custom_post_type'),0);
		add_action('init', array($postTypeCls, 'register_creative_timeline_taxonomies'),0);
		add_action('init', array($postTypeCls, 'ctwp_insert_category'), 0);
        add_filter('manage_edit-creative_timeline_columns',array($postTypeCls,'ctwp_add_new_columns'));
        add_action('manage_creative_timeline_posts_custom_column' , array($postTypeCls,'ctwp_custom_columns'), 10, 2);
        add_filter('display_post_states', array($postTypeCls, 'ctwp_generted_page_label'));
        add_action('post_submitbox_misc_actions', array($postTypeCls, 'ctwp_submitbox_metabox'));
		add_action('save_post_creative_timeline',array($postTypeCls,'ctwp_save_post'),100 ,2);
		add_action('restrict_manage_posts',array($postTypeCls, 'ctwp_manage_posts'));
		add_filter('parse_query',array($postTypeCls, 'ctwp_parse_query'));
    }

	/**
	 * Register Creative Timeline Post
	 */
	public function creative_timeline_custom_post_type() {
		$labels = array(
			'name'               => _x('Creative Timeline', 'Post Type General Name', CTWP_DOMAIN),
			'singular_name'      => _x('Creative Timeline', 'Post Type Singular Name', CTWP_DOMAIN),
			'menu_name'          => __('Creative Timeline', CTWP_DOMAIN),
			'name_admin_bar'     => __('Creative Timeline', CTWP_DOMAIN),
			'parent_item_colon'  => __('Parent Item:', CTWP_DOMAIN),
			'all_items'          => __('Creative Timeline', CTWP_DOMAIN),
			'add_new_item'       => __('Add New Timeline', CTWP_DOMAIN),
			'add_new'            => __('Add New', CTWP_DOMAIN),
			'new_item'           => __('New Timeline', CTWP_DOMAIN),
			'edit_item'          => __('Edit Timeline', CTWP_DOMAIN),
			'update_item'        => __('Update Timeline', CTWP_DOMAIN),
			'view_item'          => __('View Timeline', CTWP_DOMAIN),
			'search_items'       => __('Search Timeline', CTWP_DOMAIN),
			'not_found'          => __('Not found', CTWP_DOMAIN),
			'not_found_in_trash' => __('Not found in Trash', CTWP_DOMAIN),
		);
		$args = array(
			'label'               => __('creative_timeline', CTWP_DOMAIN),
			'description'         => __('Timeline Post Type Description', CTWP_DOMAIN),
			'labels'              => $labels,
			'supports'            => array('title','editor','thumbnail'),
			'taxonomies'          => array('categories123'),
			'public'              => true,
			'show_ui'             => true,
			'query_var' 		  => true, 
			'rewrite' 			  => array('slug' => 'creative_timeline', 'with_front'=> false), 'capability_type' => 'page', 
			'show_in_menu'        => 'ctwp-timeline-admin-menu',
			'menu_position'       => 5,
			'show_in_admin_bar'   => true,
			'show_in_nav_menus'   => true,
			'can_export'          => true,
			'has_archive'         => true,
			'exclude_from_search' => false,
			'publicly_queryable'  => true,
			'hierarchical'        => true, 
			'menu_position'       => null, 
			'supports'            => array('title','editor','thumbnail'),
			'menu_icon'           => CTWP_URL.'assets/images/timeline-icon-small.png',
		);
		register_post_type('creative_timeline', $args);
	}
	public function register_creative_timeline_taxonomies() {
		// Category Taxonomy
		$labels = array(
			'name'              => _x('Category', CTWP_DOMAIN),
			'singular_name'    	=> _x('Category', CTWP_DOMAIN),
			'search_items'      => __('Search', CTWP_DOMAIN),
			'all_items'         => __('All category', CTWP_DOMAIN),
			'parent_item'       => __('Parent category', CTWP_DOMAIN),
			'parent_item_colon' => __('Parent category:', CTWP_DOMAIN),
			'edit_item'         => __('Edit category', CTWP_DOMAIN),
			'update_item'       => __('Update category', CTWP_DOMAIN),
			'add_new_item'      => __('Add New category', CTWP_DOMAIN),
			'menu_name'         => __('Categories', CTWP_DOMAIN),
			'name_admin_bar'    => __('Categories', CTWP_DOMAIN),
		);
		$args = array(
			'hierarchical'      => true,
			'labels'            => $labels,
			'show_ui'           => true,
			'show_admin_column' => true,
			'show_in_menu'      => 'ctwp-timeline-admin-menu',
			'query_var'         => true,
			'public'          	=> true,
			'show_in_nav_menus' => true,
			'rewrite'           => array('slug' => 'ctwp_category'),
		);
		register_taxonomy('ctwp_category', array('creative_timeline'), $args);
	}

	 // Insert default category
	public function ctwp_insert_category() {
        if(!term_exists('ctwp_category', 'ctwp_category')) {
        		wp_insert_term(
					__('Timeline Category', CTWP_DOMAIN),
					'ctwp_category',
					array(
						'description' => __('All timeline stories.', CTWP_DOMAIN),
						'slug'        => 'timeline-category',
					)
				);
            }
        }

		function ctwp_save_post($post_id, $post) {
			if ('creative_timeline' === $post->post_type) {
				if ('publish' === $post->post_status) {
						$defaults = array(
						'ctwp_category' => array('ctwp_category')
					);
					$taxonomies = get_object_taxonomies($post->post_type);
					foreach ((array) $taxonomies as $taxonomy) {
						$terms = wp_get_post_terms($post_id, $taxonomy);
						if (empty($terms) && array_key_exists($taxonomy, $defaults)) {
							wp_set_object_terms($post_id, $defaults[$taxonomy], $taxonomy);
						}
					}
				}
			}
		}
		
	/**
	 * Custom columns for all stories
	 */
	public function ctwp_add_new_columns($gallery_columns) {
		$new_columns['cb'] = '<input type="checkbox" />';
		$new_columns['title'] = _x('Timeline Title', 'column name');
		$new_columns['story_year'] = __('Timeline Year', CTWP_DOMAIN);
		$new_columns['story_date'] = __('Timeline Date', CTWP_DOMAIN);
		$new_columns['icon'] = __('Timeline Icon', CTWP_DOMAIN);
		$new_columns['date'] = _x('Published Date', 'column name');			
		return $new_columns;
    }

	/**
	 * Column handlers
	 */
	public function ctwp_custom_columns($column, $post_id) {
		$ctwp_story_type = get_post_meta($post_id, 'story_type', true);		
		$ctwp_story_date = isset($ctwp_story_type['ctwp_story_date'])?$ctwp_story_type['ctwp_story_date']:'';
		switch ($column) {
			case "story_year":			    
				$story_timestamp = strtotime($ctwp_story_date);
				if($story_timestamp !== false) {
					$story_year=date("Y", $story_timestamp);
					echo"<p><strong>" . esc_html($story_year) . "</strong></p>";
				}else{
					$ctwp_story_date = trim(str_ireplace(array('am','pm'),'',$ctwp_story_date));
					$dateobj = DateTime::createFromFormat('m/d/Y H:i',$ctwp_story_date ,wp_timezone());
					if($dateobj) {
						echo"<p><strong>" . $dateobj->format(__("Y", CTWP_DOMAIN)). "</strong></p>";
					}
				}
			break;
			case "story_date":			    
				echo"<p><strong>" . esc_html($ctwp_story_date) . "</strong></p>";
			break;
			case "icon":
				$icon = get_post_meta($post_id, 'story_icon', true);
				$icon = isset($icon['fa_field_icon'])?$icon['fa_field_icon']:'';			    
				if($icon) {
					echo '<i style="font-size:32px;" class="'.esc_attr($icon).'" aria-hidden="true"></i>';
				}else{
					echo '<i  style="font-size:32px;" class="far fa-calendar-alt" aria-hidden="true"></i>';
				}
            break;
			case 'category' :
				$terms = get_the_terms($post_id, 'ctwp_category');
				if (!empty($terms)) {
					$out = array();
					foreach ($terms as $term) {
						$out[] = sprintf('<a href="%s">%s</a>',
							esc_url(add_query_arg(array('post_type' => $post->post_type, 'ctwp_category' => $term->slug), 'edit.php')),
							esc_html(sanitize_term_field('name', $term->name, $term->term_id, 'ctwp_category', 'display'))
						);
					}
					echo join(', ', $out);
				}
				break;
			default:
				echo "<p>".esc_html_e('Not Matched', CTWP_DOMAIN)."</p>";
		}
    }

	public function ctwp_generted_page_label($states) {
		if(isset($_REQUEST['post_type']) && sanitize_key($_REQUEST['post_type']) == 'creative_timeline') {
			unset($states['scheduled']);
		}
		return $states;
	}

	public function ctwp_submitbox_metabox() {
		if(isset($_REQUEST['post']) && get_post_type($_REQUEST['post']) == 'creative_timeline' ||
		isset($_REQUEST['post_type']) && sanitize_key($_REQUEST['post_type']) == 'creative_timeline') {
			$html  = '<div class="misc-pub-section ctwp-notice">';
			$html .= '<span style="color:red;font-weight:bold;">' . __('*Please select Timeline Date / Year from settings below the Timeline content.', CTWP_DOMAIN);
			$html .= '<a href="#ctwp_post_meta"><br/>- Timeline Timeline Settings (Date/Year)</a>';
			$html .= '</span>';
			$html .= '</div>';
			echo $html;		
		}
	}

	public function ctwp_manage_posts() {
        global $typenow;
        $post_type = 'creative_timeline';
        $taxonomy  = 'ctwp_category';
        if ($typenow == $post_type) {
            $selected      = isset($_GET[$taxonomy]) ? $_GET[$taxonomy] : '';
            $info_taxonomy = get_taxonomy($taxonomy);
            wp_dropdown_categories(array(
                'show_option_all' => __("Show All {$info_taxonomy->label}", CTWP_DOMAIN),
                'taxonomy'        => $taxonomy,
                'name'            => $taxonomy,
                'orderby'         => 'name',
                'selected'        => $selected,
                'show_count'      => true,
                'hide_empty'      => true,
			));
        };
    }

	/**
     * Filter posts by taxonomy in admin
     * @author  Techeshta
     *
     */
    public function ctwp_parse_query($query) {
        global $pagenow;
        $post_type = 'creative_timeline';
        $taxonomy  = 'ctwp_category';
        $q_vars    = &$query->query_vars;
        if ($pagenow == 'edit.php' && isset($q_vars['post_type']) && $q_vars['post_type'] == $post_type && isset($q_vars[$taxonomy]) && is_numeric($q_vars[$taxonomy]) && $q_vars[$taxonomy] != 0) {
            $term = get_term_by('id', $q_vars[$taxonomy], $taxonomy);
            $q_vars[$taxonomy] = $term->slug;
        }
    }
}
CTWPPosttype::register();