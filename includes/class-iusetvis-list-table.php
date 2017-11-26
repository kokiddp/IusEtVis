<?php

/**
 * Register the custom list tables
 *
 * @link       https://github.com/kokiddp/IusEtVis
 * @since      1.0.0
 *
 * @package    Iusetvis
 * @subpackage Iusetvis/includes
 */

if( ! class_exists( 'WP_List_Table' ) ) {
    require_once( ABSPATH . 'wp-admin/includes/class-wp-list-table.php' );
}

/**
 * The table class 
 */
class Subscribed_Users_List_Table extends WP_List_Table
{

	public function prepare_items()
    {
        $columns = $this->get_columns();
        $hidden = $this->get_hidden_columns();
        $sortable = $this->get_sortable_columns();
        $data = $this->table_data();
        usort( $data, array( &$this, 'sort_data' ) );
        $perPage = 10;
        $currentPage = $this->get_pagenum();
        $totalItems = count( $data );
        $this->set_pagination_args( array(
            'total_items' => $totalItems,
            'per_page'    => $perPage
        ) );
        $data = array_slice( $data, ( ( $currentPage - 1 ) * $perPage ), $perPage);
        $this->_column_headers = array( $columns, $hidden, $sortable );
        $this->items = $data;
    }

    public function get_columns()
    {
        $columns = array(
            'title'			=> 'Title',
            'name'			=> 'Name',
            'surname'		=> 'Surname',
            'associated'	=> 'Associate',
            'perfected'		=> 'Perfected',
            'confirmed'		=> 'Confirmed'
        );
        return $columns;
    }

    public function get_hidden_columns()
    {
        $hidden_columns = array(
            'id'          => 'ID'
        );
        return $hidden_columns;
    }

    public function get_sortable_columns()
    {
        return array('surname' => array('surname', false));
    }

    private function table_data()
    {
    	$course_id = 0;
    	if(!empty($_GET['course_id']))
        {
            $course_id = $_GET['course_id'];
        }

    	//stub
        $data = array();
        $data[] = array(
            'id'			=> 1,
            'title'			=> 'Avv.',
            'name'			=> 'Mario',
            'surname'		=> 'Rossi',
            'associated'	=> true,
            'perfected'		=> false,
            'confirmed'		=> false
        );
        return $data;
    }

    public function column_default( $item, $column_name )
    {
        switch( $column_name ) {
            case 'id':
            case 'title':
            case 'name':
            case 'surname':
                return $item[ $column_name ];
            case 'associated':
            case 'perfected':
            case 'confirmed':
            	return '<input type="checkbox" name="' . $column_name . '" value="' . $item[ $column_name ] . '" ' . ( ( $item[ $column_name ] == true ) ? 'checked' : '' ) . '>';
            default:
                return print_r( $item, true ) ;
        }
    }

    private function sort_data( $a, $b )
    {
        $orderby = 'surname';
        $order = 'asc';

        if(!empty($_GET['orderby']))
        {
            $orderby = $_GET['orderby'];
        }

        if(!empty($_GET['order']))
        {
            $order = $_GET['order'];
        }
        $result = strcmp( $a[$orderby], $b[$orderby] );

        if( $order === 'asc' )
        {
            return $result;
        }

        return -$result;
    }

}