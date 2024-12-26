<?php
/**
 * Recommended WP_List_Table
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'WP_List_Table' ) ) {
	require_once( ABSPATH . 'wp-admin/includes/class-wp-list-table.php' );
}

/**
 * Class Rltdpstplgn_Recommended for display WP_List_Table for Recommended
 */
class Rltdpstplgn_Recommended extends WP_List_Table {

	/**
	 * Columns for table
	 */
	public function get_columns() {
		$columns = array(
			'post_title'        => __( 'Title', 'relevant' ),
			'recommended_count' => __( 'Count / New count', 'relevant' ),
		);
		return $columns;
	}

	/**
	 * Display default columns info
	 *
	 * @param array  $item        Item info.
	 * @param string $column_name Column slug.
	 * @return string Column info.
	 */
	public function column_default( $item, $column_name ) {
		switch ( $column_name ) {
			case 'recommended_count':
				return '<span class="recommended-count">' . $item[ $column_name ] . ( isset( $item['recommended_count_new'] ) ? '<span class="recommended-count-new" title="New ' . $item['recommended_count_new'] . '">' . $item['recommended_count_new'] . '</span>' : '' ) . '</span>';
				break;
			case 'post_title':
			default:
				return $item[ $column_name ];
			break;
		}
	}

	/**
	 * Get info from DB for items
	 */
	public function prepare_items() {
		global $wpdb;
		$columns  = $this->get_columns();
		$hidden   = array();
		$sortable = array();

		$this->_column_headers = array( $columns, $hidden, $sortable );

		$per_page      = $this->get_items_per_page( 'rltdpstsplgn_recommended_per_page', 10 );
		$current_page  = $this->get_pagenum();
		$this->items   = array();
		$items_display = array();

		$query_count = 'SELECT `' . $wpdb->postmeta . '`.`post_id`, `' . $wpdb->postmeta . '`.`meta_value`, mt1.`meta_value` AS meta_value_new
			FROM `' . $wpdb->postmeta . '`
			LEFT JOIN `' . $wpdb->postmeta . '` AS mt1
			ON ( `' . $wpdb->postmeta . '`.`post_id` = mt1.`post_id`
				AND mt1.`meta_key` = "rltdpstsplgn_recommend_count_new" )
			WHERE 
				( `' . $wpdb->postmeta . '`.`meta_key` = "rltdpstsplgn_recommend_count" AND mt1.`post_id` IS NULL )
				OR ( `' . $wpdb->postmeta . '`.`meta_key` = "rltdpstsplgn_recommend_count" AND mt1.`post_id` IS NOT NULL )
			GROUP BY `' . $wpdb->postmeta . '`.`post_id`
			ORDER BY CAST(mt1.`meta_value` AS SIGNED) DESC, CAST(`' . $wpdb->postmeta . '`.`meta_value` AS SIGNED) DESC';

		$recommend_count_posts = $wpdb->get_results( $query_count, ARRAY_A );

		$total_items = count( $recommend_count_posts );

		$recommend_count_posts = array_slice( $recommend_count_posts, ( ( $current_page - 1 ) * $per_page ), $per_page );

		foreach ( $recommend_count_posts as $row ) {
			$this->items[] = array(
				'post_title'            => '<a href="' . esc_url( get_permalink( $row['post_id'] ) ) . '">' . get_the_title( $row['post_id'] ) . '</a>',
				'recommended_count'     => $row['meta_value'],
				'recommended_count_new' => $row['meta_value_new'],
			);
			$items_display[] = $row['post_id'];
		}

		if ( ! empty( $items_display ) ) {
			$query = 'DELETE FROM `' . $wpdb->postmeta . '` WHERE `meta_key` = "rltdpstsplgn_recommend_count_new" AND `post_id` IN( ' . implode( ',', $items_display ) . ' )';
			$wpdb->query( $query );
		}

		$this->set_pagination_args(
			array(
				'total_items' => $total_items,
				'per_page'    => $per_page,
				'total_pages' => ceil( $total_items / $per_page ),
			)
		);
	}
}
