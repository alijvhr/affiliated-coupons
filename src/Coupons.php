<?php

class Coupons extends WP_List_Table {
	function __construct() {
		parent::__construct( array(
			'singular' => 'aac_list_coupon',
			'plural'   => 'aac_list_coupons',
			'ajax'     => false
		) );
	}

	function extra_tablenav( $which ) {
		if ( $which == "top" ) {
			//The code that goes before the table is here
		}
		if ( $which == "bottom" ) {
			//The code that goes after the table is there
		}
	}

	function get_views() {
		global $aac_coupon_status;
		$views = array();

		$current      = $_GET['filter'] ?? 'all';
		$views['all'] = $this->make_link( 'all', $current );
		foreach ( $aac_coupon_status as $v ) {
			$views[ $v ] = $this->make_link( $v, $current );
		}

		return $views;
	}

	function make_link( $name, $current ) {
		$custom_url = add_query_arg( 'filter', $name, remove_query_arg( [ 's', 'paged', 'alert' ] ) );
		$class      = ( $current == $name ? ' class="current"' : '' );
		$name       = __( $name, 'affiliated-coupons' );

		return "<a href=\"$custom_url\" $class > $name <span class=\"count\"></span></a>";
	}

	function prepare_items() {
		global $wpdb, $aac_coupon_status;
		$users_tbl             = "{$wpdb->prefix}users";
		$posts_tbl             = "{$wpdb->prefix}posts";
		$columns               = $this->get_columns();
		$hidden                = array();
		$sortable              = $this->get_sortable_columns();
		$this->_column_headers = array( $columns, $hidden, $sortable );

		$filter_to = ['p.expire < NOW()', 'p.'];

		$screen = get_current_screen();

		$orderby   = isset( $_GET['orderby'] ) ? esc_sql( $_GET['orderby'] ) : 'p.id';
		$order     = isset( $_GET['order'] ) ? esc_sql( $_GET['order'] ) : 'DESC';
		$filter    = array_search( isset( $_GET['filter'] ) ? esc_sql( $_GET['filter'] ) : 'all', $aac_coupon_status );
		$filter    = $filter !== false ? " AND p.status = $filter" : '';
		$per_page  = 20;
		$offset    = ( ( $this->get_pagenum() - 1 ) * $per_page );
		$query     = "SELECT u.id as uid, u.user_nicename as uname, p.id as pid, p.* FROM $posts_tbl p JOIN $users_tbl u ON u.id = p.post_author WHERE p.post_type = 'shop_coupon' $filter ORDER BY $orderby $order LIMIT $offset, $per_page";
		$count_all = $wpdb->get_results( "SELECT count(*) as num_rows FROM $posts_tbl p JOIN $users_tbl u ON u.id = p.post_author WHERE p.post_type = 'shop_coupon' $filter" )['num_rows'] ?? 0;
		$this->set_pagination_args( [
			'total_items' => $count_all,
			'per_page'    => $per_page,
			'total_pages' => ceil( $count_all / $per_page )
		] );

		$posts = $wpdb->get_results( $query, ARRAY_A );

		foreach ( $posts as &$post ) {
			$post['meta'] = get_post_meta( $post['pid'] );
		}

		return $this->items = $posts;
	}

	function get_columns() {
		return [
			'code'      => __( 'code', 'affiliated-coupons' ),
			'percent'   => __( 'percent', 'affiliated-coupons' ),
			'limit'     => __( 'limit', 'affiliated-coupons' ),
			'expire'    => __( 'expire', 'affiliated-coupons' ),
			'remaining' => __( 'remaining', 'affiliated-coupons' ),
			'uname'     => __( 'username', 'affiliated-coupons' ),
		];
	}

	public function get_sortable_columns() {
		return [
			'id'       => [ 'id' ],
			'expire' => [ 'expire' ],
			'percent' => [ 'percent' ],
			'uname'    => [ 'uname' ]
		];
	}

	function column_cb( $item ) {
		return sprintf(
			'<input type="checkbox" name="element[]" value="%s" />',
			$item['id']
		);
	}

	function column_default( $item, $column_name ) {
		switch ( $column_name ) {
			case 'code':
				return $item['post_title'];
			case 'percent':
				return $item['meta']['coupon_amount'][0];
			case 'limit':
				return $item['meta']['usage_limit'][0] ?: __( 'unlimited', 'affiliated-coupons' );
			case 'expire':
				return $item['meta']['date_expires'][0] ? wp_date( 'Y-m-d H:i:s', $item['meta']['date_expires'][0] ) : __( 'never', 'affiliated-coupons' );
			case 'remaining':
				return $item['meta']['usage_limit'][0] - $item['meta']['usage_count'][0];
			default:
				return $item[ $column_name ];
		}
	}

}