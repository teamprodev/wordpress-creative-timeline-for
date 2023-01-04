<?php
/**
 * 
 * Classic Timeline Classic Style
 * 
 * @since 1.0.0
 * @version 1.0.0
 * 
 */

$ctwp_options_arr = get_option('creative_timeline_settings');

/** 
 * Get Timeline Contents, Meta & Images
 */
$ctwp_post_title_on_off = isset($ctwp_options_arr['ctwp_timeline_post_title']['ctwp_post_title_on_off'])?$ctwp_options_arr['ctwp_timeline_post_title']['ctwp_post_title_on_off']:'1';
$ctwp_post_content_on_off = isset($ctwp_options_arr['ctwp_timeline_post_content']['ctwp_post_content_on_off'])?$ctwp_options_arr['ctwp_timeline_post_content']['ctwp_post_content_on_off']:'1';
$ctwp_post_date_on_off = isset($ctwp_options_arr['ctwp_timeline_post_date']['ctwp_post_date_on_off'])?$ctwp_options_arr['ctwp_timeline_post_date']['ctwp_post_date_on_off']:'1';
$ctwp_post_author_on_off = isset($ctwp_options_arr['ctwp_timeline_post_meta']['ctwp_post_author_on_off'])?$ctwp_options_arr['ctwp_timeline_post_meta']['ctwp_post_author_on_off']:'1';
$ctwp_post_comment_on_off = isset($ctwp_options_arr['ctwp_timeline_post_meta']['ctwp_post_comment_on_off'])?$ctwp_options_arr['ctwp_timeline_post_meta']['ctwp_post_comment_on_off']:'1';
$ctwp_post_image_on_off = isset($ctwp_options_arr['ctwp_timeline_post_image']['ctwp_post_image_on_off'])?$ctwp_options_arr['ctwp_timeline_post_image']['ctwp_post_image_on_off']:'1';
$ctwp_post_social_on_off = isset($ctwp_options_arr['ctwp_timeline_post_social_share']['ctwp_post_social_on_off'])?$ctwp_options_arr['ctwp_timeline_post_social_share']['ctwp_post_social_on_off']:'1';
$ctwp_timeline_post_readmore_btn_on_off = isset($ctwp_options_arr['ctwp_timeline_post_readmore_btn']['ctwp_timeline_post_readmore_btn_on_off'])?$ctwp_options_arr['ctwp_timeline_post_readmore_btn']['ctwp_timeline_post_readmore_btn_on_off']:'1';
$ctwp_timeline_post_readmore_btn_text = isset($ctwp_options_arr['ctwp_timeline_post_readmore_btn']['ctwp_timeline_post_readmore_btn_text'])?$ctwp_options_arr['ctwp_timeline_post_readmore_btn']['ctwp_timeline_post_readmore_btn_text']:'';
$ctwp_post_cat_on_off = isset($ctwp_options_arr['ctwp_timeline_post_cat']['ctwp_post_cat_on_off'])?$ctwp_options_arr['ctwp_timeline_post_cat']['ctwp_post_cat_on_off']:'1';

/**
 * Timeline Typography Option
 */
$ctwp_main_title_typo =  isset($ctwp_options_arr['ctwp_main_title_typo'])?CTWP_styles::ctwp_get_typeo_output($ctwp_options_arr['ctwp_main_title_typo']):'';
$ctwp_post_title_tag = isset($ctwp_options_arr['ctwp_timeline_post_title']['ctwp_post_title_tag'])?$ctwp_options_arr['ctwp_timeline_post_title']['ctwp_post_title_tag']:'H2';
$ctwp_post_title_typo =  isset($ctwp_options_arr['ctwp_timeline_post_title']['ctwp_post_title_typo'])?CTWP_styles::ctwp_get_typeo_output($ctwp_options_arr['ctwp_timeline_post_title']['ctwp_post_title_typo']):'';
$ctwp_post_content_length = isset($ctwp_options_arr['ctwp_timeline_post_content']['ctwp_post_content_length'])?$ctwp_options_arr['ctwp_timeline_post_content']['ctwp_post_content_length']:'';
$ctwp_post_content_typo =  isset($ctwp_options_arr['ctwp_timeline_post_content']['ctwp_post_content_typo'])?CTWP_styles::ctwp_get_typeo_output($ctwp_options_arr['ctwp_timeline_post_content']['ctwp_post_content_typo']):'';
$ctwp_post_date_typo =  isset($ctwp_options_arr['ctwp_timeline_post_date']['ctwp_post_date_typo'])?CTWP_styles::ctwp_get_typeo_output($ctwp_options_arr['ctwp_timeline_post_date']['ctwp_post_date_typo']):'';
$ctwp_post_meta_typo =  isset($ctwp_options_arr['ctwp_timeline_post_meta']['ctwp_post_meta_typo'])?CTWP_styles::ctwp_get_typeo_output($ctwp_options_arr['ctwp_timeline_post_meta']['ctwp_post_meta_typo']):'';
$ctwp_post_readmore_btn_typo =isset($ctwp_options_arr['ctwp_timeline_post_readmore_btn']['ctwp_post_readmore_btn_typo'])?CTWP_styles::ctwp_get_typeo_output($ctwp_options_arr['ctwp_timeline_post_readmore_btn']['ctwp_post_readmore_btn_typo']):'';
$ctwp_post_readmore_btn_link = isset($ctwp_options_arr['ctwp_timeline_post_readmore_btn']['ctwp_post_readmore_btn_link'])?$ctwp_options_arr['ctwp_timeline_post_readmore_btn']['ctwp_post_readmore_btn_link']:'_self';
$ctwp_post_cat_typo =  isset($ctwp_options_arr['ctwp_timeline_post_cat']['ctwp_post_cat_typo'])?CTWP_styles::ctwp_get_typeo_output($ctwp_options_arr['ctwp_timeline_post_cat']['ctwp_post_cat_typo']):'';

/**
 * Timeline Style Option
 */
$ctwp_timeline_title_color = isset($ctwp_options_arr['ctwp_timeline_title_color'])?$ctwp_options_arr['ctwp_timeline_title_color']:'0';
$ctwp_timeline_color = isset($ctwp_options_arr['ctwp_timeline_color'])?$ctwp_options_arr['ctwp_timeline_color']:'0';
$ctwp_post_title_color = isset($ctwp_options_arr['ctwp_timeline_post_title']['ctwp_post_title_color'])?$ctwp_options_arr['ctwp_timeline_post_title']['ctwp_post_title_color']:'0';
$ctwp_post_title_hover_color = isset($ctwp_options_arr['ctwp_timeline_post_title']['ctwp_post_title_hover_color'])?$ctwp_options_arr['ctwp_timeline_post_title']['ctwp_post_title_hover_color']:'0';
$ctwp_post_content_box_color = isset($ctwp_options_arr['ctwp_timeline_post_content']['ctwp_post_content_box_color'])?$ctwp_options_arr['ctwp_timeline_post_content']['ctwp_post_content_box_color']:'0';
$ctwp_post_content_color = isset($ctwp_options_arr['ctwp_timeline_post_content']['ctwp_post_content_color'])?$ctwp_options_arr['ctwp_timeline_post_content']['ctwp_post_content_color']:'0';
$ctwp_post_ct_box_shadow_color = isset($ctwp_options_arr['ctwp_timeline_post_content']['ctwp_post_ct_box_shadow_color'])?$ctwp_options_arr['ctwp_timeline_post_content']['ctwp_post_ct_box_shadow_color']:'0';
$ctwp_post_date_color = isset($ctwp_options_arr['ctwp_timeline_post_date']['ctwp_post_date_color'])?$ctwp_options_arr['ctwp_timeline_post_date']['ctwp_post_date_color']:'0';
$ctwp_post_date_hover_color = isset($ctwp_options_arr['ctwp_timeline_post_date']['ctwp_post_date_hover_color'])?$ctwp_options_arr['ctwp_timeline_post_date']['ctwp_post_date_hover_color']:'0';
$ctwp_post_meta_color = isset($ctwp_options_arr['ctwp_timeline_post_meta']['ctwp_post_meta_color'])?$ctwp_options_arr['ctwp_timeline_post_meta']['ctwp_post_meta_color']:'0';
$ctwp_post_meta_hover_color = isset($ctwp_options_arr['ctwp_timeline_post_meta']['ctwp_post_meta_hover_color'])?$ctwp_options_arr['ctwp_timeline_post_meta']['ctwp_post_meta_hover_color']:'0';
$ctwp_post_social_color = isset($ctwp_options_arr['ctwp_timeline_post_social_share']['ctwp_post_social_color'])?$ctwp_options_arr['ctwp_timeline_post_social_share']['ctwp_post_social_color']:'0';
$ctwp_post_social_hover_color = isset($ctwp_options_arr['ctwp_timeline_post_social_share']['ctwp_post_social_hover_color'])?$ctwp_options_arr['ctwp_timeline_post_social_share']['ctwp_post_social_hover_color']:'0';
$ctwp_post_readmore_btn_color = isset($ctwp_options_arr['ctwp_timeline_post_readmore_btn']['ctwp_post_readmore_btn_color'])?$ctwp_options_arr['ctwp_timeline_post_readmore_btn']['ctwp_post_readmore_btn_color']:'0';
$ctwp_post_readmore_btn_hover_color = isset($ctwp_options_arr['ctwp_timeline_post_readmore_btn']['ctwp_post_readmore_btn_hover_color'])?$ctwp_options_arr['ctwp_timeline_post_readmore_btn']['ctwp_post_readmore_btn_hover_color']:'0';
$ctwp_post_readmore_btn_bg_color = isset($ctwp_options_arr['ctwp_timeline_post_readmore_btn']['ctwp_post_readmore_btn_bg_color'])?$ctwp_options_arr['ctwp_timeline_post_readmore_btn']['ctwp_post_readmore_btn_bg_color']:'0';
$ctwp_post_readmore_btn_bg_hover_color = isset($ctwp_options_arr['ctwp_timeline_post_readmore_btn']['ctwp_post_readmore_btn_bg_hover_color'])?$ctwp_options_arr['ctwp_timeline_post_readmore_btn']['ctwp_post_readmore_btn_bg_hover_color']:'0';
$ctwp_post_icon_color = isset($ctwp_options_arr['timeline_post_icon']['ctwp_post_icon_color'])?$ctwp_options_arr['timeline_post_icon']['ctwp_post_icon_color']:'0';
$ctwp_post_icon_hover_color = isset($ctwp_options_arr['timeline_post_icon']['ctwp_post_icon_hover_color'])?$ctwp_options_arr['timeline_post_icon']['ctwp_post_icon_hover_color']:'0';
$ctwp_post_icon_bg_color = isset($ctwp_options_arr['timeline_post_icon']['ctwp_post_icon_bg_color'])?$ctwp_options_arr['timeline_post_icon']['ctwp_post_icon_bg_color']:'0';
$ctwp_post_icon_bg_hover_color = isset($ctwp_options_arr['timeline_post_icon']['ctwp_post_icon_bg_hover_color'])?$ctwp_options_arr['timeline_post_icon']['ctwp_post_icon_bg_hover_color']:'0';
$ctwp_post_icon_border_color = isset($ctwp_options_arr['timeline_post_icon']['ctwp_post_icon_border_color'])?$ctwp_options_arr['timeline_post_icon']['ctwp_post_icon_border_color']:'0';
$ctwp_post_cat_color = isset($ctwp_options_arr['ctwp_timeline_post_cat']['ctwp_post_cat_color'])?$ctwp_options_arr['ctwp_timeline_post_cat']['ctwp_post_cat_color']:'0';
$ctwp_post_cat_hover_color = isset($ctwp_options_arr['ctwp_timeline_post_cat']['ctwp_post_cat_hover_color'])?$ctwp_options_arr['ctwp_timeline_post_cat']['ctwp_post_cat_hover_color']:'0';
$ctwp_post_cat_bg_color = isset($ctwp_options_arr['ctwp_timeline_post_cat']['ctwp_post_cat_bg_color'])?$ctwp_options_arr['ctwp_timeline_post_cat']['ctwp_post_cat_bg_color']:'0';
$category = $attribute['category'];
if ($ctwp_loop->have_posts()) {	?>
	<div class="ctwp-avatar-row">
		<div class="ctwp-avatar-container row">
			<?php if($ctwp_head_on_off) {
				if (isset($timeline_title) && !empty($timeline_title)) {?>
					<<?php echo esc_attr($ctwp_title_tag);?> class="ctwp-heading"><?php echo esc_attr($timeline_title);?></<?php echo esc_attr($ctwp_title_tag);?>>
			<?php } }?>
		</div> 
		<div class="ctwp-avatar-image"><?php
			if (isset($user_avatar[0]) && !empty($user_avatar[0])) {?>
			<img class="ctwp-center-block img-responsive img-circle" alt="<?php echo esc_attr($timeline_title);?>" src="<?php echo esc_url($user_avatar[0]);?>">
			<?php  } ?>
		</div> 
	</div>
	<div class="ctwp-classic classic-changing-<?php echo esc_attr(get_the_ID());?> ctwp-vertical-line <?php echo esc_attr($ctwp_animation); ?>" data-id="<?php echo esc_attr($ctwp_animation); ?>">
		<?php	
		while ($ctwp_loop->have_posts()) : $ctwp_loop->the_post();
		$p_id = get_the_ID();
		global $post;?>
			<div class="ctwp-entry" id="<?php echo esc_attr('ctwp-'.get_the_ID());?>">
				<div class="ctwp-entry-inner">
					<div class="ctwp-icon"><?php
						$post_icon=CTWP_functions::get_fa(true);							
						if(isset($post_icon)) {
							$icon = $post_icon;
						}else{
							$icon = '<i class="far fa-calendar-alt" aria-hidden="true"></i>';
						}
						if(isset($attribute['icons']) && $attribute['icons'] == "YES") {
								echo $icon; 
						}
						else{
								echo '<i class="far fa-calendar-alt" aria-hidden="true"></i>'; 
							}
						?>
					</div>
					<div class="ctwp-data-container">
						<div class="ctwp-featured-container">
							<?php if($ctwp_post_image_on_off && has_post_thumbnail()) { ?>
								<div class="ctwp-feature-image"><?php the_post_thumbnail(); ?></div>
							<?php } ?>
							<div class="ctwp-categories-container">
								<?php if($ctwp_post_cat_on_off) { ?>
										<?php 
										if($category == '') { 
										$terms = get_the_terms($post->ID , 'ctwp_category');
										if (is_array($terms) || is_object($terms)) {
											foreach ($terms as $term) { 
												$category_link = get_category_link($term->term_id); ?>
												<span class="ctwp-categories"><a href="<?php echo esc_url($category_link); ?>"><?php esc_html_e($term->name); ?></a></span>
									<?php } } } }?>
									<?php if($ctwp_post_title_on_off) { ?>
										<<?php echo esc_attr($ctwp_post_title_tag);?> class="ctwp-title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></<?php echo esc_attr($ctwp_post_title_tag);?>>
									<?php } ?>
							</div>
						</div>
						<div class="ctwp-meta-container">
							<?php if($ctwp_post_date_on_off) { 
								$p_id = get_the_ID();
								$date_format=$this->ctwp_date_formats($attribute['date-format'],$ctwp_options_arr);     
								$posted_date = CTWP_functions::ctwp_get_story_date($p_id,$date_format);   
								if(!empty($posted_date)) { ?>
									<div class="ctwp-meta-date">
										<span class="post-date"><i class="fa fa-calendar" aria-hidden="true"></i><?php echo __($posted_date); ?></span>
									</div>
								<?php }
								else { ?>
									<div class="ctwp-meta-date">
										<span class="post-date"><i class="fa fa-calendar" aria-hidden="true"></i><?php echo get_the_date(esc_html('d M, Y')); ?></span>
									</div>
                            <?php } }?>
							<div class="ctwp-meta-data">
								<?php if($ctwp_post_author_on_off) { ?>
									<span class="ctwp-meta author"><i class="fa fa-user-o" aria-hidden="true"></i><a href="<?php echo esc_url(get_author_posts_url(get_the_author_meta('ID'))); ?>"><?php echo esc_html(get_the_author()); ?></a></span>
								<?php } ?>
								<?php if($ctwp_post_comment_on_off) { ?>
									<?php if (!post_password_required() && (comments_open() || get_comments_number())) { ?>
										<span class="ctwp-meta comment"><i class="fa fa-comments-o" aria-hidden="true"></i><?php comments_popup_link('0', '1', '%', '', ''); ?></span>
									<?php } ?>
								<?php } ?>
							</div>
						</div>
						<?php if($ctwp_post_content_on_off) { ?>
						<p class="ctwp-content"> <?php 
							if(!empty($ctwp_post_content_length)) { ?>
								<?php echo __(wp_trim_words(get_the_excerpt(), $ctwp_post_content_length)); ?>
							<?php } else { ?>
								<?php echo __(wp_trim_words(get_the_excerpt(), 25)); 
							} ?>	
						</p>
						<?php } ?>
						<div class="ctwp-rmss-container">
							<?php if($ctwp_timeline_post_readmore_btn_on_off) { ?>
								<div class="ctwp-read-more">
									<a class="read_more" target="<?php echo esc_attr($ctwp_post_readmore_btn_link); ?>" href=" <?php the_permalink(); ?>"><?php echo esc_html($ctwp_timeline_post_readmore_btn_text); ?></a>
								</div>
							<?php } ?>
							<?php if($ctwp_post_social_on_off) { ?>
								<div class="ctwp-social-share">
									<span>
										<a href=<?php echo esc_url("https://www.facebook.com/sharer/sharer.php?u=".get_the_permalink(), CTWP_DOMAIN); ?>><i class="fab fa-facebook-f" aria-hidden="true"></i></a>
									</span>
									<span>
										<a href=<?php echo esc_url("https://twitter.com/intent/tweet?url=".get_the_permalink(), CTWP_DOMAIN); ?>><i class="fab fa-twitter" aria-hidden="true"></i></a>
									</span>
									<span>
										<a href=<?php echo esc_url("https://www.linkedin.com/shareArticle?url=".get_the_permalink(), CTWP_DOMAIN); ?>><i class="fab fa-linkedin-in" aria-hidden="true"></i></a>
									</span>
								</div>
							<?php } ?>
						</div>
					</div>
				</div>
				<style>
					<?php
					$ctwp_custom_color = get_post_meta($p_id, 'ctwp_custom_color', true); 
					$ctwp_post_custom_color = isset($ctwp_custom_color['ctwp_post_custom_color'])?$ctwp_custom_color['ctwp_post_custom_color']:'1';
					if($ctwp_post_custom_color) {
						$ctwp_title_color = isset($ctwp_custom_color['ctwp_title_color'])?$ctwp_custom_color['ctwp_title_color']:'0';
						$ctwp_title_hover_color = isset($ctwp_custom_color['ctwp_title_hover_color'])?$ctwp_custom_color['ctwp_title_hover_color']:'0';
						$ctwp_content_color = isset($ctwp_custom_color['ctwp_content_color'])?$ctwp_custom_color['ctwp_content_color']:'0';
						$ctwp_content_box_color = isset($ctwp_custom_color['ctwp_content_box_color'])?$ctwp_custom_color['ctwp_content_box_color']:'0';
						$ctwp_cat_color = isset($ctwp_custom_color['ctwp_cat_color'])?$ctwp_custom_color['ctwp_cat_color']:'0';
						$ctwp_cat_hover_color = isset($ctwp_custom_color['ctwp_cat_hover_color'])?$ctwp_custom_color['ctwp_cat_hover_color']:'0';
						$ctwp_cat_bg_color = isset($ctwp_custom_color['ctwp_cat_bg_color'])?$ctwp_custom_color['ctwp_cat_bg_color']:'0';
						$ctwp_date_color = isset($ctwp_custom_color['ctwp_date_color'])?$ctwp_custom_color['ctwp_date_color']:'0';
						$ctwp_date_hover_color = isset($ctwp_custom_color['ctwp_date_hover_color'])?$ctwp_custom_color['ctwp_date_hover_color']:'0';
						$ctwp_meta_color = isset($ctwp_custom_color['ctwp_meta_color'])?$ctwp_custom_color['ctwp_meta_color']:'0';
						$ctwp_meta_hover_color = isset($ctwp_custom_color['ctwp_meta_hover_color'])?$ctwp_custom_color['ctwp_meta_hover_color']:'0';
						$ctwp_social_color = isset($ctwp_custom_color['ctwp_social_color'])?$ctwp_custom_color['ctwp_social_color']:'0';
						$ctwp_social_hover_color = isset($ctwp_custom_color['ctwp_social_hover_color'])?$ctwp_custom_color['ctwp_social_hover_color']:'0';
						$ctwp_readmore_color = isset($ctwp_custom_color['ctwp_readmore_color'])?$ctwp_custom_color['ctwp_readmore_color']:'0';
						$ctwp_readmore_hover_color = isset($ctwp_custom_color['ctwp_readmore_hover_color'])?$ctwp_custom_color['ctwp_readmore_hover_color']:'0';
						$ctwp_readmore_bg_color = isset($ctwp_custom_color['ctwp_readmore_bg_color'])?$ctwp_custom_color['ctwp_readmore_bg_color']:'0';
						$ctwp_readmore_bg_hover_color = isset($ctwp_custom_color['ctwp_readmore_bg_hover_color'])?$ctwp_custom_color['ctwp_readmore_bg_hover_color']:'0';
						$ctwp_icon_color = isset($ctwp_custom_color['ctwp_icon_color'])?$ctwp_custom_color['ctwp_icon_color']:'0';
						$ctwp_icon_hover_color = isset($ctwp_custom_color['ctwp_icon_hover_color'])?$ctwp_custom_color['ctwp_icon_hover_color']:'0';
						$ctwp_icon_bg_color = isset($ctwp_custom_color['ctwp_icon_bg_color'])?$ctwp_custom_color['ctwp_icon_bg_color']:'0';
						$ctwp_icon_bg_hover_color = isset($ctwp_custom_color['ctwp_icon_bg_hover_color'])?$ctwp_custom_color['ctwp_icon_bg_hover_color']:'0';
						$ctwp_icon_border_color = isset($ctwp_custom_color['ctwp_icon_border_color'])?$ctwp_custom_color['ctwp_icon_border_color']:'0';
						?>
						#ctwp-<?php echo esc_attr(get_the_ID());?> .ctwp-title a{	
							color:<?php esc_attr_e($ctwp_title_color); ?>;
						} 
						#ctwp-<?php echo esc_attr(get_the_ID());?> .ctwp-title a:hover{	
							color:<?php esc_attr_e($ctwp_title_hover_color); ?>;
						}
						#ctwp-<?php echo esc_attr(get_the_ID());?> .ctwp-content{
							color:<?php esc_attr_e($ctwp_content_color); ?>
						}
						#ctwp-<?php echo esc_attr(get_the_ID());?> .post-date{
							color:<?php esc_attr_e($ctwp_date_color);?>
						}
						#ctwp-<?php echo esc_attr(get_the_ID());?> .post-date:hover{
							color:<?php esc_attr_e($ctwp_date_hover_color);?>
						}
						#ctwp-<?php echo esc_attr(get_the_ID());?> .ctwp-meta.author,#ctwp-<?php echo esc_attr(get_the_ID());?> .ctwp-meta.author a,#ctwp-<?php echo esc_attr(get_the_ID());?> .ctwp-meta.comment,#ctwp-<?php echo esc_attr(get_the_ID());?> .ctwp-meta.comment a{
							color:<?php esc_attr_e($ctwp_meta_color); ?>
						}
						#ctwp-<?php echo esc_attr(get_the_ID());?> .ctwp-meta.author:hover,#ctwp-<?php echo esc_attr(get_the_ID());?> .ctwp-meta.author a:hover,#ctwp-<?php echo esc_attr(get_the_ID());?> .ctwp-meta.comment:hover,#ctwp-<?php echo esc_attr(get_the_ID());?> .ctwp-meta.comment a:hover{
							color:<?php esc_attr_e($ctwp_meta_hover_color); ?>
						}
						#ctwp-<?php echo esc_attr(get_the_ID());?> .ctwp-entry-inner .ctwp-data-container{
							background-color: <?php esc_attr_e($ctwp_content_box_color); ?>;
						}
						#ctwp-<?php echo esc_attr(get_the_ID());?>.ctwp-entry:nth-child(odd) .ctwp-data-container:before{
							border-left-color: <?php esc_attr_e($ctwp_content_box_color); ?>;
                        }
						#ctwp-<?php echo esc_attr(get_the_ID());?>.ctwp-entry:nth-child(even) .ctwp-data-container:before{
							border-right-color: <?php esc_attr_e($ctwp_content_box_color); ?>;	
						}
						#ctwp-<?php echo esc_attr(get_the_ID());?> .ctwp-social-share a{
							color:<?php esc_attr_e($ctwp_social_color); ?>;
						}
						#ctwp-<?php echo esc_attr(get_the_ID());?> .ctwp-social-share a:hover{
							color:<?php esc_attr_e($ctwp_social_hover_color); ?>;
						}
						#ctwp-<?php echo esc_attr(get_the_ID());?> .ctwp-read-more a{
							color:<?php esc_attr_e($ctwp_readmore_color); ?>;
							background-color:<?php esc_attr_e($ctwp_readmore_bg_color); ?>;
						}
						#ctwp-<?php echo esc_attr(get_the_ID());?> .ctwp-read-more a:hover{
							color:<?php esc_attr_e($ctwp_readmore_hover_color); ?>;
							background-color:<?php esc_attr_e($ctwp_readmore_bg_hover_color); ?>;
						}
						#ctwp-<?php echo esc_attr(get_the_ID());?> .ctwp-icon i{ 
							color:<?php esc_attr_e($ctwp_icon_color); ?>;
						}
						#ctwp-<?php echo esc_attr(get_the_ID());?> .ctwp-icon:hover i{ 
							color:<?php esc_attr_e($ctwp_icon_hover_color); ?>;
						}
						#ctwp-<?php echo esc_attr(get_the_ID());?> .ctwp-icon{ 
							background-color:<?php esc_attr_e($ctwp_icon_bg_color); ?>;
							border-color: <?php esc_attr_e($ctwp_icon_border_color); ?>;
						}
						#ctwp-<?php echo esc_attr(get_the_ID());?> .ctwp-icon:hover{ 
							background-color:<?php esc_attr_e($ctwp_icon_bg_hover_color); ?>;
						}
						#ctwp-<?php echo esc_attr(get_the_ID());?> .ctwp-categories a{	
							color:<?php esc_attr_e($ctwp_cat_color); ?>
						}
						#ctwp-<?php echo esc_attr(get_the_ID());?> .ctwp-categories a:hover{	
							color:<?php esc_attr_e($ctwp_cat_hover_color); ?>
						}
						#ctwp-<?php echo esc_attr(get_the_ID());?> .ctwp-categories {
                            background:<?php esc_attr_e($ctwp_cat_bg_color); ?>;
                        }
					<?php } ?>
				</style>
			</div>
		<?php endwhile; ?>
		<?php wp_reset_postdata();?>
	</div><?php
} else {
	$ctwp_html_no_cont  = '';
	$ctwp_html_no_cont .= '<div class="no-content"><h4>';
	$ctwp_html_no_cont .= $ctwp_no_posts;
	$ctwp_html_no_cont .= esc_html__('Sorry,You have not added any Timeline Post', CTWP_DOMAIN);
	$ctwp_html_no_cont .= '</h4></div>';
} ?>

<style>
	.ctwp-avatar-row .ctwp-heading, .ctwp-avatar-container { 
		<?php esc_attr_e($ctwp_main_title_typo); ?>
		color:<?php esc_attr_e($ctwp_timeline_title_color); ?>;
	}	
	.ctwp-classic.classic-changing-<?php echo esc_attr(get_the_ID());?>.ctwp-vertical-line:before {
		background-color:<?php esc_attr_e($ctwp_timeline_color); ?>;
	}
	.ctwp-classic.classic-changing-<?php echo esc_attr(get_the_ID());?> .ctwp-title a{
		<?php esc_attr_e($ctwp_post_title_typo); ?>	
		color:<?php esc_attr_e($ctwp_post_title_color); ?>;
	}
	.ctwp-classic.classic-changing-<?php echo esc_attr(get_the_ID());?> .ctwp-title a:hover{	
		color:<?php esc_attr_e($ctwp_post_title_hover_color); ?>;
	}
	.ctwp-classic.classic-changing-<?php echo esc_attr(get_the_ID());?> .ctwp-content{
		<?php esc_attr_e($ctwp_post_content_typo); ?>
		color:<?php esc_attr_e($ctwp_post_content_color); ?>
	}
	.ctwp-classic.classic-changing-<?php echo esc_attr(get_the_ID());?> .post-date{
		<?php esc_attr_e($ctwp_post_date_typo); ?>	
		color:<?php esc_attr_e($ctwp_post_date_color);?>
	}
	.ctwp-classic.classic-changing-<?php echo esc_attr(get_the_ID());?> .post-date:hover{
		color:<?php esc_attr_e($ctwp_post_date_hover_color);?>
	}
	.ctwp-classic.classic-changing-<?php echo esc_attr(get_the_ID());?> .ctwp-meta.author,.ctwp-classic.classic-changing-<?php echo esc_attr(get_the_ID());?> .ctwp-meta.author a,.ctwp-classic.classic-changing-<?php echo esc_attr(get_the_ID());?> .ctwp-meta.comment,.ctwp-classic.classic-changing-<?php echo esc_attr(get_the_ID());?> .ctwp-meta.comment a{
		<?php esc_attr_e($ctwp_post_meta_typo); ?>	
		color:<?php esc_attr_e($ctwp_post_meta_color); ?>
	}
	.ctwp-classic.classic-changing-<?php echo esc_attr(get_the_ID());?> .ctwp-meta.author:hover,.ctwp-classic.classic-changing-<?php echo esc_attr(get_the_ID());?> .ctwp-meta.author a:hover,.ctwp-classic.classic-changing-<?php echo esc_attr(get_the_ID());?> .ctwp-meta.comment:hover,.ctwp-classic.classic-changing-<?php echo esc_attr(get_the_ID());?> .ctwp-meta.comment a:hover{
		color:<?php esc_attr_e($ctwp_post_meta_hover_color); ?>
	}
	.ctwp-classic.classic-changing-<?php echo esc_attr(get_the_ID());?> .ctwp-read-more{
		<?php esc_attr_e($ctwp_post_readmore_btn_typo); ?>	
	}
	.ctwp-classic.classic-changing-<?php echo esc_attr(get_the_ID());?> .ctwp-entry .ctwp-entry-inner .ctwp-data-container  {
		background-color:<?php esc_attr_e($ctwp_post_content_box_color); ?>;
	}
	.ctwp-classic.classic-changing-<?php echo esc_attr(get_the_ID());?> .ctwp-entry:nth-child(odd) .ctwp-data-container:before{
		border-left-color: <?php esc_attr_e($ctwp_post_content_box_color); ?>;
	}
	.ctwp-classic.classic-changing-<?php echo esc_attr(get_the_ID());?> .ctwp-entry:nth-child(even) .ctwp-data-container:before{
		border-right-color: <?php esc_attr_e($ctwp_post_content_box_color); ?>;
	}
	.ctwp-classic.classic-changing-<?php echo esc_attr(get_the_ID());?> .ctwp-social-share a{
		color:<?php esc_attr_e($ctwp_post_social_color); ?>;
	}
	.ctwp-classic.classic-changing-<?php echo esc_attr(get_the_ID());?> .ctwp-social-share a:hover{
		color:<?php esc_attr_e($ctwp_post_social_hover_color); ?>;
	}
	.ctwp-classic.classic-changing-<?php echo esc_attr(get_the_ID());?> .ctwp-read-more a{
		color:<?php esc_attr_e($ctwp_post_readmore_btn_color); ?>;
		background-color:<?php esc_attr_e($ctwp_post_readmore_btn_bg_color); ?>;
	}
	.ctwp-classic.classic-changing-<?php echo esc_attr(get_the_ID());?> .ctwp-read-more a:hover{
		color:<?php esc_attr_e($ctwp_post_readmore_btn_hover_color); ?>;
		background-color:<?php esc_attr_e($ctwp_post_readmore_btn_bg_hover_color); ?>;
	}
	.ctwp-classic.classic-changing-<?php echo esc_attr(get_the_ID());?> .ctwp-icon i{ 
		color:<?php esc_attr_e($ctwp_post_icon_color); ?>;
	}
	.ctwp-classic.classic-changing-<?php echo esc_attr(get_the_ID());?> .ctwp-icon:hover i{ 
		color:<?php esc_attr_e($ctwp_post_icon_hover_color); ?>;
	}
	.ctwp-classic.classic-changing-<?php echo esc_attr(get_the_ID());?> .ctwp-icon{ 
		background-color:<?php esc_attr_e($ctwp_post_icon_bg_color); ?>;
		border-color: <?php esc_attr_e($ctwp_post_icon_border_color); ?>;
	}
	.ctwp-classic.classic-changing-<?php echo esc_attr(get_the_ID());?> .ctwp-icon:hover{ 
		background-color:<?php esc_attr_e($ctwp_post_icon_bg_hover_color); ?>;
	}
	.ctwp-classic.classic-changing-<?php echo esc_attr(get_the_ID());?> .ctwp-entry .ctwp-entry-inner .ctwp-data-container{
		box-shadow: 0px 0px 10px <?php esc_attr_e($ctwp_post_ct_box_shadow_color); ?>;
	}
	.ctwp-classic.classic-changing-<?php echo esc_attr(get_the_ID());?> .ctwp-categories a{
		<?php esc_attr_e($ctwp_post_cat_typo); ?>	
		color:<?php esc_attr_e($ctwp_post_cat_color); ?>
	}
	.ctwp-classic.classic-changing-<?php echo esc_attr(get_the_ID());?> .ctwp-categories a:hover{	
		color:<?php esc_attr_e($ctwp_post_cat_hover_color); ?>
	} 
	.ctwp-classic.classic-changing-<?php echo esc_attr(get_the_ID());?> .ctwp-categories {
		background:<?php esc_attr_e($ctwp_post_cat_bg_color); ?>; 
	}
</style>