<?php
/**
 * Displays the content on the plugin settings page
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'Rltdpstsplgn_Settings_Tabs' ) ) {
	/**
	 * Class Rltdpstsplgn_Settings_Tabs for display Settings tab
	 */
	class Rltdpstsplgn_Settings_Tabs extends Bws_Settings_Tabs {
		/**
		 * Post types
		 *
		 * @var array
		 */
		private $post_types;
		/**
		 * Positions for buttons
		 *
		 * @var array
		 */
		private $button_positions;
		/**
		 * Position for margin
		 *
		 * @var array
		 */
		private $margin_positions;
		/**
		 * Roles
		 *
		 * @var array
		 */
		private $editable_roles;
		/**
		 * Style for submit
		 *
		 * @var array
		 */
		private $submit_style;

		/**
		 * Constructor.
		 *
		 * @access public
		 *
		 * @see Bws_Settings_Tabs::__construct() for more information on default arguments.
		 *
		 * @param string $plugin_basename Plugin basename.
		 */
		public function __construct( $plugin_basename ) {
			global $rltdpstsplgn_options, $rltdpstsplgn_plugin_info, $wp_roles;

			$tabs = array(
				'related-posts'      => array( 'label' => __( 'Related Posts', 'relevant' ) ),
				'featured-posts'     => array( 'label' => __( 'Featured Posts', 'relevant' ) ),
				'latest-posts'       => array( 'label' => __( 'Latest Posts', 'relevant' ) ),
				'popular-posts'      => array( 'label' => __( 'Popular Posts', 'relevant' ) ),
				'recommend-button'   => array( 'label' => __( 'Recommend Settings', 'relevant' ) ),
				'recommend-settings' => array( 'label' => __( 'Recommend Appearance', 'relevant' ) ),
				'misc'               => array( 'label' => __( 'Misc', 'relevant' ) ),
				'custom_code'        => array( 'label' => __( 'Custom Code', 'relevant' ) ),
				'license'            => array( 'label' => __( 'License Key', 'relevantt' ) ),
			);

			parent::__construct(
				array(
					'plugin_basename'   => $plugin_basename,
					'plugins_info'      => $rltdpstsplgn_plugin_info,
					'prefix'            => 'rltdpstsplgn',
					'default_options'   => rltdpstsplgn_get_options_default(),
					'options'           => $rltdpstsplgn_options,
					'tabs'              => $tabs,
					'wp_slug'           => 'relevant',
					'doc_link'          => 'https://bestwebsoft.com/documentation/relevant/relevant-user-guide/',
					'link_key'           => '02752308c934f887c02d4ef857add5f2',
					'link_pn'            => '100',
				)
			);

			add_action( get_parent_class( $this ) . '_display_metabox', array( $this, 'display_metabox' ) );

			$this->post_types = array(
				'post' => __( 'Post' ),
				'page' => __( 'Page' ),
			);

			$this->button_positions = array(
				'top-left'         => __( 'Top Left', 'relevant' ),
				'top-right'        => __( 'Top Right', 'relevant' ),
				'bottom-left'      => __( 'Bottom Left', 'relevant' ),
				'bottom-right'     => __( 'Bottom Right', 'relevant' ),
				'top-bottom-left'  => __( 'Top & Bottom Left', 'relevant' ),
				'top-bottom-right' => __( 'Top & Bottom Right', 'relevant' ),
			);

			$this->margin_positions = array(
				'top'    => __( 'Top', 'relevant' ),
				'bottom' => __( 'Bottom', 'relevant' ),
				'left'   => __( 'Left', 'relevant' ),
				'right'  => __( 'Right', 'relevant' ),
			);

			$this->editable_roles = $wp_roles->roles;

			$this->submit_style = array(
				'version-1' => 'images/buttons/version-1.svg',
				'version-2' => 'images/buttons/version-2.svg',
				'version-3' => 'images/buttons/version-3.svg',
				'version-4' => 'images/buttons/version-4.svg',
			);
		}

		/**
		 * Save plugin options to the database
		 *
		 * @access public
		 * @return array The action results
		 */
		public function save_options() {
			global $wpdb;
			$message = '';
			$notice  = '';
			$error   = '';

			if ( ! isset( $_POST['rltdpstsplgn_options_nonce'] ) || ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['rltdpstsplgn_options_nonce'] ) ), 'rltdpstsplgn_options' ) ) {
				/* related-posts */
				$this->options['related_display']          = isset( $_POST['rltdpstsplgn_related_display'] ) ? array_map( 'sanitize_text_field', array_map( 'wp_unslash', $_POST['rltdpstsplgn_related_display'] ) ) : array();
				$this->options['related_title']            = isset( $_POST['rltdpstsplgn_related_title'] ) ? sanitize_text_field( wp_unslash( $_POST['rltdpstsplgn_related_title'] ) ) : '';
				$this->options['related_posts_count']      = isset( $_POST['rltdpstsplgn_related_posts_count'] ) && ! empty( $_POST['rltdpstsplgn_related_posts_count'] ) ? intval( $_POST['rltdpstsplgn_related_posts_count'] ) : 1;
				$this->options['related_criteria']         = isset( $_POST['rltdpstsplgn_related_criteria'] ) && in_array( $_POST['rltdpstsplgn_related_criteria'], array( 'category', 'tags', 'title', 'meta' ) ) ? sanitize_text_field( wp_unslash( $_POST['rltdpstsplgn_related_criteria'] ) ) : 'category';
				$this->options['related_no_posts_message'] = isset( $_POST['rltdpstsplgn_related_no_posts_message'] ) ? sanitize_text_field( wp_unslash( $_POST['rltdpstsplgn_related_no_posts_message'] ) ) : '';
				$this->options['related_show_thumbnail']   = isset( $_POST['rltdpstsplgn_related_show_thumbnail'] ) ? 1 : 0;
				$this->options['related_image_height']     = isset( $_POST['rltdpstsplgn_related_image_size_height'] ) ? intval( $_POST['rltdpstsplgn_related_image_size_height'] ) : 0;
				$this->options['related_image_width']      = isset( $_POST['rltdpstsplgn_related_image_size_width'] ) ? intval( $_POST['rltdpstsplgn_related_image_size_width'] ) : 0;
				$this->options['display_related_posts']    = isset( $_POST['rltdpstsplgn_display_related_posts'] ) && in_array( $_POST['rltdpstsplgn_display_related_posts'], array( 'All', '3 days ago', '5 days ago', '7 days ago', '1 month ago', '3 month ago', '6 month ago' ) ) ? sanitize_text_field( wp_unslash( $_POST['rltdpstsplgn_display_related_posts'] ) ) : 'All';
				$this->options['related_use_category']     = isset( $_POST['rltdpstsplgn_related_use_category'] ) ? 1 : 0;
				$this->options['related_posts_slider']     = isset( $_POST['rltdpstsplgn_related_posts_slider'] ) ? 1 : 0;

				$delete               = array();
				$related_add_for_page = array();
				if ( ! empty( $_POST['rltdpstsplgn_related_add_for_page'] ) && in_array( 'category', $_POST['rltdpstsplgn_related_add_for_page'] ) ) {
					$related_add_for_page[] = 'category';
				} elseif ( in_array( 'category', $this->options['related_add_for_page'] ) ) {
					$delete[] = 'category';
				}
				if ( ! empty( $_POST['rltdpstsplgn_related_add_for_page'] ) && in_array( 'tags', $_POST['rltdpstsplgn_related_add_for_page'] ) ) {
					$related_add_for_page[] = 'tags';
				} elseif ( in_array( 'tags', $this->options['related_add_for_page'] ) ) {
					$delete[] = 'post_tag';
				}
				if ( ! empty( $_POST['rltdpstsplgn_related_add_for_page'] ) && in_array( 'meta', $_POST['rltdpstsplgn_related_add_for_page'] ) ) {
					$related_add_for_page[] = 'meta';
				}
				if ( ! empty( $_POST['rltdpstsplgn_related_add_for_page'] ) && in_array( 'title', $_POST['rltdpstsplgn_related_add_for_page'] ) ) {
					$related_add_for_page[] = 'title';
				}
				$this->options['related_add_for_page'] = $related_add_for_page;

				if ( ! empty( $delete ) ) {
					$taxonomies = implode( ',', $delete );

					$taxonomies_placeholders = implode( ', ', array_fill( 0, count( (array) $taxonomies ), '%s' ) );

					$relationships = $wpdb->get_results(
						$wpdb->prepare(
							'SELECT r.object_id FROM ' . $wpdb->terms . ' AS t
							INNER JOIN ' . $wpdb->term_taxonomy . ' AS tt ON t.term_id = tt.term_id
							INNER JOIN ' . $wpdb->term_relationships . ' AS r ON r.term_taxonomy_id = tt.term_taxonomy_id
							INNER JOIN ' . $wpdb->posts . ' AS p ON p.ID = r.object_id
							WHERE p.post_type = "page" AND tt.taxonomy IN ( ' . $taxonomies_placeholders . ' )
							GROUP BY t.term_id',
							$taxonomies
						),
						ARRAY_A
					);
					foreach ( $relationships as $key => $value ) {
						wp_delete_object_term_relationships( intval( $value['object_id'] ), $delete );
					}
				}

				$this->options['related_excerpt_length'] = isset( $_POST['rltdpstsplgn_related_excerpt_length'] ) ? intval( $_POST['rltdpstsplgn_related_excerpt_length'] ) : '';
				$this->options['related_excerpt_more']   = isset( $_POST['rltdpstsplgn_related_excerpt_more'] ) ? sanitize_text_field( wp_unslash( $_POST['rltdpstsplgn_related_excerpt_more'] ) ) : '';

				if ( empty( $this->options['related_excerpt_more'] ) ) {
					$this->options['related_excerpt_more'] = '...';
				}

				if ( isset( $_POST['rltdpstsplgn_related_no_preview_img'] ) && ! empty( $_POST['rltdpstsplgn_related_no_preview_img'] ) && rltdpstsplgn_is_200( $_POST['rltdpstsplgn_related_no_preview_img'] ) && getimagesize( $_POST['rltdpstsplgn_related_no_preview_img'] ) ) {
					$this->options['related_no_preview_img'] = $_POST['rltdpstsplgn_related_no_preview_img'];
				} else {
					$this->options['related_no_preview_img'] = $this->default_options['related_no_preview_img'];
				}

				$related_show_options = array( 'comments', 'date', 'author', 'reading_time', 'thumbnail', 'excerpt' );
				foreach ( $related_show_options as $item ) {
					$this->options[ 'related_show_' . $item ] = isset( $_POST[ 'rltdpstsplgn_related_show_' . $item ] ) ? 1 : 0;
				}

				/* featured-posts */
				$this->options['featured_display']     = isset( $_POST['rltdpstsplgn_featured_display'] ) ? array_map( 'sanitize_text_field', array_map( 'wp_unslash', $_POST['rltdpstsplgn_featured_display'] ) ) : array();
				$this->options['featured_title']         = isset( $_POST['rltdpstsplgn_featured_title'] ) ? sanitize_text_field( wp_unslash( $_POST['rltdpstsplgn_featured_title'] ) ) : array();
				$this->options['featured_posts_count'] = isset( $_POST['rltdpstsplgn_featured_posts_count'] ) && ! empty( $_POST['rltdpstsplgn_featured_posts_count'] ) ? intval( $_POST['rltdpstsplgn_featured_posts_count'] ) : 1;
				$this->options['display_featured_posts'] = isset( $_POST['rltdpstsplgn_display_featured_posts'] ) && in_array( sanitize_text_field( wp_unslash( $_POST['rltdpstsplgn_display_featured_posts'] ) ), array( 'All', '3 days ago', '5 days ago', '7 days ago', '1 month ago', '3 month ago', '6 month ago' ) ) ? sanitize_text_field( wp_unslash( $_POST['rltdpstsplgn_display_featured_posts'] ) ) : 'All';

				/*Block Width*/
				if ( isset( $_POST['rltdpstsplgn_featured_block_width'] ) && 0 < $_POST['rltdpstsplgn_featured_block_width'] ) {
					$this->options['featured_block_width_remark'] = isset( $_POST['rltdpstsplgn_featured_block_unit'] ) && in_array( sanitize_text_field( wp_unslash( $_POST['rltdpstsplgn_featured_block_unit'] ) ), array( 'px', '%' ) ) ? sanitize_text_field( wp_unslash( $_POST['rltdpstsplgn_featured_block_unit'] ) ) : '%';
					$this->options['featured_block_width'] = ( '%' == $this->options['featured_block_width_remark'] && 100 < $_POST['rltdpstsplgn_featured_block_width'] ) ? '100' : absint( $_POST['rltdpstsplgn_featured_block_width'] );
				} else {
					$error .= __( "Invalid value for 'Block Width'.", 'relevant' ) . '<br />';
				}

				/*Content Block Width*/
				if ( isset( $_POST['rltdpstsplgn_featured_text_block_width'] ) && 0 < $_POST['rltdpstsplgn_featured_text_block_width'] ) {
					$this->options['featured_text_block_width_remark'] = isset( $_POST['rltdpstsplgn_featured_text_block_unit'] ) && in_array( sanitize_text_field( wp_unslash( $_POST['rltdpstsplgn_featured_text_block_unit'] ) ), array( 'px', '%' ) ) ? sanitize_text_field( wp_unslash( $_POST['rltdpstsplgn_featured_text_block_unit'] ) ) : '%';
					$this->options['featured_text_block_width'] = ( '%' == $this->options['featured_text_block_width_remark'] && 100 < $_POST['rltdpstsplgn_featured_text_block_width'] ) ? '100' : absint( $_POST['rltdpstsplgn_featured_text_block_width'] );
				} else {
					$error .= __( "Invalid value for 'Content Block Width'.", 'relevant' ) . '<br />';
				}

				$this->options['featured_theme_style']            = isset( $_POST['rltdpstsplgn_featured_theme_style'] ) ? 1 : 0;
				$this->options['featured_background_color_block'] = isset( $_POST['rltdpstsplgn_featured_background_color_block'] ) ? sanitize_text_field( wp_unslash( $_POST['rltdpstsplgn_featured_background_color_block'] ) ) : '';
				$this->options['featured_background_color_text']  = isset( $_POST['rltdpstsplgn_featured_background_color_text'] ) ? sanitize_text_field( wp_unslash( $_POST['rltdpstsplgn_featured_background_color_text'] ) ) : '';
				$this->options['featured_color_text']             = isset( $_POST['rltdpstsplgn_featured_color_text'] ) ? sanitize_text_field( wp_unslash( $_POST['rltdpstsplgn_featured_color_text'] ) ) : '';
				$this->options['featured_color_header']           = isset( $_POST['rltdpstsplgn_featured_color_header'] ) ? sanitize_text_field( wp_unslash( $_POST['rltdpstsplgn_featured_color_header'] ) ) : '';
				$this->options['featured_color_link']             = isset( $_POST['rltdpstsplgn_featured_color_link'] ) ? sanitize_text_field( wp_unslash( $_POST['rltdpstsplgn_featured_color_link'] ) ) : '';
				$this->options['featured_image_height']           = isset( $_POST['rltdpstsplgn_featured_image_size_height'] ) ? intval( $_POST['rltdpstsplgn_featured_image_size_height'] ) : '';
				$this->options['featured_image_width']            = isset( $_POST['rltdpstsplgn_featured_image_size_width'] ) ? intval( $_POST['rltdpstsplgn_featured_image_size_width'] ) : '';

				$this->options['featured_use_category']           = isset( $_POST['rltdpstsplgn_featured_use_category'] ) ? 1 : 0;
				$this->options['featured_excerpt_length']         = isset( $_POST['rltdpstsplgn_featured_excerpt_length'] ) ? intval( $_POST['rltdpstsplgn_featured_excerpt_length'] ) : 20;
				$this->options['featured_excerpt_more']           = isset( $_POST['rltdpstsplgn_featured_excerpt_more'] ) ? sanitize_text_field( wp_unslash( $_POST['rltdpstsplgn_featured_excerpt_more'] ) ) : '';

				if ( empty( $this->options['featured_excerpt_more'] ) ) {
					$this->options['featured_excerpt_more'] = '...';
				}

				if ( ! empty( $_POST['rltdpstsplgn_featured_no_preview_img'] ) && rltdpstsplgn_is_200( $_POST['rltdpstsplgn_featured_no_preview_img'] ) && getimagesize( $_POST['rltdpstsplgn_featured_no_preview_img'] ) ) {
					$this->options['featured_no_preview_img'] = $_POST['rltdpstsplgn_featured_no_preview_img'];
				} else {
					$this->options['featured_no_preview_img'] = $this->default_options['featured_no_preview_img'];
				}

				$featured_show_options = array( 'comments', 'date', 'author', 'reading_time', 'thumbnail', 'excerpt' );
				foreach ( $featured_show_options as $item ) {
					$this->options[ 'featured_show_' . $item ] = isset( $_POST[ 'rltdpstsplgn_featured_show_' . $item ] ) ? 1 : 0;
				}

				/* Latest posts options */
				$this->options['latest_display']          = isset( $_POST['rltdpstsplgn_latest_display'] ) ? array_map( 'sanitize_text_field', array_map( 'wp_unslash', $_POST['rltdpstsplgn_latest_display'] ) ) : array();
				$this->options['latest_title']            = isset( $_POST['rltdpstsplgn_latest_title'] ) ? sanitize_text_field( wp_unslash( $_POST['rltdpstsplgn_latest_title'] ) ) : '';
				$this->options['latest_posts_count']      = isset( $_POST['rltdpstsplgn_latest_posts_count'] ) && ! empty( $_POST['rltdpstsplgn_latest_posts_count'] ) ? intval( $_POST['rltdpstsplgn_latest_posts_count'] ) : 1;
				$this->options['latest_excerpt_length']   = isset( $_POST['rltdpstsplgn_latest_excerpt_length'] ) ? intval( $_POST['rltdpstsplgn_latest_excerpt_length'] ) : '';
				$this->options['latest_excerpt_more']     = isset( $_POST['rltdpstsplgn_latest_excerpt_more'] ) ? sanitize_text_field( wp_unslash( $_POST['rltdpstsplgn_latest_excerpt_more'] ) ) : '';
				$this->options['latest_image_height']     = isset( $_POST['rltdpstsplgn_latest_image_size_height'] ) ? intval( $_POST['rltdpstsplgn_latest_image_size_height'] ) : '';
				$this->options['latest_image_width']      = isset( $_POST['rltdpstsplgn_latest_image_size_width'] ) ? intval( $_POST['rltdpstsplgn_latest_image_size_width'] ) : '';
				$this->options['latest_use_category']     = isset( $_POST['rltdpstsplgn_latest_use_category'] ) ? 1 : 0;
				if ( empty( $this->options['latest_excerpt_more'] ) ) {
					$this->options['latest_excerpt_more'] = '...';
				}

				$latest_show_options = array( 'comments', 'date', 'author', 'reading_time', 'thumbnail', 'excerpt' );
				foreach ( $latest_show_options as $item ) {
					$this->options[ 'latest_show_' . $item ] = isset( $_POST[ 'rltdpstsplgn_latest_show_' . $item ] ) ? 1 : 0;
				}

				if ( ! empty( $_POST['rltdpstsplgn_latest_no_preview_img'] ) && rltdpstsplgn_is_200( $_POST['rltdpstsplgn_latest_no_preview_img'] ) && getimagesize( $_POST['rltdpstsplgn_latest_no_preview_img'] ) ) {
					$this->options['latest_no_preview_img'] = $_POST['rltdpstsplgn_latest_no_preview_img'];
				} else {
					$this->options['latest_no_preview_img'] = $this->default_options['latest_no_preview_img'];
				}

				/* Popular posts options */
				$this->options['popular_display']         = isset( $_POST['rltdpstsplgn_popular_display'] ) ? array_map( 'sanitize_text_field', array_map( 'wp_unslash', $_POST['rltdpstsplgn_popular_display'] ) ) : array();
				$this->options['popular_title']           = isset( $_POST['rltdpstsplgn_popular_title'] ) ? sanitize_text_field( wp_unslash( $_POST['rltdpstsplgn_popular_title'] ) ) : '';
				$this->options['popular_posts_count']     = isset( $_POST['rltdpstsplgn_popular_posts_count'] ) && ! empty( $_POST['rltdpstsplgn_popular_posts_count'] ) ? absint( $_POST['rltdpstsplgn_popular_posts_count'] ) : 1;
				$this->options['popular_min_posts_count'] = isset( $_POST['rltdpstsplgn_popular_min_posts_count'] ) ? absint( $_POST['rltdpstsplgn_popular_min_posts_count'] ) : '';
				$this->options['popular_excerpt_length']  = isset( $_POST['rltdpstsplgn_popular_excerpt_length'] ) ? absint( $_POST['rltdpstsplgn_popular_excerpt_length'] ) : '';
				$this->options['popular_excerpt_more']    = isset( $_POST['rltdpstsplgn_popular_excerpt_more'] ) ? sanitize_text_field( wp_unslash( $_POST['rltdpstsplgn_popular_excerpt_more'] ) ) : '';
				$this->options['popular_image_height']    = isset( $_POST['rltdpstsplgn_popular_image_size_height'] ) ? intval( $_POST['rltdpstsplgn_popular_image_size_height'] ) : '';
				$this->options['popular_image_width']     = isset( $_POST['rltdpstsplgn_popular_image_size_width'] ) ? intval( $_POST['rltdpstsplgn_popular_image_size_width'] ) : '';
				$this->options['display_popular_posts']   = isset( $_POST['rltdpstsplgn_display_popular_posts'] ) && in_array( $_POST['rltdpstsplgn_display_popular_posts'], array( 'All', '3 days ago', '5 days ago', '7 days ago', '1 month ago', '3 month ago', '6 month ago' ) ) ? sanitize_text_field( wp_unslash( $_POST['rltdpstsplgn_display_popular_posts'] ) ) : 'All';

				if ( empty( $this->options['popular_excerpt_more'] ) ) {
					$this->options['popular_excerpt_more'] = '...';
				}

				$this->options['popular_use_category'] = isset( $_POST['rltdpstsplgn_popular_use_category'] ) ? 1 : 0;
				$this->options['popular_order_by']     = isset( $_POST['rltdpstsplgn_popular_order_by'] ) ? sanitize_text_field( wp_unslash( $_POST['rltdpstsplgn_popular_order_by'] ) ) : '';

				$show_options = array( 'views', 'excerpt', 'date', 'author', 'thumbnail', 'comments', 'reading_time' );
				foreach ( $show_options as $item ) {
					$this->options[ 'popular_show_' . $item ] = isset( $_POST[ 'rltdpstsplgn_popular_show_' . $item ] ) ? 1 : 0;
				}

				if ( ! empty( $_POST['rltdpstsplgn_popular_no_preview_img'] ) && rltdpstsplgn_is_200( $_POST['rltdpstsplgn_popular_no_preview_img'] ) && getimagesize( $_POST['rltdpstsplgn_popular_no_preview_img'] ) ) {
					$this->options['popular_no_preview_img'] = $_POST['rltdpstsplgn_popular_no_preview_img'];
				} else {
					$this->options['popular_no_preview_img'] = $this->default_options['popular_no_preview_img'];
				};

				if ( empty( $error ) ) {
					/* Update options in the database */
					update_option( 'rltdpstsplgn_options', $this->options );
					$message = __( 'Settings saved.', 'relevant' );
				}
			}
			return compact( 'message', 'notice', 'error' );
		}

		/**
		 * Display Related Posts Tab
		 */
		public function tab_related_posts() { ?>
			<h3 class="bws_tab_label"><?php esc_html_e( 'Related Posts Settings', 'relevant' ); ?></h3>
			<?php $this->help_phrase(); ?>
			<hr>
			<table class="form-table">
				<tr>
					<th><?php esc_html_e( 'Title', 'relevant' ); ?></th>
					<td>
						<input type="text" name="rltdpstsplgn_related_title" maxlength="250" value="<?php echo esc_html( $this->options['related_title'] ); ?>" />
					</td>
				</tr>
				<tr>
					<th><?php esc_html_e( 'Number of Posts', 'relevant' ); ?></th>
					<td>
						<input type="number" name="rltdpstsplgn_related_posts_count" min="1" max="10000" step="1" value="<?php echo esc_attr( $this->options['related_posts_count'] ); ?>" />
						<div class="bws_info"><?php esc_html_e( 'Number of posts displayed in Related Posts block.', 'relevant' ); ?></div>
					</td>
				</tr>
				<tr>
					<th><?php esc_html_e( 'Date Range', 'relevant' ); ?></th>
					<td>
						<select name="rltdpstsplgn_display_related_posts" >
							<option value="All" id="selectedMonth" <?php selected( 'All' == $this->options['display_related_posts'] ); ?>><?php esc_html_e( 'All', 'relevant' ); ?></option>
							<option value="3 days ago" id="selectedMonth" <?php selected( '3 days ago' == $this->options['display_related_posts'] ); ?>>3 <?php esc_html_e( 'days', 'relevant' ); ?></option>
							<option value="5 days ago" id="selectedMonth" <?php selected( '5 days ago' == $this->options['display_related_posts'] ); ?>>5 <?php esc_html_e( 'days', 'relevant' ); ?></option>
							<option value="7 days ago" id="selectedMonth" <?php selected( '7 days ago' == $this->options['display_related_posts'] ); ?>>7 <?php esc_html_e( 'days', 'relevant' ); ?></option>
							<option value="1 month ago" id="selectedMonth" <?php selected( '1 month ago' == $this->options['display_related_posts'] ); ?>>1 <?php esc_html_e( 'month', 'relevant' ); ?></option>
							<option value="3 month ago" id="selectedMonth" <?php selected( '3 month ago' == $this->options['display_related_posts'] ); ?>>3 <?php esc_html_e( 'months', 'relevant' ); ?></option>
							<option value="6 month ago" id="selectedMonth" <?php selected( '6 month ago' == $this->options['display_related_posts'] ); ?>>6 <?php esc_html_e( 'months', 'relevant' ); ?></option>
						</select>
						<div class="bws_info"><?php esc_html_e( 'Show only posts not older than the indicated time period.', 'relevant' ); ?></div>
					</td>
				</tr>
				<tr>
					<th><?php esc_html_e( 'Display', 'relevant' ); ?></th>
					<td>
						<fieldset>
							<?php
							$related_show_options = array(
								'thumbnail'     => __( 'Featured image', 'relevant' ),
								'excerpt'       => __( 'Excerpt', 'relevant' ),
								'date'          => __( 'Post date', 'relevant' ),
								'author'        => __( 'Author', 'relevant' ),
								'reading_time'  => __( 'Reading time', 'relevant' ),
								'comments'      => __( 'Comments number', 'relevant' ),
							);
							foreach ( $related_show_options as $item => $label ) {
								?>
								<label>
									<input name="rltdpstsplgn_related_show_<?php echo esc_attr( $item ); ?>" type="checkbox" value="1" <?php checked( 1, $this->options[ "related_show_{$item}" ] ); ?> /> <?php echo esc_html( $label ); ?>
								</label><br />
								<?php
							}
							?>
						</fieldset>
					</td>
				</tr>
				<tr>
					<th><?php esc_html_e( 'Featured Image Placeholder URL', 'relevant' ); ?></th>
					<td>
						<input name="rltdpstsplgn_related_no_preview_img" type="text" maxlength="250" value="<?php echo esc_attr( $this->options['related_no_preview_img'] ); ?>"/>
						<div class="bws_info"><?php esc_html_e( 'Displayed if there is no featured image available.', 'relevant' ); ?></div>
					</td>
				</tr>
				<tr>
					<th><?php esc_html_e( 'Featured Image Size', 'relevant' ); ?></th>
					<td>
						<fieldset>
							<label>
								<input class="small-text" name="rltdpstsplgn_related_image_size_height" type="number" min="40" max="240" step="20" value="<?php echo esc_attr( $this->options['related_image_height'] ); ?>"/> px
							</label>
							<label>
								<input class="small-text" name="rltdpstsplgn_related_image_size_width" type="number" min="40" max="240" step="20" value="<?php echo esc_attr( $this->options['related_image_width'] ); ?>"/> px
							</label>
						</fieldset>
					</td>
				</tr>
				<tr>
					<th><?php esc_html_e( 'Excerpt Length', 'relevant' ); ?></th>
					<td>
						<input name="rltdpstsplgn_related_excerpt_length" type="number" min="1" max="1000" value="<?php echo esc_attr( $this->options['related_excerpt_length'] ); ?>"/>
						<?php esc_html_e( 'Words(s)', 'relevant' ); ?>
					</td>
				</tr>
				<tr>
					<th><?php esc_html_e( 'Read More Link Text', 'relevant' ); ?></th>
					<td>
						<input name="rltdpstsplgn_related_excerpt_more" type="text" maxlength="250" value="<?php echo esc_html( $this->options['related_excerpt_more'] ); ?>"/>
					</td>
				</tr>
				<tr>
					<th><?php esc_html_e( 'Search Related Words in', 'relevant' ); ?></th>
					<td>
						<fieldset>
							<label>
								<input type="radio" name="rltdpstsplgn_related_criteria" value="category"<?php checked( $this->options['related_criteria'], 'category' ); ?> />
								<?php esc_html_e( 'Categories', 'relevant' ); ?>
							</label><br />
							<label>
								<input type="radio" name="rltdpstsplgn_related_criteria" value="tags"<?php checked( $this->options['related_criteria'], 'tags' ); ?> />
								<?php esc_html_e( 'Tags', 'relevant' ); ?>
							</label><br />
							<label>
								<input type="radio" name="rltdpstsplgn_related_criteria" value="title"<?php checked( $this->options['related_criteria'], 'title' ); ?> />
								<?php esc_html_e( 'Titles', 'relevant' ); ?>
							</label><br />
							<label>
								<input type="radio" name="rltdpstsplgn_related_criteria" value="meta"<?php checked( $this->options['related_criteria'], 'meta' ); ?> />
								<?php esc_html_e( 'Meta Key', 'relevant' ); ?>
								<span class="bws_info">(<?php esc_html_e( 'Enable "Key" in the "Related Post" block which is located in the post you want to display.', 'relevant' ); ?>)</span>
							</label>
						</fieldset>
						<span class="bws_info"><?php esc_html_e( 'Search related words on posts.', 'relevant' ); ?></span>
					</td>
				</tr>
				<tr>
					<th><?php esc_html_e( 'Current Category', 'relevant' ); ?></th>
					<td>
						<input name="rltdpstsplgn_related_use_category" type="checkbox" value="1" <?php checked( 1, $this->options['related_use_category'] ); ?>/> <span class="bws_info"><?php esc_html_e( 'Enable to display posts from the current category only.', 'relevant' ); ?></span>
					</td>
				</tr>
				<tr>
					<th><?php esc_html_e( 'Block Position', 'relevant' ); ?></th>
					<td>
						<fieldset>
							<label>
								<input type="checkbox" value="before" name="rltdpstsplgn_related_display[]" <?php checked( in_array( 'before', $this->options['related_display'] ), true ); ?> />
								<?php esc_html_e( 'Before content', 'relevant' ); ?>
							</label><br />
							<label>
								<input type="checkbox" value="after" name="rltdpstsplgn_related_display[]" <?php checked( in_array( 'after', $this->options['related_display'] ), true ); ?> />
								<?php esc_html_e( 'After content', 'relevant' ); ?>
							</label>
						</fieldset>
					</td>
				</tr>
				<tr>
					<th><?php esc_html_e( '"No Posts Found" Message', 'relevant' ); ?></th>
					<td>
						<input type="text" name="rltdpstsplgn_related_no_posts_message" maxlength="250" value="<?php echo wp_kses_post( $this->options['related_no_posts_message'] ); ?>" />
					</td>
				</tr>
				<tr>
					<th><?php esc_html_e( 'Search on Pages', 'relevant' ); ?></th>
					<td>
						<fieldset>
							<label>
								<input type="checkbox" name="rltdpstsplgn_related_add_for_page[]" value="category" <?php checked( in_array( 'category', $this->options['related_add_for_page'] ) ); ?> />
								<?php esc_html_e( 'Categories', 'relevant' ); ?>
								<span class="bws_info">(<?php esc_html_e( 'Post categories will be available for pages.', 'relevant' ); ?>)</span>
							</label><br />
							<label>
								<input type="checkbox" name="rltdpstsplgn_related_add_for_page[]" value="tags" <?php checked( in_array( 'tags', $this->options['related_add_for_page'] ) ); ?> />
								<?php esc_html_e( 'Tags', 'relevant' ); ?>
								<span class="bws_info">(<?php esc_html_e( 'Post tags will be available for pages.', 'relevant' ); ?>)</span>
							</label><br />
							<label>
								<input type="checkbox" name="rltdpstsplgn_related_add_for_page[]" value="title" <?php checked( in_array( 'title', $this->options['related_add_for_page'] ) ); ?> />
								<?php esc_html_e( 'Title', 'relevant' ); ?>
							</label><br />
							<label>
								<input type="checkbox" name="rltdpstsplgn_related_add_for_page[]" value="meta" <?php checked( in_array( 'meta', $this->options['related_add_for_page'] ) ); ?> />
								<?php esc_html_e( 'Meta Key', 'relevant' ); ?>
							</label>
							<div class="bws_info"><?php esc_html_e( 'Enable to search related words on pages.', 'relevant' ); ?></div>
						</fieldset>
					</td>
				</tr>
			</table>
			
			<?php
		}

		/**
		 * Display Featured Posts Tab
		 */
		public function tab_featured_posts() {
			?>
			<h3 class="bws_tab_label"><?php esc_html_e( 'Featured Posts Settings', 'relevant' ); ?></h3>
			<?php $this->help_phrase(); ?>
			<hr>
			<p><?php esc_html_e( 'Navigate to a single post or page and enable Featured Posts option.', 'relevant' ); ?></p>
			<table class="form-table">
				<tr>
					<th><?php esc_html_e( 'Title', 'relevant' ); ?></th>
					<td>
						<input type="text" name="rltdpstsplgn_featured_title" maxlength="250" value="<?php echo isset( $this->options['featured_title'] ) ? esc_html( $this->options['featured_title'] ) : ''; ?>" />
					</td>
				</tr>
				<tr>
					<th><?php esc_html_e( 'Number of Posts', 'relevant' ); ?></th>
					<td>
						<input type="number" min="1" max="999" value="<?php echo esc_attr( $this->options['featured_posts_count'] ); ?>" name="rltdpstsplgn_featured_posts_count" />
						<div class="bws_info"><?php esc_html_e( 'Number of posts displayed in Featured Posts block.', 'relevant' ); ?></div>
					</td>
				</tr>
				<tr>
					<th><?php esc_html_e( 'Date Range', 'relevant' ); ?></th>
					<td>
						<select name="rltdpstsplgn_display_featured_posts" >
							<option value="All" id="selectedMonth" <?php selected( 'All' == $this->options['display_featured_posts'] ); ?>><?php esc_html_e( 'All', 'relevant' ); ?></option>
							<option value="3 days ago" id="selectedMonth" <?php selected( '3 days ago' == $this->options['display_featured_posts'] ); ?>>3 <?php esc_html_e( 'days', 'relevant' ); ?></option>
							<option value="5 days ago" id="selectedMonth" <?php selected( '5 days ago' == $this->options['display_featured_posts'] ); ?>>5 <?php esc_html_e( 'days', 'relevant' ); ?></option>
							<option value="7 days ago" id="selectedMonth" <?php selected( '7 days ago' == $this->options['display_featured_posts'] ); ?>>7 <?php esc_html_e( 'days', 'relevant' ); ?></option>
							<option value="1 month ago" id="selectedMonth" <?php selected( '1 month ago' == $this->options['display_featured_posts'] ); ?>>1 <?php esc_html_e( 'month', 'relevant' ); ?></option>
							<option value="3 month ago" id="selectedMonth" <?php selected( '3 month ago' == $this->options['display_featured_posts'] ); ?>>3 <?php esc_html_e( 'months', 'relevant' ); ?></option>
							<option value="6 month ago" id="selectedMonth" <?php selected( '6 month ago' == $this->options['display_featured_posts'] ); ?>>6 <?php esc_html_e( 'months', 'relevant' ); ?></option>
						</select>
						<div class="bws_info"><?php esc_html_e( 'Show only posts not older than the indicated time period.', 'relevant' ); ?></div>
					</td>
				</tr>
				<tr>
					<th><?php esc_html_e( 'Display', 'relevant' ); ?></th>
					<td>
						<fieldset>
							<?php
							$featured_show_options = array(
								'excerpt'       => __( 'Excerpt', 'relevant' ),
								'date'          => __( 'Post date', 'relevant' ),
								'author'        => __( 'Author', 'relevant' ),
								'reading_time'  => __( 'Reading time', 'relevant' ),
								'comments'      => __( 'Comments number', 'relevant' ),
								'thumbnail'     => __( 'Featured image', 'relevant' ),
							);
							foreach ( $featured_show_options as $item => $label ) {
								?>
								<label>
									<input name="rltdpstsplgn_featured_show_<?php echo esc_attr( $item ); ?>" type="checkbox" value="1" <?php checked( 1, $this->options[ "featured_show_{$item}" ] ); ?> /> <?php echo esc_html( $label ); ?>
								</label><br />
							<?php } ?>
						</fieldset>
					</td>
				</tr>
				<tr>
					<th><?php esc_html_e( 'Featured Image Placeholder URL', 'relevant' ); ?></th>
					<td>
						<input name="rltdpstsplgn_featured_no_preview_img" type="text" maxlength="250" value="<?php echo esc_attr( $this->options['featured_no_preview_img'] ); ?>"/>
						<div class="bws_info"><?php esc_html_e( 'Displayed if there is no featured image available.', 'relevant' ); ?></div>
					</td>
				</tr>
				<tr>
					<th><?php esc_html_e( 'Featured Image Size', 'relevant' ); ?></th>
					<td>
						<fieldset>
							<label>
								<input class="small-text" name="rltdpstsplgn_featured_image_size_height" type="number" min="40" max="240" step="20" value="<?php echo esc_attr( $this->options['featured_image_height'] ); ?>"/> px
							</label>
							<label>
								<input class="small-text" name="rltdpstsplgn_featured_image_size_width" type="number" min="40" max="240" step="20" value="<?php echo esc_attr( $this->options['featured_image_width'] ); ?>"/> px
							</label>
						</fieldset>
					</td>
				</tr>
				<tr>
					<th><?php esc_html_e( 'Excerpt Length', 'relevant' ); ?></th>
					<td>
						<input name="rltdpstsplgn_featured_excerpt_length" type="number" min="1" max="1000" value="<?php echo esc_attr( $this->options['featured_excerpt_length'] ); ?>"/>
						<?php esc_html_e( 'Words(s)', 'relevant' ); ?>
					</td>
				</tr>
				<tr>
					<th><?php esc_html_e( 'Read More Link Text', 'relevant' ); ?></th>
					<td>
						<input name="rltdpstsplgn_featured_excerpt_more" type="text" maxlength="250" value="<?php echo esc_html( $this->options['featured_excerpt_more'] ); ?>"/>
					</td>
				</tr>
				<tr>
					<th><?php esc_html_e( 'Current Category', 'relevant' ); ?></th>
					<td>
						<input name="rltdpstsplgn_featured_use_category" type="checkbox" value="1" <?php checked( 1, $this->options['featured_use_category'] ); ?> /> <span class="bws_info"><?php esc_html_e( 'Enable to display posts from the current category only.', 'relevant' ); ?></span>
					</td>
				</tr>
				<tr>
					<th><?php esc_html_e( 'Block Position', 'relevant' ); ?></th>
					<td>
						<fieldset>
							<label>
								<input type="checkbox" value="before" name="rltdpstsplgn_featured_display[]" <?php checked( in_array( 'before', $this->options['featured_display'] ), true ); ?> />
								<?php esc_html_e( 'Before content', 'relevant' ); ?>
							</label><br />
							<label>
								<input type="checkbox" value="after" name="rltdpstsplgn_featured_display[]" <?php checked( in_array( 'after', $this->options['featured_display'] ), true ); ?> />
								<?php esc_html_e( 'After content', 'relevant' ); ?>
							</label>
						</fieldset>
					</td>
				</tr>
				<tr>
					<th><?php esc_html_e( 'Block Width', 'relevant' ); ?></th>
					<td>
						<input id="block_width_id" type="number" min="10" value="<?php echo esc_attr( $this->options['featured_block_width'] ); ?>" name="rltdpstsplgn_featured_block_width">
						<select class = "rltdpstsplgn_block_unit" name="rltdpstsplgn_featured_block_unit">
							<option value='%' <?php selected( '%', $this->options['featured_block_width_remark'] ); ?> >%</option>
							<option value='px' <?php selected( 'px', $this->options['featured_block_width_remark'] ); ?> >px</option>
						</select>
						<div class="bws_info"><?php printf( esc_html__( 'Enter the value in %1$s or %2$s, for example, %3$s or %4$s.', 'relevant' ), '&#37;', 'px', '100&#37;', '960px' ); ?></div>
					</td>
				</tr>
				<tr>
					<th><?php esc_html_e( 'Content Block Width', 'relevant' ); ?></th>
					<td>
						<input id="text_width_id" type="number" min="10" value="<?php echo esc_attr( $this->options['featured_text_block_width'] ); ?>" name="rltdpstsplgn_featured_text_block_width" />
						<select class = "rltdpstsplgn_text_unit" name="rltdpstsplgn_featured_text_block_unit">
							<option value='%' <?php selected( '%', $this->options['featured_text_block_width_remark'] ); ?> >%</option>
							<option value='px' <?php selected( 'px', $this->options['featured_text_block_width_remark'] ); ?> >px</option>
						</select>
						<div class="bws_info"><?php printf( esc_html__( 'Enter the value in %1$s or %2$s, for example, %3$s or %4$s.', 'relevant' ), '&#37;', 'px', '100&#37;', '960px' ); ?></div>
					</td>
				</tr>
				<tr>
					<th><?php esc_html_e( 'Custom Style', 'relevant' ); ?></th>
					<td>
						<label>
							<input type="checkbox" value="1" name="rltdpstsplgn_featured_theme_style" <?php checked( $this->options['featured_theme_style'], 1 ); ?> class="bws_option_affect" data-affect-show=".rltdpstsplgn_theme_style" />
							<span class="bws_info"><?php esc_html_e( 'Enable to add custom styles for Featured Posts block.', 'relevant' ); ?></span>
						</label>
					</td>
				</tr>
				<tr class="rltdpstsplgn_theme_style">
					<th><?php esc_html_e( 'Block Background Color', 'relevant' ); ?></th>
					<td>
						<input type="text" value="<?php echo esc_attr( $this->options['featured_background_color_block'] ); ?>" name="rltdpstsplgn_featured_background_color_block" maxlength="7" class="rltdpstsplgn_colorpicker" />
					</td>
				</tr>
				<tr class="rltdpstsplgn_theme_style">
					<th><?php esc_html_e( 'Text Background Color', 'relevant' ); ?></th>
					<td>
						<input type="text" value="<?php echo esc_attr( $this->options['featured_background_color_text'] ); ?>" name="rltdpstsplgn_featured_background_color_text" maxlength="7" class="rltdpstsplgn_colorpicker" />
					</td>
				</tr>
				<tr class="rltdpstsplgn_theme_style">
					<th><?php esc_html_e( 'Title Color', 'relevant' ); ?></th>
					<td>
						<input type="text" value="<?php echo esc_attr( $this->options['featured_color_header'] ); ?>" name="rltdpstsplgn_featured_color_header" maxlength="7" class="rltdpstsplgn_colorpicker" />
					</td>
				</tr>
				<tr class="rltdpstsplgn_theme_style">
					<th><?php esc_html_e( 'Text Color', 'relevant' ); ?></th>
					<td>
						<input type="text" value="<?php echo esc_attr( $this->options['featured_color_text'] ); ?>" name="rltdpstsplgn_featured_color_text" maxlength="7" class="rltdpstsplgn_colorpicker" />
					</td>
				</tr>
				<tr class="rltdpstsplgn_theme_style">
					<th><?php esc_html_e( 'Read More Link Text Color', 'relevant' ); ?></th>
					<td>
						<input type="text" value="<?php echo esc_attr( $this->options['featured_color_link'] ); ?>" name="rltdpstsplgn_featured_color_link" maxlength="7" class="rltdpstsplgn_colorpicker" />
					</td>
				</tr>
			</table>
			<?php wp_nonce_field( 'rltdpstsplgn_settings', 'rltdpstsplgn_settings_nonce' ); ?>
			<?php
		}

		/**
		 * Display Latest Posts Tab
		 */
		public function tab_latest_posts() {
			?>
			<h3 class="bws_tab_label"><?php esc_html_e( 'Latest Posts Settings', 'relevant' ); ?></h3>
			<?php $this->help_phrase(); ?>
			<hr>
			<table class="form-table">
				<tr>
					<th><?php esc_html_e( 'Title', 'relevant' ); ?></th>
					<td>
						<input name="rltdpstsplgn_latest_title" type="text" maxlength="250" value="<?php echo esc_html( $this->options['latest_title'] ); ?>"/>
					</td>
				</tr>
				<tr>
					<th><?php esc_html_e( 'Number of Posts', 'relevant' ); ?></th>
					<td>
						<input name="rltdpstsplgn_latest_posts_count" type="number" min="1" max="1000" value="<?php echo esc_attr( $this->options['latest_posts_count'] ); ?>" />
						<div class="bws_info"><?php esc_html_e( 'Number of posts displayed in Latest Posts block.', 'relevant' ); ?></div>
					</td>
				</tr>
				<tr>
					<th><?php esc_html_e( 'Display', 'relevant' ); ?></th>
					<td>
						<fieldset>
							<?php
							$latest_show_options = array(
								'thumbnail'     => __( 'Featured image', 'relevant' ),
								'excerpt'       => __( 'Excerpt', 'relevant' ),
								'date'          => __( 'Post date', 'relevant' ),
								'author'        => __( 'Author', 'relevant' ),
								'reading_time'  => __( 'Reading time', 'relevant' ),
								'comments'      => __( 'Comments number', 'relevant' ),
							);
							foreach ( $latest_show_options as $item => $label ) {
								?>
								<label>
									<input name="rltdpstsplgn_latest_show_<?php echo esc_attr( $item ); ?>" type="checkbox" value="1" <?php checked( 1, $this->options[ "latest_show_{$item}" ] ); ?> /> <?php echo esc_html( $label ); ?>
								</label>
								<br />
							<?php } ?>
						</fieldset>
					</td>
				</tr>
				<tr>
					<th><?php esc_html_e( 'Featured Image Placeholder URL', 'relevant' ); ?></th>
					<td>
						<input name="rltdpstsplgn_latest_no_preview_img" type="text" maxlength="250" value="<?php echo esc_attr( $this->options['latest_no_preview_img'] ); ?>"/>
						<div class="bws_info"><?php esc_html_e( 'Displayed if there is no featured image available.', 'relevant' ); ?></div>
					</td>
				</tr>
				<tr>
					<th><?php esc_html_e( 'Featured Image Size', 'relevant' ); ?></th>
					<td>
						<fieldset>
							<label>
								<input class="small-text" name="rltdpstsplgn_latest_image_size_height" type="number" min="40" max="240" step="20" value="<?php echo esc_attr( $this->options['latest_image_height'] ); ?>"/> px
							</label>
							<label>
								<input class="small-text" name="rltdpstsplgn_latest_image_size_width" type="number" min="40" max="240" step="20" value="<?php echo esc_attr( $this->options['latest_image_width'] ); ?>"/> px
							</label>
						</fieldset>
					</td>
				</tr>
				<tr>
					<th><?php esc_html_e( 'Excerpt Length', 'relevant' ); ?></th>
					<td>
						<input name="rltdpstsplgn_latest_excerpt_length" type="number" min="1" max="1000" value="<?php echo esc_attr( $this->options['latest_excerpt_length'] ); ?>"/>
						<?php esc_html_e( 'Words(s)', 'relevant' ); ?>
					</td>
				</tr>
				<tr>
					<th><?php esc_html_e( 'Read More Link Text', 'relevant' ); ?></th>
					<td>
						<input name="rltdpstsplgn_latest_excerpt_more" type="text" maxlength="250" value="<?php echo esc_html( $this->options['latest_excerpt_more'] ); ?>"/>
					</td>
				</tr>
				<tr>
					<th><?php esc_html_e( 'Current Category', 'relevant' ); ?></th>
					<td>
						<input name="rltdpstsplgn_latest_use_category" type="checkbox" value="1" <?php checked( 1, $this->options['latest_use_category'] ); ?> /> <span class="bws_info"><?php esc_html_e( 'Enable to display posts from the current category only.', 'relevant' ); ?></span>
					</td>
				</tr>
				<tr>
					<th><?php esc_html_e( 'Block Position', 'relevant' ); ?></th>
					<td>
						<fieldset>
							<label>
								<input type="checkbox" value="before" name="rltdpstsplgn_latest_display[]" <?php checked( in_array( 'before', $this->options['latest_display'] ), true ); ?> />
								<?php esc_html_e( 'Before content', 'relevant' ); ?>
							</label>
							<br />
							<label>
								<input type="checkbox" value="after" name="rltdpstsplgn_latest_display[]" <?php checked( in_array( 'after', $this->options['latest_display'] ), true ); ?> />
								<?php esc_html_e( 'After content', 'relevant' ); ?>
							</label>
						</fieldset>
					</td>
				</tr>
			</table>
			<?php wp_nonce_field( 'rltdpstsplgn_settings', 'rltdpstsplgn_settings_nonce' ); ?>
			<?php
		}

		/**
		 * Display Popular Posts Tab
		 */
		public function tab_popular_posts() {
			?>
			<h3 class="bws_tab_label"><?php esc_html_e( 'Popular Posts Settings', 'relevant' ); ?></h3>
			<?php $this->help_phrase(); ?>
			<hr>
			<table class="form-table">
				<tr>
					<th><?php esc_html_e( 'Title', 'relevant' ); ?></th>
					<td>
						<input name="rltdpstsplgn_popular_title" type="text" maxlength="250" value="<?php echo esc_html( $this->options['popular_title'] ); ?>"/>
					</td>
				</tr>
				<tr>
					<th><?php esc_html_e( 'Number of Posts', 'relevant' ); ?></th>
					<td>
						<input name="rltdpstsplgn_popular_posts_count" type="number" min="1" max="1000" value="<?php echo esc_attr( $this->options['popular_posts_count'] ); ?>"/>
						<div class="bws_info"><?php esc_html_e( 'Number of posts displayed in Popular Posts block.', 'relevant' ); ?></div>
					</td>
				</tr>
				<tr>
					<th><?php esc_html_e( 'Date Range', 'relevant' ); ?></th>
					<td>
						<select name="rltdpstsplgn_display_popular_posts" >
							<option value="All" id="selectedMonth" <?php selected( 'All' == $this->options['display_popular_posts'] ); ?>><?php esc_html_e( 'All', 'relevant' ); ?></option>
							<option value="3 days ago" id="selectedMonth" <?php selected( '3 days ago' == $this->options['display_popular_posts'] ); ?>>3 <?php esc_html_e( 'days', 'relevant' ); ?></option>
							<option value="5 days ago" id="selectedMonth" <?php selected( '5 days ago' == $this->options['display_popular_posts'] ); ?>>5 <?php esc_html_e( 'days', 'relevant' ); ?></option>
							<option value="7 days ago" id="selectedMonth" <?php selected( '7 days ago' == $this->options['display_popular_posts'] ); ?>>7 <?php esc_html_e( 'days', 'relevant' ); ?></option>
							<option value="1 month ago" id="selectedMonth" <?php selected( '1 month ago' == $this->options['display_popular_posts'] ); ?>>1 <?php esc_html_e( 'month', 'relevant' ); ?></option>
							<option value="3 month ago" id="selectedMonth" <?php selected( '3 month ago' == $this->options['display_popular_posts'] ); ?>>3 <?php esc_html_e( 'months', 'relevant' ); ?></option>
							<option value="6 month ago" id="selectedMonth" <?php selected( '6 month ago' == $this->options['display_popular_posts'] ); ?>>6 <?php esc_html_e( 'months', 'relevant' ); ?></option>
						</select>
						<div class="bws_info"><?php esc_html_e( 'Show only posts not older than the indicated time period.', 'relevant' ); ?></div>
					</td>
				</tr>
				<tr>
					<th><?php esc_html_e( 'Display', 'relevant' ); ?></th>
					<td>
						<fieldset>
							<?php
							$show_options = array(
								'views'         => __( 'Views number', 'relevant' ),
								'thumbnail'     => __( 'Featured image', 'relevant' ),
								'excerpt'       => __( 'Excerpt', 'relevant' ),
								'date'          => __( 'Post date', 'relevant' ),
								'author'        => __( 'Author', 'relevant' ),
								'reading_time'  => __( 'Reading time', 'relevant' ),
								'comments'      => __( 'Comments number', 'relevant' ),
							);
							foreach ( $show_options as $item => $label ) {
								?>
									<label>
										<input name="rltdpstsplgn_popular_show_<?php echo esc_attr( $item ); ?>" type="checkbox" value="1" <?php checked( 1, $this->options[ "popular_show_{$item}" ] ); ?> /><?php echo esc_html( $label ); ?>
									</label><br />
								<?php } ?>
						</fieldset>
					</td>
				</tr>
				<tr>
					<th><?php esc_html_e( 'Featured Image Placeholder URL', 'relevant' ); ?></th>
					<td>
						<input name="rltdpstsplgn_popular_no_preview_img" type="text" maxlength="250" value="<?php echo esc_attr( $this->options['popular_no_preview_img'] ); ?>"/>
						<div class="bws_info"><?php esc_html_e( 'Displayed if there is no featured image available.', 'relevant' ); ?></div>
					</td>
				</tr>
				<tr>
					<th><?php esc_html_e( 'Featured Image Size', 'relevant' ); ?></th>
					<td>
						<fieldset>
							<label>
								<input class="small-text" name="rltdpstsplgn_popular_image_size_height" type="number" min="40" max="240" step="20" value="<?php echo esc_attr( $this->options['popular_image_height'] ); ?>"/> px
							</label>
							<label>
								<input class="small-text" name="rltdpstsplgn_popular_image_size_width" type="number" min="40" max="240" step="20" value="<?php echo esc_attr( $this->options['popular_image_width'] ); ?>"/> px
							</label>
						</fieldset>
					</td>
				</tr>
				<tr>
					<th><?php esc_html_e( 'Min Posts Number', 'relevant' ); ?></th>
					<td>
						<input name="rltdpstsplgn_popular_min_posts_count" type="number" min="0" max="9999" step="1" value="<?php echo esc_attr( $this->options['popular_min_posts_count'] ); ?>" />
						<div class="bws_info"><?php esc_html_e( 'Hide Popular Posts block if posts count is less than specified.', 'relevant' ); ?></div>
					</td>
				</tr>
				<tr>
					<th><?php esc_html_e( 'Excerpt Length', 'relevant' ); ?></th>
					<td>
						<input name="rltdpstsplgn_popular_excerpt_length" type="number" min="1" max="10000" value="<?php echo esc_attr( $this->options['popular_excerpt_length'] ); ?>"/>
						<?php esc_html_e( 'Words(s)', 'relevant' ); ?>
					</td>
				</tr>
				<tr>
					<th><?php esc_html_e( 'Read More Link Text', 'relevant' ); ?></th>
					<td>
						<input name="rltdpstsplgn_popular_excerpt_more" type="text" maxlength="250" value="<?php echo esc_html( $this->options['popular_excerpt_more'] ); ?>"/>
					</td>
				</tr>
				<tr>
					<th><?php esc_html_e( 'Sort Posts by Number of', 'relevant' ); ?></th>
					<td>
						<fieldset>
							<label><input name="rltdpstsplgn_popular_order_by" type="radio" value="comment_count" <?php checked( 'comment_count', $this->options['popular_order_by'] ); ?> /><?php esc_html_e( 'Comments', 'relevant' ); ?></label>
							<br />
							<label><input name="rltdpstsplgn_popular_order_by" type="radio" value="views_count" <?php checked( 'views_count', $this->options['popular_order_by'] ); ?> /><?php esc_html_e( 'Views', 'relevant' ); ?></label>
						</fieldset>
					</td>
				</tr>
				<tr>
					<th><?php esc_html_e( 'Current Category', 'relevant' ); ?></th>
					<td>
						<input name="rltdpstsplgn_popular_use_category" type="checkbox" value="1" <?php checked( 1, $this->options['popular_use_category'] ); ?> /> <span class="bws_info"><?php esc_html_e( 'Enable to display posts from the current category only.', 'relevant' ); ?></span>
					</td>
				</tr>
				<tr>
					<th><?php esc_html_e( 'Block Position', 'relevant' ); ?></th>
					<td>
						<fieldset>
							<label>
								<input type="checkbox" value="before" name="rltdpstsplgn_popular_display[]" <?php checked( in_array( 'before', $this->options['popular_display'] ), true ); ?> />
								<?php esc_html_e( 'Before content', 'relevant' ); ?>
							</label>
							<br />
							<label>
								<input type="checkbox" value="after" name="rltdpstsplgn_popular_display[]" <?php checked( in_array( 'after', $this->options['popular_display'] ), true ); ?> />
								<?php esc_html_e( 'After content', 'relevant' ); ?>
							</label>
						</fieldset>
					</td>
				</tr>
			</table>
			<?php wp_nonce_field( 'rltdpstsplgn_settings', 'rltdpstsplgn_settings_nonce' ); ?>
			<?php
		}

		/**
		 * Display Recommendation Tab
		 */
		public function tab_recommend_button() {
			?>
			<h3 class="bws_tab_label"><?php esc_html_e( 'Recommendation Settings', 'relevant' ); ?></h3>
			<?php $this->help_phrase(); ?>
			<hr>

			<div class="bws_pro_version_bloc">
				<div class="bws_pro_version_table_bloc">
					<div class="bws_table_bg"></div>
					<table class="form-table bws_pro_version">
						<tr>
							<th scope="row"><?php esc_html_e( 'Add Button to', 'relevant' ); ?></th>
							<td>
								<fieldset>
									<?php foreach ( $this->post_types as $key => $value ) { ?>
										<label>
											<input type="checkbox" value="<?php echo esc_attr( $key ); ?>" /> <?php echo esc_html( $value ); ?>
										</label><br>
									<?php } ?>
								</fieldset>
							</td>
						</tr>
						<tr>
							<th><?php esc_html_e( 'Button Position', 'relevant' ); ?></th>
							<td>
								<fieldset>
									<?php foreach ( $this->button_positions as $key => $value ) { ?>
										<label><input type="radio" value="<?php echo esc_attr( $key ); ?>" />&nbsp;<?php echo esc_html( $value ); ?></label>
										<?php
										if ( 'top-bottom-right' !== $key ) {
											echo '<br>';
										}
										?>
									<?php } ?>
								</fieldset>
								<div class="bws_info"><?php esc_html_e( 'Select button position in the content (default is Top Right).', 'relevant' ); ?></div>
							</td>
						</tr>
						<tr>
							<th><?php esc_html_e( 'Enable Button for', 'relevant' ); ?></th>
							<td>
								<fieldset>
									<label class=hide-if-no-js>
										<input type="checkbox" class="rltdpstsplgn_recommend_select_all" /><strong> <?php esc_html_e( 'All', 'relevant' ); ?></strong>
									</label><br />
									<?php
									foreach ( $this->editable_roles as $role => $fields ) {
										printf(
											'<label><input type="checkbox" class="rltdpstsplgn_recommend_role" value="%1$s" /> %2$s</label><br/>',
											esc_attr( $role ),
											esc_attr( translate_user_role( $fields['name'] ) )
										);
									}
									?>
									<label>
										<input type="checkbox" disabled="disabled" /> <?php esc_html_e( 'Unauthorized', 'relevant' ); ?>
										<div class="bws_info"><?php esc_html_e( 'Will not be able to reccomend and will be asked to authorized.', 'relevant' ); ?></div>
									</label>
								</fieldset>
							</td>
						</tr>
					</table>
					<?php $this->bws_pro_block_links(); ?>
				</div>
			</div>
			<?php
		}

		/**
		 * Display Recommendation Appearance Tab
		 */
		public function tab_recommend_settings() {
			?>
			<h3 class="bws_tab_label"><?php esc_html_e( 'Recommendation Appearance', 'relevant' ); ?></h3>
			<?php $this->help_phrase(); ?>
			<hr>
			<div class="bws_pro_version_bloc">
				<div class="bws_pro_version_table_bloc">
					<div class="bws_table_bg"></div>
					<table class="form-table bws_pro_version">
						<tr valign="top">
							<th scope="row"><?php esc_html_e( 'Style', 'relevant' ); ?></th>
							<td>
								<fieldset>
									<?php foreach ( $this->submit_style as $key => $value ) { ?>
										<input type="radio" value="<?php echo esc_attr( $key ); ?>" />
										<label><img src="<?php echo esc_url( plugins_url( '../' . $value, __FILE__ ) ); ?>" alt="<?php echo esc_attr( $key ); ?>" /></label><br />
									<?php } ?>
								</fieldset>
							</td>
						</tr>
						<tr valign="top">
							<th scope="row"><?php esc_html_e( 'Height', 'relevant' ); ?></th>
							<td>
								<fieldset>
									<input type="number" value="45" min="1" max="999" step="1" />
									<label>px</label>
									<div class="bws_info"><?php esc_html_e( 'The recommended height should be at least 2.5 times the font size for the button to look correct', 'relevant' ); ?></div>
									<div class="bws_info"><?php esc_html_e( 'If the font size is larger than the width/1.5, then the height of the button will be equal to the "auto"', 'relevant' ); ?></div>
								</fieldset>
							</td>
						</tr>
						<tr valign="top">
							<th scope="row"><?php esc_html_e( 'Width', 'relevant' ); ?></th>
							<td>
								<fieldset>
									<input type="number" value="100" min="1" max="999" step="1" />
									<label>px</label>
									<div class="bws_info"><?php esc_html_e( 'If the content of the block is larger than the width, then the width of the button will be equal to the width of the content.', 'relevant' ); ?></div>
								</fieldset>
							</td>
						</tr>
						<tr valign="top">
							<th scope="row"><?php esc_html_e( 'Text', 'relevant' ); ?></th>
							<td>
								<fieldset>
									<input type="text" value="<?php esc_html_e( 'Recommend', 'relevant' ); ?>"  />
									<div class="bws_info"><?php esc_html_e( 'Only for button with text', 'relevant' ); ?></div>
								</fieldset>
							</td>
						</tr>
						<tr valign="top">
							<th scope="row"><?php esc_html_e( 'Font size', 'relevant' ); ?></th>
							<td>
								<fieldset>
									<input type="number" value="14" min="1" max="999" step="1" />
									<label>px</label>
									<div class="bws_info"><?php esc_html_e( 'Only for button with text', 'relevant' ); ?></div>
								</fieldset>
							</td>
						</tr>
						<tr valign="top" class="rltdpstsplgn_recommend_style_block">
							<th scope="row" style="width:200px;"><?php esc_html_e( 'Text color', 'relevant' ); ?></th>
							<td>
								<input maxlength="7" value="" data-alpha-enabled="true" data-default-color="rgba(255,255,255,1)" class="rltdpstsplgn_recommend_submit_color rltdpstsplgn_recommend_color" />
								<div class="bws_info"><?php esc_html_e( 'Only for button with text', 'relevant' ); ?></div>
							</td>
						</tr>
						<tr valign="top" class="rltdpstsplgn_recommend_style_block">
							<th scope="row" style="width:200px;"><?php esc_html_e( 'Backgroud color', 'relevant' ); ?></th>
							<td>
								<input maxlength="7" value="" data-alpha-enabled="true" data-default-color="rgba(120,193,87,1)" class="rltdpstsplgn_recommend_submit_backgroud rltdpstsplgn_recommend_color"/>
							</td>
						</tr>
						<tr valign="top">
							<th scope="row"><?php esc_html_e( 'Border radius', 'relevant' ); ?></th>
							<td>
								<fieldset>
									<input type="number" value="0" min="0" max="999" step="1" />
									<label>px</label>
								</fieldset>
							</td>
						</tr>
						<tr valign="top">
							<th scope="row"><?php esc_html_e( 'Border size', 'relevant' ); ?></th>
							<td>
								<fieldset>
									<input type="number" value="1" min="1" max="10" step="1" />
									<label>px</label>
									<div class="bws_info"><?php esc_html_e( 'Set "0" to remove the border', 'relevant' ); ?></div>
								</fieldset>
							</td>
						</tr>
						<tr valign="top" class="rltdpstsplgn_recommend_style_block">
							<th scope="row" style="width:200px;"><?php esc_html_e( 'Border color', 'relevant' ); ?></th>
							<td>
								<input maxlength="7" value="" data-alpha-enabled="true" data-default-color="rgba(120,193,87,0)" class="rltdpstsplgn_recommend_submit_border_color rltdpstsplgn_recommend_color"/>
							</td>
						</tr>
					</table>
					<?php $this->bws_pro_block_links(); ?>
				</div>
			</div>
			<?php
		}

		/**
		 * Display custom metabox
		 *
		 * @access public
		 */
		public function display_metabox() {
			?>
			<div class="postbox">
				<h3 class="hndle">
					<?php esc_html_e( 'Relevant Posts Shortcodes', 'relevant' ); ?>
				</h3>
				<div class="inside">
					<p><?php esc_html_e( 'Add "Related Posts", "Featured Posts", "Latest Posts" or "Popular Posts" to a widget.', 'relevant' ); ?> <a href="widgets.php"><?php esc_html_e( 'Navigate to Widgets', 'relevant' ); ?></a></p>
					<div class="bws_margined_box">
						<?php esc_html_e( 'Add related posts to your posts, pages or custom post types by using the following shortcode:', 'relevant' ); ?>
						<?php bws_shortcode_output( '[bws_related_posts]' ); ?>
					</div>
					<div class="bws_margined_box">
						<?php esc_html_e( 'Add related posts slider to posts by using the following shortcode:', 'relevant' ); ?>
						<?php bws_shortcode_output( '[bws_related_posts_slider]' ); ?>
					</div>
					<div class="bws_margined_box">
						<?php esc_html_e( 'Add featured posts to your posts, pages or custom post types by using the following shortcode:', 'relevant' ); ?>
						<?php bws_shortcode_output( '[bws_featured_post]' ); ?>
					</div>
					<div class="bws_margined_box">
						<?php esc_html_e( 'Add featured posts slider to posts by using the following shortcode:', 'relevant' ); ?>
						<?php bws_shortcode_output( '[bws_featured_post_slider]' ); ?>
					</div>
					<div class="bws_margined_box">
						<?php esc_html_e( 'Add related posts to your posts, pages and custom post types by slider:', 'relevant' ); ?>
						<?php bws_shortcode_output( '[bws_latest_posts]' ); ?>
					</div>
					<div class="bws_margined_box">
						<?php esc_html_e( 'Add latest posts slider to posts by using the following shortcode:', 'relevant' ); ?>
						<?php bws_shortcode_output( '[bws_latest_posts_slider]' ); ?>
					</div>
					<div class="bws_margined_box">
						<?php esc_html_e( 'Add popular posts to your posts, pages or custom post types by using the following shortcode:', 'relevant' ); ?>
						<?php bws_shortcode_output( '[bws_popular_posts]' ); ?>
					</div>
					<div class="bws_margined_box">
						<?php esc_html_e( 'Add popular posts slider to posts by using the following shortcode:', 'relevant' ); ?>
						<?php bws_shortcode_output( '[bws_popular_posts_slider]' ); ?>
					</div>
					<div class="bws_margined_box">
						<p><?php esc_html_e( 'Add featured posts to PHP template files by using the following code', 'relevant' ); ?>:</p>
						<code>&lt;?php do_action( 'ftrdpsts_featured_posts' ); ?&gt;</code>
					</div>
				</div>
			</div>
			<?php
		}
	}
}
