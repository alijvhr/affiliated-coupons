<?php

class Affiliates extends WP_List_Table {
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

	function prepare_items() {
		global $wpdb, $aac_withdraw_status;
		$users_tbl             = "{$wpdb->prefix}users";
		$umeta_tbl             = "{$wpdb->prefix}usermeta";
		$columns               = $this->get_columns();
		$hidden                = array();
		$sortable              = $this->get_sortable_columns();
		$this->_column_headers = array( $columns, $hidden, $sortable );

		$screen = get_current_screen();

		$orderby   = isset( $_GET['orderby'] ) ? esc_sql( $_GET['orderby'] ) : 'id';
		$order     = isset( $_GET['order'] ) ? esc_sql( $_GET['order'] ) : 'DESC';
		$filter    = array_search( isset( $_GET['filter'] ) ? esc_sql( $_GET['filter'] ) : 'all', $aac_withdraw_status );
		$filter    = $filter !== false ? " AND p.status = $filter" : '';
		$per_page  = 20;
		$offset    = ( ( $this->get_pagenum() - 1 ) * $per_page );
		$query     = "SELECT u.id as uid, u.user_nicename as uname, m2.meta_value as balance, m.meta_value as percent FROM $users_tbl u JOIN $umeta_tbl m ON u.id = m.user_id LEFT JOIN $umeta_tbl m2 ON u.id = m2.user_id AND m2.meta_key = 'aac_total_profit' WHERE m.meta_key = 'aac_affiliate_percentage' AND m.meta_value > 0 $filter ORDER BY $orderby $order LIMIT $offset, $per_page";
		$count_all = $wpdb->get_results( "SELECT count(*) as num_rows FROM $users_tbl u JOIN $umeta_tbl m ON u.id = m.user_id WHERE m.meta_key = 'aac_affiliate_percentage' AND m.meta_value > 0 $filter" )['num_rows'] ?? 0;
		$this->set_pagination_args( [
			'total_items' => $count_all,
			'per_page'    => $per_page,
			'total_pages' => ceil( $count_all / $per_page )
		] );


		return $this->items = $wpdb->get_results( $query, ARRAY_A );
	}

	function get_columns() {
		return [
			'uid'      => __( 'ID', 'affiliated-coupons' ),
			'uname'   => __( 'username', 'affiliated-coupons' ),
			'percent' => __( 'percent', 'affiliated-coupons' ),
			'balance' => __( 'balance', 'affiliated-coupons' )
		];
	}

	public function get_sortable_columns() {
		return [
			'uid'      => [ 'uid' ],
			'percent' => [ 'percent' ],
			'balance' => [ 'balance' ],
			'uname'   => [ 'uname' ]
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
			case 'percent':
			case 'balance':
			case 'id':
			case 'uname':
			default:
				return $item[ $column_name ];
		}
	}

}