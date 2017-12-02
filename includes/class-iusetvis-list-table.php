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
            'name'	        => __('Name', 'iusetvis'),
            'address'		=> __('Address', 'iusetvis'),
            'phone'         => __('Telephone', 'iusetvis'),
            'associated'	=> __('Associated', 'iusetvis'),
            'perfected'		=> __('Perfected', 'iusetvis'),
            'confirmed'		=> __('Confirmed', 'iusetvis'),
            'unsubscribe'   => __('Unsubscribe', 'iusetvis')
        );
        return $columns;
    }

    public function get_hidden_columns()
    {
        $hidden_columns = array(
            'id'    => 'ID'
        );
        return $hidden_columns;
    }

    public function get_sortable_columns()
    {
        return array('name' => array('name', false));
    }

    private function table_data()
    {
    	$course_id = 0;
    	if(!empty($_GET['course_id']))
        {
            $course_id = $_GET['course_id'];
        }
        $course_meta = get_post_custom( $course_id );
        $subscribed_users = !isset( $course_meta['subscribed_users'][0] ) ? array() : maybe_unserialize( $course_meta['subscribed_users'][0] );        

        $data = array();

        foreach ( $subscribed_users as $key => $value ) {
            $user_id = $value;

            $user_meta = get_user_meta( $user_id );

            $perfected_subscriptions = !isset( $user_meta['perfected_subscriptions'][0] ) ? array() : maybe_unserialize( $user_meta['perfected_subscriptions'][0] );
            $confirmed_attendances = !isset( $user_meta['confirmed_attendances'][0] ) ? array() : maybe_unserialize( $user_meta['confirmed_attendances'][0] );
            
            $user_first_name = !isset( $user_meta['first_name'][0] ) ? '' : $user_meta['first_name'][0];
            $user_last_name = !isset( $user_meta['last_name'][0] ) ? '' : $user_meta['last_name'][0];
            $user_name = $user_last_name . ' ' . $user_first_name;
            $user_address = !isset( $user_meta['address'][0] ) ? '' : $user_meta['address'][0];
            $user_phone = !isset( $user_meta['phone'][0] ) ? '' : $user_meta['phone'][0];
            $user_association_state = !isset( $user_meta['association_state'][0] ) ? false : ( $user_meta['association_state'][0] == 0 ? false : true );
            $user_sub_perfected = in_array( $course_id, $perfected_subscriptions );
            $user_att_confirmed = in_array( $course_id, $confirmed_attendances );

            $data[] = array(
                'id'            => $user_id,
                'name'          => $user_name,
                'address'       => $user_address,
                'phone'         => $user_phone,
                'associated'    => $user_association_state,
                'perfected'     => $user_sub_perfected,
                'confirmed'     => $user_att_confirmed
            );
        }
        
        return $data;
    }

    public function column_default( $item, $column_name )
    {
        switch( $column_name ) {
            case 'id':
            case 'name':
            case 'address':
            case 'phone':
                return $item[ $column_name ];
            case 'associated':
                return '<input type="checkbox" name="' . $column_name . '" value="' . $item[ $column_name ] . '" ' . ( ( $item[ $column_name ] == true ) ? 'checked' : '' ) . ' disabled="disabled">';
            case 'perfected':
            case 'confirmed':
            	return '<input class="' . $column_name . '_checkbox" data-user_id="' . $item[ 'id' ] . '" type="checkbox" name="' . $column_name . '" value="' . $item[ $column_name ] . '" ' . ( ( $item[ $column_name ] == true ) ? 'checked' : '' ) . '>';
            case 'unsubscribe':
                return '<input class="' . $column_name . '_button button button-primary button-large" data-user_id="' . $item[ 'id' ] . '" type="submit" name="' . $column_name . '" value="' . __('Unsubscribe', 'iusetvis') . '">';
            default:
                return print_r( $item, true ) ;
        }
    }

    private function sort_data( $a, $b )
    {
        $orderby = 'name';
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