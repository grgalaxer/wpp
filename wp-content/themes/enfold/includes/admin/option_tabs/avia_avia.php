<?php
/**
 * Theme Options - Frontpage Settings Tab
 * ======================================
 *
 * @since 4.8.2
 */
if( ! defined( 'ABSPATH' ) ) {  exit;  }    // Exit if accessed directly

global $avia_config, $avia_pages, $avia_elements;



$avia_elements[] = array(
			'slug'		=> 'avia',
			'name'		=> __( 'Frontpage Settings', 'avia_framework' ),
			'desc'		=> __( 'Select which page to display on your Frontpage. If left blank the Blog will be displayed. In case you do not see a select box - you have to publish pages.', 'avia_framework' ),
			'id'		=> 'frontpage',
			'type'		=> 'select',
			'subtype'	=> 'page'
		);

$avia_elements[] = array(
			'slug'		=> 'avia',
			'name'		=> __( 'Where Do You Want To Display The Blog', 'avia_framework' ),
			'desc'		=> __( 'Select which page to display as your blog page. If left blank no blog will be displayed. In case you do not see a select box - you have to publish pages.', 'avia_framework' ),
			'id'		=> 'blogpage',
			'type'		=> 'select',
			'subtype'	=> 'page',
			'required'	=> array( 'frontpage', '{true}' )
		);

$avia_elements[] = array(
			'slug'		=> 'avia',
			'desc'		=> "<strong class='av-text-notice av-prev-el-notice'>" . __( 'Notice: Your blog is currently disabled. You can enable it', 'avia_framework' ) . ' <a target="_blank" href="' . admin_url( 'admin.php?page=avia#goto_performance' ) .'">' . __( 'here', 'avia_framework' ) . '</a></strong>',
			'id'		=> 'avia_avia_widgetdescription',
			'std'		=> '',
			'type'		=> 'heading',
			'required'	=> array( 'disable_blog', '{true}' ),
			'nodescription'	=> true
		);

$desc  = __( 'Upload a logo image, or enter the URL or ID of an image if its already uploaded. The themes default logo gets applied if the input field is left blank', 'avia_framework' ) . '<br/><br/>';
$desc .= __( 'Logo Dimension: 340px * 156px (if your logo is larger you might need to change the Header size in your', 'avia_framework' );
$desc .= ' <a href="#goto_header">' . __( 'Header Settings', 'avia_framework' ) . '</a>' . '<br /><br />';
$desc .= __( 'You can learn more about Responsive Images and SVG Files support in the ', 'avia_framework' ) . ' ';
$desc .= ' <a href="https://kriesi.at/documentation/enfold/logo/" title="Enfold Documentation Logo Article">' . __( 'Enfold documentation.', 'avia_framework' ) . '</a>';

$avia_elements[] = array(
			'slug'		=> 'avia',
			'name'		=> __( 'Logo', 'avia_framework' ),
			'desc'		=> $desc,
			'id'		=> 'logo',
			'type'		=> 'upload',
			'label'		=> __( 'Use Image as logo', 'avia_framework' )
		);


$avia_elements[] =	array(
			'slug'		=> 'avia',
			'name'		=> __( 'Transparency Options', 'avia_framework' ),
			'desc'		=> __( 'Contains options for transparency header.', 'avia_framework' ),
			'id'		=> 'avia_transparency_options',
			'type'		=> 'checkbox',
			'std'		=> false,
			'global'	=> true
		);

$avia_elements[] = array(
			'slug'			=> 'avia',
			'name'			=> __( 'Transparency Options', 'avia_framework' ),
			'id'			=> 'avia_transparency_options_start',
			'type'			=> 'visual_group_start',
			'required'		=> array( 'avia_transparency_options', 'avia_transparency_options' ),
			'nodescription' => true
		);


$avia_elements[] = array(
			'slug'			=> 'avia',
			'name'			=> __( 'What Is Header Transparency', 'avia_framework' ),
			'desc'			=> __( 'When creating/editing a page you can select to have the header be transparent and display the content (usually a fullwidth slideshow or a fullwidth image) beneath. In those cases you will usually need a different Logo and Main Menu color which can be set here.', 'avia_framework' ) . "<br/><a class='av-modal-image' href='" . get_template_directory_uri() . "/images/framework-helper/header_transparency.jpg'>" . __( '(Show example Screenshot)', 'avia_framework' ) . '</a>',
			'id'			=> 'transparency_description',
			'type'			=> 'heading',
			'std'			=> '',
			'nodescription'	=> true
		);

$desc  = __( 'Upload a logo image, or enter the URL or ID of an image if its already uploaded. (Leave empty to use the default logo)', 'avia_framework' );
$desc .= '<br /><br />';
$desc .= '<strong>' . __( 'Since 4.8.6.4 (in beta) - support for svg files', 'avia_framework' ) . '</strong><br />';
$desc .= __( 'Same rules as described at &quot;Theme Options -&gt; Logo&quot;. If you want to mix svg and other image formats for the logos you might need to add custom css.', 'avia_framework' );

$avia_elements[] =	array(
			'slug'	=> 'avia',
			'name' 	=> __( 'Transparency Logo', 'avia_framework' ),
			'desc' 	=> $desc,
			'id' 	=> 'header_replacement_logo',
			'type' 	=> 'upload',
			'label'	=> __( 'Use Image as logo', 'avia_framework' )
		);

$avia_elements[] =	array(
			'slug'		=> 'avia',
			'name'		=> __( 'Transparency Menu Color', 'avia_framework' ),
			'desc'		=> __( 'Menu color for transparent header (Leave empty to use the default color)', 'avia_framework' ),
			'id'		=> 'header_replacement_menu',
			'type'		=> 'colorpicker',
			'std'		=> '',
			'globalcss'	=> true
		);

$avia_elements[] =	array(
			'slug'		=> 'avia',
			'name'		=> __( 'Transparency Menu Color On Hover', 'avia_framework' ),
			'desc'		=> __( 'Menu color for transparent header on hover (Leave empty to use the default color)', 'avia_framework' ),
			'id'		=> 'header_replacement_menu_hover',
			'type'		=> 'colorpicker',
			'std'		=> '',
			'globalcss'	=> true
		);



$avia_elements[] = array(
			'slug'			=> 'avia',
			'id'			=> 'avia_transparency_options_close',
			'type'			=> 'visual_group_end',
			'nodescription' => true
		);




$desc  = __( 'Specify a favicon for your site.', 'avia_framework' ) . ' <br/>'. __( 'Accepted formats: .ico, .png, .gif', 'avia_framework' ) . ' <br/><br/>';
$desc .= __( 'What is a', 'avia_framework' ) . " <a target='_blank' href='http://en.wikipedia.org/wiki/Favicon' rel='noopener noreferrer'>" . __( 'favicon', 'avia_framework' ) . '?</a>';

$avia_elements[] = array(
			'slug'		=> 'avia',
			'name'		=> __( 'Favicon', 'avia_framework' ),
			'desc'		=> $desc,
			'id'		=> 'favicon',
			'type'		=> 'upload',
			'label'		=> __( 'Use Image as Favicon', 'avia_framework' )
		);


$avia_elements[] = array(
			'slug'          => 'avia',
			'type'          => 'visual_group_start',
			'id'            => 'avia_preload_start',
			'nodescription' => true
		);



$avia_elements[] = array(
			'slug'		=> 'avia',
			'name'		=> __( 'Page Preloading', 'avia_framework' ),
			'desc'		=> __( 'Show a preloader when opening a page on your site.', 'avia_framework' ),
			'id'		=> 'preloader',
			'type'		=> 'checkbox',
			'std'		=> false,
			'globalcss'	=> true
		);

$avia_elements[] = array(
			'slug'		=> 'avia',
			'name'		=> __( 'Page Transitions', 'avia_framework' ),
			'desc'		=> __( 'Smooth page transition when navigating from one page to the next. Please disable if this causes problems with plugins when navigating ajax or otherwise dynamical created content', 'avia_framework' ),
			'id'		=> 'preloader_transitions',
			'type'		=> 'checkbox',
			'std'		=> 'preloader_transitions',
			'required'	=> array( 'preloader', 'preloader' ),
			'globalcss'	=> true
		);

$avia_elements[] = array(
			'slug'		=> 'avia',
			'name'		=> __( 'Custom Logo for preloader', 'avia_framework' ),
			'desc'		=> __( 'Upload an optional logo image for your preloader page', 'avia_framework' ),
			'id'		=> 'preloader_logo',
			'type'		=> 'upload',
			'required'	=> array( 'preloader','preloader' ),
			'label'		=> __( 'Use Image as logo', 'avia_framework' )
		);


$avia_elements[] = array(
			'slug'          => 'avia',
			'type'          => 'visual_group_end',
			'id'            => 'avia_preload_end',
			'nodescription' => true
		);



$avia_elements[] = array(
			'slug'          => 'avia',
			'type'          => 'visual_group_start',
			'id'            => 'avia_lightbox_start',
			'nodescription' => true
		);

$avia_elements[] = array(
			'slug'		=> 'avia',
			'name'		=> __( 'Lightbox Modal Window', 'avia_framework' ),
			'desc'		=> __( 'Check to enable the default lightbox that opens once you click a link to an image. Uncheck only if you want to use your own modal window plugin', 'avia_framework' ),
			'id'		=> 'lightbox_active',
			'type'		=> 'checkbox',
			'std'		=> 'true',
			'globalcss'	=> true,
		);

$avia_elements[] = array(
			'slug'          => 'avia',
			'type'          => 'visual_group_end',
			'id'            => 'avia_lightbox_end',
			'nodescription' => true
		);


$desc1  = '<strong><br />';
$desc1 .=		__( 'Info: If a page is selected and this option is deactivated, the selected page will show a message in the page list, that this option is inactive, so that editors are reminded that it is a &quot;special&quot; page and making changes might break intended site layout when reactivated.', 'avia_framework' ) . ' ';
$desc1 .=		__( 'To disable this message in the page list please change your page selection to &quot;Select Page&quot; so no page is selected and then disable this option.', 'avia_framework' );
$desc1 .= '</strong>';

/**
 * Error 404 page section
 *
 * @author tinabillinger
 * @since 4.3
 */
$avia_elements[] = array(
			'slug'          => 'avia',
			'type'          => 'visual_group_start',
			'id'            => 'avia_404_start',
			'nodescription' => true
		);

$avia_elements[] = array(
			'slug'		=> 'avia',
			'name'		=> __( 'Custom Error 404 Page', 'avia_framework' ),
			'desc'		=> __( 'Select if you want to use any of your pages as your custom Error 404 Page. This page will be excluded from page lists and search results. You must deselect the page to make it accessible for public again.', 'avia_framework' ) . $desc1,
			'id'		=> 'error404_custom',
			'type'		=> 'select',
			'no_first'	=> true,
			'std'		=> '',
			'subtype'	=> array(
						__( 'No custom 404 page selected', 'avia_framework' )				=> '',
						__( 'Display selected page without redirect', 'avia_framework' )	=> 'error404_custom',
						__( 'Redirect to selected page', 'avia_framework' )					=> 'error404_redirect'
				)
		);

$avia_elements[] = array(
			'slug'		=> 'avia',
			'name'		=> __( 'Select Your Custom Error 404 Page', 'avia_framework' ),
			'desc'		=> __( 'If you are using a caching plugin, make sure to exclude this page from caching.', 'avia_framework' ),
			'id'		=> 'error404_page',
			'type'		=> 'select',
			'subtype'	=> 'page',
			'required'	=> array( 'error404_custom', '{contains_array}error404_custom;error404_redirect' )
		);

$avia_elements[] = array(
			'slug'          => 'avia',
			'type'          => 'visual_group_end',
			'id'            => 'avia_404_end',
			'nodescription' => true
		);


/**
 * Maintenance mode section
 *
 * @author tinabillinger
 * @since 4.3
 */

$avia_elements[] = array(
			'slug'          => 'avia',
			'type'          => 'visual_group_start',
			'id'            => 'avia_maintain_start',
			'nodescription' => true
		);

$avia_elements[] = array(
			'slug'		=> 'avia',
			'name'		=> __( 'Maintenance Mode', 'avia_framework' ),
			'desc'		=> __( 'Check to enable maintenance mode and show or redirect all page requests to a maintenance page of your choice. This page will not be accessable and will be excluded from page lists and search results. You must deselect the page to make it accessible for public again.', 'avia_framework' ) . $desc1,
			'id'		=> 'maintenance_mode',
			'type'		=> 'select',
			'no_first'	=> true,
			'std'		=> '',
			'subtype'	=> array(
						__( 'Disabled', 'avia_framework' )												=> '',
						__( 'Enabled - Use selected page content without redirect', 'avia_framework' )	=> 'maintenance_mode',
						__( 'Enabled - Redirect to selected page', 'avia_framework' )					=> 'maintenance_mode_redirect'
				)
		);

$avia_elements[] =	array(
			'slug'		=> 'avia',
			'name'		=> __( 'Select Your Maintenance Mode Page', 'avia_framework' ),
			'desc'		=> __( 'Please note that logged in Administrators, Editors and Authors will still be able to access the site', 'avia_framework' ),
			'id'		=> 'maintenance_page',
			'type'		=> 'select',
			'subtype'	=> 'page',
			'required'	=> array( 'maintenance_mode', '{contains_array}maintenance_mode;maintenance_mode_redirect' ),
		);

$avia_elements[] = array(
			'slug'          => 'avia',
			'type'          => 'visual_group_end',
			'id'            => 'avia_maintain_end',
			'nodescription' => true
		);


/**
 * Disable block editor section - since WP 5.0
 *
 * @author Guenter
 * @since 4.5.2
 */
global $wp_version;

if( version_compare( $wp_version, '5.0', '>=' ) )
{
	$avia_elements[] = array(
				'slug'			=> 'avia',
				'type'			=> 'visual_group_start',
				'id'			=> 'avia_block_editor_start',
				'nodescription'	=> true
			);

	$avia_elements[] = array(
				'slug'		=> 'avia',
				'name'		=> __( 'Select Your Editor', 'avia_framework' ),
				'desc'		=> __( 'Choose if you want to enable and use the built in classic editor - no plugin is needed.', 'avia_framework' ),
				'id'		=> 'enable_wp_classic_editor',
				'type'		=> 'select',
				'std'		=> '',
				'no_first'	=> true,
				'globalcss'	=> true,
				'subtype'	=> array(
								__( 'Use Block Editor', 'avia_framework' )		=> '',
								__( 'Use WP Classic Editor', 'avia_framework' )	=> 'enable_wp_classic_editor'
							)
			);

	/**
	 * As a first step we ignore the option by default - as a beta user may activate it
	 * @since 4.5.2
	 */
	if( current_theme_supports( 'avia_gutenberg_post_type_support' ) )
	{
		$avia_elements[] = array(
					'slug'				=> 'avia',
					'name'				=> __( 'Disable Block Editor For Selected Post Types:', 'avia_framework' ),
					'desc'				=> __( 'Only the classic WP editor will be used for the selected post types. The links to WP block editor (Gutenberg) will not be available. You can change this behaviour for certain pages/posts with Enfold&rsquo;s filter &quot;avf_use_block_editor_for_post&quot;', 'avia_framework' ),
					'id'				=> 'disable_block_editor_post_type',
					'type'				=> 'select',
					'subtype'			=> 'post_type',
					'features'			=> array( 'editor' ),
					'multiple'			=> true,
					'option_none_text'	=> __( 'Allow block editor for all possible post types (= default)...', 'avia_framework' ),
					'option_all_text'	=> __( 'Disable block editor for all listed post types...', 'avia_framework' ),
					'required'			=> array( 'enable_wp_classic_editor', 'enable_wp_classic_editor' ),
					'globalcss'			=> true,
				);
	}

	$avia_elements[] = array(
					'slug'			=> 'avia',
					'type'			=> 'visual_group_end',
					'id'			=> 'avia_block_editor_end',
					'nodescription'	=> true
				);
}
