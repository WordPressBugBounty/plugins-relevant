<?php
/**
 * Create Latest_Posts widget
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Class Bws_Latest_Posts for Latest_Posts widget
 */
class Bws_Latest_Posts_Widget extends WP_Widget {

	/**
	 * Instantiate the parent object
	 */
	public function __construct() {
		parent::__construct(
			'ltstpsts_latest_posts_widget',
			__( 'Latest Posts', 'relevant' ),
			array( 'description' => __( 'Widget for Latest Posts displaying.', 'relevant' ) )
		);
	}

	/**
	 * Outputs the content of the widget
	 *
	 * @param array $args     Args gor widget.
	 * @param array $instance Widget options.
	 */
	public function widget( $args, $instance ) {
		global $rltdpstsplgn_options;

		$widget_title   = ( ! empty( $instance['widget_title'] ) ) ? apply_filters( 'widget_title', $instance['widget_title'], $instance, $this->id_base ) : '';
		$category       = isset( $instance['category'] ) ? $instance['category'] : 0;

		$rltdpstsplgn_options_old = $rltdpstsplgn_options;

		if ( isset( $instance['count'] ) ) {
			$rltdpstsplgn_options['latest_posts_count']     = $instance['count'];
		}
		if ( isset( $instance['height'] ) ) {
			$rltdpstsplgn_options['latest_image_height']    = $instance['height'];
		}
		if ( isset( $instance['width'] ) ) {
			$rltdpstsplgn_options['latest_image_width']     = $instance['width'];
		}
		if ( isset( $instance['excerpt_length'] ) ) {
			$rltdpstsplgn_options['latest_excerpt_length']  = intval( $instance['excerpt_length'] );
		}
		if ( isset( $instance['excerpt_more'] ) ) {
			$rltdpstsplgn_options['latest_excerpt_more']    = stripslashes( esc_html( $instance['excerpt_more'] ) );
		}
		if ( isset( $instance['no_preview_img'] ) ) {
			$rltdpstsplgn_options['latest_no_preview_img']  = $instance['no_preview_img'];
		}

		$rltdpstsplgn_options['latest_show_comments']       = isset( $instance['show_comments'] ) ? $instance['show_comments'] : 1;
		$rltdpstsplgn_options['latest_show_date']           = isset( $instance['show_date'] ) ? $instance['show_date'] : 1;
		$rltdpstsplgn_options['latest_show_author']         = isset( $instance['show_author'] ) ? $instance['show_author'] : 1;
		$rltdpstsplgn_options['latest_show_reading_time']   = isset( $instance['show_reading_time'] ) ? $instance['show_reading_time'] : 1;
		$rltdpstsplgn_options['latest_show_thumbnail']      = isset( $instance['show_image'] ) ? $instance['show_image'] : 1;
		$rltdpstsplgn_options['latest_show_excerpt']        = isset( $instance['show_excerpt'] ) ? $instance['show_excerpt'] : 1;
		$rltdpstsplgn_options['latest_use_category']        = isset( $instance['use_category'] ) ? $instance['use_category'] : 1;

		echo wp_kses_post( $args['before_widget'] );
		if ( ! empty( $widget_title ) ) {
			if ( ! empty( $category ) ) {
				echo '<a href="' . esc_url( get_category_link( $category ) ) . '">';
			}
			echo wp_kses_post( $args['before_title'] . $widget_title . $args['after_title'] );
			if ( ! empty( $category ) ) {
				echo '</a>';
			}
		}
		$post_title_tag = $this->get_post_title_tag( $args['before_title'] );
		$number = $this->number;
		echo rltdpstsplgn_latest_posts_block( $post_title_tag, true, $number, $category );
		echo wp_kses_post( $args['after_widget'] );

		$rltdpstsplgn_options = $rltdpstsplgn_options_old;
	}

	/**
	 * Outputs the options form on admin
	 *
	 * @param array $instance Widget options.
	 */
	public function form( $instance ) {
		global $rltdpstsplgn_latest_posts_excerpt_length, $rltdpstsplgn_latest_excerpt_more, $rltdpstsplgn_options;

		$widget_title       = isset( $instance['widget_title'] ) ? esc_html( wp_unslash( $instance['widget_title'] ) ) : $rltdpstsplgn_options['latest_title'];
		$count              = isset( $instance['count'] ) ? intval( $instance['count'] ) : $rltdpstsplgn_options['latest_posts_count'];
		$excerpt_length     = isset( $instance['excerpt_length'] ) ? intval( $instance['excerpt_length'] ) : $rltdpstsplgn_options['latest_excerpt_length'];
		$rltdpstsplgn_latest_posts_excerpt_length = isset( $instance['excerpt_length'] ) ? intval( $instance['excerpt_length'] ) : $rltdpstsplgn_options['latest_excerpt_length'];
		$excerpt_more       = isset( $instance['excerpt_more'] ) ? esc_html( wp_unslash( $instance['excerpt_more'] ) ) : $rltdpstsplgn_options['latest_excerpt_more'];
		$rltdpstsplgn_latest_excerpt_more = isset( $instance['excerpt_more'] ) ? esc_html( wp_unslash( $instance['excerpt_more'] ) ) : $rltdpstsplgn_options['latest_excerpt_more'];
		$no_preview_img     = isset( $instance['no_preview_img'] ) ? $instance['no_preview_img'] : $rltdpstsplgn_options['latest_no_preview_img'];
		$category           = isset( $instance['category'] ) ? $instance['category'] : 0;
		$show_comments      = isset( $instance['show_comments'] ) ? $instance['show_comments'] : $rltdpstsplgn_options['latest_show_comments'];
		$show_date          = isset( $instance['show_date'] ) ? $instance['show_date'] : $rltdpstsplgn_options['latest_show_date'];
		$show_author        = isset( $instance['show_author'] ) ? $instance['show_author'] : $rltdpstsplgn_options['latest_show_author'];
		$show_reading_time  = isset( $instance['show_reading_time'] ) ? $instance['show_reading_time'] : $rltdpstsplgn_options['latest_show_reading_time'];
		$show_image         = isset( $instance['show_image'] ) ? $instance['show_image'] : $rltdpstsplgn_options['latest_show_thumbnail'];
		$height             = isset( $instance['height'] ) ? $instance['height'] : $rltdpstsplgn_options['latest_image_height'];
		$width              = isset( $instance['width'] ) ? $instance['width'] : $rltdpstsplgn_options['latest_image_width'];
		$show_excerpt       = isset( $instance['show_excerpt'] ) ? $instance['show_excerpt'] : $rltdpstsplgn_options['latest_show_excerpt'];
		$use_category       = isset( $instance['use_category'] ) ? $instance['use_category'] : $rltdpstsplgn_options['latest_use_category']; ?>
		<p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'widget_title' ) ); ?>"><?php esc_html_e( 'Title', 'relevant' ); ?>: </label>
			<input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'widget_title' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'widget_title' ) ); ?>" type="text" maxlength="250" value="<?php echo esc_attr( $widget_title ); ?>"/>
		</p>
		<p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'use_category' ) ); ?>">
				<input id="<?php echo esc_attr( $this->get_field_id( 'use_category' ) ); ?>" class="bws_option_affect" data-affect-hide=".rltdpstsplgn_latest_category_select" name="<?php echo esc_attr( $this->get_field_name( 'use_category' ) ); ?>" type="checkbox" value="1"<?php checked( 1, $use_category ); ?> /> <?php esc_html_e( 'Display posts from the current category only', 'relevant' ); ?>
			</label>
		</p>
		<p class="rltdpstsplgn_latest_category_select">
			<label for="<?php echo esc_attr( $this->get_field_id( 'category' ) ); ?>"><?php esc_html_e( 'Category', 'relevant' ); ?>: </label>
			<?php
			wp_dropdown_categories(
				array(
					'show_option_all' => __( 'All categories', 'relevant' ),
					'name' => $this->get_field_name( 'category' ),
					'id' => $this->get_field_id( 'category' ),
					'selected' => $category,
				)
			);
			?>
		</p>
		<p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'count' ) ); ?>"><?php esc_html_e( 'Number of posts', 'relevant' ); ?>:
			<input class="tiny-text" id="<?php echo esc_attr( $this->get_field_id( 'count' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'count' ) ); ?>" type="number" min="1" max="1000" value="<?php echo esc_attr( $count ); ?>"/></label>
		</p>
		<p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'excerpt_length' ) ); ?>"><?php esc_html_e( 'Excerpt length', 'relevant' ); ?>: </label>
			<input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'excerpt_length' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'excerpt_length' ) ); ?>" type="number" min="1" max="10000" value="<?php echo esc_attr( $excerpt_length ); ?>"/>
		</p>
		<p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'excerpt_more' ) ); ?>"><?php esc_html_e( 'Read More Link Text', 'relevant' ); ?>: </label>
			<input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'excerpt_more' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'excerpt_more' ) ); ?>" type="text" maxlength="250" value="<?php echo esc_attr( $excerpt_more ); ?>"/>
		</p>
		<p>
			<?php esc_html_e( 'Show', 'relevant' ); ?>:<br />
			<label for="<?php echo esc_attr( $this->get_field_id( 'show_date' ) ); ?>">
				<input id="<?php echo esc_attr( $this->get_field_id( 'show_date' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'show_date' ) ); ?>" type="checkbox" value="1"<?php checked( 1, $show_date ); ?> />
				<?php esc_html_e( 'Post Date', 'relevant' ); ?>
			</label>
			<br />
			<label for="<?php echo esc_attr( $this->get_field_id( 'show_author' ) ); ?>">
				<input id="<?php echo esc_attr( $this->get_field_id( 'show_author' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'show_author' ) ); ?>" type="checkbox" value="1"<?php checked( 1, $show_author ); ?> />
				<?php esc_html_e( 'Author', 'relevant' ); ?>
			</label>
			<br />
			<label for="<?php echo esc_attr( $this->get_field_id( 'show_reading_time' ) ); ?>">
				<input id="<?php echo esc_attr( $this->get_field_id( 'show_reading_time' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'show_reading_time' ) ); ?>" type="checkbox" value="1"<?php checked( 1, $show_reading_time ); ?> />
				<?php esc_html_e( 'Reading time', 'relevant' ); ?>
			</label>
			<br />
			<label for="<?php echo esc_attr( $this->get_field_id( 'show_comments' ) ); ?>">
				<input id="<?php echo esc_attr( $this->get_field_id( 'show_comments' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'show_comments' ) ); ?>" type="checkbox" value="1"<?php checked( 1, $show_comments ); ?> />
				<?php esc_html_e( 'Comments number', 'relevant' ); ?>
			</label>
			<br />
			<label for="<?php echo esc_attr( $this->get_field_id( 'show_excerpt' ) ); ?>">
				<input id="<?php echo esc_attr( $this->get_field_id( 'show_excerpt' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'show_excerpt' ) ); ?>" type="checkbox" value="1"<?php checked( 1, $show_excerpt ); ?> />
				<?php esc_html_e( 'Excerpt', 'relevant' ); ?>
			</label>
			<br />
			<label for="<?php echo esc_attr( $this->get_field_id( 'show_image' ) ); ?>">
				<input id="<?php echo esc_attr( $this->get_field_id( 'show_image' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'show_image' ) ); ?>" type="checkbox" value="1"<?php checked( 1, $show_image ); ?> />
				<?php esc_html_e( 'Featured image', 'relevant' ); ?>
			</label>
			<br />
		</p>
		<p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'no_preview_img' ) ); ?>"><?php esc_html_e( 'Featured Image Placeholder URL', 'relevant' ); ?>: </label>
			<input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'no_preview_img' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'no_preview_img' ) ); ?>" type="text" maxlength="250" value="<?php echo esc_attr( $no_preview_img ); ?>"/><br />
			<small><?php esc_html_e( 'Displayed if there is no featured image available.', 'relevant' ); ?></small>
		</p>
		<p>
			<?php esc_html_e( 'Featured image size', 'relevant' ); ?>:<br />
			<label for="<?php echo esc_attr( $this->get_field_id( 'height' ) ); ?>"><?php esc_html_e( 'height', 'relevant' ); ?></label>
			<input class="tiny-text rltdpstsplgnwidget_image_size" id="<?php echo esc_attr( $this->get_field_id( 'height' ) ); ?>" type="number" min="40" max="240" step="20" name="<?php echo esc_attr( $this->get_field_name( 'height' ) ); ?>" value="<?php echo esc_attr( $height ); ?>"/>px
			<span class="bws_info">( <?php esc_html_e( 'Choose the size from 40px to 240px', 'relevant' ); ?> )</span><br />
			<label for="<?php echo esc_attr( $this->get_field_id( 'width' ) ); ?>">
				<?php
				esc_html_e( 'width', 'relevant' );
				echo '&nbsp';
				?>
			</label>
			<input class="tiny-text rltdpstsplgnwidget_image_size" id="<?php echo esc_attr( $this->get_field_id( 'width' ) ); ?>" type="number" min="40" max="240" step="20" name="<?php echo esc_attr( $this->get_field_name( 'width' ) ); ?>" value="<?php echo esc_attr( $width ); ?>"/>px
			<span class="bws_info">( <?php esc_html_e( 'Choose the size from 40px to 240px', 'relevant' ); ?> )</span>
		</p>
		<?php
	}

	/**
	 * Processing widget options on save
	 *
	 * @param array $new_instance New widget options.
	 * @param array $old_instance Widget options.
	 * @return array $instance Widget options.
	 */
	public function update( $new_instance, $old_instance ) {
		global $rltdpstsplgn_options;

		$instance = array();
		$instance['widget_title']   = ( isset( $new_instance['widget_title'] ) ) ? stripslashes( esc_html( $new_instance['widget_title'] ) ) : $rltdpstsplgn_options['latest_title'];
		$instance['count']          = ( ! empty( $new_instance['count'] ) ) ? intval( $new_instance['count'] ) : $rltdpstsplgn_options['latest_posts_count'];
		$instance['excerpt_length'] = ( ! empty( $new_instance['excerpt_length'] ) ) ? intval( $new_instance['excerpt_length'] ) : $rltdpstsplgn_options['latest_excerpt_length'];
		$instance['excerpt_more']   = ( ! empty( $new_instance['excerpt_more'] ) ) ? stripslashes( esc_html( $new_instance['excerpt_more'] ) ) : $rltdpstsplgn_options['latest_excerpt_more'];
		$instance['height']         = ( ! empty( $new_instance['height'] ) ) ? intval( $new_instance['height'] ) : $rltdpstsplgn_options['latest_image_height'];
		$instance['width']          = ( ! empty( $new_instance['width'] ) ) ? intval( $new_instance['width'] ) : $rltdpstsplgn_options['latest_image_width'];
		$instance['category']       = ( ! empty( $new_instance['category'] ) ) ? intval( $new_instance['category'] ) : 0;
		$instance['use_category']   = isset( $new_instance['use_category'] ) ? absint( $new_instance['use_category'] ) : 0;

		$show_options = array( 'comments', 'date', 'author', 'reading_time', 'image', 'excerpt' );
		if ( ! empty( $new_instance['no_preview_img'] ) && rltdpstsplgn_is_200( $new_instance['no_preview_img'] ) && getimagesize( $new_instance['no_preview_img'] ) ) {
			$instance['no_preview_img'] = $new_instance['no_preview_img'];
		} else {
			$instance['no_preview_img'] = $rltdpstsplgn_options['popular_no_preview_img'];
		}
		foreach ( $show_options as $item ) {
			$instance[ "show_{$item}" ]   = isset( $new_instance[ "show_{$item}" ] ) ? absint( $new_instance[ "show_{$item}" ] ) : 0;
		}

		return $instance;
	}

	/**
	 * Post title tag
	 *
	 * @param string $widget_tag Tag for title.
	 * @return string $widget_tag Tag for title.
	 */
	public function get_post_title_tag( $widget_tag ) {
		preg_match( '/h[1-5]{1}/', $widget_tag, $matches );
		if ( empty( $matches ) ) {
			return 'h1';
		}
		$number = absint( preg_replace( '/h/', '', $matches[0] ) );
		$number ++;
		return "h{$number}";
	}
}
