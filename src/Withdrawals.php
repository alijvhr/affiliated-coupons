<?php

class Withdrawals extends WP_List_Table {
	function __construct() {
		parent::__construct( array(
			'singular' => 'aac_list_payment',
			'plural'   => 'aac_list_payments',
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
		global $aac_withdraw_status;
		$views = array();

		$current      = $_GET['filter'] ?? 'all';
		$views['all'] = $this->make_link( 'all', $current );
		foreach ( $aac_withdraw_status as $v ) {
			$views[ $v ] = $this->make_link( $v, $current );
		}

		return $views;
	}

	function make_link( $name, $current ) {
		$custom_url = add_query_arg( 'filter', $name, remove_query_arg( array( 's', 'paged', 'alert' ) ) );
		$class      = ( $current == $name ? ' class="current"' : '' );
		$name       = __( $name, 'affiliated-coupons' );

		return "<a href=\"$custom_url\" $class > $name <span class=\"count\"></span></a>";
	}

	function prepare_items() {
		global $wpdb, $aac_table_payment, $aac_withdraw_status;
		$users_tbl             = "{$wpdb->prefix}users";
		$columns               = $this->get_columns();
		$hidden                = array();
		$sortable              = $this->get_sortable_columns();
		$this->_column_headers = array( $columns, $hidden, $sortable );

		$screen = get_current_screen();

		$orderby   = isset( $_GET['orderby'] ) ? esc_sql( $_GET['orderby'] ) : 'id';
		$order     = isset( $_GET['order'] ) ? esc_sql( $_GET['order'] ) : 'DESC';
		$filter    = array_search( isset( $_GET['filter'] ) ? esc_sql( $_GET['filter'] ) : 'all', $aac_withdraw_status );
		$filter    = $filter !== false ? " WHERE p.status = $filter" : '';
		$per_page  = 20;
		$offset    = ( ( $this->get_pagenum() - 1 ) * $per_page );
		$query     = "SELECT u.id as uid, u.user_nicename as uname, p.* FROM $aac_table_payment p JOIN $users_tbl u ON u.id = p.affiliate_id $filter ORDER BY $orderby $order LIMIT $offset, $per_page";
		$count_all = $wpdb->get_results( "SELECT count(*) as num_rows FROM $aac_table_payment p JOIN $users_tbl u ON u.id = p.affiliate_id $filter ORDER BY $orderby $order" )['num_rows'] ?? 0;

		$this->set_pagination_args( [
			'total_items' => $count_all,
			'per_page'    => $per_page,
			'total_pages' => ceil( $count_all / $per_page )
		] );


		return $this->items = $wpdb->get_results( $query, ARRAY_A );
	}

	function get_columns() {
		return [
			'id'       => __( 'ID', 'affiliated-coupons' ),
			'amount'   => __( 'amount', 'affiliated-coupons' ),
			'uname'    => __( 'affiliate', 'affiliated-coupons' ),
			'status'   => __( 'status', 'affiliated-coupons' ),
			'req_date' => __( 'request date', 'affiliated-coupons' ),
		];
	}

	public function get_sortable_columns() {
		return [
			'id'       => [ 'id' ],
			'req_date' => [ 'req_date' ],
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
			case 'amount':
				return wc_price( $item[ $column_name ] );
			case 'req_date':
				return get_date_from_gmt( $item[ $column_name ] );
			case 'id':
			case 'uname':
			case 'order':
			default:
				return $item[ $column_name ];
		}
	}

}