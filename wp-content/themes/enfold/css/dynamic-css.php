<?php
/*
 * ATTENTION: Changes to this file will only be visible in your frontend after you have re-saved your Themes Styling Page
 * ==========
 *
 * @since 5.7 refactored to support var() instead of fixed color values
 */
if( ! defined( 'ABSPATH' ) ) {  exit;  }    // Exit if accessed directly


/*
This file holds ALL color information of the theme that is applied with the styling backend admin panel.
It is recommended to not edit this file, instead create new styles in custom.css and overwrite the styles within this file

Example of available values
$bg 				=> #222222
$bg2 				=> #f8f8f8
$primary 			=> #c8ccc2
$secondary			=> #182402
$color	 			=> #ffffff
$border 			=> #e1e1e1
$img 				=> /wp-content/themes/skylink/images/background-images/dashed-cross-dark.png
$pos 				=> top left
$repeat 			=> no-repeat
$attach 			=> scroll
$heading 			=> #eeeeee
$meta 				=> #888888
$background_image	=> #222222 url(/wp-content/themes/skylink/images/background-images/dashed-cross-dark.png) top left no-repeat scroll
$default_font_size  => empty or a px size
*/
global $avia_config;

$output = '';
$body_color = '';
$color_set_var = null;

/**
 * Add theme options colors as CSS variables
 *
 * @since 5.2
 * @param boolean $supress_variables
 * @return boolean
 */
if( false === apply_filters( 'avf_supress_css_theme_variables', false ) )
{
	$color_set_var = [];

	$output .= "
:root {
";
	//	variables are in scope of includes\admin\register-dynamic-styles.php
	foreach( $color_set as $section_key => $colors )
	{
		$color_set_var[ $section_key] = [];

		$var_key = str_replace( '_', '-', $section_key );

		foreach( $colors as $color_key => $color )
		{
			if( 'img' == $color_key )
			{
				// skip as updated to "background-image" in avia_prepare_dynamic_styles()
				continue;
			}

			$color_var_key = str_replace( '_', '-', $color_key );

			$var_string = "--enfold-{$var_key}-{$color_var_key}";

			$color_set_var[ $section_key ][ $color_key ] = "var({$var_string})";
			$output .= "{$var_string}: {$color};\n";
		}
	}

	//	special case colors
	if( ! empty( $options['burger_color'] ) )
	{
		$output .= "--enfold-header_burger_color: {$options['burger_color']};\n";
		$avia_config['backend_colors']['burger_color'] = 'var(--enfold-header_burger_color)';
	}
	else
	{
		$output .= "--enfold-header_burger_color: inherit;\n";
	}

	if( ! empty( $options['header_replacement_menu'] ) )
	{
		$output .= "--enfold-header_replacement_menu_color: {$options['header_replacement_menu']};\n";
		$avia_config['backend_colors']['menu_transparent'] = 'var(--enfold-header_replacement_menu_color)';
	}
	else
	{
		$output .= "--enfold-header_replacement_menu_color: inherit;\n";
	}

	if( ! empty( $options['header_replacement_menu_hover'] ) )
	{
		$output .= "--enfold-header_replacement_menu_hover_color: {$options['header_replacement_menu_hover']};\n";
		$avia_config['backend_colors']['menu_transparent_hover'] = 'var(--enfold-header_replacement_menu_hover_color)';
	}
	else
	{
		$output .= "--enfold-header_replacement_menu_hover_color: inherit;\n";
	}

	//	fixed values from base.css
	$output .= '--enfold-font-family-theme-body: "HelveticaNeue", "Helvetica Neue", Helvetica, Arial, sans-serif;' . "\n";
	$output .= "--enfold-font-size-theme-content: 13px;\n";
	$output .= "--enfold-font-size-theme-h1: 34px;\n";
	$output .= "--enfold-font-size-theme-h2: 28px;\n";
	$output .= "--enfold-font-size-theme-h3: 20px;\n";
	$output .= "--enfold-font-size-theme-h4: 18px;\n";
	$output .= "--enfold-font-size-theme-h5: 16px;\n";
	$output .= "--enfold-font-size-theme-h6: 14px;\n";

	//	responsive typography
	if( ! empty( $typos ) )
	{
		$output .= \enfold\styling\Responsive_Typo()->create_css_vars( $typos );
	}

	/**
	 * before :root is closed - intended to add additional var defs
	 *
	 * @since 5.7
	 * @param string $output
	 * @return string
	 */
	$output .= apply_filters( 'avf_dynamic_css_additional_vars', '' );

	$output .= "
}

	";
}

$avia_config['backend_colors']['color_set_var'] = $color_set_var;

/**
 * :root is closed - intended to add :root e.g. wrapped in media query
 *
 * @since 5.7
 * @param string $output
 * @return string
 */
$output .= apply_filters( 'avf_dynamic_css_after_vars', '' );



//	variables are in scope of includes\admin\register-dynamic-styles.php
extract( $color_set );

if( $main_color !== null )
{
	//	extract color values or variables
	$data_main_color = is_array( $color_set_var ) ? $color_set_var['main_color'] : $color_set['main_color'];

	extract( $data_main_color );
}

extract( $styles );

unset( $background_image );



######################################################################
# CREATE THE CSS DYNAMIC CSS RULES
######################################################################
/*default*/

$output .= "

::selection{
	background-color: $primary;
	color: $bg;
}

";


/* not needed since we got no "boxed" option*/
$output .= "

html.html_boxed {
	background: $body_background;
}

";

//	responsive typography
if( ! empty( $typos ) )
{
	$output .= \enfold\styling\Responsive_Typo()->create_css_rules( $typos );
}

/*color sets*/
foreach( $color_set as $section_key => $colors ) // iterates over the color sets: usually $key is either: header_color, main_color, footer_color, socket_color
{
	$key = ".{$section_key}";

	// hack - loop through $colors if needed for more
	$bg_rgb = avia_backend_hex_to_rgb_array( $colors['bg'] );

	//	extract color values or variables
	$data_colors = is_array( $color_set_var ) ? $color_set_var[ $section_key ] : $colors;

	extract( $data_colors );


/*general styles*/
$output .= "

$key,
$key div,
$key header,
$key main,
$key aside,
$key footer,
$key article,
$key nav,
$key section,
$key span,
$key applet,
$key object,
$key iframe,
$key h1,
$key h2,
$key h3,
$key h4,
$key h5,
$key h6,
$key p,
$key blockquote,
$key pre,
$key a,
$key abbr,
$key acronym,
$key address,
$key big,
$key cite,
$key code,
$key del,
$key dfn,
$key em,
$key img,
$key ins,
$key kbd,
$key q,
$key s,
$key samp,
$key small,
$key strike,
$key strong,
$key sub,
$key sup,
$key tt,
$key var,
$key b,
$key u,
$key i,
$key center,
$key dl,
$key dt,
$key dd,
$key ol,
$key ul,
$key li,
$key fieldset,
$key form,
$key label,
$key legend,
$key table,
$key caption,
$key tbody,
$key tfoot,
$key thead,
$key tr,
$key th,
$key td,
$key article,
$key aside,
$key canvas,
$key details,
$key embed,
$key figure,
$key fieldset,
$key figcaption,
$key footer,
$key header,
$key hgroup,
$key menu,
$key nav,
$key output,
$key ruby,
$key section,
$key summary,
$key time,
$key mark,
$key audio,
$key video,
#top $key .pullquote_boxed,
.responsive #top $key .avia-testimonial,
.responsive #top.avia-blank #main $key.container_wrap:first-child,
#top $key.fullsize .template-blog .post_delimiter,
$key .related_posts.av-related-style-full a{
	border-color:$border;
}

$key .rounded-container,
#top $key .pagination a:hover,
$key .small-preview,
$key .fallback-post-type-icon{
	background:$meta;
	color:$bg;
}

$key .rounded-container .avia-svg-icon svg:first-child,
$key .small-preview .avia-svg-icon svg:first-child,
$key .fallback-post-type-icon.avia-svg-icon svg:first-child{
	fill: $bg;
	stroke: $bg;
}

$key .av-default-color,
#top $key .av-force-default-color,
$key .av-catalogue-item,
$key .wp-playlist-item .wp-playlist-caption,
$key .wp-playlist{
	color: $color;
}

$key,
$key .site-background,
$key .first-quote,
$key .related_image_wrap,
$key .gravatar img
$key .hr_content,
$key .news-thumb,
$key .post-format-icon,
$key .ajax_controlls a,
$key .tweet-text.avatar_no,
$key .toggler,
$key .toggler.activeTitle:hover,
$key #js_sort_items,
$key.inner-entry,
$key .grid-entry-title,
$key .related-format-icon,
.grid-entry $key .avia-arrow,
$key .avia-gallery-big,
$key .avia-gallery-big,
$key .avia-gallery img,
$key .grid-content,
$key .av-share-box ul,
#top $key .av-related-style-full .related-format-icon,
$key .related_posts.av-related-style-full a:hover,
$key.avia-fullwidth-portfolio .pagination .current,
$key.avia-fullwidth-portfolio .pagination a,
$key .av-hotspot-fallback-tooltip-inner,
$key .av-hotspot-fallback-tooltip-count{
	background-color:$bg;
	color: $color;
}

$key .ajax_controlls a.avia-svg-icon svg:first-child,
$key .avia-svg-icon svg:first-child,
$key .av-share-box ul li svg:first-child,
#top $key .avia-slider-testimonials.av-slideshow-ui .avia-slideshow-arrows a.avia-svg-icon svg:first-child{
	stroke: $color;
	fill: $color;
}

$key .avia-fold-unfold-section .av-fold-unfold-container::after{
	background: linear-gradient( to bottom, rgba({$bg_rgb[0]},{$bg_rgb[1]},{$bg_rgb[2]},0), rgba({$bg_rgb[0]},{$bg_rgb[1]},{$bg_rgb[2]},1) );
}

$key .avia-fold-unfold-section .av-fold-button-container:not(.avia-button),
$key.avia-fold-unfold-section .av-fold-button-container:not(.avia-button){
	color:$color;
}

$key .avia-fold-unfold-section .av-fold-button-container.fold-button{
	background:$bg;
	border-color:$border;
}

$key .avia-curtain-reveal-overlay{
	background: $bg;
}

$key .avia-icon-circles-icon{
	background:$bg;
	border-color:$border;
	color:$color;
}

$key .avia-icon-circles-icon.avia-svg-icon svg:first-child{
	fill: $color;
	stroke: $color;
}

$key .avia-icon-circles-icon.active{
	background:$secondary;
	border-color:$secondary;
	color:$bg;
}

$key .avia-icon-circles-icon.avia-svg-icon.active  svg:first-child{
	fill: $bg;
	stroke: $bg;
}

$key .avia-icon-circles-icon-text{
	color:$color;
	background:$bg;
}

$key .heading-color,
$key a.iconbox_icon:hover,
$key h1,
$key h2,
$key h3,
$key h4,
$key h5,
$key h6,
$key .sidebar .current_page_item>a,
$key .sidebar .current-menu-item>a,
$key .pagination .current,
$key .pagination a:hover,
$key strong.avia-testimonial-name,
$key .heading,
$key .toggle_content strong,
$key .toggle_content strong a,
$key .tab_content strong,
$key .tab_content strong a,
$key .asc_count,
$key .avia-testimonial-content strong,
#top $key .av-related-style-full .av-related-title,
$key .wp-playlist-item-meta.wp-playlist-item-title,
#top $key .av-no-image-slider h2 a,
$key .av-small-bar .avia-progress-bar .progressbar-title-wrap,
$key div .news-headline .news-title,
$key .av-default-style .av-countdown-cell-inner .av-countdown-time,
$key .av-default-style.av-countdown-timer.av-flip-numbers .card__top.card-time-color,
$key .av-default-style.av-countdown-timer.av-flip-numbers .card__bottom.card-time-color,
$key .av-default-style.av-countdown-timer.av-flip-numbers .card__back.card-time-color::before,
$key .av-default-style.av-countdown-timer.av-flip-clock .flip-clock__card .flip-clock-counter{
    color: $heading;
}

$key .heading .avia-svg-icon svg:first-child,
$key .av-special-heading .avia-svg-icon svg:first-child,
$key a.iconbox_icon.avia-svg-icon:hover svg:first-child,
$key .iconbox_icon.heading-color.avia-svg-icon svg:first-child{
	fill: $heading;
	stroke: $heading;
}

$key .av-countdown-timer.av-events-countdown a .av-countdown-time-label{
	color: $color;
}


$key .meta-color,
$key .sidebar,
$key .sidebar a,
$key .minor-meta,
$key .minor-meta a,
$key .text-sep,
$key blockquote,
$key .post_nav a,
$key .comment-text,
$key .side-container-inner,
$key .news-time,
$key .pagination a,
$key .pagination span,
$key .tweet-text.avatar_no .tweet-time,
#top $key .extra-mini-title,
$key .team-member-job-title,
$key .team-social a,
$key #js_sort_items a,
.grid-entry-excerpt,
$key .avia-testimonial-subtitle,
$key .commentmetadata a,
$key .social_bookmarks a,
$key .meta-heading > *,
$key .slide-meta,
$key .slide-meta a,
$key .taglist,
$key .taglist a,
$key .phone-info,
$key .phone-info a,
$key .av-sort-by-term a,
$key .av-magazine-time,
$key .av-magazine .av-magazine-entry-icon,
$key .av-catalogue-content,
$key .wp-playlist-item-length,
.html_modern-blog #top div $key .blog-categories a,
.html_modern-blog #top div $key .blog-categories a:hover{
	color: $meta;
}

$key .team-social a.avia-svg-icon svg:first-child,
$key .meta-heading .avia-svg-icon svg:first-child,
$key .social_bookmarks .avia-svg-icon a svg:first-child{
	stroke: $meta;
	fill: $meta;
}

$key .team-social a.avia-svg-icon:hover svg:first-child{
	stroke: $secondary;
	fill: $secondary;
}

$key .special-heading-inner-border{
	border-color: $color;
}
$key .meta-heading .special-heading-inner-border{
	border-color: $meta;
}

$key a,
$key .widget_first,
$key strong,
$key b,
$key b a,
$key strong a,
$key #js_sort_items a:hover,
$key #js_sort_items a.active_sort,
$key .av-sort-by-term a.active_sort,
$key .special_amp,
$key .taglist a.activeFilter,
$key #commentform .required,
#top $key .av-no-color.av-icon-style-border a.av-icon-char,
.html_elegant-blog #top $key .blog-categories a,
.html_elegant-blog #top $key .blog-categories a:hover{
	color: $primary;
}

$key a.avia-button .avia-svg-icon svg:first-child,
$key a.more-link .avia-svg-icon svg:first-child,
#top $key .av-no-color.av-icon-style-border a.av-icon-char svg:first-child{
	stroke: $primary;
	fill: $primary;
}

$key a:hover,
$key h1 a:hover,
$key h2 a:hover,
$key h3 a:hover,
$key h4 a:hover,
$key h5 a:hover,
$key h6 a:hover,
$key .template-search  a.news-content:hover,
$key .wp-playlist-item .wp-playlist-caption:hover{
	color: $secondary;
}

$key a.more-link:hover .avia-svg-icon svg:first-child{
	stroke: $secondary;
	fill: $secondary;
}


$key .primary-background,
$key .primary-background a,
div $key .button,
$key #submit,
$key input[type='submit'],
$key .small-preview:hover,
$key .avia-menu-fx,
$key .avia-menu-fx .avia-arrow,
$key.iconbox_top .iconbox_icon,
$key .iconbox_top a.iconbox_icon:hover,
$key .avia-data-table th.avia-highlight-col,
$key .avia-color-theme-color,
$key .avia-color-theme-color:hover,
$key .image-overlay .image-overlay-inside:before,
$key .comment-count,
$key .av_dropcap2,
.responsive #top $key .av-open-submenu.av-subnav-menu > li > a:hover,
#top $key .av-open-submenu.av-subnav-menu li > ul a:hover,
$key .av-colored-style .av-countdown-cell-inner,
$key .wc-block-components-button:not(.is-link) {
	background-color: $primary;
	color:$constant_font;
	border-color:$button_border;
}

$key #searchform .av_searchform_search.avia-svg-icon svg:first-child{
	fill: $constant_font;
	stroke: $constant_font;
}

$key .av_searchform_wrapper .av-search-icon:not(.av-input-field-icon){
	color: $constant_font;
}

$key .av_searchform_wrapper .av-search-icon.avia-svg-icon:not(.av-input-field-icon) svg:first-child{
	fill: $constant_font;
	stroke: $constant_font;
}

$key a.avia-button:hover .avia-svg-icon svg:first-child{
	fill: $secondary;
	stroke: $secondary;
}

$key.iconbox_top .iconbox_icon.avia-svg-icon svg:first-child,
$key .iconbox_top a.iconbox_icon.avia-svg-icon:hover svg:first-child{
	fill: $constant_font;
	stroke: $constant_font;
}

$key .av-colored-style.av-countdown-timer.av-flip-numbers .card__top,
$key .av-colored-style.av-countdown-timer.av-flip-numbers .card__bottom,
$key .av-colored-style.av-countdown-timer.av-flip-numbers .card__back::before,
$key .av-colored-style.av-countdown-timer.av-flip-numbers .card__back::after,
$key .av-colored-style.av-countdown-timer.av-flip-clock .flip-clock-counter{
	background-color: $primary;
	color: $constant_font;
}

#top #wrap_all $key .av-menu-button-colored > a .avia-menu-text{
	background-color: $primary;
	color:$constant_font;
	border-color:$primary;
}

#top #wrap_all $key .av-menu-button-colored > a .avia-menu-text:after{
	background-color:$button_border;
}

#top $key .mobile_menu_toggle{
	color: $primary;
	background:$bg;
}

#top $key .mobile_menu_toggle .avia-svg-icon svg:first-child{
	fill: $primary;
	stroke: $primary;
}

#top $key .av-menu-mobile-active .av-subnav-menu > li > a:before{
	color: $primary;
}

#top $key .av-open-submenu.av-subnav-menu > li > a:hover:before{
	color: $bg;
}

$key .button:hover,
$key .ajax_controlls a:hover,
$key #submit:hover,
$key .big_button:hover,
$key .contentSlideControlls a:hover,
$key #submit:hover ,
$key input[type='submit']:hover{
	background-color: $secondary;
	color: $bg;
	border-color: $button_border2;
}

$key #searchform .av_searchform_search.avia-svg-icon svg:first-child:hover{
	fill: $bg;
	stroke: $bg;
}

$key #searchform .av_searchform_search.avia-svg-icon:hover ~ #searchsubmit{
	background-color: $secondary;
	border-color: $button_border2;
}

$key .ajax_controlls a.avia-svg-icon:hover svg:first-child{
	fill: $bg;
	stroke: $bg;
}

$key .avia-toc-style-elegant a.avia-toc-level-0:last-child:after,
$key .avia-toc-style-elegant a:first-child:after,
$key .avia-toc-style-elegant a.avia-toc-level-0:after {
	background-color:$bg; border-color: $secondary;
}

$key .avia-toc-style-elegant a:first-child span:after,
$key .avia-toc-style-elegant a.avia-toc-level-0 span:after {
	background-color:$bg;
}

$key .avia-toc-style-elegant a:first-child:hover span:after,
$key .avia-toc-style-elegant a.avia-toc-level-0:hover span:after {
	border-color: $secondary
}

$key .avia-toc-style-elegant a:before{
	border-color: $border
}

$key .avia-toc-style-elegant a:first-child:after,
$key .avia-toc-style-elegant a.avia-toc-level-0:after {
	border-color: $secondary;
	background-color: $bg;
}

$key .avia-toc-style-elegant a:last-child:after{
	background-color:$border;
}

$key .timeline-bullet{
	background-color:$border;
	border-color: $bg;
}

$key table,
$key .widget_nav_menu ul:first-child>.current-menu-item,
$key .widget_nav_menu ul:first-child>.current_page_item,
$key .widget_nav_menu ul:first-child>.current-menu-ancestor,
$key .pagination .current,
$key .pagination a,
$key.iconbox_top .iconbox_content,
$key .av_promobox,
$key .toggle_content,
$key .toggler:hover,
#top $key .av-minimal-toggle .toggler,
$key .related_posts_default_image,
$key .search-result-counter,
$key .container_wrap_meta,
$key .avia-content-slider .slide-image,
$key .avia-slider-testimonials .avia-testimonial-content,
$key .avia-testimonial-arrow-wrap .avia-arrow,
$key .news-thumb,
$key .portfolio-preview-content,
$key .portfolio-preview-content .avia-arrow,
$key .av-magazine .av-magazine-entry-icon,
$key .related_posts.av-related-style-full a,
$key .aviaccordion-slide,
$key.avia-fullwidth-portfolio .pagination,
$key .isotope-item.special_av_fullwidth .av_table_col.portfolio-grid-image,
$key .av-catalogue-list li:hover,
$key .wp-playlist,
$key .avia-slideshow-fixed-height > li,
$key .avia-form-success,
$key .avia-form-error,
$key .av-boxed-grid-style .avia-testimonial{
	background: $bg2;
}

#top $key .post_timeline li:hover .timeline-bullet{
	background-color:$secondary;
}

$key blockquote,
$key .avia-bullet,
$key .av-no-color.av-icon-style-border a.av-icon-char{
	border-color:$primary;
}

.html_header_top $key .main_menu ul:first-child >li > ul,
.html_header_top #top $key .avia_mega_div > .sub-menu{
	border-top-color:$primary;
}

$key .breadcrumb,
$key .breadcrumb a,
#top $key.title_container .main-title,
#top $key.title_container .main-title a{
	color:$color;
}

$key .av-icon-display,
#top $key .av-related-style-full a:hover .related-format-icon,
$key .av-default-style .av-countdown-cell-inner,
$key .av-default-style.av-countdown-timer.av-flip-numbers .card__top,
$key .av-default-style.av-countdown-timer.av-flip-numbers .card__bottom,
$key .av-default-style.av-countdown-timer.av-flip-numbers .card__back::before,
$key .av-default-style.av-countdown-timer.av-flip-numbers .card__back::after,
$key .av-default-style.av-countdown-timer.av-flip-clock .flip-clock-counter{
	background-color:$bg2;
	color:$meta;
}

$key .av-icon-display.avia-svg-icon svg:first-child{
	fill: $meta;
	stroke: $meta;
}

$key .av-masonry-entry:hover .av-icon-display{
	background-color: $primary;
	color:$constant_font;
	border-color:$button_border;
}

$key .av-masonry-entry:hover .av-icon-display.avia-svg-icon svg:first-child{
	fill: $constant_font;
	stroke: $constant_font;
}

#top $key .av-masonry-entry.format-quote:hover .av-icon-display{
	color:$primary;
}

#top $key .av-masonry-entry.format-quote:hover .av-icon-display.avia-svg-icon svg:first-child{
	fill: $primary;
	stroke: $primary;
}

$key textarea::placeholder,
$key input::placeholder{
	color: $meta;
	opacity: 0.5;
}

";


// menu colors
$output .= "

$key .header_bg,
$key .main_menu ul ul,
$key .main_menu .menu ul li a,
$key .pointer_arrow_wrap .pointer_arrow,
$key .avia_mega_div,
$key .av-subnav-menu > li ul,
$key .av-subnav-menu a{
	background-color:$bg;
	color: $meta;
}

$key .main_menu .menu ul li a:hover,
$key .main_menu .menu ul li a:focus,
$key .av-subnav-menu ul a:hover,
$key .av-subnav-menu ul a:focus{
	background-color:$bg2;
}

$key .sub_menu > ul > li > a,
$key .sub_menu > div > ul > li > a,
$key .main_menu ul:first-child > li > a,
#top $key .main_menu .menu ul .current_page_item > a,
#top $key .main_menu .menu ul .current-menu-item > a,
#top $key .sub_menu li ul a{
	color:$meta;
}

$key .main_menu ul:first-child > li > a svg:first-child{
	stroke: $meta;
	fill: $meta;
}

$key .main_menu ul:first-child > li > a:hover svg:first-child,
$key .main_menu ul:first-child > li > a:focus svg:first-child{
	stroke: $color;
	fill: $color;
}

#top $key .main_menu .menu ul li > a:hover,
#top $key .main_menu .menu ul li > a:focus{
	color: $color;
}

$key .av-subnav-menu a:hover,
$key .av-subnav-menu a:focus,
$key .main_menu ul:first-child > li a:hover,
$key .main_menu ul:first-child > li a:focus,
$key .main_menu ul:first-child > li.current-menu-item > a,
$key .main_menu ul:first-child > li.current_page_item > a,
$key .main_menu ul:first-child > li.active-parent-item > a{
	color: $color;
}

#top $key .main_menu .menu .avia_mega_div ul .current-menu-item > a{
	color: $primary;
}

$key .sub_menu > ul > li > a:hover,
$key .sub_menu > ul > li > a:focus,
$key .sub_menu > div > ul > li > a:hover,
$key .sub_menu > div > ul > li > a:focus{
	color: $color;
}

#top $key .sub_menu ul li a:hover,
#top $key .sub_menu ul li a:focus,
$key .sub_menu ul:first-child > li.current-menu-item > a,
$key .sub_menu ul:first-child > li.current_page_item > a,
$key .sub_menu ul:first-child > li.active-parent-item > a{
	color:$color;
}

$key .sub_menu li ul a,
$key #payment,
$key .sub_menu ul li,
$key .sub_menu ul,
#top $key .sub_menu li li a:hover,
#top $key .sub_menu li li a:focus{
	background-color: $bg;
}

$key#header .avia_mega_div > .sub-menu.avia_mega_hr,
.html_bottom_nav_header.html_logo_center #top #menu-item-search>a{
	border-color: $border;
}

#top $key .widget_pages ul li a:focus,
#top $key .widget_nav_menu ul li a:focus{
color: $secondary;
}

@media only screen and (max-width: 767px) {

	#top #wrap_all .av_header_transparency{
		background-color:$bg;
		color: $color;
		border-color: $border;
	}

	#top #wrap_all .av_header_transparency .avia-svg-icon svg:first-child{
		stroke: $color;
		fill: $color;
	}

}

@media only screen and (max-width: 989px) {

	.html_mobile_menu_tablet #top #wrap_all .av_header_transparency{
		background-color:$bg;
		color: $color;
		border-color: $border;
	}

	.html_mobile_menu_tablet #top #wrap_all .av_header_transparency .avia-svg-icon svg:first-child{
		stroke: $color;
		fill: $color;
	}

}

";


	//apply background image if available
	if( isset( $background_image ) )
	{
		$output .= "

				$key .header_bg {
					background: $background_image;
				}

		";
	}



//tooltips +  ajax search
$output .= "

$key .avia-tt,
$key .avia-tt .avia-arrow,
$key .avia-tt .avia-arrow{
	background-color: $bg;
	color: $meta;
}

$key .av_ajax_search_image{
	background-color: $primary;
	color:$bg;
}

$key .av_ajax_search_image.avia-svg-icon svg:first-child{
	stroke: $bg;
	fill: $bg;
}

$key .ajax_search_excerpt{
	color: $meta;
}

$key .av_ajax_search_title{
	color: $heading;
}

$key .ajax_load{
	background-color:$primary;
}

$key .av_searchsubmit_wrapper{
	background-color:$primary;
}

";

//button
$output .= "

#top $key .avia-color-theme-color{
	color: $button_font;
	border-color: $button_border;
}

#top $key .avia-color-theme-color .avia-svg-icon svg:first-child{
	fill: $button_font;
	stroke: $button_font;
}

$key .avia-color-theme-color-subtle{
	background-color:$bg2;
	color: $color;
}

#top $key .avia-color-theme-color-subtle .avia-svg-icon svg:first-child{
	fill: $color;
	stroke: $color;
}

$key .avia-color-theme-color-subtle:hover{
	background-color:$bg;
	color: $heading;
}

#top $key .avia-color-theme-color-subtle:hover .avia-svg-icon svg:first-child{
	fill: $heading;
	stroke: $heading;
}

#top $key .avia-color-theme-color-highlight{
	color: $button_font;
	border-color: $secondary;
	background-color: $secondary;
}

#top $key .avia-color-theme-color-highlight .avia-svg-icon svg:first-child{
	fill: $button_font;
	stroke: $button_font;
}

#top $key .avia-font-color-theme-color,
#top $key .avia-font-color-theme-color-hover:hover{
	color: $button_font;
}

#top $key .avia-font-color-theme-color .avia-svg-icon svg:first-child,
#top $key .avia-font-color-theme-color-hover:hover .avia-svg-icon svg:first-child{
	fill: $button_font;
	stroke: $button_font;
}

$key .avia-font-color-theme-color-subtle{
	color: $color;
}

$key .avia-font-color-theme-color-subtle .avia-svg-icon svg:first-child{
	fill: $color;
	stroke: $color;
}

$key .avia-font-color-theme-color-subtle-hover:hover{
	color: $heading;
}

$key .avia-font-color-theme-color-subtle-hover:hover .avia-svg-icon svg:first-child{
	fill: $heading;
	stroke: $heading;
}

#top $key .avia-font-color-theme-color-highlight,
#top $key .avia-font-color-theme-color-highlight-hover:hover{
	color: $button_font;
}

#top $key .avia-font-color-theme-color-highlight .avia-svg-icon svg:first-child,
#top $key .avia-font-color-theme-color-highlight-hover:hover .avia-svg-icon svg:first-child{
	fill: $button_font;
	stroke: $button_font;
}

";

//icon list
$output .= "

$key .avia-icon-list .iconlist_icon{
	background-color:$iconlist;
}

$key .avia-icon-list .iconlist-timeline{
	border-color:$border;
}

$key .iconlist_content{
	color:$meta;
}

";

// timeline
$output .= "

$key .avia-timeline .milestone_icon{
	background-color:$timeline;
}

$key .avia-timeline .milestone_inner{
	background-color:$timeline;
}

$key .avia-timeline{
	border-color:$timeline;
}

$key .av-milestone-icon-wrap:after{
	border-color:$timeline;
}

$key .avia-timeline .av-milestone-date {
	color:$timeline_date;
}

$key .avia-timeline .av-milestone-date span{
	background-color:$timeline;
}

$key .avia-timeline-horizontal .av-milestone-content-wrap footer{
	background-color:$timeline;
}

$key .av-timeline-nav a{
	background-color:$timeline;
}

";


// form fields
$output .= "

#top $key .input-text,
#top $key input[type='text'],
#top $key input[type='input'],
#top $key input[type='password'],
#top $key input[type='email'],
#top $key input[type='number'],
#top $key input[type='url'],
#top $key input[type='tel'],
#top $key input[type='search'],
#top $key textarea,
#top $key select{
	border-color:$border;
	background-color: $bg2;
	color:$meta;
 	font-family: inherit;
}

#top $key .invers-color .input-text,
#top $key .invers-color input[type='text'],
#top $key .invers-color input[type='input'],
#top $key .invers-color input[type='password'],
#top $key .invers-color input[type='email'],
#top $key .invers-color input[type='number'],
#top $key .invers-color input[type='url'],
#top $key .invers-color input[type='tel'],
#top $key .invers-color input[type='search'],
#top $key .invers-color textarea,
#top $key .invers-color select{
	background-color: $bg;
}

$key .required{
	color:$primary;
}

";


// masonry
$output .= "

$key .av-masonry{
	background-color: $masonry;
 }

$key .av-masonry-pagination,
$key .av-masonry-pagination:hover,
$key .av-masonry-outerimage-container{
	background-color: $bg;
}


$key .container .av-inner-masonry-content,
#top $key .container .av-masonry-load-more,
#top $key .container .av-masonry-sort,
$key .container .av-masonry-entry .avia-arrow{
	background-color: $bg2;
}

";


// hr shortcode
$output .= "

$key .hr-short .hr-inner-style,
$key .hr-short .hr-inner{
	background-color: $bg;
}

";


//sidebar tab & Tabs shortcode
$output .= "

div $key .tabcontainer .active_tab_content,
div $key .tabcontainer .active_tab{
	background-color: $bg2;
	color:$color;
}

div $key .tabcontainer .active_tab .tab_icon.avia-svg-icon svg:first-child{
	fill: $color;
	stroke: $color;
}

.responsive.js_active #top $key .avia_combo_widget .top_tab .tab{
	border-top-color:$border;
}


$key .template-archives .tabcontainer a,
#top $key .tabcontainer .tab:hover,
#top $key .tabcontainer .tab.active_tab{
	color: $color;
}

#top $key .tabcontainer .tab:hover .tab_icon.avia-svg-icon svg:first-child,
#top $key .tabcontainer .tab.active_tab .tab_icon.avia-svg-icon svg:first-child{
	fill: $color;
	stroke: $color;
}

$key .template-archives .tabcontainer a:hover{
	color:$secondary;
}

$key .sidebar_tab_icon {
	background-color: $border;
}

#top $key .sidebar_active_tab .sidebar_tab_icon {
	background-color: $primary;
}

$key .sidebar_tab:hover .sidebar_tab_icon {
	background-color: $secondary;
}

$key .sidebar_tab, $key .tabcontainer .tab{
	color: $meta;
}

$key div .sidebar_active_tab ,
div $key .tabcontainer.noborder_tabs .active_tab_content,
div $key .tabcontainer.noborder_tabs .active_tab{
	color: $color;
	background-color: $bg;
}

#top $key .avia-smallarrow-slider .avia-slideshow-dots a{
	background-color: $bg2;
}

#top $key .avia-smallarrow-slider .avia-slideshow-dots a.active,
#top $key .avia-smallarrow-slider .avia-slideshow-dots a:hover{
	background-color: $meta;
}


@media only screen and (max-width: 767px) {
	.responsive #top $key .tabcontainer .active_tab{
		background-color: $secondary;
		color:$constant_font;  /*hard coded white to match the icons beside which are also white*/
	}
	.responsive #top $key .tabcontainer{
		border-color:$border;
	}
	.responsive #top $key .active_tab_content{
		background-color: $bg2;
	}
}

";


//pricing table
$output .= "

$key tr:nth-child(even),
$key .avia-data-table .avia-heading-row .avia-desc-col,
$key .avia-data-table .avia-highlight-col,
$key .pricing-table>li:nth-child(even),
body $key .pricing-table.avia-desc-col li,
#top $key .avia-data-table.avia_pricing_minimal th{
	background-color:$bg;
	color: $color;
}

$key table caption,
$key tr:nth-child(even),
$key .pricing-table>li:nth-child(even),
#top $key .avia-data-table.avia_pricing_minimal td{
	color: $meta;
}

$key tr:nth-child(odd),
$key .pricing-table>li:nth-child(odd),
$key .pricing-extra{
	background: $bg2;
}

$key .pricing-table li.avia-pricing-row,
$key .pricing-table li.avia-heading-row,
$key .pricing-table li.avia-pricing-row .pricing-extra{
	background-color: $primary;
	color:$constant_font;
	border-color:$stripe;
}

$key .pricing-table li.avia-heading-row,
$key .pricing-table li.avia-heading-row .pricing-extra{
	background-color: $stripe2;
	color:$constant_font;
	border-color:$stripe;
}

$key .pricing-table.avia-desc-col .avia-heading-row,
$key .pricing-table.avia-desc-col .avia-pricing-row{
	border-color:$border;
}

";


//media player + progress bar shortcode
$output .= "

$key .theme-color-bar .bar{
	background: $primary;
}

$key .mejs-controls .mejs-time-rail .mejs-time-current,
$key .mejs-controls .mejs-volume-button .mejs-volume-slider .mejs-volume-current,
$key .mejs-controls .mejs-horizontal-volume-slider .mejs-horizontal-volume-current,
$key .button.av-sending-button,
$key .av-striped-bar .theme-color-bar .bar{
	background: $primary;
}

body $key .mejs-controls .mejs-time-rail .mejs-time-float {
	background: $primary;
	color: #fff;
}

body $key .mejs-controls .mejs-time-rail .mejs-time-float-corner {
	border: solid 4px $primary;
	border-color: $primary transparent transparent transparent;
}

$key .progress{
	background-color:$bg2;
}

";


// ajax search shortcode

$output .= "
$key .av_searchform_element_results .av_ajax_search_entry,
$key .av_searchform_element_results .av_ajax_search_title,
$key.av_searchform_element_results .av_ajax_search_entry,
$key.av_searchform_element_results .av_ajax_search_title{
	color: $primary;
}

$key .av_searchform_element_results .ajax_search_excerpt,
$key.av_searchform_element_results .ajax_search_excerpt{
	color: $meta;
}

$key .av_searchform_element_results .av_ajax_search_image,
$key.av_searchform_element_results .av_ajax_search_image{
	color: $meta;
}
";

/*contact form send button*/
$output .= "

$key .button.av-sending-button{
	background: $secondary;
	background-image: linear-gradient(-45deg, $secondary 25%, $stripe2nd 25%, $stripe2nd 50%, $secondary 50%, $secondary 75%, $stripe2nd 75%, $stripe2nd);
	border-color: $secondary;
}

";



/*forum*/

$output .= "

$key span.bbp-admin-links a{
	color: $primary;
}

$key span.bbp-admin-links a:hover{
	color: $secondary;
}

#top $key .bbp-reply-content,
#top $key .bbp-topic-content,
#top $key .bbp-body .super-sticky .page-numbers,
#top $key .bbp-body .sticky .page-numbers,
#top $key .bbp-pagination-links a:hover,
#top $key .bbp-pagination-links span.current{
	background:$bg;
}

#top $key .bbp-topics .bbp-header,
#top $key .bbp-topics .bbp-header,
#top $key .bbp-forums .bbp-header,
#top $key .bbp-topics-front ul.super-sticky,
#top $key .bbp-topics ul.super-sticky,
#top $key .bbp-topics ul.sticky,
#top $key .bbp-forum-content ul.sticky,
#top $key .bbp-body .page-numbers{
	background-color:$bg2;
}

#top $key .bbp-meta,
#top $key .bbp-author-role,
#top $key .bbp-author-ip,
#top $key .bbp-pagination-count,
#top $key .bbp-topics .bbp-body .bbp-topic-title:before{
	color: $meta;
}

#top $key .bbp-admin-links{
	color:$border;
}

$key #bbpress-forums li.bbp-body ul.forum,
$key #bbpress-forums li.bbp-body ul.topic,
.avia_transform $key .bbp-replies .bbp-reply-author:before,
.avia_transform .forum-search $key .bbp-reply-author:before,
.avia_transform .forum-search $key .bbp-topic-author:before{
	background-color:$bg;
	border-color:$border;
}

#top $key .bbp-author-name{
	color:$heading;
}

$key .widget_display_stats dt,
$key .widget_display_stats dd{
	background-color:$bg2;
}

";


	//apply background image if available
	if( isset( $background_image ) )
	{
		$output .= "

			$key {
				background: $background_image;
			}

		";
	}

	//button and dropcap color white unless primary color is very very light
	if( avia_backend_calc_preceived_brightness( $colors['primary'], 220 ) )
	{
		$output .= "

			$key dropcap2,
			$key dropcap3,
			$key avia_button,
			$key avia_button:hover,
			$key .on-primary-color,
			$key .on-primary-color:hover{
				color: $constant_font;
			}

		";
	}


	//only for certain areas
	switch( $key )
	{
		case '.header_color':

			$output .= "

				#main, .avia-msie-8 .av_header_sticky_disabled#header{
					background-color:$bg;
				}

				.html_header_sidebar #header .av-main-nav > li > a .avia-menu-text{
					color:$heading;
				}

				.html_header_sidebar #header .av-main-nav > li > a .avia-menu-subtext{
					color:$meta;
				}

				.html_header_sidebar #header .av-main-nav > li:hover > a .avia-menu-text,
				.html_header_sidebar #header .av-main-nav > li.current-menu-ancestor > a .avia-menu-text,
				.html_header_sidebar #header .av-main-nav li.current-menu-item > a .avia-menu-text{
					color: $primary;
				}

				#top #wrap_all .av_seperator_big_border#header .av-menu-button-colored > a{
					background-color: $primary;
				}

				#top #wrap_all .av_seperator_big_border#header .av-menu-button-bordered > a{
					background-color: $bg2;
				}

				html.html_header_sidebar #wrap_all{
					background-color: $bg;
				}

				$key .av-hamburger-inner,
				$key .av-hamburger-inner::before,
				$key .av-hamburger-inner::after{
					background-color:$meta;
				}

				.html_av-overlay-side #top .av-burger-overlay-scroll{
					background:$bg
				}

				.html_av-overlay-side #top #wrap_all div .av-burger-overlay-scroll #av-burger-menu-ul a:hover{
					background-color:$bg2;
				}

				.html_av-overlay-side-classic #top #wrap_all .av-burger-overlay #av-burger-menu-ul li a{ border-color: $border; }

				.html_av-overlay-side #top #wrap_all .av-burger-overlay-scroll #av-burger-menu-ul a{color:$color}

				.html_av-overlay-side.av-burger-overlay-active #top #wrap_all #header .menu-item-search-dropdown a{
					color:$color
				}

				.html_av-overlay-side-classic #top .av-burger-overlay li li .avia-bullet,
				.html_av-overlay-side.av-burger-overlay-active #top .av-hamburger-inner,
				.html_av-overlay-side.av-burger-overlay-active #top .av-hamburger-inner::before,
				.html_av-overlay-side.av-burger-overlay-active #top .av-hamburger-inner::after{
					background-color:$color;
				}

				#header .header-reading-progress{
					background-color: $heading;
				}

			";

			if( ! empty( $avia_config['backend_colors']['burger_color'] ) )
			{
				$output .= "

					$key .av-hamburger-inner,
					$key .av-hamburger-inner::before,
					$key .av-hamburger-inner::after{
						background-color:" . $avia_config['backend_colors']['burger_color'] . ";
					}
					";

					$output .= " @media only screen and (max-width: 767px) {
						#top $key .av-hamburger-inner,
						#top $key .av-hamburger-inner::before,
						#top $key .av-hamburger-inner::after{
							background-color:" . $avia_config['backend_colors']['burger_color'] . ";
						}
					}

				";
			}


			if( ! empty( $avia_config['backend_colors']['menu_transparent'] ) )
			{
				$output .= "

					#top #wrap_all .av_header_transparency .main_menu ul:first-child > li > a,
					#top #wrap_all .av_header_transparency .sub_menu > ul > li > a,
					#top .av_header_transparency #header_main_alternate, .av_header_transparency #header_main .social_bookmarks li a{
						color:inherit;
						border-color: transparent;
						background: transparent;
					}

					#top #wrap_all {$key}.av_header_transparency,
					#top #wrap_all {$key}.av_header_transparency .phone-info.with_nav span,
					#top #header{$key}.av_header_transparency .av-main-nav > li > a .avia-menu-text,
					#top #header{$key}.av_header_transparency .av-main-nav > li > a .avia-menu-subtext{
						color: {$avia_config['backend_colors']['menu_transparent']};
					}

					#top #wrap_all {$key}.av_header_transparency .avia-svg-icon svg:first-child{
						stroke: {$avia_config['backend_colors']['menu_transparent']};
						fill: {$avia_config['backend_colors']['menu_transparent']};
					}

					#top {$key}.av_header_transparency .avia-menu-fx,
					.av_header_transparency div .av-hamburger-inner,
					.av_header_transparency div .av-hamburger-inner::before,
					.av_header_transparency div .av-hamburger-inner::after{
						background:{$avia_config['backend_colors']['menu_transparent']};
					}

				";

				if( ! empty( $avia_config['backend_colors']['menu_transparent_hover'] ) )
				{
					$output .= "

							#top #header{$key}.av_header_transparency .av-main-nav > li > a:hover,
							#top #header{$key}.av_header_transparency .av-main-nav > li > a:focus,
							#top #header{$key}.av_header_transparency .av-main-nav > li > a:hover .avia-menu-text,
							#top #header{$key}.av_header_transparency .av-main-nav > li > a:focus .avia-menu-text,
							#top #header{$key}.av_header_transparency .av-main-nav > li > a:hover .avia-menu-subtext,
							#top #header{$key}.av_header_transparency .av-main-nav > li > a:focus .avia-menu-subtext{
								color: {$avia_config['backend_colors']['menu_transparent_hover']};
								opacity: 1;
								transition: color 0.4s ease-in-out;
							}

							#top #header{$key}.av_header_transparency a.avia-svg-icon:hover svg:first-child,
							#top #header{$key}.av_header_transparency a.avia-svg-icon:focus svg:first-child{
								stroke: {$avia_config['backend_colors']['menu_transparent_hover']};
								fill: {$avia_config['backend_colors']['menu_transparent_hover']};
							}

							#top #header{$key}.av_header_transparency .av-main-nav > li > a:hover .av-hamburger-inner,
							#top #header{$key}.av_header_transparency .av-main-nav > li > a:focus .av-hamburger-inner,
							#top #header{$key}.av_header_transparency .av-main-nav > li > a:hover .av-hamburger-inner::before,
							#top #header{$key}.av_header_transparency .av-main-nav > li > a:focus .av-hamburger-inner::before,
							#top #header{$key}.av_header_transparency .av-main-nav > li > a:hover .av-hamburger-inner::after,
							#top #header{$key}.av_header_transparency .av-main-nav > li > a:focus .av-hamburger-inner::after{
								background: {$avia_config['backend_colors']['menu_transparent_hover']};
								opacity: 1;
							}
					";
				}

				$output .= "

					@media only screen and (max-width: 767px) {

						#top #wrap_all {$key}.av_header_transparency,
						#top #wrap_all {$key}.av_header_transparency .phone-info.with_nav span,
						#top #header{$key}.av_header_transparency .av-main-nav > li > a .avia-menu-text,
						#top #header{$key}.av_header_transparency .av-main-nav > li > a .avia-menu-subtex{
							color: $meta
						}

						#top #wrap_all {$key}.av_header_transparency .avia-svg-icon svg:first-child{
							stroke: $meta;
							fill: $meta;
						}

						$key div .av-hamburger-inner,
						$key div .av-hamburger-inner::before,
						$key div .av-hamburger-inner::after{
							background-color:$meta;
						}

						#top .av_header_with_border.av_header_transparency .avia-menu.av_menu_icon_beside{
							border-color:$border;
						}
					}
				";

				$output .= "

					@media only screen and (max-width: 989px) {

						.html_mobile_menu_tablet #top #wrap_all {$key}.av_header_transparency,
						.html_mobile_menu_tablet #top #wrap_all {$key}.av_header_transparency .phone-info.with_nav span,
						.html_mobile_menu_tablet #top #header{$key}.av_header_transparency .av-main-nav > li > a .avia-menu-text,
						.html_mobile_menu_tablet #top #header{$key}.av_header_transparency .av-main-nav > li > a .avia-menu-subtex{
							color: $meta
						}

						.html_mobile_menu_tablet #top #wrap_all {$key}.av_header_transparency .avia-svg-icon svg:first-child{
							stroke: $meta;
							fill: $meta;
						}

						.html_mobile_menu_tablet $key div .av-hamburger-inner,
						.html_mobile_menu_tablet $key div .av-hamburger-inner::before,
						.html_mobile_menu_tablet $key div .av-hamburger-inner::after{
							background-color:$meta;
						}

						.html_mobile_menu_tablet #top .av_header_with_border.av_header_transparency .avia-menu.av_menu_icon_beside{
							border-color:$border;
						}
					}
					";

			}

			if( ! empty( $avia_config['backend_colors']['burger_flyout_width'] ) )
			{
				$output .= "

					.html_av-overlay-side .av-burger-overlay-scroll{
						width: " . $avia_config['backend_colors']['burger_flyout_width'] . ";
						transform: translateX(" . $avia_config['backend_colors']['burger_flyout_width'] . ");
					}

				";
			}

			break;

		case '.main_color':

			$output .= "

				#main{
					border-color: $border;
				}

				#scroll-top-link:hover,
				#av-cookie-consent-badge:hover{
					background-color: $bg2;
					color: $primary;
					border:1px solid $border;
				}

				#scroll-top-link.avia-svg-icon:hover svg:first-child,
				#av-cookie-consent-badge.avia-svg-icon:hover svg:first-child{
					stroke: $primary;
					fill: $primary;
				}

				.html_stretched #wrap_all{
					background-color:$bg;
				}

			";

			/*contact form picker*/

			$output .= "

				#top .avia-datepicker-div .ui-datepicker-month,
				#top .avia-datepicker-div .ui-datepicker-year{
					color:$heading;
				}

				#top .avia-datepicker-div{
					background: $bg;
					border:1px solid $border;
				}

				#top .avia-datepicker-div a{
					color:$meta;
					background-color: $bg2;
				}

				#top .avia-datepicker-div a.ui-state-active,
				#top .avia-datepicker-div a.ui-state-highlight{
					color:$primary;
				}

				#top .avia-datepicker-div a.ui-state-hover{
					color:$bg2;
					background-color: $meta;
				}

				#top .avia-datepicker-div .ui-datepicker-buttonpane button{
					background-color: $primary;
					color: $constant_font;
					border-color: $primary;
				}

			";

			/*site loader*/
			$output .= "

				#top .av-siteloader{
					border-color: $border;
					border-left-color:$primary;
				}

				#top div.avia-popup .mfp-preloader {
					border-left-color:$primary;
				}

				.av-preloader-reactive #top .av-siteloader{
					border-color: $border;
				}

				#top .av-siteloader-wrap{
					background-color: $bg;
				}

				.av-preloader-reactive #top .av-siteloader:before{
					background-color: $border;
				}

			";

			/*tab section*/

			$output .= "

				.av-tab-section-tab-title-container{
					background-color: $bg2;
				}

				#top .av-section-tab-title{
					color: $meta;
				}

				#top .av-section-tab-title .av-tab-section-icon.avia-svg-icon svg:first-child{
					fill: $meta;
					stroke: $meta;
				}

				#top a.av-active-tab-title{
					color: $primary;
				}

				#top .av-tab-arrow-container span{
					background-color: $bg;
				}

			";

			break;

		case '.footer_color':

			$output .= "

				";
			break;

		case '.socket_color':

			$output .= "

				html,
				#scroll-top-link,
				#av-cookie-consent-badge{
					background-color: $bg;
				}

				#scroll-top-link,
				#av-cookie-consent-badge{
					color: $color;
					border:1px solid $border;
				}

				#scroll-top-link.avia-svg-icon svg:first-child,
				#av-cookie-consent-badge.avia-svg-icon svg:first-child{
					stroke: $color;
					fill: $color;
				}

			";
			break;
	}	//	end switch

	// unset all extracted vars with the help of variable vars :)
	foreach( $colors as $key => $val )
	{
		unset( $$key );
	}


}	//	****************  end foreach $color_set *************************


/**
 * filter to plug in, in case a plugin/extension/config file wants to make use of it
 *
 * @used_by		enfold\config-events-calendar\event-mod-css-dynamic.php			10
 * @used_by		enfold\config-woocommerce\woocommerce-mod-css-dynamic.php		10
 * @used_by		Avia_WC_Block_Editor											10
 * @since ???
 * @param string $output
 * @param array $color_set
 * @return string
 */
$output = apply_filters( 'avia_dynamic_css_output', $output, $color_set );



######################################################################
# DYNAMIC ICONFONT CHARACTERS
######################################################################

//forum topic icons
$output .= "

	.bbp-topics .bbp-body .bbp-topic-title:before{ " . av_icon_css_string( 'core__one_voice' ) . " }
	.bbp-topics .bbp-body .topic-voices-multi .bbp-topic-title:before { " . av_icon_css_string( 'core__multi_voice' ) . " }
	.bbp-topics .bbp-body .super-sticky .bbp-topic-title:before { " . av_icon_css_string( 'core__supersticky' ) . " }
	.bbp-topics .bbp-body .sticky .bbp-topic-title:before { " . av_icon_css_string( 'core__sticky' ) . " }
	.bbp-topics .bbp-body .status-closed .bbp-topic-title:before { " . av_icon_css_string( 'core__closed' ) . " }
	.bbp-topics .bbp-body .super-sticky.status-closed .bbp-topic-title:before{ " . av_icon_css_string( 'core__supersticky_closed' ) . " }
	.bbp-topics .bbp-body .sticky.status-closed .bbp-topic-title:before{ " . av_icon_css_string( 'core__sticky_closed' ) . " }

";

//layerslider nav icons
$output .= "

	#top .avia-layerslider .ls-nav-prev:before{  " . av_icon_css_string( 'core__prev_big' ) . " }
	#top .avia-layerslider .ls-nav-next:before{  " . av_icon_css_string( 'core__next_big' ) . " }

	#top .avia-layerslider .ls-nav-start:before,
	#top .avia_playpause_icon:before{ " . av_icon_css_string( 'core__play' ) . " }

	#top .avia-layerslider .ls-nav-stop:before,
	#top .avia_playpause_icon.av-pause:before{ " . av_icon_css_string( 'core__pause' ) . " }

";

//image hover overlay icons
$output .= "

	.image-overlay .image-overlay-inside:before{ " . av_icon_css_string( 'core__ov_image' ) . " }
	.image-overlay.overlay-type-extern .image-overlay-inside:before{ " . av_icon_css_string( 'core__ov_external' ) . " }
	.image-overlay.overlay-type-video .image-overlay-inside:before{ " . av_icon_css_string( 'core__ov_video' ) . " }

";

//lightbox next/prev icons
$output .= "
	div.avia-popup button.mfp-arrow:before		{ " . av_icon_css_string( 'core__next_big' ) . " }
	div.avia-popup button.mfp-arrow-left:before { " . av_icon_css_string( 'core__prev_big' ) . "}
";


######################################################################
# OUTPUT THE DYNAMIC CSS RULES
######################################################################

//todo: if the style are generated for the wordpress header call the generating script, otherwise create a simple css file and link to that file

$avia_config['style'] = array(

		array(
			'key'	=> 'direct_input',
			'value'	=> AviaSuperobject()->styleGenerator()->css_strip_whitespace( $output, true )
		),

		array(
			'key'	=> 'direct_input',
			'value'	=> '.html_header_transparency #top .avia-builder-el-0 .container, .html_header_transparency #top .avia-builder-el-0 .slideshow_caption{padding-top:' . avia_get_header_scroll_offset() . 'px;}'
		),

		//google webfonts
		array(
			'elements'			=> 'h1, h2, h3, h4, h5, h6, #top .title_container .main-title, tr.pricing-row td, #top .portfolio-title, .callout .content-area, .avia-big-box .avia-innerbox, .av-special-font, .av-current-sort-title, .html_elegant-blog #top .minor-meta, #av-burger-menu-ul li',
			'key'				=> 'google_webfont',
			'value'				=> avia_get_option( 'google_webfont' ),
			'font_source'		=> 'google_webfont',
			'add_font_class'	=> false
		),

		//google webfonts
		array(
			'elements'			=> 'body',
			'key'				=> 'google_webfont',
			'value'				=> avia_get_option( 'default_font' ),
			'font_source'		=> 'default_font',
			'add_font_class'	=> true
		),
);

$quick_css = avia_get_option( 'quick_css' );
if( ! empty( $quick_css ) )
{
	$avia_config['style'][] =
			array(
					'key'	=> 'direct_input',
					'value'	=> AviaSuperobject()->styleGenerator()->css_strip_whitespace( avia_get_option( 'quick_css' ), true )
				);
}


/**
 * @used_by		functions-enfold.php	avia_generate_grid_dimension()
 * @used_by		functions-enfold.php	avia_framed_layout()
 */
do_action( 'ava_generate_styles', $options, $color_set, $styles );

