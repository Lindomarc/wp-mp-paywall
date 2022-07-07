<?php
if ( ! class_exists( '_pdi_paywall_Wp_Plugins_V4' ) ) {
	require_once 'wp-last-login-class.php';
}

/**
 * Class _pdi_paywall_Wp_Last_Login.
 */
class _pdi_paywall_Wp_Last_Login extends _pdi_paywall_Wp_Plugins_V4 {

	public function __construct() {
		parent::__construct( array(
			'textdomain'     => 'wp-last-login',
			'plugin_path'    => __FILE__,
			'donate_link_id' => 'K32M878XHREQC',
		) );

		load_plugin_textdomain( 'wp-last-login', false, 'wp-last-login/lang' );

		$this->hook( 'wp_login' );
		$this->hook( 'user_register' );

		/**
		 * Programmers:
		 * To limit this information to certain user roles, add a filter to
		 * 'pdi_paywall_current_user_can' and check for user permissions, returning
		 * true or false!
		 *
		 * Example:
		 *
		 * function prefix_pdi_paywall_visibility( $bool ) {
		 *     return current_user_can( 'manage_options' ); // Only for Admins
		 * }
		 * add_filter( 'pdi_paywall_current_user_can', 'prefix_pdi_paywall_visibility' );
		 */
		if ( is_admin() && apply_filters( 'pdi_paywall_current_user_can', true ) ) {

			$this->hook( 'manage_site-users-network_columns', 'add_column', 1 );
			$this->hook( 'manage_users_columns', 'add_column', 1 );
			$this->hook( 'wpmu_users_columns', 'add_column', 1 );
			$this->hook( 'admin_print_styles-users.php', 'column_style' );
			$this->hook( 'admin_print_styles-site-users.php', 'column_style' );
			$this->hook( 'manage_users_custom_column' );
			$this->hook( 'manage_users_sortable_columns', 'add_sortable' );
			$this->hook( 'manage_users-network_sortable_columns', 'add_sortable' );
			$this->hook( 'pre_get_users' );
		}
	}

	/**
	 * Update the login timestamp.
	 * @param  string $user_login The user's login name.
	 *
	 * @return void
	 */
	public function wp_login( $user_login ) {
		$user = get_user_by( 'login', $user_login );
		update_user_meta( $user->ID, $this->textdomain, time() );
	}

	/**
	 * Set default data for new users.
	 *
	 * @param int $user_id The user ID.
	 */
	public function user_register( $user_id ) {
		update_user_meta( $user_id, $this->textdomain, 0 );
	}

	/**
	 * Adds the last login column to the network admin user list.
	 *
	 *
	 * @param  array $cols The default columns.
	 *
	 * @return array
	 */
	public function add_column( $cols ) {
		$cols[ $this->textdomain ] = 'Ultimo acesso';
		return $cols;
	}

	/**
	 * Adds the last login column to the network admin user list.
	 * @access public
	 *
	 * @param  string $value       Value of the custom column.
	 * @param  string $column_name The name of the column.
	 * @param  int    $user_id     The user's id.
	 *
	 * @return string
	 */
	public function manage_users_custom_column( $value, $column_name, $user_id ) {
		if ( $this->textdomain === $column_name ) {
			$value      = 'Nunca';
			$last_login = (int) get_user_meta( $user_id, $this->textdomain, true );

            if ( $last_login ) {
                $format = apply_filters( 'pdi_paywall_date_format', get_option( 'date_format' ) );
                $value  = get_date_from_gmt( date( 'Y-m-d H:i:s', $last_login ), $format );
            }
		}

		return $value;
	}

	/**
	 * Register the column as sortable.
	 *
	 * @access public
	 *
	 * @param  array $columns User table columns.
	 *
	 * @return array
	 */
	public function add_sortable( $columns ) {
		$columns[ $this->textdomain ] = $this->textdomain;

		return $columns;
	}

	/**
	 * Handle ordering by last login.
	 * @param  WP_User_Query $user_query Request arguments.
	 *
	 * @return WP_User_Query
	 */
	public function pre_get_users( $user_query ) {
		if ( isset( $user_query->query_vars['orderby'] ) && $this->textdomain === $user_query->query_vars['orderby'] ) {
			$user_query->query_vars = array_merge( $user_query->query_vars, array(
				// phpcs:ignore WordPress.VIP.SlowDBQuery.slow_db_query_meta_key
				'meta_key' => $this->textdomain,
				'orderby'  => 'meta_value_num',
			) );
		}

		return $user_query;
	}

	/**
	 * Defines the width of the column
	 * @return void
	 */
	public function column_style() {
		?>
		<style type="text/css">
			.column-wp-last-login { width: 12%; }
		</style>
		<?php
	}

} // End of class _pdi_paywall_Wp_Last_Login.


new _pdi_paywall_Wp_Last_Login();
