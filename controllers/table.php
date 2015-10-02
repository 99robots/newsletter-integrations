<?php

// Exit if accessed directly

if ( !defined( 'ABSPATH' ) ) exit;

// Check if class already exists

if ( !class_exists("NNR_Newsletter_Integrations_List_Table_v1") ):

/* ================================================================================
 *
 * Data Manger is a MVC addon to help you manager custom data within custom tables
 * in WordPress.
 *
 ================================================================================ */

/**
 * Include the WP_List_Table library
 *
 * @since 1.0.0
 *
 */
if ( !class_exists('WP_List_Table') ) {
    require_once( ABSPATH . 'wp-admin/includes/class-wp-list-table.php' );
}

/**
 * This class extends WP_List_Table and is used to create the table as
 *	seen in the admin panel.
 *
 * @since 1.0.0
 *
 * @extends	WP_List_Table
 */
class NNR_Newsletter_Integrations_List_Table_v1 extends WP_List_Table {

	/**
	 * The items to be displayed in the table
	 *
	 * (default value: array())
	 *
	 * @var array
	 * @access public
	 */
	public $items = array();

	/**
	 * table_name
	 *
	 * (default value: '')
	 *
	 * @var string
	 * @access public
	 */
	public $table_name = '';

	/**
	 * data_manager_table_name
	 *
	 * (default value: '')
	 *
	 * @var string
	 * @access public
	 */
	public $data_manager_table_name = '';

	/**
	 * Construtor
	 *
	 * @since 1.0.0
	 *
	 * @param	N/A
	 * @return	Instance
	 */
	function __construct( $table_name, $data_manager_table_name = '', $single = 'email', $plural = 'emails'  ) {

        global $status, $page;

        $this->table_name = $table_name;
        $this->data_manager_table_name = $data_manager_table_name;
        $this->include_scripts();

        //Set parent defaults

        parent::__construct( array(
            'singular'  => $single,     	//singular name of the listed records
            'plural'    => $plural,    	//plural name of the listed records
            'ajax'      => false        	//does this table support ajax?
        ) );
    }

    function include_scripts() {
	    // Styles

	    wp_enqueue_style('bootstrap-datetimepicker-css', 	plugins_url( 'css/bootstrap-datetimepicker.min.css', dirname(__FILE__)));

	    // Scripts

		wp_enqueue_script('bootstrap-moment-js', 			plugins_url( 'js/moment.js', dirname(__FILE__)), array('jquery'));
		wp_enqueue_script('bootstrap-datetimepicker-js', 	plugins_url( 'js/bootstrap-datetimepicker.min.js', dirname(__FILE__)), array('jquery', 'bootstrap-moment-js'));
		wp_enqueue_script('newsletter-table-js', 			plugins_url( 'js/table.js', dirname(__FILE__)), array('jquery', 'bootstrap-moment-js'));
    }

	/**
	 * Called if there are no optin fires
	 *
	 * @since 1.0.0
	 *
	 * @param	N/A
	 * @return	N/A
	 */
	function no_items() {
		_e( 'No emails found.' );
	}

	/**
	 * This method dictates the table's columns and titles.
	 *
	 * @since 1.0.0
	 *
	 * @param	N/A
	 * @return	array An associative array containing column information: 'slugs'=>'Visible Titles'
	 */
	function get_columns(){
		$columns = array(
			'date'        	 	 => __( 'Date' ),
			'email'              => __( 'Email' ),
			'first_name'         => __( 'First Name' ),
			'last_name'          => __( 'Last Name' ),
			'source'        	 => __( 'Source' ),
		);
		return $columns;
	}

	/**
	 * Called for any colunm without a realted function
	 *
	 * @since 1.0.0
	 *
	 * @param	array $item A singular item (one full row's worth of data)
	 * @param	array $column_name The name/slug of the column to be processed
	 * @return	string Text or HTML to be placed inside the column <td>
	 */
	function column_default( $item, $column_name ) {

		if ( $column_name == 'source' ) {

			if ( $this->data_manager_table_name != '' && class_exists('NNR_Data_Manager_v1') ) {
				$data_manager = new NNR_Data_Manager_v1($this->data_manager_table_name);
				return $data_manager->get_name_from_id($item['data_id']);
			}

			return $item['data_id'];
		}

		if ( $column_name == 'date' ) {
			return date('Y-m-d', strtotime($item['date']));
		}

		return $item[$column_name];
	}

	/**
	 * Prepares data for display.
	 *
	 * @since 1.0.0
	 *
	 * @param	N/A
	 * @return	N/A
	 */
	function prepare_items() {

		$newsletter_emails = new NNR_Newsletter_Integrations_Submission_v1( $this->table_name );

		global $wpdb;

        $per_page = 50;

        $columns = $this->get_columns();
        $hidden = array();
        $sortable = $this->get_sortable_columns();

        $this->_column_headers = array($columns, $hidden, $sortable);

        $current_page = $this->get_pagenum();

        // Get Start and End Dates

		$start_date = date('Y-m-d', mktime(0, 0, 0, date("m")-1, date("d"), date("Y")));
		$end_date = date('Y-m-d', strtotime(current_time('mysql')));

		if ( isset($_GET['start_date']) ) {
			$post_start_date = urldecode($_GET['start_date']);
		}

		if ( isset($_GET['end_date']) ) {
			$post_end_date = urldecode($_GET['end_date']);
		}

		if (isset($post_start_date) && $post_start_date != '') {
			$start_date = date('Y-m-d', strtotime($post_start_date));
		}

		if (isset($post_end_date) && $post_end_date != '') {
			$end_date = date('Y-m-d', strtotime($post_end_date));
		}

        $this->items = $newsletter_emails->get_emails($start_date, $end_date);

        $total_items = count($this->items);

        /**
         * The WP_List_Table class does not handle pagination for us, so we need
         * to ensure that the data is trimmed to only the current page. We can use
         * array_slice() to
         */
        $data = array_slice($this->items,(($current_page-1)*$per_page),$per_page);

        $this->set_pagination_args( array(
            'total_items' => $total_items,                  //WE have to calculate the total number of items
            'per_page'    => $per_page,                     //WE have to determine how many items to show on a page
            'total_pages' => ceil($total_items/$per_page)   //WE have to calculate the total number of pages
        ) );
	}

	/**
	 * Get a list of CSS classes for the <table> tag
	 *
	 * @since 3.1.0
	 * @access protected
	 *
	 * @return array
	 */
	public function get_table_classes() {
	    return array('table table-striped table-responsive');
	}

	/**
	 * Display the table
	 *
	 * @since 3.1.0
	 * @access public
	 */
	public function display() {

		$singular = $this->_args['singular'];

		//$this->display_tablenav( 'top' );

		?>
		<table class="<?php echo implode( ' ', $this->get_table_classes() ); ?>">
			<thead>
			<tr>
				<?php $this->print_column_headers(); ?>
			</tr>
			</thead>

			<tbody id="the-list"<?php
				if ( $singular ) {
					echo " data-wp-lists='list:$singular'";
				} ?>>
				<?php $this->display_rows_or_placeholder(); ?>
			</tbody>
		</table>
		<?php
		$this->display_tablenav( 'bottom' );
	}

	/**
	 * Generate row actions div
	 *
	 * @since 3.1.0
	 * @access protected
	 *
	 * @param array $actions The list of actions
	 * @param bool $always_visible Whether the actions should be always visible
	 * @return string
	 */
	protected function row_actions( $actions, $always_visible = false ) {

		$action_count = count( $actions );
		$i = 0;

		if ( !$action_count )
			return '';

		$out = '<div class="' . ( $always_visible ? 'row-actions visible' : 'row-actions' ) . '">';
		foreach ( $actions as $action => $link ) {
			++$i;
			( $i == $action_count ) ? $sep = '' : $sep = ' | ';
			$out .= "<span class='$action'>$link$sep</span>";
		}
		$out .= '</div>';

		return $out;
	}

	/**
	 * Generates and display row actions links for the list table.
	 *
	 * @since 4.3.0
	 * @access protected
	 *
	 * @param object $item        The item being acted upon.
	 * @param string $column_name Current column name.
	 * @param string $primary     Primary column name.
	 * @return string The row actions output. In this case, an empty string.
	 */
	protected function handle_row_actions( $item, $column_name, $primary ) {
		return '';
 	}
}

endif;