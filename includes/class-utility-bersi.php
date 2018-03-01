<?php

/**
 * Utilità generali
 *
 * @package Utility IusEtVis
 * @author  Andrea Bersi
 */
class Ius_Et_Vis_Util {

  public function __construct() {

	}

  /**
   * esporta un csv
   * @param  [type] $header_row intestazione dei campi
   * @param  [type] $data_rows  data da scrivere
   * @param  [type] $filename   nome del file
   * @return [type]             [description]
   */
  public function export_csv( $header_row , $data_rows , $filename=null ){

    if(is_null($filename)) {
      $domain = $_SERVER['SERVER_NAME'];
      $filename = 'csv-' . $domain . '-' . time() . '.csv';
    }
    $fh = @fopen( 'php://output', 'w' );
    //fprintf( $fh, chr(0xEF) . chr(0xBB) . chr(0xBF) );
    fprintf( $fh );
    header( 'Cache-Control: must-revalidate, post-check=0, pre-check=0' );
    header( 'Content-Description: File Transfer' );
    header( 'Content-type: text/csv' );
    header( "Content-Disposition: attachment; filename={$filename}" );
    header( 'Expires: 0' );
    header( 'Pragma: public' );
    fputcsv( $fh, $header_row );
    foreach ( $data_rows as $data_row ) {
        fputcsv( $fh, $data_row );
    }
    fclose( $fh );

    ob_end_flush();

    die();
  }

  /**
   * [send_email_subscribed description]
   * @param  integer $course_id [description]
   * @return [type]             [description]
   */
  public function send_email_subscribed( $course_id = 0 ){
    //echo (($course_id));die();
    $options = get_option( $this->plugin_name . '_settings' );
    $text = ! isset( $options['iusetvis_email_course_ended'] ) ? __("We would like to inform you the conclusive documentation of the course mentioned in the subject is now available on iusetvis.it in the section Personal space",'ius') : $options['iusetvis_email_course_ended'];

    $course_meta = get_post_custom( $course_id );
    $subscribed_users = !isset( $course_meta['subscribed_users'][0] ) ? array() : maybe_unserialize( $course_meta['subscribed_users'][0] );

    //dati email
    $headers =  array();
    $to = "segreteria@iusetvis.it";
    $headers[] = 'From: Ius et Vis <info@iusetvis.it>';
    foreach ( $subscribed_users as $key => $value ) {
        $user_id = $value;
        $user_data = get_userdata( $user_id );
        $headers[] = "Bcc: {$user_data->user_email}";
        //wp_mail( $user_data->user_email, get_the_title($course_id), $text );
    }
    $esito = wp_mail( $to, get_the_title($course_id), $text, $headers );
    //echo $esito; die();
    if( $esito !== 1 ) {
      //errore invio
      echo __('There was a problem with sending emails', 'iusetvis');
      die();
    }
  }

}
