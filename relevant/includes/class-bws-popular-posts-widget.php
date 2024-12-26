<?php
/**
 * Create Popular Posts widget
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Class BWS_Popular_Posts for Latest_Posts widget
 */
class BWS_Popular_Posts_Widget extends WP_Widget {

	/**
	 * Instantiate the parent object
	 */
	public function __construct() {
		/* Instantiate the parent object */
		parent::__construct(
			'pplrpsts_popular_posts_widget',
			__( 'Popular Posts', 'relevant' ),
			array( 'description' => __( 'Widget for displaying Popular Posts by comments or views count.', 'relevant' ) )
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

		$widget_title = ( ! empty( $instance['widget_title'] ) ) ? apply_filters( 'widget_title', $instance['widget_title'], $instance, $this->id_base ) : '';

		$rltdpstsplgn_options_old = $rltdpstsplgn_options;

		if ( isset( $instance['count'] ) ) {
			$rltdpstsplgn_options['popular_posts_count']        = intval( $instance['count'] );
		}
		if ( isset( $instance['excerpt_length'] ) ) {
			$rltdpstsplgn_options['popular_excerpt_length']     = intval( $instance['excerpt_length'] );
		}
		if ( isset( $instance['excerpt_more'] ) ) {
			$rltdpstsplgn_options['popular_excerpt_more']       = esc_html( wp_unslash( $instance['excerpt_more'] ) );
		}
		if ( isset( $instance['no_preview_img'] ) ) {
			$rltdpstsplgn_options['popular_no_preview_img']     = $instance['no_preview_img'];
		}
		if ( isset( $instance['order_by'] ) ) {
			$rltdpstsplgn_options['popular_order_by']           = $instance['order_by'];
		}
		if ( isset( $instance['min_count'] ) ) {
			$rltdpstsplgn_options['popular_min_posts_count']    = intval( $instance['min_count'] );
		}
		if ( isset( $instance['height'] ) ) {
			$rltdpstsplgn_options['popular_image_height']       = $instance['height'];
		}
		if ( isset( $instance['width'] ) ) {
			$rltdpstsplgn_options['popular_image_width']        = $instance['width'];
		}

		$rltdpstsplgn_options['popular_show_views']             = isset( $instance['show_views'] ) ? $instance['show_views'] : 1;
		$rltdpstsplgn_options['popular_show_date']              = isset( $instance['show_date'] ) ? $instance['show_date'] : 1;
		$rltdpstsplgn_options['popular_show_author']            = isset( $instance['show_author'] ) ? $instance['show_author'] : 1;
		$rltdpstsplgn_options['popular_show_thumbnail']         = isset( $instance['show_image'] ) ? $instance['show_image'] : 1;
		$rltdpstsplgn_options['popular_show_excerpt']           = isset( $instance['show_excerpt'] ) ? $instance['show_excerpt'] : 1;
		$rltdpstsplgn_options['popular_show_reading_time']      = isset( $instance['show_reading_time'] ) ? $instance['show_reading_time'] : 1;
		$rltdpstsplgn_options['popular_show_comments']          = isset( $instance['show_comments'] ) ? $instance['show_comments'] : 1;
		$rltdpstsplgn_options['popular_use_category']           = isset( $instance['use_category'] ) ? $instance['use_category'] : 1;

		echo wp_kses_post( $args['before_widget'] );
		if ( ! empty( $widget_title ) ) {
			echo wp_kses_post( $args['before_title'] . $widget_title . $args['after_title'] );
		}
		$post_title_tag = $this->get_post_title_tag( $args['before_title'] );
		$number = $this->number;
		echo rltdpstsplgn_popular_posts_block( $post_title_tag, true, $number );
		echo wp_kses_post( $args['after_widget'] );

		$rltdpstsplgn_options = $rltdpstsplgn_options_old;
	}

	/**
	 * Outputs the options form on admin
	 *
	 * @param array $instance Widget options.
	 */
	public function form( $instance ) {
		global $rltdpstsplgn_popular_excerpt_length, $rltdpstsplgn_popular_excerpt_more, $rltdpstsplgn_options;

		$widget_title       = isset( $instance['widget_title'] ) ? esc_html( wp_unslash( $instance['widget_title'] ) ) : $rltdpstsplgn_options['popular_title'];
		$count              = isset( $instance['count'] ) ? intval( $instance['count'] ) : $rltdpstsplgn_options['popular_posts_count'];
		$min_count          = isset( $instance['min_count'] ) ? absint( $instance['min_count'] ) : $rltdpstsplgn_options['popular_min_posts_count'];
		$excerpt_length     = isset( $instance['excerpt_length'] ) ? intval( $instance['excerpt_length'] ) : $rltdpstsplgn_options['popular_excerpt_length'];
		$rltdpstsplgn_popular_excerpt_length = isset( $instance['excerpt_length'] ) ? intval( $instance['excerpt_length'] ) : $rltdpstsplgn_options['popular_excerpt_length'];
		$excerpt_more       = isset( $instance['excerpt_more'] ) ? esc_html( wp_unslash( $instance['excerpt_more'] ) ) : $rltdpstsplgn_options['popular_excerpt_more'];
		$rltdpstsplgn_popular_excerpt_more = isset( $instance['excerpt_more'] ) ? esc_html( wp_unslash( $instance['excerpt_more'] ) ) : $rltdpstsplgn_options['popular_excerpt_more'];
		$no_preview_img     = isset( $instance['no_preview_img'] ) ? $instance['no_preview_img'] : $rltdpstsplgn_options['popular_no_preview_img'];
		$order_by           = isset( $instance['order_by'] ) ? $instance['order_by'] : $rltdpstsplgn_options['popular_order_by'];
		$show_views         = isset( $instance['show_views'] ) ? $instance['show_views'] : $rltdpstsplgn_options['popular_show_views'];
		$show_date          = isset( $instance['show_date'] ) ? $instance['show_date'] : $rltdpstsplgn_options['popular_show_date'];
		$show_author        = isset( $instance['show_author'] ) ? $instance['show_author'] : $rltdpstsplgn_options['popular_show_author'];
		$show_image         = isset( $instance['show_image'] ) ? $instance['show_image'] : $rltdpstsplgn_options['popular_show_thumbnail'];
		$show_excerpt       = isset( $instance['show_excerpt'] ) ? $instance['show_excerpt'] : $rltdpstsplgn_options['popular_show_excerpt'];
		$show_reading_time  = isset( $instance['show_reading_time'] ) ? $instance['show_reading_time'] : $rltdpstsplgn_options['popular_show_reading_time'];
		$show_comments      = isset( $instance['show_comments'] ) ? $instance['show_comments'] : $rltdpstsplgn_options['popular_show_comments'];
		$height             = isset( $instance['height'] ) ? $instance['height'] : $rltdpstsplgn_options['popular_image_height'];
		$width              = isset( $instance['width'] ) ? $instance['width'] : $rltdpstsplgn_options['popular_image_width'];
		$use_category       = isset( $instance['use_category'] ) ? $instance['use_category'] : $rltdpstsplgn_options['popular_use_category']; ?>
		<p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'widget_title' ) ); ?>"><?php esc_html_e( 'Title', 'relevant' ); ?>:</label>
			<input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'widget_title' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'widget_title' ) ); ?>" type="text" maxlength="250" value="<?php echo esc_attr( $widget_title ); ?>"/>
		</p>
		<p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'count' ) ); ?>"><?php esc_html_e( 'Number of posts', 'relevant' ); ?>:</label>
			<input class="tiny-text" id="<?php echo esc_attr( $this->get_field_id( 'count' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'count' ) ); ?>" type="number" min="1" max="10000" value="<?php echo esc_attr( $count ); ?>"/>
		</p>
		<p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'min_count' ) ); ?>"><?php esc_html_e( 'Min posts number', 'relevant' ); ?>:</label>
			<input class="tiny-text" id="<?php echo esc_attr( $this->get_field_id( 'min_count' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'min_count' ) ); ?>" type="number" min="0" max="9999" value="<?php echo esc_attr( $min_count ); ?>"/>
			<br />
			<small><?php esc_html_e( 'Hide Popular Posts block if posts count is less than specified.', 'relevant' ); ?></small>
		</p>
		<p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'excerpt_length' ) ); ?>"><?php esc_html_e( 'Excerpt length', 'relevant' ); ?>:</label>
			<input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'excerpt_length' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'excerpt_length' ) ); ?>" type="number" min="1" max="10000" value="<?php echo esc_attr( $excerpt_length ); ?>"/>
		</p>
		<p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'excerpt_more' ) ); ?>"><?php esc_html_e( 'Read More Link Text', 'relevant' ); ?>:</label>
			<input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'excerpt_more' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'excerpt_more' ) ); ?>" type="text" maxlength="250" value="<?php echo esc_attr( $excerpt_more ); ?>"/>
		</p>
		<p>
			<?php esc_html_e( 'Show', 'relevant' ); ?>:<br />
			<label for="<?php echo esc_attr( $this->get_field_id( 'show_views' ) ); ?>">
				<input id="<?php echo esc_attr( $this->get_field_id( 'show_views' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'show_views' ) ); ?>" type="checkbox" value="1"<?php checked( 1, $show_views ); ?> />
				<?php esc_html_e( 'Views number', 'relevant' ); ?>
			</label><br />
			<label for="<?php echo esc_attr( $this->get_field_id( 'show_date' ) ); ?>">
				<input id="<?php echo esc_attr( $this->get_field_id( 'show_date' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'show_date' ) ); ?>" type="checkbox" value="1"<?php checked( 1, $show_date ); ?> />
				<?php esc_html_e( 'Post date', 'relevant' ); ?>
			</label><br />
			<label for="<?php echo esc_attr( $this->get_field_id( 'show_author' ) ); ?>">
				<input id="<?php echo esc_attr( $this->get_field_id( 'show_author' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'show_author' ) ); ?>" type="checkbox" value="1"<?php checked( 1, $show_author ); ?> />
				<?php esc_html_e( 'Author', 'relevant' ); ?>
			</label><br />
			<label for="<?php echo esc_attr( $this->get_field_id( 'show_image' ) ); ?>">
				<input id="<?php echo esc_attr( $this->get_field_id( 'show_image' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'show_image' ) ); ?>" type="checkbox" value="1"<?php checked( 1, $show_image ); ?> />
				<?php esc_html_e( 'Featured image', 'relevant' ); ?>
			</label><br />
			<label for="<?php echo esc_attr( $this->get_field_id( 'show_excerpt' ) ); ?>">
				<input id="<?php echo esc_attr( $this->get_field_id( 'show_excerpt' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'show_excerpt' ) ); ?>" type="checkbox" value="1"<?php checked( 1, $show_excerpt ); ?> />
				<?php esc_html_e( 'Excerpt', 'relevant' ); ?>
			</label><br />
			<label for="<?php echo esc_attr( $this->get_field_id( 'show_reading_time' ) ); ?>">
				<input id="<?php echo esc_attr( $this->get_field_id( 'show_reading_time' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'show_reading_time' ) ); ?>" type="checkbox" value="1"<?php checked( 1, $show_reading_time ); ?> />
				<?php esc_html_e( 'Reading time', 'relevant' ); ?>
			</label><br />
			<label for="<?php echo esc_attr( $this->get_field_id( 'show_comments' ) ); ?>">
				<input id="<?php echo esc_attr( $this->get_field_id( 'show_comments' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'show_comments' ) ); ?>" type="checkbox" value="1"<?php checked( 1, $show_comments ); ?> />
				<?php esc_html_e( 'Comments number', 'relevant' ); ?>
			</label>
		</p>
		<p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'no_preview_img' ) ); ?>"><?php esc_html_e( 'Featured Image Placeholder URL', 'relevant' ); ?>:</label>
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
		<p>
			<?php esc_html_e( 'Order posts by number of', 'relevant' ); ?>:<br />
			<label>
				<input name="<?php echo esc_attr( $this->get_field_name( 'order_by' ) ); ?>" type="radio" value="comment_count" <?php checked( 'comment_count', esc_attr( $order_by ) ); ?> /><?php esc_html_e( 'Comments', 'relevant' ); ?>
			</label><br />
			<label>
				<input name="<?php echo esc_attr( $this->get_field_name( 'order_by' ) ); ?>" type="radio" value="views_count" <?php checked( 'views_count', esc_attr( $order_by ) ); ?> /><?php esc_html_e( 'Views', 'relevant' ); ?>
			</label>
		</p>
		<p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'use_category' ) ); ?>">
				<input id="<?php echo esc_attr( $this->get_field_id( 'use_category' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'use_category' ) ); ?>" type="checkbox" value="1"<?php checked( 1, $use_category ); ?> /> <?php esc_html_e( 'Display posts from the current category only', 'relevant' ); ?>
			</label>
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
		$instance                   = array();
		$instance['widget_title']   = isset( $new_instance['widget_title'] ) ? esc_html( wp_unslash( $new_instance['widget_title'] ) ) : $rltdpstsplgn_options['popular_title'];
		$instance['count']          = ( ! empty( $new_instance['count'] ) ) ? intval( $new_instance['count'] ) : $rltdpstsplgn_options['popular_posts_count'];
		$instance['height']         = ( ! empty( $new_instance['height'] ) ) ? intval( $new_instance['height'] ) : $rltdpstsplgn_options['popular_image_height'];
		$instance['width']          = ( ! empty( $new_instance['width'] ) ) ? intval( $new_instance['width'] ) : $rltdpstsplgn_options['popular_image_width'];
		$instance['min_count']      = ( ! empty( $new_instance['min_count'] ) ) ? intval( $new_instance['min_count'] ) : $rltdpstsplgn_options['popular_min_posts_count'];
		$instance['excerpt_length'] = ( ! empty( $new_instance['excerpt_length'] ) ) ? intval( $new_instance['excerpt_length'] ) : $rltdpstsplgn_options['popular_excerpt_length'];
		$instance['excerpt_more']   = ( ! empty( $new_instance['excerpt_more'] ) ) ? esc_html( wp_unslash( $new_instance['excerpt_more'] ) ) : $rltdpstsplgn_options['popular_excerpt_more'];
		$instance['use_category']   = isset( $new_instance['use_category'] ) ? absint( $new_instance['use_category'] ) : 0;

		$show_options = array( 'views', 'date', 'author', 'image', 'excerpt', 'reading_time', 'comments' );
		foreach ( $show_options as $item ) {
			$instance[ "show_{$item}" ] = isset( $new_instance[ "show_{$item}" ] ) ? absint( $new_instance[ "show_{$item}" ] ) : 0;
		}

		if ( ! empty( $new_instance['no_preview_img'] ) && rltdpstsplgn_is_200( $new_instance['no_preview_img'] ) && getimagesize( $new_instance['no_preview_img'] ) ) {
			$instance['no_preview_img'] = $new_instance['no_preview_img'];
		} else {
			$instance['no_preview_img'] = $rltdpstsplgn_options['popular_no_preview_img'];
		}

		$instance['order_by']           = ( ! empty( $new_instance['order_by'] ) ) ? $new_instance['order_by'] : $rltdpstsplgn_options['popular_order_by'];
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
