<?php
if( ! defined( 'ABSPATH' ) ) {  exit;  }    // Exit if accessed directly


if( ! function_exists( 'portfolio_register' ) )
{
	function portfolio_register()
	{
		global $avia_config;

		$labels = array(
					'name'				=> _x( 'Portfolio Items', 'post type general name', 'avia_framework' ),
					'singular_name'		=> _x( 'Portfolio Entry', 'post type singular name', 'avia_framework' ),
					'all_items'			=> __( 'All Portfolio Items', 'avia_framework' ),
					'add_new'			=> _x( 'Add New', 'portfolio', 'avia_framework' ),
					'add_new_item'		=> __( 'Add New Portfolio Entry', 'avia_framework' ),
					'edit_item'			=> __( 'Edit Portfolio Entry', 'avia_framework' ),
					'new_item'			=> __( 'New Portfolio Entry', 'avia_framework' ),
					'view_item'			=> __( 'View Portfolio Entry', 'avia_framework' ),
					'search_items'		=> __( 'Search Portfolio Entries', 'avia_framework' ),
					'not_found'			=>  __( 'No Portfolio Entries found', 'avia_framework' ),
					'not_found_in_trash' => __( 'No Portfolio Entries found in Trash', 'avia_framework' ),
					'parent_item_colon'	=> ''
				);

		$permalinks = get_option( 'avia_permalink_settings' );
		if( ! is_array( $permalinks ) )
		{
			$permalinks = array();
		}

		$permalinks['portfolio_permalink_base'] = empty( $permalinks['portfolio_permalink_base'] ) ? __( 'portfolio-item', 'avia_framework' ) : $permalinks['portfolio_permalink_base'];
		$permalinks['portfolio_entries_taxonomy_base'] = empty( $permalinks['portfolio_entries_taxonomy_base'] ) ? __( 'portfolio_entries', 'avia_framework' ) : $permalinks['portfolio_entries_taxonomy_base'];

		$args = array(
					'labels'			=> $labels,
					'public'			=> true,
					'show_ui'			=> true,
					'capability_type'	=> 'post',
					'hierarchical'		=> false,
					'rewrite'			=> array(
												'slug'			=> _x( $permalinks['portfolio_permalink_base'], 'URL slug', 'avia_framework' ),
												'with_front'	=> true
												),
					'query_var'			=> true,
					'show_in_nav_menus'	=> true,
					'show_in_admin_bar' => true,
					'show_in_rest'		=> true,				//	set to false to disallow block editor
					'taxonomies'		=> array( 'post_tag' ),
					'supports'			=> array( 'title', 'thumbnail', 'excerpt', 'editor', 'comments', 'revisions', 'author', 'custom-fields' ),
					'menu_icon'			=> 'dashicons-images-alt2'
				);

		/**
		 * Custom value added by Enfold:
		 *
		 *  - supress "Edit Table" selectbox in WP write settings screen and ignore modify of CPT edit table columns
		 *
		 * @since 6.0
		 */
		$args['cpt_edit_table_cols'] = false;

		/**
		 * @since ????
		 * @param array $args
		 * @return array
		 */
		$args = apply_filters( 'avf_portfolio_cpt_args', $args );

		register_post_type( 'portfolio' , $args );

		$tax_args = array(
						'hierarchical'		=> true,
						'label'				=> __( 'Portfolio Categories', 'avia_framework' ),
						'singular_label'	=> __( 'Portfolio Category', 'avia_framework' ),
						'rewrite'			=> array(
													'slug'			=> _x( $permalinks['portfolio_entries_taxonomy_base'], 'URL slug', 'avia_framework' ),
													'with_front'	=> true
												),
						'query_var'			=> true,
						'show_in_rest'		=> true			//	set to false to disallow block editor
					);

		/**
		 * @since 5.6.3
		 * @param array $tax_args
		 * @return array
		 */
		$tax_args = apply_filters( 'avf_portfolio_cpt_tax_args', $tax_args);

		register_taxonomy( 'portfolio_entries', array( 'portfolio' ), $tax_args );



		/*
		 * Save args to global array for reuse
		 */
		$avia_config['custom_post']['portfolio']['args'] = $args;
		$avia_config['custom_taxonomy']['portfolio']['portfolio_entries']['args'] = $tax_args;

		//deactivate the avia_flush_rewrites() function - not required because we rely on the default wordpress permalink settings
		remove_action( 'wp_loaded', 'avia_flush_rewrites' );
	}

	add_action( 'init', 'portfolio_register' );
}


#portfolio_columns, register_post_type then append _columns
add_filter( 'manage_edit-portfolio_columns', 'portfolio_edit_columns' );
add_filter( 'manage_edit-post_columns', 'post_edit_columns' );
add_filter( 'manage_edit-page_columns', 'post_edit_columns' );
add_action( 'manage_posts_custom_column', 'portfolio_custom_columns' );
add_action( 'manage_pages_custom_column', 'portfolio_custom_columns' );


/**
 * Add a custom style for featured images in admin list table
 *
 * @since 4.2.1
 */
function avia_listtable_image_css()
{
    ?>
        <style>
            .widefat thead tr th#avia-image {
                width: 45px;
            }
        </style>
    <?php
}


if( ! function_exists( 'post_edit_columns' ) )
{
	/**
	 * Add custom columns to pages and posts table
	 *
	 * @since ???
	 * @param array $columns
	 * @return array
	 */
	function post_edit_columns( $columns )
	{
		$newcolumns = array(
					'cb'			=> "<input type=\"checkbox\" />",
					'avia-image'	=> __( 'Image', 'avia_framework' ),
				);

		$columns = array_merge( $newcolumns, $columns );

		add_action( 'admin_footer', 'avia_listtable_image_css' );

		return $columns;
	}
}


if( ! function_exists( 'portfolio_edit_columns' ) )
{
	/**
	 * Add custom columns to portfolio table
	 *
	 * @since ???
	 * @param array $columns
	 * @return array
	 */
	function portfolio_edit_columns( $columns )
	{
		$newcolumns = array(
						'cb'				=> '<input type="checkbox" />',
						'avia-image'		=> __( 'Image', 'avia_framework' ),
						'title'				=> __( 'Title', 'avia_framework' ),
						'portfolio_entries' => __( 'Categories', 'avia_framework' ),
						'tags'				=> __( 'Tags', 'avia_framework' )
					);

		$columns = array_merge( $newcolumns, $columns );

		add_action( 'admin_footer', 'avia_listtable_image_css' );

		return $columns;
	}
}


if( ! function_exists( 'portfolio_custom_columns' ) )
{
	/**
	 * Handles portfolio and post columns
	 * 
	 * @since ???
	 * @param string $column
	 */
	function portfolio_custom_columns( $column )
	{
		global $post;

		switch( $column )
		{
			case 'avia-image':
				if( has_post_thumbnail( $post->ID ) )
				{
					echo get_the_post_thumbnail( $post->ID, 'widget' );
				}
				break;
			case 'portfolio_entries':
				echo get_the_term_list( $post->ID, 'portfolio_entries', '', ', ','' );
				break;
		}
	}
}


if( ! function_exists( 'avia_permalink_settings_init' ) )
{
	/**
	 * avia portfolio permalink setting.
	 *
	 * @access public
	 * @return void
	 */
	function avia_permalink_settings_init()
	{
		global $avia_config;

		if( ! empty( $avia_config['custom_post'] ) )
		{
			foreach( $avia_config['custom_post'] as $cpt => $cpt_args )
			{
			    // Add a section to the permalinks page
			    add_settings_section( 'avia-permalink-' . $cpt, $cpt_args['args']['labels']['singular_name'] . ' ' . __( 'Settings', 'avia_framework' ), 'avia_permalink_settings', 'permalink' );
			}
		}
	}

	add_action( 'admin_init', 'avia_permalink_settings_init' );
}


if( ! function_exists( 'avia_permalink_settings' ) )
{
	/**
	 * Callback to add HTML to WP options page "Permalink"
	 *
	 * @param array $section
	 */
	function avia_permalink_settings( $section )
	{
		global $avia_config;

		if( ! empty( $avia_config['custom_post'] ) )
		{
			foreach( $avia_config['custom_post'] as $cpt => $cpt_args )
			{
				$id = str_replace( 'avia-permalink-', '', $section['id'] );
				if( $id != $cpt )
				{
					continue;
				}

				echo wpautop( __( 'These settings change the permalinks used for the', 'avia_framework' ) . ' ' . strtolower( $cpt_args['args']['labels']['name'] ) . __( '.', 'avia_framework' ) );

				$permalinks = get_option( 'avia_permalink_settings' );
				if( ! is_array( $permalinks ) )
				{
					$permalinks = array();
				}

				$permalinks[ $cpt . '_permalink_base' ] = empty($permalinks[ $cpt. '_permalink_base' ] ) ? $cpt_args['args']['rewrite']['slug'] : $permalinks[ $cpt . '_permalink_base' ];

				if( ! empty( $avia_config['custom_taxonomy'][ $cpt ] ) )
				{
					foreach( $avia_config['custom_taxonomy'][ $cpt ] as $tax => $tax_args )
					{
						$permalinks[$tax . '_taxonomy_base' ] = empty( $permalinks[ $tax . '_taxonomy_base' ] ) ? $tax_args['args']['rewrite']['slug'] : $permalinks[ $tax . '_taxonomy_base' ];
					}
				}

				?>

				<table class="form-table" role="presentation">
				    <tbody>
				    <tr>
				        <th scope="row"><?php echo $cpt_args['args']['labels']['name'] . ' ' . __( 'Base', 'avia_framework' ); ?></th>
				        <td>
				        	<?php $option_id = $cpt . '_permalink_base'; ?>
				            <input name="<?php echo $option_id; ?>" id="<?php echo $option_id; ?>" type="text" value="<?php echo esc_attr( $permalinks[ $option_id ] ); ?>" class="regular-text code"> <span class="description"><?php _e( 'Enter a custom base to use. A base must be set or WordPress will use default instead.', 'avia_framework' ); ?></span>
				        </td>
				    </tr>

			<?php
				if( ! empty( $avia_config['custom_taxonomy'][ $cpt ] ) )
				{
					foreach( $avia_config['custom_taxonomy'][ $cpt ] as $tax => $tax_args )
					{
				?>
					    <tr>
					        <th scope="row"><?php echo $tax_args['args']['label']  . ' ' . __( 'Base', 'avia_framework' ); ?></th>
					        <td>
					        	<?php $option_id = $tax . '_taxonomy_base'; ?>
					            <input name="<?php echo $option_id; ?>" id="<?php echo $option_id; ?>" type="text" value="<?php echo esc_attr( $permalinks[ $option_id ] ); ?>" class="regular-text code"> <span class="description"><?php _e( 'Enter a custom base to use. A base must be set or WordPress will use default instead.', 'avia_framework' ); ?></span>
					        </td>
					    </tr>
				<?php
					}

					echo '<input type="hidden" name="avia-options-permalink-nonce" value="' . wp_create_nonce ( 'avia-options-permalink-nonce' ) . '" />';
				}
				?>
				    </tbody>
				</table>

				<?php
			}
		}
	}
}


if( ! function_exists( 'avia_permalink_settings_save' ) )
{
	/**
	 * Save WP options page permalink settings
	 *
	 * @since ???
	 */
	function avia_permalink_settings_save()
	{
		if( defined( 'DOING_AJAX' ) && DOING_AJAX )
		{
			return;
		}

		//	security vulnerabilities check: avoid to change portfolio permalinks by non authenticated users
		if( ! current_user_can( 'manage_options' ) )
		{
			return;
		}

		global $avia_config, $pagenow;

		if( $pagenow != 'options-permalink.php' )
		{
			return;
		}

		$return = check_ajax_referer( 'avia-options-permalink-nonce', 'avia-options-permalink-nonce', false );

		if( false === $return )
		{
			return;
		}

		$permalinks = get_option( 'avia_permalink_settings' );

		if( ! is_array( $permalinks ) )
		{
			$permalinks = array();
		}

		if( ! empty( $avia_config['custom_post'] ) )
		{
			foreach( $avia_config['custom_post'] as $cpt => $cpt_args )
			{
				$option_id = $cpt . '_permalink_base';

			    // We need to save the options ourselves; settings api does not trigger save for the permalinks page
			    if( isset( $_POST[ $option_id ] ) )
			    {
		        	$permalinks[ $option_id ] = untrailingslashit( esc_html( $_POST[ $option_id ] ) );
			    }

				if( ! empty( $avia_config['custom_taxonomy'][ $cpt ] ) )
				{
				    foreach( $avia_config['custom_taxonomy'][ $cpt ] as $tax => $tax_args )
					{
						$option_id = $tax . '_taxonomy_base';

						if( isset( $_POST[ $option_id ] ) )
			    		{
							$permalinks[ $option_id ] = untrailingslashit( esc_html( $_POST[ $option_id ] ) );
						}
					}
				}
			}

			update_option( 'avia_permalink_settings', $permalinks );
		}
	}

	add_action( 'admin_init', 'avia_permalink_settings_save' );
}


if( ! function_exists( 'avia_portfolio_add_meta_field_names' ) )
{
	/**
	 * Add portfolio metakeys to be recognised in autosave and revisions
	 *
	 * @since 4.5.1
	 * @param array $meta_keys
	 * @param int $post_id
	 * @param string $context			'save' | 'restore'
	 */
	function avia_portfolio_add_meta_field_names( array $meta_keys, $post_id, $context )
	{
		$keys = array(
						'_preview_ids',
						'_preview_text',
						'_preview_display',
						'_preview_autorotation',
						'_preview_columns',
						'_portfolio_custom_link',
						'_portfolio_custom_link_url',
						'breadcrumb_parent'
				);

		/**
		 * Filter keys to be saved or restored
		 *
		 * @used_by				currently unused
		 * @since 4.5.1
		 */
		$keys = apply_filters( 'avf_portfolio_meta_field_names', $keys, $meta_keys, $post_id, $context );

		return array_merge( $meta_keys, $keys );
	}

	add_filter( 'avf_alb_meta_field_names', 'avia_portfolio_add_meta_field_names', 10, 3 );
}
